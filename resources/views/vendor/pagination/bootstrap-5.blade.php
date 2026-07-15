@if ($paginator->hasPages())
    <nav class="custom-pagination" aria-label="Pagination">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            {{-- Info text --}}
            <div class="pagination-info">
                <span class="text-muted" style="font-size:.82rem;">
                    Halaman <strong>{{ $paginator->currentPage() }}</strong> dari <strong>{{ $paginator->lastPage() }}</strong>
                    <span class="d-none d-sm-inline">&nbsp;·&nbsp; {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</span>
                </span>
            </div>

            {{-- Navigation buttons --}}
            <div class="d-flex align-items-center gap-2">
                @if ($paginator->onFirstPage())
                    <button type="button" class="btn btn-pagination disabled" disabled aria-label="Sebelumnya">
                        <i class="bx bx-chevron-left me-1"></i>
                        <span class="d-none d-sm-inline">Sebelumnya</span>
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-pagination page-link" rel="prev" aria-label="Sebelumnya">
                        <i class="bx bx-chevron-left me-1"></i>
                        <span class="d-none d-sm-inline">Sebelumnya</span>
                    </a>
                @endif

                {{-- Page indicator pill --}}
                <span class="pagination-pill">
                    {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-pagination btn-pagination-next page-link" rel="next" aria-label="Selanjutnya">
                        <span class="d-none d-sm-inline">Selanjutnya</span>
                        <i class="bx bx-chevron-right ms-1"></i>
                    </a>
                @else
                    <button type="button" class="btn btn-pagination btn-pagination-next disabled" disabled aria-label="Selanjutnya">
                        <span class="d-none d-sm-inline">Selanjutnya</span>
                        <i class="bx bx-chevron-right ms-1"></i>
                    </button>
                @endif
            </div>
        </div>
    </nav>
@endif
