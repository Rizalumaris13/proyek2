<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Presensi Siswa — Sistem Presensi Cerdas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #0f3a80;
            --accent: #3f7bff;
            --bg: #f5f6f8;
            --text-muted: #7b8794;
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

        /* Ruang kamera */
        .camera-box {
            width: 100%;
            height: 280px;
            border: 2px dashed #c7c7c7;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-muted);
            font-size: 18px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="logo" style="height:34px;">
            <div>
                <h5 class="m-0 fw-bold">Sistem Presensi Cerdas</h5>
                <small>SMA NU Tenajar Kidul</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
      <div style="color:rgba(255,255,255,0.9);font-weight:600">Halo, {{ Auth::user()->name ?? 'Admin' }}</div>
      <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">Logout</a>
      <form id="logout-form" action="{{Route ('logout')}}" method="POST" style="display:none">@csrf</form>
    </div>
    </header>

    {{-- Layout --}}
    <div class="app">
        {{-- Sidebar --}}
        <aside>
            <div class="sidebar-card">
                <nav class="nav flex-column">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                    <a class="nav-link active" href="/presensi">Presensi Siswa</a>
                    <a class="nav-link" href="/siswa">Data Siswa</a>
                    <a class="nav-link" href="#">Statistik Kehadiran</a>
                    <a class="nav-link" href="/profil">Profil</a>
                </nav>
            </div>
        </aside>

        {{-- Content --}}
        <main>
            {{-- Panel Kamera (placeholder) --}}
            <div class="panel">
                <h6 class="fw-bold mb-3">Presensi Otomatis</h6>
                <div class="camera-box">
                    📷 Kamera belum terhubung
                </div>
            </div>

            {{-- Panel Data Siswa --}}
            <div class="panel">
                <h6 class="fw-bold mb-2">Data Siswa Terdeteksi</h6>
                <p class="text-muted">Senin, 17 Mei 2023</p>

                <div class="p-3 rounded" style="background:#f8f9fb;border:1px solid #eee;">
                    <strong>Ilmu Pengetahuan Alam</strong><br>
                    08:00 - 10:00 WIB | Pertemuan ke-1<br>
                    Siswa: 35 | Kelas: XII IPA 1
                    <div class="mt-3 d-flex gap-2">
                        <button class="btn btn-primary btn-sm">Generate Face Recognition</button>
                        <a href="/kehadiran/manual" class="btn btn-secondary">Presensi Manual</a>
                        <button class="btn btn-outline-secondary btn-sm">Validasi</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
