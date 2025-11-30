<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Guru;

class KehadiranController extends Controller
{
    public function index(Request $request)
{
    $guru = Guru::where('user_id', auth()->id())->first();

    if (!$guru) {
        return back()->with('error', 'Data guru tidak ditemukan.');
    }

    // guru->kelas berasal dari relasi many-to-many
    $kelasGuru = $guru->kelas;

    // ambil kelas yang sedang dipilih
    $kelasDipilih = $request->kelas_id 
                        ? $kelasGuru->firstWhere('id', $request->kelas_id)
                        : $kelasGuru->first();   // default kelas pertama

    if (!$kelasDipilih) {
        return back()->with('error', 'Guru tidak memiliki kelas yang diampu.');
    }

    // siswa per kelas
    $siswa = Siswa::where('kelas_id', $kelasDipilih->id)
                  ->orderBy('nama', 'asc')
                  ->get();

    // kehadiran hari ini per siswa
    $kehadiranHariIni = Kehadiran::where('tanggal', now()->toDateString())
        ->whereIn('siswa_id', $siswa->pluck('id'))
        ->get()
        ->keyBy('siswa_id');

    return view('kehadiran.manual', [
        'kelas' => $kelasDipilih,
        'kelasGuru' => $kelasGuru,
        'siswa' => $siswa,
        'kehadiranHariIni' => $kehadiranHariIni
    ]);
}


    public function store(Request $request)
    {
        $kehadiranInput = $request->kehadiran;
        $guru = Guru::where('user_id', auth()->id())->first();

        if (!$guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        foreach ($kehadiranInput as $siswa_id => $status) {

            if (!in_array($status, ['hadir', 'izin', 'sakit', 'alfa'])) {
                continue;
            }

            Kehadiran::create([
                'siswa_id' => $siswa_id,
                'tanggal'  => now()->toDateString(),
                'status'   => $status,
                'guru_id'  => $guru->id,
            ]);
        }

        return redirect()->route('kehadiran.index')->with('success', 'Data kehadiran berhasil disimpan!');
    }
}
