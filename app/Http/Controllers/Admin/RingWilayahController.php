<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RingWilayahController extends Controller
{
    /**
     * Tampilkan data ring wilayah.
     */
    public function index(Request $request)
    {
        $ring_wilayah = DB::table('ring_wilayah')
            ->orderByRaw("FIELD(ring, 'Ring 1', 'Ring 2', 'Ring 3', 'Ring 4')")
            ->orderBy('provinsi')
            ->orderBy('kabupaten')
            ->orderBy('kecamatan')
            ->orderBy('desa')
            ->get();

        $provinces = DB::table('reg_provinces')->orderBy('name', 'asc')->get();

        if ($request->ajax()) {
            return view('admin.ring_wilayah.index', compact('ring_wilayah', 'provinces'));
        }

        return view('layouts.admin', [
            'page'         => 'admin.ring_wilayah.index',
            'pageTitle'    => 'Manajemen Ring Wilayah',
            'ring_wilayah' => $ring_wilayah,
            'provinces'    => $provinces,
        ]);
    }

    /**
     * Simpan ring wilayah baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ring'      => ['required', 'string', Rule::in(['Ring 1', 'Ring 2', 'Ring 3', 'Ring 4'])],
            'provinsi'  => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'desa'      => ['required', 'string', 'max:100'],
        ]);

        $ring      = trim($request->input('ring'));
        $provinsi  = trim($request->input('provinsi'));
        $kabupaten = trim($request->input('kabupaten'));
        $kecamatan = trim($request->input('kecamatan'));
        $desa      = trim($request->input('desa'));

        // Cek duplikat
        $exists = DB::table('ring_wilayah')
            ->where('ring', $ring)
            ->where('provinsi', $provinsi)
            ->where('kabupaten', $kabupaten)
            ->where('kecamatan', $kecamatan)
            ->where('desa', $desa)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => "Wilayah tersebut sudah terdaftar di {$ring}."
            ], 422);
        }

        DB::table('ring_wilayah')->insert([
            'ring'       => $ring,
            'provinsi'   => $provinsi,
            'kabupaten'  => $kabupaten,
            'kecamatan'  => $kecamatan,
            'desa'       => $desa,
            'created_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Wilayah berhasil ditambahkan ke {$ring}."
        ]);
    }

    /**
     * Ambil data detail ring wilayah (JSON).
     */
    public function show($id)
    {
        $wilayah = DB::table('ring_wilayah')->where('id', $id)->first();

        if (!$wilayah) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($wilayah);
    }

    /**
     * Update ring wilayah.
     */
    public function update(Request $request, $id)
    {
        $wilayah = DB::table('ring_wilayah')->where('id', $id)->first();

        if (!$wilayah) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $request->validate([
            'ring'      => ['required', 'string', Rule::in(['Ring 1', 'Ring 2', 'Ring 3', 'Ring 4'])],
            'provinsi'  => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'desa'      => ['required', 'string', 'max:100'],
        ]);

        $ring      = trim($request->input('ring'));
        $provinsi  = trim($request->input('provinsi'));
        $kabupaten = trim($request->input('kabupaten'));
        $kecamatan = trim($request->input('kecamatan'));
        $desa      = trim($request->input('desa'));

        // Cek duplikat (abaikan ID saat ini)
        $exists = DB::table('ring_wilayah')
            ->where('id', '!=', $id)
            ->where('ring', $ring)
            ->where('provinsi', $provinsi)
            ->where('kabupaten', $kabupaten)
            ->where('kecamatan', $kecamatan)
            ->where('desa', $desa)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => "Wilayah tersebut sudah terdaftar di {$ring}."
            ], 422);
        }

        DB::table('ring_wilayah')->where('id', $id)->update([
            'ring'      => $ring,
            'provinsi'  => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'desa'      => $desa,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data wilayah berhasil diperbarui.'
        ]);
    }

    /**
     * Hapus ring wilayah.
     */
    public function destroy($id)
    {
        $wilayah = DB::table('ring_wilayah')->where('id', $id)->first();

        if (!$wilayah) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        DB::table('ring_wilayah')->where('id', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data wilayah berhasil dihapus.'
        ]);
    }

    // ── AJAX DROPDOWN HELPERS ────────────────────────────────────────

    public function getKabupaten(Request $request)
    {
        $provId = $request->query('provinsi_id');
        $data = DB::table('reg_regencies')
            ->where('province_id', $provId)
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();
        return response()->json($data);
    }

    public function getKecamatan(Request $request)
    {
        $kabId = $request->query('kabupaten_id');
        $data = DB::table('reg_districts')
            ->where('regency_id', $kabId)
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();
        return response()->json($data);
    }

    public function getDesa(Request $request)
    {
        $kecId = $request->query('kecamatan_id');
        $data = DB::table('reg_villages')
            ->where('district_id', $kecId)
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();
        return response()->json($data);
    }
}
