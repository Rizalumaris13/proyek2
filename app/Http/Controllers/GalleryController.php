<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function create()
    {
        return view('admin.gallery.create'); // simple form
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
            'type'  => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        $file = $request->file('images');
        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        // simpan ke storage/app/public/galleries
        $path = $file->storeAs('public/images', $filename);

        $gallery = Gallery::create([
            'title' => $request->post('title'),
            'filename' => $filename,
            'type' => $request->post('type'),
            'order' => 0,
            'active' => true
        ]);

        return redirect()->back()->with('success','Gambar berhasil diupload');
    }
}
