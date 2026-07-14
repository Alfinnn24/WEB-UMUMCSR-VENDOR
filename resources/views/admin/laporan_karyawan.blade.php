<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Data Karyawan</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Laporan Data Karyawan</h6>
        <form id="filterLapKaryawanForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="laporan_karyawan">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach ($all_perusahaan as $p)
                        <option value="{{ $p->id }}" {{ $filter_perusahaan == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="Aktif" {{ $filter_status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ $filter_status === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Jenis Kelamin</label>
                    <select name="jk" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="L" {{ $filter_jk === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $filter_jk === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Unit</label>
                    <select name="unit" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="UNIT 9"  {{ $filter_unit === 'UNIT 9' ? 'selected' : '' }}>UNIT 9</option>
                        <option value="UNIT 12" {{ $filter_unit === 'UNIT 12' ? 'selected' : '' }}>UNIT 12</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bx bx-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.dashboard') }}?page=laporan_karyawan" class="btn btn-sm btn-outline-secondary px-3 nav-ajax" data-url="{{ route('admin.dashboard') }}?page=laporan_karyawan" data-title="Laporan Karyawan"><i class="bx bx-reset me-1"></i>Reset</a>
                    <a href="/admin/laporan/export_karyawan.php?{{ http_build_query(array_filter(['perusahaan_id' => $filter_perusahaan, 'status' => $filter_status, 'jk' => $filter_jk, 'unit' => $filter_unit])) }}" class="btn btn-sm btn-success px-3" target="_blank"><i class="bx bx-download me-1"></i>Export Excel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Table Card -->
<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2">
            <div>
                <h6 class="fw-bold mb-0">Data Karyawan</h6>
                <small class="text-muted">Menampilkan <strong>{{ count($data) }}</strong> karyawan</small>
            </div>
            <div class="ms-auto"><input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari nama / NIK / jabatan..." style="width:240px" onkeyup="filterTable()"></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0" id="tbl">
                <thead class="table-light">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>JK</th>
                        <th>Tgl Lahir</th>
                        <th>Jabatan</th>
                        <th>Unit</th>
                        <th>Mulai Kerja</th>
                        <th>Pendidikan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($data as $i => $r)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold d-inline-block text-truncate" style="max-width:110px" title="{{ $r->nama_perusahaan ?? '-' }}">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td><small class="text-muted">{{ $r->nik }}</small></td>
                        <td><small class="fw-semibold">{{ $r->nama }}</small></td>
                        <td><small>{{ $r->jenis_kelamin === 'L' ? 'L' : 'P' }}</small></td>
                        <td><small>{{ $r->tanggal_lahir ? date('d/m/Y', strtotime($r->tanggal_lahir)) : '-' }}</small></td>
                        <td><small>{{ $r->jabatan }}</small></td>
                        <td><span class="badge bg-info bg-opacity-75 rounded-pill badge-xs">{{ $r->unit }}</span></td>
                        <td><small class="text-muted">{{ $r->mulai_masuk_kerja ? date('d/m/Y', strtotime($r->mulai_masuk_kerja)) : '-' }}</small></td>
                        <td><small>{{ $r->pendidikan_terakhir ?? '-' }}</small></td>
                        <td>
                            @if ($r->status === 'Aktif')
                                <span class="badge bg-success rounded-pill badge-xs">Aktif</span>
                            @else
                                <span class="badge bg-secondary rounded-pill badge-xs">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTable(){
    const kw = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tbl tbody tr').forEach(function(tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(kw) ? '' : 'none';
    });
}
</script>