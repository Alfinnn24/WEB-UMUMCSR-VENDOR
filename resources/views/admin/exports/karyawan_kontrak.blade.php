<html><head><meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10pt; }
table { border-collapse: collapse; width: 100%; }
th { background: #1565c0; color: #fff; border: 1px solid #999; padding: 6px 10px; white-space: nowrap; text-align: center; }
td { border: 1px solid #ccc; padding: 4px 8px; vertical-align: middle; }
.title-row td { background: #1565c0; color: #fff; font-size: 13pt; font-weight: bold; padding: 10px; }
.info-row  td { background: #e3f2fd; color: #444; font-size: 9pt; padding: 4px 8px; }
.ctr { text-align: center; }
.aktif   { color: #2e7d32; font-weight: bold; }
.nonaktif{ color: #c62828; }
</style>
</head><body>
<table>
    <tr class="title-row">
        <td colspan="11">DATA KARYAWAN KONTRAK</td>
    </tr>
    <tr class="info-row">
        <td colspan="11">
            <strong>Perusahaan:</strong> {{ $info->nama_perusahaan }} &nbsp;|&nbsp;
            <strong>Kontrak:</strong> {{ $info->judul_kontrak }} &nbsp;|&nbsp;
            <strong>No:</strong> {{ $info->nomor_kontrak }}
        </td>
    </tr>
    <tr class="info-row">
        <td colspan="11">
            <strong>Periode:</strong>
            {{ date('d M Y', strtotime($info->tgl_mulai)) }} s/d
            {{ date('d M Y', strtotime($info->tgl_selesai)) }} &nbsp;|&nbsp;
            <strong>Dicetak:</strong> {{ date('d M Y H:i') }} &nbsp;|&nbsp;
            <strong>Total Karyawan:</strong> {{ count($rows) }} orang
        </td>
    </tr>
    <tr><td colspan="11" style="height:5px"></td></tr>
    <tr>
        <th>#</th>
        <th>NIK</th>
        <th>Nama Karyawan</th>
        <th>JK</th>
        <th>Jabatan</th>
        <th>Divisi</th>
        <th>Unit</th>
        <th>Pendidikan</th>
        <th>No. HP</th>
        <th>Provinsi / Kab</th>
        <th>Status</th>
    </tr>
@foreach ($rows as $i => $r)
    <tr>
        <td class="ctr">{{ $i+1 }}</td>
        <td>'{{ $r->nik }}</td> {{-- Added single quote prefix to prevent Excel scientific format --}}
        <td>{{ $r->nama }}</td>
        <td class="ctr">{{ $r->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        <td>{{ $r->jabatan }}</td>
        <td>{{ $r->divisi ?? '-' }}</td>
        <td class="ctr">{{ $r->unit }}</td>
        <td>{{ $r->pendidikan_terakhir }}</td>
        <td>'{{ $r->no_hp }}</td> {{-- Added single quote prefix to prevent Excel scientific format --}}
        <td>{{ $r->provinsi ?? '-' }} / {{ $r->kabupaten ?? '-' }}</td>
        <td class="ctr {{ strtolower($r->status) }}">{{ $r->status }}</td>
    </tr>
@endforeach
    <tr>
        <td colspan="11" style="background:#e8f5e9;font-weight:bold;text-align:right;padding:6px 10px">
            Total: {{ count($rows) }} karyawan
        </td>
    </tr>
</table>
</body></html>
