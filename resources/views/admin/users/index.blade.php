{{--
VIEW PARTIAL: admin/users/index.blade.php
Manajemen Users CRUD dengan Modal AJAX (Tanpa Reload)
--}}

<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Users</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Users</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <button type="button" class="btn btn-primary px-4 btn-enhanced" data-bs-toggle="modal"
            data-bs-target="#addUserModal">
            <i class="bx bx-user-plus me-1"></i>Tambah User
        </button>
    </div>
</div>

<div class="row fade-in-up delay-1">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0 fw-bold"><i class="bx bxs-group me-1 text-primary"></i> Daftar Users</h5>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $users->total() }} Total User</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="usersTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>NID</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $idx => $row)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="fw-semibold">{{ $row->nid }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $row->role === 'admin' ? 'bg-danger' : ($row->role === 'perusahaan' ? 'bg-info text-dark' : 'bg-warning text-dark') }} px-2 py-1">
                                            {{ ucfirst($row->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(($row->status ?? 'aktif') === 'aktif')
                                            <span class="badge bg-success text-white px-2 py-1">
                                                <i class="bx bx-check-circle me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary text-white px-2 py-1">
                                                <i class="bx bx-x-circle me-1"></i>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- Detail --}}
                                            <button type="button" class="btn btn-sm btn-info text-white btn-detail"
                                                data-id="{{ $row->id }}" title="Detail">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                            {{-- Edit --}}
                                            <button type="button" class="btn btn-sm btn-warning btn-edit"
                                                data-id="{{ $row->id }}" title="Edit">
                                                <i class="bx bx-edit-alt text-dark"></i>
                                            </button>
                                            {{-- Delete --}}
                                            @if($row->id != session('user_id'))
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $row->id }}" data-name="{{ $row->nama }}" title="Hapus">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                <div class="mt-3 pt-2 border-top">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL: TAMBAH USER ──────────────────────────────────────────────── --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-primary">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary"><i class="bx bxs-user-plus me-2"></i>Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="addErrorAlert"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NID</label>
                        <input type="text" name="nid" class="form-control" placeholder="Masukkan NID" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap / PT</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap/PT" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group" id="show_hide_add_password">
                            <input type="password" name="password" class="form-control border-end-0"
                                placeholder="Password" required>
                            <a href="javascript:;" class="input-group-text bg-transparent toggle-pass"
                                data-target="#show_hide_add_password input">
                                <i class="bx bx-hide"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="perusahaan">Perusahaan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
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

{{-- ── MODAL: EDIT USER ────────────────────────────────────────────────── --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-warning">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-warning"><i class="bx bxs-user-detail me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editErrorAlert"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NID</label>
                        <input type="text" name="nid" id="edit_nid" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap / PT</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru <span class="text-muted small">(kosongkan
                                jika tidak diubah)</span></label>
                        <div class="input-group" id="show_hide_edit_password">
                            <input type="password" name="password" class="form-control border-end-0"
                                placeholder="Password Baru">
                            <a href="javascript:;" class="input-group-text bg-transparent toggle-pass"
                                data-target="#show_hide_edit_password input">
                                <i class="bx bx-hide"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select class="form-select" name="role" id="edit_role" required>
                            <option value="admin">Admin</option>
                            <option value="perusahaan">Perusahaan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="edit_status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 text-dark"><i
                            class="bx bx-save me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: DETAIL USER ──────────────────────────────────────────────── --}}
<div class="modal fade" id="detailUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-0 border-4 border-info">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-info"><i class="bx bx-info-circle me-2"></i>Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="text-center mb-3">
                    <img src="/assets/images/team.png" alt="User Avatar" class="rounded-circle border"
                        style="width:70px;height:70px;object-fit:cover;">
                    <h5 class="fw-bold mt-2 mb-0" id="detail_nama_title"></h5>
                    <span class="badge bg-light text-dark border" id="detail_role_badge"></span>
                </div>
                <hr>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="35%" class="fw-bold">NID</td>
                        <td width="5%">:</td>
                        <td id="detail_nid"></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama / PT</td>
                        <td>:</td>
                        <td id="detail_nama"></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Role</td>
                        <td>:</td>
                        <td id="detail_role"></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status</td>
                        <td>:</td>
                        <td><span id="detail_status"></span></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr class="my-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat Lengkap</td>
                        <td>:</td>
                        <td id="detail_alamat"></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama Admin (PIC)</td>
                        <td>:</td>
                        <td id="detail_nama_admin"></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nomor Admin (HP)</td>
                        <td>:</td>
                        <td id="detail_nomor_admin"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>