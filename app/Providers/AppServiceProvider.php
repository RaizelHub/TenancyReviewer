<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

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

        // Force the URL generator to use the actual request's scheme+host+port.
        // This ensures route() always generates URLs matching the browser's address,
        // preventing about:blank#blocked when accessing via 127.0.0.1 vs localhost.
        if (app()->runningInConsole() === false && request()->getHost()) {
            $scheme = request()->getScheme();
            $host   = request()->getHost();
            $port   = request()->getPort();

            // Build root URL only including port when it's non-standard
            $isNonStandardPort = ($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443);
            $rootUrl = $scheme . '://' . $host . ($isNonStandardPort ? ':' . $port : '');

            URL::forceRootUrl($rootUrl);
            config(['app.url' => $rootUrl]);
        }

        // Dynamic system settings overrides
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $dbSettings = \App\Models\Setting::pluck('value', 'key')->all();
                
                if (isset($dbSettings['app_name']) && !empty($dbSettings['app_name'])) {
                    config(['app.name' => $dbSettings['app_name']]);
                }
                
                foreach (['emailjs_public_key', 'emailjs_service_id', 'emailjs_template_approved', 'sendbird_app_id', 'sendbird_api_token', 'sendbird_api_url'] as $key) {
                    if (isset($dbSettings[$key]) && !empty($dbSettings[$key])) {
                        $envKey = strtoupper($key);
                        $_ENV[$envKey] = $dbSettings[$key];
                        $_SERVER[$envKey] = $dbSettings[$key];
                        putenv("{$envKey}={$dbSettings[$key]}");
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently ignore if table doesn't exist during migrations/setup
        }
    }
}
