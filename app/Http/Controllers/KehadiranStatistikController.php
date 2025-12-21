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
    $user = auth()->user();
    $tahunIni = now()->year;

    /**
     * =========================
     * ADMIN
     * =========================
     */
    if ($user->role === 'admin') {

        $kelasGuru = Kelas::orderBy('nama_kelas')->get();

        $kelasId = $request->kelas_id ?? optional($kelasGuru->first())->id;
        $kelasDipilih = $kelasGuru->firstWhere('id', $kelasId);

        $siswa = $kelasDipilih
            ? Siswa::where('kelas_id', $kelasDipilih->id)->get()
            : collect();

        $rekap = $kelasDipilih
            ? Kehadiran::selectRaw("
                MONTH(tanggal) as bulan,
                SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN status='alfa' THEN 1 ELSE 0 END) AS alfa
              ")
              ->whereIn('siswa_id', $siswa->pluck('id'))
              ->whereYear('tanggal', $tahunIni)
              ->groupBy('bulan')
              ->orderBy('bulan')
              ->get()
              ->keyBy('bulan')
            : collect();

        $total = [
            'hadir' => $rekap->sum('hadir'),
            'izin'  => $rekap->sum('izin'),
            'sakit' => $rekap->sum('sakit'),
            'alfa'  => $rekap->sum('alfa'),
        ];

        $totalSemua = array_sum($total);
        $persentase = $totalSemua > 0 ? [
            'hadir' => round($total['hadir'] / $totalSemua * 100, 1),
            'izin'  => round($total['izin']  / $totalSemua * 100, 1),
            'sakit' => round($total['sakit'] / $totalSemua * 100, 1),
            'alfa'  => round($total['alfa']  / $totalSemua * 100, 1),
        ] : ['hadir'=>0,'izin'=>0,'sakit'=>0,'alfa'=>0];

        return view('kehadiran.statistik', compact(
            'kelasGuru','kelasId','kelasDipilih',
            'rekap','siswa','total','persentase','tahunIni'
        ));
    }

    /**
     * =========================
     * GURU
     * =========================
     */
    $guru = Guru::where('user_id', $user->id)->first();

    if (!$guru) {
        return back()->with('error', 'Data guru tidak ditemukan.');
    }

    $kelasGuru = $guru->kelas()->orderBy('nama_kelas')->get();

    if ($kelasGuru->isEmpty()) {
        return back()->with('error', 'Anda belum memiliki kelas yang diampu.');
    }

    $kelasId = $request->kelas_id ?? $kelasGuru->first()->id;
    $kelasDipilih = $kelasGuru->firstWhere('id', $kelasId);

    $siswa = Siswa::where('kelas_id', $kelasDipilih->id)->get();

    $rekap = Kehadiran::selectRaw("
        MONTH(tanggal) as bulan,
        SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) AS hadir,
        SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) AS izin,
        SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) AS sakit,
        SUM(CASE WHEN status='alfa' THEN 1 ELSE 0 END) AS alfa
    ")
    ->whereIn('siswa_id', $siswa->pluck('id'))
    ->whereYear('tanggal', $tahunIni)
    ->where('guru_id', $guru->id)
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->get()
    ->keyBy('bulan');

    $total = [
        'hadir' => $rekap->sum('hadir'),
        'izin'  => $rekap->sum('izin'),
        'sakit' => $rekap->sum('sakit'),
        'alfa'  => $rekap->sum('alfa'),
    ];

    $totalSemua = array_sum($total);
    $persentase = $totalSemua > 0 ? [
        'hadir' => round($total['hadir'] / $totalSemua * 100, 1),
        'izin'  => round($total['izin']  / $totalSemua * 100, 1),
        'sakit' => round($total['sakit'] / $totalSemua * 100, 1),
        'alfa'  => round($total['alfa']  / $totalSemua * 100, 1),
    ] : ['hadir'=>0,'izin'=>0,'sakit'=>0,'alfa'=>0];

    return view('kehadiran.statistik', compact(
        'kelasGuru','kelasId','kelasDipilih',
        'rekap','siswa','total','persentase','tahunIni'
    ));
}
}
