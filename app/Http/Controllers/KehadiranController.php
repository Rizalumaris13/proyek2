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
    $siswa = Siswa::orderBy('nama', 'asc')->get();
    
    // Ambil kehadiran hari ini
    $kehadiranHariIni = Kehadiran::where('tanggal', now()->toDateString())
        ->get()
        ->keyBy('siswa_id');

    return view('kehadiran.manual', compact('kelas', 'siswa', 'kehadiranHariIni'));
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
public function statistik(Request $request)
{
    // Ambil daftar kelas untuk dropdown
    $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

    // Cek apakah data kelas tersedia
    if ($kelasList->isEmpty()) {
        return back()->with('error', 'Belum ada data kelas.');
    }

    // Tentukan kelas yang dipilih
    $kelasId = $request->kelas_id ?? $kelasList->first()->id;

    // Ambil siswa berdasarkan kelas
    $siswa = Siswa::where('kelas_id', $kelasId)
        ->orderBy('nama', 'asc')
        ->get();

    // Ambil rekap kehadiran berdasarkan bulan
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