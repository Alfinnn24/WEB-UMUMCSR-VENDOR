<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Peraturan Perusahaan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Peraturan Perusahaan</li>
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

<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link nav-ajax {{ $tab === 'PP' ? 'active' : '' }}"
           href="{{ route('perusahaan.peraturan.index', ['tab' => 'PP']) }}"
           data-url="{{ route('perusahaan.peraturan.index', ['tab' => 'PP']) }}"
           data-title="Peraturan Perusahaan - PP">
            <i class="bx bxs-file-doc me-1"></i>PP
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link nav-ajax {{ $tab === 'PKB' ? 'active' : '' }}"
           href="{{ route('perusahaan.peraturan.index', ['tab' => 'PKB']) }}"
           data-url="{{ route('perusahaan.peraturan.index', ['tab' => 'PKB']) }}"
           data-title="Peraturan Perusahaan - PKB">
            <i class="bx bxs-file-doc me-1"></i>PKB
        </a>
    </li>
</ul>

<div class="row">
    <div class="col-xl-5">
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
                <div class="border p-3 rounded">
                    <div class="card-title d-flex align-items-center gap-2">
                        <i class="bx bx-plus-circle font-22 text-primary"></i>
                        <h5 class="mb-0 fw-bold">Tambah Peraturan</h5>
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('perusahaan.peraturan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="jenis" id="jenisInput" value="{{ $tab }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor <span class="text-danger">*</span></label>
                            <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror"
                                   placeholder="Masukkan nomor peraturan" value="{{ old('nomor') }}">
                            @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Format: PDF, DOC, DOCX &nbsp;&middot;&nbsp; Maks: 10 MB</div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-save me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h5 class="mb-0 fw-bold">Daftar Peraturan</h5>
                        <small class="text-muted" id="tabLabel">Total {{ $tab === 'PP' ? $data_pp->total() : $data_pkb->total() }} {{ $tab === 'PP' ? 'PP' : 'PKB' }}</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th width="100" class="text-center">File</th>
                                <th width="120" class="text-center">Status</th>
                                <th width="130" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="ppTableBody" style="{{ $tab !== 'PP' ? 'display:none' : '' }}">
                            @forelse ($data_pp as $i => $row)
                            <tr>
                                <td class="text-muted small">{{ $data_pp->firstItem() + $i }}</td>
                                <td class="fw-semibold small">{{ $row->nomor }}</td>
                                <td><small>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</small></td>
                                <td class="text-center">
                                    @if ($row->file)
                                        <a href="/uploads/peraturan/{{ $row->file }}" target="_blank"
                                           class="btn btn-sm btn-outline-success" title="Lihat File">
                                            <i class="bx bx-file me-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($row->is_active)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bx bx-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <form action="{{ route('perusahaan.peraturan.set-active', $row->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Aktifkan peraturan ini? Peraturan lain dengan jenis yang sama akan dinonaktifkan.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="bx bx-x-circle me-1"></i>Non Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="openEditModal({{ $row->id }})" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <form action="{{ route('perusahaan.peraturan.destroy', $row->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus peraturan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bx bxs-file-doc font-40 d-block mb-2"></i>
                                    Belum ada data PP.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tbody id="pkbTableBody" style="{{ $tab !== 'PKB' ? 'display:none' : '' }}">
                            @forelse ($data_pkb as $i => $row)
                            <tr>
                                <td class="text-muted small">{{ $data_pkb->firstItem() + $i }}</td>
                                <td class="fw-semibold small">{{ $row->nomor }}</td>
                                <td><small>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</small></td>
                                <td class="text-center">
                                    @if ($row->file)
                                        <a href="/uploads/peraturan/{{ $row->file }}" target="_blank"
                                           class="btn btn-sm btn-outline-success" title="Lihat File">
                                            <i class="bx bx-file me-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($row->is_active)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bx bx-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <form action="{{ route('perusahaan.peraturan.set-active', $row->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Aktifkan peraturan ini? Peraturan lain dengan jenis yang sama akan dinonaktifkan.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="bx bx-x-circle me-1"></i>Non Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="openEditModal({{ $row->id }})" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <form action="{{ route('perusahaan.peraturan.destroy', $row->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus peraturan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bx bxs-file-doc font-40 d-block mb-2"></i>
                                    Belum ada data PKB.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="ppPagination" style="{{ $tab !== 'PP' ? 'display:none' : '' }}">
                    @if ($data_pp->hasPages())
                    <div class="mt-3 pt-2 border-top">
                        {{ $data_pp->links() }}
                    </div>
                    @endif
                </div>
                <div id="pkbPagination" style="{{ $tab !== 'PKB' ? 'display:none' : '' }}">
                    @if ($data_pkb->hasPages())
                    <div class="mt-3 pt-2 border-top">
                        {{ $data_pkb->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold mb-0">Edit Peraturan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="formEdit">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="nomor" id="editNomor" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="editTanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File</label>
                        <div id="editFileInfo" class="mb-2 small"></div>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
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
function openEditModal(id) {
    fetch('/perusahaan/peraturan/' + id)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            document.getElementById('formEdit').action = '/perusahaan/peraturan/' + id;
            document.getElementById('editNomor').value = data.nomor;
            document.getElementById('editTanggal').value = data.tanggal;
            if (data.file) {
                document.getElementById('editFileInfo').innerHTML =
                    'File saat ini: <a href="/uploads/peraturan/' + data.file + '" target="_blank">' + data.file + '</a>';
            } else {
                document.getElementById('editFileInfo').innerHTML = 'Belum ada file.';
            }
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        })
        .catch(function() {
            alert('Gagal mengambil data.');
        });
}
</script>

