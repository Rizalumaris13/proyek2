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
    $user = auth()->user();
    $filterKelas = $request->kelas_id;

    // =========================
    // JIKA ADMIN
    // =========================
    if ($user->role === 'admin') {

        $kelasList = Kelas::all();

        $query = Siswa::with('kelas')->orderBy('nama', 'asc');

        if ($filterKelas) {
            $query->where('kelas_id', $filterKelas);
        }

        $dataSiswa = $query->paginate(200)->appends($request->query());

        return view('siswa.data-siswa', compact(
            'kelasList',
            'dataSiswa',
            'filterKelas'
        ));
    }

    // =========================
    // JIKA GURU
    // =========================
    if ($user->role === 'guru') {

        $guru = $user->guru;

        // kelas yang diajar guru
        $kelasList = $guru->kelas;

        $kelasIds = $kelasList->pluck('id');

        $query = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->orderBy('nama', 'asc');

        // filter hanya boleh di kelas yg diajar
        if ($filterKelas && $kelasIds->contains($filterKelas)) {
            $query->where('kelas_id', $filterKelas);
        }

        $dataSiswa = $query->paginate(200)->appends($request->query());

        return view('siswa.data-siswa', compact(
            'kelasList',
            'dataSiswa',
            'filterKelas'
        ));
    }

    abort(403);
}

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
    abort(403);
}
        $kelasList = Kelas::all();
        return view('siswa.form', compact('kelasList'));
    }


    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
    abort(403);
}

        $request->validate([
            'nama' => 'required',
            'nisn' => 'required|unique:siswa',
            'jenis_kelamin' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'required|string',
        ]);

        Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambah');
    }


    /**
     * Form edit siswa
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
    abort(403);
}
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::all();

        return view('siswa.form', compact('siswa', 'kelasList'));
    }


    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
    abort(403);
}
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:50|unique:siswa,nisn,' . $siswa->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'required|string|max:500',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,   // FIX terpenting!
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }
}