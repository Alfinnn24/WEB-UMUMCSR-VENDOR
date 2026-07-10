{{--
    VIEW PARTIAL: perusahaan/dashboard.blade.php
    PENTING: Tidak boleh @extends layout — file ini di-inject via AJAX ke #page-content
    Jika diakses langsung (non-AJAX), DashboardController akan wrap dengan layouts.perusahaan
--}}

{{-- ── CUSTOM STYLES - Modern Dashboard ── --}}
<style>
.modern-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.04);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.modern-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.modern-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.08);
}
.modern-card:hover::before {
    opacity: 1;
}
.stat-card {
    background: linear-gradient(145deg, #ffffff 0%, #fafbfc 100%);
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
.alert-banner {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 50%, #bd2130 100%);
    border: none;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
}
.alert-banner::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}
.alert-banner::after {
    content: '';
    position: absolute;
    bottom: -30%; left: -5%;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
    border-radius: 50%;
}
.progress-enhanced {
    height: 10px;
    border-radius: 99px;
    background: #e9ecef;
    overflow: hidden;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
}
.progress-enhanced .progress-bar {
    border-radius: 99px;
    background: linear-gradient(90deg, var(--bar-color) 0%, var(--bar-light) 100%);
    box-shadow: 0 2px 8px var(--bar-shadow);
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
}
.alert-item {
    background: linear-gradient(145deg, #fff 0%, #fafafa 100%);
    border-radius: 12px;
    padding: 14px 16px;
    transition: all 0.25s ease;
    border: 1px solid rgba(0,0,0,0.04);
}
.alert-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.alert-item.danger {
    border-left: 4px solid #dc3545;
}
.alert-item.warning {
    border-left: 4px solid #ffc107;
}
.table-modern {
    border-collapse: separate;
    border-spacing: 0;
}
.table-modern th {
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    padding: 14px 12px;
    border-bottom: 2px solid #dee2e6;
}
.table-modern td {
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}
.table-modern tbody tr {
    transition: background 0.2s ease;
}
.table-modern tbody tr:hover {
    background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
}
.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    transition: all 0.3s ease;
}
.avatar-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.btn-enhanced {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.btn-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.badge-enhanced {
    padding: 6px 12px;
    border-radius: 99px;
    font-weight: 600;
    font-size: 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.section-header {
    position: relative;
    padding-bottom: 8px;
}
.section-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 40px; height: 3px;
    border-radius: 2px;
    background: linear-gradient(90deg, #0d6efd, #0dcaf0);
}
@keyframes pulse-ring {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
.pulse-alert {
    animation: pulse-ring 2s infinite;
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
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }

.chart-container {
    position: relative;
    height: 100%;
    min-height: 180px;
}
</style>

{{-- ── Breadcrumb ── --}}
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax text-decoration-none"
                       data-url="{{ route('perusahaan.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Overview</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-muted small bg-light rounded-pill px-3 py-2">
            <i class="bx bx-calendar me-1 text-primary"></i>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
        </span>
        @if($sertif_hampir > 0)
        <a href="#" class="btn btn-warning btn-sm px-3 btn-enhanced pulse-alert">
            <i class="bx bx-bell me-1"></i>{{ $sertif_hampir }} Sertifikasi Hampir Expired
        </a>
        @endif
        @if($sertif_expired > 0)
        <a href="#" class="btn btn-danger btn-sm px-3 btn-enhanced">
            <i class="bx bx-error me-1"></i>{{ $sertif_expired }} Sudah Expired
        </a>
        @endif
    </div>
</div>

@if($temuan_open > 0)
<div class="alert-banner d-flex align-items-center py-4 px-4 mb-4 fade-in-up delay-1 shadow">
    <div class="text-white fs-1 me-3"><i class="bx bx-error-circle"></i></div>
    <div>
        <h6 class="mb-1 text-white fw-bold">Perhatian: Ada {{ $temuan_open }} Temuan Audit yang masih Open!</h6>
        <div class="text-white text-opacity-75 small">Admin telah memberikan temuan audit yang memerlukan tindak lanjut Anda.</div>
    </div>
    <div class="ms-auto flex-shrink-0">
         <a href="#" class="btn btn-light btn-sm rounded-pill px-4 fw-bold text-danger shadow-sm btn-enhanced">Lihat Detail Temuan</a>
    </div>
</div>
@endif

{{-- ── ROW 1: Stat Cards Karyawan ── --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-3 mb-4">
    <!-- Total Karyawan -->
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3" 
                         style="width:64px;height:64px;--bg-color:#4f46e5;--bg-dark:#3730a3;--shadow-color:rgba(79,70,229,0.3);">
                        <i class='bx bx-group fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Total Karyawan</div>
                        <div class="fw-bold fs-4 lh-1 text-primary">{{ number_format($total_karyawan,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-muted" style="font-size:.72rem"><i class="bx bx-info-circle me-1"></i>Semua status</small>
            </div>
        </div>
    </div>

    <!-- Aktif -->
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
                        <div class="fw-bold fs-4 lh-1" style="color:#059669">{{ number_format($total_aktif,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-success" style="font-size:.72rem"><i class="bx bx-trending-up me-1"></i>Karyawan aktif</small>
            </div>
        </div>
    </div>

    <!-- Nonaktif -->
    <div class="col fade-in-up delay-1">
        <div class="card h-100 modern-card stat-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3" 
                         style="width:64px;height:64px;--bg-color:#64748b;--bg-dark:#475569;--shadow-color:rgba(100,116,139,0.3);">
                        <i class='bx bx-user-minus fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Nonaktif</div>
                        <div class="fw-bold fs-4 lh-1 text-secondary">{{ number_format($total_nonaktif,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-muted" style="font-size:.72rem"><i class="bx bx-pause-circle me-1"></i>Tidak aktif</small>
            </div>
        </div>
    </div>

    <!-- Sertifikasi Aktif -->
    <div class="col fade-in-up delay-2">
        <div class="card h-100 modern-card stat-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3" 
                         style="width:64px;height:64px;--bg-color:#0ea5e9;--bg-dark:#0284c7;--shadow-color:rgba(14,165,233,0.3);">
                        <i class='bx bxs-certification fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Sertif. Aktif</div>
                        <div class="fw-bold fs-4 lh-1" style="color:#0284c7">{{ number_format($sertif_aktif,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small style="font-size:.72rem;color:#0ea5e9"><i class="bx bx-check-shield me-1"></i>Belum expired</small>
            </div>
        </div>
    </div>

    <!-- Hampir Expired -->
    <div class="col fade-in-up delay-2">
        <div class="card h-100 modern-card stat-card {{ $sertif_hampir > 0 ? 'border-warning border-2' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3" 
                         style="width:64px;height:64px;--bg-color:#f59e0b;--bg-dark:#d97706;--shadow-color:rgba(245,158,11,0.3);">
                        <i class='bx bx-error fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Hampir Exp.</div>
                        <div class="fw-bold fs-4 lh-1" style="color:#d97706">{{ number_format($sertif_hampir,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-warning" style="font-size:.72rem"><i class="bx bx-time me-1"></i>≤ 30 hari lagi</small>
            </div>
        </div>
    </div>

    <!-- Sudah Expired -->
    <div class="col fade-in-up delay-2">
        <div class="card h-100 modern-card stat-card {{ $sertif_expired > 0 ? 'border-danger border-2' : '' }}">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper stat-icon-gradient m-3" 
                         style="width:64px;height:64px;--bg-color:#ef4444;--bg-dark:#dc2626;--shadow-color:rgba(239,68,68,0.3);">
                        <i class='bx bx-x-circle fs-2 text-white'></i>
                    </div>
                    <div class="pe-3 py-3">
                        <div class="text-muted mb-1" style="font-size:.75rem">Expired</div>
                        <div class="fw-bold fs-4 lh-1 text-danger">{{ number_format($sertif_expired,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2 px-3">
                <small class="text-danger" style="font-size:.72rem"><i class="bx bx-error-alt me-1"></i>Perlu diperbarui</small>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 2: Charts (Gender, Unit, Sertifikasi Summary) ── --}}
<div class="row g-4 mb-4">
    <!-- Komposisi Gender -->
    <div class="col-md-4 fade-in-up delay-3">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <i class='bx bx-male-female fs-2 text-white'></i>
                    </div>
                    <div>
                        <div class="text-muted small">Komposisi Gender</div>
                        <div class="fw-bold">Karyawan Aktif</div>
                    </div>
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
                <div class="chart-container" style="height: 160px;">
                    <div id="genderChart"></div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Distribusi Unit -->
    <div class="col-md-4 fade-in-up delay-3">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);">
                        <i class='bx bx-buildings fs-2 text-white'></i>
                    </div>
                    <div>
                        <div class="text-muted small">Distribusi Unit</div>
                        <div class="fw-bold">Karyawan Aktif</div>
                    </div>
                </div>
                
                @php
                $u9  = 0; $u12 = 0;
                foreach($distribusi_unit as $ur) {
                    if(strtoupper($ur->unit) == 'UNIT 9') $u9 = $ur->jumlah;
                    if(strtoupper($ur->unit) == 'UNIT 12') $u12 = $ur->jumlah;
                }
                $utotal = $u9 + $u12;
                @endphp
                <div class="row g-0 text-center mb-4">
                    <div class="col-6 border-end">
                        <div class="text-muted mb-1" style="font-size:.75rem">Unit 9</div>
                        <div class="fw-bold fs-4" style="color:#0ea5e9">{{ number_format($u9,0,',','.') }}</div>
                        <div class="badge bg-info bg-opacity-10 text-info px-2 py-1 mt-1" style="font-size:.7rem">
                            {{ $utotal > 0 ? round($u9/$utotal*100) : 0 }}%
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.75rem">Unit 12</div>
                        <div class="fw-bold fs-4" style="color:#06b6d4">{{ number_format($u12,0,',','.') }}</div>
                        <div class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 mt-1" style="font-size:.7rem">
                            {{ $utotal > 0 ? round($u12/$utotal*100) : 0 }}%
                        </div>
                    </div>
                </div>

                @if(count($distribusi_unit) > 0)
                <div class="chart-container" style="height: 160px;">
                    <div id="unitChart"></div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ringkasan Sertifikasi -->
    <div class="col-md-4 fade-in-up delay-3">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
                        <i class='bx bxs-badge-check fs-2 text-white'></i>
                    </div>
                    <div>
                        <div class="text-muted small">Ringkasan Sertifikasi</div>
                        <div class="fw-bold">Total {{ $total_sertif }} sertifikasi</div>
                    </div>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: rgba(16,185,129,0.08);">
                        <span class="text-muted" style="font-size:.85rem"><i class="bx bx-check-circle me-2 text-success"></i>Aktif</span>
                        <span class="badge badge-enhanced bg-success">{{ $sertif_aktif }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: rgba(245,158,11,0.08);">
                        <span class="text-muted" style="font-size:.85rem"><i class="bx bx-error me-2 text-warning"></i>Hampir Expired</span>
                        <span class="badge badge-enhanced bg-warning text-dark">{{ $sertif_hampir }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: rgba(239,68,68,0.08);">
                        <span class="text-muted" style="font-size:.85rem"><i class="bx bx-x-circle me-2 text-danger"></i>Expired</span>
                        <span class="badge badge-enhanced bg-danger">{{ $sertif_expired }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: rgba(100,116,139,0.08);">
                        <span class="text-muted" style="font-size:.85rem"><i class="bx bx-upload me-2 text-secondary"></i>Belum Upload</span>
                        <span class="badge badge-enhanced bg-secondary">{{ $sertif_no_file }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3: Alert Sertifikasi & Distribusi Divisi ── --}}
<div class="row g-4 mb-4">
    <!-- Alert Sertifikasi Hampir / Sudah Expired -->
    <div class="col-md-6 fade-in-up delay-4">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h6 class="fw-bold mb-1 section-header">Perhatian — Sertifikasi</h6>
                        <small class="text-muted">Hampir atau sudah expired dalam 30 hari</small>
                    </div>
                </div>

                @if($alert_sertifikasi->isEmpty())
                <div class="text-center text-muted py-5">
                    <div class="mb-3">
                        <i class="bx bx-shield-check fs-1 text-success"></i>
                    </div>
                    <p class="mb-0">Semua sertifikasi masih berlaku lebih dari 30 hari.</p>
                </div>
                @else
                <div class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto;">
                    @foreach($alert_sertifikasi as $row)
                        @php
                        $sisa  = (int)$row->sisa_hari;
                        $cls   = $sisa < 0 ? 'danger' : 'warning';
                        $icon  = $sisa < 0 ? 'bx-x-circle' : 'bx-error';
                        $label = $sisa < 0 ? 'Expired ' . abs($sisa) . ' hari' : $sisa . ' hari lagi';
                        $bgIcon = $sisa < 0 ? '#ef4444' : '#f59e0b';
                        @endphp
                        <div class="alert-item {{ $cls }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width:36px;height:36px;background:{{ $bgIcon }};">
                                    <i class="bx {{ $icon }} text-white"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-truncate" style="font-size:.85rem" title="{{ $row->nama_sertifikasi }}">
                                        {{ $row->nama_sertifikasi }}
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        {{ $row->nama_karyawan }} <span class="mx-1">•</span> {{ $row->jabatan }}
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="badge badge-enhanced bg-{{ $cls }}">{{ $label }}</span>
                                    <div class="text-muted mt-1" style="font-size:.72rem">
                                        Exp: {{ \Carbon\Carbon::parse($row->tanggal_expired)->locale('id')->isoFormat('D MMM YYYY') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Distribusi Divisi -->
    <div class="col-md-6 fade-in-up delay-4">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);">
                        <i class='bx bx-sitemap fs-2 text-white'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Distribusi Divisi</h6>
                        <small class="text-muted">Jumlah karyawan aktif per divisi</small>
                    </div>
                </div>
                
                @php
                $max_div = $distribusi_divisi->max('jumlah') ?: 1;
                $div_colors = [
                    ['bg' => '#4f46e5', 'text' => '#4f46e5'],
                    ['bg' => '#10b981', 'text' => '#059669'],
                    ['bg' => '#f59e0b', 'text' => '#d97706'],
                    ['bg' => '#0ea5e9', 'text' => '#0284c7'],
                    ['bg' => '#ef4444', 'text' => '#dc2626'],
                    ['bg' => '#8b5cf6', 'text' => '#7c3aed']
                ];
                @endphp

                @if($distribusi_divisi->isEmpty())
                <div class="text-center text-muted py-4">Belum ada data divisi.</div>
                @else
                <div style="max-height: 380px; overflow-y: auto; padding-right: 5px;">
                    @foreach($distribusi_divisi as $ci => $row)
                        @php $color = $div_colors[$ci % count($div_colors)]; @endphp
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <div class="text-truncate" style="font-size:.82rem;width:140px;font-weight:500;" title="{{ $row->div_desc }}">
                                {{ $row->div_desc }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="progress-enhanced">
                                    <div class="progress-bar" 
                                         style="width:{{ round($row->jumlah/$max_div*100) }}%;--bar-color:{{ $color['bg'] }};--bar-light:{{ $color['bg'] }}99;--bar-shadow:{{ $color['bg'] }}40"></div>
                                </div>
                            </div>
                            <div class="fw-bold" style="font-size:.85rem;min-width:28px;text-align:right;color:{{ $color['text'] }}">
                                {{ $row->jumlah }}
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 4: Distribusi Pendidikan & Karyawan Terbaru ── --}}
<div class="row g-4 mb-4">
    <!-- Distribusi Pendidikan -->
    <div class="col-md-5 fade-in-up delay-4">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);">
                        <i class='bx bx-graduation fs-2 text-white'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 section-header">Distribusi Pendidikan</h6>
                        <small class="text-muted">Komposisi pendidikan terakhir karyawan</small>
                    </div>
                </div>
                
                @if($distribusi_pendidikan->isEmpty())
                <div class="text-center text-muted py-4">Belum ada data.</div>
                @else
                <div style="height: 250px; display: flex; justify-content: center; align-items: center;">
                    <div id="pendidikanChart" style="width: 100%;"></div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Karyawan Terbaru -->
    <div class="col-md-7 fade-in-up delay-4">
        <div class="card modern-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);">
                            <i class='bx bx-user-plus fs-2 text-white'></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 section-header">Karyawan Terbaru</h6>
                            <small class="text-muted">3 karyawan terakhir ditambahkan</small>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama / NIK</th>
                                <th>Jabatan & Divisi</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($karyawan_baru->isEmpty())
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada karyawan.</td></tr>
                        @else
                            @foreach($karyawan_baru as $row)
                                @php
                                $stCls  = $row->status === 'Aktif' ? 'success' : 'secondary';
                                $unitCls= $row->unit === 'UNIT 9' ? 'primary' : 'info';
                                $avatarBg = $row->jenis_kelamin === 'L' ? '#4f46e5' : '#ec4899';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle text-white flex-shrink-0" 
                                                 style="width:40px;height:40px;font-size:.9rem;background:{{ $avatarBg }};">
                                                {{ mb_strtoupper(mb_substr($row->nama,0,1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-truncate" style="font-size:.88rem;max-width:160px;" title="{{ $row->nama }}">
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
                                    <td class="text-center">
                                        <span class="badge badge-enhanced bg-{{ $unitCls }}" style="font-size:.72rem">
                                            {{ $row->unit }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-enhanced bg-{{ $stCls }}" style="font-size:.72rem">
                                            {{ $row->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── APEXCHARTS INITIALIZATION ── --}}
<script>
(function initDashboardCharts() {
    // Chart Komposisi Gender (Donut)
    var elGender = document.getElementById('genderChart');
    if (elGender) {
        if (elGender._apexChartInstance) elGender._apexChartInstance.destroy();
        var chartGender = new ApexCharts(elGender, {
            chart: { type: 'donut', height: 160, fontFamily: 'Roboto, sans-serif' },
            series: [{{ $total_laki }}, {{ $total_perempuan }}],
            labels: ['Laki-laki', 'Perempuan'],
            colors: ['#4f46e5', '#ec4899'],
            legend: { position: 'bottom', fontSize: '11px', usePointStyle: true, padding: 5 },
            plotOptions: { pie: { donut: { size: '70%' } } },
            dataLabels: { enabled: false }
        });
        chartGender.render();
        elGender._apexChartInstance = chartGender;
    }

    // Chart Distribusi Unit (Donut)
    var elUnit = document.getElementById('unitChart');
    if (elUnit) {
        if (elUnit._apexChartInstance) elUnit._apexChartInstance.destroy();
        @php
        $unit_labels = [];
        $unit_data_vals = [];
        $unit_colors_hex = ['#0ea5e9','#10b981','#f59e0b','#8b5cf6','#ec4899'];
        foreach ($distribusi_unit as $row) {
            $unit_labels[] = $row->unit;
            $unit_data_vals[] = $row->jumlah;
        }
        @endphp
        var chartUnit = new ApexCharts(elUnit, {
            chart: { type: 'donut', height: 160, fontFamily: 'Roboto, sans-serif' },
            series: @json($unit_data_vals),
            labels: @json($unit_labels),
            colors: @json(array_slice($unit_colors_hex, 0, count($distribusi_unit))),
            legend: { position: 'bottom', fontSize: '11px', usePointStyle: true, padding: 5 },
            plotOptions: { pie: { donut: { size: '70%' } } },
            dataLabels: { enabled: false }
        });
        chartUnit.render();
        elUnit._apexChartInstance = chartUnit;
    }

    // Chart Distribusi Pendidikan (Donut)
    var elPend = document.getElementById('pendidikanChart');
    if (elPend) {
        if (elPend._apexChartInstance) elPend._apexChartInstance.destroy();
        @php
        $pend_labels = [];
        $pend_data = [];
        $pend_colors_hex = ['#4f46e5','#10b981','#f59e0b','#0ea5e9','#ec4899','#8b5cf6','#06b6d4'];
        foreach ($distribusi_pendidikan as $row) {
            $pend_labels[] = $row->pendidikan_terakhir;
            $pend_data[] = $row->jumlah;
        }
        @endphp
        var chartPend = new ApexCharts(elPend, {
            chart: { type: 'donut', height: 240, fontFamily: 'Roboto, sans-serif' },
            series: @json($pend_data),
            labels: @json($pend_labels),
            colors: @json(array_slice($pend_colors_hex, 0, count($distribusi_pendidikan))),
            legend: { position: 'right', fontSize: '11px', usePointStyle: true, padding: 5 },
            plotOptions: { pie: { donut: { size: '55%' } } },
            dataLabels: { enabled: false }
        });
        chartPend.render();
        elPend._apexChartInstance = chartPend;
    }
})();

// Listen to custom event for AJAX page navigation
$(document).off('ajaxPageLoaded.perusahaanDashboard').on('ajaxPageLoaded.perusahaanDashboard', function() {
    // Re-initialize charts on AJAX navigation
    var elGender = document.getElementById('genderChart');
    if (elGender) {
        if (elGender._apexChartInstance) elGender._apexChartInstance.destroy();
        var chartGender = new ApexCharts(elGender, {
            chart: { type: 'donut', height: 160, fontFamily: 'Roboto, sans-serif' },
            series: [{{ $total_laki }}, {{ $total_perempuan }}],
            labels: ['Laki-laki', 'Perempuan'],
            colors: ['#4f46e5', '#ec4899'],
            legend: { position: 'bottom', fontSize: '11px', usePointStyle: true, padding: 5 },
            plotOptions: { pie: { donut: { size: '70%' } } },
            dataLabels: { enabled: false }
        });
        chartGender.render();
        elGender._apexChartInstance = chartGender;
    }

    var elUnit = document.getElementById('unitChart');
    if (elUnit) {
        if (elUnit._apexChartInstance) elUnit._apexChartInstance.destroy();
        var chartUnit = new ApexCharts(elUnit, {
            chart: { type: 'donut', height: 160, fontFamily: 'Roboto, sans-serif' },
            series: @json($unit_data_vals),
            labels: @json($unit_labels),
            colors: @json(array_slice($unit_colors_hex, 0, count($distribusi_unit))),
            legend: { position: 'bottom', fontSize: '11px', usePointStyle: true, padding: 5 },
            plotOptions: { pie: { donut: { size: '70%' } } },
            dataLabels: { enabled: false }
        });
        chartUnit.render();
        elUnit._apexChartInstance = chartUnit;
    }

    var elPend = document.getElementById('pendidikanChart');
    if (elPend) {
        if (elPend._apexChartInstance) elPend._apexChartInstance.destroy();
        var chartPend = new ApexCharts(elPend, {
            chart: { type: 'donut', height: 240, fontFamily: 'Roboto, sans-serif' },
            series: @json($pend_data),
            labels: @json($pend_labels),
            colors: @json(array_slice($pend_colors_hex, 0, count($distribusi_pendidikan))),
            legend: { position: 'right', fontSize: '11px', usePointStyle: true, padding: 5 },
            plotOptions: { pie: { donut: { size: '55%' } } },
            dataLabels: { enabled: false }
        });
        chartPend.render();
        elPend._apexChartInstance = chartPend;
    }
});
</script>
