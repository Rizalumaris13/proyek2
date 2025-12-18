<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Guru;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // =========================
        // CEK GURU
        // =========================
        $guru = Guru::where('user_id', $user->id)->first();

        // =========================
        // JIKA ADMIN
        // =========================
        if (!$guru) {
            return view('kehadiran.manual', [
                'kelas' => null,
                'kelasGuru' => collect(),
                'siswa' => collect(),
                'kehadiranHariIni' => collect(),
                'message' => 'Admin tidak memiliki data kehadiran.'
            ]);
        }

        // =========================
        // JIKA GURU
        // =========================
        $kelasGuru = $guru->kelas;

        if ($kelasGuru->isEmpty()) {
            return view('kehadiran.manual', [
                'kelas' => null,
                'kelasGuru' => $kelasGuru,
                'siswa' => collect(),
                'kehadiranHariIni' => collect(),
                'message' => 'Guru belum memiliki kelas yang diampu.'
            ]);
        }

        // Kelas dipilih
        $kelasDipilih = $request->kelas_id
            ? $kelasGuru->firstWhere('id', $request->kelas_id)
            : $kelasGuru->first();

        if (!$kelasDipilih) {
            return back()->with('error', 'Kelas tidak valid.');
        }

        // Ambil siswa
        $siswa = Siswa::where('kelas_id', $kelasDipilih->id)
            ->orderBy('nama', 'asc')
            ->get();

        // Kehadiran hari ini
        $kehadiranHariIni = Kehadiran::whereDate('tanggal', now())
            ->where('guru_id', $guru->id)
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
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        // =========================
        // ADMIN DILARANG INPUT
        // =========================
        if (!$guru) {
            return back()->with('error', 'Admin tidak dapat menginput kehadiran.');
        }

        $request->validate([
            'kehadiran' => 'required|array'
        ]);

        $today = now()->toDateString();

        foreach ($request->kehadiran as $siswa_id => $status) {

            if (!in_array($status, ['hadir', 'izin', 'sakit', 'alfa'])) {
                continue;
            }

            Kehadiran::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'tanggal' => $today,
                    'guru_id' => $guru->id,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()
            ->route('kehadiran.index', ['kelas_id' => $request->kelas_id])
            ->with('success', 'Data kehadiran berhasil disimpan!');
    }
}
