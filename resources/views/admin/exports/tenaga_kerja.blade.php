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
        <td colspan="6">LAPORAN TENAGA KERJA — TAHUN {{ $filter_tahun }}</td>
    </tr>
    <tr class="sub-row">
        <td colspan="6">Dicetak: {{ date('d M Y H:i') }} &nbsp;|&nbsp; Total: {{ count($rows) }} laporan</td>
    </tr>
    <tr><td colspan="6" style="height:6px"></td></tr>
    <tr>
        <th>#</th>
        <th>Perusahaan</th>
        <th>Nomor Surat</th>
        <th>Tanggal Laporan</th>
        <th>Status Upload</th>
        <th>Berkas</th>
    </tr>
@foreach ($rows as $i => $r)
    <tr>
        <td class="ctr">{{ $i+1 }}</td>
        <td>{{ $r->nama_perusahaan ?? '-' }}</td>
        <td>{{ $r->nomor_surat ?? '-' }}</td>
        <td>{{ $r->tgl_laporan ? date('d/m/Y', strtotime($r->tgl_laporan)) : '-' }}</td>
        <td>{{ $r->status_upload }}</td>
        <td>{{ $r->status_upload === 'Sudah Upload' ? $r->file_laporan : '-' }}</td>
    </tr>
@endforeach
    <tr>
        <td colspan="6" style="background:#e8f5e9;font-weight:bold;text-align:right;padding:6px 10px">
            Total: {{ count($rows) }} laporan
        </td>
    </tr>
</table>
</body></html>