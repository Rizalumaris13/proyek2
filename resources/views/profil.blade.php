<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profil Pengguna — Sistem Presensi Cerdas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
:root{
  --brand:#0f3a80;
  --accent:#3f7bff;
  --bg:#f5f6f8;
  --header-height:70px;
}

*{box-sizing:border-box}

body{
  margin:0;
  background:var(--bg);
  font-family:'Inter',sans-serif;
}

/* ================= HEADER ================= */
.topbar{
  height:var(--header-height);
  background:var(--brand);
  color:#fff;
  padding:0 22px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  position:fixed;
  top:0;
  left:0;
  right:0;
  z-index:2000;
}

/* ================= LAYOUT ================= */
.app{
  display:flex;
  gap:24px;
  padding:24px;
  margin-top:var(--header-height);
}

/* ================= SIDEBAR (DESKTOP) ================= */
.sidebar{
  width:240px;
  background:#fff;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(15,58,128,.06);
  padding:20px;
  height:fit-content;
}

.sidebar a{
  display:block;
  padding:10px;
  font-weight:600;
  color:#333;
  text-decoration:none;
  border-radius:8px;
  margin-bottom:6px;
}

.sidebar a.active,
.sidebar a:hover{
  background:#e6f0ff;
  color:#2f63d6;
}

/* ================= CONTENT ================= */
.main{
  flex:1;
}

.profile-card{
  background:#fff;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(0,0,0,.06);
  padding:30px;
}

.profile-icon{
  width:120px;
  height:120px;
  border-radius:50%;
  background:#e9eef7;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:48px;
  color:var(--brand);
  margin:auto;
}

.logout-box{
  border-top:1px dashed #ddd;
  margin-top:30px;
  padding-top:20px;
}

/* ================= MOBILE MODE ================= */
@media (max-width:768px){

  .app{
    padding:16px;
  }

  /* Sidebar jadi offcanvas */
  .sidebar{
    position:fixed;
    top:var(--header-height);
    left:-260px;
    width:240px;
    height:calc(100vh - var(--header-height));
    z-index:1500;
    border-radius:0;
    transition:left .3s ease;
  }

  body.sidebar-open .sidebar{
    left:0;
  }

  /* Overlay */
  .sidebar-overlay{
    position:fixed;
    top:var(--header-height);
    left:0;
    right:0;
    bottom:0;
    background:rgba(0,0,0,.45);
    opacity:0;
    pointer-events:none;
    transition:.3s;
    z-index:1400;
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
    <img src="{{ asset('images/lo.png') }}" style="height:36px">
    <div>
      <h6 class="m-0 fw-bold">Sistem Presensi Cerdas</h6>
      <small>SMA NU Tenajar Kidul</small>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3">
    <!-- Hamburger (mobile) -->
    <button class="btn btn-link text-white d-md-none"
            onclick="toggleSidebar()"
            style="font-size:22px;text-decoration:none">☰</button>

    <!-- Desktop -->
    <div class="d-none d-md-flex align-items-center gap-3">
      <span class="fw-semibold">Halo, {{ Auth::user()->name }}</span>
    </div>
  </div>
</header>

<!-- ================= LAYOUT ================= -->
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <a href="/dashboard">Dashboard</a>
    <a href="/presensi">Presensi Siswa</a>
    <a href="/siswa">Data Siswa</a>
    <a href="/kehadiran/statistik">Statistik Kehadiran</a>
    <a class="active" href="/profil">Profil</a>
  </aside>

  <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

  <!-- CONTENT -->
  <main class="main">
    <div class="profile-card">
      <h5 class="fw-bold text-primary mb-4">Profil Pengguna</h5>

      <div class="row mb-4 align-items-center">
        <div class="col-md-3 text-center">
          <div class="profile-icon">👤</div>
        </div>
        <div class="col-md-9">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input class="form-control" value="{{ Auth::user()->name }}" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" value="{{ Auth::user()->email }}" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <input class="form-control" value="{{ ucfirst(Auth::user()->role) }}" disabled>
          </div>
        </div>
      </div>

      <div class="logout-box">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-danger w-100">Logout</button>
        </form>
      </div>
    </div>
  </main>

</div>

<script>
function toggleSidebar(){
  document.body.classList.toggle('sidebar-open');
}
</script>

</body>
</html>
