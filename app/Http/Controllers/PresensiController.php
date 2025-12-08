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
        $guru = auth()->user()->guru;

        $kelas = $guru->kelas; 

        return view('presensi-siswa', compact('kelas'));
    }
}
