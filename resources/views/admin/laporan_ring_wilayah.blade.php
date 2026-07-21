<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Ring Wilayah</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Summary Stats Cards -->
<div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">
    <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:68px; min-height:76px;">
                        <i class='bx bx-map-pin fs-2'></i>
                    </div>
                    <div class="p-3">
                        <div class="text-muted" style="font-size:.75rem">Karyawan Ring 1</div>
                        <div class="fw-bold fs-4 lh-1">{{ number_format($grand_ring1, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:68px; min-height:76px;">
                        <i class='bx bx-map fs-2'></i>
                    </div>
                    <div class="p-3">
                        <div class="text-muted" style="font-size:.75rem">Karyawan Ring 2</div>
                        <div class="fw-bold fs-4 lh-1">{{ number_format($grand_ring2, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary text-white p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:68px; min-height:76px;">
                        <i class='bx bx-map-alt fs-2'></i>
                    </div>
                    <div class="p-3">
                        <div class="text-muted" style="font-size:.75rem">Karyawan Ring 3</div>
                        <div class="fw-bold fs-4 lh-1">{{ number_format($grand_ring3, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="bg-dark text-white p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:68px; min-height:76px;">
                        <i class='bx bx-pin fs-2'></i>
                    </div>
                    <div class="p-3">
                        <div class="text-muted" style="font-size:.75rem">Karyawan Ring 4</div>
                        <div class="fw-bold fs-4 lh-1">{{ number_format($grand_ring4, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-dark p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:68px; min-height:76px;">
                        <i class='bx bx-error fs-2'></i>
                    </div>
                    <div class="p-3">
                        <div class="text-muted" style="font-size:.75rem">Belum Terpetakan</div>
                        <div class="fw-bold fs-4 lh-1">{{ number_format($grand_no, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-1">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-3">
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-0"><i class="bx bxs-map-alt me-2 text-primary"></i>Laporan Ring Wilayah per Perusahaan</h6>
                <small class="text-muted">Karyawan aktif, dikelompokkan berdasarkan ring wilayah alamat masing-masing</small>
            </div>
            <a href="/admin/laporan/export_ring_wilayah.php" target="_blank"
               class="btn btn-sm btn-success flex-shrink-0">
                <i class="bx bx-download me-1"></i> Export Excel
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0" id="tblRingReport">
                <thead class="table-dark">
                    <tr>
                        <th width="40">#</th>
                        <th>Nama Perusahaan</th>
                        <th class="text-center" style="min-width:110px"><span class="badge bg-success rounded-pill px-3">Ring 1</span></th>
                        <th class="text-center" style="min-width:110px"><span class="badge bg-info rounded-pill px-3">Ring 2</span></th>
                        <th class="text-center" style="min-width:110px"><span class="badge bg-secondary rounded-pill px-3">Ring 3</span></th>
                        <th class="text-center" style="min-width:110px"><span class="badge bg-dark rounded-pill px-3">Ring 4</span></th>
                        <th class="text-center" style="min-width:130px"><span class="badge bg-warning rounded-pill px-3">Belum Terpetakan</span></th>
                        <th class="text-center" style="min-width:110px">Total Aktif</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    @php
                        $tot = max((int)$r->total_aktif, 1);
                        $p1  = round($r->ring1   / $tot * 100);
                        $p2  = round($r->ring2   / $tot * 100);
                        $p3  = round($r->ring3   / $tot * 100);
                        $p4  = round($r->ring4   / $tot * 100);
                        $pn  = round($r->no_ring / $tot * 100);
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold" style="font-size:.88rem">{{ $r->nama_perusahaan }}</div>
                            <small class="text-muted">ID: {{ $r->perusahaan_id }}</small>
                        </td>
                        <td class="text-center">
                            @if ($r->ring1 > 0)
                            <a href="/admin/laporan/export_ring_detail.php?perusahaan_id={{ $r->perusahaan_id }}&ring=Ring+1"
                               target="_blank" class="btn btn-sm btn-success py-0 px-2 d-inline-flex align-items-center gap-1"
                               title="Export detail karyawan Ring 1" style="font-size:.82rem">
                                <i class="bx bx-download"></i> {{ number_format($r->ring1, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                            <div style="font-size:.7rem;color:#6c757d">{{ $p1 }}%</div>
                        </td>
                        <td class="text-center">
                            @if ($r->ring2 > 0)
                            <a href="/admin/laporan/export_ring_detail.php?perusahaan_id={{ $r->perusahaan_id }}&ring=Ring+2"
                               target="_blank" class="btn btn-sm btn-info py-0 px-2 d-inline-flex align-items-center gap-1 text-white"
                               title="Export detail karyawan Ring 2" style="font-size:.82rem">
                                <i class="bx bx-download"></i> {{ number_format($r->ring2, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                            <div style="font-size:.7rem;color:#6c757d">{{ $p2 }}%</div>
                        </td>
                        <td class="text-center">
                            @if ($r->ring3 > 0)
                            <a href="/admin/laporan/export_ring_detail.php?perusahaan_id={{ $r->perusahaan_id }}&ring=Ring+3"
                               target="_blank" class="btn btn-sm btn-secondary py-0 px-2 d-inline-flex align-items-center gap-1"
                               title="Export detail karyawan Ring 3" style="font-size:.82rem">
                                <i class="bx bx-download"></i> {{ number_format($r->ring3, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                            <div style="font-size:.7rem;color:#6c757d">{{ $p3 }}%</div>
                        </td>
                        <td class="text-center">
                            @if ($r->ring4 > 0)
                            <a href="/admin/laporan/export_ring_detail.php?perusahaan_id={{ $r->perusahaan_id }}&ring=Ring+4"
                               target="_blank" class="btn btn-sm btn-dark py-0 px-2 d-inline-flex align-items-center gap-1"
                               title="Export detail karyawan Ring 4" style="font-size:.82rem">
                                <i class="bx bx-download"></i> {{ number_format($r->ring4, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                            <div style="font-size:.7rem;color:#6c757d">{{ $p4 }}%</div>
                        </td>
                        <td class="text-center">
                            @if ($r->no_ring > 0)
                            <a href="/admin/laporan/export_ring_detail.php?perusahaan_id={{ $r->perusahaan_id }}&ring=no_ring"
                               target="_blank" class="btn btn-sm py-0 px-2 d-inline-flex align-items-center gap-1 btn-warning text-dark"
                               style="font-size:.82rem"
                               title="Export detail karyawan Belum Terpetakan">
                                <i class="bx bx-download"></i> {{ number_format($r->no_ring, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                            <div style="font-size:.7rem;color:#6c757d">{{ $pn }}%</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary text-white rounded-pill px-3" style="font-size:.82rem">
                                {{ number_format($r->total_aktif, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="/admin/laporan/export_ring_wilayah.php?perusahaan_id={{ $r->perusahaan_id }}"
                               target="_blank"
                               class="btn btn-xs btn-outline-success py-0 px-2" style="font-size:.75rem"
                               title="Export Excel perusahaan ini">
                                <i class="bx bx-download"></i> XLS
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-center text-success">{{ number_format($grand_ring1, 0, ',', '.') }}</td>
                        <td class="text-center text-info">{{ number_format($grand_ring2, 0, ',', '.') }}</td>
                        <td class="text-center text-secondary">{{ number_format($grand_ring3, 0, ',', '.') }}</td>
                        <td class="text-center text-dark">{{ number_format($grand_ring4, 0, ',', '.') }}</td>
                        <td class="text-center text-warning">{{ number_format($grand_no, 0, ',', '.') }}</td>
                        <td class="text-center text-primary">{{ number_format($grand_total, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
