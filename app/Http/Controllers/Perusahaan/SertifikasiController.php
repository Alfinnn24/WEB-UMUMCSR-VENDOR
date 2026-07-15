<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SertifikasiController extends Controller
{
    public function index(Request $request)
    {
        $perusahaan_id = session('user_id');

        $data = DB::table('sertifikasi_karyawan as s')
            ->leftJoin('karyawan as k', 's.karyawan_id', '=', 'k.id')
            ->select('s.*', 'k.nama as nama_karyawan', 'k.nik', 'k.jabatan')
            ->where('s.perusahaan_id', $perusahaan_id)
            ->orderBy('s.id', 'desc')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        $view = 'perusahaan.sertifikasi.index';
        $params = ['data' => $data];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Sertifikasi Karyawan',
        ]));
    }

    public function create(Request $request)
    {
        $perusahaan_id = session('user_id');

        $all_karyawan = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('status', 'Aktif')
            ->orderBy('nama', 'asc')
            ->get();

        $view = 'perusahaan.sertifikasi.create';
        $params = ['all_karyawan' => $all_karyawan];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Tambah Sertifikasi Karyawan',
        ]));
    }

    public function store(Request $request)
    {
        $perusahaan_id = session('user_id');

        $validator = Validator::make($request->all(), [
            'karyawan_id'         => 'required|integer',
            'nama_sertifikasi'    => 'required|string|max:255',
            'nomor_sertifikat'    => 'nullable|string|max:255',
            'tanggal_sertifikasi' => 'required|date',
            'masa_berlaku'         => 'required|integer|min:1',
            'tanggal_expired'      => 'required|date',
            'lembaga_sertifikasi'  => 'required|string|max:255',
            'kota_pelaksanaan'     => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('sertifikasi_karyawan')->insert([
            'perusahaan_id'       => $perusahaan_id,
            'karyawan_id'         => $request->karyawan_id,
            'nama_sertifikasi'    => $request->nama_sertifikasi,
            'nomor_sertifikat'    => $request->nomor_sertifikat,
            'tanggal_sertifikasi' => $request->tanggal_sertifikasi,
            'masa_berlaku'        => $request->masa_berlaku,
            'tanggal_expired'     => $request->tanggal_expired,
            'lembaga_sertifikasi' => $request->lembaga_sertifikasi,
            'kota_pelaksanaan'    => $request->kota_pelaksanaan,
            'created_at'          => now(),
        ]);

        return redirect()->route('perusahaan.sertifikasi.index')
            ->with('success', 'Data sertifikasi berhasil disimpan.');
    }

    public function edit(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('sertifikasi_karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            abort(404, 'Data tidak ditemukan atau akses ditolak.');
        }

        $all_karyawan = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('status', 'Aktif')
            ->orderBy('nama', 'asc')
            ->get();

        $view = 'perusahaan.sertifikasi.edit';
        $params = [
            'rec'          => $rec,
            'all_karyawan' => $all_karyawan
        ];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Edit Sertifikasi Karyawan',
        ]));
    }

    public function update(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('sertifikasi_karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            abort(404, 'Data tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'karyawan_id'         => 'required|integer',
            'nama_sertifikasi'    => 'required|string|max:255',
            'nomor_sertifikat'    => 'nullable|string|max:255',
            'tanggal_sertifikasi' => 'required|date',
            'masa_berlaku'         => 'required|integer|min:1',
            'tanggal_expired'      => 'required|date',
            'lembaga_sertifikasi'  => 'required|string|max:255',
            'kota_pelaksanaan'     => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('sertifikasi_karyawan')
            ->where('id', $id)
            ->update([
                'karyawan_id'         => $request->karyawan_id,
                'nama_sertifikasi'    => $request->nama_sertifikasi,
                'nomor_sertifikat'    => $request->nomor_sertifikat,
                'tanggal_sertifikasi' => $request->tanggal_sertifikasi,
                'masa_berlaku'        => $request->masa_berlaku,
                'tanggal_expired'     => $request->tanggal_expired,
                'lembaga_sertifikasi' => $request->lembaga_sertifikasi,
                'kota_pelaksanaan'    => $request->kota_pelaksanaan,
            ]);

        return redirect()->route('perusahaan.sertifikasi.index')
            ->with('success', 'Data sertifikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('sertifikasi_karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if ($rec) {
            if ($rec->file_sertifikat) {
                $path = public_path('uploads/sertifikasi/' . $rec->file_sertifikat);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            DB::table('sertifikasi_karyawan')->where('id', $id)->delete();
            return redirect()->route('perusahaan.sertifikasi.index')
                ->with('warning', 'Data sertifikasi berhasil dihapus.');
        }

        return redirect()->route('perusahaan.sertifikasi.index')
            ->with('error', 'Data tidak ditemukan.');
    }

    public function upload(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $rec = DB::table('sertifikasi_karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->first();

        if (!$rec) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        if ($request->hasFile('file_sertifikat')) {
            $file = $request->file('file_sertifikat');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'sertif_' . $id . '_' . time() . '.' . $ext;

            $upload_path = public_path('uploads/sertifikasi');
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0755, true);
            }

            // Hapus berkas lama jika ada
            if ($rec->file_sertifikat) {
                $old_path = $upload_path . '/' . $rec->file_sertifikat;
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }

            $file->move($upload_path, $filename);

            DB::table('sertifikasi_karyawan')
                ->where('id', $id)
                ->update(['file_sertifikat' => $filename]);

            return redirect()->route('perusahaan.sertifikasi.index')
                ->with('success', 'File sertifikat berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Tidak ada file yang diunggah.');
    }
}
