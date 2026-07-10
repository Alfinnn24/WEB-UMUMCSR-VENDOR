<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Cek session login + role admin.
     * Jika belum login → redirect ke /login.
     * Jika login tapi bukan admin → redirect ke /login dengan pesan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login (pakai key 'logged_in' yang di-set AuthController)
        if (!session('logged_in') || !session('user_id')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role admin (case-insensitive)
        $role = strtolower(trim(session('role', '')));
        if ($role !== 'admin') {
            session()->flush();
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}
