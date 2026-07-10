<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanExportController extends Controller
{
    public function exportRingDetail(Request $request)
    {
        $filter_pid  = (int)$request->query('perusahaan_id', 0);
        $filter_ring = trim($request->query('ring', ''));

        $where_pid = $filter_pid ? "AND k.perusahaan_id = $filter_pid" : "";

        if (in_array($filter_ring, ['Ring 1', 'Ring 2', 'Ring 3', 'Ring 4'])) {
            $sql = "
                SELECT k.nik, k.nama, k.jenis_kelamin, k.jabatan, k.unit,
                       k.pendidikan_terakhir, k.status,
                       k.provinsi, k.kabupaten, k.kecamatan, k.desa,
                       k.mulai_masuk_kerja,
                       rw.ring AS ring_label,
                       u.nama AS nama_perusahaan
                FROM karyawan k
                INNER JOIN ring_wilayah rw
                    ON rw.ring = ?
                    AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                    AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                    AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                    AND TRIM(rw.desa)      = TRIM(k.desa)
                LEFT JOIN users u ON k.perusahaan_id = u.id
                WHERE k.status = 'Aktif' $where_pid
                ORDER BY u.nama, k.nama ASC
            ";
            $rows = DB::select($sql, [$filter_ring]);
            $label_ring = $filter_ring;
        } else {
            $sql = "
                SELECT k.nik, k.nama, k.jenis_kelamin, k.jabatan, k.unit,
                       k.pendidikan_terakhir, k.status,
                       k.provinsi, k.kabupaten, k.kecamatan, k.desa,
                       k.mulai_masuk_kerja,
                       'Belum Terpetakan' AS ring_label,
                       u.nama AS nama_perusahaan
                FROM karyawan k
                LEFT JOIN users u ON k.perusahaan_id = u.id
                WHERE k.status = 'Aktif'
                  AND NOT EXISTS (
                      SELECT 1 FROM ring_wilayah rw
                      WHERE TRIM(rw.provinsi)  = TRIM(k.provinsi)
                        AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                        AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                        AND TRIM(rw.desa)      = TRIM(k.desa)
                  ) $where_pid
                ORDER BY u.nama, k.nama ASC
            ";
            $rows = DB::select($sql);
            $label_ring = 'Belum Terpetakan';
        }

        $label_pid   = $filter_pid ? ('_Perusahaan'.$filter_pid) : '';
        $label_r     = str_replace([' ','/'], ['_',''], $label_ring);
        $filename    = 'Detail_'.$label_r.$label_pid.'_'.date('Ymd_His').'.xls';

        $output = view('admin.exports.ring_detail', [
            'rows' => $rows,
            'label_ring' => $label_ring
        ])->render();

        return response($output)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    public function exportRingWilayah(Request $request)
    {
        $filter_pid = (int)$request->query('perusahaan_id', 0);
        $where_u = $filter_pid ? "AND u.id = $filter_pid" : "";

        $sql = "
        SELECT
            u.id   AS perusahaan_id,
            u.nama AS nama_perusahaan,
        
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 1'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id AND k.status = 'Aktif'
            ) AS ring1,
        
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 2'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id AND k.status = 'Aktif'
            ) AS ring2,
            
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 3'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id AND k.status = 'Aktif'
            ) AS ring3,
            
            (SELECT COUNT(DISTINCT k.id)
             FROM karyawan k
             INNER JOIN ring_wilayah rw ON rw.ring = 'Ring 4'
                AND TRIM(rw.provinsi)  = TRIM(k.provinsi)
                AND TRIM(rw.kabupaten) = TRIM(k.kabupaten)
                AND TRIM(rw.kecamatan) = TRIM(k.kecamatan)
                AND TRIM(rw.desa)      = TRIM(k.desa)
             WHERE k.perusahaan_id = u.id AND k.status = 'Aktif'
            ) AS ring4,
        
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
        
            (SELECT COUNT(*) FROM karyawan k WHERE k.perusahaan_id = u.id AND k.status = 'Aktif') AS total_aktif
        
        FROM users u
        WHERE u.role = 'perusahaan' $where_u
        ORDER BY u.nama ASC
        ";

        $rows = DB::select($sql);

        $grand_ring1 = array_sum(array_column($rows, 'ring1'));
        $grand_ring2 = array_sum(array_column($rows, 'ring2'));
        $grand_ring3 = array_sum(array_column($rows, 'ring3'));
        $grand_ring4 = array_sum(array_column($rows, 'ring4'));
        $grand_no    = array_sum(array_column($rows, 'no_ring'));
        $grand_total = array_sum(array_column($rows, 'total_aktif'));

        $label_file  = $filter_pid ? ('_Perusahaan'.$filter_pid) : '_SemuaPerusahaan';
        $filename    = 'Laporan_RingWilayah'.$label_file.'_'.date('Ymd_His').'.xls';

        $output = view('admin.exports.ring_wilayah', compact(
            'rows', 'grand_ring1', 'grand_ring2', 'grand_ring3', 'grand_ring4', 'grand_no', 'grand_total', 'filter_pid'
        ))->render();

        return response($output)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    public function exportKaryawanKontrak(Request $request)
    {
        $kontrak_id = (int)$request->query('kontrak_id', 0);
        if (!$kontrak_id) {
            return response('Parameter kontrak_id tidak valid.', 400);
        }

        $info = DB::table('kontrak_kerja as k')
            ->leftJoin('users as u', 'k.perusahaan_id', '=', 'u.id')
            ->select('k.judul_kontrak', 'k.nomor_kontrak', 'k.tgl_mulai', 'k.tgl_selesai', 'u.nama as nama_perusahaan')
            ->where('k.id', $kontrak_id)
            ->first();

        if (!$info) {
            return response('Kontrak tidak ditemukan.', 404);
        }

        $sql = "
            SELECT kar.nik, kar.nama, kar.jenis_kelamin, kar.jabatan, kar.unit,
                   kar.pendidikan_terakhir, kar.status,
                   kar.provinsi, kar.kabupaten, kar.no_hp,
                   d.div_desc AS divisi,
                   kar.mulai_masuk_kerja
            FROM kontrak_karyawan kk
            INNER JOIN karyawan kar ON kk.karyawan_id = kar.id
            LEFT JOIN divisions d ON kar.div_id = d.id
            WHERE kk.kontrak_id = ?
            ORDER BY kar.nama ASC
        ";

        $rows = DB::select($sql, [$kontrak_id]);

        $filename = 'Karyawan_Kontrak_'.preg_replace('/[^a-z0-9]/i','_',$info->nomor_kontrak).'_'.date('Ymd_His').'.xls';

        $output = view('admin.exports.karyawan_kontrak', compact('info', 'rows'))->render();

        return response($output)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}
