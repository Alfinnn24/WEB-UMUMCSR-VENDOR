<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Kontrak Kerja</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="nav-ajax">Kontrak Kerja</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 mx-auto">
        <form action="{{ route('perusahaan.kontrak-kerja.update', $rec->id) }}" method="POST" id="formKontrak">
            @csrf
            @method('PUT')

            <div class="card border-top border-0 border-4 border-warning mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-file-blank font-22 text-warning"></i>
                            <h5 class="mb-0">Edit Data Kontrak Kerja</h5>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Judul Kontrak <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="judul_kontrak" class="form-control"
                                       value="{{ $rec->judul_kontrak }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Nomor Kontrak <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="nomor_kontrak" class="form-control"
                                       value="{{ $rec->nomor_kontrak }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Deskripsi Pekerjaan</label>
                            <div class="col-sm-8">
                                <textarea name="deskripsi_pekerjaan" class="form-control" rows="3"
                                          placeholder="Opsional: Tuliskan ruang lingkup atau deskripsi pekerjaan...">{{ $rec->deskripsi_pekerjaan }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"
                                       value="{{ $rec->tgl_mulai }}"
                                       onchange="hitungDurasi()" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"
                                       value="{{ $rec->tgl_selesai }}"
                                       onchange="hitungDurasi()" required>
                                <div id="infoDurasi" class="mt-1"></div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label class="col-sm-4 col-form-label">Jumlah Tenaga Kerja <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" name="jumlah_tenaga_kerja" class="form-control"
                                           value="{{ $rec->jumlah_tenaga_kerja }}" min="0" required>
                                    <span class="input-group-text">Orang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info berkas -->
            @if ($rec->berkas_kontrak)
            <div class="p-3 rounded mb-4 d-flex align-items-center gap-3" style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #166534;">
                <i class="bx bx-file font-24 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong>Berkas Tersedia</strong><br>
                    <small>{{ $rec->berkas_kontrak }}</small>
                </div>
                <a href="/uploads/kontrak/{{ $rec->berkas_kontrak }}"
                   target="_blank" class="btn btn-sm btn-success text-white">
                    <i class="bx bx-show me-1"></i>Lihat
                </a>
            </div>
            @else
            <div class="p-3 rounded mb-4 d-flex align-items-center gap-3" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;">
                <i class="bx bx-info-circle font-24 flex-shrink-0"></i>
                <div>
                    <strong>Berkas Belum Diupload</strong><br>
                    <small>Upload berkas melalui tombol <strong>Upload</strong> di halaman daftar.</small>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('perusahaan.kontrak-kerja.index') }}" class="btn btn-outline-secondary px-4 nav-ajax">
                    <i class="bx bx-arrow-back me-1"></i> Batal
                </a>
                <a href="{{ route('perusahaan.kontrak-kerja.karyawan', $rec->id) }}" class="btn btn-info px-4 nav-ajax">
                    <i class="bx bx-group me-1"></i> Kelola Karyawan
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan
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
hitungDurasi();
</script>
