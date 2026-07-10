<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data  = $this->getDashboardData();
        $view  = 'perusahaan.dashboard';
        $title = 'Dashboard';

        if ($request->ajax()) {
            return view($view, $data);
        }

        return view('layouts.perusahaan', array_merge($data, [
            'page'      => $view,
            'pageTitle' => $title,
        ]));
    }

    // ══════════════════════════════════════════════════════════════
    //  DASHBOARD DATA — Persis seperti perusahaan/home.php
    // ══════════════════════════════════════════════════════════════
    private function getDashboardData(): array
    {
        $perusahaan_id = (int) session('user_id');
        $today  = now()->toDateString();
        $tgl_30 = now()->addDays(30)->toDateString();

        // ── STATISTIK KARYAWAN ────────────────────────────────────
        $total_karyawan = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)->count();

        $total_aktif = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('status', 'Aktif')->count();

        $total_nonaktif = $total_karyawan - $total_aktif;

        $gender = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->selectRaw("
                SUM(jenis_kelamin = 'L') as laki,
                SUM(jenis_kelamin = 'P') as perempuan
            ")->first();

        $total_laki      = (int) ($gender->laki ?? 0);
        $total_perempuan = (int) ($gender->perempuan ?? 0);

        // ── STATISTIK SERTIFIKASI ─────────────────────────────────
        $total_sertif = DB::table('sertifikasi_karyawan')
            ->where('perusahaan_id', $perusahaan_id)->count();

        $sertif_aktif = DB::table('sertifikasi_karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('tanggal_expired', '>', $today)->count();

        $sertif_hampir = DB::table('sertifikasi_karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('tanggal_expired', '>', $today)
            ->where('tanggal_expired', '<=', $tgl_30)->count();

        $sertif_expired = DB::table('sertifikasi_karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('tanggal_expired', '<=', $today)->count();

        $sertif_no_file = DB::table('sertifikasi_karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where(function ($q) {
                $q->whereNull('file_sertifikat')
                  ->orWhere('file_sertifikat', '');
            })->count();

        // ── TEMUAN AUDIT OPEN ─────────────────────────────────────
        $temuan_open = DB::table('temuan_audit')
            ->where('id_perusahaan', $perusahaan_id)
            ->where('status', 'Open')->count();

        // ── DISTRIBUSI DIVISI ─────────────────────────────────────
        $distribusi_divisi = DB::table('karyawan as k')
            ->join('divisions as d', 'k.div_id', '=', 'd.id')
            ->where('k.perusahaan_id', $perusahaan_id)
            ->where('k.status', 'Aktif')
            ->selectRaw('d.div_desc, COUNT(*) as jumlah')
            ->groupBy('k.div_id', 'd.div_desc')
            ->orderByDesc('jumlah')
            ->get();

        // ── DISTRIBUSI UNIT ───────────────────────────────────────
        $distribusi_unit = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('status', 'Aktif')
            ->selectRaw('unit, COUNT(*) as jumlah')
            ->groupBy('unit')
            ->orderByDesc('jumlah')
            ->get();

        // ── DISTRIBUSI PENDIDIKAN ─────────────────────────────────
        $distribusi_pendidikan = DB::table('karyawan')
            ->where('perusahaan_id', $perusahaan_id)
            ->selectRaw('pendidikan_terakhir, COUNT(*) as jumlah')
            ->groupBy('pendidikan_terakhir')
            ->orderByDesc('jumlah')
            ->get();

        // ── SERTIFIKASI HAMPIR EXPIRED (detail list, maks 8) ──────
        $alert_sertifikasi = DB::table('sertifikasi_karyawan as s')
            ->join('karyawan as k', 's.karyawan_id', '=', 'k.id')
            ->where('s.perusahaan_id', $perusahaan_id)
            ->where('s.tanggal_expired', '<=', $tgl_30)
            ->select(
                's.id', 's.nama_sertifikasi', 's.tanggal_expired',
                's.lembaga_sertifikasi', 's.file_sertifikat',
                'k.nama as nama_karyawan', 'k.jabatan',
                DB::raw("DATEDIFF(s.tanggal_expired, '$today') AS sisa_hari")
            )
            ->orderBy('s.tanggal_expired')
            ->limit(8)
            ->get();

        // ── KARYAWAN TERBARU (3 terakhir) ─────────────────────────
        $karyawan_baru = DB::table('karyawan as k')
            ->leftJoin('divisions as d', 'k.div_id', '=', 'd.id')
            ->where('k.perusahaan_id', $perusahaan_id)
            ->select(
                'k.id', 'k.nama', 'k.nik', 'k.jabatan', 'k.unit',
                'k.status', 'k.jenis_kelamin', 'k.mulai_masuk_kerja',
                'd.div_desc'
            )
            ->orderByDesc('k.id')
            ->limit(3)
            ->get();

        return compact(
            'perusahaan_id',
            'total_karyawan', 'total_aktif', 'total_nonaktif',
            'total_laki', 'total_perempuan',
            'total_sertif', 'sertif_aktif', 'sertif_hampir', 'sertif_expired', 'sertif_no_file',
            'temuan_open',
            'distribusi_divisi', 'distribusi_unit', 'distribusi_pendidikan',
            'alert_sertifikasi', 'karyawan_baru',
            'today', 'tgl_30'
        );
    }
}
