<!doctype html>
<html lang="id" class="color-sidebar sidebarcolor7 color-header headercolor2">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/assets/js/jquery.min.js"></script>
    <link rel="icon" href="/assets/images/logo.png?v=1.0" type="image/png" />
    <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <script>window.paceOptions = { ajax: false, restartOnRequestAfter: false };</script>
    <script src="/assets/js/pace.min.js"></script>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dark-theme.css" />
    <link rel="stylesheet" href="/assets/css/semi-dark.css" />
    <link rel="stylesheet" href="/assets/css/header-colors.css" />
    <title>{{ $pageTitle ?? 'Dashboard' }} - Portal Perusahaan</title>
    <style>
        #ajax-loader{display:none;position:fixed;top:0;left:0;width:100%;height:3px;background:linear-gradient(90deg,#4f46e5,#0ea5e9,#10b981);background-size:200% 100%;animation:shimmer 1.2s ease-in-out infinite;z-index:9999}
        @keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
        #page-content{position:relative;transition:opacity .18s ease}
        #page-content.ajax-fading{opacity:.3;pointer-events:none}
        .metismenu li.mm-active>a,.metismenu li>a.nav-link-active{background:rgba(255,255,255,.15)!important;border-radius:6px}
    </style>
</head>
<body>
    <div id="ajax-loader"></div>
    <div class="wrapper">

        {{-- ── SIDEBAR ── --}}
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div><img src="/assets/images/logo.png" style="height:48px;width:auto;" alt="logo"></div>
                <div><h4 class="logo-text"></h4></div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
            </div>
            <ul class="metismenu" id="menu">
                <li>
                    <a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"
                       data-url="{{ route('perusahaan.dashboard') }}" data-title="Dashboard">
                        <div class="parent-icon"><i class='bx bxs-dashboard'></i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('perusahaan.profile') }}" class="nav-ajax" data-url="{{ route('perusahaan.profile') }}" data-title="Profile">
                        <div class="parent-icon"><i class='bx bxs-user-circle'></i></div>
                        <div class="menu-title">Profile</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('perusahaan.temuan-audit.index') }}" class="nav-ajax" data-url="{{ route('perusahaan.temuan-audit.index') }}" data-title="Temuan Audit">
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
                        <li><a href="{{ route('perusahaan.karyawan.index') }}" class="nav-ajax" data-url="{{ route('perusahaan.karyawan.index') }}" data-title="Data Karyawan"><i class="bx bxs-group"></i>Data Karyawan</a></li>
                        <li><a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="nav-ajax" data-url="{{ route('perusahaan.kontrak-kerja.index') }}" data-title="Kontrak Kerja"><i class="bx bxs-file-blank"></i>Kontrak Kerja</a></li>
                        <li><a href="{{ route('perusahaan.sertifikasi.index') }}" class="nav-ajax" data-url="{{ route('perusahaan.sertifikasi.index') }}" data-title="Data Sertifikasi"><i class="bx bxs-tree"></i>Data Sertifikasi</a></li>
                        <li><a href="{{ route('perusahaan.laporan-tenaga-kerja.index') }}" class="nav-ajax" data-url="{{ route('perusahaan.laporan-tenaga-kerja.index') }}" data-title="Laporan Tenaga Kerja"><i class="bx bxs-group"></i>Laporan Tenaga Kerja</a></li>
                    </ul>
                </li>
                <li class="menu-label">Informasi</li>
                <li>
                    <a href="{{ route('perusahaan.informasi.index') }}" class="nav-ajax" data-url="{{ route('perusahaan.informasi.index') }}" data-title="Informasi">
                        <div class="parent-icon"><i class='bx bxs-info-circle'></i></div>
                        <div class="menu-title">Informasi</div>
                    </a>
                </li>
            </ul>
        </div>

        {{-- ── HEADER ── --}}
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
                    <div class="search-bar flex-grow-1">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" placeholder="Type to search...">
                            <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                        </div>
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item mobile-search-icon"><a class="nav-link" href="#"><i class='bx bx-search'></i></a></li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="/assets/images/team.png" class="user-img" alt="avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{ ucwords(session('nama', 'Perusahaan')) }}</p>
                                <p class="designattion mb-0">{{ ucwords(session('role', '')) }}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item nav-ajax" data-url="{{ route('perusahaan.profile') }}" data-title="Profile" href="{{ route('perusahaan.profile') }}"><i class="bx bx-user"></i><span>Profile</span></a></li>
                            <li><div class="dropdown-divider mb-0"></div></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a></li>
                        </ul>
                    </div>
                </nav>
                <div class="header-notifications-list" style="display:none"></div>
                <div class="header-message-list" style="display:none"></div>
            </div>
        </header>

        {{-- ── PAGE CONTENT ── --}}
        <div class="page-wrapper">
            <div class="page-content page-content-wrapper" id="page-content">
                @includeIf($page ?? 'perusahaan.dashboard', $__data ?? [])
            </div>
        </div>

        <div class="overlay toggle-icon"></div>
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <footer class="page-footer">
            <p class="mb-0">Copyright &copy; {{ date('Y') }}. <a href="https://tuyulcode.netlify.app/" target="_blank">tuyulcode</a></p>
        </footer>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/assets/js/app_baru.js?v=3"></script>

    <script>
        $(function () {

            // ── Setup CSRF & AJAX headers ──────────────────────────────
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // ── Fungsi load halaman via AJAX ────────────────────────────
            function ajaxLoadPage(url, pageTitle, pushHistory) {
                var $loader = $('#ajax-loader');
                var $content = $('#page-content');

                $loader.show();
                $content.addClass('ajax-fading');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (html) {
                        setTimeout(function () {
                            $content.html(html);

                            if (pageTitle) {
                                document.title = pageTitle + ' - Portal Perusahaan';
                            }

                            if (pushHistory !== false) {
                                history.pushState({ url: url, title: pageTitle }, pageTitle, url);
                            }

                            $content.removeClass('ajax-fading');
                            $('.page-wrapper').animate({ scrollTop: 0 }, 150);

                            reinitDataTables();
                            reinitCharts();
                            highlightActiveMenu(url);

                            $loader.hide();
                        }, 180);
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

            // ── Event: klik link sidebar / header ───────────────────────
            $(document).on('click', 'a.nav-ajax', function (e) {
                e.preventDefault();
                const url = $(this).data('url') || $(this).attr('href');
                const pageTitle = $(this).data('title') || '';
                if (!url || url === '#' || url === 'javascript:;') return;
                ajaxLoadPage(url, pageTitle, true);
            });

            // ── Event: browser back / forward ────────────────────────────
            window.addEventListener('popstate', function (e) {
                if (e.state && e.state.url) {
                    ajaxLoadPage(e.state.url, e.state.title, false);
                }
            });

            history.replaceState(
                { url: window.location.href, title: document.title },
                document.title,
                window.location.href
            );

            // ── Highlight menu aktif ─────────────────────────────────────
            function highlightActiveMenu(url) {
                $('.metismenu a').removeClass('nav-link-active');
                $('.metismenu a.nav-ajax').each(function () {
                    const linkUrl = $(this).data('url') || $(this).attr('href');
                    if (linkUrl && url.includes(linkUrl)) {
                        $(this).addClass('nav-link-active');
                    }
                });
            }
            highlightActiveMenu(window.location.href);

            // ── Re-init DataTables setelah AJAX inject ───────────────────
            function reinitDataTables() {
                if ($.fn.DataTable) {
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
                        $(this).DataTable({ lengthChange: false });
                    });
                }
            }

            // ── Trigger custom re-init untuk charts dkk ──────────────────
            function reinitCharts() {
                document.dispatchEvent(new CustomEvent('ajaxPageLoaded'));
            }

            $(document).on('ajaxPageLoaded', reinitDataTables);
            reinitDataTables();

            // ── Alert auto-fade ──────────────────────────────────────────
            $(document).on('ajaxPageLoaded', function () {
                window.setTimeout(function () {
                    $('.alert-auto-dismiss').fadeTo(1000, 0).slideUp(1000, function () {
                        $(this).remove();
                    });
                }, 3000);
            });
            window.setTimeout(function () {
                $('.alert-auto-dismiss').fadeTo(1000, 0).slideUp(1000, function () {
                    $(this).remove();
                });
            }, 3000);

            // ── Profile Form AJAX ────────────────────────────────────────
            $(document).on('submit', '#updateProfileForm', function (e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function (res) {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan Profil');
                        $('#profileSuccessAlert').removeClass('d-none').text(res.message);
                        $('#profileErrorAlert').addClass('d-none');
                        $('#profile-display-alamat').text(form.find('textarea[name="alamat"]').val() || 'Alamat belum diisi');
                        $('#profile-display-admin').text(form.find('input[name="nama_admin"]').val() || 'Nama Admin belum diisi');
                        $('#profile-display-nomor').text(form.find('input[name="nomor_admin"]').val() || 'No Admin belum diisi');
                        setTimeout(function () { $('#profileSuccessAlert').addClass('d-none'); }, 3000);
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan Profil');
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        $('#profileErrorAlert').removeClass('d-none').text(msg);
                        $('#profileSuccessAlert').addClass('d-none');
                    }
                });
            });

            // ── Password Form AJAX ───────────────────────────────────────
            $(document).on('submit', '#updatePasswordForm', function (e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function (res) {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Ubah Password');
                        $('#passwordSuccessAlert').removeClass('d-none').text(res.message);
                        $('#passwordErrorAlert').addClass('d-none');
                        form.trigger('reset');
                        setTimeout(function () { $('#passwordSuccessAlert').addClass('d-none'); }, 3000);
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Ubah Password');
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        $('#passwordErrorAlert').removeClass('d-none').text(msg);
                        $('#passwordSuccessAlert').addClass('d-none');
                    }
                });
            });

            // ── Tindak Lanjut Form AJAX ─────────────────────────────────
            $(document).on('submit', '#tindakLanjutForm', function (e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function (res) {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan');
                        if (res.status === 'success') {
                            if (typeof ajaxLoadPage === 'function' && res.redirect) {
                                ajaxLoadPage(res.redirect, 'Temuan Audit', true);
                            } else {
                                window.location.href = res.redirect || '/perusahaan/temuan-audit';
                            }
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan');
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        $('#tindakLanjutErrorAlert').removeClass('d-none').text(msg);
                    }
                });
            });

            // ── Toggle Password Show/Hide ───────────────────────────────
            $(document).on('click', '.toggle-pass', function (e) {
                e.preventDefault();
                var target = $($(this).data('target'));
                var icon = $(this).find('i');
                if (target.attr('type') === 'password') {
                    target.attr('type', 'text');
                    icon.removeClass('bx-hide').addClass('bx-show');
                } else {
                    target.attr('type', 'password');
                    icon.removeClass('bx-show').addClass('bx-hide');
                }
            });

            // ═══════════════════════════════════════════════════════════
            //  KARYAWAN EVENT DELEGATION
            // ═══════════════════════════════════════════════════════════

            $(document).on('click', '.btn-show-detail', function (e) {
                var id = $(this).data('id');
                var modal = new bootstrap.Modal(document.getElementById('modalDetail'));
                document.getElementById('modalDetailNama').textContent = 'Memuat Data...';
                document.getElementById('modalDetailMeta').innerHTML = '';
                document.getElementById('modalDetailBody').innerHTML = '<div class="text-center py-5"><i class="bx bx-loader-alt bx-spin font-40 text-primary"></i></div>';
                modal.show();
                $.ajax({
                    url: '/perusahaan/karyawan/' + id, type: 'GET',
                    success: function (row) {
                        document.getElementById('modalDetailNama').textContent = row.nama;
                        document.getElementById('modalDetailMeta').innerHTML =
                            '<span class="badge bg-light text-dark me-2">NIK: ' + (row.nik || '') + '</span>' +
                            '<span class="badge bg-light text-dark me-2">KTP: ' + (row.nomor_ktp || '') + '</span>' +
                            '<span class="badge bg-light text-dark">HP: ' + (row.no_hp || '') + '</span>';
                        var esc = function (s) { return s || '-'; };
                        var jk = row.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                        var unitCls = row.unit === 'UNIT 9' ? 'primary' : 'info';
                        var stClass = row.status === 'Aktif' ? 'success' : 'danger';
                        var stIcon = row.status === 'Aktif' ? 'bx-check-circle' : 'bx-x-circle';
                        var dRow = function (l, v) { return '<tr><td width="200" class="text-muted">' + l + '</td><td width="15">:</td><td>' + v + '</td></tr>'; };
                        var dSec = function (t, c) { return '<div class="px-4 py-3 bg-light border-bottom"><h6 class="mb-0 fw-bold text-primary">' + t + '</h6></div><div class="p-4"><table class="table table-sm table-borderless mb-0">' + c + '</table></div>'; };
                        document.getElementById('modalDetailBody').innerHTML =
                            dSec('Identitas Utama',
                                dRow('Nama', '<strong>' + esc(row.nama) + '</strong>') +
                                dRow('NIK', esc(row.nik)) + dRow('KTP', esc(row.nomor_ktp)) +
                                dRow('NPWP', esc(row.npwp)) + dRow('HP', esc(row.no_hp)) +
                                dRow('Email', esc(row.email))) +
                            dSec('Data Pribadi',
                                dRow('Jenis Kelamin', jk) + dRow('Agama', esc(row.agama)) +
                                dRow('Tgl Lahir', (row.tempat_lahir || '') + ', ' + (row.tanggal_lahir || '-')) +
                                dRow('Status', esc(row.status_perkawinan))) +
                            dSec('Alamat Tinggal',
                                dRow('Alamat', esc(row.alamat_tinggal)) +
                                dRow('Desa', esc(row.desa)) + dRow('Kecamatan', esc(row.kecamatan)) +
                                dRow('Kabupaten', esc(row.kabupaten)) + dRow('Provinsi', esc(row.provinsi))) +
                            dSec('Alamat KTP', dRow('Alamat KTP', esc(row.alamat_ktp))) +
                            dSec('Kepegawaian',
                                dRow('Mulai Kerja', esc(row.mulai_masuk_kerja)) +
                                dRow('Pendidikan', esc(row.pendidikan_terakhir)) +
                                dRow('BPJS Kes', esc(row.bpjs_kesehatan)) +
                                dRow('BPJS TK', esc(row.bpjs_ketenagakerjaan))) +
                            dSec('Jabatan & Struktur',
                                dRow('Jabatan', esc(row.jabatan)) +
                                dRow('Divisi', esc(row.div_desc)) +
                                dRow('Sub Divisi', esc(row.subdiv_desc)) +
                                dRow('Unit', '<span class="badge bg-' + unitCls + ' px-3">' + esc(row.unit) + '</span>') +
                                dRow('Status', '<span class="badge bg-' + stClass + ' px-3"><i class="bx ' + stIcon + ' me-1"></i>' + esc(row.status) + '</span>'));
                        document.getElementById('modalEditLink').href = '/perusahaan/karyawan/' + row.id + '/edit';
                    },
                    error: function () {
                        document.getElementById('modalDetailBody').innerHTML = '<div class="alert alert-danger m-3">Gagal mengambil data.</div>';
                    }
                });
            });

            $(document).on('submit', '.form-toggle-status', function (e) {
                e.preventDefault();
                var form = $(this), btn = form.find('button[type="submit"]');
                var cur = btn.data('status'), act = cur === 'Aktif' ? 'menonaktifkan' : 'mengaktifkan';
                if (!confirm('Yakin ' + act + ' karyawan ini?')) return;
                btn.prop('disabled', true);
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function () { ajaxLoadPage(window.location.href, 'Data Karyawan', false); },
                    error: function (xhr) {
                        btn.prop('disabled', false);
                        alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal');
                    }
                });
            });

            $(document).on('submit', '.form-delete-karyawan', function (e) {
                e.preventDefault();
                var form = $(this), btn = form.find('button[type="submit"]'), nama = btn.data('nama') || 'ini';
                if (!confirm('Yakin hapus ' + nama + '? Tidak bisa dibatalkan.')) return;
                btn.prop('disabled', true);
                $.ajax({
                    url: form.attr('action'), type: 'DELETE', data: form.serialize(),
                    success: function () { ajaxLoadPage(window.location.href, 'Data Karyawan', false); },
                    error: function (xhr) {
                        btn.prop('disabled', false);
                        alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal');
                    }
                });
            });

            $(document).on('submit', '#formKaryawan', function (e) {
                e.preventDefault();
                var form = $(this), btn = form.find('button[type="submit"]'), orig = btn.html();
                var ktp = form.find('input[name="nomor_ktp"]').val().trim();
                if (ktp.length !== 16 || !/^\d+$/.test(ktp)) { alert('KTP harus 16 digit angka.'); form.find('input[name="nomor_ktp"]').focus(); return false; }
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: form.serialize(),
                    success: function (res) { btn.prop('disabled', false).html(orig); alert(res.message); ajaxLoadPage('/perusahaan/karyawan', 'Data Karyawan', true); },
                    error: function (xhr) {
                        btn.prop('disabled', false).html(orig);
                        alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal');
                    }
                });
            });

            $(document).on('submit', '#formImportExcel', function (e) {
                e.preventDefault();
                var form = $(this), fd = new FormData(this), $l = $('#ajax-loader'), $c = $('#page-content');
                var mEl = document.getElementById('modalImport'), m = bootstrap.Modal.getInstance(mEl);
                if (m) m.hide();
                $l.show(); $c.addClass('ajax-fading');
                $.ajax({
                    url: form.attr('action'), type: 'POST', data: fd, processData: false, contentType: false,
                    success: function (html) {
                        $c.html(html);
                        document.title = 'Review Import Karyawan - Portal Perusahaan';
                        history.pushState({ url: '/perusahaan/karyawan/import-review', title: 'Review Import' }, 'Review Import', '/perusahaan/karyawan/import-review');
                        $c.removeClass('ajax-fading'); $l.hide();
                        document.dispatchEvent(new CustomEvent('ajaxPageLoaded'));
                    },
                    error: function (xhr) { $c.removeClass('ajax-fading'); $l.hide(); alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal'); }
                });
            });

            $(document).on('submit', '#formImport', function (e) {
                e.preventDefault();
                var allOK = true, firstBad = -1;
                if (typeof totalRows !== 'undefined') {
                    for (var i = 0; i < totalRows; i++) {
                        if (typeof checkComplete === 'function') checkComplete(i);
                        if (typeof rowComplete !== 'undefined' && !rowComplete[i]) { allOK = false; if (firstBad === -1) firstBad = i; }
                    }
                }
                if (!allOK) {
                    alert('Lengkapi Alamat, Provinsi/Kab/Kec/Desa, Alamat KTP, Divisi, dan Sub Divisi.');
                    var cEl = document.getElementById('detail-' + firstBad);
                    if (cEl && !cEl.classList.contains('show')) new bootstrap.Collapse(cEl, { toggle: true });
                    document.getElementById('card-' + firstBad).scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
                if (!confirm('Simpan semua data? Status akan Aktif.')) return;
                var form = $(this), btn = form.find('#btnSimpan'), orig = btn.html();
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
                $.ajax({
                    url: '/perusahaan/karyawan/import-store', type: 'POST', data: form.serialize(),
                    success: function (res) { btn.prop('disabled', false).html(orig); alert(res.message); ajaxLoadPage('/perusahaan/karyawan', 'Data Karyawan', true); },
                    error: function (xhr) { btn.prop('disabled', false).html(orig); alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal'); }
                });
            });

        });
    </script>
</body>
</html>