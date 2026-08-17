<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $requiredRole
     */
    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Please sign in to access the management portal.');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        if (!$user->isStaff()) {
            abort(403, 'Unauthorized access to the JACARIO administration console.');
        }

        if ($requiredRole) {
            $roles = explode('|', $requiredRole);
            if (!in_array($user->role, $roles) && !$user->isSuperAdmin()) {
                abort(403, 'You do not hold the required permissions to access this section.');
            }
        }

        return $next($request);
    }
}
