<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa + filter kelas
     */
    public function index(Request $request)
{
    $kelasList = Kelas::all();

    $filterKelas = $request->kelas_id;

    $query = Siswa::with('kelas');

    if ($filterKelas) {
        $query->where('kelas_id', $filterKelas);
    }

    $dataSiswa = $query->paginate(10)->appends($request->query());

    return view('siswa.data-siswa', compact('kelasList', 'dataSiswa', 'filterKelas'));
}


    /**
     * Form tambah siswa
     */
    public function create()
    {
        $kelasList = Kelas::all();
        return view('siswa.form', compact('kelasList'));
    }


    /**
     * Simpan data siswa baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nisn' => 'required|unique:siswa',
            'jenis_kelamin' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambah');
    }


    /**
     * Form edit siswa
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::all();

        return view('siswa.form', compact('siswa', 'kelasList'));
    }


    /**
     * Update data siswa
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:50|unique:siswa,nisn,' . $siswa->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,   // FIX terpenting!
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }
}