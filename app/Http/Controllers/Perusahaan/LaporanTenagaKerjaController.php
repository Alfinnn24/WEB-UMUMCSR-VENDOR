<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class LaporanTenagaKerjaController extends Controller
{
    public function index(Request $request)
    {
        $perusahaan_id = session('user_id');

        $data = DB::table('laporan_tenaga_kerja')
            ->where('perusahaan_id', $perusahaan_id)
            ->orderBy('tgl_laporan', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        $view = 'perusahaan.laporan_tenaga_kerja.index';
        $params = ['data' => $data];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Laporan Tenaga Kerja',
        ]));
    }

    public function create(Request $request)
    {
        $view = 'perusahaan.laporan_tenaga_kerja.create';

        if ($request->ajax()) {
            return view($view);
        }

        return view('layouts.perusahaan', [
            'page' => $view,
            'pageTitle' => 'Tambah Laporan Tenaga Kerja',
        ]);
    }

    public function store(Request $request)
    {
        $perusahaan_id = session('user_id');

        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string|max:255',
            'tgl_laporan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('laporan_tenaga_kerja')->insert([
            'perusahaan_id' => $perusahaan_id,
            'nomor_surat'   => $request->nomor_surat,
            'tgl_laporan'   => $request->tgl_laporan,
            'created_at'    => now(),
        ]);

        return redirect()->route('perusahaan.laporan-tenaga-kerja.index')
            ->with('success', 'Data laporan berhasil disimpan.');
    }

    public function edit(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('laporan_tenaga_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            abort(404, 'Data tidak ditemukan atau akses ditolak.');
        }

        $view = 'perusahaan.laporan_tenaga_kerja.edit';
        $params = ['rec' => $rec];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Edit Laporan Tenaga Kerja',
        ]));
    }

    public function update(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('laporan_tenaga_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            abort(404, 'Data tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string|max:255',
            'tgl_laporan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('laporan_tenaga_kerja')
            ->where('id', $id)
            ->update([
                'nomor_surat' => $request->nomor_surat,
                'tgl_laporan' => $request->tgl_laporan,
            ]);

        return redirect()->route('perusahaan.laporan-tenaga-kerja.index')
            ->with('success', 'Data laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('laporan_tenaga_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if ($rec) {
            if ($rec->file_laporan) {
                $path = public_path('uploads/laporan_tenaker/' . $rec->file_laporan);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            DB::table('laporan_tenaga_kerja')->where('id', $id)->delete();
            return redirect()->route('perusahaan.laporan-tenaga-kerja.index')
                ->with('warning', 'Data laporan berhasil dihapus.');
        }

        return redirect()->route('perusahaan.laporan-tenaga-kerja.index')
            ->with('error', 'Data tidak ditemukan.');
    }

    public function upload(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('laporan_tenaga_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'file_laporan' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        if ($request->hasFile('file_laporan')) {
            $file = $request->file('file_laporan');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'laporan_' . $id . '_' . time() . '.' . $ext;

            $upload_path = public_path('uploads/laporan_tenaker');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            // Hapus berkas lama jika ada
            if ($rec->file_laporan) {
                $old_path = $upload_path . '/' . $rec->file_laporan;
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }

            $file->move($upload_path, $filename);

            DB::table('laporan_tenaga_kerja')
                ->where('id', $id)
                ->update(['file_laporan' => $filename]);

            return redirect()->route('perusahaan.laporan-tenaga-kerja.index')
                ->with('success', 'File laporan berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Tidak ada file yang diunggah.');
    }
}
