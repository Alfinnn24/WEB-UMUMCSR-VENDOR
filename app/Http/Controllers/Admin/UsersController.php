<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Tampilkan data users.
     */
    public function index(Request $request)
    {
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return view('admin.users.index', compact('users'));
        }

        return view('layouts.admin', [
            'page'      => 'admin.users.index',
            'pageTitle' => 'Manajemen Users',
            'users'     => $users,
        ]);
    }

    /**
     * Ambil data detail user (JSON) — dipakai modal Detail & Edit.
     */
    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json($user);
    }

    /**
     * Simpan user baru.
     * Kolom tabel: id, nid, nama, alamat, password, role(enum:admin,perusahaan),
     *              status(enum:aktif,nonaktif), created_at, nama_admin, nomor_admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'nid'      => ['required', 'string', 'max:100', 'unique:users,nid'],
            'nama'     => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
            'role'     => ['required', 'string', Rule::in(['admin', 'perusahaan'])],
            'status'   => ['required', 'string', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'nid.required'      => 'NID wajib diisi.',
            'nid.unique'        => 'NID sudah digunakan.',
            'nama.required'     => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 4 karakter.',
        ]);

        DB::table('users')->insert([
            'nid'        => trim($request->input('nid')),
            'nama'       => trim($request->input('nama')),
            'password'   => Hash::make($request->input('password')),
            'role'       => $request->input('role'),
            'status'     => $request->input('status'),
            'created_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil ditambahkan.'
        ]);
    }

    /**
     * Update user.
     */
    public function update(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $request->validate([
            'nid'      => ['required', 'string', 'max:100', Rule::unique('users', 'nid')->ignore($id)],
            'nama'     => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:4'],
            'role'     => ['required', 'string', Rule::in(['admin', 'perusahaan'])],
            'status'   => ['required', 'string', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'nid.required'  => 'NID wajib diisi.',
            'nid.unique'    => 'NID sudah digunakan user lain.',
            'nama.required' => 'Nama wajib diisi.',
            'password.min'  => 'Password minimal 4 karakter.',
        ]);

        $updateData = [
            'nid'    => trim($request->input('nid')),
            'nama'   => trim($request->input('nama')),
            'role'   => $request->input('role'),
            'status' => $request->input('status'),
        ];

        // Jika password diisi, update password (sama seperti PHP native)
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil diperbarui.'
        ]);
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        // Jangan biarkan user menghapus dirinya sendiri
        if ($id == session('user_id') || $id == session('id')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak dapat menghapus akun sendiri yang sedang aktif.'
            ], 400);
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        DB::table('users')->where('id', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil dihapus.'
        ]);
    }
}
