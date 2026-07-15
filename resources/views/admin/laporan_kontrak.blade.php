<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Kontrak</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 fade-in-up delay-1">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Laporan Kontrak Kerja</h6>
        <form id="filterLapKontrakForm" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="hidden" name="page" value="laporan_kontrak">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select form-select-sm">
                        <option value="">— Semua Perusahaan —</option>
                        @foreach ($all_perusahaan as $p)
                        <option value="{{ $p->id }}" {{ $filter_perusahaan == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Status Kontrak</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">— Semua Status —</option>
                        <option value="aktif" {{ $filter_status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="hampir" {{ $filter_status === 'hampir' ? 'selected' : '' }}>Hampir Berakhir</option>
                        <option value="selesai" {{ $filter_status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bx bx-search me-1"></i>Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill btn-reset-lap-kontrak"><i class="bx bx-reset me-1"></i>Reset</button>
                    <a href="/admin/laporan/export_kontrak.php" target="_blank"
                       class="btn btn-sm btn-success flex-fill"
                       onclick="event.preventDefault();var f=$('#filterLapKontrakForm');window.open('/admin/laporan/export_kontrak.php?'+f.serialize(),'_blank');">
                        <i class="bx bx-download me-1"></i> Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Table Card -->
<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0">Data Laporan Kontrak Kerja</h6>
                <small class="text-muted">Menampilkan <strong>{{ $data->total() }}</strong> data kontrak</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle mb-0" id="tblLapKontrak">
                <thead class="table-dark">
                    <tr>
                        <th width="35">#</th>
                        <th>Perusahaan</th>
                        <th>Judul / No. Kontrak</th>
                        <th>Deskripsi</th>
                        <th>Periode</th>
                        <th class="text-center">Jml TK (Target)</th>
                        <th class="text-center">Assigned</th>
                        <th>Status</th>
                        <th>Berkas</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $i => $r)
                    @php
                        $sisa = (int)$r->sisa_hari;
                        if ($today > $r->tgl_selesai) {
                            $badge_c = 'secondary';
                            $badge_l = 'Selesai';
                        } elseif ($sisa <= 30) {
                            $badge_c = 'warning';
                            $badge_l = 'Hampir Berakhir';
                        } else {
                            $badge_c = 'success';
                            $badge_l = 'Aktif';
                        }
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><small class="fw-semibold">{{ $r->nama_perusahaan ?? '-' }}</small></td>
                        <td>
                            <div class="small fw-bold">{{ $r->judul_kontrak }}</div>
                            <small class="text-muted">{{ $r->nomor_kontrak }}</small>
                        </td>
                        <td>
                            <div class="small text-muted text-wrap" style="max-width: 250px;">
                                {{ $r->deskripsi_pekerjaan ? Str::limit($r->deskripsi_pekerjaan, 100) : '-' }}
                            </div>
                        </td>
                        <td>
                            <small class="d-block"><i class="bx bx-calendar text-success me-1"></i>{{ $r->tgl_mulai ? date('d/m/Y', strtotime($r->tgl_mulai)) : '-' }}</small>
                            <small class="d-block"><i class="bx bx-calendar-x text-danger me-1"></i>{{ $r->tgl_selesai ? date('d/m/Y', strtotime($r->tgl_selesai)) : '-' }}</small>
                        </td>
                        <td class="text-center"><span class="badge bg-primary rounded-pill px-2">{{ number_format($r->jumlah_tenaga_kerja) }}</span></td>
                        <td class="text-center">
                            @if ($r->jml_assigned > 0)
                                <a href="/admin/laporan/export_karyawan_kontrak.php?kontrak_id={{ $r->id }}" target="_blank" class="btn btn-sm btn-success py-0 px-2 d-inline-flex align-items-center gap-1" style="font-size: .8rem;">
                                    <i class="bx bx-download"></i> {{ $r->jml_assigned }}
                                </a>
                            @else
                                <span class="badge bg-light text-dark border">0</span>
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $badge_c }} badge-xs rounded-pill">{{ $badge_l }}</span></td>
                        <td>
                            @if ($r->status_berkas === 'Ada')
                                <a href="/uploads/kontrak/{{ $r->berkas_kontrak }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 d-inline-flex align-items-center gap-1" style="font-size: .75rem;">
                                    <i class="bx bx-download"></i> Unduh
                                </a>
                            @else
                                <span class="badge bg-light text-secondary border badge-xs">Belum Upload</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if ($data->hasPages())
        <div class="mt-3 pt-2 border-top">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>
