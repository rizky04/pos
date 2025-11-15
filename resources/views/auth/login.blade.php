<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SaaS POS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(145deg, #f3f4f6, #e5e7eb);
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .login-card {
      background: #ffffff;
      border-radius: 28px;
      box-shadow: 0 25px 60px rgba(15, 23, 42, 0.1);
      padding: 40px 36px;
      width: 100%;
      max-width: 420px;
      transition: all .3s ease;
    }

    .login-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 28px 70px rgba(15, 23, 42, 0.12);
    }

    .login-logo {
      width: 54px;
      height: 54px;
      border-radius: 14px;
      background: #ff3b5c;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 24px;
      font-weight: 700;
      margin: 0 auto 16px;
    }

    .login-title {
      font-size: 20px;
      font-weight: 600;
      color: #111827;
      text-align: center;
    }

    .login-sub {
      font-size: 11px;
      color: #6b7280;
      text-align: center;
      margin-bottom: 24px;
    }

    .form-label {
      font-size: 10px;
      color: #6b7280;
      margin-bottom: 4px;
    }

    .form-control {
      font-size: 11px;
      border-radius: 12px;
      padding: 8px 10px;
      border: 1px solid #e5e7eb;
    }

    .form-control:focus {
      border-color: #ff3b5c;
      box-shadow: 0 0 0 2px rgba(255, 59, 92, 0.15);
    }

    .btn-login {
      width: 100%;
      border-radius: 999px;
      background: #ff3b5c;
      border: none;
      padding: 8px 14px;
      color: #fff;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: .2px;
      transition: all .2s ease;
    }

    .btn-login:hover {
      background: #e03450;
      box-shadow: 0 6px 18px rgba(255, 59, 92, 0.3);
      transform: translateY(-1px);
    }

    .login-footer {
      font-size: 10px;
      color: #9ca3af;
      text-align: center;
      margin-top: 16px;
    }

    .form-check-label {
      font-size: 10px;
      color: #6b7280;
    }

    .text-danger {
      font-size: 10px;
      text-align: center;
      display: none;
    }

    @media (max-width: 768px) {
      body {
        background: #fff;
      }
      .login-card {
        box-shadow: none;
        border-radius: 0;
        padding: 32px 20px;
      }
    }
  </style>
</head>

<body>

  <div class="login-card">
    <div class="login-logo">M</div>
    <div class="login-title">Masuk ke SaaS POS</div>
    <div class="login-sub">Kelola bisnis, transaksi, dan laporan dari satu platform.</div>

    <form id="loginForm" action="{{ route('login') }}" method="POST">
          @csrf
      <div class="mb-3">
        <label class="form-label">Email / Username</label>
         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
      </div>
      <div class="mb-2">
        <label class="form-label">Kata Sandi</label>
        <div class="input-group">
 <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror          <button class="btn btn-light" type="button" id="togglePassword" style="border-radius: 12px;">
            <i class="bi bi-eye-slash"></i>
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="rememberMe" style="transform: scale(.9)">
          <label class="form-check-label" for="rememberMe">Ingat saya</label>
        </div>
        <a href="#" class="text-decoration-none" style="font-size:10px; color:#ff3b5c;">Lupa password?</a>
      </div>

      <button type="submit" class="btn-login">Masuk Sekarang</button>
      <div class="text-danger mt-2" id="errorMsg">Username atau password salah!</div>
      <div class="login-footer mt-3" style="font-size:11px;">
    Belum punya akun?
    <a href="{{ route('register') }}" style="color:#ff3b5c; font-weight:600; text-decoration:none;">
        Daftar di sini
    </a>
</div>

    </form>

    <div class="login-footer">
      © 2025 SaaS POS • All rights reserved
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $('#togglePassword').on('click', function() {
      const input = $('#password');
      const icon = $(this).find('i');
      if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('bi-eye-slash').addClass('bi-eye');
      } else {
        input.attr('type', 'password');
        icon.removeClass('bi-eye').addClass('bi-eye-slash');
      }
    });
  </script>
</body>
</html>
