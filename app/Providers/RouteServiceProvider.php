<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Force URL generation to always use the actual request's scheme+host+port.
        // This prevents domain-bound routes from generating links with a different
        // host (e.g. "localhost") when the user is browsing on "127.0.0.1", which
        // causes the browser to show "about:blank#blocked" when clicking sidebar links.
        $this->app->booted(function () {
            $request = $this->app['request'];
            $scheme = $request->getScheme();
            $host   = $request->getHost();
            $port   = $request->getPort();

            $root = $scheme . '://' . $host;
            if (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)) {
                $root .= ':' . $port;
            }

            $centralDomains = config('tenancy.central_domains', []);
            $defaultCentral = $centralDomains[0] ?? $host;

            URL::forceRootUrl($root);
            URL::defaults(['central_domain' => $defaultCentral]);
            config(['app.url' => $root]);
        });

        $centralDomains = $this->centralDomains();

        if (!empty($centralDomains)) {
            $pattern = implode('|', array_map('preg_quote', $centralDomains));

            Route::domain('{central_domain}')->where(['central_domain' => $pattern])->group(function () {
                Route::middleware('web')->group(base_path('routes/web.php'));

                if (file_exists(base_path('routes/api.php'))) {
                    Route::middleware('api')
                        ->prefix('api')
                        ->group(base_path('routes/api.php'));
                }
            });
        }
    }

    protected function centralDomains(): array
    {
        return config('tenancy.central_domains');
    }
}