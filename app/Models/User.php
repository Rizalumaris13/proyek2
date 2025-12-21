<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function guru()
    {
        return $this->hasOne(Guru::class);
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }
    
    public function getMapelAttribute()
    {
        return $this->guru ? $this->guru->mapel : null;
    }
    public function getRoleNameAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'guru' => 'Guru', 
            'siswa' => 'Siswa',
            'user' => 'User'
        ];
        
        return $roles[$this->role] ?? 'User';
    }
}