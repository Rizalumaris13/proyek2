<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
     public function index()
    {
        // Ambil semua data dari tabel siswa
        $dataSiswa = Siswa::orderBy('nama', 'asc')->get();

        // Kirim ke view
        return view('siswa.data-siswa', compact('dataSiswa'));
    }
    
    // Tampilkan halaman tambah siswa
    public function create()
    {
        return view('siswa.tambah-siswa');
    }

    // Simpan data siswa ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:50|unique:siswa,nisn',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        // Simpan ke database
        Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        // Arahkan kembali ke halaman data siswa
        return redirect('/data-siswa')->with('success', 'Data siswa berhasil ditambahkan!');
    }
}
