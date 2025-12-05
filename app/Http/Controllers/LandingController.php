<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // hero / header image (manual)
        $hero = asset('images/1.jpg');

        // kegiatan unggulan (manual list — bisa ditambah)
        $events = [
            ['title' => 'Latihan Dasar Kepemimpinan Siswa', 'image' => asset('images/lddk.jpg')],
            ['title' => 'Upacara Pengibaran Bendera', 'image' => asset('images/pengibar.jpg')],
            ['title' => 'Kegiatan Mengaji', 'image' => asset('images/ngaji.jpg')],
            ['title' => 'Ekstrakurikuler Paskibra', 'image' => asset('images/paskibra.jpg')],
            ['title' => 'Ekstrakurikuler Pramuka', 'image' => asset('images/pramuka.jpg')],
        ];

        // galeri sekolah (manual)
        $galleries = [
            asset('images/juara.jpg'),
            asset('images/topeng.jpg'),
            asset('images/juara2.jpg'),
        ];

        return view('landingpage', compact('hero', 'events', 'galleries'));
    }
}
