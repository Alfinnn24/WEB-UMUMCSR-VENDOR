<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $id = session('user_id');
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Sesi telah habis.');
        }

        $view = 'perusahaan.profile';
        $data = ['user' => $user];

        if ($request->ajax()) {
            return view($view, $data);
        }

        return view('layouts.perusahaan', array_merge($data, [
            'page'      => $view,
            'pageTitle' => 'Profile',
        ]));
    }

    public function updateProfile(Request $request)
    {
        $id = session('user_id');
        
        $validator = Validator::make($request->all(), [
            'alamat'      => 'nullable|string',
            'nama_admin'  => 'nullable|string|max:255',
            'nomor_admin' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => implode(' ', $validator->errors()->all())
            ], 422);
        }

        DB::table('users')->where('id', $id)->update([
            'alamat'      => $request->alamat,
            'nama_admin'  => $request->nama_admin,
            'nomor_admin' => $request->nomor_admin,
            'updated_at'  => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $id = session('user_id');

        $validator = Validator::make($request->all(), [
            'password_baru'        => 'required|string|min:6',
            'konfirmasi_password' => 'required|string|same:password_baru',
        ], [
            'password_baru.required'        => 'Password baru tidak boleh kosong.',
            'password_baru.min'             => 'Password minimal 6 karakter.',
            'konfirmasi_password.required'  => 'Konfirmasi password tidak boleh kosong.',
            'konfirmasi_password.same'      => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => implode(' ', $validator->errors()->all())
            ], 422);
        }

        DB::table('users')->where('id', $id)->update([
            'password'   => Hash::make($request->password_baru),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Password berhasil diubah.',
        ]);
    }
}
