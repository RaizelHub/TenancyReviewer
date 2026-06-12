<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantApplication extends Model
{
    protected $fillable = [
        'company_name',
        'full_name',
        'email',
        'domain_name',
        'contact',
        'subscription_plan',
        'message',
        'notes',
        'status',
        'reviewed_at',
        'tenant_id',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
