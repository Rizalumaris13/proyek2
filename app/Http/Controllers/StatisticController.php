<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;

class AttendanceStatisticController extends Controller
{
    public function index(Request $request)
    {
        
        $kelas = $request->get();
        $bulan = $request->get();

        // untuk chart
        $labels = $students->pluck('name');
        $hadir = $students->pluck('hadir');
        $izin = $students->pluck('izin');
        $sakit = $students->pluck('sakit');
        $alpha = $students->pluck('alpha');

        return view('attendance.index', compact('students', 'labels', 'hadir', 'izin', 'sakit', 'alpha', 'kelas', 'bulan'));
    }
}