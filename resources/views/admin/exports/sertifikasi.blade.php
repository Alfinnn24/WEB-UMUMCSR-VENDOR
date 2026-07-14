<html><head><meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10pt; }
table { border-collapse: collapse; width: 100%; }
th { background: #1565c0; color: #fff; border: 1px solid #999; padding: 6px 10px; white-space: nowrap; text-align: center; }
td { border: 1px solid #ccc; padding: 4px 8px; vertical-align: middle; }
.title-row td { background: #1565c0; color: #fff; font-size: 14pt; font-weight: bold; padding: 10px; }
.sub-row td  { background: #e3f2fd; color: #555; font-size: 9pt; padding: 4px 8px; }
.ctr { text-align: center; }
.aktif { color: #2e7d32; font-weight: bold; }
.hampir { color: #f57f17; font-weight: bold; }
.expired { color: #c62828; font-weight: bold; }
</style>
</head><body>
<table>
    <tr class="title-row">
        <td colspan="10">LAPORAN SERTIFIKASI KARYAWAN</td>
    </tr>
    <tr class="sub-row">
        <td colspan="10">Dicetak: {{ date('d M Y H:i') }} &nbsp;|&nbsp; Total: {{ count($rows) }} sertifikasi</td>
    </tr>
    <tr><td colspan="10" style="height:6px"></td></tr>
    <tr>
        <th>#</th>
        <th>Perusahaan</th>
        <th>Karyawan</th>
        <th>NIK</th>
        <th>Nama Sertifikasi</th>
        <th>Nomor Sertifikat</th>
        <th>Lembaga</th>
        <th>Tgl Sertifikasi</th>
        <th>Masa Berlaku (Bln)</th>
        <th>Status</th>
    </tr>
@foreach ($rows as $i => $r)
    @php
        $sisa = (int)$r->sisa_hari;
        if ($sisa < 0) { $label = 'Expired'; }
        elseif ($sisa <= 30) { $label = 'Hampir Expired'; }
        else { $label = 'Aktif'; }
    @endphp
    <tr>
        <td class="ctr">{{ $i+1 }}</td>
        <td>{{ $r->nama_perusahaan ?? '-' }}</td>
        <td>{{ $r->nama_karyawan ?? '-' }}</td>
        <td>'{{ $r->nik }}</td>
        <td>{{ $r->nama_sertifikasi }}</td>
        <td>{{ $r->nomor_sertifikat ?? '-' }}</td>
        <td>{{ $r->lembaga_sertifikasi }}</td>
        <td>{{ $r->tanggal_sertifikasi ? date('d/m/Y', strtotime($r->tanggal_sertifikasi)) : '-' }}</td>
        <td class="ctr">{{ $r->masa_berlaku }}</td>
        <td class="{{ $label === 'Expired' ? 'expired' : ($label === 'Hampir Expired' ? 'hampir' : 'aktif') }}">{{ $label }}</td>
    </tr>
@endforeach
    <tr>
        <td colspan="10" style="background:#e8f5e9;font-weight:bold;text-align:right;padding:6px 10px">
            Total: {{ count($rows) }} sertifikasi
        </td>
    </tr>
</table>
</body></html>