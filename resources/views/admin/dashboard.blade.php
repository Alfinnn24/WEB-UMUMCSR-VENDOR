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
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
.fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
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
        <div class="card h-100 modern-card stat-card">
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
    </div>

    {{-- Temuan Open --}}
    <div class="col fade-in-up delay-2">
        <div class="card h-100 modern-card stat-card {{ $temuan_open > 0 ? 'border border-danger border-2' : '' }}">
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
                <div class="mt-4">
                    <div class="progress" style="height:12px;border-radius:99px;background:#e9ecef;overflow:hidden">
                        @php
                            $rings = [$karyawan_ring1,$karyawan_ring2,$karyawan_ring3,$karyawan_ring4,$karyawan_tanpa_ring];
                            $colors = ['#1e293b','#475569','#64748b','#94a3b8','#cbd5e1'];
                        @endphp
                        @foreach($rings as $i => $count)
                        <div class="progress-bar" style="width:{{ round($count/$total_aktif*100) }}%;background:{{ $colors[$i] }}"
                             title="Ring {{ $i<4 ? $i+1 : 'Belum' }}: {{ round($count/$total_aktif*100) }}%"></div>
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
        plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
        xaxis: { categories: usiaLabels },
        colors: colors,
        fill: { type: 'gradient', gradient: { type: 'vertical', shadeIntensity: 0.3 } },
        dataLabels: { enabled: true, style: { fontSize: '12px' } },
        grid: { borderColor: '#f0f0f0' },
        tooltip: { y: { formatter: val => val + ' orang' } }
    });
    chart.render();
    el._apexChartInstance = chart; // simpan referensi untuk destroy saat reload
})();
</script>
