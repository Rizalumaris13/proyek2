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

    // Ambil kelas yang diajar guru ini
    $kelasGuru = $guru->kelas;

    // Cari kelas yang dipilih
    $kelasDipilih = $request->kelas_id 
                    ? $kelasGuru->firstWhere('id', $request->kelas_id)
                    : $kelasGuru->first();

    if (!$kelasDipilih) {
        return back()->with('error', 'Guru tidak memiliki kelas yang diampu.');
    }

    // Ambil siswa dari kelas yang dipilih
    $siswa = Siswa::where('kelas_id', $kelasDipilih->id)
                  ->orderBy('nama', 'asc')
                  ->get();

    // Ambil kehadiran hari ini HANYA untuk guru ini
    $kehadiranHariIni = Kehadiran::where('tanggal', now()->toDateString())
        ->where('guru_id', $guru->id) // FILTER BERDASARKAN GURU
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
    // VALIDASI INPUT
    $request->validate([
        'kehadiran' => 'required|array',
        'kehadiran.*' => 'required|in:hadir,izin,sakit,alfa',
        'kelas_id' => 'required|exists:kelas,id',
    ]);

    $guru = Guru::where('user_id', auth()->id())->first();

    if (!$guru) {
        return back()->with('error', 'Data guru tidak ditemukan.');
    }

    $today = now()->toDateString();
    $kehadiranInput = $request->kehadiran;

    // DEBUG: Tampilkan data yang akan disimpan
    // dd($kehadiranInput, $guru->id);

    foreach ($kehadiranInput as $siswa_id => $status) {
        
        // Gunakan updateOrCreate agar tidak duplikat
        Kehadiran::updateOrCreate(
            [
                'siswa_id' => $siswa_id,
                'tanggal'  => $today,
                'guru_id'  => $guru->id, // PASTIKAN INI ADA
            ],
            [
                'status'   => $status,
            ]
        );
    }

    // Redirect ke halaman yang sama dengan kelas_id
    return redirect()->route('kehadiran.index', ['kelas_id' => $request->kelas_id])
                    ->with('success', 'Data kehadiran berhasil disimpan!');
}
}
