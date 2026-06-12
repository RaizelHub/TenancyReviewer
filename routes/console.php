<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tenants:migrate-all {--fresh : Whether to run a fresh migration}', function () {
    $this->info('Starting tenant migrations...');

    $tenants = Tenant::all();
    $count = $tenants->count();

    $this->info("Found {$count} tenants.");

    $bar = $this->output->createProgressBar($count);
    $bar->start();

    foreach ($tenants as $tenant) {
        $this->info("\nMigrating tenant: {$tenant->id}");

        try {
            $tenant->run(function () use ($tenant) {
                $this->info("Running migrations for tenant {$tenant->id}");

                if ($this->option('fresh')) {
                    $this->info("Running fresh migrations");
                    Artisan::call('migrate:fresh', [
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);
                } else {
                    $this->info("Running migrations");
                    Artisan::call('migrate', [
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);
                }

                $this->info(Artisan::output());
            });

            $this->info("Successfully migrated tenant: {$tenant->id}");
        } catch (\Exception $e) {
            $this->error("Failed to migrate tenant {$tenant->id}: {$e->getMessage()}");
        }

        $bar->advance();
    }

    $bar->finish();
    $this->info("\nAll tenant migrations completed.");
})->purpose('Run migrations for all tenants');
