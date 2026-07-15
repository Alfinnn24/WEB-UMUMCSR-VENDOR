<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Statistik</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Statistik Kontrak Kerja</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Statistik Kontrak Kerja</h6>
        <form id="filterKontrakForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="monitoring_kontrak">
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
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Status Kontrak</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua Status —</option>
                        <option value="aktif" {{ $filter_status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="hampir" {{ $filter_status === 'hampir' ? 'selected' : '' }}>Hampir Berakhir (≤ 30 hari)</option>
                        <option value="selesai" {{ $filter_status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="belum" {{ $filter_status === 'belum' ? 'selected' : '' }}>Belum Mulai</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-reset-kontrak"><i class="bx bx-reset me-1"></i>Reset</button>
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
                        <p class="text-muted small mb-1">Total Kontrak</p>
                        <h4 class="fw-bold mb-0">{{ number_format($stat_total) }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                        <i class="bx bxs-file-blank fs-3"></i>
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
                        <p class="text-muted small mb-1">Hampir Berakhir</p>
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
                        <p class="text-muted small mb-1">Selesai / Berakhir</p>
                        <h4 class="fw-bold mb-0 text-secondary">{{ number_format($stat_selesai) }}</h4>
                    </div>
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2">
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
                        <p class="text-muted small mb-1">Belum Mulai</p>
                        <h4 class="fw-bold mb-0 text-info">{{ number_format($stat_belum) }}</h4>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded p-2">
                        <i class="bx bxs-time-five fs-3"></i>
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
                <h6 class="fw-bold mb-3"><i class="bx bx-pie-chart-alt-2 me-2 text-primary"></i>Status Kontrak</h6>
                <div class="d-flex justify-content-center align-items-center">
                    <div id="chart-kontrak-status" 
                         data-series="[{{ $stat_aktif }}, {{ $stat_hampir }}, {{ $stat_selesai }}, {{ $stat_belum }}]" 
                         data-labels='["Aktif", "Hampir Berakhir", "Selesai", "Belum Mulai"]'
                         style="min-height: 260px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>Top 5 Perusahaan Kontrak Terbanyak</h6>
                <div id="chart-kontrak-top" 
                     data-series="{{ json_encode($top_kontrak->pluck('jumlah')) }}" 
                     data-categories="{{ json_encode($top_kontrak->pluck('nama')) }}"
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
                <h6 class="fw-bold mb-0">Daftar Kontrak Kerja Perusahaan</h6>
                <small class="text-muted">Menampilkan <strong>{{ $data->total() }}</strong> data kontrak kerja</small>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0" id="kontrakTable">
                <thead class="table-dark">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Judul Kontrak</th>
                        <th>Nomor Kontrak</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Sisa Hari</th>
                        <th>TK (Target)</th>
                        <th>TK (Assigned)</th>
                        <th>Berkas</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    @php
                        $sisa = (int)$r->sisa_hari;
                        if ($today > $r->tgl_selesai) {
                            $badge_c = 'secondary';
                        } elseif ($sisa <= 30) {
                            $badge_c = 'warning';
                        } else {
                            $badge_c = 'success';
                        }
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td><small class="fw-bold">{{ $r->judul_kontrak }}</small></td>
                        <td><small class="text-muted">{{ $r->nomor_kontrak }}</small></td>
                        <td><small>{{ $r->tgl_mulai ? date('d/m/Y', strtotime($r->tgl_mulai)) : '-' }}</small></td>
                        <td><small class="text-{{ $badge_c }} fw-semibold">{{ $r->tgl_selesai ? date('d/m/Y', strtotime($r->tgl_selesai)) : '-' }}</small></td>
                        <td>
                            @if ($today > $r->tgl_selesai)
                                <span class="badge bg-secondary rounded-pill px-2">Berakhir</span>
                            @else
                                <span class="badge bg-{{ $badge_c }} rounded-pill px-2">{{ number_format($sisa) }} Hari</span>
                            @endif
                        </td>
                        <td class="text-center"><span class="badge bg-info rounded-pill px-2">{{ number_format($r->jumlah_tenaga_kerja) }}</span></td>
                        <td class="text-center"><span class="badge bg-primary rounded-pill px-2">{{ number_format($r->jml_assigned) }}</span></td>
                        <td>
                            @if (!empty($r->berkas_kontrak))
                                <a href="/uploads/kontrak/{{ $r->berkas_kontrak }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
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
