
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;

class AttendanceStatisticController extends Controller
{
    public function index(Request $request)
    {
        // data dummy (nanti bisa diganti dari database)
        $kelas = $request->get('kelas', 'X MIPA 1');
        $bulan = $request->get('bulan', 'Oktober');

        $students = collect([
            ['name' => 'Afiza Putri', 'nisn' => '1912212122', 'gender' => 'P', 'hadir' => 20, 'izin' => 2, 'sakit' => 1, 'alpha' => 0],
            ['name' => 'Agatha Chelsea', 'nisn' => '1921222112', 'gender' => 'P', 'hadir' => 21, 'izin' => 1, 'sakit' => 0, 'alpha' => 1],
            ['name' => 'Akard Pradana', 'nisn' => '1934268342', 'gender' => 'L', 'hadir' => 19, 'izin' => 0, 'sakit' => 1, 'alpha' => 2],
            ['name' => 'Anggita Nirwana', 'nisn' => '1912244124', 'gender' => 'P', 'hadir' => 22, 'izin' => 1, 'sakit' => 0, 'alpha' => 0],
            ['name' => 'Azizas Amira', 'nisn' => '1912448222', 'gender' => 'P', 'hadir' => 20, 'izin' => 2, 'sakit' => 1, 'alpha' => 0],
        ]);

        // untuk chart
        $labels = $students->pluck('name');
        $hadir = $students->pluck('hadir');
        $izin = $students->pluck('izin');
        $sakit = $students->pluck('sakit');
        $alpha = $students->pluck('alpha');

        return view('attendance.index', compact('students', 'labels', 'hadir', 'izin', 'sakit', 'alpha', 'kelas', 'bulan'));
    }
}