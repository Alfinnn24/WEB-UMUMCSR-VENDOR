<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Data Karyawan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.karyawan.index') }}" class="nav-ajax">Data Karyawan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Review Import</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ── Info bar ─────────────────────────────────────────────────── -->
<div class="card border-top border-0 border-4 border-warning mb-4">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div>
                <i class="bx bxs-spreadsheet font-40 text-warning"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-1 fw-bold">Review Data Import</h5>
                <p class="mb-0 text-muted">
                    <span class="badge bg-warning text-dark fs-6">{{ count($import_data) }} karyawan</span>
                    akan diimport. Lengkapi <strong>Alamat Tinggal</strong>, <strong>Provinsi/Kab/Kec/Desa</strong>, 
                    <strong>Alamat KTP</strong>, <strong>Divisi</strong> dan <strong>Sub Divisi</strong> untuk setiap karyawan.
                </p>
            </div>
            <div>
                <a href="{{ route('perusahaan.karyawan.index') }}" class="btn btn-outline-secondary nav-ajax"
                   onclick="return confirm('Yakin batalkan import? Data yang sudah diupload akan hilang.')">
                    <i class="bx bx-x me-1"></i> Batalkan
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('perusahaan.karyawan.import-store') }}" method="POST" id="formImport">
    @csrf

    @foreach ($import_data as $idx => $row)
    <!-- ═══════════════ KARYAWAN #{{ $idx + 1 }} ═══════════════ -->
    <div class="card border-start border-0 border-4 border-primary mb-3 import-card" id="card-{{ $idx }}">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white flex-shrink-0"
                     style="width:36px;height:36px;font-weight:700;font-size:.85rem">
                    {{ $idx + 1 }}
                </div>
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold">{{ $row['nama'] }}</h6>
                    <small class="text-muted">NIK: {{ $row['nik'] }} | KTP: {{ $row['nomor_ktp'] }}</small>
                </div>
                <div class="ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" 
                            data-bs-target="#detail-{{ $idx }}" aria-expanded="false">
                        <i class="bx bx-chevron-down me-1"></i> Detail & Lengkapi
                    </button>
                </div>
            </div>

            <!-- Ringkasan quick-view -->
            <div class="row g-2 mb-0 small">
                <div class="col-auto"><span class="badge bg-light text-dark"><i class="bx bx-phone me-1"></i>{{ $row['no_hp'] }}</span></div>
                <div class="col-auto"><span class="badge bg-light text-dark">{{ $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                <div class="col-auto"><span class="badge bg-light text-dark">{{ $row['agama'] }}</span></div>
                <div class="col-auto"><span class="badge bg-light text-dark">{{ $row['jabatan'] }}</span></div>
                <div class="col-auto"><span class="badge bg-{{ $row['unit'] === 'UNIT 9' ? 'primary' : 'info' }} rounded-pill">{{ $row['unit'] }}</span></div>
                <div class="col-auto status-badge-{{ $idx }}">
                    <span class="badge bg-danger rounded-pill"><i class="bx bx-x-circle me-1"></i>Belum Lengkap</span>
                </div>
            </div>

            <!-- Hidden fields: data dari Excel -->
            <input type="hidden" name="rows[{{ $idx }}][nama]" value="{{ $row['nama'] }}">
            <input type="hidden" name="rows[{{ $idx }}][nik]" value="{{ $row['nik'] }}">
            <input type="hidden" name="rows[{{ $idx }}][nomor_ktp]" value="{{ $row['nomor_ktp'] }}">
            <input type="hidden" name="rows[{{ $idx }}][npwp]" value="{{ $row['npwp'] ?? '' }}">
            <input type="hidden" name="rows[{{ $idx }}][no_hp]" value="{{ $row['no_hp'] }}">
            <input type="hidden" name="rows[{{ $idx }}][email]" value="{{ $row['email'] ?? '' }}">
            <input type="hidden" name="rows[{{ $idx }}][jenis_kelamin]" value="{{ $row['jenis_kelamin'] }}">
            <input type="hidden" name="rows[{{ $idx }}][agama]" value="{{ $row['agama'] }}">
            <input type="hidden" name="rows[{{ $idx }}][tempat_lahir]" value="{{ $row['tempat_lahir'] }}">
            <input type="hidden" name="rows[{{ $idx }}][tanggal_lahir]" value="{{ $row['tanggal_lahir'] }}">
            <input type="hidden" name="rows[{{ $idx }}][status_perkawinan]" value="{{ $row['status_perkawinan'] }}">
            <input type="hidden" name="rows[{{ $idx }}][mulai_masuk_kerja]" value="{{ $row['mulai_masuk_kerja'] }}">
            <input type="hidden" name="rows[{{ $idx }}][pendidikan_terakhir]" value="{{ $row['pendidikan_terakhir'] }}">
            <input type="hidden" name="rows[{{ $idx }}][bpjs_kesehatan]" value="{{ $row['bpjs_kesehatan'] ?? '' }}">
            <input type="hidden" name="rows[{{ $idx }}][bpjs_ketenagakerjaan]" value="{{ $row['bpjs_ketenagakerjaan'] ?? '' }}">
            <input type="hidden" name="rows[{{ $idx }}][jabatan]" value="{{ $row['jabatan'] }}">
            <input type="hidden" name="rows[{{ $idx }}][unit]" value="{{ $row['unit'] }}">

            <!-- Collapse: Detail dari Excel + Input Manual -->
            <div class="collapse mt-3" id="detail-{{ $idx }}">
                <hr class="my-2">

                <!-- Info dari Excel (read-only) -->
                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <span class="badge bg-secondary mb-2"><i class="bx bx-file me-1"></i>Data dari Excel</span>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-0">Email</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['email'] ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-0">Tempat, Tgl Lahir</label>
                        <input type="text" class="form-control form-control-sm bg-light" 
                               value="{{ $row['tempat_lahir'] }}, {{ $row['tanggal_lahir'] }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-0">Status Perkawinan</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['status_perkawinan'] }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">Mulai Masuk Kerja</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['mulai_masuk_kerja'] }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">Pendidikan</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['pendidikan_terakhir'] }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">BPJS Kesehatan</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['bpjs_kesehatan'] ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">BPJS Ketenagakerjaan</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['bpjs_ketenagakerjaan'] ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">NPWP</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $row['npwp'] ?? '-' }}" readonly>
                    </div>
                </div>

                <!-- ── INPUT MANUAL: Alamat & Divisi ────────────────── -->
                <div class="border rounded p-3 bg-light bg-opacity-50">
                    <span class="badge bg-warning text-dark mb-2"><i class="bx bx-edit me-1"></i>Lengkapi Manual</span>

                    <!-- Alamat Tinggal -->
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-0">Alamat Tinggal <span class="text-danger">*</span></label>
                            <textarea name="rows[{{ $idx }}][alamat_tinggal]" class="form-control form-control-sm" rows="2"
                                      placeholder="Jalan, RT/RW, No. rumah..." required
                                      data-row="{{ $idx }}" data-field="alamat_tinggal" onchange="checkComplete({{ $idx }})"></textarea>
                        </div>
                    </div>

                    <!-- Provinsi, Kabupaten, Kecamatan, Desa -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-0">Provinsi <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm prov-sel" data-row="{{ $idx }}"
                                    onchange="onProvChange(this, {{ $idx }})" required>
                                <option value="" disabled selected>-- Provinsi --</option>
                                @foreach ($all_prov as $pv)
                                    <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="rows[{{ $idx }}][provinsi]" id="prov_nama_{{ $idx }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-0">Kabupaten <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="kab_sel_{{ $idx }}"
                                    onchange="onKabChange(this, {{ $idx }})" disabled required>
                                <option value="" disabled selected>-- Kabupaten --</option>
                            </select>
                            <input type="hidden" name="rows[{{ $idx }}][kabupaten]" id="kab_nama_{{ $idx }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-0">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="kec_sel_{{ $idx }}"
                                    onchange="onKecChange(this, {{ $idx }})" disabled required>
                                <option value="" disabled selected>-- Kecamatan --</option>
                            </select>
                            <input type="hidden" name="rows[{{ $idx }}][kecamatan]" id="kec_nama_{{ $idx }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-0">Desa <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="desa_sel_{{ $idx }}"
                                    onchange="onDesChange(this, {{ $idx }})" disabled required>
                                <option value="" disabled selected>-- Desa --</option>
                            </select>
                            <input type="hidden" name="rows[{{ $idx }}][desa]" id="desa_nama_{{ $idx }}">
                        </div>
                    </div>

                    <!-- Alamat KTP -->
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-0">Alamat KTP <span class="text-danger">*</span></label>
                            <textarea name="rows[{{ $idx }}][alamat_ktp]" class="form-control form-control-sm" rows="2"
                                      placeholder="Alamat lengkap sesuai KTP..." required
                                      data-row="{{ $idx }}" data-field="alamat_ktp" onchange="checkComplete({{ $idx }})"></textarea>
                        </div>
                    </div>

                    <!-- Checkbox salin alamat -->
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="samaAlamat_{{ $idx }}"
                               onchange="salinAlamatImport(this, {{ $idx }})">
                        <label class="form-check-label small" for="samaAlamat_{{ $idx }}">
                            Alamat KTP sama dengan Alamat Tinggal
                        </label>
                    </div>

                    <!-- Divisi & Sub Divisi -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-0">Divisi <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="rows[{{ $idx }}][div_id]"
                                    onchange="onDivChange(this, {{ $idx }})" required>
                                <option value="" disabled selected>-- Pilih Divisi --</option>
                                @foreach ($all_div as $d)
                                    <option value="{{ $d->id }}">{{ $d->div_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-0">Sub Divisi <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="subdiv_sel_{{ $idx }}"
                                    name="rows[{{ $idx }}][subdiv_id]" disabled required
                                    onchange="checkComplete({{ $idx }})">
                                <option value="" disabled selected>-- Pilih Divisi dahulu --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ── Fixed bottom bar ─────────────────────────────────────── -->
    <div class="card border-top border-0 border-4 border-success position-sticky bg-white shadow-lg" style="bottom: 0; z-index: 100;">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <span class="text-muted">Total: <strong>{{ count($import_data) }}</strong> karyawan</span>
                    <span class="ms-3 text-muted">Lengkap: <strong id="countComplete" class="text-success">0</strong> / {{ count($import_data) }}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('perusahaan.karyawan.index') }}" class="btn btn-outline-secondary px-4 nav-ajax"
                       onclick="return confirm('Yakin batalkan import?')">
                        <i class="bx bx-x me-1"></i> Batalkan
                    </a>
                    <button type="submit" class="btn btn-success px-5" id="btnSimpan">
                        <i class="bx bx-save me-1"></i> Simpan Semua Data
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>

