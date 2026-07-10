<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerusahaanMiddleware
{
    /**
     * Cek session login + role perusahaan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('logged_in') || !session('user_id')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $role = strtolower(trim(session('role', '')));
        if ($role !== 'perusahaan') {
            session()->flush();
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Perusahaan.');
        }

        return $next($request);
    }
}
