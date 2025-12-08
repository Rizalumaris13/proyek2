<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa'; // nama tabel
    protected $fillable = ['nama','nisn','jenis_kelamin','kelas_id', 'alamat'];

    public function kelas()
{
    return $this->belongsTo(kelas::class);
}
}
