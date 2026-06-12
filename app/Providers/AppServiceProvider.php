<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register global facades
        $this->app->singleton('facades', function ($app) {
            return new \App\Helpers\Facades();
        });

        // Antivirus often deletes vendor/.../server.php; use our bootstrap router instead.
        $this->app->singleton(
            \Illuminate\Foundation\Console\ServeCommand::class,
            \App\Console\Commands\SafeServeCommand::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure pagination to use Tailwind CSS
        Paginator::useTailwind();

        // Make Route facade available in all Blade templates
        Blade::directive('routeCheck', function ($expression) {
            return "<?php if (\\Illuminate\\Support\\Facades\\Route::has($expression)): ?>";
        });

        Blade::directive('endrouteCheck', function () {
            return "<?php endif; ?>";
        });

        // Register the tenant-app-layout component
        Blade::component('tenant-app-layout', \App\View\Components\TenantAppLayout::class);

        // Share the Route facade with all views
        view()->share('Route', app('router'));

        // Configure URL generation to use the current domain
        // This ensures that route() helper generates URLs for the current domain
        // instead of the central domain
        if (app()->bound('tenant')) {
            $tenant = app('tenant');
            if ($tenant) {
                $domain = request()->getHost();
                config(['app.url' => request()->getScheme() . '://' . $domain]);
            }
        }
    }
}
