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
        return view('app.tenants.show', ['tenant' => $tenant]);
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
        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Disable a tenant.
     */
    public function disable(Tenant $tenant)
    {
        $tenant->update(['active' => false]);
        return redirect()->route('tenants.index')->with('success', 'Tenant disabled successfully.');
    }

    /**
     * Enable a tenant.
     */
    public function enable(Tenant $tenant)
    {
        $tenant->update(['active' => true]);
        return redirect()->route('tenants.index')->with('success', 'Tenant enabled successfully.');
    }
}

