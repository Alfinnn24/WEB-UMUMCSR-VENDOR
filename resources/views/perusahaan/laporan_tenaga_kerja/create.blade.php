<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan Tenaga Kerja</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.laporan-tenaga-kerja.index') }}" class="nav-ajax">Laporan Tenaga Kerja</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-7 mx-auto">
        <form action="{{ route('perusahaan.laporan-tenaga-kerja.store') }}" method="POST" id="formLaporan">
            @csrf

            <div class="card border-top border-0 border-4 border-primary mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-file-doc font-22 text-primary"></i>
                            <h5 class="mb-0">Data Laporan Tenaga Kerja</h5>
                        </div>
                        <hr />

                        <!-- Nomor Surat -->
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Nomor Surat <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="nomor_surat" class="form-control"
                                       placeholder="Contoh: 001/LTK/III/2026" required>
                            </div>
                        </div>

                        <!-- Tanggal Laporan -->
                        <div class="row mb-0">
                            <label class="col-sm-4 col-form-label">Tanggal Laporan <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="tgl_laporan" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Upload -->
            <div class="p-3 rounded mb-4 d-flex align-items-center gap-3" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;">
                <i class="bx bx-info-circle font-24 flex-shrink-0"></i>
                <div>
                    <strong>Upload File Laporan</strong><br>
                    <small>File laporan dapat diunggah setelah data disimpan, melalui tombol <strong>Upload File</strong> di halaman daftar laporan.</small>
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('perusahaan.laporan-tenaga-kerja.index') }}" class="btn btn-outline-secondary px-4 nav-ajax">
                    <i class="bx bx-arrow-back me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bx bx-save me-1"></i> Simpan Data
                </button>
            </div>

        </form>
    </div>
</div>
