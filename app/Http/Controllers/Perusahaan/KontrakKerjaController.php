<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KontrakKerjaController extends Controller
{
    public function index() { return 'Kontrak Kerja Index'; }
    public function create() { return 'Kontrak Kerja Create'; }
    public function store(Request $request) { return 'Kontrak Kerja Store'; }
    public function show($id) { return 'Kontrak Kerja Show'; }
    public function edit($id) { return 'Kontrak Kerja Edit'; }
    public function update(Request $request, $id) { return 'Kontrak Kerja Update'; }
    public function destroy($id) { return 'Kontrak Kerja Destroy'; }
    public function upload(Request $request, $id) { return 'Kontrak Kerja Upload'; }
}
