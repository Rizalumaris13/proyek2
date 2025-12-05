<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kehadiran;

class DashboardController extends Controller
{
    public function index()
    {
        // Total siswa
        $totalStudents = Siswa::count();

        // Hadir hari ini
        $todayPresent = Kehadiran::whereDate('created_at', now())
            ->where('status', 'Hadir')
            ->count();

        // Aktivitas terbaru (jumlah kehadiran hari ini)
        $recentActivity = Kehadiran::whereDate('created_at', now())->count();

        // --- Data grafik ---
        // Ambil total kehadiran per bulan
        // Kamu bisa ubah sesuai struktur tabelmu: status = Hadir / Sakit / Izin
        $months = ['Januari','Februari','Maret','April','Mei','Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $hadir = [];
        $sakit = [];
        $izin = [];
        $alfa = [];

        for ($i = 1; $i <= 12; $i++) {
            $hadir[] = Kehadiran::whereMonth('created_at', $i)->where('status','Hadir')->count();
            $sakit[] = Kehadiran::whereMonth('created_at', $i)->where('status','Sakit')->count();
            $izin[]  = Kehadiran::whereMonth('created_at', $i)->where('status','Izin')->count();
            $alfa[]  = Kehadiran::whereMonth('created_at', $i)->where('status','Alfa')->count();
        }

        return view('dashboard', compact(
            'totalStudents',
            'todayPresent',
            'recentActivity',
            'months',
            'hadir',
            'sakit',
            'izin',
            'alfa'
        ));
    }
}
