<html><head><meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10pt; }
table { border-collapse: collapse; width: 100%; }
th {
    background: #1565c0;
    color: #fff;
    border: 1px solid #999;
    padding: 6px 10px;
    white-space: nowrap;
    text-align: center;
}
td { border: 1px solid #ccc; padding: 4px 8px; vertical-align: middle; }
.title-row td { background: #1565c0; color: #fff; font-size: 14pt; font-weight: bold; padding: 10px; }
.sub-row  td  { background: #e3f2fd; color: #555; font-size: 9pt; padding: 4px 8px; }
.aktif   { color: #2e7d32; }
.nonaktif{ color: #c62828; }
.num  { text-align: right; }
.ctr  { text-align: center; }
</style>
</head><body>
<table>
    <tr class="title-row">
        <td colspan="12">DETAIL KARYAWAN — {{ $label_ring }}
            @if (!empty($rows)) — {{ $rows[0]->nama_perusahaan ?? 'Semua Perusahaan' }} @endif
        </td>
    </tr>
    <tr class="sub-row">
        <td colspan="12">
            Dicetak: {{ date('d M Y H:i') }} &nbsp;|&nbsp;
            Total karyawan: {{ count($rows) }} &nbsp;|&nbsp;
            Status: <strong>Aktif</strong>
        </td>
    </tr>
    <tr><td colspan="12" style="height:6px"></td></tr>
    <tr>
        <th>#</th>
        <th>Perusahaan</th>
        <th>NIK</th>
        <th>Nama Karyawan</th>
        <th>JK</th>
        <th>Jabatan</th>
        <th>Unit</th>
        <th>Pendidikan</th>
        <th>Provinsi</th>
        <th>Kabupaten</th>
        <th>Kecamatan</th>
        <th>Desa</th>
    </tr>
@foreach ($rows as $i => $r)
    <tr>
        <td class="ctr">{{ $i+1 }}</td>
        <td>{{ $r->nama_perusahaan ?? '-' }}</td>
        <td>'{{ $r->nik }}</td> {{-- Added single quote prefix to prevent Excel scientific format --}}
        <td>{{ $r->nama }}</td>
        <td class="ctr">{{ $r->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        <td>{{ $r->jabatan }}</td>
        <td class="ctr">{{ $r->unit }}</td>
        <td>{{ $r->pendidikan_terakhir }}</td>
        <td>{{ $r->provinsi }}</td>
        <td>{{ $r->kabupaten }}</td>
        <td>{{ $r->kecamatan }}</td>
        <td>{{ $r->desa }}</td>
    </tr>
@endforeach
    <tr>
        <td colspan="12" style="background:#e8f5e9;font-weight:bold;text-align:right;padding:6px 10px">
            Total: {{ count($rows) }} karyawan
        </td>
    </tr>
</table>
</body></html>
