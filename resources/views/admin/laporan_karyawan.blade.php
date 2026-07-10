<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Karyawan</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Laporan Karyawan</h6>
        <form id="filterLapKaryawanForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="laporan_karyawan">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach ($all_perusahaan as $p)
                        <option value="{{ $p->id }}" {{ $filter_perusahaan == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Status Karyawan</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua Status —</option>
                        <option value="Aktif" {{ $filter_status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ $filter_status === 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Jenis Kelamin</label>
                    <select name="jk" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="L" {{ $filter_jk === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ $filter_jk === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Unit Kerja</label>
                    <select name="unit" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="Lokalkaryawan" {{ $filter_unit === 'Lokalkaryawan' ? 'selected' : '' }}>Lokal</option>
                        <option value="Organik" {{ $filter_unit === 'Organik' ? 'selected' : '' }}>Organik</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-reset-lap-karyawan"><i class="bx bx-reset me-1"></i>Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Table Card -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0">Data Laporan Karyawan</h6>
                <small class="text-muted">Menampilkan <strong>{{ count($data) }}</strong> baris karyawan</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle mb-0 table-report" id="tblLapKaryawan" data-export-title="Laporan Karyawan">
                <thead class="table-light">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Nama Karyawan</th>
                        <th>NIK</th>
                        <th>L/P</th>
                        <th>Jabatan</th>
                        <th>Unit</th>
                        <th>Divisi</th>
                        <th>Sub-Divisi</th>
                        <th>Mulai Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td><strong class="small">{{ $r->nama }}</strong></td>
                        <td><small class="text-muted">{{ $r->nik }}</small></td>
                        <td class="text-center"><small>{{ $r->jenis_kelamin }}</small></td>
                        <td><small>{{ $r->jabatan }}</small></td>
                        <td><small>{{ $r->unit }}</small></td>
                        <td><small>{{ $r->divisi ?? '-' }}</small></td>
                        <td><small>{{ $r->subdivisi ?? '-' }}</small></td>
                        <td><small>{{ $r->mulai_masuk_kerja ? date('d/m/Y', strtotime($r->mulai_masuk_kerja)) : '-' }}</small></td>
                        <td>
                            @if ($r->status === 'Aktif')
                                <span class="badge bg-success rounded-pill px-2" style="font-size: .7rem;">Aktif</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-2" style="font-size: .7rem;">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
