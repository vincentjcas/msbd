<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Expected usage: middleware('role:admin') or middleware('role:guru')
        $role = null;
        $parameters = func_get_args();
        // Laravel passes additional parameters after $next, but here we rely on the route middleware parameter
        if ($request->route()) {
            $action = $request->route()->getAction();
            if (isset($action['middleware'])) {
                // try to parse role from middleware string
                foreach ((array) $action['middleware'] as $mw) {
                    if (str_starts_with($mw, 'role:')) {
                        $parts = explode(':', $mw, 2);
                        $role = $parts[1] ?? null;
                        break;
                    }
                }
            }
        }

        if (!$role) {
            // fallback: check route parameters
            $role = $request->route('role');
        }

        if (!$role) {
            return $next($request);
        }

        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Unauthorized - role: '.$role);
        }

        return $next($request);
    }
}
