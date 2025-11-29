<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Kehadiran;

class KehadiranStatistikController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

        if ($kelasList->isEmpty()) {
            return back()->with('error', 'Belum ada data kelas.');
        }

        $kelasId = $request->kelas_id ?? $kelasList->first()->id;

        $siswa = Siswa::where('kelas_id', $kelasId)
            ->orderBy('nama', 'asc')
            ->get();

        $rekap = Kehadiran::selectRaw("
                MONTH(tanggal) as bulan,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status = 'izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN status = 'alfa'  THEN 1 ELSE 0 END) AS alfa
            ")
            ->whereIn('siswa_id', $siswa->pluck('id'))
            ->groupBy('bulan')
            ->get()
            ->keyBy('bulan');

        return view('kehadiran.statistik', compact(
            'kelasList',
            'kelasId',
            'rekap',
            'siswa'
        ));
    }
}
