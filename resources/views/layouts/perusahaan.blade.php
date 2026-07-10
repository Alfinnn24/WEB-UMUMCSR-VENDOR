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
                        <li><a href="{{ route('perusahaan.laporan-tenaga-kerja') }}" class="nav-ajax" data-url="{{ route('perusahaan.laporan-tenaga-kerja') }}" data-title="Laporan Tenaga Kerja"><i class="bx bxs-group"></i>Laporan Tenaga Kerja</a></li>
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
    $(function(){
        // Setup CSRF and AJAX headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Page loader function
        function ajaxLoadPage(url,title,push){
            var $l=$('#ajax-loader'),$c=$('#page-content');
            $l.show();$c.addClass('ajax-fading');
            $.ajax({url:url,type:'GET',
                success:function(html){setTimeout(function(){$c.html(html);if(title)document.title=title+' - Portal Perusahaan';if(push!==false)history.pushState({url:url,title:title},title,url);$c.removeClass('ajax-fading');$('.page-wrapper').animate({scrollTop:0},150);document.dispatchEvent(new CustomEvent('ajaxPageLoaded'));highlightActiveMenu(url);$l.hide()},180)},
                error:function(xhr){$c.html('<div class="alert alert-danger m-4"><i class="bx bx-error-circle me-2"></i>Gagal memuat ('+xhr.status+').</div>');$c.removeClass('ajax-fading');$l.hide()}
            });
        }

        // Navigation click handler
        $(document).on('click','a.nav-ajax',function(e){e.preventDefault();var u=$(this).data('url')||$(this).attr('href'),t=$(this).data('title')||'';if(!u||u==='#')return;ajaxLoadPage(u,t,true)});
        window.addEventListener('popstate',function(e){if(e.state&&e.state.url)ajaxLoadPage(e.state.url,e.state.title,false)});
        history.replaceState({url:window.location.href,title:document.title},document.title,window.location.href);

        // Highlight active menu
        function highlightActiveMenu(url){$('.metismenu a').removeClass('nav-link-active');$('.metismenu a.nav-ajax').each(function(){var l=$(this).data('url')||$(this).attr('href');if(l&&url.includes(l))$(this).addClass('nav-link-active')});}
        highlightActiveMenu(window.location.href);

        // Profile Form AJAX Submission
        $(document).on('submit', '#updateProfileForm', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan Profil');
                    $('#profileSuccessAlert').removeClass('d-none').text(res.message);
                    $('#profileErrorAlert').addClass('d-none');
                    $('#profile-display-alamat').text(form.find('textarea[name="alamat"]').val() || 'Alamat belum diisi');
                    $('#profile-display-admin').text(form.find('input[name="nama_admin"]').val() || 'Nama Admin belum diisi');
                    $('#profile-display-nomor').text(form.find('input[name="nomor_admin"]').val() || 'No Admin belum diisi');
                    setTimeout(function() { $('#profileSuccessAlert').addClass('d-none'); }, 3000);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan Profil');
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                    $('#profileErrorAlert').removeClass('d-none').text(msg);
                    $('#profileSuccessAlert').addClass('d-none');
                }
            });
        });



        // Password Form AJAX Submission
        $(document).on('submit', '#updatePasswordForm', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Ubah Password');
                    $('#passwordSuccessAlert').removeClass('d-none').text(res.message);
                    $('#passwordErrorAlert').addClass('d-none');
                    form.trigger('reset');
                    setTimeout(function() { $('#passwordSuccessAlert').addClass('d-none'); }, 3000);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Ubah Password');
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                    $('#passwordErrorAlert').removeClass('d-none').text(msg);
                    $('#passwordSuccessAlert').addClass('d-none');
                }
            });
        });

        // Tindak Lanjut Form AJAX Submission
        $(document).on('submit', '#tindakLanjutForm', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan');
                    if (res.status === 'success') {
                        if (typeof ajaxLoadPage === 'function' && res.redirect) {
                            ajaxLoadPage(res.redirect, 'Temuan Audit', true);
                        } else {
                            window.location.href = res.redirect || '/perusahaan/temuan-audit';
                        }
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan');
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                    $('#tindakLanjutErrorAlert').removeClass('d-none').text(msg);
                }
            });
        });

        // Toggle Password Show/Hide
        $(document).on('click', '.toggle-pass', function(e) {
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

        // ── KARYAWAN EVENT DELEGATION ─────────────────────────────────

        // Detail Karyawan (Show Modal)
        $(document).on('click', '.btn-show-detail', function(e) {
            var id = $(this).data('id');
            var modal = new bootstrap.Modal(document.getElementById('modalDetail'));
            
            document.getElementById('modalDetailNama').textContent = 'Memuat Data...';
            document.getElementById('modalDetailMeta').innerHTML = '';
            document.getElementById('modalDetailBody').innerHTML = '<div class="text-center py-5"><i class="bx bx-loader-alt bx-spin font-40 text-primary"></i></div>';
            
            modal.show();
            
            $.ajax({
                url: '/perusahaan/karyawan/' + id,
                type: 'GET',
                success: function(row) {
                    document.getElementById('modalDetailNama').textContent = row.nama;
                    document.getElementById('modalDetailMeta').innerHTML = `
                        <span class="badge bg-light text-dark me-2">NIK: ${row.nik}</span>
                        <span class="badge bg-light text-dark me-2">KTP: ${row.nomor_ktp}</span>
                        <span class="badge bg-light text-dark">HP: ${row.no_hp}</span>
                    `;
                    
                    const esc = (s) => (s || '-');
                    const jk = row.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                    const unitCls = row.unit === 'UNIT 9' ? 'primary' : 'info';
                    const stClass = row.status === 'Aktif' ? 'success' : 'danger';
                    const stIcon = row.status === 'Aktif' ? 'bx-check-circle' : 'bx-x-circle';
                    
                    const dSection = (title, content) => `
                        <div class="px-4 py-3 bg-light border-bottom">
                            <h6 class="mb-0 fw-bold text-primary">${title}</h6>
                        </div>
                        <div class="p-4"><table class="table table-sm table-borderless mb-0">${content}</table></div>
                    `;
                    const dRow = (label, val) => `
                        <tr>
                            <td width="200" class="text-muted">${label}</td>
                            <td width="15">:</td>
                            <td>${val}</td>
                        </tr>
                    `;

                    document.getElementById('modalDetailBody').innerHTML =
                        dSection('Identitas Utama', [
                            dRow('Nama Lengkap',   `<strong>${esc(row.nama)}</strong>`),
                            dRow('NIK Karyawan',   esc(row.nik)),
                            dRow('Nomor KTP',      esc(row.nomor_ktp)),
                            dRow('NPWP',           esc(row.npwp)),
                            dRow('No HP',          esc(row.no_hp)),
                            dRow('Email',          esc(row.email)),
                        ].join('')) +

                        dSection('Data Pribadi', [
                            dRow('Jenis Kelamin',     jk),
                            dRow('Agama',             esc(row.agama)),
                            dRow('Tempat, Tgl Lahir', `${esc(row.tempat_lahir)}, ${row.tanggal_lahir || '-'}`),
                            dRow('Status Perkawinan', esc(row.status_perkawinan)),
                        ].join('')) +

                        dSection('Alamat Asal / Kelahiran', [
                            dRow('Alamat',           esc(row.alamat_tinggal)),
                            dRow('Desa / Kelurahan', esc(row.desa)),
                            dRow('Kecamatan',        esc(row.kecamatan)),
                            dRow('Kabupaten / Kota', esc(row.kabupaten)),
                            dRow('Provinsi',         esc(row.provinsi)),
                        ].join('')) +

                        dSection('Alamat Sesuai KTP/Domisili', [
                            dRow('Alamat KTP', esc(row.alamat_ktp)),
                        ].join('')) +

                        dSection('Data Kepegawaian', [
                            dRow('Mulai Masuk Kerja',    esc(row.mulai_masuk_kerja)),
                            dRow('Pendidikan Terakhir',  esc(row.pendidikan_terakhir)),
                            dRow('BPJS Kesehatan',       esc(row.bpjs_kesehatan)),
                            dRow('BPJS Ketenagakerjaan', esc(row.bpjs_ketenagakerjaan)),
                        ].join('')) +

                        dSection('Jabatan & Struktur Organisasi', [
                            dRow('Jabatan',    esc(row.jabatan)),
                            dRow('Divisi',     esc(row.div_desc)),
                            dRow('Sub Divisi', esc(row.subdiv_desc)),
                            dRow('Unit',       `<span class="badge bg-${unitCls} rounded-pill px-3">${esc(row.unit)}</span>`),
                            dRow('Status',     `<span class="badge bg-${stClass} rounded-pill px-3"><i class="bx ${stIcon} me-1"></i>${esc(row.status)}</span>`),
                        ].join('')) +

                        `<div style="height:12px"></div>`;

                    document.getElementById('modalEditLink').href = '/perusahaan/karyawan/' + row.id + '/edit';
                },
                error: function(xhr) {
                    document.getElementById('modalDetailBody').innerHTML = '<div class="alert alert-danger m-3">Gagal mengambil data karyawan.</div>';
                }
            });
        });

        // Toggle Status Karyawan
        $(document).on('submit', '.form-toggle-status', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var currentStatus = btn.data('status');
            const action = currentStatus === 'Aktif' ? 'menonaktifkan' : 'mengaktifkan';
            if (!confirm(`Apakah Anda yakin ingin ${action} karyawan ini?`)) {
                return false;
            }
            btn.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    ajaxLoadPage(window.location.href, 'Data Karyawan', false);
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal mengubah status');
                }
            });
        });

        // Hapus Karyawan
        $(document).on('submit', '.form-delete-karyawan', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var nama = btn.data('nama') || 'ini';
            if (!confirm(`Yakin ingin menghapus karyawan ${nama}? Tindakan ini tidak dapat dibatalkan.`)) {
                return false;
            }
            btn.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: 'DELETE',
                data: form.serialize(),
                success: function(res) {
                    ajaxLoadPage(window.location.href, 'Data Karyawan', false);
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menghapus data';
                    alert(msg);
                }
            });
        });

        // Form Tambah / Edit Karyawan AJAX
        $(document).on('submit', '#formKaryawan', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var originalText = btn.html();
            
            // NIK & KTP Validation
            const ktp = form.find('input[name="nomor_ktp"]').val().trim();
            if (ktp.length !== 16 || !/^\d+$/.test(ktp)) {
                alert('Nomor KTP harus 16 digit angka.');
                form.find('input[name="nomor_ktp"]').focus();
                return false;
            }

            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
            
            // Decide method (if _method exists in serialize)
            var type = form.attr('method') || 'POST';
            if (form.find('input[name="_method"]').val()) {
                // If it is a PUT request
                type = 'POST';
            }

            $.ajax({
                url: form.attr('action'),
                type: type,
                data: form.serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html(originalText);
                    alert(res.message);
                    ajaxLoadPage('/perusahaan/karyawan', 'Data Karyawan', true);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html(originalText);
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                    alert(msg);
                }
            });
        });

        // Form Import Excel (Upload & Review)
        $(document).on('submit', '#formImportExcel', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            var $loader = $('#ajax-loader'), $c = $('#page-content');
            
            // Hide modal
            var modalEl = document.getElementById('modalImport');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            
            $loader.show();
            $c.addClass('ajax-fading');
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(html) {
                    $c.html(html);
                    document.title = 'Review Import Karyawan - Portal Perusahaan';
                    history.pushState({url: '/perusahaan/karyawan/import-review', title: 'Review Import Karyawan'}, 'Review Import Karyawan', '/perusahaan/karyawan/import-review');
                    $c.removeClass('ajax-fading');
                    $loader.hide();
                    document.dispatchEvent(new CustomEvent('ajaxPageLoaded'));
                },
                error: function(xhr) {
                    $c.removeClass('ajax-fading');
                    $loader.hide();
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memproses file.';
                    alert(msg);
                }
            });
        });

        // Form Import Review (Save Store)
        $(document).on('submit', '#formImport', function(e) {
            e.preventDefault();
            
            let allComplete = true;
            let firstIncomplete = -1;

            if (typeof totalRows !== 'undefined') {
                for (let i = 0; i < totalRows; i++) {
                    checkComplete(i);
                    if (!rowComplete[i]) {
                        allComplete = false;
                        if (firstIncomplete === -1) firstIncomplete = i;
                    }
                }
            }

            if (!allComplete) {
                alert(`Masih ada data yang belum lengkap. Silakan lengkapi Alamat, Provinsi/Kab/Kec/Desa, Alamat KTP, Divisi, dan Sub Divisi untuk setiap karyawan.`);
                const collapseEl = document.getElementById('detail-' + firstIncomplete);
                if (collapseEl && !collapseEl.classList.contains('show')) {
                    new bootstrap.Collapse(collapseEl, { toggle: true });
                }
                document.getElementById('card-' + firstIncomplete).scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            if (!confirm('Yakin simpan semua data karyawan? Semua akan tersimpan dengan status Aktif.')) {
                return false;
            }

            var form = $(this);
            var btn = form.find('#btnSimpan');
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
            $.ajax({
                url: '/perusahaan/karyawan/import-store',
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html(originalText);
                    alert(res.message);
                    ajaxLoadPage('/perusahaan/karyawan', 'Data Karyawan', true);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html(originalText);
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                    alert(msg);
                }
            });
        });
    });
</body>
</html>
