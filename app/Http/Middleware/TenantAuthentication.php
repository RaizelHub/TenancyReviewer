<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply this middleware in tenant context
        if (tenant()) {
            try {
                // Check if we're trying to access tenant_applications table
                // This is a workaround to prevent errors when tenant code tries to access central tables
                $route = $request->route();
                $action = $route ? $route->getAction() : null;

                // Skip the middleware for specific routes if needed
                // if ($action && isset($action['as']) && in_array($action['as'], ['tenant.disabled'])) {
                //     return $next($request);
                // }

                // Continue with the request
                return $next($request);
            } catch (\Exception $e) {
                // Log the error
                \Log::error('Tenant authentication error: ' . $e->getMessage(), [
                    'tenant_id' => tenant('id'),
                    'exception' => $e,
                ]);

                // If there's a database error, it might be related to missing tables
                if (strpos($e->getMessage(), "Table 'tenant_") !== false &&
                    strpos($e->getMessage(), "tenant_applications") !== false) {
                    // Redirect to a tenant-specific error page
                    return redirect()->route('tenant.dashboard');
                }

                // For other errors, just continue
                return $next($request);
            }
        }

        return $next($request);
    }
}
