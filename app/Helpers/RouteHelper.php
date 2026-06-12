<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

/**
 * Helper functions for route-related operations in Blade templates
 */
class RouteHelper
{
    /**
     * Check if a route exists
     *
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return app('router')->has($name);
    }

    /**
     * Generate a URL for a named route
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    public static function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return app('url')->route($name, $parameters, $absolute);
    }

    /**
     * Get the current route name
     *
     * @return string|null
     */
    public static function currentRouteName(): ?string
    {
        return app('router')->currentRouteName();
    }

    /**
     * Check if the current route matches a given pattern
     *
     * @param string|array $patterns
     * @return bool
     */
    public static function is($patterns): bool
    {
        return app('router')->is($patterns);
    }
}
