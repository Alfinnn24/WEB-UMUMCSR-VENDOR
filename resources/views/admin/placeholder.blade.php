{{--
    VIEW PARTIAL: admin/placeholder.blade.php
    Sebagai placeholder halaman yang belum dimigrasi (Modul 2+)
--}}
<div class="card modern-card fade-in-up">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-3 p-3 bg-light text-primary">
                <i class='bx bx-info-circle fs-2'></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">{{ $pageTitle ?? $pageName ?? 'Halaman' }}</h5>
                <small class="text-muted">Modul ini akan diimplementasikan pada langkah berikutnya.</small>
            </div>
        </div>
        <div class="p-5 text-center text-muted border rounded-3 bg-light bg-opacity-50">
            <i class="bx bx-cog fs-1 text-secondary mb-3 animate-spin"></i>
            <h5>Fitur dalam Pengembangan</h5>
            <p class="mb-0">Halaman partial <strong>{{ $pageTitle ?? $pageName ?? 'Halaman' }}</strong> berhasil diload menggunakan AJAX tanpa full reload.</p>
        </div>
    </div>
</div>
