<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'dashboard');
        
        if ($page === 'dashboard') {
            $data = $this->getDashboardData();
            $view = 'admin.dashboard';
            $title = 'Dashboard';
        } elseif ($page === 'monitoring_sertifikasi') {
            $data = $this->getSertifikasiData($request);
            $view = 'admin.monitoring_sertifikasi';
            $title = 'Statistik Sertifikasi';
        } elseif ($page === 'monitoring_laporan_tenaker') {
            $data = $this->getLaporanTenakerData($request);
            $view = 'admin.monitoring_laporan_tenaker';
            $title = 'Statistik Tenaga Kerja';
        } elseif ($page === 'monitoring_kontrak') {
            $data = $this->getKontrakData($request);
            $view = 'admin.monitoring_kontrak';
            $title = 'Statistik Kontrak';
        } elseif ($page === 'laporan_karyawan') {
            $data = $this->getLaporanKaryawanData($request);
            $view = 'admin.laporan_karyawan';
            $title = 'Laporan Karyawan';
        } elseif ($page === 'laporan_sertifikasi') {
            $data = $this->getLaporanSertifikasiData($request);
            $view = 'admin.laporan_sertifikasi';
            $title = 'Laporan Sertifikasi';
        } elseif ($page === 'laporan_tenaga_kerja_admin') {
            $data = $this->getLaporanTenagaKerjaData($request);
            $view = 'admin.laporan_tenaga_kerja_admin';
            $title = 'Laporan Tenaga Kerja';
        } elseif ($page === 'laporan_kontrak') {
            $data = $this->getLaporanKontrakData($request);
            $view = 'admin.laporan_kontrak';
            $title = 'Laporan Kontrak Kerja';
        } elseif ($page === 'laporan_ring_wilayah') {
            $data = $this->getLaporanRingWilayahData($request);
            $view = 'admin.laporan_ring_wilayah';
            $title = 'Laporan Ring Wilayah';
        } else {
            $data = [];
            $view = 'admin.' . $page;
            $title = ucwords(str_replace('_', ' ', $page));
            
            if (!view()->exists($view)) {
                $data = ['pageName' => $title];
                $view = 'admin.placeholder';
            }
        }

        if ($request->ajax()) {
            return view($view, $data);
        }

        return view('layouts.admin', array_merge($data, [
            'page'       => $view,
            'pageTitle'  => $title,
        ]));
    }

    // ─── Ambil semua data statistik ────────────────────────────────────
    private function getDashboardData(): array
    {
        $today  = now()->toDateString();
        $tgl30  = now()->addDays(30)->toDateString();

        // Statistik perusahaan & karyawan
        $total_perusahaan = DB::table('users')->where('role', 'perusahaan')->count();
        $total_karyawan   = DB::table('karyawan')->count();
        $total_aktif      = DB::table('karyawan')->where('status', 'Aktif')->count();
        $total_nonaktif   = $total_karyawan - $total_aktif;

        $gender = DB::table('karyawan')->selectRaw("
            SUM(jenis_kelamin = 'L') as laki,
            SUM(jenis_kelamin = 'P') as perempuan
        ")->first();
        $total_laki      = (int)($gender->laki ?? 0);
        $total_perempuan = (int)($gender->perempuan ?? 0);

        // Temuan audit
        $total_temuan = DB::table('temuan_audit')->count();
        $temuan_open  = DB::table('temuan_audit')->where('status', 'Open')->count();

        // Karyawan per Ring Wilayah
        $karyawan_ring1 = $this->countRing('Ring 1');
        $karyawan_ring2 = $this->countRing('Ring 2');
        $karyawan_ring3 = $this->countRing('Ring 3');
        $karyawan_ring4 = $this->countRing('Ring 4');

        // Karyawan aktif tidak terpetakan di ring manapun
        $karyawan_tanpa_ring = DB::select("
            SELECT COUNT(*) as n FROM karyawan k
            WHERE k.status = 'Aktif'
            AND NOT EXISTS (
                SELECT 1 FROM ring_wilayah r
                WHERE r.provinsi  = k.provinsi
                  AND r.kabupaten = k.kabupaten
                  AND r.kecamatan = k.kecamatan
                  AND r.desa      = k.desa
            )
        ")[0]->n ?? 0;

        // Distribusi divisi
        $distribusi_divisi = DB::table('karyawan as k')
            ->join('divisions as d', 'k.div_id', '=', 'd.id')
            ->where('k.status', 'Aktif')
            ->selectRaw('d.div_desc, COUNT(*) as jumlah')
            ->groupBy('k.div_id', 'd.div_desc')
            ->orderByDesc('jumlah')
            ->get();

        // Distribusi unit
        $distribusi_unit = DB::table('karyawan')
            ->where('status', 'Aktif')
            ->selectRaw('unit, COUNT(*) as jumlah')
            ->groupBy('unit')
            ->orderByDesc('jumlah')
            ->get();

        // Distribusi usia
        $usia = DB::select("
            SELECT
                SUM(CASE WHEN age < 25 THEN 1 ELSE 0 END)              AS age1,
                SUM(CASE WHEN age BETWEEN 25 AND 35 THEN 1 ELSE 0 END) AS age2,
                SUM(CASE WHEN age BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS age3,
                SUM(CASE WHEN age BETWEEN 46 AND 55 THEN 1 ELSE 0 END) AS age4,
                SUM(CASE WHEN age > 55 THEN 1 ELSE 0 END)              AS age5
            FROM (
                SELECT TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS age
                FROM karyawan
                WHERE status = 'Aktif'
                  AND tanggal_lahir IS NOT NULL
                  AND CAST(tanggal_lahir AS CHAR) != '0000-00-00'
            ) AS current_ages
        ")[0];
        $usia_data = [
            (int)($usia->age1 ?? 0),
            (int)($usia->age2 ?? 0),
            (int)($usia->age3 ?? 0),
            (int)($usia->age4 ?? 0),
            (int)($usia->age5 ?? 0),
        ];

        // 5 Karyawan terbaru
        $karyawan_baru = DB::table('karyawan as k')
            ->leftJoin('divisions as d', 'k.div_id', '=', 'd.id')
            ->leftJoin('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->select('k.id','k.nama','k.nik','k.jabatan','k.unit','k.status',
                     'k.jenis_kelamin','k.mulai_masuk_kerja',
                     'd.div_desc','u.nama as nama_perusahaan')
            ->orderByDesc('k.id')
            ->limit(5)
            ->get();

        return compact(
            'total_perusahaan','total_karyawan','total_aktif','total_nonaktif',
            'total_laki','total_perempuan',
            'total_temuan','temuan_open',
            'karyawan_ring1','karyawan_ring2','karyawan_ring3','karyawan_ring4','karyawan_tanpa_ring',
            'distribusi_divisi','distribusi_unit','usia_data','karyawan_baru'
        );
    }

    // ─── Helper hitung karyawan aktif dalam ring tertentu ──────────────
    private function countRing(string $ring): int
    {
        $result = DB::select("
            SELECT COUNT(DISTINCT k.id) AS n
            FROM karyawan k
            INNER JOIN ring_wilayah r
                ON r.ring = ?
                AND TRIM(r.provinsi)  = TRIM(k.provinsi)
                AND TRIM(r.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(r.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(r.desa)      = TRIM(k.desa)
            WHERE k.status = 'Aktif'
        ", [$ring]);

        return (int)($result[0]->n ?? 0);
    }

    // ─── MODUL 5: MONITORING DATA HELPERS ────────────────────────────────
    private function getSertifikasiData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_status     = trim($request->query('status', ''));
        $filter_file       = trim($request->query('file', ''));

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $today = now()->toDateString();
        $tgl_30 = now()->addDays(30)->toDateString();

        $countQuery = DB::table('sertifikasi_karyawan');
        if ($filter_perusahaan) {
            $countQuery->where('perusahaan_id', $filter_perusahaan);
        }

        $stat_total = (clone $countQuery)->count();
        $stat_aktif = (clone $countQuery)->where('tanggal_expired', '>', $tgl_30)->count();
        $stat_hampir = (clone $countQuery)->where('tanggal_expired', '>', $today)->where('tanggal_expired', '<=', $tgl_30)->count();
        $stat_expired = (clone $countQuery)->where('tanggal_expired', '<=', $today)->count();
        $stat_no_file = (clone $countQuery)->where(function($q) {
            $q->whereNull('file_sertifikat')->orWhere('file_sertifikat', '');
        })->count();

        $top_expired = DB::table('sertifikasi_karyawan as s')
            ->join('users as u', 's.perusahaan_id', '=', 'u.id')
            ->where('s.tanggal_expired', '<=', $today)
            ->selectRaw('u.nama, COUNT(*) as jumlah')
            ->groupBy('s.perusahaan_id', 'u.nama')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        $query = DB::table('sertifikasi_karyawan as s')
            ->leftJoin('karyawan as k', 's.karyawan_id', '=', 'k.id')
            ->leftJoin('users as u', 's.perusahaan_id', '=', 'u.id')
            ->select('s.*', 'k.nama as nama_karyawan', 'k.nik', 'k.jabatan', 'u.nama as nama_perusahaan',
                DB::raw("DATEDIFF(s.tanggal_expired, '$today') as sisa_hari"));

        if ($filter_perusahaan) {
            $query->where('s.perusahaan_id', $filter_perusahaan);
        }
        if ($filter_status === 'aktif') {
            $query->where('s.tanggal_expired', '>', $tgl_30);
        } elseif ($filter_status === 'hampir') {
            $query->where('s.tanggal_expired', '>', $today)->where('s.tanggal_expired', '<=', $tgl_30);
        } elseif ($filter_status === 'expired') {
            $query->where('s.tanggal_expired', '<=', $today);
        }

        if ($filter_file === 'ada') {
            $query->whereNotNull('s.file_sertifikat')->where('s.file_sertifikat', '!=', '');
        } elseif ($filter_file === 'tidak') {
            $query->where(function($q) {
                $q->whereNull('s.file_sertifikat')->orWhere('s.file_sertifikat', '');
            });
        }

        $data = $query->orderBy('s.tanggal_expired')->get();

        return compact(
            'filter_perusahaan', 'filter_status', 'filter_file', 'all_perusahaan',
            'stat_total', 'stat_aktif', 'stat_hampir', 'stat_expired', 'stat_no_file',
            'top_expired', 'data', 'today', 'tgl_30'
        );
    }

    private function getLaporanTenakerData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_file       = trim($request->query('file', ''));
        $filter_tahun      = (int)$request->query('tahun', 0);

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $countQuery = DB::table('laporan_tenaga_kerja');
        if ($filter_perusahaan) {
            $countQuery->where('perusahaan_id', $filter_perusahaan);
        }
        if ($filter_tahun) {
            $countQuery->whereYear('tgl_laporan', $filter_tahun);
        }

        $stat_total = (clone $countQuery)->count();
        $stat_sudah = (clone $countQuery)->whereNotNull('file_laporan')->where('file_laporan', '!=', '')->count();
        $stat_belum = (clone $countQuery)->where(function($q) {
            $q->whereNull('file_laporan')->orWhere('file_laporan', '');
        })->count();
        $stat_perusahaan = (clone $countQuery)->distinct('perusahaan_id')->count('perusahaan_id');

        $top_reporting = DB::table('laporan_tenaga_kerja as l')
            ->join('users as u', 'l.perusahaan_id', '=', 'u.id')
            ->selectRaw('u.nama, COUNT(*) as jumlah')
            ->groupBy('l.perusahaan_id', 'u.nama')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        $query = DB::table('laporan_tenaga_kerja as l')
            ->leftJoin('users as u', 'l.perusahaan_id', '=', 'u.id')
            ->select('l.*', 'u.nama as nama_perusahaan');

        if ($filter_perusahaan) {
            $query->where('l.perusahaan_id', $filter_perusahaan);
        }
        if ($filter_tahun) {
            $query->whereYear('l.tgl_laporan', $filter_tahun);
        }
        if ($filter_file === 'ada') {
            $query->whereNotNull('l.file_laporan')->where('l.file_laporan', '!=', '');
        } elseif ($filter_file === 'tidak') {
            $query->where(function($q) {
                $q->whereNull('l.file_laporan')->orWhere('l.file_laporan', '');
            });
        }

        $data = $query->orderByDesc('l.tgl_laporan')->orderByDesc('l.id')->get();

        return compact(
            'filter_perusahaan', 'filter_file', 'filter_tahun', 'all_perusahaan',
            'stat_total', 'stat_sudah', 'stat_belum', 'stat_perusahaan',
            'top_reporting', 'data'
        );
    }

    private function getKontrakData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_status     = trim($request->query('status', ''));

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $today = now()->toDateString();
        $tgl_30 = now()->addDays(30)->toDateString();

        $countQuery = DB::table('kontrak_kerja');
        if ($filter_perusahaan) {
            $countQuery->where('perusahaan_id', $filter_perusahaan);
        }

        $stat_total = (clone $countQuery)->count();
        $stat_aktif = (clone $countQuery)->where('tgl_mulai', '<=', $today)->where('tgl_selesai', '>=', $today)->where('tgl_selesai', '>', $tgl_30)->count();
        $stat_hampir = (clone $countQuery)->where('tgl_mulai', '<=', $today)->where('tgl_selesai', '>=', $today)->where('tgl_selesai', '<=', $tgl_30)->count();
        $stat_selesai = (clone $countQuery)->where('tgl_selesai', '<', $today)->count();
        $stat_belum = (clone $countQuery)->where('tgl_mulai', '>', $today)->count();

        $top_kontrak = DB::table('kontrak_kerja as k')
            ->join('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->selectRaw('u.nama, COUNT(*) as jumlah')
            ->groupBy('k.perusahaan_id', 'u.nama')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        $query = DB::table('kontrak_kerja as k')
            ->leftJoin('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->select('k.*', 'u.nama as nama_perusahaan',
                DB::raw("DATEDIFF(k.tgl_selesai, '$today') as sisa_hari"),
                DB::raw("(SELECT COUNT(*) FROM kontrak_karyawan kk WHERE kk.kontrak_id = k.id) as jml_assigned"));

        if ($filter_perusahaan) {
            $query->where('k.perusahaan_id', $filter_perusahaan);
        }

        if ($filter_status === 'aktif') {
            $query->where('k.tgl_mulai', '<=', $today)->where('k.tgl_selesai', '>=', $today)->where('k.tgl_selesai', '>', $tgl_30);
        } elseif ($filter_status === 'hampir') {
            $query->where('k.tgl_mulai', '<=', $today)->where('k.tgl_selesai', '>=', $today)->where('k.tgl_selesai', '<=', $tgl_30);
        } elseif ($filter_status === 'selesai') {
            $query->where('k.tgl_selesai', '<', $today);
        } elseif ($filter_status === 'belum') {
            $query->where('k.tgl_mulai', '>', $today);
        }

        $data = $query->orderBy('k.tgl_selesai')->orderByDesc('k.id')->get();

        return compact(
            'filter_perusahaan', 'filter_status', 'all_perusahaan',
            'stat_total', 'stat_aktif', 'stat_hampir', 'stat_selesai', 'stat_belum',
            'top_kontrak', 'data', 'today', 'tgl_30'
        );
    }

    // ─── MODUL 6: LAPORAN DATA HELPERS ───────────────────────────────────
    private function getLaporanKaryawanData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_status     = trim($request->query('status', ''));
        $filter_jk         = trim($request->query('jk', ''));
        $filter_unit       = trim($request->query('unit', ''));

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $query = DB::table('karyawan as k')
            ->leftJoin('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->leftJoin('divisions as d', 'k.div_id', '=', 'd.id')
            ->leftJoin('subdivisions as sd', 'k.subdiv_id', '=', 'sd.id')
            ->select('k.*', 'u.nama as nama_perusahaan', 'd.div_desc as divisi', 'sd.subdiv_desc as subdivisi');

        if ($filter_perusahaan) {
            $query->where('k.perusahaan_id', $filter_perusahaan);
        }
        if ($filter_status) {
            $query->where('k.status', $filter_status);
        }
        if ($filter_jk) {
            $query->where('k.jenis_kelamin', $filter_jk);
        }
        if ($filter_unit) {
            $query->where('k.unit', $filter_unit);
        }

        $data = $query->orderBy('u.nama')->orderBy('k.nama')->get();

        return compact('filter_perusahaan', 'filter_status', 'filter_jk', 'filter_unit', 'all_perusahaan', 'data');
    }

    private function getLaporanSertifikasiData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_status     = trim($request->query('status', ''));
        $filter_tahun      = (int)$request->query('tahun', 0);

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $today = now()->toDateString();
        $tgl_30 = now()->addDays(30)->toDateString();

        $query = DB::table('sertifikasi_karyawan as s')
            ->leftJoin('karyawan as k', 's.karyawan_id', '=', 'k.id')
            ->leftJoin('users as u', 's.perusahaan_id', '=', 'u.id')
            ->select('s.*', 'k.nama as nama_karyawan', 'k.nik', 'u.nama as nama_perusahaan',
                DB::raw("DATEDIFF(s.tanggal_expired, '$today') as sisa_hari"));

        if ($filter_perusahaan) {
            $query->where('s.perusahaan_id', $filter_perusahaan);
        }
        if ($filter_tahun) {
            $query->whereYear('s.tanggal_sertifikasi', $filter_tahun);
        }
        if ($filter_status === 'aktif') {
            $query->where('s.tanggal_expired', '>', $tgl_30);
        } elseif ($filter_status === 'hampir') {
            $query->where('s.tanggal_expired', '>', $today)->where('s.tanggal_expired', '<=', $tgl_30);
        } elseif ($filter_status === 'expired') {
            $query->where('s.tanggal_expired', '<=', $today);
        }

        $data = $query->orderBy('u.nama')->orderBy('k.nama')->get();

        $all_tahun = DB::table('sertifikasi_karyawan')
            ->whereNotNull('tanggal_sertifikasi')
            ->selectRaw('DISTINCT YEAR(tanggal_sertifikasi) as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        return compact('filter_perusahaan', 'filter_status', 'filter_tahun', 'all_perusahaan', 'data', 'all_tahun', 'today', 'tgl_30');
    }

    private function getLaporanTenagaKerjaData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_tahun      = (int)$request->query('tahun', date('Y'));

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $all_tahun = DB::table('laporan_tenaga_kerja')
            ->whereNotNull('tgl_laporan')
            ->selectRaw('DISTINCT YEAR(tgl_laporan) as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        if (!in_array((int)date('Y'), $all_tahun)) {
            array_unshift($all_tahun, (int)date('Y'));
        }
        rsort($all_tahun);

        $query = DB::table('users as u')
            ->leftJoin('laporan_tenaga_kerja as l', function($join) use ($filter_tahun) {
                $join->on('u.id', '=', 'l.perusahaan_id')
                     ->whereYear('l.tgl_laporan', $filter_tahun);
            })
            ->where('u.role', 'perusahaan');

        if ($filter_perusahaan) {
            $query->where('u.id', $filter_perusahaan);
        }

        $raw_data = $query->select('u.id as perusahaan_id', 'u.nama as nama_perusahaan', 'l.id as laporan_id', 'l.nomor_surat', 'l.tgl_laporan', 'l.file_laporan')
            ->orderBy('u.nama')
            ->orderByDesc('l.tgl_laporan')
            ->get();

        $data_flat = [];
        foreach ($raw_data as $row) {
            $status = empty($row->file_laporan) ? 'Belum Upload' : 'Sudah Upload';
            $row->status_upload = $status;
            $data_flat[] = $row;
        }

        return compact('filter_perusahaan', 'filter_tahun', 'all_perusahaan', 'all_tahun', 'data_flat');
    }

    private function getLaporanKontrakData(Request $request): array
    {
        $filter_perusahaan = (int)$request->query('perusahaan_id', 0);
        $filter_status     = trim($request->query('status', ''));

        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $today = now()->toDateString();
        $tgl_30 = now()->addDays(30)->toDateString();

        $query = DB::table('kontrak_kerja as k')
            ->leftJoin('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->select('k.*', 'u.nama as nama_perusahaan',
                DB::raw("DATEDIFF(k.tgl_selesai, '$today') as sisa_hari"),
                DB::raw("IF(k.berkas_kontrak IS NOT NULL AND k.berkas_kontrak != '', 'Ada', 'Belum') as status_berkas"),
                DB::raw("(SELECT COUNT(*) FROM kontrak_karyawan kk WHERE kk.kontrak_id = k.id) as jml_assigned"));

        if ($filter_perusahaan) {
            $query->where('k.perusahaan_id', $filter_perusahaan);
        }

        if ($filter_status === 'aktif') {
            $query->where('k.tgl_mulai', '<=', $today)->where('k.tgl_selesai', '>=', $today)->where('k.tgl_selesai', '>', $tgl_30);
        } elseif ($filter_status === 'hampir') {
            $query->where('k.tgl_mulai', '<=', $today)->where('k.tgl_selesai', '>=', $today)->where('k.tgl_selesai', '<=', $tgl_30);
        } elseif ($filter_status === 'selesai') {
            $query->where('k.tgl_selesai', '<', $today);
        }

        $data = $query->orderBy('u.nama')->orderBy('k.tgl_selesai')->get();

        return compact('filter_perusahaan', 'filter_status', 'all_perusahaan', 'data', 'today', 'tgl_30');
    }

    private function getLaporanRingWilayahData(Request $request): array
    {
        $all_perusahaan = DB::table('users')->where('role', 'perusahaan')->orderBy('nama')->get();

        $sql_summary = "
        SELECT
            u.id   AS perusahaan_id,
            u.nama AS nama_perusahaan,
        
            /* Ring 1 */
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 1'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id
               AND k.status = 'Aktif'
            ) AS ring1,
        
            /* Ring 2 */
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 2'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id
               AND k.status = 'Aktif'
            ) AS ring2,
            
            /* Ring 3 */
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 3'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id
               AND k.status = 'Aktif'
            ) AS ring3,
            
            /* Ring 4 */
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 4'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id
               AND k.status = 'Aktif'
            ) AS ring4,
        
            /* Belum Terpetakan */
            (SELECT COUNT(*)
             FROM karyawan k
             WHERE k.perusahaan_id = u.id
               AND k.status = 'Aktif'
               AND NOT EXISTS (
                   SELECT 1 FROM ring_wilayah rw
                   WHERE TRIM(rw.provinsi)  = TRIM(k.provinsi)
                     AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                     AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                     AND TRIM(rw.desa)      = TRIM(k.desa)
               )
            ) AS no_ring,
        
            /* Total aktif */
            (SELECT COUNT(*) FROM karyawan k WHERE k.perusahaan_id = u.id AND k.status = 'Aktif') AS total_aktif
        
        FROM users u
        WHERE u.role = 'perusahaan'
        ORDER BY u.nama ASC
        ";

        $data = DB::select($sql_summary);

        $grand_ring1  = array_sum(array_column($data, 'ring1'));
        $grand_ring2  = array_sum(array_column($data, 'ring2'));
        $grand_ring3  = array_sum(array_column($data, 'ring3'));
        $grand_ring4  = array_sum(array_column($data, 'ring4'));
        $grand_no     = array_sum(array_column($data, 'no_ring'));
        $grand_total  = array_sum(array_column($data, 'total_aktif'));

        return compact('all_perusahaan', 'data', 'grand_ring1', 'grand_ring2', 'grand_ring3', 'grand_ring4', 'grand_no', 'grand_total');
    }
}
