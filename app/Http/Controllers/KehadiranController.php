<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $kehadiranHariIni = Kehadiran::where('tanggal', now()->toDateString())
            ->get()
            ->keyBy('siswa_id');

        return view('kehadiran.manual', compact('kelas', 'siswa', 'kehadiranHariIni'));
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
