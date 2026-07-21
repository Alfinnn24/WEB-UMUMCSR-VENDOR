<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Peraturan Perusahaan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Peraturan Perusahaan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            <h6 class="fw-bold mb-0"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter</h6>
            <ul class="nav nav-pills ms-auto" style="margin-bottom:0!important">
                <li class="nav-item">
                    <a class="nav-link py-1 px-3 tab-filter {{ $tab === 'semua' ? 'active' : '' }}" href="#" data-tab="semua">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-3 tab-filter {{ $tab === 'PP' ? 'active' : '' }}" href="#" data-tab="PP">PP</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-3 tab-filter {{ $tab === 'PKB' ? 'active' : '' }}" href="#" data-tab="PKB">PKB</a>
                </li>
            </ul>
        </div>
        <form method="GET" action="{{ route('admin.peraturan.index') }}" id="filterForm">
            <input type="hidden" name="tab" id="tabFilter" value="{{ $tab }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Perusahaan</label>
                    <select name="perusahaan_id" id="perusahaanSelect" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach ($perusahaanOptions as $p)
                        <option value="{{ $p->id }}" {{ $perusahaan_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Cari Nomor Dokumen</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="searchPeraturan" class="form-control"
                               placeholder="Cari nomor dokumen..." value="{{ $search }}" autocomplete="off">
                        @if ($search)
                        <a href="#" class="btn btn-outline-secondary clear-search" title="Hapus pencarian">
                            <i class="bx bx-x"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" id="statusPeraturan" class="form-select form-select-sm">
                        <option value="all">Semua Status</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-outline-secondary w-100 btn-reset-peraturan">
                        <i class="bx bx-reset me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>

        @if ($perusahaan_id || $search || ($status && $status !== 'aktif') || $tab !== 'semua')
        <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
            <small class="text-muted fw-semibold me-1">Filter aktif:</small>
            @php
                $perusahaanNama = $perusahaan_id ? $perusahaanOptions->firstWhere('id', $perusahaan_id)?->nama : '';
            @endphp
            @if ($perusahaan_id && $perusahaanNama)
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bx bx-building text-muted me-1"></i>Perusahaan: <strong>{{ $perusahaanNama }}</strong>
                <a href="#" class="text-decoration-none ms-1 text-muted badge-remove" data-filter="perusahaan_id">
                    <i class="bx bx-x"></i>
                </a>
            </span>
            @endif
            @if ($search)
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bx bx-search text-muted me-1"></i>Pencarian: <strong>{{ $search }}</strong>
                <a href="#" class="text-decoration-none ms-1 text-muted badge-remove" data-filter="search">
                    <i class="bx bx-x"></i>
                </a>
            </span>
            @endif
            @if ($status && $status !== 'all')
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bx bx-check-circle text-muted me-1"></i>Status: <strong>{{ ucfirst($status) }}</strong>
                <a href="#" class="text-decoration-none ms-1 text-muted badge-remove" data-filter="status">
                    <i class="bx bx-x"></i>
                </a>
            </span>
            @endif
            @if ($tab !== 'semua')
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bx bx-tag text-muted me-1"></i>Jenis: <strong>{{ $tab }}</strong>
            </span>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
(function () {
    var typingTimer;
    var baseUrl = '{{ route('admin.peraturan.index') }}';

    function getVal(id) { var el = document.getElementById(id); return el ? el.value : ''; }

    function setVal(id, v) { var el = document.getElementById(id); if (el) el.value = v; }

    function loadTable() {
        var tab = getVal('tabFilter');
        var pid = getVal('perusahaanSelect');
        var q   = getVal('searchPeraturan');
        var st  = getVal('statusPeraturan');
        var params = { tab: tab, partial: 'table' };
        if (pid) params.perusahaan_id = pid;
        if (q)   params.search = q;
        if (st)  params.status = st;

        var qs      = $.param(params);
        var cleanQs = qs.replace(/(&?)partial=table/g, '');
        var container = document.getElementById('tableContainer');
        if (!container) return;

        container.innerHTML =
            '<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2"><div class="card-body text-center py-5"><div class="spinner-border text-primary mb-3" role="status"></div><div class="text-muted">Memuat data...</div></div></div>';

        $.ajax({
            url: baseUrl + '?' + qs,
            type: 'GET',
            dataType: 'html',
            success: function (html) {
                container.innerHTML = html;
                window.history.pushState(null, '', baseUrl + '?' + cleanQs);
            },
            error: function () {
                container.innerHTML =
                    '<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2"><div class="card-body text-center py-5"><div class="text-muted"><i class="bx bx-error-circle fs-3 d-block mb-2"></i>Gagal memuat data.</div></div></div>';
            }
        });
    }

    $(document).on('change', '#perusahaanSelect, #statusPeraturan', function () {
        loadTable();
    });

    $(document).on('keyup', '#searchPeraturan', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(loadTable, 500);
    });

    $(document).on('click', '.tab-filter', function (e) {
        e.preventDefault();
        $('.tab-filter').removeClass('active');
        $(this).addClass('active');
        setVal('tabFilter', $(this).data('tab'));
        loadTable();
    });

    $(document).on('click', '.btn-reset-peraturan', function (e) {
        e.preventDefault();
        setVal('perusahaanSelect', '');
        setVal('searchPeraturan', '');
        setVal('statusPeraturan', 'aktif');
        setVal('tabFilter', 'semua');
        $('.tab-filter').removeClass('active');
        $('.tab-filter[data-tab="semua"]').addClass('active');
        loadTable();
    });

    $(document).on('click', '.clear-search', function (e) {
        e.preventDefault();
        setVal('searchPeraturan', '');
        loadTable();
    });

    $(document).on('click', '.badge-remove', function (e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        if (filter === 'perusahaan_id') setVal('perusahaanSelect', '');
        if (filter === 'search') setVal('searchPeraturan', '');
        if (filter === 'status') setVal('statusPeraturan', 'aktif');
        loadTable();
    });
})();
</script>

<div id="tableContainer">
    @include('admin.peraturan.partials.table')
</div>
