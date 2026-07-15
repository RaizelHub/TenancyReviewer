<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Load helper functions
require_once __DIR__ . '/../app/Helpers/helpers.php';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        // Laravel core providers
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        // Custom providers
        App\Providers\TenancyServiceProvider::class,
        App\Providers\SendbirdServiceProvider::class,
        App\Providers\BladeServiceProvider::class,
        CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'tenant' => \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);

        // Configure web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\SetTenantAppUrl::class,
            \App\Http\Middleware\CheckTenantStatus::class,
            \App\Http\Middleware\TenantAuthentication::class,
            \App\Http\Middleware\RemoveCentralDomainParameter::class,
        ]);

        // Configure global middleware
        $middleware->append([
            \App\Http\Middleware\SetTenantAppUrl::class,
            \App\Http\Middleware\CheckTenantStatus::class,
            \App\Http\Middleware\TenantAuthentication::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
