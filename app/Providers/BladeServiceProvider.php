<?php

namespace App\Providers;

use App\Helpers\RouteHelper;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Log;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Route facade as a Blade directive
        Blade::directive('routeHas', function ($expression) {
            return "<?php if (\\App\\Helpers\\RouteHelper::has($expression)): ?>";
        });

        Blade::directive('endrouteHas', function () {
            return "<?php endif; ?>";
        });

        // Register a directive for route generation
        Blade::directive('routeUrl', function ($expression) {
            return "<?php echo \\App\\Helpers\\RouteHelper::route($expression); ?>";
        });

        // Make the Route facade available in all Blade templates
        Blade::directive('route', function ($expression) {
            return "<?php echo \\App\\Helpers\\RouteHelper::route($expression); ?>";
        });

        // Add a directive for checking current route
        Blade::directive('routeIs', function ($expression) {
            return "<?php if (\\App\\Helpers\\RouteHelper::is($expression)): ?>";
        });

        Blade::directive('endrouteIs', function () {
            return "<?php endif; ?>";
        });

        // Register Log facade directives
        Blade::directive('logInfo', function ($expression) {
            return "<?php \\App\\Helpers\\LogHelper::info($expression); ?>";
        });

        Blade::directive('logError', function ($expression) {
            return "<?php \\App\\Helpers\\LogHelper::error($expression); ?>";
        });

        Blade::directive('logWarning', function ($expression) {
            return "<?php \\App\\Helpers\\LogHelper::warning($expression); ?>";
        });

        Blade::directive('logDebug', function ($expression) {
            return "<?php \\App\\Helpers\\LogHelper::debug($expression); ?>";
        });

        // Share the Log facade with all views
        view()->share('Log', app('log'));
    }
}
