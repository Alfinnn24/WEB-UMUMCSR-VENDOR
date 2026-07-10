<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── Halaman Login ─────────────────────────────────────────────────
    public function showLogin()
    {
        // Jika sudah login, redirect sesuai role
        if (session('logged_in') && session('user_id')) {
            return $this->redirectByRole(session('role'));
        }
        return view('auth.login');
    }

    // ─── Proses Login ──────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'nid'      => 'required|string|max:50',
            'password' => 'required|string',
        ], [
            'nid.required'      => 'NID wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $nid  = trim($request->input('nid'));
        $pass = $request->input('password');

        // Ambil user dari DB
        $user = DB::table('users')->where('nid', $nid)->first();

        if (!$user) {
            return back()->with('error', 'NID atau password salah.')->withInput(['nid' => $nid]);
        }

        // Verifikasi password dengan Hash::check (kompatibel bcrypt / password_verify)
        if (!Hash::check($pass, $user->password)) {
            return back()->with('error', 'NID atau password salah.')->withInput(['nid' => $nid]);
        }

        // Cek status user (jika kolom ada)
        if (isset($user->status) && strtolower(trim($user->status)) === 'nonaktif') {
            return back()->with('error', 'Akun Anda dinonaktifkan. Hubungi Admin.')->withInput(['nid' => $nid]);
        }

        // Cek role admin (case-insensitive)
        $role = strtolower(trim($user->role));
        if ($role !== 'admin' && $role !== 'perusahaan') {
            return back()->with('error', 'Akses ditolak. Role Anda tidak diizinkan.')->withInput(['nid' => $nid]);
        }

        // Set session — simpan SEMUA key yang dibutuhkan middleware & seluruh app
        session([
            'logged_in' => true,
            'id'        => $user->id,
            'user_id'   => $user->id,
            'nid'       => $user->nid,
            'nama'      => $user->nama,
            'role'      => strtolower(trim($user->role)),  // simpan sudah lowercase
            'status'    => $user->status ?? 'aktif',
        ]);

        // Regenerate session ID untuk keamanan
        $request->session()->regenerate();

        return $this->redirectByRole($role);
    }

    // ─── Logout ────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    // ─── Helper: redirect berdasarkan role ─────────────────────────────
    private function redirectByRole(string $role)
    {
        $role = strtolower(trim($role));

        return match($role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'perusahaan' => redirect()->route('perusahaan.dashboard'),
            'unit'       => redirect()->route('admin.dashboard'), // fallback
            default      => redirect()->route('login')->with('error', 'Role tidak dikenal.'),
        };
    }
}
