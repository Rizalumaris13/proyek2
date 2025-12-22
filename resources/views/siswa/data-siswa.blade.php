<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Data Siswa — Sistem Presensi Cerdas</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --brand:#0f3a80;
      --accent:#3f7bff;
      --muted:#7b8794;
      --panel:#ffffff;
      --bg:#f5f6f8;
    }

    html,body{height:100%}
    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial;
      background:var(--bg);
      color:#122233;
    }

    /* ===== TOPBAR ===== */
    .topbar{
      background:var(--brand);
      color:#fff;
      padding:14px 22px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{height:34px}
    .brand h5{margin:0;font-weight:700}

    /* ===== LAYOUT ===== */
    .app{
      display:grid;
      grid-template-columns:260px 1fr;
      gap:28px;
      padding:28px;
      align-items:start;
    }

    /* ===== SIDEBAR ===== */
    .sidebar-card{
      background:var(--panel);
      border-radius:14px;
      padding:18px;
      box-shadow:0 6px 18px rgba(15,58,128,.06);
    }
    .nav-link{
      color:#2d3b4a;
      font-weight:600;
      padding:10px;
      border-radius:8px;
      margin-bottom:6px;
    }
    .nav-link.active{
      background:linear-gradient(90deg,var(--accent),#2f63d6);
      color:#fff;
    }

    /* ===== CONTENT ===== */
    .content{
      min-height:70vh;
    }
    .panel{
      background:var(--panel);
      padding:22px;
      border-radius:14px;
      box-shadow:0 6px 18px rgba(2,6,23,.06);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width:768px){
      .app{
        grid-template-columns:1fr;
        padding:16px;
      }

      .sidebar{
        position:fixed;
        top:0;
        left:-280px;
        width:260px;
        height:100vh;
        z-index:1050;
        transition:.3s;
      }

      body.sidebar-open .sidebar{
        left:0;
      }

      .sidebar-overlay{
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

    footer{
      background:var(--brand);
      color:#fff;
      text-align:center;
      padding:10px;
      font-size:13px;
    }
  </style>
</head>

<body>

<!-- TOPBAR -->
<header class="topbar">
  <div class="brand">
    <img src="{{ asset('images/lo.png') }}">
    <div>
      <h5>Sistem Presensi Cerdas</h5>
      <small>SMA NU Tenajar Kidul</small>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-link text-white d-md-none"
      onclick="toggleSidebar()"
      style="font-size:22px;text-decoration:none">☰</button>
    <div class="d-none d-md-block fw-semibold">
      Halo, {{ Auth::user()->name ?? 'Admin' }}
    </div>
  </div>
</header>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- APP -->
<div class="app container-fluid">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <nav class="nav flex-column">
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <a class="nav-link" href="/presensi">Presensi Siswa</a>
        <a class="nav-link active" href="/siswa">Data Siswa</a>
        <a class="nav-link" href="/kehadiran/statistik">Statistik Kehadiran</a>
        <a class="nav-link" href="/profil">Profil</a>
      </nav>
    </div>
  </aside>

  <!-- CONTENT -->
  <main class="content">
    <div class="panel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Data Siswa</h6>

        <form method="GET" action="{{ route('siswa.index') }}">
          <select name="kelas_id" class="form-select form-select-sm"
            style="width:auto" onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $kelas)
              <option value="{{ $kelas->id }}"
                {{ $filterKelas==$kelas->id?'selected':'' }}>
                {{ $kelas->nama_kelas }}
              </option>
            @endforeach
          </select>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Siswa</th>
              <th>NISN</th>
              <th>Jenis Kelamin</th>
              <th>Sinkronkan Wajah</th>
              @if(Auth::user()->role==='admin')
                <th>Edit</th>
              @endif
            </tr>
          </thead>
          <tbody>
          @forelse($dataSiswa as $i=>$siswa)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $siswa->nama }}</td>
              <td>{{ $siswa->nisn }}</td>
              <td>{{ $siswa->jenis_kelamin }}</td>
              <td>X</td>
              @if(Auth::user()->role==='admin')
              <td>
                <a href="{{ route('siswa.edit',$siswa->id) }}"
                   class="btn btn-sm btn-warning">✏️Edit</a>
              </td>
              @endif
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">
                Belum ada data siswa
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if(Auth::user()->role==='admin')
      <div class="text-end mt-3">
        <a href="{{ route('siswa.create') }}"
           class="btn btn-primary">Tambah Siswa</a>
      </div>
      @endif
    </div>
  </main>
</div>

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
