@php
use Illuminate\Support\Facades\Auth;
@endphp

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kehadiran Manual — Sistem Presensi Cerdas</title>

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
  box-shadow:0 6px 18px rgba(0,0,0,.06);
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

/* ================= PANEL ================= */
.panel{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(0,0,0,.06);
  margin-bottom:24px;
}

/* ================= ABSEN OPTION ================= */
.attendance-group{
  display:flex;
  gap:10px;
}

.attendance-option input{
  display:none;
}

.attendance-option label{
  width:40px;
  height:40px;
  border-radius:50%;
  display:flex;
  justify-content:center;
  align-items:center;
  font-weight:700;
  border:2px solid #d0d5dd;
  cursor:pointer;
  background:#fff;
  color:#555;
  transition:.2s;
}

.attendance-option input:checked + label{
  background:var(--accent);
  color:#fff;
  border-color:var(--accent);
}
footer {
            background: var(--brand);
            color: #fff;
            text-align: center;
            padding: 10px;
            font-size: 13px;
        }
/* ================= OVERLAY ================= */
.sidebar-overlay{display:none}

/* ================= MOBILE MODE ================= */
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
    transition:left .3s;
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
    transition:.3s;
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
    <img src="{{ asset('images/lo.png') }}" style="height:34px">
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

    <!-- HAMBURGER -->
    <button class="btn btn-link text-white d-md-none"
            onclick="toggleSidebar()"
            style="font-size:22px;text-decoration:none">☰</button>
  </div>
</header>

<div class="app">

<!-- ================= SIDEBAR ================= -->
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

<!-- ================= CONTENT ================= -->
<main>

  <div class="panel">
    <h5 class="fw-bold">Absensi Manual</h5>
    <div class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="mt-2 fw-semibold">
      Kelas: <span class="text-primary">{{ $kelas->nama_kelas }}</span>
    </div>
  </div>

  <div class="panel">
    <form method="POST" action="{{ route('kehadiran.store') }}">
      @csrf
      <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th width="60">No</th>
            <th>Nama Siswa</th>
            <th width="240">Status</th>
          </tr>
        </thead>
        <tbody>
        @foreach($siswa as $i => $s)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $s->nama }}</td>
          <td>
            <div class="attendance-group">
              @foreach(['hadir'=>'H','sakit'=>'S','izin'=>'I','alfa'=>'A'] as $v=>$l)
              <div class="attendance-option">
                <input type="radio"
                       id="{{ $v.$s->id }}"
                       name="kehadiran[{{ $s->id }}]"
                       value="{{ $v }}"
                       {{ (isset($kehadiranHariIni[$s->id]) && $kehadiranHariIni[$s->id]->status == $v) ? 'checked' : '' }}>
                <label for="{{ $v.$s->id }}">{{ $l }}</label>
              </div>
              @endforeach
            </div>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>

      <div class="d-flex gap-2 mt-3">
        <button class="btn btn-primary">Simpan Kehadiran</button>
        <a href="/presensi" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>

</main>
</div>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
<footer>
    © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
  </footer>
<script>
function toggleSidebar(){
  document.body.classList.toggle('sidebar-open');
}
</script>

</body>
</html>
