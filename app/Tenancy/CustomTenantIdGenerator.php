<?php

namespace App\Tenancy;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CustomTenantIdGenerator
{
    /**
     * Generate a unique tenant ID
     *
     * @param mixed $tenant
     * @return string
     */
    public function __invoke($tenant): string
    {
        // Generate a unique ID based on the tenant's name
        $nameSlug = '';
        if (isset($tenant->name)) {
            $nameSlug = Str::slug($tenant->name);
            if (empty($nameSlug) || is_numeric($nameSlug)) {
                $nameSlug = 'app';  // Use 'app' instead of 'tenant' to avoid tenant_ prefix
            }
        } else {
            $nameSlug = 'app';
        }

        // Create a unique ID with timestamp and random string
        // Format: name_timestamp_randomstring
        $timestamp = time();
        $randomString = Str::random(7);
        $id = "{$nameSlug}_{$timestamp}_{$randomString}";

        // Log the ID generation
        Log::info('Generated tenant ID in CustomTenantIdGenerator', [
            'tenant_name' => $tenant->name ?? 'unknown',
            'new_id' => $id
        ]);

        return $id;
    }
}
