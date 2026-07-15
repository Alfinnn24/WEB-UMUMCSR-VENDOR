<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Statistik</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Statistik Tenaga Kerja</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Statistik Tenaga Kerja</h6>
        <form id="filterLaporanTenakerForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="monitoring_laporan_tenaker">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
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
                    <input type="number" name="tahun" class="form-control form-control-sm" placeholder="Contoh: {{ date('Y') }}" value="{{ $filter_tahun ?: '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Berkas Laporan</label>
                    <select name="file" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="ada" {{ $filter_file === 'ada' ? 'selected' : '' }}>Sudah Upload File</option>
                        <option value="tidak" {{ $filter_file === 'tidak' ? 'selected' : '' }}>Belum Upload File</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-reset-laporan-tenaker"><i class="bx bx-reset me-1"></i>Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Summary Widgets -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Laporan</p>
                        <h4 class="fw-bold mb-0">{{ number_format($stat_total) }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                        <i class="bx bxs-file-doc fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Sudah Upload</p>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($stat_sudah) }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded p-2">
                        <i class="bx bxs-check-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Belum Upload</p>
                        <h4 class="fw-bold mb-0 text-danger">{{ number_format($stat_belum) }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded p-2">
                        <i class="bx bxs-x-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Perusahaan Terdaftar</p>
                        <h4 class="fw-bold mb-0 text-info">{{ number_format($stat_perusahaan) }}</h4>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded p-2">
                        <i class="bx bxs-business fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bx bx-pie-chart-alt-2 me-2 text-primary"></i>Kelengkapan Laporan</h6>
                <div class="d-flex justify-content-center align-items-center">
                    <div id="chart-tenaker-status" 
                         data-series="[{{ $stat_sudah }}, {{ $stat_belum }}]" 
                         data-labels='["Sudah Upload", "Belum Upload"]'
                         style="min-height: 260px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>Top 5 Perusahaan Laporan Terbanyak</h6>
                <div id="chart-tenaker-top" 
                     data-series="{{ json_encode($top_reporting->pluck('jumlah')) }}" 
                     data-categories="{{ json_encode($top_reporting->pluck('nama')) }}"
                     style="min-height: 260px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Data Listing Card -->
<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0">Daftar Laporan Tenaga Kerja</h6>
                <small class="text-muted">Menampilkan <strong>{{ $data->total() }}</strong> data laporan</small>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0" id="laporanTenakerTable">
                <thead class="table-dark">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Laporan</th>
                        <th>Status Upload</th>
                        <th>Berkas</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    @php
                        $has_file = !empty($r->file_laporan);
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td><small>{{ $r->nomor_surat ?? '-' }}</small></td>
                        <td><small>{{ $r->tgl_laporan ? date('d/m/Y', strtotime($r->tgl_laporan)) : '-' }}</small></td>
                        <td>
                            @if ($has_file)
                                <span class="badge bg-success badge-xs rounded-pill">Sudah Upload</span>
                            @else
                                <span class="badge bg-danger badge-xs rounded-pill">Belum Upload</span>
                            @endif
                        </td>
                        <td>
                            @if ($has_file)
                                <a href="/uploads/laporan_tenaker/{{ $r->file_laporan }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bx bx-file"></i>
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

        @if ($data->hasPages())
        <div class="mt-3 pt-2 border-top">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>
