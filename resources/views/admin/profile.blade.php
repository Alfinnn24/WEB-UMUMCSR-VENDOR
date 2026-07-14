<!-- breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 fade-in-up">
    <div class="breadcrumb-title pe-3 fw-semibold">Profile</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-ajax text-decoration-none"
                        data-url="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>
<!-- end breadcrumb -->

<div class="container fade-in-up delay-1">
    <div class="main-body">
        <div class="row">
            {{-- Detail Kiri --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/images/team.png" alt="Admin" class="rounded-circle p-1 bg-warning" width="110">
                            <div class="mt-3 w-100">
                                <h4 class="fw-bold text-dark">{{ ucwords($user->nama) }}</h4>
                                <p class="text-secondary mb-1"><b>{{ $user->nid }}</b></p>
                                <p class="text-muted font-size-sm">{{ strtoupper($user->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Kanan --}}
            <div class="col-lg-8">
                {{-- Update Password --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="mb-4 d-flex align-items-center fw-bold text-warning">
                            <i class="bx bx-key me-2 font-22"></i> Ubah Password
                        </h5>

                        <div class="alert alert-success d-none" id="passwordSuccessAlert"></div>
                        <div class="alert alert-danger d-none" id="passwordErrorAlert"></div>

                        <form id="updatePasswordForm" action="{{ route('admin.profile.password') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0 text-secondary fw-semibold">Status Password</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" value="******** (Tersimpan dengan aman)" class="form-control bg-light" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0 text-secondary fw-semibold">Password Baru</h6>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group" id="show_hide_password_baru">
                                        <input type="password" name="password_baru" class="form-control border-end-0" id="password_baru" placeholder="Masukkan password baru" required>
                                        <a href="javascript:;" class="input-group-text bg-transparent toggle-pass" data-target="#password_baru">
                                            <i class='bx bx-hide'></i>
                                        </a>
                                    </div>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0 text-secondary fw-semibold">Konfirmasi Password</h6>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group" id="show_hide_konfirmasi_password">
                                        <input type="password" name="konfirmasi_password" class="form-control border-end-0" id="konfirmasi_password" placeholder="Ketik ulang password baru" required>
                                        <a href="javascript:;" class="input-group-text bg-transparent toggle-pass" data-target="#konfirmasi_password">
                                            <i class='bx bx-hide'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-warning px-4 text-dark fw-semibold btn-enhanced">
                                        <i class="bx bx-save me-1"></i> Ubah Password
                                    </button>
                                    <button type="reset" class="btn btn-secondary px-4 ms-2">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $(".toggle-pass").on('click', function(event) {
        event.preventDefault();
        var target = $(this).data('target');
        var input = $(target);
        var icon = $(this).find('i');
        if (input.attr("type") == "text") {
            input.attr('type', 'password');
            icon.removeClass("bx-show").addClass("bx-hide");
        } else {
            input.attr('type', 'text');
            icon.removeClass("bx-hide").addClass("bx-show");
        }
    });

    $('#updatePasswordForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var successAlert = $('#passwordSuccessAlert');
        var errorAlert = $('#passwordErrorAlert');
        successAlert.addClass('d-none').html('');
        errorAlert.addClass('d-none').html('');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                successAlert.removeClass('d-none').html(res.message);
                form[0].reset();
                window.setTimeout(function() {
                    successAlert.fadeTo(500, 0).slideUp(500, function() {
                        $(this).addClass('d-none').html('');
                    });
                }, 3000);
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Terjadi kesalahan.';
                errorAlert.removeClass('d-none').html(msg);
                window.setTimeout(function() {
                    errorAlert.fadeTo(500, 0).slideUp(500, function() {
                        $(this).addClass('d-none').html('');
                    });
                }, 3000);
            }
        });
    });
});
</script>
@endpush
