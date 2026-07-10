<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SertifikasiController extends Controller
{
    public function index() { return 'Sertifikasi Index'; }
    public function create() { return 'Sertifikasi Create'; }
    public function store(Request $request) { return 'Sertifikasi Store'; }
    public function show($id) { return 'Sertifikasi Show'; }
    public function edit($id) { return 'Sertifikasi Edit'; }
    public function update(Request $request, $id) { return 'Sertifikasi Update'; }
    public function destroy($id) { return 'Sertifikasi Destroy'; }
    public function upload(Request $request, $id) { return 'Sertifikasi Upload'; }
}
