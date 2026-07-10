<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Statistik</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Statistik Sertifikasi</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Statistik Sertifikasi</h6>
        <form id="filterSertifikasiForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="monitoring_sertifikasi">
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
                    <label class="form-label small text-muted mb-1">Status Masa Berlaku</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua Status —</option>
                        <option value="aktif" {{ $filter_status === 'aktif' ? 'selected' : '' }}>Aktif (Lebih dari 30 hari)</option>
                        <option value="hampir" {{ $filter_status === 'hampir' ? 'selected' : '' }}>Hampir Expired (≤ 30 hari)</option>
                        <option value="expired" {{ $filter_status === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Berkas Sertifikat</label>
                    <select name="file" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="ada" {{ $filter_file === 'ada' ? 'selected' : '' }}>Ada File</option>
                        <option value="tidak" {{ $filter_file === 'tidak' ? 'selected' : '' }}>Belum Upload File</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-reset-sertifikasi"><i class="bx bx-reset me-1"></i>Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Summary Widgets -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Sertifikasi</p>
                        <h4 class="fw-bold mb-0">{{ number_format($stat_total) }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                        <i class="bx bxs-certification fs-3"></i>
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
                        <p class="text-muted small mb-1">Status Aktif</p>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($stat_aktif) }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded p-2">
                        <i class="bx bxs-badge-check fs-3"></i>
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
                        <p class="text-muted small mb-1">Hampir Expired</p>
                        <h4 class="fw-bold mb-0 text-warning">{{ number_format($stat_hampir) }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-2">
                        <i class="bx bxs-error-circle fs-3"></i>
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
                        <p class="text-muted small mb-1">Expired / Kedaluwarsa</p>
                        <h4 class="fw-bold mb-0 text-danger">{{ number_format($stat_expired) }}</h4>
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
                        <p class="text-muted small mb-1">Belum Upload</p>
                        <h4 class="fw-bold mb-0 text-secondary">{{ number_format($stat_no_file) }}</h4>
                    </div>
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2">
                        <i class="bx bxs-file-blank fs-3"></i>
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
                <h6 class="fw-bold mb-3"><i class="bx bx-pie-chart-alt-2 me-2 text-primary"></i>Status Sertifikasi</h6>
                <div class="d-flex justify-content-center align-items-center">
                    <div id="chart-sertifikasi-status" 
                         data-series="[{{ $stat_aktif }}, {{ $stat_hampir }}, {{ $stat_expired }}]" 
                         data-labels='["Aktif", "Hampir Expired", "Expired"]'
                         style="min-height: 260px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>Top 5 Perusahaan Expired Terbanyak</h6>
                <div id="chart-sertifikasi-top" 
                     data-series="{{ json_encode($top_expired->pluck('jumlah')) }}" 
                     data-categories="{{ json_encode($top_expired->pluck('nama')) }}"
                     style="min-height: 260px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Data Listing Card -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0">Daftar Sertifikasi Karyawan</h6>
                <small class="text-muted">Menampilkan <strong>{{ count($data) }}</strong> data sertifikasi</small>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0" id="sertifikasiTable">
                <thead class="table-light">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Karyawan</th>
                        <th>Sertifikasi</th>
                        <th>Lembaga</th>
                        <th>Tanggal Mulai</th>
                        <th>Masa</th>
                        <th>Expired</th>
                        <th>Status</th>
                        <th>Berkas</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    @php
                        $sisa = (int)$r->sisa_hari;
                        if ($sisa < 0) {
                            $badge_c = 'danger';
                            $badge_l = 'Expired';
                        } elseif ($sisa <= 30) {
                            $badge_c = 'warning';
                            $badge_l = 'Hampir Expired';
                        } else {
                            $badge_c = 'success';
                            $badge_l = 'Aktif';
                        }
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td>
                            <div class="small fw-bold">{{ $r->nama_karyawan ?? '-' }}</div>
                            <small class="text-muted">{{ $r->nik }}</small>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ $r->nama_sertifikasi }}</div>
                            <small class="text-muted">{{ $r->nomor_sertifikat ?? '-' }}</small>
                        </td>
                        <td><small>{{ $r->lembaga_sertifikasi }}</small></td>
                        <td><small>{{ $r->tanggal_sertifikasi ? date('d/m/Y', strtotime($r->tanggal_sertifikasi)) : '-' }}</small></td>
                        <td><small>{{ $r->masa_berlaku }} Bln</small></td>
                        <td><small class="text-{{ $badge_c }} fw-semibold">{{ $r->tanggal_expired ? date('d/m/Y', strtotime($r->tanggal_expired)) : '-' }}</small></td>
                        <td><span class="badge bg-{{ $badge_c }} rounded-pill px-2" style="font-size: .7rem;">{{ $badge_l }}</span></td>
                        <td>
                            @if (!empty($r->file_sertifikat))
                                <a href="/uploads/sertifikasi/{{ $r->file_sertifikat }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
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
    </div>
</div>
