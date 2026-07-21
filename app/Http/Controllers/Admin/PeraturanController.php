<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeraturanController extends Controller
{
    public function index(Request $request)
    {
        $tab           = $request->query('tab', 'semua');
        $search        = trim($request->query('search', ''));
        $status        = trim($request->query('status', 'aktif'));
        $perusahaan_id = $request->query('perusahaan_id', '');

        if (!in_array($tab, ['semua', 'PP', 'PKB'])) {
            $tab = 'semua';
        }

        $perusahaanOptions = DB::table('users')
            ->whereIn('id', function ($q) {
                $q->select('perusahaan_id')->from('peraturan_perusahaan');
            })
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $query = DB::table('peraturan_perusahaan as p')
            ->join('users as u', 'p.perusahaan_id', '=', 'u.id')
            ->select('p.*', 'u.nama as nama_perusahaan');

        if ($tab === 'PP') {
            $query->where('p.jenis', 'PP');
        } elseif ($tab === 'PKB') {
            $query->where('p.jenis', 'PKB');
        }

        if ($perusahaan_id) {
            $query->where('p.perusahaan_id', $perusahaan_id);
        }

        if ($search) {
            $query->where('p.nomor', 'like', "%{$search}%");
        }

        if ($status === 'aktif') {
            $query->where('p.is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('p.is_active', false);
        }

        $data = $query->orderByDesc('p.created_at')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        $params = compact('data', 'tab', 'search', 'status', 'perusahaan_id', 'perusahaanOptions');

        if ($request->query('partial') === 'table') {
            return view('admin.peraturan.partials.table', $params)->render();
        }

        if ($request->ajax()) {
            return view('admin.peraturan.index', $params);
        }

        return view('layouts.admin', array_merge($params, [
            'page'      => 'admin.peraturan.index',
            'pageTitle' => 'Peraturan Perusahaan',
        ]));
    }
}
