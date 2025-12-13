<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    // DEBUG: Cek input
    // dd($request->all(), auth()->id());
    
    $guru = Guru::where('user_id', auth()->id())->first();
    
    // DEBUG: Cek data guru
    if (!$guru) {
        dd('Guru tidak ditemukan!', 'User ID:', auth()->id(), 'User:', auth()->user());
    }
    
    $today = now()->toDateString();
    $kehadiranInput = $request->kehadiran;
    
    // DEBUG: Cek input kehadiran
    // dd($kehadiranInput, 'Guru ID:', $guru->id);
    
    foreach ($kehadiranInput as $siswa_id => $status) {
        
        if (!in_array($status, ['hadir', 'izin', 'sakit', 'alfa'])) {
            continue;
        }

        // DEBUG SETIAP ITERASI
        // dd([
        //     'siswa_id' => $siswa_id,
        //     'status' => $status,
        //     'guru_id' => $guru->id,
        //     'tanggal' => $today
        // ]);
        
        Kehadiran::updateOrCreate(
            [
                'siswa_id' => $siswa_id,
                'tanggal'  => $today,
                'guru_id'  => $guru->id, // INI YANG PERLU DIPASTIKAN
            ],
            [
                'status'   => $status,
            ]
        );
    }

    return redirect()->route('kehadiran.index', ['kelas_id' => $request->kelas_id])
                    ->with('success', 'Data kehadiran berhasil disimpan!');
}
}
