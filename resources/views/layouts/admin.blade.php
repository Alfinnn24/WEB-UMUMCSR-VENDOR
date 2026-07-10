<!doctype html>
<html lang="id" class="color-sidebar sidebarcolor7 color-header headercolor2">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- jQuery dimuat PERTAMA agar semua script di bawah bisa pakai $ -->
    <script src="/assets/js/jquery.min.js"></script>
    {{-- Favicon --}}
    <link rel="icon" href="/assets/images/logo.png?v=1.0" type="image/png" />
    {{-- Plugins CSS --}}
    <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    {{-- Loader (pace.js dimatikan untuk XHR agar tidak flicker saat AJAX nav) --}}
    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <script>window.paceOptions = { ajax: false, restartOnRequestAfter: false };</script>
    <script src="/assets/js/pace.min.js"></script>
    {{-- Bootstrap & App CSS --}}
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/icons.css" rel="stylesheet">
    {{-- Theme --}}
    <link rel="stylesheet" href="/assets/css/dark-theme.css" />
    <link rel="stylesheet" href="/assets/css/semi-dark.css" />
    <link rel="stylesheet" href="/assets/css/header-colors.css" />

    <title>{{ $pageTitle ?? 'Admin' }} - Karyawan &amp; Audit Monitoring</title>

    <style>
        /* ── Slim AJAX progress bar (atas layar) ── */
        #ajax-loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #4f46e5, #0ea5e9, #10b981);
            background-size: 200% 100%;
            animation: shimmer 1.2s ease-in-out infinite;
            z-index: 9999;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* ── Fade transition untuk konten ── */
        #page-content {
            position: relative;
            transition: opacity 0.18s ease;
        }

        #page-content.ajax-fading {
            opacity: 0.3;
            pointer-events: none;
        }

        /* ── Active menu highlight ── */
        .metismenu li.mm-active>a,
        .metismenu li>a.nav-link-active {
            background: rgba(255, 255, 255, 0.15) !important;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <!-- Top-bar loading indicator (3px bar di atas layar) -->
    <div id="ajax-loader"></div>

    <!--wrapper-->
    <div class="wrapper">

        <!--sidebar wrapper-->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="/assets/images/logo.png" style="height:48px;width:auto;" alt="logo">
                </div>
                <div>
                    <h4 class="logo-text"></h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
            </div>

            <!--navigation-->
            <ul class="metismenu" id="menu">

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax" data-url="{{ route('admin.dashboard') }}"
                        data-title="Dashboard">
                        <div class="parent-icon"><i class='bx bxs-dashboard'></i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-ajax" data-url="{{ route('admin.dashboard') }}?page=profile"
                        data-title="Profile">
                        <div class="parent-icon"><i class='bx bxs-user-circle'></i></div>
                        <div class="menu-title">Profile</div>
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-ajax" data-url="{{ route('admin.dashboard') }}?page=monitoring_sertifikasi"
                        data-title="Statistik Sertifikasi">
                        <div class="parent-icon"><i class='bx bxs-badge-check'></i></div>
                        <div class="menu-title">Statistik Sertifikasi</div>
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-ajax"
                        data-url="{{ route('admin.dashboard') }}?page=monitoring_laporan_tenaker"
                        data-title="Statistik Tenaga Kerja">
                        <div class="parent-icon"><i class='bx bxs-file-doc'></i></div>
                        <div class="menu-title">Statistik Tenaga Kerja</div>
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-ajax" data-url="{{ route('admin.dashboard') }}?page=monitoring_kontrak"
                        data-title="Statistik Kontrak">
                        <div class="parent-icon"><i class='bx bxs-file-blank'></i></div>
                        <div class="menu-title">Statistik Kontrak Kerja</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.temuan-audit.index') }}" class="nav-ajax"
                        data-url="{{ route('admin.temuan-audit.index') }}" data-title="Temuan Audit">
                        <div class="parent-icon"><i class='bx bx-search-alt-2'></i></div>
                        <div class="menu-title">Temuan Audit</div>
                    </a>
                </li>

                <li class="menu-label">Master Data</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bxs-folder-open'></i></div>
                        <div class="menu-title">Master Data</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="nav-ajax"
                                data-url="{{ route('admin.users.index') }}" data-title="Data Users">
                                <i class="bx bxs-id-card"></i>Data Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ring-wilayah.index') }}" class="nav-ajax"
                                data-url="{{ route('admin.ring-wilayah.index') }}" data-title="Data Ring Wilayah">
                                <i class="bx bxs-map-alt"></i>Data Ring Wilayah
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.informasi.index') }}" class="nav-ajax" data-url="{{ route('admin.informasi.index') }}"
                                data-title="Informasi">
                                <i class="bx bxs-info-circle"></i>Informasi
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-label">Laporan</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bxs-report'></i></div>
                        <div class="menu-title">Laporan</div>
                    </a>
                    <ul>
                        <li>
                            <a href="#" class="nav-ajax" data-url="{{ route('admin.dashboard') }}?page=laporan_karyawan"
                                data-title="Laporan Karyawan">
                                <i class="bx bxs-user-detail"></i>Lap. Data Karyawan
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-ajax"
                                data-url="{{ route('admin.dashboard') }}?page=laporan_sertifikasi"
                                data-title="Laporan Sertifikasi">
                                <i class="bx bxs-badge-check"></i>Lap. Sertifikasi
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-ajax"
                                data-url="{{ route('admin.dashboard') }}?page=laporan_tenaga_kerja_admin"
                                data-title="Laporan Tenaga Kerja">
                                <i class="bx bxs-group"></i>Lap. Tenaga Kerja
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-ajax" data-url="{{ route('admin.dashboard') }}?page=laporan_kontrak"
                                data-title="Laporan Kontrak">
                                <i class="bx bxs-file-blank"></i>Lap. Kontrak
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-ajax"
                                data-url="{{ route('admin.dashboard') }}?page=laporan_ring_wilayah"
                                data-title="Laporan Ring Wilayah">
                                <i class="bx bxs-map-alt"></i>Lap. Ring Wilayah
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper-->

        <!--start header-->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>

                    <div class="search-bar flex-grow-1">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" placeholder="Type to search...">
                            <span class="position-absolute top-50 search-show translate-middle-y"><i
                                    class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i
                                    class='bx bx-x'></i></span>
                        </div>
                    </div>

                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item mobile-search-icon">
                                <a class="nav-link" href="#"><i class='bx bx-search'></i></a>
                            </li>
                        </ul>
                    </div>

                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            role="button" data-bs-toggle="dropdown">
                            <img src="/assets/images/team.png" class="user-img" alt="avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{ ucwords(session('nama', 'Admin')) }}</p>
                                <p class="designattion mb-0">{{ ucwords(session('role', '')) }}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item nav-ajax" href="{{ route('admin.dashboard') }}?page=profile"
                                    data-url="{{ route('admin.dashboard') }}?page=profile" data-title="Profile">
                                    <i class="bx bx-user"></i><span>Profile</span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class='bx bx-log-out-circle'></i><span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- Diperlukan app.js (PerfectScrollbar), jangan dihapus -->
                <div class="header-notifications-list" style="display:none"></div>
                <div class="header-message-list" style="display:none"></div>
            </div>
        </header>
        <!--end header-->

        <!--start page wrapper-->
        <div class="page-wrapper">
            <div class="page-content page-content-wrapper" id="page-content">

                {{-- Konten halaman awal (di-render server-side saat pertama kali akses) --}}
                @includeIf($page ?? 'admin.dashboard', $__data ?? [])

            </div>
        </div>
        <!--end page wrapper-->

        <div class="overlay toggle-icon"></div>
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

        <footer class="page-footer">
            <p class="mb-0">Copyright &copy; {{ date('Y') }}.
                <a href="https://tuyulcode.netlify.app/" target="_blank">tuyulcode</a>
            </p>
        </footer>
    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <!-- Plugins -->
    <script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons and Export Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <!-- ApexCharts dimuat SEKALI di layout, bukan di tiap partial -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/assets/js/app_baru.js?v=3"></script>

    {{-- ═══════════════════════════════════════════════════════════
    AJAX NAVIGATION ENGINE
    Cara kerja:
    1. Klik .nav-ajax → ambil data-url → $.ajax GET
    2. Tampilkan spinner, sembunyikan konten lama
    3. Setelah response → inject ke #page-content
    4. history.pushState agar URL berubah tanpa reload
    5. Browser back/forward → popstate → load ulang via AJAX
    6. Request langsung (bukan AJAX) → Laravel render full page
    ═══════════════════════════════════════════════════════════ --}}
    <script>
        $(function () {

            // ── Fungsi load halaman via AJAX ────────────────────────────────
            function ajaxLoadPage(url, pageTitle, pushHistory) {
                var $loader = $('#ajax-loader');
                var $content = $('#page-content');

                // 1. Tampilkan progress bar tipis
                $loader.show();

                // 2. Fade-out konten lama (cepat, 180ms)
                $content.addClass('ajax-fading');

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (html) {
                        // 3. Setelah fade-out selesai → ganti isi → fade-in
                        setTimeout(function () {
                            // Ganti konten
                            $content.html(html);

                            // Update title browser
                            if (pageTitle) {
                                document.title = pageTitle + ' - Karyawan & Audit Monitoring';
                            }

                            // Push state ke browser history
                            if (pushHistory !== false) {
                                history.pushState({ url: url, title: pageTitle }, pageTitle, url);
                            }

                            // 4. Fade-in konten baru
                            $content.removeClass('ajax-fading');

                            // 5. Scroll halus ke atas
                            $('.page-wrapper').animate({ scrollTop: 0 }, 150);

                            // Re-init DataTable & Charts
                            reinitDataTables();
                            reinitCharts();
                            highlightActiveMenu(url);

                            $loader.hide();
                        }, 180); // tunggu fade-out CSS selesai
                    },
                    error: function (xhr) {
                        $content.html(
                            '<div class="alert alert-danger m-4">' +
                            '<i class="bx bx-error-circle me-2"></i>' +
                            'Gagal memuat halaman (' + xhr.status + '). Coba refresh atau hubungi Admin.' +
                            '</div>'
                        );
                        $content.removeClass('ajax-fading');
                        $loader.hide();
                    }
                });
            }

            // ── Event: klik link di sidebar / header ────────────────────────
            $(document).on('click', 'a.nav-ajax', function (e) {
                e.preventDefault();
                const url = $(this).data('url') || $(this).attr('href');
                const pageTitle = $(this).data('title') || '';

                if (!url || url === '#' || url === 'javascript:;') return;

                ajaxLoadPage(url, pageTitle, true);
            });

            // ── Event: browser back / forward ────────────────────────────────
            window.addEventListener('popstate', function (e) {
                if (e.state && e.state.url) {
                    ajaxLoadPage(e.state.url, e.state.title, false);
                }
            });

            // Simpan state awal (halaman pertama kali dibuka)
            history.replaceState(
                { url: window.location.href, title: document.title },
                document.title,
                window.location.href
            );

            // ── Highlight menu aktif berdasarkan URL ─────────────────────────
            function highlightActiveMenu(url) {
                $('.metismenu a').removeClass('nav-link-active');
                $('.metismenu a.nav-ajax').each(function () {
                    const linkUrl = $(this).data('url') || $(this).attr('href');
                    if (linkUrl && url.includes(linkUrl)) {
                        $(this).addClass('nav-link-active');
                    }
                });
            }
            // Highlight untuk halaman awal
            highlightActiveMenu(window.location.href);

            // ── Re-init DataTables setelah AJAX inject ───────────────────────
            function reinitDataTables() {
                if ($.fn.DataTable) {
                    // Hancurkan instance lama dulu
                    $('#example').each(function () {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().destroy();
                        }
                        $(this).DataTable();
                    });
                    $('#example2').each(function () {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().destroy();
                        }
                        $(this).DataTable({
                            lengthChange: false,
                            buttons: ['copy', 'excel', 'pdf', 'print']
                        });
                    });
                    $('.table-report').each(function () {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().destroy();
                        }
                        var table = $(this).DataTable({
                            lengthChange: true,
                            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                            order: [],
                            language: {
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ data",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                paginate: { first: "Pertama", last: "Terakhir", next: "Lanjut", previous: "Kembali" }
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                { extend: 'copy', className: 'btn btn-sm btn-outline-secondary' },
                                { extend: 'excel', className: 'btn btn-sm btn-outline-success', title: $(this).data('export-title') || 'Laporan' },
                                { extend: 'pdf', className: 'btn btn-sm btn-outline-danger', title: $(this).data('export-title') || 'Laporan' },
                                { extend: 'print', className: 'btn btn-sm btn-outline-primary' }
                            ]
                        });
                    });
                }
            }

            // ── Trigger custom re-init event untuk charts ────────────────────
            function reinitCharts() {
                // Dispatch event kustom agar halaman yang diload bisa listen
                document.dispatchEvent(new CustomEvent('ajaxPageLoaded'));
            }

            // ── Initialize ApexCharts for Modul 5 & Dashboard ──────────────
            function initMyPageCharts() {
                // Sertifikasi Charts
                var elSertStatus = document.getElementById('chart-sertifikasi-status');
                if (elSertStatus) {
                    if (elSertStatus._apexChartInstance) elSertStatus._apexChartInstance.destroy();
                    var series = JSON.parse(elSertStatus.getAttribute('data-series') || '[]');
                    var labels = JSON.parse(elSertStatus.getAttribute('data-labels') || '[]');
                    var chart = new ApexCharts(elSertStatus, {
                        chart: { type: 'donut', height: 260 },
                        series: series,
                        labels: labels,
                        colors: ['#10b981', '#f59e0b', '#ef4444'],
                        legend: { position: 'bottom' }
                    });
                    chart.render();
                    elSertStatus._apexChartInstance = chart;
                }
                
                var elSertTop = document.getElementById('chart-sertifikasi-top');
                if (elSertTop) {
                    if (elSertTop._apexChartInstance) elSertTop._apexChartInstance.destroy();
                    var series = JSON.parse(elSertTop.getAttribute('data-series') || '[]');
                    var categories = JSON.parse(elSertTop.getAttribute('data-categories') || '[]');
                    var chart = new ApexCharts(elSertTop, {
                        chart: { type: 'bar', height: 280, toolbar: { show: false } },
                        series: [{ name: 'Expired', data: series }],
                        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                        colors: ['#ef4444'],
                        xaxis: { categories: categories },
                        dataLabels: { enabled: true }
                    });
                    chart.render();
                    elSertTop._apexChartInstance = chart;
                }

                // Tenaga Kerja Charts
                var elTenakerStatus = document.getElementById('chart-tenaker-status');
                if (elTenakerStatus) {
                    if (elTenakerStatus._apexChartInstance) elTenakerStatus._apexChartInstance.destroy();
                    var series = JSON.parse(elTenakerStatus.getAttribute('data-series') || '[]');
                    var labels = JSON.parse(elTenakerStatus.getAttribute('data-labels') || '[]');
                    var chart = new ApexCharts(elTenakerStatus, {
                        chart: { type: 'donut', height: 260 },
                        series: series,
                        labels: labels,
                        colors: ['#10b981', '#ef4444'],
                        legend: { position: 'bottom' }
                    });
                    chart.render();
                    elTenakerStatus._apexChartInstance = chart;
                }
                
                var elTenakerTop = document.getElementById('chart-tenaker-top');
                if (elTenakerTop) {
                    if (elTenakerTop._apexChartInstance) elTenakerTop._apexChartInstance.destroy();
                    var series = JSON.parse(elTenakerTop.getAttribute('data-series') || '[]');
                    var categories = JSON.parse(elTenakerTop.getAttribute('data-categories') || '[]');
                    var chart = new ApexCharts(elTenakerTop, {
                        chart: { type: 'bar', height: 280, toolbar: { show: false } },
                        series: [{ name: 'Laporan', data: series }],
                        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                        colors: ['#0ea5e9'],
                        xaxis: { categories: categories },
                        dataLabels: { enabled: true }
                    });
                    chart.render();
                    elTenakerTop._apexChartInstance = chart;
                }

                // Kontrak Charts
                var elKontrakStatus = document.getElementById('chart-kontrak-status');
                if (elKontrakStatus) {
                    if (elKontrakStatus._apexChartInstance) elKontrakStatus._apexChartInstance.destroy();
                    var series = JSON.parse(elKontrakStatus.getAttribute('data-series') || '[]');
                    var labels = JSON.parse(elKontrakStatus.getAttribute('data-labels') || '[]');
                    var chart = new ApexCharts(elKontrakStatus, {
                        chart: { type: 'donut', height: 260 },
                        series: series,
                        labels: labels,
                        colors: ['#10b981', '#f59e0b', '#94a3b8', '#0ea5e9'],
                        legend: { position: 'bottom' }
                    });
                    chart.render();
                    elKontrakStatus._apexChartInstance = chart;
                }
                
                var elKontrakTop = document.getElementById('chart-kontrak-top');
                if (elKontrakTop) {
                    if (elKontrakTop._apexChartInstance) elKontrakTop._apexChartInstance.destroy();
                    var series = JSON.parse(elKontrakTop.getAttribute('data-series') || '[]');
                    var categories = JSON.parse(elKontrakTop.getAttribute('data-categories') || '[]');
                    var chart = new ApexCharts(elKontrakTop, {
                        chart: { type: 'bar', height: 280, toolbar: { show: false } },
                        series: [{ name: 'Kontrak', data: series }],
                        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                        colors: ['#6366f1'],
                        xaxis: { categories: categories },
                        dataLabels: { enabled: true }
                    });
                    chart.render();
                    elKontrakTop._apexChartInstance = chart;
                }
            }

            $(document).on('ajaxPageLoaded', initMyPageCharts);
            initMyPageCharts();
            reinitDataTables();

            // Generic filter form AJAX submission
            $(document).on('submit', '#filterSertifikasiForm, #filterLaporanTenakerForm, #filterKontrakForm, #filterLapKaryawanForm, #filterLapSertifikasiForm, #filterLapTenakerForm, #filterLapKontrakForm', function (e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                ajaxLoadPage(url, document.title, true);
            });
            
            // Reset button handlers
            $(document).on('click', '.btn-reset-sertifikasi', function () {
                ajaxLoadPage('/admin/dashboard?page=monitoring_sertifikasi', 'Statistik Sertifikasi', true);
            });
            $(document).on('click', '.btn-reset-laporan-tenaker', function () {
                ajaxLoadPage('/admin/dashboard?page=monitoring_laporan_tenaker', 'Statistik Tenaga Kerja', true);
            });
            $(document).on('click', '.btn-reset-kontrak', function () {
                ajaxLoadPage('/admin/dashboard?page=monitoring_kontrak', 'Statistik Kontrak Kerja', true);
            });
            $(document).on('click', '.btn-reset-lap-karyawan', function () {
                ajaxLoadPage('/admin/dashboard?page=laporan_karyawan', 'Laporan Karyawan', true);
            });
            $(document).on('click', '.btn-reset-lap-sertifikasi', function () {
                ajaxLoadPage('/admin/dashboard?page=laporan_sertifikasi', 'Laporan Sertifikasi', true);
            });
            $(document).on('click', '.btn-reset-lap-tenaker', function () {
                ajaxLoadPage('/admin/dashboard?page=laporan_tenaga_kerja_admin', 'Laporan Tenaga Kerja', true);
            });
            $(document).on('click', '.btn-reset-lap-kontrak', function () {
                ajaxLoadPage('/admin/dashboard?page=laporan_kontrak', 'Laporan Kontrak Kerja', true);
            });

            // ── Alert auto-fade ──────────────────────────────────────────────
            $(document).on('ajaxPageLoaded', function () {
                window.setTimeout(function () {
                    $('.alert').fadeTo(1000, 0).slideUp(1000, function () {
                        $(this).remove();
                    });
                }, 3000);
            });

            // Alert di halaman pertama (server-rendered)
            window.setTimeout(function () {
                $('.alert').fadeTo(1000, 0).slideUp(1000, function () {
                    $(this).remove();
                });
            }, 3000);

        });
    </script>

    <!-- ═══ USERS CRUD (delegated — tetap jalan walau konten dimuat via AJAX) ═══ -->
    <script>
        $(function () {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Setup AJAX default: selalu kirim CSRF token & header XHR
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            function reloadUsers() {
                $.ajax({
                    url: '/admin/users',
                    type: 'GET',
                    success: function (html) {
                        $('#page-content').html(html);
                        initUsersTable();
                    }
                });
            }

            function initUsersTable() {
                if ($.fn.DataTable && $('#usersTable').length) {
                    if ($.fn.DataTable.isDataTable('#usersTable')) {
                        $('#usersTable').DataTable().destroy();
                    }
                    $('#usersTable').DataTable({
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: { first: "Pertama", last: "Terakhir", next: "Lanjut", previous: "Kembali" }
                        }
                    });
                }
            }
            initUsersTable();
            $(document).on('ajaxPageLoaded', initUsersTable);

            // Toggle password
            $(document).on('click', '.toggle-pass', function () {
                var t = $($(this).data('target')), icon = $(this).find('i');
                if (t.attr('type') === 'password') { t.attr('type', 'text'); icon.removeClass('bx-hide').addClass('bx-show'); }
                else { t.attr('type', 'password'); icon.removeClass('bx-show').addClass('bx-hide'); }
            });

            // CREATE
            $(document).on('submit', '#addUserForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#addErrorAlert');
                alertBox.addClass('d-none').html('');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function () {
                        $('#addUserModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        form[0].reset(); reloadUsers();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // EDIT (buka modal)
            $(document).on('click', '.btn-edit', function () {
                var id = $(this).data('id');
                $('#editUserForm').attr('action', '/admin/users/' + id);
                $('#editErrorAlert').addClass('d-none').html('');
                $.get('/admin/users/' + id, function (u) {
                    $('#edit_nid').val(u.nid);
                    $('#edit_nama').val(u.nama);
                    $('#edit_role').val(u.role);
                    $('#edit_status').val(u.status || 'aktif');
                    $('#editUserModal').modal('show');
                });
            });

            // EDIT (submit)
            $(document).on('submit', '#editUserForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#editErrorAlert');
                alertBox.addClass('d-none').html('');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function () {
                        $('#editUserModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        reloadUsers();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // DELETE
            $(document).on('click', '.btn-delete', function () {
                var btn = $(this);
                var id = btn.data('id');
                var name = btn.data('name');
                if (!confirm('Yakin ingin menghapus user "' + name + '"?')) return;
                
                $.ajax({
                    url: '/admin/users/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            alert(res.message || 'User berhasil dihapus.');
                            if ($.fn.DataTable && $('#usersTable').length && $.fn.DataTable.isDataTable('#usersTable')) {
                                var table = $('#usersTable').DataTable();
                                table.row(btn.parents('tr')).remove().draw(false);
                            } else {
                                btn.parents('tr').remove();
                            }
                        } else {
                            alert(res.message || 'Gagal menghapus user.');
                        }
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Gagal menghapus user.';
                        alert('Gagal (' + xhr.status + '): ' + msg);
                    }
                });
            });

            // DETAIL
            $(document).on('click', '.btn-detail', function () {
                var id = $(this).data('id');
                $.get('/admin/users/' + id, function (u) {
                    $('#detail_nama_title').text(u.nama);
                    $('#detail_role_badge').text((u.role || '').toUpperCase());
                    $('#detail_nid').text(u.nid);
                    $('#detail_nama').text(u.nama);
                    $('#detail_role').text(u.role);
                    $('#detail_status').html((u.status || 'aktif') === 'aktif'
                        ? '<span class="badge" style="background:#198754;color:#fff;">Aktif</span>'
                        : '<span class="badge" style="background:#6c757d;color:#fff;">Nonaktif</span>');
                    $('#detail_alamat').html(u.alamat ? String(u.alamat).replace(/\n/g, '<br>') : '<i class="text-muted">Belum ada</i>');
                    $('#detail_nama_admin').html(u.nama_admin || '<i class="text-muted">Belum ada</i>');
                    $('#detail_nomor_admin').html(u.nomor_admin || '<i class="text-muted">Belum ada</i>');
                    $('#detailUserModal').modal('show');
                });
            });
            // === INFORMASI CRUD (delegated) ===
            function reloadInformasi() {
                $.ajax({
                    url: '/admin/informasi',
                    type: 'GET',
                    success: function (html) {
                        $('#page-content').html(html);
                        initInformasiTable();
                    }
                });
            }

            function initInformasiTable() {
                if ($.fn.DataTable && $('#informasiTable').length) {
                    if ($.fn.DataTable.isDataTable('#informasiTable')) {
                        $('#informasiTable').DataTable().destroy();
                    }
                    $('#informasiTable').DataTable({
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: { first: "Pertama", last: "Terakhir", next: "Lanjut", previous: "Kembali" }
                        }
                    });
                }
            }
            initInformasiTable();
            $(document).on('ajaxPageLoaded', initInformasiTable);

            // CREATE (Submit Add Form)
            $(document).on('submit', '#addInformasiForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#addInfoErrorAlert');
                alertBox.addClass('d-none').html('');
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                        $('#addInformasiModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        form[0].reset(); reloadInformasi();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // EDIT (buka modal & load data)
            $(document).on('click', '.btn-edit-info', function () {
                var id = $(this).data('id');
                $('#editInformasiForm').attr('action', '/admin/informasi/' + id);
                $('#editInfoErrorAlert').addClass('d-none').html('');
                $.get('/admin/informasi/' + id, function (info) {
                    $('#edit_info_judul').val(info.judul);
                    var cleanName = info.file;
                    var pos = cleanName.indexOf('_');
                    if (pos !== -1) {
                        cleanName = cleanName.substring(pos + 1);
                    }
                    $('#edit_info_current_file').text(cleanName).attr('href', '/uploads/informasi/' + info.file);
                    $('#editInformasiModal').modal('show');
                });
            });

            // EDIT (submit)
            $(document).on('submit', '#editInformasiForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#editInfoErrorAlert');
                alertBox.addClass('d-none').html('');
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                        $('#editInformasiModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        reloadInformasi();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // DELETE
            $(document).on('click', '.btn-delete-info', function () {
                var id = $(this).data('id'), name = $(this).data('name');
                if (!confirm('Yakin ingin menghapus informasi "' + name + '"?')) return;
                $.ajax({
                    url: '/admin/informasi/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE', _token: csrfToken },
                    success: function () { reloadInformasi(); },
                    error: function (xhr) { alert((xhr.responseJSON && xhr.responseJSON.message) || 'Gagal menghapus informasi.'); }
                });
            });

            // DETAIL
            $(document).on('click', '.btn-detail-info', function () {
                var id = $(this).data('id');
                $.get('/admin/informasi/' + id, function (info) {
                    $('#detail_info_judul').text(info.judul);
                    var cleanName = info.file;
                    var pos = cleanName.indexOf('_');
                    if (pos !== -1) {
                        cleanName = cleanName.substring(pos + 1);
                    }
                    $('#detail_info_file_name').text(cleanName);
                    $('#detail_info_file_link').attr('href', '/uploads/informasi/' + info.file);
                    $('#detail_info_download_btn').attr('href', '/uploads/informasi/' + info.file);
                    
                    var ext = info.file.split('.').pop().toLowerCase();
                    var icon = 'bx bxs-file';
                    if (ext === 'pdf') icon = 'bx bxs-file-pdf text-danger';
                    else if (ext === 'doc' || ext === 'docx') icon = 'bx bxs-file-doc text-primary';
                    else if (ext === 'xls' || ext === 'xlsx') icon = 'bx bxs-file-export text-success';
                    else if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') icon = 'bx bxs-file-image text-info';
                    else if (ext === 'ppt' || ext === 'pptx') icon = 'bx bxs-file-blank text-warning';
                    else if (ext === 'zip' || ext === 'rar') icon = 'bx bxs-file-archive text-dark';
                    $('#detail_info_file_icon').attr('class', icon);

                    var date = new Date(info.created_at);
                    var formattedDate = ('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear() + ' ' + ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2);
                    $('#detail_info_tanggal').text(formattedDate);

                    $('#detailInformasiModal').modal('show');
                });
            });

            // === RING WILAYAH CRUD (delegated) ===
            function reloadRingWilayah() {
                $.ajax({
                    url: '/admin/ring-wilayah',
                    type: 'GET',
                    success: function (html) {
                        $('#page-content').html(html);
                    }
                });
            }

            // Filter tab Ring
            $(document).on('click', '.btn-filter-ring', function (e) {
                e.preventDefault();
                $('#ringTab .nav-link').removeClass('active');
                $(this).addClass('active');
                var ring = $(this).data('ring');
                $('#ringTable tbody tr').each(function () {
                    if (ring === 'semua') {
                        $(this).show();
                    } else {
                        $(this).toggle($(this).data('ring') === ring);
                    }
                });
            });

            // Dependent dropdown: Provinsi → Kabupaten
            $(document).on('change', '#provinsi_sel', function () {
                var id = $(this).val();
                var text = $(this).find('option:selected').text().trim();
                $('#provinsi_nama').val(text);
                $('#kabupaten_sel').html('<option disabled selected>⏳ Memuat...</option>').prop('disabled', true);
                $('#kecamatan_sel').html('<option disabled selected>-- Pilih Kabupaten dahulu --</option>').prop('disabled', true);
                $('#desa_sel').html('<option disabled selected>-- Pilih Kecamatan dahulu --</option>').prop('disabled', true);
                $('#kabupaten_nama, #kecamatan_nama, #desa_nama').val('');
                if (!id) return;
                $.get('/admin/ring-wilayah/kabupaten', { provinsi_id: id }, function (data) {
                    var opts = '<option value="" disabled selected>-- Pilih Kabupaten / Kota --</option>';
                    $.each(data, function (i, k) { opts += '<option value="' + k.id + '">' + k.name + '</option>'; });
                    $('#kabupaten_sel').html(opts).prop('disabled', false);
                });
            });

            // Dependent dropdown: Kabupaten → Kecamatan
            $(document).on('change', '#kabupaten_sel', function () {
                var id = $(this).val();
                var text = $(this).find('option:selected').text().trim();
                $('#kabupaten_nama').val(text);
                $('#kecamatan_sel').html('<option disabled selected>⏳ Memuat...</option>').prop('disabled', true);
                $('#desa_sel').html('<option disabled selected>-- Pilih Kecamatan dahulu --</option>').prop('disabled', true);
                $('#kecamatan_nama, #desa_nama').val('');
                if (!id) return;
                $.get('/admin/ring-wilayah/kecamatan', { kabupaten_id: id }, function (data) {
                    var opts = '<option value="" disabled selected>-- Pilih Kecamatan --</option>';
                    $.each(data, function (i, k) { opts += '<option value="' + k.id + '">' + k.name + '</option>'; });
                    $('#kecamatan_sel').html(opts).prop('disabled', false);
                });
            });

            // Dependent dropdown: Kecamatan → Desa
            $(document).on('change', '#kecamatan_sel', function () {
                var id = $(this).val();
                var text = $(this).find('option:selected').text().trim();
                $('#kecamatan_nama').val(text);
                $('#desa_sel').html('<option disabled selected>⏳ Memuat...</option>').prop('disabled', true);
                $('#desa_nama').val('');
                if (!id) return;
                $.get('/admin/ring-wilayah/desa', { kecamatan_id: id }, function (data) {
                    var opts = '<option value="" disabled selected>-- Pilih Desa / Kelurahan --</option>';
                    $.each(data, function (i, d) { opts += '<option value="' + d.id + '">' + d.name + '</option>'; });
                    $('#desa_sel').html(opts).prop('disabled', false);
                });
            });

            // Dependent dropdown: Desa → hidden input
            $(document).on('change', '#desa_sel', function () {
                $('#desa_nama').val($(this).find('option:selected').text().trim());
            });

            // CREATE Ring
            $(document).on('submit', '#addRingForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#addRingErrorAlert');
                alertBox.addClass('d-none').html('');
                // Validasi JS sederhana
                if (!$('input[name="ring"]:checked').length) {
                    alertBox.removeClass('d-none').html('<ul><li>Pilih Ring terlebih dahulu.</li></ul>'); return;
                }
                if (!$('#provinsi_nama').val() || !$('#kabupaten_nama').val() || !$('#kecamatan_nama').val() || !$('#desa_nama').val()) {
                    alertBox.removeClass('d-none').html('<ul><li>Lengkapi semua pilihan wilayah.</li></ul>'); return;
                }
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function (res) {
                        if (res.status === 'success') { reloadRingWilayah(); }
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // DELETE Ring
            $(document).on('click', '.btn-delete-ring', function () {
                var id = $(this).data('id'), name = $(this).data('name'), ring = $(this).data('ring');
                if (!confirm('Hapus wilayah ' + name + ' dari ' + ring + '?')) return;
                $.ajax({
                    url: '/admin/ring-wilayah/' + id, type: 'POST',
                    data: { _method: 'DELETE', _token: csrfToken },
                    success: function () { reloadRingWilayah(); },
                    error: function (xhr) { alert((xhr.responseJSON && xhr.responseJSON.message) || 'Gagal menghapus.'); }
                });
            });


            // === TEMUAN AUDIT CRUD (delegated) ===
            function reloadTemuanAudit(filters) {
                var url = '/admin/temuan-audit';
                if (filters) { url += '?' + filters; }
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (html) {
                        $('#page-content').html(html);
                        initTemuanTable();
                    }
                });
            }

            function initTemuanTable() {
                if ($.fn.DataTable && $('#temuanTable').length) {
                    if ($.fn.DataTable.isDataTable('#temuanTable')) {
                        $('#temuanTable').DataTable().destroy();
                    }
                    $('#temuanTable').DataTable({
                        order: [[1, 'desc']], // Urutkan tanggal audit terbaru
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: { first: "Pertama", last: "Terakhir", next: "Lanjut", previous: "Kembali" }
                        }
                    });
                }
            }
            initTemuanTable();
            $(document).on('ajaxPageLoaded', initTemuanTable);

            // Filter Submit
            $(document).on('submit', '#filterTemuanForm', function (e) {
                e.preventDefault();
                reloadTemuanAudit($(this).serialize());
            });

            // Reset Filter
            $(document).on('click', '.btn-reset-filter', function () {
                reloadTemuanAudit('');
            });

            // CREATE (Submit Add Form)
            $(document).on('submit', '#addTemuanForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#addTemuanErrorAlert');
                alertBox.addClass('d-none').html('');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function () {
                        $('#addTemuanModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        form[0].reset(); reloadTemuanAudit();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // EDIT (Buka modal & load data)
            $(document).on('click', '.btn-edit-temuan', function () {
                var id = $(this).data('id');
                $('#editTemuanForm').attr('action', '/admin/temuan-audit/' + id);
                $('#editTemuanErrorAlert').addClass('d-none').html('');
                $.get('/admin/temuan-audit/' + id, function (data) {
                    $('#edit_audit_tanggal').val(data.tanggal_audit);
                    $('#edit_audit_perusahaan').val(data.id_perusahaan);
                    $('#edit_audit_temuan').val(data.temuan);
                    $('#edit_audit_tindak_lanjut').val(data.tindak_lanjut || '');
                    $('#edit_audit_evaluasi').val(data.evaluasi || '');
                    $('#edit_audit_status').val(data.status);
                    $('#editTemuanModal').modal('show');
                });
            });

            // EDIT (Submit Edit Form)
            $(document).on('submit', '#editTemuanForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#editTemuanErrorAlert');
                alertBox.addClass('d-none').html('');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function () {
                        $('#editTemuanModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        reloadTemuanAudit();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // CLOSE (Buka modal & load data)
            $(document).on('click', '.btn-close-temuan', function () {
                var id = $(this).data('id');
                $('#closeTemuanForm').attr('action', '/admin/temuan-audit/' + id + '/close');
                $('#closeTemuanErrorAlert').addClass('d-none').html('');
                $('#closeTemuanForm')[0].reset();
                $.get('/admin/temuan-audit/' + id, function (data) {
                    var date = new Date(data.tanggal_audit);
                    var formattedDate = ('0' + date.getDate()).slice(-2) + ' ' + date.toLocaleString('id-ID', { month: 'short' }) + ' ' + date.getFullYear();
                    $('#close_audit_tanggal_txt').text(formattedDate);
                    $('#close_audit_perusahaan_txt').text(data.nama_perusahaan);
                    $('#close_audit_temuan_txt').text(data.temuan);
                    $('#close_audit_tindak_lanjut_txt').text(data.tindak_lanjut || 'Belum ada');
                    $('#closeTemuanModal').modal('show');
                });
            });

            // CLOSE (Submit Close/Evaluasi Form)
            $(document).on('submit', '#closeTemuanForm', function (e) {
                e.preventDefault();
                var form = $(this), alertBox = $('#closeTemuanErrorAlert');
                alertBox.addClass('d-none').html('');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function () {
                        $('#closeTemuanModal').modal('hide');
                        $('.modal-backdrop').remove(); $('body').removeClass('modal-open');
                        reloadTemuanAudit();
                    },
                    error: function (xhr) { alertBox.removeClass('d-none').html(parseErr(xhr)); }
                });
            });

            // DELETE
            $(document).on('click', '.btn-delete-temuan', function () {
                var id = $(this).data('id'), name = $(this).data('name');
                if (!confirm('Hapus temuan audit untuk ' + name + '?')) return;
                $.ajax({
                    url: '/admin/temuan-audit/' + id, type: 'POST',
                    data: { _method: 'DELETE', _token: csrfToken },
                    success: function () { reloadTemuanAudit(); },
                    error: function (xhr) { alert((xhr.responseJSON && xhr.responseJSON.message) || 'Gagal menghapus.'); }
                });
            });

            // DETAIL
            $(document).on('click', '.btn-detail-temuan', function () {
                var id = $(this).data('id');
                $.get('/admin/temuan-audit/' + id, function (data) {
                    var date = new Date(data.tanggal_audit);
                    var formattedDate = ('0' + date.getDate()).slice(-2) + ' ' + date.toLocaleString('id-ID', { month: 'short' }) + ' ' + date.getFullYear();
                    $('#detail_audit_tanggal').text(formattedDate);
                    $('#detail_audit_perusahaan').text(data.nama_perusahaan);
                    $('#detail_audit_temuan').text(data.temuan);
                    $('#detail_audit_tindak_lanjut').text(data.tindak_lanjut || 'Belum ada');
                    $('#detail_audit_evaluasi').text(data.evaluasi || 'Belum dievaluasi');
                    
                    var badge = $('#detail_audit_status_badge');
                    badge.text(data.status).attr('class', 'badge px-3 py-2 rounded-pill bg-' + (data.status === 'Open' ? 'danger' : 'success'));

                    $('#detailTemuanModal').modal('show');
                });
            });

            function parseErr(xhr) {
                var out = '<ul>';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (k, m) { out += '<li>' + m[0] + '</li>'; });
                } else {
                    out += '<li>' + ((xhr.responseJSON && xhr.responseJSON.message) || 'Terjadi kesalahan sistem.') + '</li>';
                }
                return out + '</ul>';
            }
        });
    </script>
    {{-- Stack untuk script tambahan dari view partial --}}
    @stack('scripts')

</body>

</html>