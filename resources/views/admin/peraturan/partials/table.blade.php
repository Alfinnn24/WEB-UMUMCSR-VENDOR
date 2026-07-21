<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-2">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 gap-2">
            <div>
                <h6 class="fw-bold mb-0">Daftar Peraturan Perusahaan</h6>
                <small class="text-muted">
                    @if ($data->total() > 0)
                        Menampilkan <strong>{{ $data->total() }}</strong> data
                        @if ($perusahaan_id || $search || ($status && $status !== 'all') || $tab !== 'semua')
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
            <table class="table table-hover table-sm align-middle mb-0" id="tblPeraturan">
                <thead class="table-dark">
                    <tr>
                        <th width="35">#</th>
                        <th>Nama Perusahaan</th>
                        <th width="60">Jenis</th>
                        <th>Nomor</th>
                        <th width="100">Tanggal</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="110">Tgl Upload</th>
                        <th width="110" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($data as $i => $r)
                    <tr>
                        <td class="text-muted">{{ $data->firstItem() + $i }}</td>
                        <td>
                            <span class="fw-semibold" title="{{ $r->nama_perusahaan ?? '-' }}">
                                {{ $r->nama_perusahaan ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if ($r->jenis === 'PP')
                                <span class="badge bg-primary rounded-pill badge-xs">PP</span>
                            @elseif ($r->jenis === 'PKB')
                                <span class="badge bg-info rounded-pill badge-xs">PKB</span>
                            @else
                                <span class="badge bg-secondary rounded-pill badge-xs">-</span>
                            @endif
                        </td>
                        <td><span class="fw-semibold">{{ $r->nomor }}</span></td>
                        <td><small>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</small></td>
                        <td class="text-center">
                            @if ($r->is_active)
                                <span class="badge bg-success rounded-pill badge-xs">Aktif</span>
                            @else
                                <span class="badge bg-secondary rounded-pill badge-xs">Non Aktif</span>
                            @endif
                        </td>
                        <td><small>{{ $r->created_at ? date('d/m/Y H:i', strtotime($r->created_at)) : '-' }}</small></td>
                        <td class="text-center">
                            @if ($r->file)
                                <a href="/uploads/peraturan/{{ $r->file }}" target="_blank"
                                   class="btn btn-sm btn-outline-success" title="Lihat File">
                                    <i class="bx bx-file me-1"></i>Lihat File
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted mb-2">
                                <i class="bx bx-search-alt-2 fs-4 d-block mb-2"></i>
                                @if ($search)
                                    Tidak ditemukan data untuk pencarian: <strong>"{{ $search }}"</strong>
                                @else
                                    Tidak ada data yang sesuai dengan filter.
                                @endif
                            </div>
                            @if ($perusahaan_id || $search || ($status && $status !== 'all') || $tab !== 'semua')
                            <a href="#" class="btn btn-sm btn-outline-secondary btn-reset-peraturan">
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
