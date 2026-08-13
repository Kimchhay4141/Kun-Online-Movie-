<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     * @param  string|null  $guard
     */
    public function handle(Request $request, Closure $next, string $permission, ?string $guard = null): Response
    {
        // Check if user is authenticated
        if (!auth()->guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'authentication_required'
                ], 401);
            }
            
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        $user = auth()->guard($guard)->user();

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. You do not have the required permission.',
                    'error' => 'permission_denied',
                    'required_permission' => $permission
                ], 403);
            }
            
            abort(403, "Access denied. Required permission: {$permission}");
        }

        return $next($request);
    }
}
