<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Kontrak Kerja</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Kontrak Kerja</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('perusahaan.kontrak-kerja.create') }}" class="btn btn-primary px-4 nav-ajax">
            <i class="bx bx-plus me-1"></i> Tambah Kontrak
        </a>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-white">{{ session('success') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('warning'))
<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-dark"><i class="bx bxs-trash"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-dark">{{ session('warning') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="font-35 text-white"><i class="bx bxs-x-circle"></i></div>
        <div class="ms-3"><h6 class="mb-0 text-white">{{ session('error') }}</h6></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
            <div>
                <h5 class="mb-0 fw-bold">Daftar Kontrak Kerja</h5>
                <small class="text-muted">Total {{ $data->total() }} kontrak</small>
            </div>
            <div class="ms-auto d-flex gap-2">
                <input type="text" id="searchInput" class="form-control form-control-sm"
                       placeholder="Cari judul / nomor kontrak..." style="width:230px" onkeyup="filterTable()">
                <select id="filterStatus" class="form-select form-select-sm" style="width:160px" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Hampir Berakhir">Hampir Berakhir</option>
                    <option value="Belum Mulai">Belum Mulai</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelKontrak">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Judul / Nomor Kontrak</th>
                        <th>Deskripsi</th>
                        <th>Periode</th>
                        <th>Jml TK</th>
                        <th>Assigned</th>
                        <th width="130">Status</th>
                        <th width="90" class="text-center">Berkas</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @if ($data->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bx bx-file-blank font-40 d-block mb-2"></i>
                            Belum ada kontrak kerja.
                            <a href="{{ route('perusahaan.kontrak-kerja.create') }}" class="nav-ajax">Tambah sekarang</a>
                        </td>
                    </tr>
                @else
                    @foreach ($data as $i => $row)
                        @php
                            $today = date('Y-m-d');
                            $sisa_hari = (int) ((strtotime($row->tgl_selesai) - strtotime($today)) / 86400);
                            
                            if ($today < $row->tgl_mulai) {
                                $st = ['label' => 'Belum Mulai', 'class' => 'info', 'icon' => 'bx-time'];
                            } elseif ($today > $row->tgl_selesai) {
                                $st = ['label' => 'Selesai', 'class' => 'secondary', 'icon' => 'bx-check'];
                            } elseif ($sisa_hari <= 30) {
                                $st = ['label' => 'Hampir Berakhir', 'class' => 'warning', 'icon' => 'bx-error'];
                            } else {
                                $st = ['label' => 'Aktif', 'class' => 'success', 'icon' => 'bx-check-circle'];
                            }
                            $adaBerkas = !empty($row->berkas_kontrak);
                        @endphp
                        <tr data-status="{{ $st['label'] }}">
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold small">{{ $row->judul_kontrak }}</div>
                                <small class="text-muted"><i class="bx bx-hash me-1"></i>{{ $row->nomor_kontrak }}</small>
                            </td>
                            <td>
                                <div class="small text-muted text-wrap" style="max-width:200px" title="{{ $row->deskripsi_pekerjaan ?? 'Tidak ada deskripsi' }}">
                                    {!! !empty($row->deskripsi_pekerjaan) ? nl2br(e(Str::limit($row->deskripsi_pekerjaan, 100))) : '<span class="text-light-50 fst-italic">Tidak ada deskripsi</span>' !!}
                                </div>
                            </td>
                            <td>
                                <small class="d-block"><i class="bx bx-calendar-check me-1 text-success"></i>{{ date('d M Y', strtotime($row->tgl_mulai)) }}</small>
                                <small class="d-block"><i class="bx bx-calendar-x me-1 text-danger"></i>{{ date('d M Y', strtotime($row->tgl_selesai)) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">{{ number_format($row->jumlah_tenaga_kerja) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('perusahaan.kontrak-kerja.karyawan', $row->id) }}"
                                   class="badge bg-{{ $row->jml_assigned > 0 ? 'success' : 'light text-dark border' }} text-decoration-none nav-ajax">
                                    <i class="bx bx-group me-1"></i>{{ $row->jml_assigned }} karyawan
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-{{ $st['class'] }} rounded-pill px-3 py-2">
                                    <i class="bx {{ $st['icon'] }} me-1"></i>{{ $st['label'] }}
                                </span>
                                @if ($st['label'] === 'Aktif' || $st['label'] === 'Hampir Berakhir')
                                    <br><small class="text-muted" style="font-size:.72rem">{{ $sisa_hari }} hari lagi</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($adaBerkas)
                                    <a href="/uploads/kontrak/{{ $row->berkas_kontrak }}"
                                       target="_blank" class="btn btn-sm btn-outline-success" title="Lihat Berkas">
                                        <i class="bx bx-file me-1"></i>Lihat
                                    </a>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            onclick="showUpload({{ $row->id }}, '{{ addslashes($row->judul_kontrak) }}')"
                                            title="Upload Berkas">
                                        <i class="bx bx-upload me-1"></i>Upload
                                    </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('perusahaan.kontrak-kerja.karyawan', $row->id) }}"
                                       class="btn btn-sm btn-outline-info nav-ajax" title="Kelola Karyawan">
                                        <i class="bx bx-group"></i>
                                    </a>
                                    <a href="{{ route('perusahaan.kontrak-kerja.edit', $row->id) }}"
                                       class="btn btn-sm btn-outline-warning nav-ajax" title="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('perusahaan.kontrak-kerja.destroy', $row->id) }}" method="POST" class="d-inline form-delete"
                                          onsubmit="return confirm('Yakin hapus kontrak ini? Data karyawan yang terhubung juga akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
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

<!-- Modal Upload Berkas -->
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h6 class="modal-title fw-bold mb-0">Upload Berkas Kontrak</h6>
                    <small class="text-muted" id="uploadSubtitle">—</small>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="formUploadAction">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" name="berkas_kontrak" class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        <div class="form-text text-muted">Format: PDF, JPG, PNG, DOC &nbsp;&middot;&nbsp; Maks: 10 MB</div>
                    </div>
                    <div class="alert alert-light border mb-0">
                        <small><i class="bx bx-info-circle me-1 text-info"></i>File lama akan digantikan jika sudah ada.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning px-4">
                        <i class="bx bx-upload me-1"></i>Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showUpload(id, judul) {
    document.getElementById('uploadSubtitle').textContent = judul;
    document.getElementById('formUploadAction').action = `{{ url('perusahaan/kontrak-kerja') }}/${id}/upload`;
    new bootstrap.Modal(document.getElementById('modalUpload')).show();
}

function filterTable() {
    const kw  = document.getElementById('searchInput').value.toLowerCase();
    const st  = document.getElementById('filterStatus').value;
    document.querySelectorAll('#tabelKontrak tbody tr[data-status]').forEach(tr => {
        let match = tr.textContent.toLowerCase().includes(kw);
        if (st) match = match && tr.dataset.status === st;
        tr.style.display = match ? '' : 'none';
    });
}
</script>
