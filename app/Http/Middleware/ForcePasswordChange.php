<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Allow routes necessary for password change and logout
            if (! $request->routeIs('password.force-change', 'password.force-update', 'logout')) {
                return redirect()->route('password.force-change');
            }
        }

        return $next($request);
    }
}
