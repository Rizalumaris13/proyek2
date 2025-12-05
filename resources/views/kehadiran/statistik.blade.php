<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Statistik Kehadiran — Sistem Presensi Cerdas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #0f3a80;
            --accent: #3f7bff;
            --bg: #f5f6f8;
        }

        body {
            background: var(--bg);
            font-family: 'Inter', sans-serif;
        }

        .topbar {
            background: var(--brand);
            color: #fff;
            padding: 14px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .app {
            padding: 28px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 24px;
        }

        .sidebar-card {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 58, 128, 0.06);
            height: fit-content;
        }

        .nav-link {
            font-weight: 600;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .nav-link.active {
            background: linear-gradient(90deg, var(--accent), #2f63d6);
            color: #fff;
        }

        .panel {
            background: #fff;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
            margin-bottom: 24px;
        }

        th {
            background: #f8f9fb;
            color: var(--brand);
            font-weight: 700;
        }

        footer {
            background: var(--brand);
            color: #fff;
            text-align: center;
            padding: 10px;
            font-size: 13px;
        }

        .chart-card {
            background: #fff;
            padding: 26px;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="logo" style="height:34px;">
            <div>
                <h5 class="m-0 fw-bold">Sistem Presensi Cerdas</h5>
                <small>SMA NU Tenajar Kidul</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div style="color:rgba(255,255,255,0.9);font-weight:600">
                Halo, {{ Auth::user()->name ?? 'Admin' }}
            </div>
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">Logout</a>
            <form id="logout-form" action="{{Route ('logout')}}" method="POST" style="display:none">@csrf</form>
        </div>
    </header>


    <div class="app">

        {{-- SIDEBAR --}}
        <aside>
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

            <div class="panel mb-4">
                <h6 class="fw-bold mb-3">Statistik Kehadiran Siswa</h6>

                <form method="GET">
                    <select name="kelas_id" onchange="this.form.submit()" class="form-select" style="width:160px;">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- GRAFIK --}}
            <div class="chart-card mb-4">
                <canvas id="chartKehadiran"></canvas>
            </div>

            {{-- TABEL --}}
            <div class="panel">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alfa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 12; $i++)
                            <tr>
                                <td>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</td>
                                <td>{{ $rekap[$i]->hadir ?? 0 }}</td>
                                <td>{{ $rekap[$i]->izin ?? 0 }}</td>
                                <td>{{ $rekap[$i]->sakit ?? 0 }}</td>
                                <td>{{ $rekap[$i]->alfa ?? 0 }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

        </main>

    </div>

    <footer>
        © 2025 SMA NU Tenajar Kidul. All rights reserved.
    </footer>


    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('chartKehadiran');

    const data = {
        labels: ['JAN','FEB','MAR','APR','MEI','JUN','JUL','AGU','SEP','OKT','NOV','DES'],
        datasets: [
            {
                label: 'Hadir',
                data: [
                    @for($i=1;$i<=12;$i++) {{ $rekap[$i]->hadir ?? 0 }}, @endfor
                ],
                backgroundColor: '#4e73df',
                borderRadius: 8,
                maxBarThickness: 28
            },
            {
                label: 'Izin',
                data: [
                    @for($i=1;$i<=12;$i++) {{ $rekap[$i]->izin ?? 0 }}, @endfor
                ],
                backgroundColor: '#f6c23e',
                borderRadius: 8,
                maxBarThickness: 28
            },
            {
                label: 'Sakit',
                data: [
                    @for($i=1;$i<=12;$i++) {{ $rekap[$i]->sakit ?? 0 }}, @endfor
                ],
                backgroundColor: '#1cc88a',
                borderRadius: 8,
                maxBarThickness: 28
            },
            {
                label: 'Alfa',
                data: [
                    @for($i=1;$i<=12;$i++) {{ $rekap[$i]->alfa ?? 0 }}, @endfor
                ],
                backgroundColor: '#e74a3b',
                borderRadius: 8,
                maxBarThickness: 28
            }
        ]
    };

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,  // hapus angka desimal
                        callback: function (value) {
                            return Number.isInteger(value) ? value : null;
                        },
                        font: { size: 12 }
                    },
                    grid: {
                        color: "rgba(0,0,0,0.06)"
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 12 }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 18,
                        padding: 16,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
</script>


</body>
</html>
