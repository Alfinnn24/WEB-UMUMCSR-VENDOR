<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan Tenaga Kerja</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Tenaga Kerja</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('perusahaan.laporan-tenaga-kerja.create') }}" class="btn btn-primary px-4 nav-ajax">
            <i class="bx bx-plus me-1"></i> Tambah Laporan
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
                <h5 class="mb-0 fw-bold">Daftar Laporan Tenaga Kerja</h5>
                <small class="text-muted">Total {{ $data->total() }} laporan</small>
            </div>
            <div class="ms-auto d-flex gap-2">
                <input type="text" id="searchInput" class="form-control form-control-sm"
                       placeholder="Cari nomor surat..." style="width:230px" onkeyup="filterTable()">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelLaporan">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Laporan</th>
                        <th>Tanggal Dibuat</th>
                        <th width="120" class="text-center">File</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @if ($data->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bx bx-file font-40 d-block mb-2"></i>
                            Belum ada laporan tenaga kerja.
                            <a href="{{ route('perusahaan.laporan-tenaga-kerja.create') }}" class="nav-ajax">Tambah sekarang</a>
                        </td>
                    </tr>
                @else
                    @foreach ($data as $i => $row)
                        @php
                            $adaFile = !empty($row->file_laporan);
                        @endphp
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold small">{{ $row->nomor_surat }}</div>
                            </td>
                            <td>
                                <small>{{ date('d M Y', strtotime($row->tgl_laporan)) }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ date('d M Y H:i', strtotime($row->created_at ?? now())) }}</small>
                            </td>
                            <td class="text-center">
                                @if ($adaFile)
                                    <a href="/uploads/laporan_tenaker/{{ $row->file_laporan }}"
                                       target="_blank" class="btn btn-sm btn-outline-success" title="Lihat File">
                                        <i class="bx bx-file me-1"></i>Lihat
                                    </a>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            onclick="showUpload({{ $row->id }}, '{{ addslashes($row->nomor_surat) }}')"
                                            title="Upload File">
                                        <i class="bx bx-upload me-1"></i>Upload
                                    </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('perusahaan.laporan-tenaga-kerja.edit', $row->id) }}"
                                       class="btn btn-sm btn-outline-warning nav-ajax" title="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('perusahaan.laporan-tenaga-kerja.destroy', $row->id) }}" method="POST" class="d-inline form-delete"
                                          onsubmit="return confirm('Yakin hapus data laporan ini?')">
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
                    <h6 class="modal-title fw-bold mb-0">Upload File Laporan</h6>
                    <small class="text-muted" id="uploadSubtitle">—</small>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="formUploadAction">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" name="file_laporan" class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                        <div class="form-text text-muted">Format: PDF, JPG, PNG, DOC, XLS &nbsp;&middot;&nbsp; Maks: 10 MB</div>
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

<script>
function showUpload(id, nomorSurat) {
    document.getElementById('uploadSubtitle').textContent = "Surat: " + nomorSurat;
    document.getElementById('formUploadAction').action = `{{ url('perusahaan/laporan-tenaga-kerja') }}/${id}/upload`;
    new bootstrap.Modal(document.getElementById('modalUpload')).show();
}

function filterTable() {
    const kw  = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tabelLaporan tbody tr').forEach(tr => {
        let match = tr.textContent.toLowerCase().includes(kw);
        tr.style.display = match ? '' : 'none';
    });
}
</script>
