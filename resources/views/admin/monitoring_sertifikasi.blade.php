@unless (request()->ajax())
<style id="sertifikasi-base-css">
.modern-card {
    background: #fff;
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.04);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.modern-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.08);
}
.stat-card:hover .stat-icon-wrapper {
    transform: scale(1.08) rotate(3deg);
}
.stat-icon-wrapper {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon-gradient {
    background: linear-gradient(135deg, var(--bg-color) 0%, var(--bg-dark) 100%);
    box-shadow: 0 4px 15px var(--shadow-color);
}
.badge-enhanced {
    padding: 6px 12px;
    border-radius: 99px;
    font-weight: 600;
    font-size: 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.btn-enhanced {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.25s ease;
}
.btn-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.section-header {
    position: relative;
    padding-bottom: 8px;
}
.section-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    border-radius: 2px;
    background: linear-gradient(90deg, #0d6efd, #0dcaf0);
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.top-item {
    background: linear-gradient(145deg, #fff 0%, #fafafa 100%);
    border-radius: 10px;
    padding: 10px 12px;
    transition: all 0.2s ease;
    border: 1px solid rgba(0,0,0,0.03);
}
.top-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.pulse-alert {
    animation: pulse-ring 2s infinite;
}
@keyframes pulse-ring {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

</style>
@endunless

<script>
(function(){
    var id = 'sertifikasi-persist-css';
    if (document.getElementById(id)) return;
    var src = document.getElementById('sertifikasi-base-css');
    var cssText = src ? src.textContent :
`.modern-card{background:#fff;border:1px solid rgba(0,0,0,0.04);border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.06),0 1px 4px rgba(0,0,0,0.04);transition:all 0.35s cubic-bezier(0.4,0,0.2,1);position:relative;overflow:hidden}
.modern-card:hover{transform:translateY(-4px);box-shadow:0 8px 30px rgba(0,0,0,0.12),0 4px 12px rgba(0,0,0,0.08)}
.stat-card:hover .stat-icon-wrapper{transform:scale(1.08) rotate(3deg)}
.stat-icon-wrapper{transition:all 0.4s cubic-bezier(0.4,0,0.2,1);border-radius:14px;display:flex;align-items:center;justify-content:center}
.stat-icon-gradient{background:linear-gradient(135deg,var(--bg-color) 0%,var(--bg-dark) 100%);box-shadow:0 4px 15px var(--shadow-color)}
.badge-enhanced{padding:6px 12px;border-radius:99px;font-weight:600;font-size:0.75rem;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.btn-enhanced{border-radius:10px;font-weight:500;transition:all 0.25s ease}
.btn-enhanced:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,0.15)}
.section-header{position:relative;padding-bottom:8px}
.section-header::after{content:'';position:absolute;bottom:0;left:0;width:40px;height:3px;border-radius:2px;background:linear-gradient(90deg,#0d6efd,#0dcaf0)}
.top-item{background:linear-gradient(145deg,#fff 0%,#fafafa 100%);border-radius:10px;padding:10px 12px;transition:all 0.2s ease;border:1px solid rgba(0,0,0,0.03)}
.top-item:hover{transform:translateX(4px);box-shadow:0 4px 12px rgba(0,0,0,0.06)}
.pulse-alert{animation:pulse-ring 2s infinite}
@keyframes pulse-ring{0%{box-shadow:0 0 0 0 rgba(220,53,69,0.4)}70%{box-shadow:0 0 0 10px rgba(220,53,69,0)}100%{box-shadow:0 0 0 0 rgba(220,53,69,0)}}
`;
    cssText += '#page-content{opacity:1!important;transition:none!important}.fade-in-up{animation:none!important}';
    var s = document.createElement('style');
    s.id = id;
    s.textContent = cssText;
    document.head.appendChild(s);
})();
</script>

<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Monitoring Sertifikasi</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Monitoring Sertifikasi</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">
        @if ($stat_expired > 0)
        <span class="badge badge-enhanced bg-danger pulse-alert">
            <i class="bx bx-x-circle me-1"></i>{{ $stat_expired }} Expired
        </span>
        @endif
    </div>
</div>

<!-- Stat Cards -->
<div id="sertifikasiStatCards" class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">

    {{-- Total --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card" role="button" data-filter-status="" data-filter-file=""
             style="{{ !$filter_status && !$filter_file ? 'border:2px solid #4f46e5' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:60px;height:60px;--bg-color:#4f46e5;--bg-dark:#3730a3;--shadow-color:rgba(79,70,229,0.3);">
                        <i class="bx bxs-certification fs-2 text-white"></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.72rem">Total</div>
                        <div class="fw-bold fs-4 lh-1 text-primary">{{ number_format($stat_total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-primary" style="font-size:.7rem"><i class="bx bx-list-ul me-1"></i>Semua sertifikasi</small>
            </div>
        </div>
    </div>

    {{-- Aktif --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card" role="button" data-filter-status="aktif" data-filter-file=""
             style="{{ $filter_status === 'aktif' ? 'border:2px solid #10b981' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:60px;height:60px;--bg-color:#10b981;--bg-dark:#059669;--shadow-color:rgba(16,185,129,0.3);">
                        <i class="bx bx-check-circle fs-2 text-white"></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.72rem">Aktif</div>
                        <div class="fw-bold fs-4 lh-1 text-success">{{ number_format($stat_aktif, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-success" style="font-size:.7rem"><i class="bx bx-check-shield me-1"></i>> 30 hari</small>
            </div>
        </div>
    </div>

    {{-- Hampir Expired --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card" role="button" data-filter-status="hampir" data-filter-file=""
             style="{{ $filter_status === 'hampir' ? 'border:2px solid #f59e0b' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:60px;height:60px;--bg-color:#f59e0b;--bg-dark:#d97706;--shadow-color:rgba(245,158,11,0.3);">
                        <i class="bx bx-error fs-2 text-white"></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.72rem">Hampir Exp.</div>
                        <div class="fw-bold fs-4 lh-1" style="color:#d97706">{{ number_format($stat_hampir, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-warning" style="font-size:.7rem"><i class="bx bx-time me-1"></i>≤ 30 hari</small>
            </div>
        </div>
    </div>

    {{-- Expired --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card" role="button" data-filter-status="expired" data-filter-file=""
             style="{{ $filter_status === 'expired' ? 'border:2px solid #ef4444' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:60px;height:60px;--bg-color:#ef4444;--bg-dark:#dc2626;--shadow-color:rgba(239,68,68,0.3);">
                        <i class="bx bx-x-circle fs-2 text-white"></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.72rem">Expired</div>
                        <div class="fw-bold fs-4 lh-1 text-danger">{{ number_format($stat_expired, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-danger" style="font-size:.7rem"><i class="bx bx-error-alt me-1"></i>Kedaluwarsa</small>
            </div>
        </div>
    </div>

    {{-- Belum Upload --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card" role="button" data-filter-status="" data-filter-file="tidak"
             style="{{ $filter_file === 'tidak' ? 'border:2px solid #64748b' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:60px;height:60px;--bg-color:#64748b;--bg-dark:#475569;--shadow-color:rgba(100,116,139,0.3);">
                        <i class="bx bx-upload fs-2 text-white"></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.72rem">Belum Upload</div>
                        <div class="fw-bold fs-4 lh-1 text-secondary">{{ number_format($stat_no_file, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-secondary" style="font-size:.7rem"><i class="bx bx-file me-1"></i>File belum ada</small>
            </div>
        </div>
    </div>

</div>

<style>
/* Filter card: remove overflow hidden that can clip native select dropdowns,
   remove hover transform that interferes with pointer targeting */
.col-md-7.fade-in-up.delay-2 > .modern-card {
    overflow: visible;
}
.col-md-7.fade-in-up.delay-2 > .modern-card:hover {
    transform: none;
}
</style>

<!-- Row: Top Expired + Filter -->
<div class="row g-4 mb-4">

    {{-- Top Perusahaan Expired --}}
    <div id="sertifikasiTopExpired" class="col-md-5 fade-in-up delay-2">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);">
                        <i class="bx bx-error-circle fs-2 text-white"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Top Expired Terbanyak</h6>
                        <small class="text-muted">Perusahaan dengan sertifikat expired terbanyak</small>
                    </div>
                </div>

                @if ($top_expired->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="bx bx-shield-check fs-1 text-success d-block mb-2"></i>
                    Tidak ada sertifikat expired.
                </div>
                @else
                    @php $maxExp = $top_expired->max('jumlah') ?: 1; @endphp
                    @foreach ($top_expired as $i => $row)
                        @php $pct = round($row->jumlah / $maxExp * 100); @endphp
                        <div class="top-item d-flex align-items-center mb-2 gap-3">
                            <div class="text-white fw-bold rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:28px;height:28px;font-size:.75rem;background:#ef4444">{{ $i + 1 }}</div>
                            <div class="text-truncate flex-grow-1" style="font-size:.85rem;font-weight:500;" title="{{ $row->nama }}">
                                {{ $row->nama }}
                            </div>
                            <div style="width:70px">
                                <div class="progress" style="height:6px;border-radius:99px;">
                                    <div class="progress-bar bg-danger" style="width:{{ $pct }}%;border-radius:99px;"></div>
                                </div>
                            </div>
                            <span class="badge badge-enhanced bg-danger" style="font-size:.72rem">{{ $row->jumlah }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Panel Filter --}}
    <div class="col-md-7 fade-in-up delay-2">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#0ea5e9 0%,#06b6d4 100%);">
                        <i class="bx bx-filter-alt fs-2 text-white"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Filter Data</h6>
                        <small class="text-muted">Sesuaikan tampilan data sertifikasi</small>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}" id="sertifikasiFilterForm">
                    <input type="hidden" name="page" value="monitoring_sertifikasi">
                    <input type="hidden" name="search" id="searchHidden" value="{{ $search ?? '' }}">
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label small text-muted mb-1">Perusahaan / Vendor</label>
                            <select name="perusahaan_id" class="form-select">
                                <option value="">— Semua Perusahaan —</option>
                                @foreach ($all_perusahaan as $p)
                                <option value="{{ $p->id }}" {{ $filter_perusahaan == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Status Sertifikasi</label>
                            <select name="status" class="form-select">
                                <option value="">— Semua Status —</option>
                                <option value="aktif"   {{ $filter_status === 'aktif'   ? 'selected' : '' }}>Aktif (> 30 hari)</option>
                                <option value="hampir"  {{ $filter_status === 'hampir'  ? 'selected' : '' }}>Hampir Expired (≤ 30 hari)</option>
                                <option value="expired" {{ $filter_status === 'expired' ? 'selected' : '' }}>Expired / Kedaluwarsa</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">File Sertifikat</label>
                            <select name="file" class="form-select">
                                <option value="">— Semua —</option>
                                <option value="ada"   {{ $filter_file === 'ada'   ? 'selected' : '' }}>Sudah Upload</option>
                                <option value="tidak" {{ $filter_file === 'tidak' ? 'selected' : '' }}>Belum Upload</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button type="button" class="btn btn-primary px-4 btn-enhanced" id="btnFilterSertifikasi">
                                <i class="bx bx-search me-1"></i> Terapkan Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4 btn-enhanced" id="btnResetSertifikasi">
                                <i class="bx bx-reset me-1"></i> Reset
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Tabel Data -->
<div id="sertifikasiTableWrap">
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
            <div>
                <h6 class="fw-bold mb-0">Data Sertifikasi</h6>
                <small class="text-muted">
                    Menampilkan <strong>{{ $data->firstItem() ?? 0 }}–{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> data
                </small>
            </div>
            <div class="ms-auto">
                <input type="text" id="searchInput" class="form-control form-control-sm"
                       placeholder="Cari nama / sertifikasi / perusahaan..." style="width:240px"
                       value="{{ $search ?? '' }}">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="40">No</th>
                        <th>Perusahaan</th>
                        <th>Karyawan</th>
                        <th>Sertifikasi</th>
                        <th>Lembaga</th>
                        <th>Tgl Sertifikasi</th>
                        <th>Masa Berlaku</th>
                        <th width="150">Status / Expired</th>
                        <th width="80" class="text-center">File</th>
                    </tr>
                </thead>
                <tbody>
                @if ($data->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bx bx-certification fs-3 d-block mb-2"></i>
                            Tidak ada data ditemukan.
                        </td>
                    </tr>
                @else
                    @foreach ($data as $i => $r)
                        @php
                            $sisa = (int)$r->sisa_hari;
                            if ($sisa < 0) {
                                $badgeClass = 'danger';
                                $badgeLabel = 'Expired';
                                $badgeIcon = 'bx-x-circle';
                            } elseif ($sisa <= 30) {
                                $badgeClass = 'warning';
                                $badgeLabel = 'Hampir Exp.';
                                $badgeIcon = 'bx-error';
                            } else {
                                $badgeClass = 'success';
                                $badgeLabel = 'Aktif';
                                $badgeIcon = 'bx-check-circle';
                            }
                            $adaFile = !empty($r->file_sertifikat);
                        @endphp
                        <tr>
                            <td class="text-muted small">{{ $data->firstItem() + $loop->index }}</td>
                            <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                            <td>
                                <small class="fw-semibold">{{ $r->nama_karyawan ?? '-' }}</small>
                                @if (!empty($r->nik))
                                <br><small class="text-muted">{{ $r->nik }}</small>
                                @endif
                            </td>
                            <td>
                                <small class="fw-semibold">{{ $r->nama_sertifikasi }}</small>
                                @if (!empty($r->kota_pelaksanaan))
                                <br><small class="text-muted"><i class="bx bx-map-pin me-1"></i>{{ $r->kota_pelaksanaan }}</small>
                                @endif
                            </td>
                            <td><small>{{ $r->lembaga_sertifikasi }}</small></td>
                            <td><small>{{ $r->tanggal_sertifikasi ? date('d M Y', strtotime($r->tanggal_sertifikasi)) : '-' }}</small></td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $r->masa_berlaku }} Bulan</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $badgeClass }} rounded-pill px-2">
                                    <i class="bx {{ $badgeIcon }} me-1"></i>{{ $badgeLabel }}
                                </span>
                                <div style="font-size:.72rem">
                                    @if ($sisa < 0)
                                        <span class="text-danger fw-semibold">{{ abs($sisa) }} hari lalu</span>
                                    @elseif ($sisa <= 30)
                                        <span class="text-warning fw-semibold">{{ $sisa }} hari lagi</span>
                                    @else
                                        <span class="text-muted">{{ date('d M Y', strtotime($r->tanggal_expired)) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if ($adaFile)
                                    <a href="/uploads/sertifikasi/{{ $r->file_sertifikat }}"
                                       target="_blank" class="btn btn-sm btn-outline-success py-0 px-2" title="Lihat File">
                                        <i class="bx bx-file"></i>
                                    </a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
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
</div>

<script>
$(document).on('submit', '#sertifikasiFilterForm', function(e) {
    e.preventDefault();
    sertifikasiLoad();
});
$(document).on('change', '#sertifikasiFilterForm select', function() {
    sertifikasiLoad();
});
$(document).on('keyup', '#searchInput', function() {
    clearTimeout(window._sertifSearchTimer);
    var val = this.value;
    $('#searchHidden').val(val);
    window._sertifSearchTimer = setTimeout(sertifikasiLoad, 500);
});
$(document).on('click', '#btnFilterSertifikasi', function(e) {
    e.preventDefault();
    $('#searchHidden').val($('#searchInput').val());
    sertifikasiLoad();
});
$(document).on('click', '#sertifikasiStatCards .stat-card[role="button"]', function() {
    var f = document.getElementById('sertifikasiFilterForm');
    if (!f) return;
    f.querySelector('[name="status"]').value = $(this).data('filter-status') || '';
    f.querySelector('[name="file"]').value = $(this).data('filter-file') || '';
    sertifikasiLoad();
});
$(document).on('click', '#btnResetSertifikasi', function() {
    var f = document.getElementById('sertifikasiFilterForm');
    if (!f) return;
    f.reset();
    f.querySelector('[name="perusahaan_id"]').value = '';
    f.querySelector('[name="status"]').value = '';
    f.querySelector('[name="file"]').value = '';
    $('#searchInput').val('');
    $('#searchHidden').val('');
    sertifikasiLoad();
});
var _sertifikasiCache = {};
$('#searchHidden').val() && $('#searchInput').val($('#searchHidden').val());

function setSertifikasiContent(html) {
    var $doc = $(html);
    var $stats = $doc.find('#sertifikasiStatCards');
    var $top = $doc.find('#sertifikasiTopExpired');
    var $table = $doc.find('#sertifikasiTableWrap');
    if ($stats.length) $('#sertifikasiStatCards').replaceWith($stats);
    if ($top.length) $('#sertifikasiTopExpired').replaceWith($top);
    if ($table.length) $('#sertifikasiTableWrap').replaceWith($table);
}

function sertifikasiLoad() {
    var f = document.getElementById('sertifikasiFilterForm');
    if (!f) return;
    var params = new URLSearchParams(new FormData(f)).toString();
    sertifikasiLoadUrl(f.action + '?' + params);
}

function sertifikasiLoadUrl(url) {
    if (!url) return;
    if (_sertifikasiCache[url]) { setSertifikasiContent(_sertifikasiCache[url]); history.pushState(null, '', url); return; }
    $.ajax({
        url: url,
        type: 'GET',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(html) {
            _sertifikasiCache[url] = html;
            setSertifikasiContent(html);
            history.pushState(null, '', url);
        }
    });
}

</script>
