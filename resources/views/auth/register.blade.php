<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Tenant - SaaS POS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(145deg, #f3f4f6, #e5e7eb);
      font-family: system-ui, -apple-system;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .register-card {
      background: #ffffff;
      border-radius: 28px;
      box-shadow: 0 25px 60px rgba(15,23,42, .1);
      padding: 35px 32px;
      width: 100%;
      max-width: 760px;
    }

    .register-logo {
      width: 54px; height: 54px;
      border-radius: 14px;
      background: #ff3b5c; color:#fff;
      display:flex; justify-content:center; align-items:center;
      font-size:22px; font-weight:700;
      margin: 0 auto 10px;
    }

    .form-label { font-size:10px; color:#6b7280; margin-bottom:4px; }
    .form-control, .form-select {
      font-size:11px;
      border-radius:10px;
      padding:10px 12px;
    }

    .btn-register {
      width:100%;
      background:#ff3b5c; color:white;
      border:none;
      padding:10px 14px;
      border-radius:999px;
      font-size:12px;
      font-weight:600;
      margin-top:10px;
    }

    .link-login { color:#ff3b5c; font-size:10px; text-decoration:none; }
  </style>
</head>

<body>

<div class="register-card">

    <div class="register-logo">M</div>
    <h5 class="text-center fw-bold" style="font-size: 18px;">Daftarkan Bisnismu</h5>
    <p class="text-center" style="font-size:11px; color:#6b7280">
        Daftar tenant + setup outlet utama dalam 1 langkah.
    </p>

    <form id="registerForm">
        @csrf

        <!-- ===================== DATA TENANT ===================== -->
        <h6 style="font-size:12px; font-weight:600;" class="mt-3">Data Tenant</h6>

        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="form-label">Nama Bisnis</label>
                <input type="text" name="tenant_name" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Nama Pemilik</label>
                <input type="text" name="owner_name" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control">
                    <button class="btn btn-light" type="button" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Pilih Paket</label>
                <select name="package" class="form-select">
                    <option value="Basic">Basic (1 outlet)</option>
                    <option value="Pro">Pro (3 outlet)</option>
                    <option value="Enterprise">Enterprise (Unlimited)</option>
                </select>
            </div>

        </div>

        <!-- ===================== DATA OUTLET ===================== -->
        <h6 style="font-size:12px; font-weight:600;" class="mt-3">Outlet Utama</h6>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Outlet</label>
                <input type="text" name="outlet_name" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Alamat Outlet</label>
                <input type="text" name="outlet_address" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Kota</label>
                <input type="text" name="city" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Zona Waktu</label>
                <select name="timezone" class="form-select">
                    <option value="Asia/Jakarta">WIB</option>
                    <option value="Asia/Makassar">WITA</option>
                    <option value="Asia/Jayapura">WIT</option>
                </select>
            </div>
        </div>

        <div class="form-check mt-3 mb-2">
            <input class="form-check-input" type="checkbox" id="agreeTerms">
            <label class="form-check-label" for="agreeTerms" style="font-size:10px;">
                Saya menyetujui syarat & ketentuan SaaS POS.
            </label>
        </div>

        <button class="btn-register" type="submit">Daftarkan Tenant</button>
    </form>

    <div class="text-center mt-3" style="font-size: 11px;">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="link-login">Masuk di sini</a>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#togglePassword').on('click', function(){
    let input = $('#password');
    let icon  = $(this).find('i');

    if(input.attr('type') === 'password'){
        input.attr('type', 'text');
        icon.removeClass('bi-eye-slash').addClass('bi-eye');
    } else {
        input.attr('type', 'password');
        icon.removeClass('bi-eye').addClass('bi-eye-slash');
    }
});

$('#registerForm').on('submit', function(e){
    e.preventDefault();

    if(!$('#agreeTerms').is(':checked')){
        Swal.fire('Oops!', 'Anda harus menyetujui syarat & ketentuan.', 'warning');
        return;
    }

    $.ajax({
        url: "{{ route('register.process') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(res){
            if(res.status === 'ok'){
                Swal.fire('Berhasil!', res.message, 'success')
                    .then(() => window.location.href = res.redirect);
            } else {
                Swal.fire('Gagal!', res.message, 'error');
            }
        },
        error: function(){
            Swal.fire('Error!', 'Terjadi kesalahan di server.', 'error');
        }
    });
});
</script>

</body>
</html>
