<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Siswa;
use Carbon\Carbon;

class PresensiOtomatisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
        ]);

        Kehadiran::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'tanggal' => Carbon::today(),
            ],
            [
                'status' => 'hadir',
            ]
        );

        return response()->json([
            'message' => 'Presensi berhasil',
        ]);
    }
}
