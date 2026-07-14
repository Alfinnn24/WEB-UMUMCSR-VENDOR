<html><head><meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10pt; }
table { border-collapse: collapse; width: 100%; }
th { background: #1565c0; color: #fff; border: 1px solid #999; padding: 6px 10px; white-space: nowrap; text-align: center; }
td { border: 1px solid #ccc; padding: 4px 8px; vertical-align: middle; }
.title-row td { background: #1565c0; color: #fff; font-size: 14pt; font-weight: bold; padding: 10px; }
.sub-row td  { background: #e3f2fd; color: #555; font-size: 9pt; padding: 4px 8px; }
.ctr { text-align: center; }
</style>
</head><body>
<table>
    <tr class="title-row">
        <td colspan="10">LAPORAN KONTRAK KERJA</td>
    </tr>
    <tr class="sub-row">
        <td colspan="10">Dicetak: {{ date('d M Y H:i') }} &nbsp;|&nbsp; Total: {{ count($rows) }} kontrak</td>
    </tr>
    <tr><td colspan="10" style="height:6px"></td></tr>
    <tr>
        <th>#</th>
        <th>Perusahaan</th>
        <th>Judul / No. Kontrak</th>
        <th>Deskripsi</th>
        <th>Tgl Mulai</th>
        <th>Tgl Selesai</th>
        <th>Jml TK (Target)</th>
        <th>Assigned</th>
        <th>Status</th>
        <th>Berkas</th>
    </tr>
@foreach ($rows as $i => $r)
    @php
        $today = now()->toDateString();
        if ($r->tgl_selesai < $today) { $label = 'Selesai'; }
        elseif ((int)$r->sisa_hari <= 30) { $label = 'Hampir Berakhir'; }
        else { $label = 'Aktif'; }
    @endphp
    <tr>
        <td class="ctr">{{ $i+1 }}</td>
        <td>{{ $r->nama_perusahaan ?? '-' }}</td>
        <td>{{ $r->judul_kontrak }} ({{ $r->nomor_kontrak }})</td>
        <td>{{ $r->deskripsi_pekerjaan ? Str::limit($r->deskripsi_pekerjaan, 100) : '-' }}</td>
        <td>{{ $r->tgl_mulai ? date('d/m/Y', strtotime($r->tgl_mulai)) : '-' }}</td>
        <td>{{ $r->tgl_selesai ? date('d/m/Y', strtotime($r->tgl_selesai)) : '-' }}</td>
        <td class="ctr">{{ number_format($r->jumlah_tenaga_kerja) }}</td>
        <td class="ctr">{{ $r->jml_assigned }}</td>
        <td>{{ $label }}</td>
        <td>{{ $r->status_berkas === 'Ada' ? $r->berkas_kontrak : '-' }}</td>
    </tr>
@endforeach
    <tr>
        <td colspan="10" style="background:#e8f5e9;font-weight:bold;text-align:right;padding:6px 10px">
            Total: {{ count($rows) }} kontrak
        </td>
    </tr>
</table>
</body></html>