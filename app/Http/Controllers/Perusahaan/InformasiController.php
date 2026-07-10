<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index() { return 'Informasi Index'; }
    public function show($id) { return 'Informasi Show'; }
}
