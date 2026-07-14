<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformasiController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('informasi')
            ->orderBy('created_at', 'desc')
            ->get();

        $view = 'perusahaan.informasi.index';
        $params = ['data' => $data];

        if ($request->ajax()) {
            return view($view, $params);
        }

        return view('layouts.perusahaan', array_merge($params, [
            'page' => $view,
            'pageTitle' => 'Informasi',
        ]));
    }
}
