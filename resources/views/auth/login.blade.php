<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Karyawan &amp; Audit Monitoring System</title>
    <link rel="icon" href="/assets/images/logo.png?v=1.0">
    <!-- Bootstrap 5 CDN (fallback kalau aset lokal belum siap) -->
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
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
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
        .input-group { margin-bottom:1.2rem; box-shadow:0 5px 15px rgba(0,0,0,0.05); }
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
        .password-toggle {
            background:white; border:2px solid #ffe0cc; border-left:none;
            border-radius:0 16px 16px 0; cursor:pointer; color:#ff8c42;
            display:flex; align-items:center; padding:0 1rem;
        }
        .password-toggle:hover { color:#e06d1f; background:#fff9f2; }
        .btn-login {
            background: linear-gradient(135deg,#ffb27a,#ff8c42,#e06d1f);
            border:none; border-radius:16px; padding:14px 20px;
            font-weight:600; font-size:1.1rem; color:white; width:100%;
            margin:1.8rem 0 1rem 0;
            box-shadow:0 10px 25px rgba(224,109,31,0.4);
            transition:all 0.3s;
        }
        .btn-login:hover { transform:translateY(-3px); box-shadow:0 15px 30px rgba(200,80,0,0.5); }
        .alert-custom {
            border-radius:16px; border:none; padding:1rem 1.25rem;
            background:rgba(255,245,235,0.95); color:#b94e00;
            border-left:5px solid #ff8c42; margin-bottom:1.8rem;
        }
        .alert-success-custom {
            border-radius:16px; padding:1rem 1.25rem;
            background:#f0fdf4; color:#166534;
            border-left:5px solid #22c55e; margin-bottom:1.8rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="/assets/images/logo.png" alt="logo" class="logo"
                 onerror="this.src='https://placehold.co/80x80/ff8c42/white?text=K'">
        </div>

        <!-- Title -->
        <div class="text-center">
            <h4>Data Karyawan</h4>
            <div class="sub-title">
                <i class="bi bi-shield-check"></i>
                <span>Portal Login</span>
            </div>
        </div>

        <!-- Error / Success Message -->
        @if(session('error'))
            <div class="alert-custom">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert-success-custom">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <!-- NID -->
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" class="form-control" name="nid"
                       placeholder="NID"
                       value="{{ old('nid') }}" required autocomplete="username">
            </div>

            <!-- Password -->
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" class="form-control" name="password"
                       id="passwordField" placeholder="Password" required autocomplete="current-password">
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </span>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>LOGIN
            </button>
        </form>
    </div>

    <script>
    function togglePassword() {
        const pwd  = document.getElementById('passwordField');
        const icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            pwd.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
    // Trim NID sebelum submit
    document.querySelector('form').addEventListener('submit', function() {
        const nid = document.querySelector('input[name="nid"]');
        if (nid) nid.value = nid.value.trim();
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
