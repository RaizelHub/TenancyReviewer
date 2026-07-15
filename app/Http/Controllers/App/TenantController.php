<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->get();
        return view('app.tenants.index', ['tenants' => $tenants]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //validation
       $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'domain_name' => 'required|string|max:255|unique:domains,domain',
       ]);

       // Create the tenant with a random, unique ID
       // Generate a unique ID that includes the domain name (for readability) and random elements
       $domainSlug = Str::slug($validatedData['domain_name']);
       $tenantId = $domainSlug . '_' . time() . '_' . Str::random(8);

       // Check if the database already exists
       $dbName = 'tenant_' . $tenantId;
       $dbExists = \DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

       // If the database exists, generate a new ID
       if (!empty($dbExists)) {
           $tenantId = $domainSlug . '_' . time() . '_' . Str::uuid()->toString();
           $dbName = 'tenant_' . $tenantId;

           // Check again to be absolutely sure
           $dbExists = \DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);
           if (!empty($dbExists)) {
               // If it still exists, generate a completely random ID
               $tenantId = 'tenant_' . time() . '_' . Str::random(15);
           }
       }

       // Final check to prevent tenant_0 database
       if ($tenantId === '0' || empty($tenantId) || is_numeric($tenantId) || $dbName === 'tenant_0') {
           $tenantId = 'tenant_' . time() . '_' . Str::uuid()->toString();
       }

       $tenant = Tenant::create([
           'id' => $tenantId,
           'name' => $validatedData['name'],
           'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
           'active' => true,
           'data' => [
                'domain_name' => $validatedData['domain_name'],
           ],
       ]);

       // Create the domain for the tenant (without port number)
       $domain = $validatedData['domain_name'] . '.' . config('app.domain');
       // Remove port if present
       $domain = preg_replace('/:\d+$/', '', $domain);

       $tenant->domains()->create([
        'domain' => $domain
       ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $stats = [
            'users' => 0,
            'subjects' => 0,
            'students' => 0,
            'quizzes' => 0,
            'activities' => 0,
        ];

        if ($tenant->active) {
            try {
                $stats = $tenant->run(function () {
                    return [
                        'users' => \App\Models\User::count(),
                        'subjects' => \Illuminate\Support\Facades\DB::table('subjects')->count(),
                        'students' => \Illuminate\Support\Facades\DB::table('students')->count(),
                        'quizzes' => \Illuminate\Support\Facades\DB::table('quizzes')->count(),
                        'activities' => \Illuminate\Support\Facades\DB::table('activities')->count(),
                    ];
                });
            } catch (\Exception $e) {
                \Log::warning("Could not gather tenant statistics for {$tenant->id}: " . $e->getMessage());
            }
        }

        return view('app.tenants.show', [
            'tenant' => $tenant,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('app.tenants.edit', ['tenant' => $tenant]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'active' => 'boolean',
        ]);

        $tenant->update($validatedData);

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenantName = $tenant->name;
        $tenant->delete();
        
        // Log action
        \App\Models\ActivityLog::log('Tenant Deleted', "Super Admin deleted tenant {$tenantName} and all associated data.");

        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Disable a tenant.
     */
    public function disable(Tenant $tenant)
    {
        $tenant->update(['active' => false]);
        
        // Log action
        \App\Models\ActivityLog::log('Tenant Disabled', "Super Admin disabled workspace access for tenant {$tenant->name}.");

        return redirect()->route('tenants.index')->with('success', 'Tenant disabled successfully.');
    }

    /**
     * Enable a tenant.
     */
    public function enable(Tenant $tenant)
    {
        $tenant->update(['active' => true]);
        
        // Log action
        \App\Models\ActivityLog::log('Tenant Enabled', "Super Admin enabled workspace access for tenant {$tenant->name}.");

        return redirect()->route('tenants.index')->with('success', 'Tenant enabled successfully.');
    }

    /**
     * Trigger database migrations for a specific tenant database.
     */
    public function runMigrations(Tenant $tenant)
    {
        // Log action
        \App\Models\ActivityLog::log('Database Migrations Executed', "Super Admin executed migrations for tenant {$tenant->name}.");

        try {
            \Artisan::call('tenants:migrate', [
                '--tenant' => $tenant->id,
                '--force' => true
            ]);
            $output = \Artisan::output();
            return redirect()->back()->with('success', 'Database migrations completed successfully! Output: ' . trim($output));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed running migrations: ' . $e->getMessage());
        }
    }

    /**
     * Export and download a SQL schema/data backup of the tenant's database.
     */
    public function downloadBackup(Tenant $tenant)
    {
        // Log action
        \App\Models\ActivityLog::log('Database Backup Exported', "Super Admin exported SQL backup for tenant {$tenant->name}.");

        $dbName = $tenant->tenancy_db_name ?? 'tenant_' . $tenant->id;
        
        try {
            $tables = \DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?", [$dbName]);

            $sql = "-- Review Platform Tenant SQL Backup\n";
            $sql .= "-- Tenant: {$tenant->name}\n";
            $sql .= "-- Database: {$dbName}\n";
            $sql .= "-- Date: " . now()->toDateTimeString() . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $t) {
                $tableName = $t->TABLE_NAME;
                
                // Fetch drop and create statement
                $createTable = \DB::select("SHOW CREATE TABLE `{$dbName}`.`{$tableName}`");
                $sql .= "-- Table structure for `{$tableName}`\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                if (isset($createTable[0])) {
                    $createArr = (array) $createTable[0];
                    $sql .= $createArr['Create Table'] . ";\n\n";
                }

                // Fetch row values
                $rows = \DB::select("SELECT * FROM `{$dbName}`.`{$tableName}`");
                if (!empty($rows)) {
                    $sql .= "-- Dumping data for table `{$tableName}`\n";
                    foreach ($rows as $row) {
                        $rowArr = (array) $row;
                        $escapedValues = array_map(function ($val) {
                            if ($val === null) return 'NULL';
                            return "'" . addslashes($val) . "'";
                        }, $rowArr);
                        $sql .= "INSERT INTO `{$tableName}` (`" . implode("`, `", array_keys($rowArr)) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            $filename = "backup_" . strtolower(Str::slug($tenant->name)) . "_" . date('Y_m_d_His') . ".sql";

            return response($sql)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed generating database backup: ' . $e->getMessage());
        }
    }

    /**
     * Check if the tenant domain is online and responding.
     */
    public function checkDomain(Tenant $tenant)
    {
        $domainRecord = $tenant->domains->first();
        if (!$domainRecord) {
            return redirect()->back()->with('error', 'No domain configured for this tenant.');
        }

        $domain = $domainRecord->domain;
        $port = request()->getPort();
        $isNonStandard = (request()->getScheme() === 'http' && $port != 80) || (request()->getScheme() === 'https' && $port != 443);
        $domainWithPort = $domain . ($isNonStandard ? ':' . $port : '');

        $url = request()->getScheme() . '://' . $domainWithPort;

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get($url);
            $status = $response->status();
            
            // Log action
            \App\Models\ActivityLog::log('Domain Checked', "Checked connectivity for {$tenant->name} ({$url}) - Status: {$status}");

            if ($response->successful() || $response->redirect()) {
                return redirect()->back()->with('success', "Domain is online! (HTTP status: {$status})");
            }

            return redirect()->back()->with('error', "Domain responded with status: {$status}");
        } catch (\Exception $e) {
            // Log action
            \App\Models\ActivityLog::log('Domain Checked Failed', "Checked connectivity for {$tenant->name} ({$url}) - Offline");

            return redirect()->back()->with('error', 'Domain is unreachable or offline. (Error: ' . $e->getMessage() . ')');
        }
    }
}

