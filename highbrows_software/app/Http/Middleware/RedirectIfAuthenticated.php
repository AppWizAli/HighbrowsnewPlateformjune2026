<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                Log::info('RedirectIfAuthenticated triggered', [
                    'path' => $request->path(),
                    'full_url' => $request->fullUrl(),
                    'user_id' => $user?->id,
                    'usertype' => $user?->usertype,
                ]);

                if ($user?->usertype === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                if ($user?->usertype === 'subadmin') {
                    return redirect()->route('subadmin.dashboard');
                }

                if ($user?->usertype === 'cordinator') {
                    return redirect()->route('cordinator.dashboard');
                }

                if ($user?->usertype === 'user') {
                    return redirect()->route('student.dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
