<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    // Tampilkan daftar siswa (dengan optional filter kelas)
    public function index(Request $request)
    {
        $filterKelas = $request->input('kelas');
        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas');

        $dataSiswa = Siswa::when($filterKelas, function($q, $kelas){
            return $q->where('kelas', $kelas);
        })->orderBy('nama','asc')->get();

        return view('siswa.data-siswa', compact('dataSiswa','kelasList','filterKelas'));
    }

    // Tampilkan form tambah siswa
    public function create()
    {
        // Jika kamu menggunakan daftar kelas statis, bisa kirim array
        $kelas = ['X TKJ','X Akutansi','XI TKJ','XI Akutansi','XII Akutansi','XII TKJ']; // atau ambil dari tabel kelas jika ada
        return view('siswa.tambah-siswa', compact('kelas'));
    }

    // Simpan data siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:50|unique:siswa,nisn',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas' => 'required|string|max:100',
        ]);

        Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('siswa.index')->with('success','Data siswa berhasil ditambahkan!');
    }

    // (opsional) edit, update, destroy dsb...
}
