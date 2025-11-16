<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa + filter kelas
     */
    public function index(Request $request)
    {
        $filterKelas = $request->input('kelas');

        // Ambil seluruh kelas unik
        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas')->toArray();

        // Urutkan kelas: X → XI → XII, lalu jurusan
        usort($kelasList, function($a, $b) {

            preg_match('/\b(X|XI|XII)\b/', $a, $matchA);
            preg_match('/\b(X|XI|XII)\b/', $b, $matchB);

            if (!$matchA || !$matchB) {
                return strcmp($a, $b);
            }

            $order = ['X' => 1, 'XI' => 2, 'XII' => 3];

            if ($order[$matchA[1]] !== $order[$matchB[1]]) {
                return $order[$matchA[1]] <=> $order[$matchB[1]];
            }

            return strcmp($a, $b);
        });

        // Ambil siswa sesuai filter kelas
        $dataSiswa = Siswa::when($filterKelas, function($q, $kelas){
                return $q->where('kelas', $kelas);
            })
            ->orderBy('nama', 'asc')
            ->get();

        return view('siswa.data-siswa', compact('dataSiswa', 'kelasList', 'filterKelas'));
    }

    /**
     * Form tambah siswa
     */
    public function create()
    {
        // buat variabel $siswa null agar tidak error saat include form
        $siswa = null;
        return view('siswa.form', compact('siswa'));
    }

    /**
     * Simpan data siswa baru
     */
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

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Form edit siswa
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.form', compact('siswa'));
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
            'kelas' => 'required|string|max:100',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

}