<!-- ═══════════════════════════════════════════════════════════════
     JAVASCRIPT LOCAL FOR IMPORT REVIEW CASCADE & COMPLETION CHECK
     ════════════════════════════════════════════════════════════════ -->
<script>
const allSubdiv = {!! json_encode($all_subdiv) !!};
const totalRows = {{ count($import_data) }};

// Track completion per row
const rowComplete = {};

function checkComplete(idx) {
    const card = document.getElementById('card-' + idx);
    const form = card.closest('form');

    const alamat    = form.querySelector(`textarea[name="rows[${idx}][alamat_tinggal]"]`).value.trim();
    const prov      = document.getElementById('prov_nama_' + idx).value.trim();
    const kab       = document.getElementById('kab_nama_' + idx).value.trim();
    const kec       = document.getElementById('kec_nama_' + idx).value.trim();
    const desa      = document.getElementById('desa_nama_' + idx).value.trim();
    const alamatKtp = form.querySelector(`textarea[name="rows[${idx}][alamat_ktp]"]`).value.trim();
    const divId     = form.querySelector(`select[name="rows[${idx}][div_id]"]`).value;
    const subdivId  = form.querySelector(`select[name="rows[${idx}][subdiv_id]"]`).value;

    const complete = alamat && prov && kab && kec && desa && alamatKtp && divId && subdivId;
    rowComplete[idx] = complete;

    // Update badge
    const badgeEl = card.querySelector('.status-badge-' + idx);
    if (complete) {
        badgeEl.innerHTML = '<span class="badge bg-success rounded-pill"><i class="bx bx-check-circle me-1"></i>Lengkap</span>';
    } else {
        badgeEl.innerHTML = '<span class="badge bg-danger rounded-pill"><i class="bx bx-x-circle me-1"></i>Belum Lengkap</span>';
    }

    // Update counter
    let cnt = 0;
    for (let k in rowComplete) { if (rowComplete[k]) cnt++; }
    document.getElementById('countComplete').textContent = cnt;
}

