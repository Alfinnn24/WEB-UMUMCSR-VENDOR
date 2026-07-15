<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TemuanAuditController extends Controller
{
    public function index(Request $request)
    {
        $id_perusahaan = (int) session('user_id');
        $filter_tahun = (int) $request->get('tahun', 0);

        // Fetch distinct audit years
        $all_tahun = DB::table('temuan_audit')
            ->where('id_perusahaan', $id_perusahaan)
            ->selectRaw('DISTINCT YEAR(tanggal_audit) AS tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        $query = DB::table('temuan_audit as t')
            ->join('users as u', 't.id_perusahaan', '=', 'u.id')
            ->where('t.id_perusahaan', $id_perusahaan)
            ->select('t.*', 'u.nama as nama_perusahaan');

        if ($filter_tahun > 0) {
            $query->whereYear('t.tanggal_audit', $filter_tahun);
        }

        $result = $query->orderByDesc('t.created_at')->paginate(15, ['*'], 'p')->withQueryString();

        $view = 'perusahaan.temuan_audit.index';
        $data = [
            'result'       => $result,
            'all_tahun'    => $all_tahun,
            'filter_tahun' => $filter_tahun,
        ];

        if ($request->ajax()) {
            return view($view, $data);
        }

        return view('layouts.perusahaan', array_merge($data, [
            'page'      => $view,
            'pageTitle' => 'Temuan Audit',
        ]));
    }

    public function tindakLanjut(Request $request, $id)
    {
        $id_perusahaan = (int) session('user_id');
        
        $data = DB::table('temuan_audit')
            ->where('id', $id)
            ->where('id_perusahaan', $id_perusahaan)
            ->first();

        if (!$data) {
            return $request->ajax()
                ? response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses!'], 404)
                : redirect()->route('perusahaan.temuan-audit.index')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses!');
        }

        if ($data->status === 'Close') {
            return $request->ajax()
                ? response()->json(['status' => 'error', 'message' => 'Temuan audit ini sudah ditutup, tidak dapat diubah lagi!'], 400)
                : redirect()->route('perusahaan.temuan-audit.index')->with('error', 'Temuan audit ini sudah ditutup, tidak dapat diubah lagi!');
        }

        $view = 'perusahaan.temuan_audit.tindak_lanjut';
        $viewData = ['data' => $data];

        if ($request->ajax()) {
            return view($view, $viewData);
        }

        return view('layouts.perusahaan', array_merge($viewData, [
            'page'      => $view,
            'pageTitle' => 'Isi Tindak Lanjut',
        ]));
    }

    public function storeTindakLanjut(Request $request, $id)
    {
        $id_perusahaan = (int) session('user_id');

        $data = DB::table('temuan_audit')
            ->where('id', $id)
            ->where('id_perusahaan', $id_perusahaan)
            ->first();

        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan atau akses ditolak.'], 404);
        }

        if ($data->status === 'Close') {
            return response()->json(['status' => 'error', 'message' => 'Temuan audit ini sudah ditutup.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'tindak_lanjut' => 'required|string',
        ], [
            'tindak_lanjut.required' => 'Tindak lanjut harus diisi!',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        DB::table('temuan_audit')
            ->where('id', $id)
            ->where('id_perusahaan', $id_perusahaan)
            ->update([
                'tindak_lanjut' => $request->tindak_lanjut,
                'updated_at'    => now(),
            ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tindak lanjut berhasil disimpan.',
            'redirect' => route('perusahaan.temuan-audit.index')
        ]);
    }
}
