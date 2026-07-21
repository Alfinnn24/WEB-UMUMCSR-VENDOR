<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Bukti Kepesertaan BPJS Perusahaan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Bukti Kepesertaan BPJS</li>
            </ol>
        </nav>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2 alert-auto-dismiss">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-white">{{ session('success') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2 alert-auto-dismiss">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-x-circle"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-white">{{ session('error') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter</h6>
        <form method="GET" action="{{ route('admin.bpjs-monitoring.index') }}" id="filterBpjsForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Cari Perusahaan</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="searchPerusahaan" class="form-control" placeholder="Cari nama perusahaan..." value="{{ $search }}" autocomplete="off">
                        @if ($search)
                        <a href="#" class="btn btn-outline-secondary clear-search" title="Hapus pencarian"><i class="bx bx-x"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Status Dokumen</label>
                    <select name="status" id="filterStatus" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="uploaded" {{ $filter_status === 'uploaded' ? 'selected' : '' }}>Ada File</option>
                        <option value="not_uploaded" {{ $filter_status === 'not_uploaded' ? 'selected' : '' }}>Belum Ada File</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-reset-bpjs"><i class="bx bx-reset me-1"></i>Reset</button>
                </div>
            </div>
        </form>

        @if ($search || $filter_status)
        <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
            <small class="text-muted fw-semibold me-1">Filter aktif:</small>
            @if ($search)
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bx bx-search text-muted me-1"></i>Pencarian: <strong>{{ $search }}</strong>
                <a href="#" class="text-decoration-none ms-1 text-muted badge-remove" data-filter="search"><i class="bx bx-x"></i></a>
            </span>
            @endif
            @if ($filter_status)
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bx bx-check-circle text-muted me-1"></i>Status: <strong>{{ $filter_status === 'uploaded' ? 'Ada File' : 'Belum Ada File' }}</strong>
                <a href="#" class="text-decoration-none ms-1 text-muted badge-remove" data-filter="status"><i class="bx bx-x"></i></a>
            </span>
            @endif
        </div>
        @endif
    </div>
</div>

<div id="tableContainer">
    @include('admin.bpjs_monitoring.partials.table')
</div>

<script>
(function () {
    var typingTimer;
    var baseUrl = '{{ route('admin.bpjs-monitoring.index') }}';

    function getVal(id) { var el = document.getElementById(id); return el ? el.value : ''; }
    function setVal(id, v) { var el = document.getElementById(id); if (el) el.value = v; }

    function loadTable() {
        var search = getVal('searchPerusahaan');
        var status = getVal('filterStatus');

        var params = { partial: 'table' };
        if (search) params.search = search;
        if (status) params.status = status;

        var qs = $.param(params);
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

    $(document).on('change', '#filterStatus', function () {
        loadTable();
    });

    $(document).on('keyup', '#searchPerusahaan', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(loadTable, 500);
    });

    $(document).on('click', '.clear-search', function (e) {
        e.preventDefault();
        setVal('searchPerusahaan', '');
        loadTable();
    });

    $(document).on('click', '.btn-reset-bpjs', function (e) {
        e.preventDefault();
        setVal('searchPerusahaan', '');
        setVal('filterStatus', '');
        loadTable();
    });

    $(document).on('click', '.badge-remove', function (e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        if (filter === 'search') setVal('searchPerusahaan', '');
        if (filter === 'status') setVal('filterStatus', '');
        loadTable();
    });
})();
</script>