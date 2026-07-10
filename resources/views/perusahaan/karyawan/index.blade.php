<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Data Karyawan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto d-flex gap-2">
        <button type="button" class="btn btn-warning px-4" data-bs-toggle="modal" data-bs-target="#modalImport">
            <i class="bx bx-upload me-1"></i> Import Excel
        </button>
        <a href="#" onclick="exportExcel()" class="btn btn-success px-4">
            <i class="bx bx-file me-1"></i> Export Excel
        </a>
        <a href="{{ route('perusahaan.karyawan.create') }}" class="btn btn-primary px-4 nav-ajax" data-title="Tambah Karyawan">
            <i class="bx bx-plus me-1"></i> Tambah Karyawan
        </a>
    </div>
</div>

@if (session('import_success'))
<div class="alert alert-success alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i></div>
        <div class="ms-3">
            <h6 class="mb-0 text-white">{{ session('import_success') }}</h6>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('import_error'))
<div class="alert alert-danger alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-x-circle"></i></div>
        <div class="ms-3">
            <h6 class="mb-0 text-white" style="white-space:pre-line">{{ session('import_error') }}</h6>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- ── Tabel ─────────────────────────────────────────────────── -->
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
            <div>
                <h5 class="mb-0 fw-bold">Daftar Karyawan Vendor</h5>
                <small class="text-muted">Total {{ count($data) }} karyawan</small>
            </div>
            <div class="ms-auto d-flex gap-2 flex-wrap">
                <input type="text" id="searchInput" class="form-control form-control-sm"
                       placeholder="Cari nama / NIK / jabatan..." style="width:220px" onkeyup="filterTable()">
                <select id="filterUnit" class="form-select form-select-sm" style="width:120px" onchange="filterTable()">
                    <option value="">Semua Unit</option>
                    <option value="UNIT 9">UNIT 9</option>
                    <option value="UNIT 12">UNIT 12</option>
                    <option value="UNIT 129">UNIT 129</option>
                </select>
                <select id="filterStatus" class="form-select form-select-sm" style="width:130px" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
                <select id="filterJK" class="form-select form-select-sm" style="width:140px" onchange="filterTable()">
                    <option value="">Semua J. Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelKaryawan">
                <thead class="table-light">
                    <tr>
                        <th width="45">#</th>
                        <th>Nama / NIK</th>
                        <th>Jabatan & Divisi</th>
                        <th>Unit</th>
                        <th>Kontak</th>
                        <th>Mulai Kerja</th>
                        <th width="105">Status</th>
                        <th width="130" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($data) === 0)
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bx bx-user-x font-40 d-block mb-2"></i>
                            Belum ada data karyawan.
                            <a href="{{ route('perusahaan.karyawan.create') }}" class="nav-ajax" data-title="Tambah Karyawan">Tambah sekarang</a>
                        </td>
                    </tr>
                @else
                    @foreach ($data as $i => $row)
                    @php
                        $stClass = $row->status === 'Aktif' ? 'success' : 'danger';
                        $stIcon = $row->status === 'Aktif' ? 'bx-check-circle' : 'bx-x-circle';
                    @endphp
                    <tr data-status="{{ $row->status }}" data-unit="{{ $row->unit }}" data-jk="{{ $row->jenis_kelamin }}">
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                                            {{ $row->jenis_kelamin === 'L' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-danger bg-opacity-10 text-danger' }}"
                                        style="width:36px;height:36px;font-weight:700;font-size:.85rem">
                                        {{ mb_strtoupper(mb_substr($row->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-truncate" style="max-width:200px" title="{{ $row->nama }}">
                                        {{ $row->nama }}
                                    </div>
                                    <small class="text-muted">{{ $row->nik }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold small">{{ $row->jabatan }}</div>
                            <small class="text-muted">
                                {{ $row->div_desc ?? '-' }}
                                @if ($row->subdiv_desc)
                                    &rsaquo; {{ $row->subdiv_desc }}
                                @endif
                            </small>
                        </td>
                        <td>
                            <span class="badge {{ $row->unit === 'UNIT 9' ? 'bg-primary' : 'bg-info' }} rounded-pill px-3">
                                {{ $row->unit }}
                            </span>
                        </td>
                        <td>
                            <div class="small"><i class="bx bx-phone me-1 text-muted"></i>{{ $row->no_hp }}</div>
                            @if ($row->email)
                            <div class="small text-muted text-truncate" style="max-width:160px" title="{{ $row->email }}">
                                <i class="bx bx-envelope me-1"></i>{{ $row->email }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <small>{{ $row->mulai_masuk_kerja ? date('d M Y', strtotime($row->mulai_masuk_kerja)) : '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $stClass }} rounded-pill px-3 py-2">
                                <i class="bx {{ $stIcon }} me-1"></i>{{ $row->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Detail -->
                                <button type="button" class="btn btn-sm btn-outline-primary btn-show-detail"
                                        data-id="{{ $row->id }}"
                                        title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                </button>
                                <!-- Edit -->
                                <a href="{{ route('perusahaan.karyawan.edit', $row->id) }}"
                                   class="btn btn-sm btn-outline-warning nav-ajax" data-title="Edit Karyawan" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <!-- Toggle Status -->
                                <form action="{{ route('perusahaan.karyawan.toggle-status', $row->id) }}" method="POST" class="d-inline form-toggle-status">
                                    @csrf
                                    <input type="hidden" name="new_status" value="{{ $row->status === 'Aktif' ? 'Nonaktif' : 'Aktif' }}">
                                    <button type="submit"
                                            class="btn btn-sm {{ $row->status === 'Aktif' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            data-status="{{ $row->status }}"
                                            title="{{ $row->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bx {{ $row->status === 'Aktif' ? 'bx-user-minus' : 'bx-user-check' }}"></i>
                                    </button>
                                </form>
                                <!-- Hapus -->
                                <form action="{{ route('perusahaan.karyawan.destroy', $row->id) }}" method="POST" class="d-inline form-delete-karyawan">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-nama="{{ $row->nama }}" title="Hapus Data">
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
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     MODAL DETAIL
     ══════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h6 class="modal-title fw-bold mb-1" id="modalDetailNama">—</h6>
                    <div id="modalDetailMeta"></div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="modalDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modalEditLink" class="btn btn-warning px-4 nav-ajax" data-title="Edit Karyawan" data-bs-dismiss="modal">
                    <i class="bx bx-edit me-1"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     MODAL IMPORT
     ══════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('perusahaan.karyawan.import-review') }}" method="POST" enctype="multipart/form-data" id="formImportExcel">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Import Karyawan via Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        Silakan download template, isi data karyawan, lalu upload kembali file Excel ke sini.
                    </p>
                    <div class="d-grid mb-4">
                        <a href="{{ route('perusahaan.karyawan.download-template') }}" class="btn btn-outline-primary">
                            <i class="bx bx-download me-1"></i> Download Template Excel
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel (.xlsx / .xls) <span class="text-danger">*</span></label>
                        <input type="file" name="file_import" id="fileImport" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Maksimal file size 5MB. Batas upload 50 baris data per file.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4" id="btnUpload" disabled>
                        <i class="bx bx-upload me-1"></i> Upload & Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     SCRIPT JAVASCRIPT LOCAL
     ══════════════════════════════════════════════════════════════ -->
<script>
// Filter Table Client Side
function filterTable() {
    const kw  = document.getElementById('searchInput').value.toLowerCase();
    const st  = document.getElementById('filterStatus').value;
    const un  = document.getElementById('filterUnit').value;
    const jk  = document.getElementById('filterJK').value;

    document.querySelectorAll('#tabelKaryawan tbody tr[data-status]').forEach(tr => {
        let match = tr.textContent.toLowerCase().includes(kw);
        if (st) match = match && tr.dataset.status === st;
        if (un) match = match && tr.dataset.unit   === un;
        if (jk) match = match && tr.dataset.jk     === jk;
        tr.style.display = match ? '' : 'none';
    });
}

// Export excel with filters
function exportExcel() {
    const kw = document.getElementById('searchInput').value;
    const st = document.getElementById('filterStatus').value;
    const un = document.getElementById('filterUnit').value;
    const jk = document.getElementById('filterJK').value;
    
    const params = new URLSearchParams({ keyword: kw, status: st, unit: un, jk: jk }).toString();
    window.open(`{{ route('perusahaan.karyawan.export') }}?${params}`, '_blank');
}

// Enable upload button when file selected
document.getElementById('fileImport').addEventListener('change', function() {
    document.getElementById('btnUpload').disabled = !this.files.length;
});

// Auto-open modal if import error exists
@if (session('import_error'))
setTimeout(() => {
    var modalEl = document.getElementById('modalImport');
    if(modalEl) {
        new bootstrap.Modal(modalEl).show();
    }
}, 500);
@endif
</script>
