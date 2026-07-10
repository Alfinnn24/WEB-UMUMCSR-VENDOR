<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>No KTP</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Jenis Kelamin</th>
                <th>Agama</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Status Perkawinan</th>
                <th>Alamat Tinggal</th>
                <th>Alamat KTP</th>
                <th>Mulai Masuk Kerja</th>
                <th>Pendidikan Terakhir</th>
                <th>BPJS Kesehatan</th>
                <th>BPJS Ketenagakerjaan</th>
                <th>Jabatan</th>
                <th>Divisi</th>
                <th>Sub Divisi</th>
                <th>Unit</th>
                <th>Status</th>
                <th>NPWP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->nama }}</td>
                <td style="mso-number-format:'\@';">{{ $row->nik }}</td>
                <td style="mso-number-format:'\@';">{{ $row->nomor_ktp }}</td>
                <td style="mso-number-format:'\@';">{{ $row->no_hp }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td>{{ $row->agama }}</td>
                <td>{{ $row->tempat_lahir }}, {{ $row->tanggal_lahir ? date('d-m-Y', strtotime($row->tanggal_lahir)) : '' }}</td>
                <td>{{ $row->status_perkawinan }}</td>
                <td>{{ $row->alamat_tinggal }} Ds.{{ $row->desa }} Kec.{{ $row->kecamatan }} Kab.{{ $row->kabupaten }} Prov.{{ $row->provinsi }}</td>
                <td>{{ $row->alamat_ktp }}</td>
                <td>{{ $row->mulai_masuk_kerja ? date('d-m-Y', strtotime($row->mulai_masuk_kerja)) : '' }}</td>
                <td>{{ $row->pendidikan_terakhir }}</td>
                <td style="mso-number-format:'\@';">{{ $row->bpjs_kesehatan }}</td>
                <td style="mso-number-format:'\@';">{{ $row->bpjs_ketenagakerjaan }}</td>
                <td>{{ $row->jabatan }}</td>
                <td>{{ $row->div_desc }}</td>
                <td>{{ $row->subdiv_desc }}</td>
                <td>{{ $row->unit }}</td>
                <td>{{ $row->status }}</td>
                <td style="mso-number-format:'\@';">{{ $row->npwp }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
