<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Guru;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil guru yang login
        $user = auth()->user();
        $guru = $user->guru ?? Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return back()->with('error', 'Anda belum terdaftar sebagai guru.');
        }

        // Ambil kelas yang diajar guru
        $kelasGuru = $guru->kelas;
        
        if ($kelasGuru->isEmpty()) {
            return view('dashboard', [
                'totalStudents' => 0,
                'todayPresent' => 0,
                'recentActivity' => 0,
                'kelasCount' => 0,
                'months' => [],
                'hadir' => [],
                'sakit' => [],
                'izin' => [],
                'alfa' => [],
                'kelasGuru' => $kelasGuru,
                'recentAttendances' => collect(),
                'message' => 'Anda belum memiliki kelas yang diampu.'
            ]);
        }

        // Ambil semua siswa dari kelas yang diajar
        $kelasIds = $kelasGuru->pluck('id');
        $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');

        // Total siswa di kelas yang diajar
        $totalStudents = $siswaIds->count();

        // Hadir hari ini
        $today = now()->toDateString();
        $todayPresent = Kehadiran::where('tanggal', $today)
            ->where('status', 'hadir')
            ->whereIn('siswa_id', $siswaIds)
            ->where('guru_id', $guru->id)
            ->count();

        // Aktivitas terbaru (kehadiran hari ini)
        $recentActivity = Kehadiran::where('tanggal', $today)
            ->whereIn('siswa_id', $siswaIds)
            ->where('guru_id', $guru->id)
            ->count();

        // Jumlah kelas yang diajar
        $kelasCount = $kelasGuru->count();

        // Data kehadiran per bulan TAHUN INI
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $tahunIni = now()->year;
        $hadir = $sakit = $izin = $alfa = [];

        for ($i = 1; $i <= 12; $i++) {
            $hadir[] = Kehadiran::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahunIni)
                ->where('status', 'hadir')
                ->whereIn('siswa_id', $siswaIds)
                ->where('guru_id', $guru->id)
                ->count();
                
            $sakit[] = Kehadiran::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahunIni)
                ->where('status', 'sakit')
                ->whereIn('siswa_id', $siswaIds)
                ->where('guru_id', $guru->id)
                ->count();
                
            $izin[] = Kehadiran::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahunIni)
                ->where('status', 'izin')
                ->whereIn('siswa_id', $siswaIds)
                ->where('guru_id', $guru->id)
                ->count();
                
            $alfa[] = Kehadiran::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahunIni)
                ->where('status', 'alfa')
                ->whereIn('siswa_id', $siswaIds)
                ->where('guru_id', $guru->id)
                ->count();
        }

        // Kehadiran terbaru (5 data terakhir)
        $recentAttendances = Kehadiran::with('siswa')
            ->whereIn('siswa_id', $siswaIds)
            ->where('guru_id', $guru->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'todayPresent',
            'recentActivity',
            'kelasCount',
            'months',
            'hadir',
            'sakit',
            'izin',
            'alfa',
            'kelasGuru',
            'recentAttendances',
            'tahunIni'
        ));
    }
}