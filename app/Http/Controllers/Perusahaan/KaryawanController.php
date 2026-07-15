<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class KaryawanController extends Controller
{
    /**
     * Tampilkan daftar karyawan vendor.
     */
    public function index(Request $request)
    {
        $perusahaan_id = session('user_id');

        $data = DB::table('karyawan as k')
            ->leftJoin('divisions as d', 'k.div_id', '=', 'd.id')
            ->leftJoin('subdivisions as s', 'k.subdiv_id', '=', 's.id')
            ->select('k.*', 'd.div_desc', 's.subdiv_desc')
            ->where('k.perusahaan_id', $perusahaan_id)
            ->orderBy('k.id', 'DESC')
            ->paginate(15, ['*'], 'p')
            ->withQueryString();

        if ($request->ajax()) {
            return view('perusahaan.karyawan.index', compact('data'));
        }

        return view('layouts.perusahaan', [
            'page' => 'perusahaan.karyawan.index',
            'pageTitle' => 'Data Karyawan',
            'data' => $data
        ]);
    }

    /**
     * Tampilkan form tambah karyawan.
     */
    public function create(Request $request)
    {
        $all_div = DB::table('divisions')->orderBy('div_desc', 'asc')->get();
        $all_subdiv = DB::table('subdivisions')->orderBy('subdiv_desc', 'asc')->get();
        $all_prov = DB::table('reg_provinces')->orderBy('name', 'asc')->get();

        if ($request->ajax()) {
            return view('perusahaan.karyawan.create', compact('all_div', 'all_subdiv', 'all_prov'));
        }

        return view('layouts.perusahaan', [
            'page' => 'perusahaan.karyawan.create',
            'pageTitle' => 'Tambah Karyawan',
            'all_div' => $all_div,
            'all_subdiv' => $all_subdiv,
            'all_prov' => $all_prov
        ]);
    }

    /**
     * Simpan data karyawan baru.
     */
    public function store(Request $request)
    {
        $perusahaan_id = session('user_id');

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50',
            'nomor_ktp' => 'required|string|size:16',
            'npwp' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'status_perkawinan' => 'required|string|max:50',
            'alamat_tinggal' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'alamat_ktp' => 'required|string',
            'mulai_masuk_kerja' => 'required|date',
            'pendidikan_terakhir' => 'required|string|max:50',
            'bpjs_kesehatan' => 'required|string|max:50',
            'bpjs_ketenagakerjaan' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'div_id' => 'required|integer',
            'subdiv_id' => 'required|integer',
            'unit' => 'required|in:UNIT 9,UNIT 12,UNIT 129',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // Cek duplikat NIK / KTP
        $existing = DB::table('karyawan as k')
            ->join('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->select('k.nama', 'u.nama as nama_perusahaan')
            ->where('k.nik', $request->nik)
            ->orWhere('k.nomor_ktp', $request->nomor_ktp)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => "Gagal menyimpan: Karyawan dengan NIK/KTP tersebut sudah terdaftar di sistem atas nama <strong>{$existing->nama}</strong> oleh vendor <strong>{$existing->nama_perusahaan}</strong>."
            ], 422);
        }

        DB::table('karyawan')->insert([
            'perusahaan_id' => $perusahaan_id,
            'nama' => trim($request->nama),
            'nik' => trim($request->nik),
            'nomor_ktp' => trim($request->nomor_ktp),
            'npwp' => trim($request->npwp),
            'no_hp' => trim($request->no_hp),
            'email' => trim($request->email),
            'jenis_kelamin' => trim($request->jenis_kelamin),
            'agama' => trim($request->agama),
            'tempat_lahir' => trim($request->tempat_lahir),
            'tanggal_lahir' => trim($request->tanggal_lahir),
            'status_perkawinan' => trim($request->status_perkawinan),
            'alamat_tinggal' => trim($request->alamat_tinggal),
            'provinsi' => trim($request->provinsi),
            'kabupaten' => trim($request->kabupaten),
            'kecamatan' => trim($request->kecamatan),
            'desa' => trim($request->desa),
            'alamat_ktp' => trim($request->alamat_ktp),
            'mulai_masuk_kerja' => trim($request->mulai_masuk_kerja),
            'pendidikan_terakhir' => trim($request->pendidikan_terakhir),
            'bpjs_kesehatan' => trim($request->bpjs_kesehatan),
            'bpjs_ketenagakerjaan' => trim($request->bpjs_ketenagakerjaan),
            'jabatan' => trim($request->jabatan),
            'div_id' => (int)$request->div_id,
            'subdiv_id' => (int)$request->subdiv_id,
            'unit' => trim($request->unit),
            'status' => trim($request->status),
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil ditambahkan.'
        ]);
    }

    /**
     * Ambil data detail karyawan (JSON).
     */
    public function show($id)
    {
        $perusahaan_id = session('user_id');
        $karyawan = DB::table('karyawan as k')
            ->leftJoin('divisions as d', 'k.div_id', '=', 'd.id')
            ->leftJoin('subdivisions as s', 'k.subdiv_id', '=', 's.id')
            ->select('k.*', 'd.div_desc', 's.subdiv_desc')
            ->where('k.id', $id)
            ->where('k.perusahaan_id', $perusahaan_id)
            ->first();

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan tidak ditemukan.'], 404);
        }

        return response()->json($karyawan);
    }

    /**
     * Tampilkan form edit karyawan.
     */
    public function edit(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $rec = DB::table('karyawan')->where('id', $id)->where('perusahaan_id', $perusahaan_id)->first();

        if (!$rec) {
            abort(404, 'Data karyawan tidak ditemukan.');
        }

        $all_div = DB::table('divisions')->orderBy('div_desc', 'asc')->get();
        $all_subdiv = DB::table('subdivisions')->orderBy('subdiv_desc', 'asc')->get();
        $all_prov = DB::table('reg_provinces')->orderBy('name', 'asc')->get();

        // Cari ID wilayah untuk dropdown selection cascading
        $prov_id = DB::table('reg_provinces')->where('name', $rec->provinsi)->value('id') ?? '';
        $kab_id = '';
        if ($prov_id) {
            $kab_id = DB::table('reg_regencies')->where('name', $rec->kabupaten)->where('province_id', $prov_id)->value('id') ?? '';
        }
        $kec_id = '';
        if ($kab_id) {
            $kec_id = DB::table('reg_districts')->where('name', $rec->kecamatan)->where('regency_id', $kab_id)->value('id') ?? '';
        }
        $desa_id = '';
        if ($kec_id) {
            $desa_id = DB::table('reg_villages')->where('name', $rec->desa)->where('district_id', $kec_id)->value('id') ?? '';
        }

        $all_kab = $prov_id ? DB::table('reg_regencies')->where('province_id', $prov_id)->orderBy('name', 'asc')->get() : collect();
        $all_kec = $kab_id ? DB::table('reg_districts')->where('regency_id', $kab_id)->orderBy('name', 'asc')->get() : collect();
        $all_desa = $kec_id ? DB::table('reg_villages')->where('district_id', $kec_id)->orderBy('name', 'asc')->get() : collect();

        if ($request->ajax()) {
            return view('perusahaan.karyawan.edit', compact(
                'rec', 'all_div', 'all_subdiv', 'all_prov',
                'prov_id', 'kab_id', 'kec_id', 'desa_id',
                'all_kab', 'all_kec', 'all_desa'
            ));
        }

        return view('layouts.perusahaan', [
            'page' => 'perusahaan.karyawan.edit',
            'pageTitle' => 'Edit Karyawan',
            'rec' => $rec,
            'all_div' => $all_div,
            'all_subdiv' => $all_subdiv,
            'all_prov' => $all_prov,
            'prov_id' => $prov_id,
            'kab_id' => $kab_id,
            'kec_id' => $kec_id,
            'desa_id' => $desa_id,
            'all_kab' => $all_kab,
            'all_kec' => $all_kec,
            'all_desa' => $all_desa
        ]);
    }

    /**
     * Update data karyawan.
     */
    public function update(Request $request, $id)
    {
        $perusahaan_id = session('user_id');

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50',
            'nomor_ktp' => 'required|string|size:16',
            'npwp' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'status_perkawinan' => 'required|string|max:50',
            'alamat_tinggal' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'alamat_ktp' => 'required|string',
            'mulai_masuk_kerja' => 'required|date',
            'pendidikan_terakhir' => 'required|string|max:50',
            'bpjs_kesehatan' => 'required|string|max:50',
            'bpjs_ketenagakerjaan' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'div_id' => 'required|integer',
            'subdiv_id' => 'required|integer',
            'unit' => 'required|in:UNIT 9,UNIT 12,UNIT 129',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // Cek duplikat NIK / KTP (kecuali karyawan ID ini)
        $existing = DB::table('karyawan as k')
            ->join('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->select('k.nama', 'u.nama as nama_perusahaan')
            ->where('k.id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->where('k.nik', $request->nik)
                      ->orWhere('k.nomor_ktp', $request->nomor_ktp);
            })
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => "Gagal memperbarui: NIK/KTP tersebut sudah terdaftar di sistem atas nama <strong>{$existing->nama}</strong> oleh vendor <strong>{$existing->nama_perusahaan}</strong>."
            ], 422);
        }

        DB::table('karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->update([
                'nama' => trim($request->nama),
                'nik' => trim($request->nik),
                'nomor_ktp' => trim($request->nomor_ktp),
                'npwp' => trim($request->npwp),
                'no_hp' => trim($request->no_hp),
                'email' => trim($request->email),
                'jenis_kelamin' => trim($request->jenis_kelamin),
                'agama' => trim($request->agama),
                'tempat_lahir' => trim($request->tempat_lahir),
                'tanggal_lahir' => trim($request->tanggal_lahir),
                'status_perkawinan' => trim($request->status_perkawinan),
                'alamat_tinggal' => trim($request->alamat_tinggal),
                'provinsi' => trim($request->provinsi),
                'kabupaten' => trim($request->kabupaten),
                'kecamatan' => trim($request->kecamatan),
                'desa' => trim($request->desa),
                'alamat_ktp' => trim($request->alamat_ktp),
                'mulai_masuk_kerja' => trim($request->mulai_masuk_kerja),
                'pendidikan_terakhir' => trim($request->pendidikan_terakhir),
                'bpjs_kesehatan' => trim($request->bpjs_kesehatan),
                'bpjs_ketenagakerjaan' => trim($request->bpjs_ketenagakerjaan),
                'jabatan' => trim($request->jabatan),
                'div_id' => (int)$request->div_id,
                'subdiv_id' => (int)$request->subdiv_id,
                'unit' => trim($request->unit),
                'status' => trim($request->status),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diperbarui.'
        ]);
    }

    /**
     * Hapus data karyawan.
     */
    public function destroy($id)
    {
        $perusahaan_id = session('user_id');

        // Cek relasi ke sertifikasi_karyawan
        $cek_sertif = DB::table('sertifikasi_karyawan')->where('karyawan_id', $id)->count();

        // Cek relasi ke kontrak_karyawan
        $cek_kontrak = DB::table('kontrak_karyawan')->where('karyawan_id', $id)->count();

        if ($cek_sertif > 0 || $cek_kontrak > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Karyawan tidak dapat dihapus karena masih memiliki data terkait (Sertifikasi/Kontrak Kerja).'
            ], 422);
        }

        DB::table('karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil dihapus.'
        ]);
    }

    /**
     * Toggle status karyawan (Aktif ↔ Nonaktif).
     */
    public function toggleStatus(Request $request, $id)
    {
        $perusahaan_id = session('user_id');
        $new_status = $request->input('new_status');

        if (!in_array($new_status, ['Aktif', 'Nonaktif'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Status tidak valid.'
            ], 422);
        }

        DB::table('karyawan')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan_id)
            ->update([
                'status' => $new_status
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status karyawan berhasil diperbarui.'
        ]);
    }

    /**
     * Export daftar karyawan ke Excel (HTML Table format).
     */
    public function export(Request $request)
    {
        $perusahaan_id = session('user_id');

        // Parameter filter
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', '');
        $unit = $request->query('unit', '');
        $jk = $request->query('jk', '');

        $query = DB::table('karyawan as k')
            ->leftJoin('divisions as d', 'k.div_id', '=', 'd.id')
            ->leftJoin('subdivisions as s', 'k.subdiv_id', '=', 's.id')
            ->select('k.*', 'd.div_desc', 's.subdiv_desc')
            ->where('k.perusahaan_id', $perusahaan_id);

        if ($keyword !== '') {
            $query->where(function($q) use ($keyword) {
                $q->where('k.nama', 'LIKE', "%{$keyword}%")
                  ->orWhere('k.nik', 'LIKE', "%{$keyword}%")
                  ->orWhere('k.jabatan', 'LIKE', "%{$keyword}%");
            });
        }
        if ($status !== '') {
            $query->where('k.status', $status);
        }
        if ($unit !== '') {
            $query->where('k.unit', $unit);
        }
        if ($jk !== '') {
            $query->where('k.jenis_kelamin', $jk);
        }

        $rows = $query->orderBy('k.id', 'DESC')->get();

        $filename = "Data_Karyawan_" . date('Ymd_His') . ".xls";

        $output = view('perusahaan.exports.karyawan', compact('rows'))->render();

        return response($output)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Download template import Excel.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();

        // SHEET 1: Template Import
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Lengkap *',
            'C1' => 'NIK (Nomor Induk Karyawan) *',
            'D1' => 'Nomor KTP (16 digit) *',
            'E1' => 'NPWP',
            'F1' => 'No HP *',
            'G1' => 'Email *',
            'H1' => 'Jenis Kelamin *',
            'I1' => 'Agama *',
            'J1' => 'Tempat Lahir *',
            'K1' => 'Tanggal Lahir (YYYY-MM-DD) *',
            'L1' => 'Status Perkawinan *',
            'M1' => 'Mulai Masuk Kerja (YYYY-MM-DD) *',
            'N1' => 'Pendidikan Terakhir *',
            'O1' => 'BPJS Kesehatan *',
            'P1' => 'BPJS Ketenagakerjaan *',
            'Q1' => 'Jabatan *',
            'R1' => 'Unit *',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ];
        $sheet->getStyle('A1:R1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(35);

        $widths = ['A'=>5,'B'=>25,'C'=>22,'D'=>20,'E'=>22,'F'=>16,'G'=>22,'H'=>18,'I'=>22,'J'=>16,'K'=>22,'L'=>20,'M'=>25,'N'=>22,'O'=>20,'P'=>22,'Q'=>18,'R'=>20];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $sampleData = ['A2'=>1,'B2'=>'Contoh Nama','C2'=>'030.06.15','D2'=>'3501234567890001','E2'=>'12.345.678.9-012.000','F2'=>'085233223872','G2'=>'tesemail@email.com','H2'=>'L','I2'=>'Islam','J2'=>'Surabaya','K2'=>'1990-05-15','L2'=>'Kawin','M2'=>'2024-01-10','N2'=>'S3','O2'=>'0001234567890','P2'=>'0001234567890','Q2'=>'Helper','R2'=>'UNIT 9'];
        foreach ($sampleData as $c => $v) {
            $sheet->setCellValueExplicit($c, (string)$v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
        $sheet->setCellValueExplicit('A2', 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

        $sheet->getStyle('A2:R2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '6c757d']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'cccccc']]],
        ]);

        foreach (['D','F','O','P'] as $col) {
            $sheet->getStyle($col.'2:'.$col.'200')->getNumberFormat()->setFormatCode('@');
        }

        // SHEET 2: Pilihan Dropdown
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Pilihan Dropdown');

        $refHeaders = ['A1'=>'Jenis Kelamin','B1'=>'Agama','C1'=>'Status Perkawinan','D1'=>'Pendidikan Terakhir','E1'=>'Unit'];
        foreach ($refHeaders as $c => $v) {
            $refSheet->setCellValue($c, $v);
        }
        $refSheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $lists = [
            'A' => ['L', 'P'],
            'B' => ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'],
            'C' => ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'],
            'D' => ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'],
            'E' => ['UNIT 9', 'UNIT 12', 'UNIT 129'],
        ];
        foreach ($lists as $col => $items) {
            foreach ($items as $i => $val) {
                $refSheet->setCellValue($col . ($i + 2), $val);
            }
        }

        foreach (['A'=>16,'B'=>22,'C'=>20,'D'=>20,'E'=>16] as $c => $w) {
            $refSheet->getColumnDimension($c)->setWidth($w);
        }
        $refSheet->getStyle('A2:E10')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'cccccc']]],
            'font' => ['size' => 11],
        ]);

        $refSheet->setCellValue('A12', '→ Salin nilai di atas ke kolom yang sesuai di sheet "Template Import"');
        $refSheet->getStyle('A12')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF6600'));
        $refSheet->mergeCells('A12:E12');

        // SHEET 3: Petunjuk Pengisian
        $petunjuk = $spreadsheet->createSheet();
        $petunjuk->setTitle('Petunjuk Pengisian');

        $petunjuk->setCellValue('A1', 'PETUNJUK PENGISIAN TEMPLATE IMPORT KARYAWAN');
        $petunjuk->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $petunjuk->mergeCells('A1:D1');

        $instructions = [
            ['A3','1.','Isi data karyawan pada sheet "Template Import"'],
            ['A4','2.','Kolom bertanda (*) wajib diisi'],
            ['A5','3.','Nomor KTP harus 16 digit angka'],
            ['A6','4.','Format tanggal: YYYY-MM-DD (contoh: 1990-05-15)'],
            ['A7','5.','Lihat sheet "Pilihan Dropdown" untuk pilihan kolom: Jenis Kelamin, Agama, Status Perkawinan, Pendidikan, Unit'],
            ['A8','6.','Baris contoh (baris 2) bisa dihapus atau ditimpa'],
            ['A9','7.','Maksimal 50 baris data per upload'],
            ['A10',''],
            ['A11','','KOLOM YANG TIDAK ADA DI TEMPLATE (akan diisi manual saat Review):'],
            ['A12','','- Alamat Tinggal, Provinsi, Kabupaten, Kecamatan, Desa'],
            ['A13','','- Alamat KTP'],
            ['A14','','- Divisi, Sub Divisi'],
        ];
        foreach ($instructions as $row) {
            $petunjuk->setCellValue($row[0], $row[1]);
            if (isset($row[2])) {
                $petunjuk->setCellValue('B'.substr($row[0],1), $row[2]);
            }
        }
        $petunjuk->getStyle('A11:B11')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000'));
        $petunjuk->getColumnDimension('A')->setWidth(5);
        $petunjuk->getColumnDimension('B')->setWidth(70);

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        
        $filename = 'Template_Import_Karyawan.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    /**
     * Preview / upload spreadsheet import data.
     */
    public function importReview(Request $request)
    {
        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $file = $request->file('file_import');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows  = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return back()->with('import_error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $valid_jk        = ['L', 'P'];
        $valid_agama     = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'];
        $valid_kawin     = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
        $valid_pendidikan = ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'];
        $valid_unit      = ['UNIT 9', 'UNIT 12', 'UNIT 129'];

        $data   = [];
        $errors = [];
        $rowNum = 0;

        foreach ($rows as $i => $row) {
            if ($i === 1) continue; // skip header

            $nama = trim($row['B'] ?? '');
            if ($nama === '') continue; // skip empty rows

            $rowNum++;

            $nik          = trim($row['C'] ?? '');
            $nomor_ktp    = trim($row['D'] ?? '');
            $npwp         = trim($row['E'] ?? '');
            $no_hp        = trim($row['F'] ?? '');
            $email        = trim($row['G'] ?? '');
            $jk           = strtoupper(trim($row['H'] ?? ''));
            $agama        = trim($row['I'] ?? '');
            $tempat_lahir = trim($row['J'] ?? '');
            $tgl_lahir    = trim($row['K'] ?? '');
            $status_kawin = trim($row['L'] ?? '');
            $mulai_kerja  = trim($row['M'] ?? '');
            $pendidikan   = trim($row['N'] ?? '');
            $bpjs_kes     = trim($row['O'] ?? '');
            $bpjs_tk      = trim($row['P'] ?? '');
            $jabatan      = trim($row['Q'] ?? '');
            $unit         = strtoupper(trim($row['R'] ?? ''));

            $rowErrors = [];

            if ($nama === '')          $rowErrors[] = 'Nama kosong';
            if ($nik === '')           $rowErrors[] = 'NIK kosong';
            if ($nomor_ktp === '')     $rowErrors[] = 'Nomor KTP kosong';
            if ($no_hp === '')         $rowErrors[] = 'No HP kosong';
            if ($email === '')         $rowErrors[] = 'Email kosong';
            if ($jk === '')            $rowErrors[] = 'Jenis Kelamin kosong';
            if ($agama === '')         $rowErrors[] = 'Agama kosong';
            if ($tempat_lahir === '')  $rowErrors[] = 'Tempat Lahir kosong';
            if ($tgl_lahir === '')     $rowErrors[] = 'Tanggal Lahir kosong';
            if ($status_kawin === '')  $rowErrors[] = 'Status Perkawinan kosong';
            if ($mulai_kerja === '')   $rowErrors[] = 'Mulai Masuk Kerja kosong';
            if ($pendidikan === '')    $rowErrors[] = 'Pendidikan Terakhir kosong';
            if ($bpjs_kes === '')      $rowErrors[] = 'BPJS Kesehatan kosong';
            if ($bpjs_tk === '')       $rowErrors[] = 'BPJS Ketenagakerjaan kosong';
            if ($jabatan === '')       $rowErrors[] = 'Jabatan kosong';
            if ($unit === '')          $rowErrors[] = 'Unit kosong';

            if ($nomor_ktp !== '' && (strlen($nomor_ktp) !== 16 || !ctype_digit($nomor_ktp))) {
                $rowErrors[] = 'Nomor KTP harus 16 digit angka';
            }

            if ($jk !== '' && !in_array($jk, $valid_jk)) {
                $rowErrors[] = 'Jenis Kelamin harus L atau P';
            }
            if ($agama !== '' && !in_array($agama, $valid_agama)) {
                $rowErrors[] = "Agama '$agama' tidak valid";
            }
            if ($status_kawin !== '' && !in_array($status_kawin, $valid_kawin)) {
                $rowErrors[] = "Status Perkawinan '$status_kawin' tidak valid";
            }
            if ($pendidikan !== '' && !in_array($pendidikan, $valid_pendidikan)) {
                $rowErrors[] = "Pendidikan '$pendidikan' tidak valid";
            }
            if ($unit !== '' && !in_array($unit, $valid_unit)) {
                $rowErrors[] = "Unit '$unit' tidak valid";
            }

            // Tanggal lahir
            if ($tgl_lahir !== '') {
                if (is_numeric($tgl_lahir)) {
                    try {
                        $dateObj = ExcelDate::excelToDateTimeObject($tgl_lahir);
                        $tgl_lahir = $dateObj->format('Y-m-d');
                    } catch (\Exception $e) {
                        $rowErrors[] = 'Format tanggal lahir tidak valid';
                    }
                } else {
                    $d = date_create($tgl_lahir);
                    if ($d) {
                        $tgl_lahir = $d->format('Y-m-d');
                    } else {
                        $rowErrors[] = 'Format tanggal lahir tidak valid (gunakan YYYY-MM-DD)';
                    }
                }
            }

            // Mulai masuk kerja
            if ($mulai_kerja !== '') {
                if (is_numeric($mulai_kerja)) {
                    try {
                        $dateObj = ExcelDate::excelToDateTimeObject($mulai_kerja);
                        $mulai_kerja = $dateObj->format('Y-m-d');
                    } catch (\Exception $e) {
                        $rowErrors[] = 'Format mulai masuk kerja tidak valid';
                    }
                } else {
                    $d = date_create($mulai_kerja);
                    if ($d) {
                        $mulai_kerja = $d->format('Y-m-d');
                    } else {
                        $rowErrors[] = 'Format mulai masuk kerja tidak valid (gunakan YYYY-MM-DD)';
                    }
                }
            }

            if (!empty($rowErrors)) {
                $errors[] = "Baris $rowNum ($nama): " . implode(', ', $rowErrors);
            }

            $data[] = [
                'nama'              => $nama,
                'nik'               => $nik,
                'nomor_ktp'         => $nomor_ktp,
                'npwp'              => $npwp,
                'no_hp'             => $no_hp,
                'email'             => $email,
                'jenis_kelamin'     => $jk,
                'agama'             => $agama,
                'tempat_lahir'      => $tempat_lahir,
                'tanggal_lahir'     => $tgl_lahir,
                'status_perkawinan' => $status_kawin,
                'mulai_masuk_kerja' => $mulai_kerja,
                'pendidikan_terakhir' => $pendidikan,
                'bpjs_kesehatan'    => $bpjs_kes,
                'bpjs_ketenagakerjaan' => $bpjs_tk,
                'jabatan'           => $jabatan,
                'unit'              => $unit,
            ];
        }

        if (empty($data)) {
            return back()->with('import_error', 'File Excel tidak berisi data. Pastikan data diisi mulai baris ke-2.');
        }

        if (!empty($errors)) {
            return back()->with('import_error', "Terdapat kesalahan pada data:\n" . implode("\n", $errors));
        }

        if (count($data) > 50) {
            return back()->with('import_error', 'Maksimal 50 baris data per upload. File Anda berisi ' . count($data) . ' baris.');
        }

        session(['import_data' => $data]);

        // Kirim response review page ke #page-content (AJAX request)
        $all_div = DB::table('divisions')->orderBy('div_desc', 'asc')->get();
        $all_subdiv = DB::table('subdivisions')->orderBy('subdiv_desc', 'asc')->get();
        $all_prov = DB::table('reg_provinces')->orderBy('name', 'asc')->get();

        if ($request->ajax()) {
            return view('perusahaan.karyawan.review', [
                'import_data' => $data,
                'all_div' => $all_div,
                'all_subdiv' => $all_subdiv,
                'all_prov' => $all_prov
            ]);
        }

        return view('layouts.perusahaan', [
            'page' => 'perusahaan.karyawan.review',
            'pageTitle' => 'Review Import Karyawan',
            'import_data' => $data,
            'all_div' => $all_div,
            'all_subdiv' => $all_subdiv,
            'all_prov' => $all_prov
        ]);
    }

    /**
     * Simpan hasil review import ke DB.
     */
    public function importStore(Request $request)
    {
        $perusahaan_id = session('user_id');
        $rows = $request->input('rows', []);

        if (empty($rows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data untuk disimpan.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $save_count = 0;
            foreach ($rows as $idx => $row) {
                $nama = trim($row['nama'] ?? '');
                $nik = trim($row['nik'] ?? '');
                $nomor_ktp = trim($row['nomor_ktp'] ?? '');
                $npwp = trim($row['npwp'] ?? '');
                $no_hp = trim($row['no_hp'] ?? '');
                $email = trim($row['email'] ?? '');
                $jenis_kelamin = trim($row['jenis_kelamin'] ?? '');
                $agama = trim($row['agama'] ?? '');
                $tempat_lahir = trim($row['tempat_lahir'] ?? '');
                $tanggal_lahir = trim($row['tanggal_lahir'] ?? '');
                $status_perkawinan = trim($row['status_perkawinan'] ?? '');
                $alamat_tinggal = trim($row['alamat_tinggal'] ?? '');
                $provinsi = trim($row['provinsi'] ?? '');
                $kabupaten = trim($row['kabupaten'] ?? '');
                $kecamatan = trim($row['kecamatan'] ?? '');
                $desa = trim($row['desa'] ?? '');
                $alamat_ktp = trim($row['alamat_ktp'] ?? '');
                $mulai_masuk_kerja = trim($row['mulai_masuk_kerja'] ?? '');
                $pendidikan_terakhir = trim($row['pendidikan_terakhir'] ?? '');
                $bpjs_kesehatan = trim($row['bpjs_kesehatan'] ?? '');
                $bpjs_ketenagakerjaan = trim($row['bpjs_ketenagakerjaan'] ?? '');
                $jabatan = trim($row['jabatan'] ?? '');
                $div_id = (int)($row['div_id'] ?? 0);
                $subdiv_id = (int)($row['subdiv_id'] ?? 0);
                $unit = trim($row['unit'] ?? '');

                if ($alamat_tinggal === '' || $provinsi === '' || $kabupaten === '' || 
                    $kecamatan === '' || $desa === '' || $alamat_ktp === '' || 
                    $div_id <= 0 || $subdiv_id <= 0) {
                    $baris = $idx + 1;
                    throw new \Exception("Baris $baris ({$nama}): Alamat Tinggal, Provinsi, Kabupaten, Kecamatan, Desa, Alamat KTP, Divisi dan Sub Divisi wajib diisi.");
                }

                // Cek duplikat NIK / KTP
                $existing = DB::table('karyawan as k')
                    ->join('users as u', 'k.perusahaan_id', '=', 'u.id')
                    ->select('k.nama', 'u.nama as nama_perusahaan')
                    ->where('k.nik', $nik)
                    ->orWhere('k.nomor_ktp', $nomor_ktp)
                    ->first();

                if ($existing) {
                    $baris = $idx + 1;
                    throw new \Exception("Baris $baris ({$nama}): NIK/KTP sudah terdaftar di sistem atas nama <strong>{$existing->nama}</strong> oleh vendor <strong>{$existing->nama_perusahaan}</strong>.");
                }

                DB::table('karyawan')->insert([
                    'perusahaan_id' => $perusahaan_id,
                    'nama' => $nama,
                    'nik' => $nik,
                    'nomor_ktp' => $nomor_ktp,
                    'npwp' => $npwp,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'jenis_kelamin' => $jenis_kelamin,
                    'agama' => $agama,
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'status_perkawinan' => $status_perkawinan,
                    'alamat_tinggal' => $alamat_tinggal,
                    'provinsi' => $provinsi,
                    'kabupaten' => $kabupaten,
                    'kecamatan' => $kecamatan,
                    'desa' => $desa,
                    'alamat_ktp' => $alamat_ktp,
                    'mulai_masuk_kerja' => $mulai_masuk_kerja,
                    'pendidikan_terakhir' => $pendidikan_terakhir,
                    'bpjs_kesehatan' => $bpjs_kesehatan,
                    'bpjs_ketenagakerjaan' => $bpjs_ketenagakerjaan,
                    'jabatan' => $jabatan,
                    'div_id' => $div_id,
                    'subdiv_id' => $subdiv_id,
                    'unit' => $unit,
                    'status' => 'Aktif',
                    'created_at' => now(),
                ]);
                $save_count++;
            }

            DB::commit();
            session()->forget('import_data');

            return response()->json([
                'status' => 'success',
                'message' => "$save_count data karyawan berhasil diimport."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
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
