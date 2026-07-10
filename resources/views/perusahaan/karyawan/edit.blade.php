<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Data Karyawan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.karyawan.index') }}" class="nav-ajax">Data Karyawan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-10 mx-auto">
        <form action="{{ route('perusahaan.karyawan.update', $rec->id) }}" method="POST" id="formKaryawan">
            @csrf
            @method('PUT')

            <!-- ══════════════════════════════════════════════════
                 SEKSI 1 — Identitas Utama
            ══════════════════════════════════════════════════ -->
            <div class="card border-top border-0 border-4 border-primary mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-id-card font-22 text-primary"></i>
                            <h5 class="mb-0">Identitas Utama</h5>
                            <span class="badge bg-primary ms-auto">SEKSI 1</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" class="form-control"
                                       value="{{ $rec->nama }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nomor Induk Karyawan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="nik" class="form-control"
                                       value="{{ $rec->nik }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nomor KTP <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="nomor_ktp" class="form-control"
                                       value="{{ $rec->nomor_ktp }}"
                                       maxlength="16" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-9">
                                <input type="text" name="npwp" class="form-control"
                                       value="{{ $rec->npwp }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">No HP <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                    <input type="text" name="no_hp" class="form-control"
                                           value="{{ $rec->no_hp }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ $rec->email }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════
                 SEKSI 2 — Data Pribadi
            ══════════════════════════════════════════════════ -->
            <div class="card border-top border-0 border-4 border-warning mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-user-detail font-22 text-warning"></i>
                            <h5 class="mb-0">Data Pribadi</h5>
                            <span class="badge bg-warning text-dark ms-auto">SEKSI 2</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="L" {{ $rec->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $rec->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Agama <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="agama" class="form-select" required>
                                    @foreach (['Islam','Kristen Protestan','Kristen Katolik','Hindu','Budha','Konghucu','Lainnya'] as $ag)
                                        <option {{ $rec->agama === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="tempat_lahir" class="form-control"
                                       value="{{ $rec->tempat_lahir }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" name="tanggal_lahir" class="form-control"
                                       value="{{ $rec->tanggal_lahir }}" required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label class="col-sm-3 col-form-label">Status Perkawinan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="status_perkawinan" class="form-select" required>
                                    @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $kw)
                                        <option {{ $rec->status_perkawinan === $kw ? 'selected' : '' }}>{{ $kw }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════
                 SEKSI 3 — Alamat Asal / Kelahiran
            ══════════════════════════════════════════════════ -->
            <div class="card border-top border-0 border-4 border-success mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-map font-22 text-success"></i>
                            <h5 class="mb-0">Alamat Asal / Kelahiran</h5>
                            <span class="badge bg-success ms-auto">SEKSI 3</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea name="alamat_tinggal" id="alamat_tinggal" class="form-control" rows="2" required>{{ $rec->alamat_tinggal }}</textarea>
                            </div>
                        </div>

                        <!-- Provinsi -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Provinsi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="provinsi_sel" name="provinsi_id"
                                        onchange="onProvinsiChange(this.value)" required>
                                    <option value="" disabled>-- Pilih Provinsi --</option>
                                    @foreach($all_prov as $pv)
                                        <option value="{{ $pv->id }}" {{ $pv->id == $prov_id ? 'selected' : '' }}>
                                            {{ $pv->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="provinsi" id="provinsi_nama" value="{{ $rec->provinsi }}">
                            </div>
                        </div>

                        <!-- Kabupaten -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Kabupaten / Kota <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="kabupaten_sel" name="kabupaten_id"
                                        onchange="onKabupatenChange(this.value)"
                                        {{ $kab_id ? '' : 'disabled' }} required>
                                    <option value="" disabled>-- Pilih Kabupaten / Kota --</option>
                                    @foreach($all_kab as $kb)
                                        <option value="{{ $kb->id }}" {{ $kb->id == $kab_id ? 'selected' : '' }}>
                                            {{ $kb->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="kabupaten" id="kabupaten_nama" value="{{ $rec->kabupaten }}">
                            </div>
                        </div>

                        <!-- Kecamatan -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Kecamatan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="kecamatan_sel" name="kecamatan_id"
                                        onchange="onKecamatanChange(this.value)"
                                        {{ $kec_id ? '' : 'disabled' }} required>
                                    <option value="" disabled>-- Pilih Kecamatan --</option>
                                    @foreach($all_kec as $kc)
                                        <option value="{{ $kc->id }}" {{ $kc->id == $kec_id ? 'selected' : '' }}>
                                            {{ $kc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="kecamatan" id="kecamatan_nama" value="{{ $rec->kecamatan }}">
                            </div>
                        </div>

                        <!-- Desa -->
                        <div class="row mb-0">
                            <label class="col-sm-3 col-form-label">Desa / Kelurahan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="desa_sel" name="desa_id"
                                        onchange="onDesaChange(this.value)"
                                        {{ $desa_id ? '' : 'disabled' }} required>
                                    <option value="" disabled>-- Pilih Desa / Kelurahan --</option>
                                    @foreach($all_desa as $ds)
                                        <option value="{{ $ds->id }}" {{ $ds->id == $desa_id ? 'selected' : '' }}>
                                            {{ $ds->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="desa" id="desa_nama" value="{{ $rec->desa }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════
                 SEKSI 4 — Alamat KTP
            ══════════════════════════════════════════════════ -->
            <div class="card border-top border-0 border-4 border-info mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-file-doc font-22 text-info"></i>
                            <h5 class="mb-0">Alamat Sesuai KTP/Domisili</h5>
                            <span class="badge bg-info ms-auto">SEKSI 4</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Alamat KTP <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="3" required>{{ $rec->alamat_ktp }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="samaAlamat"
                                           onchange="salinAlamat(this)">
                                    <label class="form-check-label" for="samaAlamat">
                                        Sama dengan Alamat Tinggal / Saat Ini
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════
                 SEKSI 5 — Kepegawaian
            ══════════════════════════════════════════════════ -->
            <div class="card border-top border-0 border-4 border-secondary mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-briefcase font-22 text-secondary"></i>
                            <h5 class="mb-0">Data Kepegawaian</h5>
                            <span class="badge bg-secondary ms-auto">SEKSI 5</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Mulai Masuk Kerja <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" name="mulai_masuk_kerja" class="form-control"
                                       value="{{ $rec->mulai_masuk_kerja }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="pendidikan_terakhir" class="form-select" required>
                                    @foreach (['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $p)
                                        <option {{ $rec->pendidikan_terakhir === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">BPJS Kesehatan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white border-danger">
                                        <i class="bx bx-plus-medical"></i>
                                    </span>
                                    <input type="text" name="bpjs_kesehatan" class="form-control"
                                           value="{{ $rec->bpjs_kesehatan }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label class="col-sm-3 col-form-label">BPJS Ketenagakerjaan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-primary">
                                        <i class="bx bx-building"></i>
                                    </span>
                                    <input type="text" name="bpjs_ketenagakerjaan" class="form-control"
                                           value="{{ $rec->bpjs_ketenagakerjaan }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════
                 SEKSI 6 — Jabatan & Struktur
            ══════════════════════════════════════════════════ -->
            <div class="card border-top border-0 border-4 border-danger mb-4">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center gap-2">
                            <i class="bx bxs-institution font-22 text-danger"></i>
                            <h5 class="mb-0">Jabatan & Struktur Organisasi</h5>
                            <span class="badge bg-danger ms-auto">SEKSI 6</span>
                        </div>
                        <hr />

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jabatan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="jabatan" class="form-control"
                                       value="{{ $rec->jabatan }}" required>
                            </div>
                        </div>

                        <!-- Divisi -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Divisi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="div_id" name="div_id"
                                        onchange="onDivisiChange(this.value)" required>
                                    <option value="" disabled>-- Pilih Divisi --</option>
                                    @foreach ($all_div as $d)
                                        <option value="{{ $d->id }}" {{ $d->id == $rec->div_id ? 'selected' : '' }}>
                                            {{ $d->div_desc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Sub Divisi -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Sub Divisi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="subdiv_id" name="subdiv_id"
                                        {{ $rec->div_id ? '' : 'disabled' }} required>
                                    <option value="" disabled>-- Pilih Divisi dahulu --</option>
                                    @foreach ($all_subdiv as $s)
                                        @if ($s->div_id != $rec->div_id) @continue @endif
                                        <option value="{{ $s->id }}" {{ $s->id == $rec->subdiv_id ? 'selected' : '' }}>
                                            {{ $s->subdiv_desc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Unit <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="unit" class="form-select" required>
                                    <option value="UNIT 9"  {{ $rec->unit === 'UNIT 9'  ? 'selected' : '' }}>UNIT 9</option>
                                    <option value="UNIT 12" {{ $rec->unit === 'UNIT 12' ? 'selected' : '' }}>UNIT 12</option>
                                    <option value="UNIT 129" {{ $rec->unit === 'UNIT 129' ? 'selected' : '' }}>UNIT 129</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label class="col-sm-3 col-form-label">Status Karyawan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="status" class="form-select" required>
                                    <option value="Aktif"    {{ $rec->status === 'Aktif'    ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ $rec->status === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Tombol Simpan ───────────────────────────────── -->
            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('perusahaan.karyawan.index') }}" class="btn btn-outline-secondary px-4 nav-ajax">
                    <i class="bx bx-arrow-back me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     JAVASCRIPT LOCAL
     ════════════════════════════════════════════════════════════════ -->
<script>
const allSubdiv = {!! json_encode($all_subdiv) !!};

// ── Divisi berubah → filter sub divisi ────────────────────────
function onDivisiChange(divId) {
    const sel      = document.getElementById('subdiv_id');
    const filtered = allSubdiv.filter(s => s.div_id == divId);

    sel.innerHTML = '<option value="" disabled selected>-- Pilih Sub Divisi --</option>';
    filtered.forEach(s => {
        sel.innerHTML += `<option value="${s.id}">${s.subdiv_desc}</option>`;
    });
    sel.disabled = filtered.length === 0;
}

// ── Wilayah — AJAX ────────────────────────────────────────────
function setLoading(id, teks) {
    const el = document.getElementById(id);
    el.innerHTML = `<option disabled selected>${teks}</option>`;
    el.disabled = true;
}
function resetSelect(id, placeholder) {
    const el = document.getElementById(id);
    el.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
    el.disabled = true;
}

function onProvinsiChange(provinsiId) {
    const sel = document.getElementById('provinsi_sel');
    document.getElementById('provinsi_nama').value = sel.options[sel.selectedIndex].text;
    resetSelect('kabupaten_sel', '-- Pilih Kabupaten / Kota --');
    resetSelect('kecamatan_sel', '-- Pilih Kecamatan --');
    resetSelect('desa_sel',      '-- Pilih Desa / Kelurahan --');
    document.getElementById('kabupaten_nama').value = '';
    document.getElementById('kecamatan_nama').value = '';
    document.getElementById('desa_nama').value      = '';
    if (!provinsiId) return;
    setLoading('kabupaten_sel', '⏳ Memuat kabupaten...');
    fetch(`{{ route('perusahaan.karyawan.kabupaten') }}?provinsi_id=${provinsiId}`)
        .then(r => r.json())
        .then(data => {
            const s = document.getElementById('kabupaten_sel');
            s.innerHTML = '<option value="" disabled selected>-- Pilih Kabupaten / Kota --</option>';
            data.forEach(k => { s.innerHTML += `<option value="${k.id}">${k.name}</option>`; });
            s.disabled = false;
        }).catch(() => setLoading('kabupaten_sel', '⚠ Gagal memuat'));
}

function onKabupatenChange(kabupatenId) {
    const sel = document.getElementById('kabupaten_sel');
    document.getElementById('kabupaten_nama').value = sel.options[sel.selectedIndex].text;
    resetSelect('kecamatan_sel', '-- Pilih Kecamatan --');
    resetSelect('desa_sel',      '-- Pilih Desa / Kelurahan --');
    document.getElementById('kecamatan_nama').value = '';
    document.getElementById('desa_nama').value      = '';
    if (!kabupatenId) return;
    setLoading('kecamatan_sel', '⏳ Memuat kecamatan...');
    fetch(`{{ route('perusahaan.karyawan.kecamatan') }}?kabupaten_id=${kabupatenId}`)
        .then(r => r.json())
        .then(data => {
            const s = document.getElementById('kecamatan_sel');
            s.innerHTML = '<option value="" disabled selected>-- Pilih Kecamatan --</option>';
            data.forEach(k => { s.innerHTML += `<option value="${k.id}">${k.name}</option>`; });
            s.disabled = false;
        }).catch(() => setLoading('kecamatan_sel', '⚠ Gagal memuat'));
}

function onKecamatanChange(kecamatanId) {
    const sel = document.getElementById('kecamatan_sel');
    document.getElementById('kecamatan_nama').value = sel.options[sel.selectedIndex].text;
    resetSelect('desa_sel', '-- Pilih Desa / Kelurahan --');
    document.getElementById('desa_nama').value = '';
    if (!kecamatanId) return;
    setLoading('desa_sel', '⏳ Memuat desa...');
    fetch(`{{ route('perusahaan.karyawan.desa') }}?kecamatan_id=${kecamatanId}`)
        .then(r => r.json())
        .then(data => {
            const s = document.getElementById('desa_sel');
            s.innerHTML = '<option value="" disabled selected>-- Pilih Desa / Kelurahan --</option>';
            data.forEach(d => { s.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
            s.disabled = false;
        }).catch(() => setLoading('desa_sel', '⚠ Gagal memuat'));
}

function onDesaChange(desaId) {
    const sel = document.getElementById('desa_sel');
    document.getElementById('desa_nama').value = sel.options[sel.selectedIndex].text;
}

// ── Salin alamat tinggal → alamat KTP ─────────────────────────
function salinAlamat(cb) {
    const txtKtp = document.getElementById('alamat_ktp');
    if (cb.checked) {
        const tinggal  = document.getElementById('alamat_tinggal').value;
        const provinsi = document.getElementById('provinsi_nama').value;
        const kabupaten= document.getElementById('kabupaten_nama').value;
        const kecamatan= document.getElementById('kecamatan_nama').value;
        const desa     = document.getElementById('desa_nama').value;
        const parts    = [tinggal, desa, kecamatan, kabupaten, provinsi].filter(Boolean);
        txtKtp.value   = parts.join(', ');
        txtKtp.readOnly = true;
    } else {
        txtKtp.readOnly = false;
    }
}
</script>
