<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Bukti Kepesertaan BPJS</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Bukti Kepesertaan BPJS</li>
            </ol>
        </nav>
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

@if ($errors->any())
<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-error-alt"></i></div>
        <div class="ms-3">
            <h6 class="mb-0 text-white">Terjadi kesalahan:</h6>
            <ul class="mb-0 mt-1 text-white small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    {{-- BPJS Kesehatan --}}
    <div class="col-lg-6 mb-3">
        <div class="card h-100 border-top border-0 border-4 border-danger">
            <div class="card-body">
                <div class="card-title d-flex align-items-center gap-2 mb-3">
                    <i class="bx bxs-heart font-22 text-danger"></i>
                    <h5 class="mb-0 fw-bold">BPJS Kesehatan</h5>
                </div>

                @if (!$bpjs_kesehatan)
                <div class="border p-3 rounded mb-3">
                    <form method="POST" action="{{ route('perusahaan.bpjs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kategori" value="kesehatan">
                        <label class="form-label fw-semibold">Upload bukti kepesertaan</label>
                        <div class="input-group">
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <button type="submit" class="btn btn-danger">
                                <i class="bx bx-upload me-1"></i>Simpan
                            </button>
                        </div>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG &nbsp;&middot;&nbsp; Maks: 10 MB</div>
                    </form>
                </div>
                @endif

                <h6 class="fw-semibold mb-2">Dokumen saat ini</h6>
                @if ($bpjs_kesehatan)
                    @php
                        $ext = strtolower(pathinfo($bpjs_kesehatan->file, PATHINFO_EXTENSION));
                        $fileUrl = asset('uploads/bpjs/' . $bpjs_kesehatan->file);
                    @endphp
                    <div class="border rounded p-2 mb-3 bg-light">
                        @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                            <img src="{{ $fileUrl }}" class="img-fluid rounded d-block mx-auto" style="max-height:300px;object-fit:contain;">
                        @elseif ($ext === 'pdf')
                            <iframe src="{{ $fileUrl }}" style="width:100%;height:400px;" class="rounded border-0"></iframe>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bxs-file-doc font-50 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Pratinjau tidak tersedia untuk format file ini.</p>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-show me-1"></i>Buka File
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-warning"
                                onclick="openEditModal({{ $bpjs_kesehatan->id }}, '{{ $bpjs_kesehatan->file }}')">
                            <i class="bx bx-edit me-1"></i>Ganti File
                        </button>
                        <form action="{{ route('perusahaan.bpjs.destroy', $bpjs_kesehatan->id) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Yakin hapus bukti kepesertaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-5 border rounded bg-light">
                        <i class="bx bxs-file-doc font-50 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Belum ada dokumen BPJS Kesehatan yang diupload.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BPJS Ketenagakerjaan --}}
    <div class="col-lg-6 mb-3">
        <div class="card h-100 border-top border-0 border-4 border-primary">
            <div class="card-body">
                <div class="card-title d-flex align-items-center gap-2 mb-3">
                    <i class="bx bxs-briefcase font-22 text-primary"></i>
                    <h5 class="mb-0 fw-bold">BPJS Ketenagakerjaan</h5>
                </div>

                @if (!$bpjs_ketenagakerjaan)
                <div class="border p-3 rounded mb-3">
                    <form method="POST" action="{{ route('perusahaan.bpjs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kategori" value="ketenagakerjaan">
                        <label class="form-label fw-semibold">Upload bukti kepesertaan</label>
                        <div class="input-group">
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-upload me-1"></i>Simpan
                            </button>
                        </div>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG &nbsp;&middot;&nbsp; Maks: 10 MB</div>
                    </form>
                </div>
                @endif

                <h6 class="fw-semibold mb-2">Dokumen saat ini</h6>
                @if ($bpjs_ketenagakerjaan)
                    @php
                        $ext = strtolower(pathinfo($bpjs_ketenagakerjaan->file, PATHINFO_EXTENSION));
                        $fileUrl = asset('uploads/bpjs/' . $bpjs_ketenagakerjaan->file);
                    @endphp
                    <div class="border rounded p-2 mb-3 bg-light">
                        @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                            <img src="{{ $fileUrl }}" class="img-fluid rounded d-block mx-auto" style="max-height:300px;object-fit:contain;">
                        @elseif ($ext === 'pdf')
                            <iframe src="{{ $fileUrl }}" style="width:100%;height:400px;" class="rounded border-0"></iframe>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bxs-file-doc font-50 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Pratinjau tidak tersedia untuk format file ini.</p>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-show me-1"></i>Buka File
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-warning"
                                onclick="openEditModal({{ $bpjs_ketenagakerjaan->id }}, '{{ $bpjs_ketenagakerjaan->file }}')">
                            <i class="bx bx-edit me-1"></i>Ganti File
                        </button>
                        <form action="{{ route('perusahaan.bpjs.destroy', $bpjs_ketenagakerjaan->id) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Yakin hapus bukti kepesertaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-5 border rounded bg-light">
                        <i class="bx bxs-file-doc font-50 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Belum ada dokumen BPJS Ketenagakerjaan yang diupload.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Ganti File Modal --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold mb-0">Ganti File</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="formEdit">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File</label>
                        <div id="editFileInfo" class="mb-2 small"></div>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <div class="form-text text-muted">Kosongkan jika tidak ingin mengganti file.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        <i class="bx bx-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, fileName) {
    document.getElementById('formEdit').action = '/perusahaan/bpjs/' + id;
    if (fileName) {
        document.getElementById('editFileInfo').innerHTML =
            'File saat ini: <a href="/uploads/bpjs/' + fileName + '" target="_blank">' + fileName + '</a>';
    } else {
        document.getElementById('editFileInfo').innerHTML = 'Belum ada file.';
    }
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
