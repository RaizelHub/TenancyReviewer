<?php

namespace App\Http\Controllers;

use App\Models\TenantApplication;
use App\Notifications\TenantApplicationApproved;
use App\Notifications\TenantApplicationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription application form.
     */
    public function showApplicationForm()
    {
        return view('welcome');
    }

    /**
     * Process a new subscription application.
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'domain_name' => 'required|string|max:255|unique:tenant_applications,domain_name|regex:/^[a-z0-9-]+$/',
            'subscription_plan' => 'required|string|in:Basic,Premium,Pro',
            'contact' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the application
            TenantApplication::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'domain_name' => $request->domain_name,
                'subscription_plan' => $request->subscription_plan,
                'contact' => $request->contact,
                'status' => 'pending',
            ]);

            $successMessage = 'Subscription successful! Please wait for the email when your application is ready.';

            // Handle AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMessage]);
            }

            // Return to welcome page with success message for regular requests
            return redirect()->route('welcome')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            \Log::error('Failed to create tenant application: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'There was a problem submitting your application. Please try again.'], 500);
            }

            return redirect()->back()->with('error', 'There was a problem submitting your application. Please try again.');
        }
    }

    /**
     * List all tenant applications.
     */
    public function listApplications()
    {
        $applications = TenantApplication::orderBy('created_at', 'desc')->paginate(10);
        return view('applications.index', compact('applications'));
    }

    /**
     * Approve a tenant application.
     */
    public function approve(Request $request, TenantApplication $application)
    {
        // First, check if tenant_0 database exists to prevent issues
        try {
            $tenant0Exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", ['tenant_0']);
            if ($tenant0Exists) {
                Log::warning('tenant_0 database already exists - this could cause issues with tenant creation');

                // If tenant_0 database exists, we need to be extra careful
                // Let's check if there's a tenant with ID '0' in the database
                $zeroTenant = \App\Models\Tenant::find('0');
                if ($zeroTenant) {
                    Log::warning('Found tenant with ID 0, this is problematic', ['tenant' => $zeroTenant]);
                    // We could delete this tenant, but that might cause data loss
                    // Instead, let's just log it and be extra careful with our ID generation
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error checking for tenant_0 database: ' . $e->getMessage());
        }

        // Update application status
        $application->update([
            'status' => 'approved',
            'notes' => $request->notes,
            'reviewed_at' => now(),
        ]);

        // Generate a password
        $password = '12345678';

        // Validate the domain name
        if (empty($application->domain_name)) {
            Log::warning('Empty domain name', ['application_id' => $application->id]);
            return redirect()->route('applications.index')
                ->with('error', "Failed to create tenant: Domain name cannot be empty.");
        }

        // Ensure domain name only contains valid characters
        if (!preg_match('/^[a-z0-9-]+$/i', $application->domain_name)) {
            Log::warning('Invalid domain name format', ['domain_name' => $application->domain_name]);
            return redirect()->route('applications.index')
                ->with('error', "Failed to create tenant: Domain name can only contain letters, numbers, and hyphens.");
        }

        // Ensure domain name is not just a number (to avoid tenant_0 issue)
        if (is_numeric($application->domain_name)) {
            Log::warning('Numeric domain name', ['domain_name' => $application->domain_name]);
            return redirect()->route('applications.index')
                ->with('error', "Failed to create tenant: Domain name cannot be just a number. Please include letters.");
        }

        // Create the tenant domain
        $domain = strtolower($application->domain_name) . '.' . config('app.domain');

        // Remove port if present
        $domain = preg_replace('/:\d+$/', '', $domain);

        Log::info('Domain name validated and formatted', [
            'original_domain_name' => $application->domain_name,
            'formatted_domain' => $domain
        ]);

        // Check if domain already exists
        if (\Stancl\Tenancy\Database\Models\Domain::where('domain', $domain)->exists()) {
            Log::warning('Domain already exists', ['domain' => $domain]);
            return redirect()->route('applications.index')
                ->with('error', "Failed to create tenant: The domain {$domain} is already in use.");
        }

        // Generate a unique tenant ID based on the domain name
        $domainSlug = Str::slug($application->domain_name);
        $timestamp = time();
        $randomString = Str::random(8);
        $tenantId = "{$domainSlug}_{$timestamp}_{$randomString}";

        // Ensure the tenant ID is valid
        if (empty($tenantId) || $tenantId === '0' || is_numeric($tenantId)) {
            $tenantId = "tenant_{$timestamp}_" . Str::uuid()->toString();
        }

        Log::info('Generated tenant ID', [
            'tenant_id' => $tenantId,
            'expected_database_name' => 'tenant_' . $tenantId
        ]);

        // Create the tenant with the generated ID
            $tenant = \App\Models\Tenant::create([
            'id' => $tenantId,
                'name' => $application->company_name,
                'email' => $application->email,
                'password' => $password,
                'active' => true,
                'data' => [
                'company_name' => $application->company_name,
                    'full_name' => $application->full_name,
                    'contact' => $application->contact,
                    'subscription_plan' => $application->subscription_plan,
                    'domain_name' => $application->domain_name,
                    'application_id' => $application->id,
                ],
            ]);

        // Create the domain for the tenant
        $domainCreated = false;
        $domain = '';

            try {
                // Format the domain properly
                $domainName = strtolower($application->domain_name);
            $domain = $domainName . '.localhost';

                Log::info('Attempting to create tenant domain', [
                    'tenant_id' => $tenant->id,
                    'domain' => $domain
                ]);

                // Check if the domain already exists
                $existingDomain = \Stancl\Tenancy\Database\Models\Domain::where('domain', $domain)->first();

                if ($existingDomain) {
                    // If domain exists but belongs to another tenant, create a unique one
                    if ($existingDomain->tenant_id !== $tenant->id) {
                        $uniqueDomain = $domainName . '-' . Str::random(4) . '.' . config('app.domain');
                        Log::info('Domain already exists, creating unique domain', [
                            'original_domain' => $domain,
                            'new_domain' => $uniqueDomain
                        ]);

                        $tenantDomain = $tenant->domains()->create(['domain' => $uniqueDomain]);
                        $domain = $uniqueDomain;
                    } else {
                        // Domain already belongs to this tenant
                        $tenantDomain = $existingDomain;
                        Log::info('Domain already belongs to this tenant', [
                            'domain' => $domain
                        ]);
                    }
                } else {
                    // Create the domain
                    $tenantDomain = $tenant->domains()->create(['domain' => $domain]);
                    Log::info('Domain created successfully', [
                        'domain' => $domain
                    ]);
                }

                $domainCreated = true;

            } catch (\Exception $e) {
                // If domain creation fails, try with a fallback domain
                Log::error('Failed to create domain: ' . $e->getMessage(), [
                    'tenant_id' => $tenant->id,
                    'exception' => $e
                ]);

                try {
                    // Create a fallback domain using the tenant ID
                    $fallbackDomain = 'tenant-' . $tenant->id . '.' . config('app.domain');
                    Log::info('Creating fallback domain', ['domain' => $fallbackDomain]);

                    $tenantDomain = $tenant->domains()->create(['domain' => $fallbackDomain]);
                    $domain = $fallbackDomain;
                    $domainCreated = true;

                    Log::info('Fallback domain created successfully', [
                        'domain' => $domain
                    ]);
                } catch (\Exception $fallbackException) {
                    Log::critical('Failed to create fallback domain: ' . $fallbackException->getMessage());
                    $domainCreated = false;
                }
            }

            // Link the tenant to the application
            $application->tenant_id = $tenant->id;
            $application->save();

            Log::info('Tenant application updated with tenant ID', [
                'application_id' => $application->id,
                'tenant_id' => $tenant->id
            ]);

            // Manually run migrations for the tenant using the --tenant option
            try {
                Log::info('Running migrations for tenant', [
                    'tenant_id' => $tenant->id,
                    'database_name' => 'tenant_' . $tenant->id
                ]);

                // Double-check that the tenant ID is valid before running migrations
                if ($tenant->id === '0' || empty($tenant->id)) {
                    Log::error('Cannot run migrations for tenant with invalid ID', ['tenant_id' => $tenant->id]);
                    throw new \Exception('Invalid tenant ID for migrations: ' . $tenant->id);
                }

                // Use the --tenant option for Stancl Tenancy v1
                \Illuminate\Support\Facades\Artisan::call('tenants:migrate', [
                    '--tenant' => $tenant->id,
                    '--force' => true,
                ]);

                Log::info('Tenant migrations completed: ' . \Illuminate\Support\Facades\Artisan::output());
            } catch (\Exception $e) {
                Log::error('Error running tenant migrations: ' . $e->getMessage(), [
                    'tenant_id' => $tenant->id,
                    'database_name' => 'tenant_' . $tenant->id,
                    'exception' => $e
                ]);
                // Continue with the process even if migrations fail
            }

            // Run the seed job to create the initial tenant user
            try {
                (new \App\Jobs\SeedTenantJob($tenant))->handle();
            } catch (\Exception $e) {
                Log::error('Error seeding tenant database: ' . $e->getMessage(), [
                    'tenant_id' => $tenant->id,
                    'exception' => $e
                ]);
                // Continue with the process even if seeding fails
            }

            // Send approval email with login credentials
            try {
                // Direct mail sending instead of notification
                Mail::send([], [], function ($message) use ($application, $password, $domain) {
                    $message->to($application->email, $application->full_name)
                        ->subject('Your Tenant Application Has Been Approved')
                        ->html(view('emails.tenant-approved', [
                            'application' => $application,
                            'password' => $password,
                            'domain' => $domain
                        ])->render());
                });
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                Log::error('Failed to send tenant approval email: ' . $e->getMessage());
            }

            // Create a success message that includes the domain information
            $successMessage = 'Tenant application approved and tenant created successfully.';

            // Add domain information if a domain was created
            if ($domainCreated && !empty($domain)) {
                $successMessage .= ' Domain: ' . $domain;
            }

            return redirect()->route('applications.index')
                ->with('success', $successMessage);
    }

    /**
     * Reject a tenant application.
     */
    public function reject(Request $request, TenantApplication $application)
    {
        // Update application status
        $application->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        // Send rejection email
        try {
            Notification::route('mail', $application->email)
                ->notify(new TenantApplicationRejected($application));
        } catch (\Exception $e) {
            // Log the error but don't stop the process
            Log::error('Failed to send tenant rejection email: ' . $e->getMessage());
        }

        return redirect()->route('applications.index')
            ->with('success', 'Tenant application rejected successfully.');
    }
}
