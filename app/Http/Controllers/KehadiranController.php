<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Guru;

class KehadiranController extends Controller
{
    public function index()
    {
        $kelas = Kelas::first();
        $siswa = Siswa::all();

        return view('kehadiran.manual', compact('kelas', 'siswa'));
    }

    public function store(Request $request)
{
    // 1. Ambil array kehadiran dari form:
    //    contoh: ['1' => 'hadir', '2' => 'izin', ...]
    $kehadiranInput = $request->kehadiran;

    // 2. Ambil guru berdasarkan user login
    $guru = Guru::where('user_id', auth()->id())->first();

    // Jika guru tidak ditemukan (opsional)
    if (!$guru) {
        return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
    }

    // 3. Loop setiap siswa yang dikirim dari form
    foreach ($kehadiranInput as $siswa_id => $status) {

        // Validasi status biar aman
        if (!in_array($status, ['hadir', 'izin', 'sakit', 'alfa'])) {
            continue; // abaikan jika status tidak valid
        }

        // 4. Simpan data kehadiran
        Kehadiran::create([
            'siswa_id' => $siswa_id,                    // ← benar!
            'tanggal'  => now()->toDateString(),        // atau $request->tanggal
            'status'   => $status,                      // ← benar!
            'guru_id'  => $guru->id,                    // ambil dari guru yang login
        ]);
    }

    return redirect()->route('kehadiran.index')
        ->with('success', 'Data kehadiran berhasil disimpan!');
}

}
