{{--
VIEW PARTIAL: admin/informasi/index.blade.php
Manajemen Informasi CRUD dengan Modal AJAX (Tanpa Reload)
--}}

<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Informasi</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Informasi</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <button type="button" class="btn btn-primary px-4 btn-enhanced" data-bs-toggle="modal"
            data-bs-target="#addInformasiModal">
            <i class="bx bx-plus-circle me-1"></i>Tambah Informasi
        </button>
    </div>
</div>

<div class="row fade-in-up delay-1">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0 fw-bold"><i class="bx bxs-info-circle me-1 text-primary"></i> Daftar Informasi</h5>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">{{ count($informasi) }} Total Data</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="informasiTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th>Judul</th>
                                <th>File</th>
                                <th width="15%">Tanggal</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($informasi as $idx => $row)
                                @php
                                    $file_ext = strtolower(pathinfo($row->file, PATHINFO_EXTENSION));
                                    $icon_class = 'bx bxs-file';
                                    $icon_color = 'text-secondary';
                                    if (in_array($file_ext, ['pdf'])) { $icon_class = 'bx bxs-file-pdf'; $icon_color = 'text-danger'; }
                                    elseif (in_array($file_ext, ['doc', 'docx'])) { $icon_class = 'bx bxs-file-doc'; $icon_color = 'text-primary'; }
                                    elseif (in_array($file_ext, ['xls', 'xlsx'])) { $icon_class = 'bx bxs-file-export'; $icon_color = 'text-success'; }
                                    elseif (in_array($file_ext, ['jpg', 'jpeg', 'png'])) { $icon_class = 'bx bxs-file-image'; $icon_color = 'text-info'; }
                                    elseif (in_array($file_ext, ['ppt', 'pptx'])) { $icon_class = 'bx bxs-file-blank'; $icon_color = 'text-warning'; }
                                    elseif (in_array($file_ext, ['zip', 'rar'])) { $icon_class = 'bx bxs-file-archive'; $icon_color = 'text-dark'; }
                                    
                                    // Clean display file name
                                    $clean_name = $row->file;
                                    if (($pos = strpos($clean_name, '_')) !== false) {
                                        $clean_name = substr($clean_name, $pos + 1);
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="fw-semibold">{{ $row->judul }}</td>
                                    <td>
                                        <a href="/uploads/informasi/{{ $row->file }}" target="_blank" class="text-decoration-none">
                                            <i class="{{ $icon_class }} {{ $icon_color }} me-1"></i>
                                            {{ Str::limit($clean_name, 30) }}
                                        </a>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- Detail --}}
                                            <button type="button" class="btn btn-sm btn-info text-white btn-detail-info"
                                                data-id="{{ $row->id }}" title="Detail">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                            {{-- Edit --}}
                                            <button type="button" class="btn btn-sm btn-warning btn-edit-info"
                                                data-id="{{ $row->id }}" title="Edit">
                                                <i class="bx bx-edit-alt text-dark"></i>
                                            </button>
                                            {{-- Delete --}}
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-info"
                                                data-id="{{ $row->id }}" data-name="{{ $row->judul }}" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data informasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL: TAMBAH INFORMASI ────────────────────────────────────────── --}}
<div class="modal fade" id="addInformasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-primary">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary"><i class="bx bx-plus-circle me-2"></i>Tambah Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addInformasiForm" method="POST" action="{{ route('admin.informasi.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="addInfoErrorAlert"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Informasi <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul informasi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required>
                        <small class="text-muted d-block mt-1">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP, RAR. Maks 10MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bx bx-upload me-1"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: EDIT INFORMASI ──────────────────────────────────────────── --}}
<div class="modal fade" id="editInformasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-warning">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-warning"><i class="bx bx-edit me-2"></i>Edit Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editInformasiForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editInfoErrorAlert"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Informasi <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="edit_info_judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Saat Ini</label>
                        <div class="p-2 bg-light rounded d-flex align-items-center">
                            <i class="bx bxs-file text-secondary me-2 font-20"></i>
                            <a href="#" id="edit_info_current_file" target="_blank" class="text-decoration-none text-truncate"></a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ganti File <span class="text-muted font-normal">(opsional)</span></label>
                        <input type="file" name="file" class="form-control">
                        <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti file. Maks 10MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 text-dark"><i class="bx bx-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: DETAIL INFORMASI ────────────────────────────────────────── --}}
<div class="modal fade" id="detailInformasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-info">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-info"><i class="bx bx-info-circle me-2"></i>Detail Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="30%" class="fw-bold">Judul</td>
                        <td width="5%">:</td>
                        <td id="detail_info_judul"></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">File</td>
                        <td>:</td>
                        <td>
                            <a href="#" id="detail_info_file_link" target="_blank" class="text-decoration-none fw-semibold">
                                <i id="detail_info_file_icon" class="bx bxs-file me-1"></i>
                                <span id="detail_info_file_name"></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal</td>
                        <td>:</td>
                        <td id="detail_info_tanggal"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <a href="#" id="detail_info_download_btn" target="_blank" class="btn btn-primary px-4"><i class="bx bx-download me-1"></i>Download</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
