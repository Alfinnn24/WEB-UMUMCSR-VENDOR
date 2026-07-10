<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Sertifikasi</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Laporan Sertifikasi</h6>
        <form id="filterLapSertifikasiForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="laporan_sertifikasi">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach ($all_perusahaan as $p)
                        <option value="{{ $p->id }}" {{ $filter_perusahaan == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Tahun Sertifikasi</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">— Semua Tahun —</option>
                        @foreach ($all_tahun as $t)
                        <option value="{{ $t }}" {{ $filter_tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Status Masa Berlaku</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua Status —</option>
                        <option value="aktif" {{ $filter_status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="hampir" {{ $filter_status === 'hampir' ? 'selected' : '' }}>Hampir Expired (≤ 30 hari)</option>
                        <option value="expired" {{ $filter_status === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 btn-reset-lap-sertifikasi"><i class="bx bx-reset me-1"></i>Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Table Card -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0">Data Laporan Sertifikasi</h6>
                <small class="text-muted">Menampilkan <strong>{{ count($data) }}</strong> data sertifikasi</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle mb-0 table-report" id="tblLapSertifikasi" data-export-title="Laporan Sertifikasi Karyawan">
                <thead class="table-light">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Karyawan</th>
                        <th>Nama Sertifikasi</th>
                        <th>Nomor Sertifikat</th>
                        <th>Lembaga Sertifikasi</th>
                        <th>Tanggal Sertifikasi</th>
                        <th>Masa Berlaku</th>
                        <th>Tanggal Expired</th>
                        <th>Sisa Hari</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    @php
                        $sisa = (int)$r->sisa_hari;
                        if ($sisa < 0) {
                            $badge_c = 'danger';
                            $badge_l = 'Expired';
                        } elseif ($sisa <= 30) {
                            $badge_c = 'warning';
                            $badge_l = 'Hampir Expired';
                        } else {
                            $badge_c = 'success';
                            $badge_l = 'Aktif';
                        }
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td>
                            <div class="small fw-bold">{{ $r->nama_karyawan ?? '-' }}</div>
                            <small class="text-muted">{{ $r->nik }}</small>
                        </td>
                        <td><small class="fw-semibold">{{ $r->nama_sertifikasi }}</small></td>
                        <td><small>{{ $r->nomor_sertifikat ?? '-' }}</small></td>
                        <td><small>{{ $r->lembaga_sertifikasi }}</small></td>
                        <td><small>{{ $r->tanggal_sertifikasi ? date('d/m/Y', strtotime($r->tanggal_sertifikasi)) : '-' }}</small></td>
                        <td><small>{{ $r->masa_berlaku }} Bulan</small></td>
                        <td><small class="text-{{ $badge_c }} fw-semibold">{{ $r->tanggal_expired ? date('d/m/Y', strtotime($r->tanggal_expired)) : '-' }}</small></td>
                        <td>
                            @if ($sisa < 0)
                                <span class="text-danger small fw-bold">Expired</span>
                            @else
                                <span class="small">{{ number_format($sisa) }} Hari</span>
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $badge_c }} rounded-pill px-2" style="font-size: .7rem;">{{ $badge_l }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
