<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanTenagaKerjaController extends Controller
{
    public function index() { return 'Laporan Tenaga Kerja Index'; }
    public function create() { return 'Laporan Tenaga Kerja Create'; }
    public function store(Request $request) { return 'Laporan Tenaga Kerja Store'; }
    public function show($id) { return 'Laporan Tenaga Kerja Show'; }
    public function edit($id) { return 'Laporan Tenaga Kerja Edit'; }
    public function update(Request $request, $id) { return 'Laporan Tenaga Kerja Update'; }
    public function destroy($id) { return 'Laporan Tenaga Kerja Destroy'; }
    public function upload(Request $request, $id) { return 'Laporan Tenaga Kerja Upload'; }
}
