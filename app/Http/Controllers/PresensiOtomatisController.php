<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Carbon\Carbon;

class PresensiOtomatisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'status'   => 'required'
        ]);

        $tanggal = Carbon::today();

        // anti double absen
        $sudah = Kehadiran::where('siswa_id', $request->siswa_id)
            ->where('tanggal', $tanggal)
            ->exists();

        if ($sudah) {
            return response()->json([
                'message' => 'Sudah absen'
            ]);
        }

        Kehadiran::create([
            'siswa_id' => $request->siswa_id,
            'guru_id'  => null, // AMAN
            'tanggal'  => $tanggal,
            'status'   => $request->status
        ]);

        return response()->json([
            'message' => 'Presensi otomatis berhasil'
        ]);
    }
}
