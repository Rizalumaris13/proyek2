<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Siswa</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        /* ===== HEADER ===== */
        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border: none;
        }

        .header img {
            height: 55px;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
        }

        .school-sub {
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 20px 0 10px;
            text-transform: uppercase;
        }

        /* ===== INFO ===== */
        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
            font-size: 11px;
        }

        /* ===== TABLE ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th,
        table.data td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        table.data th {
            background: #f2f2f2;
            font-weight: bold;
        }

        table.data td.nama {
            text-align: left;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <table>
            <tr>
                <td width="80">
                    <img src="{{ public_path('images/lo.png') }}">
                </td>
                <td>
                    <div class="school-name">SMA NU TENAJAR KIDUL</div>
                    <div class="school-sub">
                        Sistem Presensi Cerdas Berbasis Web
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- JUDUL --}}
    <div class="title">
        Rekap Absensi Siswa
    </div>

    {{-- INFO --}}
    <div class="info">
        <table>
            <tr>
                <td width="50%">Tahun Ajaran : {{ date('Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- TABEL DATA --}}
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="10%">Hadir</th>
                <th width="10%">Izin</th>
                <th width="10%">Sakit</th>
                <th width="10%">Alfa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $i => $row)
            <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->nama_kelas }}</td>
            <td>{{ $row->hadir }}</td>
            <td>{{ $row->izin }}</td>
            <td>{{ $row->sakit }}</td>
            <td>{{ $row->alfa }}</td>
        </tr>
            @endforeach
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>
