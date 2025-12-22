<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<h3>REKAP ABSENSI SISWA</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alfa</th>
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

</body>
</html>
