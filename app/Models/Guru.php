<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'gurus';   // ← penting karena nama tabel tidak plural

    protected $fillable = [
        'user_id',
        'mapel',
    ];

    public function user() {
    return $this->belongsTo(User::class);
}

    public function kelas()
{
    return $this->belongsToMany(Kelas::class, 'guru_kelas', 'guru_id', 'kelas_id');
}

}

