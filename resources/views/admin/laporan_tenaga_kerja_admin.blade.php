<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Tenaga Kerja</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Laporan Tenaga Kerja</h6>
        <form id="filterLapTenakerForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="laporan_tenaga_kerja_admin">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach ($all_perusahaan as $p)
                        <option value="{{ $p->id }}" {{ $filter_perusahaan == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Tahun Laporan</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">— Semua Tahun —</option>
                        @foreach ($all_tahun as $t)
                        <option value="{{ $t }}" {{ $filter_tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill btn-reset-lap-tenaker"><i class="bx bx-reset me-1"></i>Reset</button>
                    <a href="/admin/laporan/export_tenaga_kerja.php" target="_blank"
                       class="btn btn-sm btn-success flex-fill"
                       onclick="event.preventDefault();var f=$('#filterLapTenakerForm');window.open('/admin/laporan/export_tenaga_kerja.php?'+f.serialize(),'_blank');">
                        <i class="bx bx-download me-1"></i> Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Table Card -->
<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0">Data Laporan Tenaga Kerja</h6>
                <small class="text-muted">Menampilkan <strong>{{ count($data_flat) }}</strong> data (Tahun {{ $filter_tahun }})</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle mb-0" id="tblLapTenaker">
                <thead class="table-dark">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Laporan</th>
                        <th class="text-center" width="130">Status Upload</th>
                        <th>Berkas</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data_flat as $i => $r)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><strong class="small fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</strong></td>
                        <td><small>{{ $r->nomor_surat ?? '-' }}</small></td>
                        <td><small>{{ $r->tgl_laporan ? date('d/m/Y', strtotime($r->tgl_laporan)) : '-' }}</small></td>
                        <td class="text-center">
                            @if ($r->status_upload === 'Sudah Upload')
                                <span class="badge bg-success badge-xs rounded-pill">Sudah Upload</span>
                            @else
                                <span class="badge bg-danger badge-xs rounded-pill">Belum Upload</span>
                            @endif
                        </td>
                        <td>
                            @if ($r->status_upload === 'Sudah Upload')
                                <a href="/uploads/laporan_tenaker/{{ $r->file_laporan }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bx bx-file"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted small fst-italic">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
