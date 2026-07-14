<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\InformasiController;

use App\Http\Controllers\Admin\RingWilayahController;
use App\Http\Controllers\Admin\TemuanAuditController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// ─── Root redirect ───────────────────────────────────────────────────────────
Route::get('/', function () {
    if (session('logged_in') && session('user_id')) {
        return match(strtolower(trim(session('role', '')))) {
            'admin'      => redirect()->route('admin.dashboard'),
            'perusahaan' => redirect()->route('perusahaan.dashboard'),
            default      => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ─── Admin area ──────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users CRUD
    Route::resource('users', UsersController::class);

    // Informasi CRUD
    Route::resource('informasi', InformasiController::class);

    // Ring Wilayah CRUD & AJAX dropdown helpers
    Route::get('/ring-wilayah/kabupaten', [RingWilayahController::class, 'getKabupaten'])->name('ring-wilayah.kabupaten');
    Route::get('/ring-wilayah/kecamatan', [RingWilayahController::class, 'getKecamatan'])->name('ring-wilayah.kecamatan');
    Route::get('/ring-wilayah/desa',      [RingWilayahController::class, 'getDesa'])->name('ring-wilayah.desa');
    Route::resource('ring-wilayah',        RingWilayahController::class);

    // Temuan Audit CRUD & close action
    Route::post('/temuan-audit/{id}/close', [TemuanAuditController::class, 'close'])->name('temuan-audit.close');
    Route::resource('temuan-audit',        TemuanAuditController::class);

    // Laporan exports
    Route::get('/laporan/export_ring_detail.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportRingDetail']);
    Route::get('/laporan/export_ring_wilayah.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportRingWilayah']);
    Route::get('/laporan/export_karyawan_kontrak.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportKaryawanKontrak']);
    Route::get('/laporan/export_karyawan.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportKaryawan']);
    Route::get('/laporan/export_sertifikasi.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportSertifikasi']);
    Route::get('/laporan/export_tenaga_kerja.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportTenagaKerja']);
    Route::get('/laporan/export_kontrak.php', [\App\Http\Controllers\Admin\LaporanExportController::class, 'exportKontrak']);

    // ── Profile ──
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // ── Modul yang akan ditambah di Modul 3+ ──
    // dst...
});

// ─── Perusahaan Area ─────────────────────────────────────────────────────
Route::prefix('perusahaan')->middleware('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Perusahaan\DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Perusahaan\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Perusahaan\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\Perusahaan\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Temuan Audit
    Route::get('/temuan-audit', [App\Http\Controllers\Perusahaan\TemuanAuditController::class, 'index'])->name('temuan-audit.index');
    Route::get('/temuan-audit/{id}/tindak-lanjut', [App\Http\Controllers\Perusahaan\TemuanAuditController::class, 'tindakLanjut'])->name('temuan-audit.tindak-lanjut');
    Route::post('/temuan-audit/{id}/tindak-lanjut', [App\Http\Controllers\Perusahaan\TemuanAuditController::class, 'storeTindakLanjut'])->name('temuan-audit.tindak-lanjut.store');

    // Data Karyawan CRUD
    Route::get('/karyawan/export', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'export'])->name('karyawan.export');
    Route::get('/karyawan/download-template', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'downloadTemplate'])->name('karyawan.download-template');
    Route::post('/karyawan/import-review', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'importReview'])->name('karyawan.import-review');
    Route::post('/karyawan/import-store', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'importStore'])->name('karyawan.import-store');
    Route::post('/karyawan/{id}/toggle-status', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'toggleStatus'])->name('karyawan.toggle-status');
    Route::get('/karyawan/kabupaten', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'getKabupaten'])->name('karyawan.kabupaten');
    Route::get('/karyawan/kecamatan', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'getKecamatan'])->name('karyawan.kecamatan');
    Route::get('/karyawan/desa', [App\Http\Controllers\Perusahaan\KaryawanController::class, 'getDesa'])->name('karyawan.desa');
    Route::resource('karyawan', App\Http\Controllers\Perusahaan\KaryawanController::class);

    // Kontrak Kerja CRUD
    Route::post('/kontrak-kerja/{id}/upload', [App\Http\Controllers\Perusahaan\KontrakKerjaController::class, 'upload'])->name('kontrak-kerja.upload');
    Route::get('/kontrak-kerja/{id}/karyawan', [App\Http\Controllers\Perusahaan\KontrakKerjaController::class, 'karyawan'])->name('kontrak-kerja.karyawan');
    Route::post('/kontrak-kerja/{id}/karyawan', [App\Http\Controllers\Perusahaan\KontrakKerjaController::class, 'storeKaryawan'])->name('kontrak-kerja.karyawan.store');
    Route::resource('kontrak-kerja', App\Http\Controllers\Perusahaan\KontrakKerjaController::class);

    // Data Sertifikasi CRUD
    Route::post('/sertifikasi/{id}/upload', [App\Http\Controllers\Perusahaan\SertifikasiController::class, 'upload'])->name('sertifikasi.upload');
    Route::resource('sertifikasi', App\Http\Controllers\Perusahaan\SertifikasiController::class);

    // Laporan Tenaga Kerja CRUD
    Route::post('/laporan-tenaga-kerja/{id}/upload', [App\Http\Controllers\Perusahaan\LaporanTenagaKerjaController::class, 'upload'])->name('laporan-tenaga-kerja.upload');
    Route::resource('laporan-tenaga-kerja', App\Http\Controllers\Perusahaan\LaporanTenagaKerjaController::class);

    // Informasi
    Route::resource('informasi', App\Http\Controllers\Perusahaan\InformasiController::class)->only(['index', 'show']);
});
