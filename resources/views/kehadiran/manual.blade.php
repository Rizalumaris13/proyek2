<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kehadiran Manual — Sistem Presensi Cerdas</title>

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

        .sidebar-card,
        .panel {
            background: #fff;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .sidebar-card {
            padding: 20px;
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

        .attendance-group {
            display: flex;
            gap: 10px;
        }

        .attendance-option input {
            display: none;
        }

        .attendance-option label {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            border: 2px solid #d0d5dd;
            cursor: pointer;
            transition: 0.2s;
            background: #fff;
            color: #555;
        }

        .attendance-option input:checked+label {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .attendance-option label:hover {
            border-color: var(--accent);
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" style="height:34px;">
            <div>
                <h5 class="m-0 fw-bold">Sistem Presensi Cerdas</h5>
                <small>SMA NU Tenajar Kidul</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="fw-semibold text-white-50">
                Halo, {{ Auth::user()->name }}
            </div>
            <a href="#"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                class="text-white">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </header>

    {{-- LAYOUT --}}
    <div class="app">

        {{-- SIDEBAR --}}
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

        {{-- CONTENT --}}
        <main>

            {{-- Header Panel --}}
            <div class="panel mb-4">
                <h5 class="fw-bold">Kehadiran Manual</h5>
                <div class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</div>
                <div class="mt-2 fw-semibold">
                    Kelas: <span class="text-primary">{{ $kelas->nama_kelas ?? '-' }}</span>
                </div>
            </div>

            {{-- Form Absen Manual --}}
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
                            @foreach ($siswa as $i => $s)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $s->nama }}</td>
                                    <td>
                                        <div class="attendance-group">

                                            @php
                                                $options = [
                                                    'hadir' => 'H',
                                                    'sakit' => 'S',
                                                    'izin'  => 'I',
                                                    'alfa'  => 'A'
                                                ];
                                            @endphp

                                            @foreach ($options as $value => $label)
                                                <div class="attendance-option">
                                                    <input 
                                                        type="radio"
                                                        id="{{ $value . $s->id }}"
                                                        name="kehadiran[{{ $s->id }}]"
                                                        value="{{ $value }}"
                                                        @if($value === 'hadir') checked @endif
                                                    >
                                                    <label for="{{ $value . $s->id }}">{{ $label }}</label>
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

</body>

</html>
