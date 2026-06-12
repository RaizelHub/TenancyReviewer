<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're in a tenant context
        if (tenant()) {
            // Check if the tenant is active
            if (!tenant()->active) {
                // If the tenant is not active, show the disabled page
                return response()->view('tenant.disabled', [], 503);
            }
        }

        return $next($request);
    }
}
