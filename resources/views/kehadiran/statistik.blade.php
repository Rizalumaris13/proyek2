<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Statistik Kehadiran — Sistem Presensi Cerdas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
  --brand:#0f3a80;
  --accent:#3f7bff;
  --bg:#f5f6f8;
  --hadir:#4e73df;
  --izin:#f6c23e;
  --sakit:#1cc88a;
  --alfa:#e74a3b;
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

/* ================= PANEL ================= */
.panel{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(0,0,0,.06);
  margin-bottom:24px;
}

/* ================= STAT CARD ================= */
.stat-card{
  background:#fff;
  border-radius:12px;
  padding:20px;
  box-shadow:0 4px 12px rgba(0,0,0,.05);
  border-left:4px solid;
}
.stat-card.hadir{border-left-color:var(--hadir)}
.stat-card.izin{border-left-color:var(--izin)}
.stat-card.sakit{border-left-color:var(--sakit)}
.stat-card.alfa{border-left-color:var(--alfa)}

.stat-number{
  font-size:28px;
  font-weight:800;
}

/* ================= CHART ================= */
.chart-container{
  background:#fff;
  padding:26px;
  border-radius:18px;
  height:400px;
}

/* ================= FOOTER ================= */
footer{
  background:var(--brand);
  color:#fff;
  text-align:center;
  padding:10px;
  font-size:13px;
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

<!-- ================= APP ================= -->
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <nav class="nav flex-column">
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <a class="nav-link" href="/presensi">Presensi Siswa</a>
        <a class="nav-link" href="/siswa">Data Siswa</a>
        <a class="nav-link active" href="/kehadiran/statistik">Statistik Kehadiran</a>
        <a class="nav-link" href="/profil">Profil</a>
      </nav>
    </div>
  </aside>


        {{-- KONTEN UTAMA --}}
        <main>

            {{-- HEADER PANEL --}}
            <div class="panel mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Statistik Kehadiran Siswa</h5>
                    <div class="text-muted">
                        <i class="fas fa-calendar me-2"></i>Tahun {{ $tahunIni }}
                    </div>
                </div>
                
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex align-items-center gap-3">
                            <div>
                                <label class="form-label mb-1">Pilih Kelas:</label>
                                <select name="kelas_id" onchange="this.form.submit()" class="form-select" style="width:200px;">
                                    @foreach($kelasGuru as $k)
                                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- STATISTIK CARD --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card hadir">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Hadir</div>
                                <div class="stat-number">{{ $total['hadir'] }}</div>
                                <div class="stat-percent">{{ $persentase['hadir'] }}%</div>
                            </div>
                            <div class="icon-circle" style="background: #e3e7fd; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check" style="color: var(--hadir); font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card izin">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Izin</div>
                                <div class="stat-number">{{ $total['izin'] }}</div>
                                <div class="stat-percent">{{ $persentase['izin'] }}%</div>
                            </div>
                            <div class="icon-circle" style="background: #fef5e0; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope" style="color: var(--izin); font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card sakit">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Sakit</div>
                                <div class="stat-number">{{ $total['sakit'] }}</div>
                                <div class="stat-percent">{{ $persentase['sakit'] }}%</div>
                            </div>
                            <div class="icon-circle" style="background: #e0f7ef; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-thermometer" style="color: var(--sakit); font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card alfa">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Alfa</div>
                                <div class="stat-number">{{ $total['alfa'] }}</div>
                                <div class="stat-percent">{{ $persentase['alfa'] }}%</div>
                            </div>
                            <div class="icon-circle" style="background: #fde8e6; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times" style="color: var(--alfa); font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRAFIK BATANG --}}
            <div class="panel mb-4">
                <h6 class="fw-bold mb-3">Grafik Kehadiran Per Bulan ({{ $tahunIni }})</h6>
                <div class="chart-container">
                    <canvas id="chartKehadiran"></canvas>
                </div>
            </div>

            {{-- TABEL DETAIL --}}
            <div class="panel">
                <h6 class="fw-bold mb-3">Rekap Detail Per Bulan</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th width="120">Bulan</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Izin</th>
                                <th class="text-center">Sakit</th>
                                <th class="text-center">Alfa</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">% Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            @for ($i = 1; $i <= 12; $i++)
                                @php
                                    $hadir = $rekap[$i]->hadir ?? 0;
                                    $izin = $rekap[$i]->izin ?? 0;
                                    $sakit = $rekap[$i]->sakit ?? 0;
                                    $alfa = $rekap[$i]->alfa ?? 0;
                                    $totalBulan = $hadir + $izin + $sakit + $alfa;
                                    $persenHadir = $totalBulan > 0 ? round(($hadir / $totalBulan) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $namaBulan[$i] }}</td>
                                    <td class="text-center fw-bold" style="color: var(--hadir)">{{ $hadir }}</td>
                                    <td class="text-center" style="color: var(--izin)">{{ $izin }}</td>
                                    <td class="text-center" style="color: var(--sakit)">{{ $sakit }}</td>
                                    <td class="text-center" style="color: var(--alfa)">{{ $alfa }}</td>
                                    <td class="text-center fw-bold">{{ $totalBulan }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $persenHadir >= 80 ? 'bg-success' : ($persenHadir >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $persenHadir }}%
                                        </span>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <footer>
        © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
    </footer>

    {{-- CHART.JS - DIUBAH JADI KOTAK --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartKehadiran');
            
            // Data dari controller
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const hadir = [
                @for($i = 1; $i <= 12; $i++) 
                    {{ $rekap[$i]->hadir ?? 0 }},
                @endfor
            ];
            
            const izin = [
                @for($i = 1; $i <= 12; $i++) 
                    {{ $rekap[$i]->izin ?? 0 }},
                @endfor
            ];
            
            const sakit = [
                @for($i = 1; $i <= 12; $i++) 
                    {{ $rekap[$i]->sakit ?? 0 }},
                @endfor
            ];
            
            const alfa = [
                @for($i = 1; $i <= 12; $i++) 
                    {{ $rekap[$i]->alfa ?? 0 }},
                @endfor
            ];
            
            // Buat chart dengan legend KOTAK
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: hadir,
                            backgroundColor: 'rgba(63,123,255,0.9)'
                        },
                        {
                            label: 'Izin',
                            data: izin,
                            backgroundColor: 'rgba(246,194,62,0.85)'
                        },
                        {
                            label: 'Sakit',
                            data: sakit,
                            backgroundColor: 'rgba(28,200,138,0.85)'
                        },
                        {
                            label: 'Alfa',
                            data: alfa,
                            backgroundColor: 'rgba(255,80,80,0.85)'
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
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
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
        });
    </script>
<script>
function toggleSidebar(){
  document.body.classList.toggle('sidebar-open');
}
</script>
</body>
</html>