// Provinsi → Kabupaten
function onProvChange(sel, idx) {
    const provId = sel.value;
    const provNama = sel.options[sel.selectedIndex].text;
    document.getElementById('prov_nama_' + idx).value = provNama;

    resetSel('kab_sel_' + idx, '-- Kabupaten --');
    resetSel('kec_sel_' + idx, '-- Kecamatan --');
    resetSel('desa_sel_' + idx, '-- Desa --');
    document.getElementById('kab_nama_' + idx).value = '';
    document.getElementById('kec_nama_' + idx).value = '';
    document.getElementById('desa_nama_' + idx).value = '';

    if (!provId) { checkComplete(idx); return; }

    const kabSel = document.getElementById('kab_sel_' + idx);
    kabSel.innerHTML = '<option disabled selected>⏳ Memuat...</option>';

    fetch(`{{ route('perusahaan.karyawan.kabupaten') }}?provinsi_id=${provId}`)
        .then(r => r.json())
        .then(data => {
            kabSel.innerHTML = '<option value="" disabled selected>-- Kabupaten --</option>';
            data.forEach(k => { kabSel.innerHTML += `<option value="${k.id}">${k.name}</option>`; });
            kabSel.disabled = false;
        })
        .catch(() => { kabSel.innerHTML = '<option disabled selected>⚠ Gagal</option>'; });

    checkComplete(idx);
}

