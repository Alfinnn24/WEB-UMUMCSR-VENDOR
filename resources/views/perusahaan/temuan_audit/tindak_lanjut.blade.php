<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Temuan Audit</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('perusahaan.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('perusahaan.temuan-audit.index') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('perusahaan.temuan-audit.index') }}" data-title="Data Temuan Audit">
                        Data Temuan Audit
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Isi Tindak Lanjut</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row fade-in-up delay-1">
    <div class="col-xl-7 mx-auto">
        <div class="card border-top border-0 border-4 border-primary shadow-sm rounded-4">
            <div class="card-body p-5">
                <div class="card-title d-flex align-items-center mb-4 pb-2 border-bottom">
                    <div><i class="bx bx-edit me-1 font-22 text-primary"></i></div>
                    <h5 class="mb-0 text-primary fw-bold">Isi Tindak Lanjut Temuan Audit</h5>
                </div>
                
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-semibold text-muted">Tanggal Audit</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($data->tanggal_audit)->locale('id')->isoFormat('D MMMM YYYY') }}" readonly>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-sm-3 col-form-label fw-semibold text-muted">Uraian Temuan</label>
                    <div class="col-sm-9">
                        <div class="p-3 bg-light border rounded">
                            {!! nl2br(e($data->temuan)) !!}
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert alert-danger d-none" id="tindakLanjutErrorAlert"></div>

                <form id="tindakLanjutForm" action="{{ route('perusahaan.temuan-audit.tindak-lanjut.store', $data->id) }}" method="POST">
                    @csrf
                    <div class="row mb-3 mt-4">
                        <label class="col-sm-3 col-form-label fw-bold text-dark">Tindak Lanjut <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="tindak_lanjut" rows="5" placeholder="Masukkan uraian tindakan/perbaikan yang telah dilakukan..." required>{{ $data->tindak_lanjut }}</textarea>
                            <small class="text-muted mt-1 d-block"><i class='bx bx-info-circle'></i> Jelaskan perbaikan yang telah perusahaan Anda lakukan. Akan direview oleh Admin.</small>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm btn-enhanced">
                                <i class="bx bx-save me-1"></i>Simpan
                            </button>
                            <a href="{{ route('perusahaan.temuan-audit.index') }}" 
                               class="btn btn-light px-4 border shadow-sm nav-ajax"
                               data-url="{{ route('perusahaan.temuan-audit.index') }}"
                               data-title="Data Temuan Audit">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
