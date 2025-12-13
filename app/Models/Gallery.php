<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = ['title','filename','type','order','active'];

    // memberikan url publik ke file
    public function url()
    {
        // gunakan storage:link (public disk)
        return asset('public/images' . $this->images);

    }
}
