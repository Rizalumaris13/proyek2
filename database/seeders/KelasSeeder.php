<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
{
    $data = [
        ['nama_kelas' => 'X TKJ', 'tingkat' => 'X', 'jurusan' => 'TKJ'],
        ['nama_kelas' => 'X AKL', 'tingkat' => 'X', 'jurusan' => 'AKL'],
        ['nama_kelas' => 'XI TKJ', 'tingkat' => 'XI', 'jurusan' => 'TKJ'],
        ['nama_kelas' => 'XI AKL', 'tingkat' => 'XI', 'jurusan' => 'AKL'],
        ['nama_kelas' => 'XII TKJ', 'tingkat' => 'XII', 'jurusan' => 'TKJ'],
        ['nama_kelas' => 'XII AKL', 'tingkat' => 'XII', 'jurusan' => 'AKL'],
    ];

    Kelas::insert($data);
}
}
