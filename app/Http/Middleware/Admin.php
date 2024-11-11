<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pastikan pengguna sudah login
        $users = Auth::user();

        if (!$users) {
            // Redirect ke halaman login jika pengguna belum login
            return redirect()->route('login');
        }

        // Jika pengguna bukan admin, arahkan ke halaman dashboard
        if ($users->level !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Izinkan akses ke halaman admin jika level pengguna adalah admin
        return $next($request);
    }
}
