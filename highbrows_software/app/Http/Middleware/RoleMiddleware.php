<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles  Allowed roles for the route
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Not logged in, redirect to login
            return redirect()->route('login.form');
        }

        $user = Auth::user();

        if (!in_array($user->usertype, $roles)) {
            // User role not allowed
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
