<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Sertifikasi</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Sertifikasi Karyawan</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('perusahaan.sertifikasi.create') }}" class="btn btn-primary px-4 nav-ajax">
            <i class="bx bx-plus me-1"></i> Tambah Sertifikasi
        </a>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-white">{{ session('success') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('warning'))
<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-dark"><i class="bx bxs-trash"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-dark">{{ session('warning') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-x-circle"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-white">{{ session('error') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
            <div>
                <h5 class="mb-0 fw-bold">Daftar Sertifikasi Karyawan</h5>
                <small class="text-muted">Total {{ $data->total() }} sertifikasi</small>
            </div>
            <div class="ms-auto d-flex gap-2 flex-wrap">
                <input type="text" id="searchInput" class="form-control form-control-sm"
                       placeholder="Cari nama / sertifikasi..." style="width:220px" onkeyup="filterTable()">
                <select id="filterStatus" class="form-select form-select-sm" style="width:145px" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Hampir Exp.">Hampir Expired</option>
                    <option value="Expired">Expired</option>
                </select>
                <select id="filterFile" class="form-select form-select-sm" style="width:145px" onchange="filterTable()">
                    <option value="">Semua File</option>
                    <option value="sudah">Sudah Upload</option>
                    <option value="belum">Belum Upload</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelSertifikasi">
                <thead class="table-light">
                    <tr>
                        <th width="45">#</th>
                        <th>Karyawan</th>
                        <th>Sertifikasi</th>
                        <th>Lembaga</th>
                        <th>Tgl Sertifikasi</th>
                        <th>Masa Berlaku</th>
                        <th width="130">Status</th>
                        <th width="110" class="text-center">File</th>
                        <th width="130" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @if ($data->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bx bx-certification font-40 d-block mb-2"></i>
                            Belum ada data sertifikasi.
                            <a href="{{ route('perusahaan.sertifikasi.create') }}" class="nav-ajax">Tambah sekarang</a>
                        </td>
                    </tr>
                @else
                    @foreach ($data as $i => $row)
                        @php
                            $today = new DateTime();
                            $expired = new DateTime($row->tanggal_expired);
                            $selisih = (int) $today->diff($expired)->format('%r%a');
                            
                            if ($selisih < 0) {
                                $st = ['label' => 'Expired', 'class' => 'danger', 'icon' => 'bx-x-circle', 'hari' => abs($selisih)];
                            } elseif ($selisih <= 30) {
                                $st = ['label' => 'Hampir Exp.', 'class' => 'warning', 'icon' => 'bx-error', 'hari' => $selisih];
                            } else {
                                $st = ['label' => 'Aktif', 'class' => 'success', 'icon' => 'bx-check-circle', 'hari' => $selisih];
                            }
                            $adaFile = !empty($row->file_sertifikat);
                        @endphp
                        <tr data-status="{{ $st['label'] }}" data-file="{{ $adaFile ? 'sudah' : 'belum' }}">
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold small">{{ $row->nama_karyawan ?? '-' }}</div>
                                <small class="text-muted">{{ $row->nik ?? '' }} &middot; {{ $row->jabatan ?? '' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold small text-truncate" style="max-width:180px" title="{{ $row->nama_sertifikasi }}">
                                    {{ $row->nama_sertifikasi }}
                                </div>
                                @if (!empty($row->nomor_sertifikat))
                                <small class="text-muted"><i class="bx bx-hash me-1"></i>{{ $row->nomor_sertifikat }}</small><br>
                                @endif
                                <small class="text-muted"><i class="bx bx-map-pin me-1"></i>{{ $row->kota_pelaksanaan }}</small>
                            </td>
                            <td>
                                <small class="text-truncate d-inline-block" style="max-width:140px" title="{{ $row->lembaga_sertifikasi }}">
                                    {{ $row->lembaga_sertifikasi }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $row->tanggal_sertifikasi ? date('d M Y', strtotime($row->tanggal_sertifikasi)) : '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $row->masa_berlaku }} Bulan</span>
                                <br><small class="text-muted">Exp: {{ $row->tanggal_expired ? date('d M Y', strtotime($row->tanggal_expired)) : '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $st['class'] }} rounded-pill px-3 py-2">
                                    <i class="bx {{ $st['icon'] }} me-1"></i>{{ $st['label'] }}
                                </span>
                                @if ($st['label'] === 'Aktif')
                                    <br><small class="text-muted">{{ $st['hari'] }} hari lagi</small>
                                @elseif ($st['label'] === 'Hampir Exp.')
                                    <br><small class="text-warning fw-semibold">{{ $st['hari'] }} hari lagi!</small>
                                @else
                                    <br><small class="text-danger">{{ $st['hari'] }} hari lalu</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($adaFile)
                                    <a href="/uploads/sertifikasi/{{ $row->file_sertifikat }}"
                                       target="_blank" class="btn btn-sm btn-outline-success" title="Lihat File">
                                        <i class="bx bx-file me-1"></i>Lihat
                                    </a>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            onclick="showUpload({{ $row->id }}, '{{ addslashes($row->nama_karyawan) }}', '{{ addslashes($row->nama_sertifikasi) }}')"
                                            title="Upload File">
                                        <i class="bx bx-upload me-1"></i>Upload
                                    </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick='showDetail({!! json_encode($row) !!})'
                                            title="Detail">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <a href="{{ route('perusahaan.sertifikasi.edit', $row->id) }}"
                                       class="btn btn-sm btn-outline-warning nav-ajax" title="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('perusahaan.sertifikasi.destroy', $row->id) }}" method="POST" class="d-inline form-delete"
                                          onsubmit="return confirm('Yakin hapus data sertifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        @if ($data->hasPages())
        <div class="mt-3 pt-2 border-top">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Upload File -->
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h6 class="modal-title fw-bold mb-0">Upload File Sertifikat</h6>
                    <small class="text-muted" id="uploadSubtitle">—</small>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="formUploadAction">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" name="file_sertifikat" class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text text-muted">Format: PDF, JPG, PNG &nbsp;&middot;&nbsp; Maks: 5 MB</div>
                    </div>
                    <div class="alert alert-light border mb-0">
                        <small><i class="bx bx-info-circle me-1 text-info"></i>File lama akan digantikan jika sudah ada.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning px-4">
                        <i class="bx bx-upload me-1"></i>Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h6 class="modal-title fw-bold mb-1" id="modalDetailJudul">—</h6>
                    <div id="modalDetailMeta"></div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="modalDetailBody"></div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modalEditLink" class="btn btn-sm btn-warning nav-ajax" onclick="bootstrap.Modal.getInstance(document.getElementById('modalDetail')).hide()">
                    <i class="bx bx-edit me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showUpload(id, namaKaryawan, namaSertif) {
    document.getElementById('uploadSubtitle').textContent = namaKaryawan + ' — ' + namaSertif;
    document.getElementById('formUploadAction').action = `{{ url('perusahaan/sertifikasi') }}/${id}/upload`;
    new bootstrap.Modal(document.getElementById('modalUpload')).show();
}

const esc = s => (s || '-').toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

function dRow(label, value) {
    return `<tr>
        <td style="width:45%;padding:7px 16px;font-size:.82rem;color:#6c757d;white-space:nowrap">${label}</td>
        <td style="padding:7px 16px;font-size:.86rem;color:#212529">${value}</td>
    </tr>`;
}
function dSection(title, rows) {
    return `<div style="padding:12px 20px 4px">
        <span style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#adb5bd">${title}</span>
    </div>
    <table class="table table-sm mb-0" style="border-top:1px solid #f0f0f0">${rows}</table>`;
}

function showDetail(row) {
    const today   = new Date(); today.setHours(0,0,0,0);
    const expDate = new Date(row.tanggal_expired);
    const selisih = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
    let stLabel, stClass, stIcon;
    if (selisih < 0)       { stLabel = 'Expired';     stClass = 'danger';  stIcon = 'bx-x-circle'; }
    else if (selisih <= 30){ stLabel = 'Hampir Exp.'; stClass = 'warning'; stIcon = 'bx-error'; }
    else                   { stLabel = 'Aktif';       stClass = 'success'; stIcon = 'bx-check-circle'; }

    document.getElementById('modalDetailJudul').textContent = row.nama_sertifikasi || '-';
    document.getElementById('modalDetailMeta').innerHTML =
        `<span class="badge bg-${stClass} rounded-pill me-2" style="font-size:.72rem">
            <i class="bx ${stIcon} me-1"></i>${stLabel}
         </span>
         <span style="font-size:.78rem;color:#6c757d">ID #${row.id} &middot; ${esc(row.created_at||'')}</span>`;

    const fileHtml = row.file_sertifikat
        ? `<a href="/uploads/sertifikasi/${esc(row.file_sertifikat)}" target="_blank" class="btn btn-sm btn-outline-success">
               <i class="bx bx-file me-1"></i>Lihat File
           </a>`
        : `<span class="badge bg-secondary">Belum diupload</span>`;

    document.getElementById('modalDetailBody').innerHTML =
        dSection('Karyawan', [
            dRow('Nama Karyawan', `<strong>${esc(row.nama_karyawan)}</strong>`),
            dRow('NIK',           esc(row.nik)),
            dRow('Jabatan',       esc(row.jabatan)),
        ].join('')) +
        dSection('Data Sertifikasi', [
            dRow('Nama Sertifikasi',   esc(row.nama_sertifikasi)),
            dRow('Nomor Sertifikat',   row.nomor_sertifikat ? esc(row.nomor_sertifikat) : '<span class="text-muted fst-italic">-</span>'),
            dRow('Lembaga Sertifikasi',esc(row.lembaga_sertifikasi)),
            dRow('Kota Pelaksanaan',   esc(row.kota_pelaksanaan)),
            dRow('Tanggal Sertifikasi',esc(row.tanggal_sertifikasi)),
            dRow('Masa Berlaku',       `${esc(row.masa_berlaku)} Bulan`),
            dRow('Tanggal Expired',    `<strong class="text-${stClass}">${esc(row.tanggal_expired)}</strong>`),
        ].join('')) +
        dSection('File Sertifikat', [
            dRow('File', fileHtml),
        ].join('')) +
        `<div style="height:12px"></div>`;

    document.getElementById('modalEditLink').href = '{{ url("perusahaan/sertifikasi") }}/' + row.id + '/edit';
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

function filterTable() {
    const kw  = document.getElementById('searchInput').value.toLowerCase();
    const st  = document.getElementById('filterStatus').value;
    const fil = document.getElementById('filterFile').value;

    document.querySelectorAll('#tabelSertifikasi tbody tr[data-status]').forEach(tr => {
        let match = tr.textContent.toLowerCase().includes(kw);
        if (st)  match = match && tr.dataset.status === st;
        if (fil) match = match && tr.dataset.file   === fil;
        tr.style.display = match ? '' : 'none';
    });
}
</script>
