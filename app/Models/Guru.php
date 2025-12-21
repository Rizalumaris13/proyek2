<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'gurus';

    protected $primaryKey = 'id'; // Pastikan ada primary key
    
    protected $fillable = [
        'user_id',
        'mapel',
        // tambahkan field lain jika ada
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // ✅ Tambahkan foreign key
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas', 'guru_id', 'kelas_id');
    }

    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class, 'guru_id'); // ✅ Tambahkan foreign key
    }
}