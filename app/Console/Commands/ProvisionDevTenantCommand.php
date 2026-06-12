<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ProvisionDevTenantCommand extends Command
{
    protected $signature = 'tenant:provision-dev
                            {slug=buksu : Subdomain slug (becomes slug.localhost)}
                            {--email=admin@buksu.local : Tenant login email}
                            {--password=12345678 : Tenant login password}
                            {--name=BUKSU Demo : Tenant display name}
                            {--force : Re-create if tenant exists}';

    protected $description = 'Create a local dev tenant with database, migrations, and login user';

    public function handle(): int
    {
        $slug = Str::slug($this->argument('slug'));
        $appDomain = config('app.domain', 'localhost');
        $fullDomain = "{$slug}.{$appDomain}";

        $existing = Tenant::query()
            ->whereHas('domains', fn ($q) => $q->where('domain', $fullDomain))
            ->first();

        if ($existing && ! $this->option('force')) {
            $this->info("Tenant already exists for {$fullDomain} (id: {$existing->id}).");
            $this->line("Open: http://{$fullDomain}:8000");
            $this->line("Login: {$existing->email} / {$this->option('password')}");

            return self::SUCCESS;
        }

        if ($existing && $this->option('force')) {
            $this->warn("Deleting existing tenant {$existing->id}...");
            $existing->delete();
        }

        $tenantId = "{$slug}_".time().'_'.Str::random(8);

        $this->info("Creating tenant {$tenantId} for {$fullDomain}...");

        $tenant = Tenant::create([
            'id' => $tenantId,
            'name' => $this->option('name'),
            'email' => $this->option('email'),
            'password' => $this->option('password'),
            'active' => true,
            'data' => [
                'domain_name' => $slug,
                'subscription_plan' => 'Pro',
            ],
        ]);

        $tenant->domains()->create(['domain' => $fullDomain]);

        $this->newLine();
        $this->components->info('Dev tenant ready');
        $this->line("  URL:      http://{$fullDomain}:8000");
        $this->line("  Login:    {$this->option('email')}");
        $this->line("  Password: {$this->option('password')}");
        $this->newLine();
        $this->line('Central admin: http://127.0.0.1:8000');

        return self::SUCCESS;
    }
}
