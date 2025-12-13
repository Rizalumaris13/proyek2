<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Guru;

class KehadiranStatistikController extends Controller
{
    public function index(Request $request)
    {
        // Ambil guru yang login
        $guru = Guru::where('user_id', auth()->id())->first();
        
        if (!$guru) {
            return back()->with('error', 'Anda belum terdaftar sebagai guru.');
        }

        // Ambil hanya kelas yang diajar oleh guru ini
        $kelasGuru = $guru->kelas()->orderBy('nama_kelas', 'asc')->get();

        if ($kelasGuru->isEmpty()) {
            return back()->with('error', 'Anda belum memiliki kelas yang diampu.');
        }

        // Pilih kelas (default kelas pertama)
        $kelasId = $request->kelas_id ?? $kelasGuru->first()->id;
        
        // Pastikan kelas yang dipilih adalah kelas yang diajar guru
        $kelasDipilih = $kelasGuru->firstWhere('id', $kelasId);
        if (!$kelasDipilih) {
            $kelasId = $kelasGuru->first()->id;
            $kelasDipilih = $kelasGuru->first();
        }

        // Ambil siswa dari kelas yang dipilih
        $siswa = Siswa::where('kelas_id', $kelasId)
            ->orderBy('nama', 'asc')
            ->get();

        // Hitung rekap per bulan TAHUN INI
        $tahunIni = now()->year;
        $rekap = Kehadiran::selectRaw("
                MONTH(tanggal) as bulan,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status = 'izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN status = 'alfa'  THEN 1 ELSE 0 END) AS alfa
            ")
            ->whereIn('siswa_id', $siswa->pluck('id'))
            ->whereYear('tanggal', $tahunIni)
            ->where('guru_id', $guru->id) // Filter berdasarkan guru
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->keyBy('bulan');

        // Hitung total per status
        $total = [
            'hadir' => $rekap->sum('hadir'),
            'izin' => $rekap->sum('izin'),
            'sakit' => $rekap->sum('sakit'),
            'alfa' => $rekap->sum('alfa'),
        ];

        // Persentase kehadiran
        $totalSemua = array_sum($total);
        $persentase = $totalSemua > 0 ? [
            'hadir' => round(($total['hadir'] / $totalSemua) * 100, 1),
            'izin' => round(($total['izin'] / $totalSemua) * 100, 1),
            'sakit' => round(($total['sakit'] / $totalSemua) * 100, 1),
            'alfa' => round(($total['alfa'] / $totalSemua) * 100, 1),
        ] : [
            'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0
        ];

        return view('kehadiran.statistik', compact(
            'kelasGuru',  // Ganti nama dari kelasList
            'kelasId',
            'kelasDipilih',
            'rekap',
            'siswa',
            'total',
            'persentase',
            'tahunIni'
        ));
    }
}