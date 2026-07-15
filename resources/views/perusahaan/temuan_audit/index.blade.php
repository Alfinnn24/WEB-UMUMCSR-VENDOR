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
                <li class="breadcrumb-item active" aria-current="page">Data Temuan Audit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 fade-in-up delay-1">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
            <div class="d-flex align-items-center">
                <i class="bx bx-search-alt-2 me-2 font-22 text-primary"></i>
                <h5 class="mb-0 fw-bold">Daftar Temuan Audit</h5>
            </div>
            <div>
                <form id="filterTahunForm" method="GET" action="{{ route('perusahaan.temuan-audit.index') }}" class="d-flex gap-2">
                    <select name="tahun" class="form-select form-select-sm" onchange="submitFilterTahun(this)">
                        <option value="">Semua Tahun</option>
                        @foreach ($all_tahun as $thn)
                            <option value="{{ $thn }}" {{ $filter_tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%" class="text-center">Tanggal Audit</th>
                        <th width="35%">Temuan</th>
                        <th width="25%">Tindak Lanjut</th>
                        <th width="20%">Evaluasi</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $row)
                        @php $badge_status = $row->status == 'Open' ? 'danger' : 'success'; @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_audit)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                            <td>{!! nl2br(e($row->temuan)) !!}</td>
                            <td>
                                @if (empty($row->tindak_lanjut))
                                    <span class="text-danger fst-italic"><i class="bx bx-loader-circle bx-spin me-1"></i>Belum ada tindak lanjut</span>
                                @else
                                    {!! nl2br(e($row->tindak_lanjut)) !!}
                                    @if ($row->status == 'Open')
                                        <br><span class="text-warning fst-italic mt-1 d-inline-block"><i class="bx bx-time me-1"></i>Menunggu Evaluasi Admin</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($row->evaluasi)
                                    {!! nl2br(e($row->evaluasi)) !!}
                                @else
                                    <span class="text-muted fst-italic">Belum ada evaluasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $badge_status }} rounded-pill px-3">{{ $row->status }}</span>
                            </td>
                            <td class="text-center">
                                @if ($row->status == 'Open' && empty($row->tindak_lanjut))
                                    <a href="{{ route('perusahaan.temuan-audit.tindak-lanjut', $row->id) }}"
                                       class="btn btn-primary btn-sm w-100 nav-ajax"
                                       data-url="{{ route('perusahaan.temuan-audit.tindak-lanjut', $row->id) }}"
                                       data-title="Isi Tindak Lanjut"
                                       title="Isi Tindak Lanjut">
                                        <i class="bx bx-edit me-1"></i>Isi Tindak Lanjut
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm w-100" disabled><i class="bx bx-check me-1"></i>Selesai</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($result->hasPages())
        <div class="mt-3 pt-2 border-top">
            {{ $result->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function submitFilterTahun(select) {
    var url = $(select).closest('form').attr('action') + '?tahun=' + select.value;
    if (typeof ajaxLoadPage === 'function') {
        ajaxLoadPage(url, 'Temuan Audit', true);
    } else {
        var $l = $('#ajax-loader'), $c = $('#page-content');
        $l.show(); $c.addClass('ajax-fading');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(html) {
                setTimeout(function() {
                    $c.html(html);
                    history.pushState({url: url, title: 'Temuan Audit'}, 'Temuan Audit', url);
                    $c.removeClass('ajax-fading');
                    $l.hide();
                    document.dispatchEvent(new CustomEvent('ajaxPageLoaded'));
                }, 150);
            }
        });
    }
}

// Re-initialize DataTable on page load
$(document).off('ajaxPageLoaded.temuanAudit').on('ajaxPageLoaded.temuanAudit', function() {
    if ($.fn.DataTable.isDataTable('#example2')) {
        $('#example2').DataTable().destroy();
    }
    $('#example2').DataTable({
        paging: false,
        info: false,
        searching: false,
        lengthChange: false,
        ordering: false,
        language: {
            zeroRecords: "Tidak ada data ditemukan"
        }
    });
});

// Trigger once in case page was loaded directly
if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#example2')) {
    $('#example2').DataTable({
        paging: false,
        info: false,
        searching: false,
        lengthChange: false,
        ordering: false,
        language: {
            zeroRecords: "Tidak ada data ditemukan"
        }
    });
}
</script>
