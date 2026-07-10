{{--
VIEW PARTIAL: admin/ring_wilayah/index.blade.php
Ring Wilayah CRUD — HTML saja, TANPA <script>
--}}

<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Ring Wilayah</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Ring Wilayah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row fade-in-up delay-1">

    <!-- FORM TAMBAH -->
    <div class="col-xl-4">
        <div class="card border-top border-0 border-4 border-success modern-card">
            <div class="card-body">
                <div class="border p-4 rounded">
                    <div class="card-title d-flex align-items-center gap-2">
                        <i class="bx bxs-map-pin font-22 text-success"></i>
                        <h5 class="mb-0 fw-bold">Tambah Ring Wilayah</h5>
                    </div>
                    <hr />
                    <div class="alert alert-danger d-none" id="addRingErrorAlert"></div>
                    <form id="addRingForm" method="POST" action="{{ route('admin.ring-wilayah.store') }}">
                        @csrf
                        <!-- Pilih Ring -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ring <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ring" id="ring1" value="Ring 1" required>
                                    <label class="form-check-label" for="ring1">Ring 1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ring" id="ring2" value="Ring 2">
                                    <label class="form-check-label" for="ring2">Ring 2</label>
                                </div>
                            </div>
                        </div>

                        <!-- Provinsi -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                            <select class="form-select" id="provinsi_sel" required>
                                <option value="" disabled selected>-- Pilih Provinsi --</option>
                                @foreach($provinces as $pv)
                                    <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="provinsi" id="provinsi_nama">
                        </div>

                        <!-- Kabupaten -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kabupaten / Kota <span class="text-danger">*</span></label>
                            <select class="form-select" id="kabupaten_sel" disabled required>
                                <option value="" disabled selected>-- Pilih Provinsi dahulu --</option>
                            </select>
                            <input type="hidden" name="kabupaten" id="kabupaten_nama">
                        </div>

                        <!-- Kecamatan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select" id="kecamatan_sel" disabled required>
                                <option value="" disabled selected>-- Pilih Kabupaten dahulu --</option>
                            </select>
                            <input type="hidden" name="kecamatan" id="kecamatan_nama">
                        </div>

                        <!-- Desa -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Desa / Kelurahan <span class="text-danger">*</span></label>
                            <select class="form-select" id="desa_sel" disabled required>
                                <option value="" disabled selected>-- Pilih Kecamatan dahulu --</option>
                            </select>
                            <input type="hidden" name="desa" id="desa_nama">
                        </div>

                        <button type="submit" class="btn btn-success px-5">
                            <i class="bx bx-plus-circle me-1"></i> Tambah ke Ring
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Ringkasan -->
        @php
            $total_r1 = $ring_wilayah->where('ring', 'Ring 1')->count();
            $total_r2 = $ring_wilayah->where('ring', 'Ring 2')->count();
        @endphp
        <div class="row g-2 mt-1">
            <div class="col-6">
                <div class="card border text-center py-2" style="background:#f8f9fa">
                    <div class="fs-4 fw-bold text-dark">{{ $total_r1 }}</div>
                    <div class="small text-body-secondary" style="font-size: .7rem;">Ring 1</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border text-center py-2" style="background:#f8f9fa">
                    <div class="fs-4 fw-bold text-dark">{{ $total_r2 }}</div>
                    <div class="small text-body-secondary" style="font-size: .7rem;">Ring 2</div>
                </div>
            </div>
        </div>
    </div>

    <!-- LIST DATA -->
    <div class="col-xl-8">
        <div class="card modern-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bx bxs-map-alt me-1 text-primary"></i> Daftar Ring Wilayah</h5>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">{{ count($ring_wilayah) }} Wilayah</span>
                </div>

                <!-- Filter Tab -->
                <ul class="nav nav-pills mb-3" id="ringTab">
                    <li class="nav-item">
                        <a class="nav-link active btn-filter-ring" href="#" data-ring="semua">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-filter-ring" href="#" data-ring="Ring 1">Ring 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-filter-ring" href="#" data-ring="Ring 2">Ring 2</a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="ringTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Ring</th>
                                <th>Provinsi</th>
                                <th>Kabupaten / Kota</th>
                                <th>Kecamatan</th>
                                <th>Desa / Kelurahan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $prev_ring = ''; @endphp
                            @forelse($ring_wilayah as $idx => $row)
                                @if($row->ring !== $prev_ring)
                                    <tr class="ring-separator" data-ring="{{ $row->ring }}">
                                        <td colspan="7" class="py-2 px-3 bg-light">
                                            <strong class="text-dark">{{ $row->ring }}</strong>
                                        </td>
                                    </tr>
                                    @php $prev_ring = $row->ring; @endphp
                                @endif
                                <tr data-ring="{{ $row->ring }}">
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <span class="badge {{ $row->ring === 'Ring 1' ? 'bg-success' : 'bg-info' }} px-2">
                                            {{ $row->ring }}
                                        </span>
                                    </td>
                                    <td>{{ $row->provinsi }}</td>
                                    <td>{{ $row->kabupaten }}</td>
                                    <td>{{ $row->kecamatan }}</td>
                                    <td>{{ $row->desa }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-ring"
                                            data-id="{{ $row->id }}" data-name="{{ $row->desa }}" data-ring="{{ $row->ring }}" title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bx bxs-map-alt fs-1 d-block mb-2 opacity-25"></i>
                                        Belum ada data ring wilayah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
