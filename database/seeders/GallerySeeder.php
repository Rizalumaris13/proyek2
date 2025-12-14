<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // pastikan file-file sudah ditempatkan di storage/app/public/galleries/
        $items = [
            ['title'=>'Hero Gedung','filename'=>'hero1.jpg','type'=>'hero','order'=>0],
            ['title'=>'Latihan Kepemimpinan','filename'=>'event1.jpg','type'=>'event','order'=>1],
            ['title'=>'Upacara Bendera','filename'=>'event2.jpg','type'=>'event','order'=>2],
            ['title'=>'Paskibra','filename'=>'event3.jpg','type'=>'event','order'=>3],
            ['title'=>'Galeri 1','filename'=>'gal1.jpg','type'=>'gallery','order'=>1],
            ['title'=>'Galeri 2','filename'=>'gal2.jpg','type'=>'gallery','order'=>2],
        ];

        foreach($items as $it){
            Gallery::create($it);
        }
    }
}
