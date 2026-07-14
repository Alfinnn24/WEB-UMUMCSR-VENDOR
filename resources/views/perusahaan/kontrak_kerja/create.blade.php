<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Kontrak Kerja</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="nav-ajax">Kontrak Kerja</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 mx-auto">
        <form action="{{ route('perusahaan.kontrak-kerja.store') }}" method="POST" id="formKontrak">
            @csrf

            <div class="card border-top border-0 border-4 border-primary mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-file-blank font-22 text-primary"></i>
                            <h5 class="mb-0">Data Kontrak Kerja</h5>
                        </div>
                        <hr />

                        <!-- Judul Kontrak -->
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Judul Kontrak <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="judul_kontrak" class="form-control"
                                       placeholder="Contoh: Kontrak Pengadaan Tenaga Kerja 2026" required>
                            </div>
                        </div>

                        <!-- Nomor Kontrak -->
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Nomor Kontrak <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="nomor_kontrak" class="form-control"
                                       placeholder="Contoh: PKS/2026/001" required>
                            </div>
                        </div>

                        <!-- Deskripsi Pekerjaan -->
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Deskripsi Pekerjaan</label>
                            <div class="col-sm-8">
                                <textarea name="deskripsi_pekerjaan" class="form-control" rows="3"
                                          placeholder="Opsional: Tuliskan ruang lingkup atau deskripsi pekerjaan..."></textarea>
                            </div>
                        </div>

                        <!-- Periode -->
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="tgl_mulai" id="tgl_mulai"
                                       class="form-control" onchange="hitungDurasi()" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="tgl_selesai" id="tgl_selesai"
                                       class="form-control" onchange="hitungDurasi()" required>
                                <div id="infoDurasi" class="mt-1"></div>
                            </div>
                        </div>

                        <!-- Jumlah TK -->
                        <div class="row mb-0">
                            <label class="col-sm-4 col-form-label">Jumlah Tenaga Kerja <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" name="jumlah_tenaga_kerja" class="form-control"
                                           placeholder="0" min="0" required>
                                    <span class="input-group-text">Orang</span>
                                </div>
                                <div class="form-text text-muted">
                                    <i class="bx bx-info-circle"></i> Karyawan yang terhubung dapat ditetapkan setelah menyimpan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded mb-4 d-flex align-items-center gap-3" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;">
                <i class="bx bx-info-circle font-24 flex-shrink-0"></i>
                <div>
                    <strong>Upload Berkas Kontrak</strong><br>
                    <small>Berkas dapat diunggah setelah data disimpan melalui tombol <strong>Upload</strong> di halaman daftar.</small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="btn btn-outline-secondary px-4 nav-ajax">
                    <i class="bx bx-arrow-back me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bx bx-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function hitungDurasi() {
    const mulai   = document.getElementById('tgl_mulai').value;
    const selesai = document.getElementById('tgl_selesai').value;
    const el      = document.getElementById('infoDurasi');
    if (!mulai || !selesai) { el.innerHTML = ''; return; }
    const hari = Math.ceil((new Date(selesai) - new Date(mulai)) / 86400000);
    if (hari <= 0) {
        el.innerHTML = '<small class="text-danger"><i class="bx bx-x-circle me-1"></i>Tanggal selesai harus setelah tanggal mulai.</small>';
    } else {
        const bulan = Math.round(hari / 30);
        el.innerHTML = `<small class="text-success"><i class="bx bx-check-circle me-1"></i>Durasi <strong>${hari}</strong> hari (~${bulan} bulan)</small>`;
    }
}
</script>
