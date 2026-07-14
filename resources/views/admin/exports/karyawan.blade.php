<html><head><meta charset="UTF-8">
<style>
table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; }
th { background: #1976d2; color: #fff; border: 1px solid #ccc; padding: 5px 8px; white-space: nowrap; }
td { border: 1px solid #ccc; padding: 4px 8px; vertical-align: top; }
.hdr { background: #e3f2fd; color: #1565c0; font-weight: bold; }
.aktif { color: #388e3c; }
.nonaktif { color: #d32f2f; }
</style>
</head><body>
<table>
    <tr><td colspan="28" class="hdr" style="font-size:13pt;padding:10px">LAPORAN DATA KARYAWAN</td></tr>
    <tr><td colspan="28" style="font-size:9pt;color:#555">Dicetak: {{ date('d M Y H:i') }} — Total: {{ count($rows) }} karyawan</td></tr>
    <tr><td colspan="28"></td></tr>
    <tr>
        <th>#</th>
        <th>Perusahaan</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Nomor KTP</th>
        <th>NPWP</th>
        <th>JK</th>
        <th>Tempat Lahir</th>
        <th>Tgl Lahir</th>
        <th>Agama</th>
        <th>Status Kawin</th>
        <th>No HP</th>
        <th>Email</th>
        <th>Alamat Tinggal</th>
        <th>Desa</th>
        <th>Kecamatan</th>
        <th>Kabupaten</th>
        <th>Provinsi</th>
        <th>Alamat KTP</th>
        <th>Pendidikan</th>
        <th>Jabatan</th>
        <th>Divisi</th>
        <th>Sub Divisi</th>
        <th>Unit</th>
        <th>Mulai Kerja</th>
        <th>BPJS Kes</th>
        <th>BPJS TK</th>
        <th>Status</th>
    </tr>
@foreach ($rows as $i => $r)
    <tr>
        <td align="center">{{ $i+1 }}</td>
        <td>{{ $r->nama_perusahaan ?? '-' }}</td>
        <td>'{{ $r->nik }}</td>
        <td>{{ $r->nama }}</td>
        <td>{{ $r->nomor_ktp ?? '-' }}</td>
        <td>{{ $r->npwp ?? '-' }}</td>
        <td align="center">{{ $r->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        <td>{{ $r->tempat_lahir ?? '-' }}</td>
        <td>{{ $r->tanggal_lahir ? date('d/m/Y', strtotime($r->tanggal_lahir)) : '-' }}</td>
        <td>{{ $r->agama ?? '-' }}</td>
        <td>{{ $r->status_perkawinan ?? '-' }}</td>
        <td>{{ $r->no_hp ?? '-' }}</td>
        <td>{{ $r->email ?? '-' }}</td>
        <td>{{ $r->alamat_tinggal ?? '-' }}</td>
        <td>{{ $r->desa ?? '-' }}</td>
        <td>{{ $r->kecamatan ?? '-' }}</td>
        <td>{{ $r->kabupaten ?? '-' }}</td>
        <td>{{ $r->provinsi ?? '-' }}</td>
        <td>{{ $r->alamat_ktp ?? '-' }}</td>
        <td>{{ $r->pendidikan_terakhir ?? '-' }}</td>
        <td>{{ $r->jabatan }}</td>
        <td>{{ $r->divisi ?? '-' }}</td>
        <td>{{ $r->subdivisi ?? '-' }}</td>
        <td>{{ $r->unit }}</td>
        <td>{{ $r->mulai_masuk_kerja ? date('d/m/Y', strtotime($r->mulai_masuk_kerja)) : '-' }}</td>
        <td>{{ $r->bpjs_kesehatan ?? '-' }}</td>
        <td>{{ $r->bpjs_ketenagakerjaan ?? '-' }}</td>
        <td class="{{ strtolower($r->status) }}">{{ $r->status }}</td>
    </tr>
@endforeach
    <tr><td colspan="28" class="hdr" align="right">Total: {{ count($rows) }} karyawan</td></tr>
</table>
</body></html>