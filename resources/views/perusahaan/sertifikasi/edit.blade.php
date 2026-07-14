<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Sertifikasi</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.sertifikasi.index') }}" class="nav-ajax">Sertifikasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 mx-auto">
        <form action="{{ route('perusahaan.sertifikasi.update', $rec->id) }}" method="POST" id="formSertifikasi">
            @csrf
            @method('PUT')

            <!-- SEKSI 1 — Karyawan -->
            <div class="card border-top border-0 border-4 border-primary mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-user-badge font-22 text-primary"></i>
                            <h5 class="mb-0">Pilih Karyawan</h5>
                            <span class="badge bg-primary ms-auto">SEKSI 1</span>
                        </div>
                        <hr />

                        <div class="row mb-0">
                            <label class="col-sm-3 col-form-label">Nama Karyawan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="karyawan_id" id="karyawan_id"
                                        onchange="onKaryawanChange(this.value)" required>
                                    <option value="" disabled>-- Pilih Karyawan --</option>
                                    @foreach ($all_karyawan as $k)
                                        <option value="{{ $k->id }}"
                                                data-nik="{{ $k->nik }}"
                                                data-jabatan="{{ $k->jabatan }}"
                                                {{ $k->id == $rec->karyawan_id ? 'selected' : '' }}>
                                            {{ $k->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="infoKaryawan" class="mt-2 {{ $rec->karyawan_id ? '' : 'd-none' }}">
                                    <div class="d-flex gap-3 p-2 bg-light rounded border">
                                        <small><span class="text-muted">NIK:</span> <strong id="infoNik">—</strong></small>
                                        <small><span class="text-muted">Jabatan:</span> <strong id="infoJabatan">—</strong></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEKSI 2 — Data Sertifikasi -->
            <div class="card border-top border-0 border-4 border-warning mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-certification font-22 text-warning"></i>
                            <h5 class="mb-0">Data Sertifikasi</h5>
                            <span class="badge bg-warning text-dark ms-auto">SEKSI 2</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Nama Sertifikasi <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="nama_sertifikasi" class="form-control"
                                       value="{{ $rec->nama_sertifikasi }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Nomor Sertifikat</label>
                            <div class="col-sm-8">
                                <input type="text" name="nomor_sertifikat" class="form-control"
                                       value="{{ $rec->nomor_sertifikat }}"
                                       placeholder="Contoh: SKT/2024/001 (opsional)">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Lembaga Sertifikasi <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="lembaga_sertifikasi" class="form-control"
                                       value="{{ $rec->lembaga_sertifikasi }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Kota Pelaksanaan <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-map-pin"></i></span>
                                    <input type="text" name="kota_pelaksanaan" class="form-control"
                                           value="{{ $rec->kota_pelaksanaan }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Tanggal Sertifikasi <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="tanggal_sertifikasi" id="tanggal_sertifikasi"
                                       class="form-control"
                                       value="{{ $rec->tanggal_sertifikasi }}"
                                       onchange="hitungExpired()" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Masa Berlaku <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" name="masa_berlaku" id="masa_berlaku"
                                           class="form-control" min="1"
                                           value="{{ $rec->masa_berlaku }}"
                                           onchange="hitungExpired()" required>
                                    <span class="input-group-text">Bulan</span>
                                </div>
                                <div class="form-text text-muted">
                                    <i class="bx bx-info-circle"></i> Ubah nilai ini untuk menghitung ulang tanggal expired
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label class="col-sm-4 col-form-label">Tanggal Expired</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-warning text-dark border-warning">
                                        <i class="bx bx-calendar-x"></i>
                                    </span>
                                    <input type="date" name="tanggal_expired" id="tanggal_expired"
                                           class="form-control bg-light" readonly
                                           value="{{ $rec->tanggal_expired }}">
                                </div>
                                <div id="sisaHari" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info file -->
            @if ($rec->file_sertifikat)
            <div class="p-3 rounded mb-4 d-flex align-items-center gap-3" style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #166534;">
                <i class="bx bx-file font-24 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong>File Sertifikat Tersedia</strong><br>
                    <small>{{ $rec->file_sertifikat }}</small>
                </div>
                <a href="/uploads/sertifikasi/{{ $rec->file_sertifikat }}"
                   target="_blank" class="btn btn-sm btn-success text-white">
                    <i class="bx bx-show me-1"></i>Lihat
                </a>
            </div>
            @else
            <div class="p-3 rounded mb-4 d-flex align-items-center gap-3" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;">
                <i class="bx bx-info-circle font-24 flex-shrink-0"></i>
                <div>
                    <strong>File Belum Diupload</strong><br>
                    <small>Upload file dapat dilakukan melalui tombol <strong>Upload File</strong> di halaman daftar.</small>
                </div>
            </div>
            @endif

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('perusahaan.sertifikasi.index') }}" class="btn btn-outline-secondary px-4 nav-ajax">
                    <i class="bx bx-arrow-back me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function onKaryawanChange(val) {
    const opt = document.querySelector(`#karyawan_id option[value="${val}"]`);
    if (!opt) return;
    document.getElementById('infoNik').textContent     = opt.dataset.nik     || '-';
    document.getElementById('infoJabatan').textContent = opt.dataset.jabatan || '-';
    document.getElementById('infoKaryawan').classList.remove('d-none');
}

function hitungExpired() {
    const tgl   = document.getElementById('tanggal_sertifikasi').value;
    const bulan = parseInt(document.getElementById('masa_berlaku').value) || 0;

    if (!tgl || !bulan) {
        document.getElementById('sisaHari').innerHTML = '';
        return;
    }

    const d = new Date(tgl);
    d.setMonth(d.getMonth() + bulan);
    const tglExpired = d.toISOString().split('T')[0];
    document.getElementById('tanggal_expired').value = tglExpired;

    const today   = new Date(); today.setHours(0, 0, 0, 0);
    const expDate = new Date(tglExpired);
    const selisih = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
    const sisaEl  = document.getElementById('sisaHari');

    if (selisih > 30) {
        sisaEl.innerHTML = `<small class="text-success"><i class="bx bx-check-circle me-1"></i>Berlaku <strong>${selisih}</strong> hari lagi</small>`;
    } else if (selisih > 0) {
        sisaEl.innerHTML = `<small class="text-warning"><i class="bx bx-error me-1"></i>Akan expired dalam <strong>${selisih}</strong> hari!</small>`;
    } else {
        sisaEl.innerHTML = `<small class="text-danger"><i class="bx bx-x-circle me-1"></i>Sertifikat sudah <strong>expired</strong>!</small>`;
    }
}

// Init
(function init() {
    const sel = document.getElementById('karyawan_id');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('infoNik').textContent     = opt.dataset.nik     || '-';
        document.getElementById('infoJabatan').textContent = opt.dataset.jabatan || '-';
    }
    hitungExpired();
})();
</script>
