<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class KontrakKerjaController extends Controller
{
    public function index(Request $request)
    {
        $perusahaan_id = session('user_id');

        $data = DB::table('kontrak_kerja as k')
            ->select('k.*')
            ->selectRaw('(SELECT COUNT(*) FROM kontrak_karyawan kk WHERE kk.kontrak_id = k.id) as jml_assigned')
            ->where('k.perusahaan_id', $perusahaan_id)
            ->orderBy('k.tgl_mulai', 'desc')
            ->orderBy('k.id', 'desc')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        $view = 'perusahaan.kontrak_kerja.index';
        $params = ['data' => $data];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Kontrak Kerja',
        ]));
    }

    public function create(Request $request)
    {
        $view = 'perusahaan.kontrak_kerja.create';

        if ($request->ajax()) {
            return view($view);
        }

        return view('layouts.perusahaan', [
            'page' => $view,
            'pageTitle' => 'Tambah Kontrak Kerja',
        ]);
    }

    public function store(Request $request)
    {
        $perusahaan_id = session('user_id');

        $validator = Validator::make($request->all(), [
            'judul_kontrak'       => 'required|string|max:255',
            'nomor_kontrak'       => 'required|string|max:255',
            'tgl_mulai'           => 'required|date',
            'tgl_selesai'         => 'required|date|after:tgl_mulai',
            'jumlah_tenaga_kerja' => 'required|integer|min:0',
            'deskripsi_pekerjaan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = DB::table('kontrak_kerja')->insertGetId([
            'perusahaan_id'       => $perusahaan_id,
            'judul_kontrak'       => $request->judul_kontrak,
            'nomor_kontrak'       => $request->nomor_kontrak,
            'tgl_mulai'           => $request->tgl_mulai,
            'tgl_selesai'         => $request->tgl_selesai,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            'created_at'          => now(),
        ]);

        return redirect()->route('perusahaan.kontrak-kerja.index')
            ->with('success', 'Kontrak berhasil disimpan. Silakan tetapkan karyawan atau upload berkas.');
    }

    public function edit(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $rec = DB::table('kontrak_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            abort(404, 'Kontrak tidak ditemukan atau akses ditolak.');
        }

        $view = 'perusahaan.kontrak_kerja.edit';
        $params = ['rec' => $rec];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Edit Kontrak Kerja',
        ]));
    }

    public function update(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $rec = DB::table('kontrak_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            abort(404, 'Kontrak tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'judul_kontrak'       => 'required|string|max:255',
            'nomor_kontrak'       => 'required|string|max:255',
            'tgl_mulai'           => 'required|date',
            'tgl_selesai'         => 'required|date|after:tgl_mulai',
            'jumlah_tenaga_kerja' => 'required|integer|min:0',
            'deskripsi_pekerjaan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('kontrak_kerja')
            ->where('id', $id)
            ->update([
                'judul_kontrak'       => $request->judul_kontrak,
                'nomor_kontrak'       => $request->nomor_kontrak,
                'tgl_mulai'           => $request->tgl_mulai,
                'tgl_selesai'         => $request->tgl_selesai,
                'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
                'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            ]);

        return redirect()->route('perusahaan.kontrak-kerja.index')
            ->with('success', 'Data kontrak berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perusahaan_id = session('user_id');
        $rec = DB::table('kontrak_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if ($rec) {
            if ($rec->berkas_kontrak) {
                $path = public_path('uploads/kontrak/' . $rec->berkas_kontrak);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            DB::table('kontrak_karyawan')->where('kontrak_id', $id)->delete();
            DB::table('kontrak_kerja')->where('id', $id)->delete();
            return redirect()->route('perusahaan.kontrak-kerja.index')
                ->with('warning', 'Data kontrak berhasil dihapus.');
        }

        return redirect()->route('perusahaan.kontrak-kerja.index')
            ->with('error', 'Kontrak tidak ditemukan.');
    }

    public function upload(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $rec = DB::table('kontrak_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Kontrak tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'berkas_kontrak' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        if ($request->hasFile('berkas_kontrak')) {
            $file = $request->file('berkas_kontrak');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'kontrak_' . $id . '_' . time() . '.' . $ext;

            // Pastikan folder uploads/kontrak exists
            $upload_path = public_path('uploads/kontrak');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            // Hapus berkas lama jika ada
            if ($rec->berkas_kontrak) {
                $old_path = $upload_path . '/' . $rec->berkas_kontrak;
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }

            $file->move($upload_path, $filename);

            DB::table('kontrak_kerja')
                ->where('id', $id)
                ->update(['berkas_kontrak' => $filename]);

            return redirect()->route('perusahaan.kontrak-kerja.index')
                ->with('success', 'Berkas kontrak berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Tidak ada file yang diunggah.');
    }

    // Kelola karyawan dalam kontrak
    public function karyawan(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $kontrak = DB::table('kontrak_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$kontrak) {
            abort(404, 'Kontrak tidak ditemukan.');
        }

        $all_karyawan = DB::table('karyawan as k')
            ->leftJoin('kontrak_karyawan as kk', function ($join) use ($id) {
                $join->on('kk.karyawan_id', '=', 'k.id')
                     ->where('kk.kontrak_id', '=', $id);
            })
            ->select('k.id', 'k.nama', 'k.nik', 'k.jabatan', 'k.unit')
            ->selectRaw('IF(kk.id IS NOT NULL, 1, 0) as is_assigned')
            ->where('k.perusahaan_id', $perusahaan_id)
            ->where('k.status', 'Aktif')
            ->orderBy('is_assigned', 'desc')
            ->orderBy('k.nama', 'asc')
            ->get();

        $jml_assigned = $all_karyawan->where('is_assigned', 1)->count();

        $view = 'perusahaan.kontrak_kerja.karyawan';
        $params = [
            'kontrak'      => $kontrak,
            'all_karyawan' => $all_karyawan,
            'jml_assigned' => $jml_assigned,
        ];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Karyawan Sesuai Kontrak',
        ]));
    }

    public function storeKaryawan(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $kontrak = DB::table('kontrak_kerja')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$kontrak) {
            return redirect()->back()->with('error', 'Kontrak tidak ditemukan.');
        }

        DB::transaction(function () use ($id, $request) {
            DB::table('kontrak_karyawan')->where('kontrak_id', $id)->delete();

            $karyawan_ids = $request->input('karyawan_ids', []);
            foreach ($karyawan_ids as $kid) {
                $kid = (int)$kid;
                if ($kid > 0) {
                    DB::table('kontrak_karyawan')->insertOrIgnore([
                        'kontrak_id'  => $id,
                        'karyawan_id' => $kid,
                        'created_at'  => now(),
                    ]);
                }
            }
        });

        return redirect()->route('perusahaan.kontrak-kerja.index')
            ->with('success', 'Data karyawan kontrak berhasil disimpan.');
    }
}
