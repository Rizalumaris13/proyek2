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
    .content{min-height:70vh;}

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

    footer {
            background: var(--brand);
            color: #fff;
            text-align: center;
            padding: 10px;
            font-size: 13px;
        }
  </style>
</head>
<body>

  {{-- Topbar --}}
  <header class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
      <div>
        <h5>Sistem Presensi Cerdas</h5>
        <div style="font-size:12px;color:rgba(255,255,255,0.85)">SMA NU Tenajar Kidul</div>
      </div>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div style="color:rgba(255,255,255,0.9);font-weight:600">
        Halo, {{ Auth::user()->name ?? 'Admin' }}
      </div>

      <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">
        Logout
      </a>

      <form id="logout-form" action="{{ Route('logout') }}" method="POST" style="display:none">
        @csrf
      </form>
    </div>
  </header>

  {{-- Main app grid --}}
  <div class="app container-fluid">
    
    {{-- Sidebar --}}
    <aside class="sidebar">
      <div class="sidebar-card">
        <nav class="nav flex-column">
          <a class="nav-link active" href="/dashboard">Dashboard</a>
          <a class="nav-link" href="/presensi">Presensi Siswa</a>
          <a class="nav-link" href="/siswa">Data Siswa</a>
          <a class="nav-link" href="/kehadiran/statistik">Statistik Kehadiran</a>
          <a class="nav-link" href="/profil">Profil</a>
        </nav>
      </div>
    </aside>

    {{-- Content --}}
    <main class="content">
      <h5 style="margin-bottom:18px;font-weight:700;color:#22314f">Aksi Cepat</h5>

      {{-- Quick Cards --}}
      <div class="quick-cards">
        <div class="quick-card">
          <div class="icon">👥</div>
          <h6>{{ $totalStudents }}</h6>
          <p>Total Siswa</p>
        </div>

        <div class="quick-card">
          <div class="icon">📋</div>
          <h6>{{ $todayPresent }}</h6>
          <p>Hadir Hari Ini</p>
        </div>

        <div class="quick-card">
          <div class="icon">⏱️</div>
          <h6>{{ $recentActivity }}</h6>
          <p>Aktivitas Terbaru</p>
        </div>
      </div>

      {{-- Chart --}}
      <section class="chart-panel">
        <h6 style="font-weight:700;margin-bottom:12px;color:#22314f">
          Grafik Total Kehadiran Siswa
        </h6>

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('attendanceChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: @json($months),
      datasets: [
        {
          label:'Hadir',
          data:@json($hadir),
          backgroundColor:'rgba(63,123,255,0.9)'
        },
        {
          label:'Izin',
          data:@json($izin),
          backgroundColor:'rgba(246,194,62,0.85)'
        },
        {
          label:'Sakit',
          data:@json($sakit),
          backgroundColor:'rgba(28,200,138,0.85)'
        },
        {
          label:'Alfa',
          data:@json($alfa),
          backgroundColor:'rgba(255,80,80,0.85)'
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      scales: {
        y: { 
          beginAtZero: true,
          ticks: {
            precision: 0
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 12,        // Lebar kotak di legend
            padding: 15,
            usePointStyle: true, // Gunakan pointStyle
            pointStyle: 'rect',  // INI YANG DIUBAH: 'rect' untuk kotak
            font: {
              size: 13,
              family: "'Inter', sans-serif",
              weight: '500'
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(15, 58, 128, 0.9)',
          titleFont: {
            family: "'Inter', sans-serif",
            size: 12
          },
          bodyFont: {
            family: "'Inter', sans-serif",
            size: 13
          },
          padding: 12,
          cornerRadius: 6
        }
      }
    }
  });
</script>
</body>
</html>
