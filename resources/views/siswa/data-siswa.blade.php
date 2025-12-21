<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Siswa — Sistem Presensi Cerdas</title>

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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fb;
            color: var(--brand);
            font-weight: 700;
            padding: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .btn-edit {
            background: none;
            border: none;
            color: var(--accent);
            cursor: pointer;
            font-size: 18px;
        }
        .btn-tambah {
        display: inline-block;
        background-color: #004080; /* warna biru tua */
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none; /* HILANGKAN GARIS BAWAH */
        font-weight: 500;
        }

        .btn-tambah:hover {
        background-color: #0059b3; /* efek hover */
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
        </div>
    </header>

    <div class="app">
        {{-- Sidebar --}}
        <aside>
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

        {{-- Konten Utama --}}
        <main>
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Data Siswa</h6>
    <form method="GET" action="{{ route('siswa.index') }}">
    <select name="kelas_id" class="form-select form-select-sm" 
            style="width:auto; display:inline-block" onchange="this.form.submit()">

        <option value="">Semua Kelas</option>

        @foreach ($kelasList as $kelas)
            <option value="{{ $kelas->id }}"
                {{ $filterKelas == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama_kelas}}
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
                                @if(Auth::user()->role === 'admin')
    <th>Edit</th>
@endif

                            </tr>
                        </thead>
                      <tbody>
                        @forelse ($dataSiswa as $index => $siswa)
                           <tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $siswa->nama }}</td>
    <td>{{ $siswa->nisn }}</td>
    <td>{{ $siswa->jenis_kelamin }}</td>
    <td>X</td>
    @if(Auth::user()->role === 'admin')
<td>
    <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning">
        ✏️ Edit
    </a>
</td>
@endif

</tr>

                        @empty
                            <tr>
                                 <td colspan="6" class="text-center text-muted">Belum ada data siswa</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

               @if(Auth::user()->role === 'admin')
    <div class="mt-3 text-end">
        <a href="{{ route('siswa.create') }}" class="btn-tambah">Tambah Siswa</a>
    </div>
@endif

            </div>
        </main>
    </div>

    <footer>
    © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
  </footer>
</body>

</html>
