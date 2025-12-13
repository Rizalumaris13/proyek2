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
        }

        body {
            background: var(--bg);
            font-family: 'Inter', sans-serif;
            margin: 0;
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

        .sidebar-card, .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 58, 128, 0.06);
        }

        .sidebar-card {
            padding: 22px;
        }

        .panel {
            padding: 22px;
            margin-bottom: 20px;
        }

        .nav-link {
            font-weight: 600;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 6px;
            text-decoration: none;
            display: block;
        }

        .nav-link.active {
            background: linear-gradient(90deg, var(--accent), #2f63d6);
            color: #fff;
        }

        .camera-box {
            width: 100%;
            height: 280px;
            border: 2px dashed #c7c7c7;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #7b8794;
            font-size: 18px;
        }

        /* ===== RAPIHAN TAMBAHAN ===== */
        .kelas-item {
            background: #f8f9fb;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 15px;
        }

        .kelas-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .kelas-title {
            font-weight: 700;
            color: #333;
            font-size: 16px;
            margin: 0;
        }

        .kelas-info {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .kelas-mapel {
            color: #888;
            font-size: 13px;
            margin-top: 5px;
        }

        .kelas-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .kelas-actions .btn {
            font-size: 13px;
            padding: 6px 14px;
            border-radius: 6px;
        }

        .date-info {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .panel-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            color: #856404;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
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
        <div class="text-white fw-semibold">Halo, {{ auth()->user()->name }}</div>
        <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</header>

<div class="app">

    {{-- SIDEBAR --}}
    <aside>
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

    {{-- CONTENT --}}
    <main>

        {{-- PANEL KAMERA --}}
        <div class="panel">
            <div class="panel-title">Presensi Otomatis</div>
            <div class="camera-box">📷 Kamera belum terhubung</div>
        </div>

        {{-- PANEL KELAS & DATA --}}
        <div class="panel">
            <div class="panel-title">Kelas Yang Anda Ampu</div>
            <div class="date-info">{{ now()->translatedFormat('l, d F Y') }}</div>

            {{-- CEK JIKA GURU TIDAK PUNYA KELAS --}}
            @if (!isset($kelas) || $kelas->isEmpty())
                <div class="alert-warning">
                    Anda belum memiliki kelas yang diampu.
                </div>
            @else
                @foreach ($kelas as $k)
                    <div class="kelas-item">
                        <div class="kelas-header">
                            <div>
                                <div class="kelas-title">{{ $k->nama_kelas }}</div>
                                <div class="kelas-info">
                                    Jumlah siswa: {{ $k->siswa_count ?? $k->siswa()->count() }} orang
                                </div>
                            </div>
                        </div>

                        {{-- MAPEL --}}
                        <div class="kelas-mapel">
                            Mapel: 
                            @if ($k->gurus && $k->gurus->count())
                                @foreach ($k->gurus as $g)
                                    <span>{{ $g->mapel }}</span>
                                    @if (!$loop->last), @endif
                                @endforeach
                            @else
                                <span class="text-muted">Belum ada guru/mapel</span>
                            @endif
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="kelas-actions">
                            <a href="{{ route('kehadiran.index', ['kelas_id' => $k->id]) }}"
                               class="btn btn-secondary btn-sm">
                                Presensi Manual
                            </a>
                            <button class="btn btn-primary btn-sm">
                                Generate Face Recognition
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </main>

</div>
 <footer>
    © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
  </footer>
</body>
</html>