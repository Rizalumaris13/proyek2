<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresensiController extends Controller
{
    public function index()
    {
        // guru login
        $guru = auth()->user()->guru;

        // ambil kelas yang dia ampu
        $kelas = $guru->kelas; // ← INI YANG PENTING

        return view('presensi-siswa', compact('kelas'));
    }
}
