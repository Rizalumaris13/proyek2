<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Presensi Siswa — Sistem Presensi Cerdas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
:root{
  --brand:#0f3a80;
  --accent:#3f7bff;
  --bg:#f5f6f8;
}

body{
  margin:0;
  background:var(--bg);
  font-family:'Inter',sans-serif;
}

/* ================= HEADER ================= */
.topbar{
  background:var(--brand);
  color:#fff;
  padding:14px 22px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

/* ================= LAYOUT ================= */
.app{
  padding:28px;
  display:grid;
  grid-template-columns:240px 1fr;
  gap:24px;
}

/* ================= SIDEBAR ================= */
.sidebar-card{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(15,58,128,.06);
}

.nav-link{
  display:block;
  font-weight:600;
  color:#333;
  padding:10px;
  border-radius:8px;
  margin-bottom:6px;
  text-decoration:none;
}

.nav-link.active{
  background:linear-gradient(90deg,var(--accent),#2f63d6);
  color:#fff;
}

/* ================= CONTENT ================= */
.panel{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(15,58,128,.06);
  margin-bottom:20px;
}

.camera-box{
  width:100%;
  height:280px;
  border:2px dashed #c7c7c7;
  border-radius:12px;
  display:flex;
  justify-content:center;
  align-items:center;
  font-size:18px;
  color:#7b8794;
}

.kelas-item{
  background:#f8f9fb;
  border:1px solid #eee;
  border-radius:10px;
  padding:16px;
  margin-bottom:15px;
}

.kelas-title{
  font-weight:700;
}

.kelas-actions{
  display:flex;
  gap:10px;
  margin-top:15px;
}

/* ================= FOOTER ================= */
footer{
  background:var(--brand);
  color:#fff;
  text-align:center;
  padding:10px;
  font-size:13px;
}

/* =============== OVERLAY (DESKTOP OFF) =============== */
.sidebar-overlay{
  display:none;
}

/* =====================================================
   MOBILE MODE (SAMA PERSIS KAYAK DASHBOARD)
===================================================== */
@media (max-width:768px){

  .app{
    grid-template-columns:1fr;
    padding:16px;
  }

  aside.sidebar{
    position:fixed;
    top:0;
    left:-280px;
    width:260px;
    height:100vh;
    z-index:1050;
    transition:left .3s ease;
  }

  body.sidebar-open aside.sidebar{
    left:0;
  }

  .sidebar-card{
    height:100%;
    border-radius:0;
  }

  .sidebar-overlay{
    display:block;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    z-index:1040;
    opacity:0;
    pointer-events:none;
    transition:opacity .3s;
  }

  body.sidebar-open .sidebar-overlay{
    opacity:1;
    pointer-events:auto;
  }
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="topbar">
  <div class="d-flex align-items-center gap-3">
    <img src="{{ asset('images/logo.png') }}" style="height:34px">
    <div>
      <h5 class="m-0 fw-bold">Sistem Presensi Cerdas</h5>
      <small>SMA NU Tenajar Kidul</small>
    </div>
  </div>

  <div class="d-none d-md-flex align-items-center gap-3">
      <div style="font-weight:600">
        Halo, {{ Auth::user()->name ?? 'Admin' }}
      </div>
    </div>
    <!-- HAMBURGER (MOBILE ONLY) -->
    <button class="btn btn-link text-white d-md-none"
            onclick="toggleSidebar()"
            style="font-size:22px;text-decoration:none">
      ☰
    </button>
  </div>
</header>

<!-- ================= APP ================= -->
<div class="app">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <nav class="nav flex-column">
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <a class="nav-link active" href="/presensi">Presensi Siswa</a>
        <a class="nav-link" href="/siswa">Data Siswa</a>
        <a class="nav-link" href="/kehadiran/statistik">Statistik Kehadiran</a>
        <a class="nav-link" href="/profil">Profil</a>
      </nav>
    </div>
  </aside>

  <!-- ===== CONTENT ===== -->
  <main>

    <div class="panel">
      <div class="fw-bold mb-2">Presensi Otomatis</div>
      <div class="camera-box">📷 Kamera belum terhubung</div>
    </div>

    <div class="panel">
      <div class="fw-bold mb-2">Kelas Yang Anda Ampu</div>
      <div class="text-muted mb-3">{{ now()->translatedFormat('l, d F Y') }}</div>

      @foreach($kelas as $k)
      <div class="kelas-item">
        <div class="kelas-title">{{ $k->nama_kelas }}</div>
        <small class="text-muted">
          Jumlah siswa: {{ $k->siswa_count ?? $k->siswa()->count() }}
        </small>

        <div class="kelas-actions">
          <a href="{{ route('kehadiran.index',['kelas_id'=>$k->id]) }}"
             class="btn btn-secondary btn-sm">
            Presensi Manual
          </a>
          <button class="btn btn-primary btn-sm">
            Generate Face Recognition
          </button>
        </div>
      </div>
      @endforeach
    </div>

  </main>
</div>

<!-- ===== OVERLAY (HARUS DI LUAR .app) ===== -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<footer>
© {{ date('Y') }} SMA NU Tenajar Kidul
</footer>

<script>
function toggleSidebar(){
  document.body.classList.toggle('sidebar-open');
}
</script>

</body>
</html>
