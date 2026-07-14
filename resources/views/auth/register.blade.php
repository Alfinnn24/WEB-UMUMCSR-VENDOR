<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Perusahaan - Karyawan &amp; Audit Monitoring System</title>
    <link rel="icon" href="/assets/images/logo.png?v=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: url('/bg.jpg') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        .register-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 2.5rem 2rem;
            box-shadow: 0 30px 50px -20px rgba(180,70,0,0.5), 0 0 0 1px rgba(255,255,255,0.5) inset;
            animation: fadeIn 0.6s ease-out;
            border: 1px solid rgba(255,255,255,0.8);
        }
        @keyframes fadeIn {
            from { opacity:0; transform:translateY(15px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .logo-wrapper {
            width:100px; height:100px;
            margin: -60px auto 15px auto;
            background: linear-gradient(135deg,#ffffff,#fff5ec);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            padding: 15px;
            box-shadow: 0 15px 25px -8px rgba(200,80,0,0.4), 0 0 0 3px rgba(255,255,255,0.9);
        }
        .logo { max-width:100%; max-height:100%; object-fit:contain; }
        h4 { color:#3d2b1a; font-weight:700; font-size:1.9rem; margin-bottom:0.25rem; }
        .sub-title {
            color:#cc6a2c; font-size:0.95rem; margin-bottom:2rem;
            display:flex; align-items:center; justify-content:center; gap:6px;
            background:rgba(255,255,255,0.4); padding:0.3rem 1.2rem;
            border-radius:50px; width:fit-content; margin-left:auto; margin-right:auto;
            border:1px solid rgba(255,255,255,0.6);
        }
        .input-group { margin-bottom:1rem; box-shadow:0 5px 15px rgba(0,0,0,0.05); }
        .input-group-text {
            background:white; border:2px solid #ffe0cc; border-right:none;
            border-radius:16px 0 0 16px; color:#ff8c42; padding:0.75rem 1rem;
        }
        .form-control {
            border:2px solid #ffe0cc; border-left:none;
            border-radius:0 16px 16px 0; padding:0.75rem 1rem; background:white;
        }
        .form-control:focus { border-color:#ff8c42; box-shadow:none; }
        .input-group:focus-within .input-group-text { border-color:#ff8c42; }
        textarea.form-control { resize:vertical; min-height:60px; }
        .btn-register {
            background: linear-gradient(135deg,#ffb27a,#ff8c42,#e06d1f);
            border:none; border-radius:16px; padding:14px 20px;
            font-weight:600; font-size:1.1rem; color:white; width:100%;
            margin:1.5rem 0 0.5rem 0;
            box-shadow:0 10px 25px rgba(224,109,31,0.4);
            transition:all 0.3s;
        }
        .btn-register:hover { transform:translateY(-3px); box-shadow:0 15px 30px rgba(200,80,0,0.5); }
        .alert-custom {
            border-radius:16px; border:none; padding:1rem 1.25rem;
            background:rgba(255,245,235,0.95); color:#b94e00;
            border-left:5px solid #ff8c42; margin-bottom:1.5rem;
        }
        .alert-success-custom {
            border-radius:16px; padding:1rem 1.25rem;
            background:#f0fdf4; color:#166534;
            border-left:5px solid #22c55e; margin-bottom:1.5rem;
        }
        .login-link {
            text-align: center; margin-top: 1.2rem;
            font-size:0.9rem; color:#6c757d;
        }
        .login-link a { color:#ff8c42; font-weight:600; text-decoration:none; }
        .login-link a:hover { color:#e06d1f; text-decoration:underline; }
        .section-label {
            color:#3d2b1a; font-weight:600; font-size:0.85rem;
            margin-bottom:0.8rem; padding-left:0.25rem;
        }
        .section-divider {
            border:0; height:1px;
            background: linear-gradient(to right, #ffe0cc, transparent);
            margin: 1.2rem 0;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo-wrapper">
            <img src="/assets/images/logo.png" alt="logo" class="logo"
                 onerror="this.src='https://placehold.co/80x80/ff8c42/white?text=K'">
        </div>

        <div class="text-center">
            <h4>Daftar Perusahaan</h4>
            <div class="sub-title">
                <i class="bi bi-building-add"></i>
                <span>Pendaftaran Akun Baru</span>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-custom">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert-success-custom">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <div class="mt-2">
                    <a href="{{ route('login') }}" class="btn btn-sm" style="background:#22c55e;color:white;border-radius:10px;font-weight:600;">Ke Halaman Login</a>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" class="form-control" name="nid"
                       placeholder="NID" value="{{ old('nid') }}" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-building"></i></span>
                <input type="text" class="form-control" name="nama"
                       placeholder="Nama PT / Perusahaan" value="{{ old('nama') }}" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                <textarea class="form-control" name="alamat"
                          placeholder="Alamat Lengkap Perusahaan" rows="2" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" class="form-control" name="password"
                       id="passwordField" placeholder="Password" required>
            </div>

            <hr class="section-divider">

            <div class="section-label">
                <i class="bi bi-person-lines-fill me-1"></i>Informasi Admin (PIC)
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" name="nama_admin"
                       placeholder="Nama Admin / PIC" value="{{ old('nama_admin') }}" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                <input type="text" class="form-control" name="nomor_admin"
                       placeholder="No WhatsApp (Contoh: 08123...)" value="{{ old('nomor_admin') }}" required>
            </div>

            <button type="submit" name="submit" class="btn-register">
                <i class="bi bi-check-lg me-1"></i> DAFTARKAN SEKARANG
            </button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
        </div>
    </div>
</body>
</html>