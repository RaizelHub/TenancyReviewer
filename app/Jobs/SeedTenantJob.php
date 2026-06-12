<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
use App\Models\{
    User,
    Tenant
};

class SeedTenantJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
     protected $tenant;
     public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(function(){
            // Always use the plain text password '12345678' for tenant users
            $plainPassword = '12345678';

            // Log the tenant user creation
            \Log::info('Creating tenant user', [
                'tenant_id' => $this->tenant->id,
                'email' => $this->tenant->email,
                'tenant_name' => $this->tenant->name,
                'password' => $plainPassword
            ]);

            // Log the entire tenant data for debugging
            \Log::info('Tenant data', [
                'tenant_id' => $this->tenant->id,
                'tenant_data' => $this->tenant->data
            ]);

            // Get the subscription plan from tenant data
            $subscriptionPlan = $this->tenant->data['subscription_plan'] ?? 'Pro';

            // Ensure the subscription plan has the correct case (first letter capitalized)
            $subscriptionPlan = ucfirst(strtolower($subscriptionPlan));

            // Log the subscription plan from application
            \Log::info('Using subscription plan from application for tenant user', [
                'tenant_id' => $this->tenant->id,
                'subscription_plan' => $subscriptionPlan,
                'original_plan' => $this->tenant->data['subscription_plan'] ?? 'not set'
            ]);

            // Create the user with the hashed password and subscription plan
            // We need to hash it manually to ensure it's properly hashed
            $user = User::create([
                'name' => $this->tenant->name,
                'email' => $this->tenant->email,
                'password' => Hash::make($plainPassword),
                'subscription_plan' => $subscriptionPlan,
            ]);

            // Log successful user creation
            \Log::info('Tenant user created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        });
    }
}
