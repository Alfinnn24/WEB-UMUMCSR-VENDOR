<style>
.btn-file-action {
    font-size: 11px;
    line-height: 1.4;
    padding: 1px 8px;
    border-width: 1.5px;
    font-weight: 500;
}
.btn-file-action i {
    font-size: 12px;
    margin-right: 3px;
}
.file-empty-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 10px;
    font-weight: 500;
    color: #8c8c8c;
    background: #f0f0f0;
    padding: 2px 10px;
    border-radius: 20px;
    letter-spacing: 0.2px;
}
</style>

<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2">
            <div>
                <h6 class="fw-bold mb-0">Daftar Perusahaan</h6>
                <small class="text-muted">
                    @if ($data->total() > 0)
                        Menampilkan <strong>{{ $data->total() }}</strong> data
                        @if ($search || $filter_status)
                            sesuai filter
                        @else
                            semua
                        @endif
                    @else
                        Tidak ada data
                    @endif
                </small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0" id="tblBpjsMonitoring">
                <thead class="table-dark">
                    <tr>
                        <th width="35">#</th>
                        <th>Nama Perusahaan</th>
                        <th width="160" class="text-center">BPJS Kesehatan</th>
                        <th width="160" class="text-center">BPJS Ketenagakerjaan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($data as $i => $row)
                    @php
                        $kes_ada = !empty($row->bpjs_kes_file);
                        $tk_ada = !empty($row->bpjs_tk_file);
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $data->firstItem() + $i }}</td>
                        <td>
                            <small class="fw-semibold" title="{{ $row->nama_perusahaan }}">{{ $row->nama_perusahaan }}</small>
                        </td>
                        <td class="text-center">
                            @if ($kes_ada)
                                <a href="{{ asset('uploads/bpjs/' . $row->bpjs_kes_file) }}" target="_blank" class="btn btn-outline-success btn-file-action" title="Lihat File BPJS Kesehatan">
                                    <i class="bx bx-show"></i> Lihat File
                                </a>
                            @else
                                <span class="file-empty-badge">
                                    <i class="bx bx-x-circle"></i> Belum Upload
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($tk_ada)
                                <a href="{{ asset('uploads/bpjs/' . $row->bpjs_tk_file) }}" target="_blank" class="btn btn-outline-success btn-file-action" title="Lihat File BPJS Ketenagakerjaan">
                                    <i class="bx bx-show"></i> Lihat File
                                </a>
                            @else
                                <span class="file-empty-badge">
                                    <i class="bx bx-x-circle"></i> Belum Upload
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="text-muted mb-2">
                                <i class="bx bx-search-alt-2 fs-4 d-block mb-2"></i>
                                @if ($search)
                                    Tidak ditemukan data untuk pencarian: <strong>"{{ $search }}"</strong>
                                @else
                                    Tidak ada data yang sesuai dengan filter.
                                @endif
                            </div>
                            @if ($search || $filter_status)
                            <a href="#" class="btn btn-sm btn-outline-secondary btn-reset-bpjs">
                                <i class="bx bx-reset me-1"></i>Reset Filter
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
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