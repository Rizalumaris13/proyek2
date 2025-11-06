{{-- resources/views/dashboard.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard — Sistem Presensi Cerdas</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Optional: Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --brand:#0f3a80; /* header blue */
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
      -webkit-font-smoothing:antialiased;
    }

    /* Header */
    .topbar{
      background:var(--brand);
      color:#fff;
      padding:14px 22px;
      display:flex;
      align-items:center;
      justify-content:space-between;
    }
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{height:34px}
    .brand h5{margin:0;font-weight:700;letter-spacing:0.3px}

    /* Layout */
    .app{display:grid;grid-template-columns:260px 1fr;gap:28px;padding:28px;align-items:start}
    /* Sidebar card */
    .sidebar-card{
      background:var(--panel);
      border-radius:14px;
      padding:18px;
      box-shadow:0 6px 18px rgba(15,58,128,0.06);
      height:fit-content;
    }
    .sidebar .nav-link{color:#2d3b4a;font-weight:600;padding:10px;border-radius:8px;margin-bottom:6px}
    .sidebar .nav-link.active{background:linear-gradient(90deg,var(--accent),#2f63d6);color:#fff}

    /* content area */
    .content{
      min-height:70vh;
    }

    /* quick action cards */
    .quick-cards{display:flex;gap:18px;flex-wrap:wrap;margin-bottom:18px}
    .quick-card{
      background:var(--panel);
      border-radius:12px;
      padding:20px;
      width:220px;
      box-shadow:0 8px 24px rgba(2,6,23,0.06);
      display:flex;flex-direction:column;gap:12px;align-items:center;justify-content:center;
    }
    .quick-card .icon{width:54px;height:54px;border-radius:12px;display:grid;place-items:center;background:#eef5ff;color:var(--accent);font-size:20px}
    .quick-card h6{margin:0;font-weight:800}
    .quick-card p{margin:0;color:var(--muted);font-size:13px}

    /* chart panel */
    .chart-panel{
      margin-top:12px;
      background:var(--panel);
      border-radius:18px;padding:18px;
      box-shadow:0 10px 30px rgba(2,6,23,0.06);
    }

    /* footer */
    footer{padding:14px 28px;text-align:center;color:var(--muted);font-size:13px;background:transparent}

    /* responsive */
    @media (max-width:980px){
      .app{grid-template-columns:1fr;padding:18px}
      .quick-card{width:48%}
    }
    @media (max-width:560px){
      .quick-card{width:100%}
    }
  </style>
</head>
<body>

  {{-- Topbar --}}
  <header class="topbar">
    <div class="brand">
      {{-- ubah path logo jika perlu --}}
      <img src="{{ asset('images/logo.png') }}" alt="logo">
      <div>
        <h5>Sistem Presensi Cerdas</h5>
        <div style="font-size:12px;color:rgba(255,255,255,0.85)">SMA NU Tenajar Kidul</div>
      </div>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div style="color:rgba(255,255,255,0.9);font-weight:600">Halo, {{ Auth::user()->name ?? 'Admin' }}</div>
      <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">Logout</a>
      <form id="logout-form" action="{{Route ('logout')}}" method="POST" style="display:none">@csrf</form>
    </div>
  </header>

  {{-- Main app grid --}}
  <div class="app container-fluid">
    {{-- Sidebar --}}
    <aside class="sidebar">
      <div class="sidebar-card">
        <nav class="nav flex-column">
          <a class="nav-link active" href="#">Dashboard</a>
          <a class="nav-link" href="/presensi">Presensi Siswa</a>
          <a class="nav-link" href="/data-siswa">Data Siswa</a>
          <a class="nav-link" href="#">Statistik Kehadiran</a>
          <a class="nav-link" href="#">Profile</a>
        </nav>
      </div>
    </aside>

    {{-- Content --}}
    <main class="content">
      <h5 style="margin-bottom:18px;font-weight:700;color:#22314f">Aksi Cepat</h5>

      {{-- Quick action cards --}}
      <div class="quick-cards">
        <div class="quick-card">
          <div class="icon">👥</div>
          <h6>{{ $totalStudents ?? 120 }}</h6>
          <p>Total Siswa</p>
        </div>

        <div class="quick-card">
          <div class="icon">📋</div>
          <h6>{{ $todayPresent ?? 18 }}</h6>
          <p>Hadir Hari Ini</p>
        </div>

        <div class="quick-card">
          <div class="icon">⏱️</div>
          <h6>{{ $recentActivity ?? 5 }}</h6>
          <p>Aktivitas Terbaru</p>
        </div>
      </div>

      {{-- Chart panel --}}
      <section class="chart-panel">
        <h6 style="font-weight:700;margin-bottom:12px;color:#22314f">Grafik Total Kehadiran Siswa</h6>
        <div style="background:#fff;padding:18px;border-radius:12px">
          <canvas id="attendanceChart" height="220"></canvas>
        </div>
      </section>

    </main>
  </div>

  <footer>
    © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
  </footer>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <script>
    // contoh data — gantikan dengan data nyata dari controller jika perlu
    const labels = ['Januari','Februari','Maret','April','Mei','Juni'];
    const hadir = [7, 13, 14, 11, 10, 12];        // contoh hadir
    const sakit = [10, 5, 6, 6, 8, 9];           // contoh sakit/tidak hadir
    const izin = [6, 14, 13, 14, 17, 11];        // contoh izin/total lainnya

    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Hadir',
            data: hadir,
            backgroundColor: 'rgba(63,123,255,0.9)'
          },
          {
            label: 'Sakit',
            data: sakit,
            backgroundColor: 'rgba(255,159,64,0.85)'
          },
          {
            label: 'Izin',
            data: izin,
            backgroundColor: 'rgba(142,142,142,0.85)'
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        scales: {
          x: {
            grid: { display: false },
            ticks: { color: '#556677' }
          },
          y: {
            beginAtZero: true,
            ticks: { color: '#556677' },
            grid: { color: 'rgba(0,0,0,0.05)' }
          }
        },
        plugins: {
          legend: {
            labels: { color: '#334455', boxWidth:12, boxHeight:8 }
          },
          tooltip: {
            backgroundColor: '#0b2a55',
            titleColor: '#fff',
            bodyColor: '#fff',
          }
        }
      }
    });
  </script>

  <!-- Bootstrap bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
