<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BpjsController extends Controller
{
    public function index(Request $request)
    {
        $perusahaan_id = session('user_id');

        $bpjs_kesehatan = DB::table('bukti_kepesertaan_bpjs')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('kategori', 'kesehatan')
            ->orderBy('created_at', 'desc')
            ->first();

        $bpjs_ketenagakerjaan = DB::table('bukti_kepesertaan_bpjs')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('kategori', 'ketenagakerjaan')
            ->orderBy('created_at', 'desc')
            ->first();

        $view = 'perusahaan.bpjs.index';
        $params = compact('bpjs_kesehatan', 'bpjs_ketenagakerjaan');

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page'      => $view,
            'pageTitle' => 'Bukti Kepesertaan BPJS',
        ]));
    }

    public function store(Request $request)
    {
        $perusahaan_id = session('user_id');

        $validator = Validator::make($request->all(), [
            'kategori' => 'required|in:kesehatan,ketenagakerjaan',
            'file'     => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'kategori.required' => 'Kategori BPJS wajib dipilih.',
            'kategori.in'       => 'Kategori BPJS tidak valid.',
            'file.required'     => 'File wajib diunggah.',
            'file.mimes'        => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG.',
            'file.max'          => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $exists = DB::table('bukti_kepesertaan_bpjs')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('kategori', $request->kategori)
            ->exists();

        if ($exists) {
            $label = $request->kategori === 'kesehatan' ? 'Kesehatan' : 'Ketenagakerjaan';
            return redirect()->route('perusahaan.bpjs.index')
                ->with('error', "Dokumen BPJS {$label} sudah ada. Gunakan tombol Ganti File untuk mengganti dokumen.");
        }

        $filename = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'bpjs_' . time() . '_' . uniqid() . '.' . $ext;

            $upload_path = public_path('uploads/bpjs');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            $file->move($upload_path, $filename);
        }

        DB::table('bukti_kepesertaan_bpjs')->insert([
            'perusahaan_id' => $perusahaan_id,
            'kategori'      => $request->kategori,
            'file'          => $filename,
            'is_active'     => false,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('perusahaan.bpjs.index')
            ->with('success', 'Bukti kepesertaan BPJS berhasil ditambahkan.');
    }

    public function show($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('bukti_kepesertaan_bpjs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($rec);
    }

    public function update(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('bukti_kepesertaan_bpjs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'file.mimes' => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG.',
            'file.max'   => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filename = $rec->file;
        if ($request->hasFile('file')) {
            if ($rec->file) {
                $old_path = public_path('uploads/bpjs/' . $rec->file);
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }

            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'bpjs_' . time() . '_' . uniqid() . '.' . $ext;

            $upload_path = public_path('uploads/bpjs');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            $file->move($upload_path, $filename);
        }

        DB::table('bukti_kepesertaan_bpjs')
            ->where('id', $id)
            ->update([
                'file'       => $filename,
                'updated_at' => now(),
            ]);

        return redirect()->route('perusahaan.bpjs.index')
            ->with('success', 'Bukti kepesertaan BPJS berhasil diperbarui.');
    }

    public function setActive($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('bukti_kepesertaan_bpjs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        DB::transaction(function () use ($id, $rec, $perusahaan_id) {
            DB::table('bukti_kepesertaan_bpjs')
                ->where('perusahaan_id', $perusahaan_id)
                ->where('kategori', $rec->kategori)
                ->update(['is_active' => false]);

            DB::table('bukti_kepesertaan_bpjs')
                ->where('id', $id)
                ->update(['is_active' => true]);
        });

        return redirect()->route('perusahaan.bpjs.index')
            ->with('success', 'Status bukti kepesertaan BPJS berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('bukti_kepesertaan_bpjs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if ($rec) {
            if ($rec->file) {
                $file_path = public_path('uploads/bpjs/' . $rec->file);
                if (File::exists($file_path)) {
                    File::delete($file_path);
                }
            }

            DB::table('bukti_kepesertaan_bpjs')
                ->where('id', $id)
                ->where('perusahaan_id', $perusahaan_id)
                ->delete();

            return redirect()->route('perusahaan.bpjs.index')
                ->with('warning', 'Bukti kepesertaan BPJS berhasil dihapus.');
        }

        return redirect()->route('perusahaan.bpjs.index')
            ->with('error', 'Data tidak ditemukan.');
    }
}
