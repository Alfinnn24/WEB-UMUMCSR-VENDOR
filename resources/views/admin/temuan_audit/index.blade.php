{{--
VIEW PARTIAL: admin/temuan_audit/index.blade.php
Manajemen Temuan Audit CRUD dengan Modal AJAX & Filter (Tanpa Reload)
--}}

<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Temuan Audit</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Data Temuan Audit</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <button type="button" class="btn btn-primary px-4 btn-enhanced" data-bs-toggle="modal"
            data-bs-target="#addTemuanModal">
            <i class="bx bx-plus-circle me-1"></i>Tambah Temuan
        </button>
    </div>
</div>

<!-- Widgets Cards -->
<div class="row row-cols-1 row-cols-md-3 g-3 mb-3 fade-in-up delay-1">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden widgets-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 text-secondary fw-semibold">Total Temuan</p>
                        <h3 class="my-1 fw-bold">{{ number_format($cnt_all, 0, ',', '.') }}</h3>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white"><i class='bx bx-receipt'></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden widgets-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 text-secondary fw-semibold">Temuan Open</p>
                        <h3 class="my-1 fw-bold text-danger">{{ number_format($cnt_open, 0, ',', '.') }}</h3>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white"><i class='bx bx-error-circle'></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden widgets-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 text-secondary fw-semibold">Temuan Close</p>
                        <h3 class="my-1 fw-bold text-success">{{ number_format($cnt_close, 0, ',', '.') }}</h3>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white"><i class='bx bx-check-shield'></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-3 fade-in-up delay-2">
    <div class="card-body">
        <form id="filterTemuanForm" method="GET" action="{{ route('admin.temuan-audit.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold mb-1">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach($companies as $p)
                            <option value="{{ $p->id }}" {{ ($filters['perusahaan_id'] ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold mb-1">Tahun Audit</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">— Semua Tahun —</option>
                        @foreach($years as $thn)
                            <option value="{{ $thn }}" {{ ($filters['tahun'] ?? '') == $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua Status —</option>
                        <option value="Open" {{ ($filters['status'] ?? '') === 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Close" {{ ($filters['status'] ?? '') === 'Close' ? 'selected' : '' }}>Close</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill btn-reset-filter">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="temuanTable" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal Audit</th>
                        <th>Perusahaan</th>
                        <th>Temuan</th>
                        <th>Tindak Lanjut</th>
                        <th>Evaluasi</th>
                        <th>Status</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuan as $idx => $row)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ \Carbon\Carbon::parse($row->tanggal_audit)->format('d M Y') }}</td>
                            <td class="fw-semibold">{{ $row->nama_perusahaan }}</td>
                            <td>{{ Str::limit($row->temuan, 60) }}</td>
                            <td>
                                @if($row->tindak_lanjut)
                                    {{ Str::limit($row->tindak_lanjut, 60) }}
                                @else
                                    <span class="text-muted fst-italic">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if($row->evaluasi)
                                    {{ Str::limit($row->evaluasi, 60) }}
                                @else
                                    <span class="text-muted fst-italic">Belum dievaluasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-sm rounded-pill bg-{{ $row->status === 'Open' ? 'danger' : 'success' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Detail --}}
                                    <button type="button" class="btn btn-sm btn-info text-white btn-detail-temuan"
                                        data-id="{{ $row->id }}" title="Detail/Show">
                                        <i class="bx bx-info-circle"></i>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-sm btn-warning btn-edit-temuan"
                                        data-id="{{ $row->id }}" title="Edit">
                                        <i class="bx bx-edit-alt text-dark"></i>
                                    </button>

                                    {{-- Action: Evaluasi & Close --}}
                                    @if($row->status === 'Open')
                                        @if(empty($row->tindak_lanjut))
                                            <button class="btn btn-warning btn-sm text-dark px-2" title="Menunggu Tindak Lanjut Perusahaan" disabled>
                                                <i class="bx bx-time"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-success btn-sm btn-close-temuan px-2"
                                                data-id="{{ $row->id }}" title="Evaluasi & Close Temuan">
                                                <i class="bx bx-check-shield"></i>
                                            </button>
                                        @endif
                                    @else
                                        <button class="btn btn-secondary btn-sm" title="Closed" disabled>
                                            <i class="bx bx-lock-alt"></i>
                                        </button>
                                    @endif

                                    {{-- Delete --}}
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-temuan"
                                        data-id="{{ $row->id }}" data-name="{{ $row->nama_perusahaan }}" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bx bx-receipt fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data temuan audit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($temuan->hasPages())
        <div class="mt-3 pt-2 border-top">
            {{ $temuan->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ── MODAL: TAMBAH TEMUAN ───────────────────────────────────────────── --}}
<div class="modal fade" id="addTemuanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-primary">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary"><i class="bx bx-plus-circle me-2"></i>Tambah Temuan Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTemuanForm" method="POST" action="{{ route('admin.temuan-audit.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="addTemuanErrorAlert"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Audit <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_audit" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Perusahaan <span class="text-danger">*</span></label>
                        <select name="id_perusahaan" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Perusahaan --</option>
                            @foreach($companies as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Temuan <span class="text-danger">*</span></label>
                        <textarea name="temuan" class="form-control" rows="4" placeholder="Uraikan temuan audit disini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bx bx-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: EDIT TEMUAN ─────────────────────────────────────────────── --}}
<div class="modal fade" id="editTemuanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-warning">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-warning"><i class="bx bx-edit me-2"></i>Edit Temuan Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTemuanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editTemuanErrorAlert"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Audit <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_audit" id="edit_audit_tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Perusahaan <span class="text-danger">*</span></label>
                        <select name="id_perusahaan" id="edit_audit_perusahaan" class="form-select" required>
                            @foreach($companies as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Temuan <span class="text-danger">*</span></label>
                        <textarea name="temuan" id="edit_audit_temuan" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tindak Lanjut Perusahaan</label>
                        <textarea name="tindak_lanjut" id="edit_audit_tindak_lanjut" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Evaluasi Admin</label>
                        <textarea name="evaluasi" id="edit_audit_evaluasi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit_audit_status" class="form-select" required>
                            <option value="Open">Open</option>
                            <option value="Close">Close</option>
                        </select>
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

{{-- ── MODAL: EVALUASI & CLOSE TEMUAN ─────────────────────────────────── --}}
<div class="modal fade" id="closeTemuanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-success">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-success"><i class="bx bx-check-shield me-2"></i>Close Temuan Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="closeTemuanForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="closeTemuanErrorAlert"></div>

                    <table class="table table-borderless table-sm small mb-3">
                        <tr>
                            <td width="35%" class="fw-bold text-muted">Tanggal Audit</td>
                            <td width="5%">:</td>
                            <td id="close_audit_tanggal_txt"></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Perusahaan</td>
                            <td>:</td>
                            <td id="close_audit_perusahaan_txt" class="fw-semibold"></td>
                        </tr>
                    </table>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Uraian Temuan</label>
                        <div class="p-3 bg-light border rounded small" id="close_audit_temuan_txt" style="white-space: pre-line;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Tindak Lanjut Perusahaan</label>
                        <div class="p-3 bg-light border rounded small text-primary" id="close_audit_tindak_lanjut_txt" style="white-space: pre-line;"></div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Evaluasi Admin <span class="text-danger">*</span></label>
                        <textarea name="evaluasi" class="form-control" rows="4" placeholder="Masukkan evaluasi atas tindak lanjut yang diberikan..." required></textarea>
                        <small class="text-muted mt-1 d-block"><i class="bx bx-info-circle"></i> Evaluasi wajib diisi untuk menutup temuan audit ini.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4"><i class="bx bx-check-shield me-1"></i>Evaluasi & Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: DETAIL TEMUAN ───────────────────────────────────────────── --}}
<div class="modal fade" id="detailTemuanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-top border-0 border-4 border-info">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-info"><i class="bx bx-info-circle me-2"></i>Detail Temuan Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%" class="fw-bold">Tanggal Audit</td>
                                <td width="5%">:</td>
                                <td id="detail_audit_tanggal"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Perusahaan</td>
                                <td>:</td>
                                <td id="detail_audit_perusahaan" class="fw-semibold"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%" class="fw-bold">Status</td>
                                <td width="5%">:</td>
                                <td><span class="badge px-3 py-2 rounded-pill" id="detail_audit_status_badge"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr class="my-3">
                <div class="mb-3">
                    <h6 class="fw-bold text-dark"><i class="bx bx-receipt me-1"></i> Uraian Temuan</h6>
                    <div class="p-3 bg-light border rounded" id="detail_audit_temuan" style="white-space: pre-line;"></div>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold text-dark"><i class="bx bx-comment-detail me-1"></i> Tindak Lanjut Perusahaan</h6>
                    <div class="p-3 bg-light border rounded" id="detail_audit_tindak_lanjut" style="white-space: pre-line;"></div>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold text-dark"><i class="bx bx-check-shield me-1"></i> Evaluasi Admin</h6>
                    <div class="p-3 bg-light border rounded" id="detail_audit_evaluasi" style="white-space: pre-line;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
