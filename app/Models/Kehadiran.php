<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadirans';

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'tanggal',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
