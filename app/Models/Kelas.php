<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan'
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // TAMBAHKAN RELASI INI (Many-to-Many dengan Guru)
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_kelas', 'kelas_id', 'guru_id');
    }

    // OPSIONAL: Relasi ke kehadiran melalui siswa
    public function kehadirans()
    {
        return $this->hasManyThrough(Kehadiran::class, Siswa::class);
    }
}