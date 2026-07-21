<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class PeraturanController extends Controller
{
    public function index(Request $request)
    {
        $perusahaan_id = session('user_id');
        $tab = $request->query('tab', 'PP');
        if (!in_array($tab, ['PP', 'PKB'])) {
            $tab = 'PP';
        }

        $data_pp = DB::table('peraturan_perusahaan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('jenis', 'PP')
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        $data_pkb = DB::table('peraturan_perusahaan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('jenis', 'PKB')
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        $view = 'perusahaan.peraturan.index';
        $params = compact('data_pp', 'data_pkb', 'tab');

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page'      => $view,
            'pageTitle' => 'Peraturan Perusahaan',
        ]));
    }

    public function store(Request $request)
    {
        $perusahaan_id = session('user_id');

        $validator = Validator::make($request->all(), [
            'jenis'   => 'required|in:PP,PKB',
            'nomor'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'file'    => 'required|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'jenis.required'   => 'Jenis peraturan wajib dipilih.',
            'jenis.in'         => 'Jenis peraturan tidak valid.',
            'nomor.required'   => 'Nomor peraturan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date'     => 'Format tanggal tidak valid.',
            'file.required'    => 'File peraturan wajib diunggah.',
            'file.mimes'       => 'Format file harus PDF, DOC, atau DOCX.',
            'file.max'         => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filename = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'peraturan_' . time() . '_' . uniqid() . '.' . $ext;

            $upload_path = public_path('uploads/peraturan');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            $file->move($upload_path, $filename);
        }

        DB::table('peraturan_perusahaan')->insert([
            'perusahaan_id' => $perusahaan_id,
            'jenis'         => $request->jenis,
            'nomor'         => $request->nomor,
            'tanggal'       => $request->tanggal,
            'file'          => $filename,
            'is_active'     => false,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $tab = $request->jenis;

        return redirect()->route('perusahaan.peraturan.index', ['tab' => $tab])
            ->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('peraturan_perusahaan')
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

        $rec = DB::table('peraturan_perusahaan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'nomor'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'file'    => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'nomor.required'   => 'Nomor peraturan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date'     => 'Format tanggal tidak valid.',
            'file.mimes'       => 'Format file harus PDF, DOC, atau DOCX.',
            'file.max'         => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filename = $rec->file;
        if ($request->hasFile('file')) {
            if ($rec->file) {
                $old_path = public_path('uploads/peraturan/' . $rec->file);
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }

            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'peraturan_' . time() . '_' . uniqid() . '.' . $ext;

            $upload_path = public_path('uploads/peraturan');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            $file->move($upload_path, $filename);
        }

        DB::table('peraturan_perusahaan')
            ->where('id', $id)
            ->update([
                'nomor'       => $request->nomor,
                'tanggal'     => $request->tanggal,
                'file'        => $filename,
                'updated_at'  => now(),
            ]);

        return redirect()->route('perusahaan.peraturan.index', ['tab' => $rec->jenis])
            ->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function setActive($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('peraturan_perusahaan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        DB::transaction(function () use ($id, $rec, $perusahaan_id) {
            DB::table('peraturan_perusahaan')
                ->where('perusahaan_id', $perusahaan_id)
                ->where('jenis', $rec->jenis)
                ->update(['is_active' => false]);

            DB::table('peraturan_perusahaan')
                ->where('id', $id)
                ->update(['is_active' => true]);
        });

        return redirect()->route('perusahaan.peraturan.index', ['tab' => $rec->jenis])
            ->with('success', 'Status peraturan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('peraturan_perusahaan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if ($rec) {
            if ($rec->file) {
                $file_path = public_path('uploads/peraturan/' . $rec->file);
                if (File::exists($file_path)) {
                    File::delete($file_path);
                }
            }

            DB::table('peraturan_perusahaan')
                ->where('id', $id)
                ->where('perusahaan_id', $perusahaan_id)
                ->delete();

            return redirect()->route('perusahaan.peraturan.index', ['tab' => $rec->jenis])
                ->with('warning', 'Peraturan berhasil dihapus.');
        }

        return redirect()->route('perusahaan.peraturan.index')
            ->with('error', 'Data tidak ditemukan.');
    }
}
