{{-- resources/views/auth/register.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register — SMA NU Tenajar Kidul</title>

  <!-- Bootstrap (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --navy: #0f2a5a;
      --card-bg: #ffffff;
      --accent: #4a7bff; /* tombol */
      --ribbon: #ffd966;
      --muted: #7a8aa3;
      --input-bg: #efefef;
    }

    html,body{height:100%;margin:0;font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial;}
    body{
      background: var(--navy);
      color:#fff;
      display:flex;
      flex-direction:column;
      min-height:100vh;
    }

    /* NAVBAR */
    .topbar{
      background: #ffffff;
      color:#0b2a55;
      padding:12px 28px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .topbar .nav-link{color: #0b2a55 !important; opacity:.85; font-weight:600; margin-right:12px}
    .brand {display:flex;align-items:center;gap:10px}
    .brand img{height:42px}
    /* layout utama: split */
    .hero-wrap{flex:1;display:grid;grid-template-columns: 1fr 520px;gap:32px;align-items:center;padding:48px 6%;}

    /* kiri */
    .left {
      padding:24px 32px;
    }
    .left h1{
      font-size:36px;margin:0 0 14px 0;line-height:1.05;font-weight:800;
    }
    .left p.lead{color:var(--muted);font-size:16px;margin:0 0 8px}

    /* ribbon kuning (pojok kanan atas) */
    .ribbon{
      position:absolute;right:72px;top:64px;width:36px;height:160px;background:var(--ribbon);
      clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    /* form card kanan */
    .card-register{
      background:var(--card-bg);
      color:#0b2540;
      border-radius:18px;
      padding:28px;
      box-shadow: 0 20px 50px rgba(2,6,23,0.5);
      position:relative;
      min-height:420px;
    }
    .form-label{font-weight:700;color:#44546b}
    .form-control.custom{
      background:var(--input-bg);
      border:0;border-radius:10px;height:44px;padding:10px 14px;
      box-shadow:none;
    }
    .form-control.custom:focus{outline:none;box-shadow:0 6px 20px rgba(74,123,255,0.14)}
    .btn-primary.custom{
      background: linear-gradient(90deg,var(--accent), #3867f5);
      border:0;border-radius:10px;padding:10px 16px;font-weight:800;
      color:#fff;width:100%;
    }

    .text-center-muted{color:#6d7b98;font-size:14px}

    footer.site-footer{
      padding:14px 0;color:#dbe7ff;text-align:center;font-size:13px;background:transparent;
    }

    /* responsive */
    @media (max-width:1000px){
      .hero-wrap{grid-template-columns:1fr; padding:28px;}
      .ribbon{right:28px;top:28px}
      .card-register{margin-top:18px}
    }

  </style>
</head>
<body>
  <!-- NAVBAR -->
  <header class="topbar">
    <div class="d-flex align-items-center justify-content-between">
      <div class="brand">
        {{-- letakkan logo di public/images/logo.png atau ubah path --}}
        <img src="{{ asset('images/logo.png') }}" alt="logo">
        <div>
          <div style="font-weight:800">SMA NU</div>
          <div style="font-size:12px;color:#6b7a99">Tenajar Kidul</div>
        </div>
      </div>

      <nav class="d-none d-md-flex">
        <a href="#" class="nav-link">Beranda</a>
        <a href="#" class="nav-link">Berita</a>
        <a href="#" class="nav-link">Profil Sekolah</a>
        <a href="#" class="nav-link">Guru dan TU</a>
        <a href="#" class="nav-link">Galeri</a>
      </nav>
    </div>
  </header>

  <!-- RIBBON -->
  <div class="ribbon" aria-hidden="true"></div>

  <!-- HERO / CONTENT -->
  <main class="hero-wrap">
    <!-- kiri: welcome -->
    <section class="left">
      <h1>SELAMAT DATANG<br>DI SMA NU TENAJAR KIDUL</h1>
      <p class="lead">Isi data Anda untuk mendaftar. Setelah berhasil, Anda akan diarahkan ke halaman login.</p>
      <div style="height:18px"></div>
      <!-- bisa ditambah banner kecil atau info -->
    </section>

    <!-- kanan: form register -->
    <aside>
      <div class="card-register">
        {{-- tampilkan error validation --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input name="name" value="{{ old('name') }}" class="form-control custom" placeholder="Nama lengkap" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" class="form-control custom" placeholder="Email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control custom" placeholder="Password" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control custom" placeholder="Confirm password" required>
          </div>

          <div class="d-grid mt-3">
            <button class="btn btn-primary custom" type="submit">Daftar</button>
          </div>

          <div class="text-center mt-3 text-center-muted">
            Sudah punya akun? <a href="/login">Masuk</a>
          </div>
        </form>
      </div>
    </aside>
  </main>

  <footer class="site-footer">
    © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
