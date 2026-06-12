<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'domain_name',
        'active',
        'data',
        'original_domain',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'data' => 'array',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'password',
            'active',
            'created_at',
            'updated_at',
            'data',
            'original_domain',
        ];
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * We're not overriding getTenantKeyName() since it's non-static in the parent class
     * and we can't make it static in the child class
     */

    /**
     * Override the getTenantKey method to ensure we get the correct key
     */
    public function getTenantKey()
    {
        return $this->getAttribute('id');
    }

    /**
     * Override the getCustomDatabaseName method to create database names without 'tenant_' prefix
     * This is crucial to prevent the tenant_0 database issue
     */
    public function getCustomDatabaseName(): string
    {
        // We'll use the ID directly without the tenant_ prefix
        // The package will add the prefix from config
        return $this->getTenantKey();
    }

    /**
     * Override the getDatabaseName method to ensure we get the correct database name
     */
    public function getDatabaseName(): string
    {
        // Use the database prefix from config and our tenant key
        return config('tenancy.database.prefix') . $this->getTenantKey() . config('tenancy.database.suffix');
    }
}