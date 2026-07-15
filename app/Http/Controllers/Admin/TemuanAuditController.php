<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TemuanAuditController extends Controller
{
    /**
     * Tampilkan data temuan audit.
     */
    public function index(Request $request)
    {
        // Total counts for dashboard cards
        $cnt_all   = DB::table('temuan_audit')->count();
        $cnt_open  = DB::table('temuan_audit')->where('status', 'Open')->count();
        $cnt_close = DB::table('temuan_audit')->where('status', 'Close')->count();

        // Dropdowns data
        $companies = DB::table('users')->where('role', 'perusahaan')->orderBy('nama', 'asc')->select('id', 'nama')->get();
        
        $years = DB::table('temuan_audit')
            ->selectRaw('DISTINCT YEAR(tanggal_audit) AS tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Main Query
        $query = DB::table('temuan_audit as t')
            ->join('users as u', 't.id_perusahaan', '=', 'u.id')
            ->select('t.*', 'u.nama as nama_perusahaan');

        // Apply filters if present
        if ($request->filled('perusahaan_id')) {
            $query->where('t.id_perusahaan', $request->integer('perusahaan_id'));
        }
        if ($request->filled('tahun')) {
            $query->whereYear('t.tanggal_audit', $request->integer('tahun'));
        }
        if ($request->filled('status')) {
            $query->where('t.status', $request->input('status'));
        }

        $temuan = $query->orderBy('t.created_at', 'desc')->paginate(15, ['*'], 'p')->withQueryString();

        // Data array to pass to views
        $data = [
            'cnt_all'   => $cnt_all,
            'cnt_open'  => $cnt_open,
            'cnt_close' => $cnt_close,
            'companies' => $companies,
            'years'     => $years,
            'temuan'    => $temuan,
            'filters'   => $request->only(['perusahaan_id', 'tahun', 'status']),
        ];

        if ($request->ajax()) {
            return view('admin.temuan_audit.index', $data);
        }

        return view('layouts.admin', array_merge($data, [
            'page'      => 'admin.temuan_audit.index',
            'pageTitle' => 'Manajemen Temuan Audit',
        ]));
    }

    /**
     * Ambil data detail temuan (JSON).
     */
    public function show($id)
    {
        $audit = DB::table('temuan_audit as t')
            ->join('users as u', 't.id_perusahaan', '=', 'u.id')
            ->select('t.*', 'u.nama as nama_perusahaan')
            ->where('t.id', $id)
            ->first();

        if (!$audit) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($audit);
    }

    /**
     * Simpan temuan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_audit' => ['required', 'date'],
            'id_perusahaan' => ['required', 'integer', 'exists:users,id'],
            'temuan'        => ['required', 'string'],
        ], [
            'tanggal_audit.required' => 'Tanggal audit wajib diisi.',
            'id_perusahaan.required' => 'Perusahaan wajib dipilih.',
            'temuan.required'        => 'Uraian temuan wajib diisi.',
        ]);

        DB::table('temuan_audit')->insert([
            'tanggal_audit' => $request->input('tanggal_audit'),
            'id_perusahaan' => $request->input('id_perusahaan'),
            'temuan'        => trim($request->input('temuan')),
            'status'        => 'Open',
            'created_at'    => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Temuan audit berhasil ditambahkan.'
        ]);
    }

    /**
     * Update data temuan.
     */
    public function update(Request $request, $id)
    {
        $audit = DB::table('temuan_audit')->where('id', $id)->first();

        if (!$audit) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $request->validate([
            'tanggal_audit' => ['required', 'date'],
            'id_perusahaan' => ['required', 'integer', 'exists:users,id'],
            'temuan'        => ['required', 'string'],
            'tindak_lanjut' => ['nullable', 'string'],
            'evaluasi'      => ['nullable', 'string'],
            'status'        => ['required', Rule::in(['Open', 'Close'])],
        ]);

        DB::table('temuan_audit')->where('id', $id)->update([
            'tanggal_audit' => $request->input('tanggal_audit'),
            'id_perusahaan' => $request->input('id_perusahaan'),
            'temuan'        => trim($request->input('temuan')),
            'tindak_lanjut' => $request->input('tindak_lanjut'),
            'evaluasi'      => $request->input('evaluasi'),
            'status'        => $request->input('status'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Temuan audit berhasil diperbarui.'
        ]);
    }

    /**
     * Aksi Evaluasi & Close Temuan.
     */
    public function close(Request $request, $id)
    {
        $audit = DB::table('temuan_audit')->where('id', $id)->first();

        if (!$audit) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        if (empty($audit->tindak_lanjut)) {
            return response()->json(['message' => 'Tindak lanjut belum diisi oleh Perusahaan!'], 400);
        }

        $request->validate([
            'evaluasi' => ['required', 'string'],
        ], [
            'evaluasi.required' => 'Evaluasi wajib diisi.',
        ]);

        DB::table('temuan_audit')->where('id', $id)->update([
            'evaluasi' => trim($request->input('evaluasi')),
            'status'   => 'Close',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Temuan audit berhasil ditutup (Closed).'
        ]);
    }

    /**
     * Hapus temuan audit.
     */
    public function destroy($id)
    {
        $audit = DB::table('temuan_audit')->where('id', $id)->first();

        if (!$audit) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        DB::table('temuan_audit')->where('id', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Temuan audit berhasil dihapus.'
        ]);
    }
}
