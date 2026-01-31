<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsSiswa
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/'); // belum login
        }

        return Auth::user()->role === 'siswa'
            ? $next($request)
            : redirect()->route('admin.dashboard'); // kalau bukan siswa, kirim ke admin
    }
}
