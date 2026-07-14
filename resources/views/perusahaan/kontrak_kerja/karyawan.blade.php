<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Kontrak Kerja</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="nav-ajax">Kontrak Kerja</a></li>
                <li class="breadcrumb-item active" aria-current="page">Karyawan Sesuai Kontrak</li>
            </ol>
        </nav>
    </div>
</div>

@php
    $today = date('Y-m-d');
    $sisa = (int) ((strtotime($kontrak->tgl_selesai) - strtotime($today)) / 86400);
    if ($today < $kontrak->tgl_mulai) {
        $st = ['label' => 'Belum Mulai', 'class' => 'info'];
    } elseif ($today > $kontrak->tgl_selesai) {
        $st = ['label' => 'Selesai', 'class' => 'secondary'];
    } elseif ($sisa <= 30) {
        $st = ['label' => 'Hampir Berakhir', 'class' => 'warning'];
    } else {
        $st = ['label' => 'Aktif', 'class' => 'success'];
    }
@endphp

<!-- Info Kontrak -->
<div class="card border-top border-0 border-4 border-primary mb-4">
    <div class="card-body">
        <div class="d-flex align-items-start gap-3 flex-wrap">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="mb-0 fw-bold">{{ $kontrak->judul_kontrak }}</h5>
                    <span class="badge bg-{{ $st['class'] }}">{{ $st['label'] }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3 mt-2">
                    <small class="text-muted"><i class="bx bx-hash me-1"></i><strong>No:</strong> {{ $kontrak->nomor_kontrak }}</small>
                    <small class="text-muted"><i class="bx bx-calendar-check me-1 text-success"></i>{{ date('d M Y', strtotime($kontrak->tgl_mulai)) }}</small>
                    <small class="text-muted"><i class="bx bx-calendar-x me-1 text-danger"></i>{{ date('d M Y', strtotime($kontrak->tgl_selesai)) }}</small>
                    <small class="text-muted"><i class="bx bx-group me-1"></i>Target: <strong>{{ number_format($kontrak->jumlah_tenaga_kerja) }}</strong> orang</small>
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold fs-3 text-primary" id="assignedCount">{{ $jml_assigned }}</div>
                <small class="text-muted">Karyawan di-assign</small>
                @php
                    $pct = $kontrak->jumlah_tenaga_kerja > 0
                        ? min(100, round($jml_assigned / $kontrak->jumlah_tenaga_kerja * 100)) : 0;
                @endphp
                <div class="progress mt-1" style="height:6px;width:100px">
                    <div class="progress-bar bg-{{ $pct >= 100 ? 'success' : 'primary' }}" id="progressBar"
                         style="width:{{ $pct }}%"></div>
                </div>
                <small class="text-muted" style="font-size:.7rem" id="progressPercentage">{{ $pct }}% dari target</small>
            </div>
        </div>
    </div>
</div>

<!-- Form Centang Karyawan -->
<form method="POST" action="{{ route('perusahaan.kontrak-kerja.karyawan.store', $kontrak->id) }}" id="formKaryawan" class="form-ajax-submit">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
                <div>
                    <h5 class="mb-0 fw-bold">Pilih Karyawan</h5>
                    <small class="text-muted">Total {{ count($all_karyawan) }} karyawan aktif</small>
                </div>
                <div class="ms-auto d-flex gap-2 flex-wrap align-items-center">
                    <input type="text" id="searchKaryawan" class="form-control form-control-sm"
                           placeholder="Cari nama / NIK / jabatan..." style="width:230px"
                           onkeyup="filterKaryawan()">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="toggleAll" onchange="toggleSemua(this)">
                        <label class="form-check-label small" for="toggleAll">Pilih Semua</label>
                    </div>
                </div>
            </div>

            @if ($all_karyawan->isEmpty())
            <div class="alert alert-warning mb-0">
                <i class="bx bx-info-circle me-2"></i>
                Belum ada karyawan aktif. <a href="{{ route('perusahaan.karyawan.create') }}" class="nav-ajax">Tambah karyawan terlebih dahulu.</a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelKaryawan">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">✓</th>
                            <th>Nama Karyawan</th>
                            <th>NIK</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($all_karyawan as $k)
                    <tr class="karyawan-row {{ $k->is_assigned ? 'table-primary' : '' }}">
                        <td class="text-center">
                            <div class="form-check d-flex justify-content-center mb-0">
                                <input class="form-check-input karyawan-cb" type="checkbox"
                                       name="karyawan_ids[]"
                                       value="{{ $k->id }}"
                                       id="k_{{ $k->id }}"
                                       {{ $k->is_assigned ? 'checked' : '' }}
                                       onchange="updateRow(this)">
                            </div>
                        </td>
                        <td>
                            <label for="k_{{ $k->id }}" class="mb-0 fw-semibold small cursor-pointer" style="cursor:pointer">
                                {{ $k->nama }}
                            </label>
                        </td>
                        <td><small class="text-muted">{{ $k->nik }}</small></td>
                        <td><small>{{ $k->jabatan }}</small></td>
                        <td><small class="text-muted">{{ $k->unit ?? '-' }}</small></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    @if (!$all_karyawan->isEmpty())
    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
        <div>
            <span class="badge bg-primary px-3 py-2" id="counterBadge">
                <i class="bx bx-group me-1"></i>
                <span id="counterText">{{ $jml_assigned }}</span> karyawan dipilih
            </span>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="btn btn-outline-secondary px-4 nav-ajax">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary px-5">
                <i class="bx bx-save me-1"></i> Simpan Karyawan
            </button>
        </div>
    </div>
    @endif
</form>

<script>
function updateRow(cb) {
    const tr = cb.closest('tr');
    tr.classList.toggle('table-primary', cb.checked);
    updateCounter();
}
function updateCounter() {
    const n = document.querySelectorAll('.karyawan-cb:checked').length;
    document.getElementById('counterText').textContent = n;
    
    // Live update status panel
    const target = {{ $kontrak->jumlah_tenaga_kerja }};
    document.getElementById('assignedCount').textContent = n;
    const pct = target > 0 ? Math.min(100, Math.round(n / target * 100)) : 0;
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = pct + '%';
        if (pct >= 100) {
            progressBar.className = 'progress-bar bg-success';
        } else {
            progressBar.className = 'progress-bar bg-primary';
        }
    }
    const pctText = document.getElementById('progressPercentage');
    if (pctText) {
        pctText.textContent = pct + '% dari target';
    }
}
function toggleSemua(el) {
    document.querySelectorAll('.karyawan-cb').forEach(cb => {
        const tr = cb.closest('tr');
        if (tr.style.display !== 'none') {
            cb.checked = el.checked;
            tr.classList.toggle('table-primary', el.checked);
        }
    });
    updateCounter();
}
function filterKaryawan() {
    const kw = document.getElementById('searchKaryawan').value.toLowerCase();
    document.querySelectorAll('#tabelKaryawan tbody tr.karyawan-row').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(kw) ? '' : 'none';
    });
}
// Init counter
updateCounter();
</script>
