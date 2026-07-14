{{--
    VIEW PARTIAL: admin/dashboard.blade.php
    PENTING: Tidak boleh @extends layout — file ini di-inject via AJAX ke #page-content
    Jika diakses langsung (non-AJAX), DashboardController akan wrap dengan layouts.admin
--}}

{{-- ── CSS Dashboard (sama persis dengan home.php) ──────────────────────── --}}
<style>
.modern-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid rgba(0,0,0,0.04); border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.04);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative; overflow: hidden;
}
.modern-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: 16px 16px 0 0;
    background: linear-gradient(90deg, var(--card-glow, #0d6efd), var(--card-glow-to, #0dcaf0));
    opacity: 0; transition: opacity 0.35s ease;
}
.modern-card:hover::before { opacity: 1; }
.modern-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
.stat-card:hover .stat-icon-wrapper { transform: scale(1.08) rotate(3deg); }
.stat-icon-wrapper {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
}
.stat-icon-gradient {
    background: linear-gradient(135deg, var(--bg-color) 0%, var(--bg-dark) 100%);
    box-shadow: 0 4px 15px var(--shadow-color);
}

/* Pulse alert */
.pulse-alert {
    animation: pulseAlert 2s ease-in-out infinite;
}
@keyframes pulseAlert {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
    50% { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
}

.ring-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 14px; border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;
}
.ring-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
.ring-card.ring-1 { border-left: 4px solid #1e293b; }
.ring-card.ring-2 { border-left: 4px solid #475569; }
.ring-card.ring-3 { border-left: 4px solid #94a3b8; }
.ring-card.ring-4 { border-left: 4px solid #cbd5e1; }
.ring-card.ring-none { border-left: 4px solid #94a3b8; }

.progress-enhanced {
    height: 10px; border-radius: 99px; background: #e9ecef;
    overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
}
.progress-enhanced .progress-bar {
    border-radius: 99px;
    background: linear-gradient(90deg, var(--bar-color) 0%, var(--bar-light) 100%);
    box-shadow: 0 2px 8px var(--bar-shadow);
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.section-header { position: relative; padding-bottom: 8px; }
.section-header::after {
    content: ''; position: absolute; bottom: 0; left: 0;
    width: 40px; height: 3px; border-radius: 2px;
    background: linear-gradient(90deg, #0d6efd, #0dcaf0);
}

/* Avatar circle */
.avatar-circle {
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 50%; font-weight: 700;
}

/* Badge enhanced */
.badge-enhanced {
    padding: 4px 10px; font-weight: 500; border-radius: 99px;
}

/* Table modern */
.table-modern th {
    font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;
    color: #64748b; border-bottom: 2px solid #e9ecef; padding: 10px 8px;
}
.table-modern td { padding: 10px 8px; vertical-align: middle; border-color: #f1f5f9; }
.table-modern tbody tr { transition: all .2s ease; }
.table-modern tbody tr:hover { background: rgba(0,0,0,0.02); transform: translateX(4px); }

/* Ring legend */
.ring-legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
.fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
.delay-1 { animation-delay: 0.05s; }
.delay-2 { animation-delay: 0.10s; }
.delay-3 { animation-delay: 0.15s; }
.delay-4 { animation-delay: 0.20s; }
.delay-5 { animation-delay: 0.25s; }
</style>

{{-- ── Breadcrumb ────────────────────────────────────────────────────────── --}}
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Dashboard Admin</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax text-decoration-none"
                       data-url="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">Overview</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <span class="text-muted small bg-light rounded-pill px-3 py-2">
            <i class="bx bx-calendar me-1 text-primary"></i>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
        </span>
    </div>
</div>

{{-- ── ROW 1: 5 Stat Cards ───────────────────────────────────────────────── --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">

    {{-- Total Perusahaan --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:64px;height:64px;--bg-color:#4f46e5;--bg-dark:#3730a3;--shadow-color:rgba(79,70,229,0.3);">
                        <i class='bx bx-buildings fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Total Perusahaan</div>
                        <div class="fw-bold fs-4 lh-1 text-primary">{{ number_format($total_perusahaan,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-primary" style="font-size:.72rem"><i class="bx bx-check-shield me-1"></i>Terdaftar</small>
            </div>
        </div>
    </div>

    {{-- Total Karyawan --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:64px;height:64px;--bg-color:#0ea5e9;--bg-dark:#0284c7;--shadow-color:rgba(14,165,233,0.3);">
                        <i class='bx bx-group fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Total Karyawan</div>
                        <div class="fw-bold fs-4 lh-1" style="color:#0284c7">{{ number_format($total_karyawan,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small style="font-size:.72rem;color:#0ea5e9"><i class="bx bx-globe me-1"></i>Semua perusahaan</small>
            </div>
        </div>
    </div>

    {{-- Karyawan Aktif --}}
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:64px;height:64px;--bg-color:#10b981;--bg-dark:#059669;--shadow-color:rgba(16,185,129,0.3);">
                        <i class='bx bx-user-check fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Aktif</div>
                        <div class="fw-bold fs-4 lh-1 text-success">{{ number_format($total_aktif,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-success" style="font-size:.72rem"><i class="bx bx-trending-up me-1"></i>Karyawan aktif</small>
            </div>
        </div>
    </div>

    {{-- Total Temuan Audit --}}
    <div class="col fade-in-up delay-2">
        <a href="{{ route('admin.temuan-audit.index') }}" class="text-decoration-none nav-ajax"
           data-url="{{ route('admin.temuan-audit.index') }}" data-title="Temuan Audit">
        <div class="card h-100 modern-card stat-card" style="--card-glow:#f59e0b;--card-glow-to:#d97706;">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:64px;height:64px;--bg-color:#f59e0b;--bg-dark:#d97706;--shadow-color:rgba(245,158,11,0.3);">
                        <i class='bx bx-receipt fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Total Temuan</div>
                        <div class="fw-bold fs-4 lh-1" style="color:#d97706">{{ number_format($total_temuan,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small style="font-size:.72rem;color:#f59e0b"><i class="bx bx-list-check me-1"></i>Audit Monitoring</small>
            </div>
        </div>
        </a>
    </div>

    {{-- Temuan Open --}}
    <div class="col fade-in-up delay-2">
        <a href="{{ route('admin.temuan-audit.index') }}" class="text-decoration-none nav-ajax"
           data-url="{{ route('admin.temuan-audit.index') }}" data-title="Temuan Audit">
        <div class="card h-100 modern-card stat-card {{ $temuan_open > 0 ? 'border border-danger border-2 pulse-alert' : '' }}"
             style="--card-glow:#ef4444;--card-glow-to:#dc2626;">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3"
                         style="width:64px;height:64px;--bg-color:#ef4444;--bg-dark:#dc2626;--shadow-color:rgba(239,68,68,0.3);">
                        <i class='bx bx-error-circle fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Temuan Open</div>
                        <div class="fw-bold fs-4 lh-1 text-danger">{{ number_format($temuan_open,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-danger" style="font-size:.72rem"><i class="bx bx-alarm-exclamation me-1"></i>Perlu tindak lanjut</small>
            </div>
        </div>
        </a>
    </div>

</div>

{{-- ── ROW 2: Ring Wilayah ───────────────────────────────────────────────── --}}
<div class="row g-3 mb-4 fade-in-up delay-2">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#1e293b,#475569);">
                            <i class='bx bx-map fs-2 text-white'></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Karyawan per Ring Wilayah</h6>
                            <small class="text-muted">Karyawan aktif berdasarkan alamat (provinsi, kabupaten, kecamatan, desa)</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.ring-wilayah.index') }}"
                       class="btn btn-outline-secondary btn-sm btn-enhanced nav-ajax"
                       data-url="{{ route('admin.ring-wilayah.index') }}" data-title="Ring Wilayah">
                        <i class="bx bxs-tree me-1"></i>Kelola Ring Wilayah
                    </a>
                </div>
                <div class="row g-3">
                    @foreach([1=>$karyawan_ring1, 2=>$karyawan_ring2, 3=>$karyawan_ring3, 4=>$karyawan_ring4] as $r => $count)
                    <div class="col-md-2">
                        <div class="ring-card ring-{{ $r }} p-3 h-100">
                            <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600">Ring {{ $r }}</div>
                            <div class="fw-bold fs-3">{{ number_format($count,0,',','.') }}</div>
                            <small class="text-muted">karyawan</small>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-md-4">
                        <div class="ring-card ring-none p-3 h-100">
                            <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600">Belum terpetakan</div>
                            <div class="fw-bold fs-3">{{ number_format($karyawan_tanpa_ring,0,',','.') }}</div>
                            <small class="text-muted">alamat tidak di ring wilayah</small>
                        </div>
                    </div>
                </div>
                @if($total_aktif > 0)
                @php
                    $ringList = [
                        ['label'=>'Ring 1', 'count'=>$karyawan_ring1, 'color'=>'#1e293b'],
                        ['label'=>'Ring 2', 'count'=>$karyawan_ring2, 'color'=>'#475569'],
                        ['label'=>'Ring 3', 'count'=>$karyawan_ring3, 'color'=>'#64748b'],
                        ['label'=>'Ring 4', 'count'=>$karyawan_ring4, 'color'=>'#94a3b8'],
                        ['label'=>'Belum terpetakan', 'count'=>$karyawan_tanpa_ring, 'color'=>'#cbd5e1'],
                    ];
                @endphp
                <div class="mt-4">
                    <div class="d-flex align-items-center justify-content-between mb-2" style="font-size:.8rem">
                        <div class="d-flex gap-4 flex-wrap">
                            @foreach($ringList as $r)
                            <span>
                                <span class="ring-legend-dot me-1" style="background:{{ $r['color'] }}"></span>
                                {{ $r['label'] }} ({{ round($r['count']/$total_aktif*100) }}%)
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="progress" style="height:12px;border-radius:99px;background:#e9ecef;overflow:hidden">
                        @foreach($ringList as $r)
                        <div class="progress-bar" style="width:{{ round($r['count']/$total_aktif*100) }}%;background:{{ $r['color'] }}"
                             title="{{ $r['label'] }}: {{ round($r['count']/$total_aktif*100) }}%"></div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3: Gender + Unit ──────────────────────────────────────────────── --}}
<div class="row g-4 mb-4">
    {{-- Gender --}}
    <div class="col-md-6 fade-in-up delay-3">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <i class='bx bx-male-female fs-2 text-white'></i>
                    </div>
                    <div><div class="text-muted small">Komposisi Gender</div><div class="fw-bold">Semua Karyawan</div></div>
                </div>
                <div class="row g-0 text-center mb-4">
                    <div class="col-6 border-end">
                        <div class="text-muted mb-1" style="font-size:.75rem">Laki-laki</div>
                        <div class="fw-bold fs-4" style="color:#4f46e5">{{ number_format($total_laki,0,',','.') }}</div>
                        <div class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 mt-1" style="font-size:.7rem">
                            {{ $total_karyawan > 0 ? round($total_laki/$total_karyawan*100) : 0 }}%
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.75rem">Perempuan</div>
                        <div class="fw-bold fs-4" style="color:#ec4899">{{ number_format($total_perempuan,0,',','.') }}</div>
                        <div class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 mt-1" style="font-size:.7rem">
                            {{ $total_karyawan > 0 ? round($total_perempuan/$total_karyawan*100) : 0 }}%
                        </div>
                    </div>
                </div>
                @if($total_karyawan > 0)
                <div class="progress-enhanced">
                    <div class="progress-bar"
                         style="width:{{ round($total_laki/$total_karyawan*100) }}%;--bar-color:#4f46e5;--bar-light:#6366f1;--bar-shadow:rgba(79,70,229,0.3)"></div>
                    <div class="progress-bar"
                         style="width:{{ round($total_perempuan/$total_karyawan*100) }}%;--bar-color:#ec4899;--bar-light:#f472b6;--bar-shadow:rgba(236,72,153,0.3)"></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:.75rem">
                    <span class="text-primary"><i class="bx bx-male me-1"></i>Laki-laki</span>
                    <span class="text-danger"><i class="bx bx-female me-1"></i>Perempuan</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Distribusi Unit --}}
    <div class="col-md-6 fade-in-up delay-3">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#0ea5e9,#06b6d4);">
                        <i class='bx bx-buildings fs-2 text-white'></i>
                    </div>
                    <div><div class="text-muted small">Distribusi Unit</div><div class="fw-bold">Karyawan Aktif</div></div>
                </div>
                @php
                    $unitColors = [
                        ['#0ea5e9','#38bdf8','rgba(14,165,233,0.3)'],
                        ['#10b981','#34d399','rgba(16,185,129,0.3)'],
                        ['#f59e0b','#fbbf24','rgba(245,158,11,0.3)'],
                        ['#8b5cf6','#a78bfa','rgba(139,92,246,0.3)'],
                        ['#ec4899','#f472b6','rgba(236,72,153,0.3)'],
                    ];
                    $maxUnit = $distribusi_unit->max('jumlah') ?: 1;
                @endphp
                @forelse($distribusi_unit as $i => $row)
                @php $uc = $unitColors[$i % count($unitColors)]; @endphp
                <div class="d-flex align-items-center mb-3 gap-3">
                    <span class="badge px-2 py-1" style="font-size:.72rem;min-width:65px;background:{{ $uc[0] }}">
                        {{ $row->unit }}
                    </span>
                    <div class="flex-grow-1">
                        <div class="progress-enhanced">
                            <div class="progress-bar"
                                 style="width:{{ round($row->jumlah/$maxUnit*100) }}%;--bar-color:{{ $uc[0] }};--bar-light:{{ $uc[1] }};--bar-shadow:{{ $uc[2] }}"></div>
                        </div>
                    </div>
                    <span class="fw-bold" style="font-size:.85rem;min-width:28px;color:{{ $uc[0] }}">{{ $row->jumlah }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-4">Belum ada data unit</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 4: Distribusi Usia ───────────────────────────────────────────── --}}
<div class="row g-3 mb-4 fade-in-up delay-3">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#10b981,#059669);">
                        <i class='bx bx-calendar fs-2 text-white'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Distribusi Usia Karyawan</h6>
                        <small class="text-muted">Karyawan aktif saja (semua perusahaan)</small>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div id="chart-usia" style="min-height:280px;"></div>
                    </div>
                    <div class="col-md-4">
                        @php
                            $usiaLabels = ['< 25 Tahun','25 - 35 Tahun','36 - 45 Tahun','46 - 55 Tahun','> 55 Tahun'];
                            $usiaBgColors = ['#0d6efd','#0dcaf0','#198754','#ffc107','#dc3545'];
                        @endphp
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                @foreach($usia_data as $idx => $val)
                                <tr>
                                    <td><span class="d-inline-block rounded-circle me-2"
                                              style="width:10px;height:10px;background:{{ $usiaBgColors[$idx] }}"></span>
                                        {{ $usiaLabels[$idx] }}</td>
                                    <td class="text-end fw-bold">{{ number_format($val,0,',','.') }}</td>
                                    <td class="text-end text-muted small">
                                        {{ $total_aktif > 0 ? round($val/$total_aktif*100) : 0 }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 5: Distribusi Divisi ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4 fade-in-up delay-4">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#8b5cf6,#a855f7);">
                        <i class='bx bx-sitemap fs-2 text-white'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Distribusi Divisi</h6>
                        <small class="text-muted">Jumlah karyawan aktif per divisi (semua perusahaan)</small>
                    </div>
                </div>
                @php $maxDiv = $distribusi_divisi->max('jumlah') ?: 1; @endphp
                <div class="row g-2">
                    @forelse($distribusi_divisi as $i => $row)
                    @php $uc = $unitColors[$i % count($unitColors)]; @endphp
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted" style="font-size:.78rem;min-width:130px">{{ $row->div_desc }}</span>
                            <div class="flex-grow-1">
                                <div class="progress-enhanced">
                                    <div class="progress-bar"
                                         style="width:{{ round($row->jumlah/$maxDiv*100) }}%;--bar-color:{{ $uc[0] }};--bar-light:{{ $uc[1] }};--bar-shadow:{{ $uc[2] }}"></div>
                                </div>
                            </div>
                            <span class="fw-bold" style="font-size:.85rem;min-width:28px;color:{{ $uc[0] }}">{{ $row->jumlah }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-4">Belum ada data divisi</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 6: Distribusi Pendidikan + Karyawan Terbaru ─────────────────── --}}
<div class="row g-4 mb-4 fade-in-up delay-5">

    {{-- Distribusi Pendidikan --}}
    <div class="col-md-5">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#ec4899,#f43f5e);">
                        <i class='bx bx-graduation fs-2 text-white'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Distribusi Pendidikan</h6>
                        <small class="text-muted">Komposisi pendidikan terakhir (semua perusahaan)</small>
                    </div>
                </div>
                @php
                    $pendColors = [
                        ['bg'=>'#4f46e5','text'=>'#4f46e5'],
                        ['bg'=>'#10b981','text'=>'#059669'],
                        ['bg'=>'#f59e0b','text'=>'#d97706'],
                        ['bg'=>'#0ea5e9','text'=>'#0284c7'],
                        ['bg'=>'#ef4444','text'=>'#dc2626'],
                        ['bg'=>'#8b5cf6','text'=>'#7c3aed'],
                        ['bg'=>'#64748b','text'=>'#475569'],
                    ];
                    $maxPend = $distribusi_pendidikan->max('jumlah') ?: 1;
                @endphp
                @forelse($distribusi_pendidikan as $i => $row)
                @php $c = $pendColors[$i % count($pendColors)]; @endphp
                <div class="d-flex align-items-center mb-2 gap-3">
                    <div style="font-size:.82rem;width:60px;font-weight:500;color:{{ $c['text'] }}"
                         title="{{ $row->pendidikan_terakhir }}">
                        {{ $row->pendidikan_terakhir }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="progress-enhanced">
                            <div class="progress-bar"
                                 style="width:{{ round($row->jumlah/$maxPend*100) }}%;--bar-color:{{ $c['bg'] }};--bar-light:{{ $c['bg'] }}cc;--bar-shadow:{{ $c['bg'] }}40"></div>
                        </div>
                    </div>
                    <div class="fw-bold" style="width:28px;font-size:.82rem;text-align:right">{{ $row->jumlah }}</div>
                    <div class="text-muted" style="width:36px;font-size:.75rem">
                        {{ $total_karyawan > 0 ? round($row->jumlah/$total_karyawan*100) : 0 }}%
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">Belum ada data.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Karyawan Terbaru --}}
    <div class="col-md-7">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:linear-gradient(135deg,#06b6d4,#0ea5e9);">
                            <i class='bx bx-user-plus fs-2 text-white'></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 section-header">Karyawan Terbaru</h6>
                            <small class="text-muted">5 karyawan terakhir ditambahkan (semua perusahaan)</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama / NIK</th>
                                <th>Jabatan &amp; Divisi</th>
                                <th>Perusahaan</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($karyawan_baru as $row)
                            @php
                                $stCls  = $row->status === 'Aktif' ? 'success' : 'secondary';
                                $jkCls  = $row->jenis_kelamin === 'L' ? 'primary' : 'danger';
                                $avatarBg = $row->jenis_kelamin === 'L' ? '#4f46e5' : '#ec4899';
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle text-white flex-shrink-0"
                                             style="width:36px;height:36px;font-size:.85rem;background:{{ $avatarBg }};">
                                            {{ strtoupper(mb_substr($row->nama,0,1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.85rem;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                                                 title="{{ $row->nama }}">
                                                {{ $row->nama }}
                                            </div>
                                            <small class="text-muted">{{ $row->nik }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:.85rem;font-weight:500;">{{ $row->jabatan }}</div>
                                    <small class="text-muted">{{ $row->div_desc ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width:130px;font-size:.85rem" title="{{ $row->nama_perusahaan ?? '-' }}">
                                        {{ $row->nama_perusahaan ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-enhanced bg-{{ $jkCls }} badge-xs">
                                        {{ $row->unit ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-enhanced bg-{{ $stCls }} badge-xs">
                                        {{ $row->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada karyawan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── ApexCharts: Usia (library sudah dimuat di layout) ─────────────────── --}}
<script>
(function initUsiaChart() {
    var usiaData   = @json($usia_data);
    var usiaLabels = ['< 25 Thn', '25-35 Thn', '36-45 Thn', '46-55 Thn', '> 55 Thn'];
    var colors     = ['#0d6efd','#0dcaf0','#198754','#ffc107','#dc3545'];

    var el = document.getElementById('chart-usia');
    if (!el) return;

    // Hancurkan chart lama jika ada (penting untuk AJAX reload)
    if (el._apexChartInstance) {
        el._apexChartInstance.destroy();
    }

    var chart = new ApexCharts(el, {
        series: [{ name: 'Karyawan', data: usiaData }],
        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        plotOptions: { bar: { borderRadius: 8, columnWidth: '50%', distributed: true, dataLabels: { position: 'top' } } },
        dataLabels: { enabled: true, formatter: function(v) { return v; }, offsetY: -22, style: { fontSize: '13px', fontWeight: 600, colors: ['#64748b'] } },
        xaxis: { categories: usiaLabels, axisBorder: { show: false }, axisTicks: { show: false } },
        colors: colors,
        yaxis: { axisBorder: { show: false }, axisTicks: { show: false }, labels: { show: false } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { y: { formatter: val => val + ' orang' } },
        legend: { show: false }
    });
    chart.render();
    el._apexChartInstance = chart; // simpan referensi untuk destroy saat reload
})();
</script>