// Kabupaten → Kecamatan
function onKabChange(sel, idx) {
    const kabId = sel.value;
    document.getElementById('kab_nama_' + idx).value = sel.options[sel.selectedIndex].text;

    resetSel('kec_sel_' + idx, '-- Kecamatan --');
    resetSel('desa_sel_' + idx, '-- Desa --');
    document.getElementById('kec_nama_' + idx).value = '';
    document.getElementById('desa_nama_' + idx).value = '';

    if (!kabId) { checkComplete(idx); return; }

    const kecSel = document.getElementById('kec_sel_' + idx);
    kecSel.innerHTML = '<option disabled selected>⏳ Memuat...</option>';

    fetch(`{{ route('perusahaan.karyawan.kecamatan') }}?kabupaten_id=${kabId}`)
        .then(r => r.json())
        .then(data => {
            kecSel.innerHTML = '<option value="" disabled selected>-- Kecamatan --</option>';
            data.forEach(k => { kecSel.innerHTML += `<option value="${k.id}">${k.name}</option>`; });
            kecSel.disabled = false;
        })
        .catch(() => { kecSel.innerHTML = '<option disabled selected>⚠ Gagal</option>'; });

    checkComplete(idx);
}

// Kecamatan → Desa
function onKecChange(sel, idx) {
    const kecId = sel.value;
    document.getElementById('kec_nama_' + idx).value = sel.options[sel.selectedIndex].text;

    resetSel('desa_sel_' + idx, '-- Desa --');
    document.getElementById('desa_nama_' + idx).value = '';

    if (!kecId) { checkComplete(idx); return; }

    const desaSel = document.getElementById('desa_sel_' + idx);
    desaSel.innerHTML = '<option disabled selected>⏳ Memuat...</option>';

    fetch(`{{ route('perusahaan.karyawan.desa') }}?kecamatan_id=${kecId}`)
        .then(r => r.json())
        .then(data => {
            desaSel.innerHTML = '<option value="" disabled selected>-- Desa --</option>';
            data.forEach(d => { desaSel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
            desaSel.disabled = false;
        })
        .catch(() => { desaSel.innerHTML = '<option disabled selected>⚠ Gagal</option>'; });

    checkComplete(idx);
}

// Desa change
function onDesChange(sel, idx) {
    document.getElementById('desa_nama_' + idx).value = sel.options[sel.selectedIndex].text;
    checkComplete(idx);
}

// Divisi → Sub Divisi
function onDivChange(sel, idx) {
    const divId = sel.value;
    const subdivSel = document.getElementById('subdiv_sel_' + idx);
    const filtered = allSubdiv.filter(s => s.div_id == divId);

    subdivSel.innerHTML = '<option value="" disabled selected>-- Pilih Sub Divisi --</option>';
    filtered.forEach(s => {
        subdivSel.innerHTML += `<option value="${s.id}">${s.subdiv_desc}</option>`;
    });
    subdivSel.disabled = filtered.length === 0;
    checkComplete(idx);
}

function resetSel(id, text) {
    const el = document.getElementById(id);
    el.innerHTML = `<option value="" disabled selected>${text}</option>`;
    el.disabled = true;
}

// Salin alamat tinggal ke alamat KTP
function salinAlamatImport(cb, idx) {
    const form = document.getElementById('formImport');
    const ktpField = form.querySelector(`textarea[name="rows[${idx}][alamat_ktp]"]`);

    if (cb.checked) {
        const alamat  = form.querySelector(`textarea[name="rows[${idx}][alamat_tinggal]"]`).value;
        const prov    = document.getElementById('prov_nama_' + idx).value;
        const kab     = document.getElementById('kab_nama_' + idx).value;
        const kec     = document.getElementById('kec_nama_' + idx).value;
        const desa    = document.getElementById('desa_nama_' + idx).value;

        const parts = [alamat, desa, kec, kab, prov].filter(Boolean);
        ktpField.value = parts.join(', ');
        ktpField.readOnly = true;
    } else {
        ktpField.value = '';
        ktpField.readOnly = false;
    }
    checkComplete(idx);
}
</script>
