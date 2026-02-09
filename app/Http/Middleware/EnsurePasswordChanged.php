<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // allow access to the change-password routes and logout
            if ($request->routeIs('password.change') || $request->routeIs('password.change.update') || $request->routeIs('logout') || $request->is('logout')) {
                return $next($request);
            }

            // also allow asset and verification signed route access
            if ($request->routeIs('peminjaman.verify-resi')) {
                return $next($request);
            }

            return redirect()->route('password.change')
                ->with('info', 'Silakan ganti password Anda terlebih dahulu sebelum melanjutkan.');
        }

        return $next($request);
    }
}
