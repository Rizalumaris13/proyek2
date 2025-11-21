<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Pengguna – Sistem Presensi Cerdas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f6f8; }

        /* HEADER */
        .topbar {
            background: #0f3a80; 
            color: #fff; 
            padding: 14px 22px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }

        /* LAYOUT */
        .app {
            display: flex;
            gap: 24px;
            padding: 24px;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
        }
         .sidebar-card {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 58, 128, 0.06);
            height: fit-content;
        }
        .sidebar-card a {
            display: block;
            padding: 10px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
        }
        .sidebar-card a.active,
        .sidebar-card a:hover {
            background: #e6f0ff;
            color: #2f63d6;
        }

        /* CARD PROFIL UTAMA */
        .profile-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            padding: 30px;
            flex-grow: 1;
        }

        /* Ikon Profil */
        .profile-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e9eef7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #0f3a80;
            margin: auto;
        }

        /* Input seperti mockup */
        input.form-control {
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-shadow: none;
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
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">Logout</a>
            <form id="logout-form" action="{{Route ('logout')}}" method="POST" style="display:none">@csrf</form>
        </div>
    </header>

    <!-- LAYOUT -->
    <div class="app">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-card">
                <a href="/dashboard">Dashboard</a>
                <a href="/presensi">Presensi Siswa</a>
                <a href="/siswa">Data Siswa</a>
                <a href="#">Statistik Kehadiran</a>
                <a class="active" href="/profil">Profil</a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="profile-card">
            <h5 class="fw-bold mb-4 text-primary">Profil Pengguna</h5>

            <div class="row">
                <div class="col-md-3">
                    <div class="profile-icon">👤</div>
                </div>

                <div class="col-md-9">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="Guru Aida" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" class="form-control" value="aida.guru@smanu.sch.id" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="Guru/Admin" disabled>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Tanggal bergabung</label>
                            <input type="text" class="form-control" value="15 Januari 2023" disabled>
                        </div>
                        <button type="button" class="btn btn-success px-3 rounded-pill">Edit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
