<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa' }} — Sistem Presensi Cerdas</title>

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
        }
        .textarea-beauty {
    resize: vertical;
    border-radius: 12px;
    border: 1.5px solid #d0d7e2;
    padding: 12px 14px;
    font-size: 15px;
    line-height: 1.5;
    transition: all 0.25s ease;
    background: #fdfdfd;
}

.textarea-beauty:focus {
    border-color: #3f7bff;
    box-shadow: 0 0 0 4px rgba(63, 123, 255, 0.15);
    background: white;
}

    </style>
</head>

<body>
    {{-- Header --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/lo.png') }}" alt="logo" style="height:34px;">
            <div>
                <h5 class="m-0 fw-bold">Sistem Presensi Cerdas</h5>
                <small>SMA NU Tenajar Kidul</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div style="color:rgba(255,255,255,0.9);font-weight:600">Halo, {{ Auth::user()->name ?? 'Admin' }}</div>
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">Logout</a>
            <form id="logout-form" action="{{ Route('logout') }}" method="POST" style="display:none">@csrf</form>
        </div>
    </header>

    {{-- Layout --}}
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

        {{-- Content --}}
        <main>
            <div class="panel">
                <h5 class="fw-bold mb-3">
                    {{ isset($siswa) ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}
                </h5>

                <form method="POST" action="{{ isset($siswa) ? route('siswa.update', $siswa->id) : route('siswa.store') }}">
                    @csrf
                    @if(isset($siswa))
                        @method('PUT')
                    @endif

                    {{-- Nama Siswa --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="{{ old('nama', $siswa->nama ?? '') }}"
                            placeholder="Masukkan nama siswa" required>
                    </div>

                    {{-- NISN --}}
                    <div class="mb-3">
                        <label for="nisn" class="form-label">Nomor NISN</label>
                        <input type="text" class="form-control" id="nisn" name="nisn"
                            value="{{ old('nisn', $siswa->nisn ?? '') }}"
                            placeholder="Masukkan NISN" required>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="mb-3">
                        <label for="jk" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jk" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                   {{-- Kelas --}}
<div class="mb-4">
    <label for="kelas" class="form-label">Kelas</label>
    <select name="kelas_id" class="form-select">

        @foreach ($kelasList as $kelas)
            <option value="{{ $kelas->id }}"
                {{ (isset($siswa) && $siswa->kelas_id == $kelas->id) ? 'selected' : '' }}>
                {{ $kelas->nama_kelas }}
            </option>
        @endforeach
    </select>
</div>
                    {{-- Alamat --}}
<div class="mb-3">
    <label for="alamat" class="form-label">Alamat</label>
    <textarea 
        id="alamat" 
        name="alamat" 
        class="form-control textarea-beauty" 
        rows="3"
        placeholder="Masukkan alamat lengkap siswa..."
        required>{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
</div>

                    {{-- Tombol --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($siswa) ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <a href="{{ url('/siswa') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
document.addEventListener('input', function (e) {
    if (e.target.matches('.textarea-beauty')) {
        e.target.style.height = 'auto';
        e.target.style.height = (e.target.scrollHeight) + 'px';
    }
});
</script>

</body>
</html>
