<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InformasiController extends Controller
{
    /**
     * Tampilkan data informasi.
     */
    public function index(Request $request)
    {
        $informasi = DB::table('informasi')->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return view('admin.informasi.index', compact('informasi'));
        }

        return view('layouts.admin', [
            'page'      => 'admin.informasi.index',
            'pageTitle' => 'Manajemen Informasi',
            'informasi' => $informasi,
        ]);
    }

    /**
     * Detail informasi (JSON).
     */
    public function show($id)
    {
        $info = DB::table('informasi')->where('id', $id)->first();

        if (!$info) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($info);
    }

    /**
     * Simpan informasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'file'  => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar'],
        ], [
            'judul.required' => 'Judul informasi wajib diisi.',
            'file.required'  => 'File wajib diunggah.',
            'file.max'       => 'Ukuran file maksimal 10MB.',
            'file.mimes'     => 'Format file tidak diizinkan.',
        ]);

        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $targetDir = public_path('uploads/informasi');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0777, true, true);
            }
            $file->move($targetDir, $fileName);
        }

        DB::table('informasi')->insert([
            'judul'      => trim($request->input('judul')),
            'file'       => $fileName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Informasi berhasil ditambahkan.'
        ]);
    }

    /**
     * Update informasi.
     */
    public function update(Request $request, $id)
    {
        $info = DB::table('informasi')->where('id', $id)->first();

        if (!$info) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'file'  => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar'],
        ], [
            'judul.required' => 'Judul informasi wajib diisi.',
            'file.max'       => 'Ukuran file maksimal 10MB.',
            'file.mimes'     => 'Format file tidak diizinkan.',
        ]);

        $fileName = $info->file;
        if ($request->hasFile('file')) {
            // Delete old file
            if ($info->file) {
                $oldPath = public_path('uploads/informasi/' . $info->file);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $targetDir = public_path('uploads/informasi');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0777, true, true);
            }
            $file->move($targetDir, $fileName);
        }

        DB::table('informasi')->where('id', $id)->update([
            'judul'      => trim($request->input('judul')),
            'file'       => $fileName,
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Informasi berhasil diperbarui.'
        ]);
    }

    /**
     * Hapus informasi.
     */
    public function destroy($id)
    {
        $info = DB::table('informasi')->where('id', $id)->first();

        if (!$info) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        // Delete file
        if ($info->file) {
            $filePath = public_path('uploads/informasi/' . $info->file);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        DB::table('informasi')->where('id', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Informasi berhasil dihapus.'
        ]);
    }
}
