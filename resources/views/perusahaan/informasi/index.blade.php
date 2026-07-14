<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Informasi</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('perusahaan.dashboard') }}" class="nav-ajax"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Informasi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0 fw-bold"><i class="bx bxs-info-circle me-1"></i> Daftar Informasi</h5>
                        <small class="text-muted">Informasi terbaru dari administrator</small>
                    </div>
                    <span class="badge bg-primary px-3 py-2">{{ count($data) }} Informasi</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th>Judul Informasi</th>
                                <th width="25%">File</th>
                                <th width="15%">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($data->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada informasi tersedia.</td>
                            </tr>
                        @else
                            @foreach ($data as $i => $row)
                                @php
                                    $file_ext = strtolower(pathinfo($row->file, PATHINFO_EXTENSION));
                                    $icon_class = 'bx bxs-file';
                                    $icon_color = 'text-secondary';
                                    if (in_array($file_ext, ['pdf'])) {
                                        $icon_class = 'bx bxs-file-pdf';
                                        $icon_color = 'text-danger';
                                    } elseif (in_array($file_ext, ['doc', 'docx'])) {
                                        $icon_class = 'bx bxs-file-doc';
                                        $icon_color = 'text-primary';
                                    } elseif (in_array($file_ext, ['xls', 'xlsx'])) {
                                        $icon_class = 'bx bxs-file-export';
                                        $icon_color = 'text-success';
                                    } elseif (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
                                        $icon_class = 'bx bxs-file-image';
                                        $icon_color = 'text-info';
                                    } elseif (in_array($file_ext, ['ppt', 'pptx'])) {
                                        $icon_class = 'bx bxs-file-blank';
                                        $icon_color = 'text-warning';
                                    } elseif (in_array($file_ext, ['zip', 'rar'])) {
                                        $icon_class = 'bx bxs-file-archive';
                                        $icon_color = 'text-dark';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold small">{{ $row->judul }}</td>
                                    <td>
                                        <a href="/uploads/informasi/{{ $row->file }}" target="_blank" class="btn btn-sm btn-outline-primary px-3">
                                            <i class="{{ $icon_class }} {{ $icon_color }} me-1"></i>
                                            Download / Lihat
                                        </a>
                                    </td>
                                    <td>
                                        <small>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
