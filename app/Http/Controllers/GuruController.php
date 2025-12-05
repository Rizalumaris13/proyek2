<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
{
    $guru = auth()->user()->guru;
    $kelas = $guru->kelas()->withCount('siswa')->get();

    return view('presensi.index', compact('guru','kelas'));
}

    public function create()
{
    return view('guru.create');
}

    public function store(Request $request)
{
    $request->validate([
        'nama'  => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required',
        'mapel' => 'required'
    ]);

    // 1. Buat user
    $user = User::create([
        'name'     => $request->nama,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'guru'
    ]);

    // 2. Simpan data guru
    Guru::create([
        'user_id' => $user->id,
        'mapel'   => $request->mapel,
    ]);

    return redirect()->route('guru.index')
        ->with('success', 'Data guru berhasil ditambahkan');
}

}
