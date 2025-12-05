<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
<<<<<<< Updated upstream
use App\Http\Controllers\KehadiranController;
=======
use App\Http\Controllers\ProfileController;


Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/presensi', function () {
    return view('presensi-siswa');
});

Route::get('/data-siswa', function () {
    return view('data-siswa');
});

Route::get('/tambah', function () {
    return view('siswa.tambah-siswa');
});

Route::get('/profil', function () {
    return view('profil');
});
>>>>>>> Stashed changes


Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');

Route::get('/register', [AuthController::class,'showRegister'])->name('register');
Route::post('/register', [AuthController::class,'register'])->name('register.post');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // Presensi & Profil
    Route::view('/presensi', 'presensi-siswa')->name('presensi');
    Route::view('/profil', 'profil')->name('profil');

   
    Route::resource('siswa', SiswaController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/kehadiran/manual', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::post('/kehadiran/store', [KehadiranController::class, 'store'])->name('kehadiran.store');
});

<<<<<<< Updated upstream
=======
Route::get('/data-siswa', [SiswaController::class, 'index'])->name('siswa.index');
Route::get('/siswa/tambah', [SiswaController::class, 'create'])->name('siswa.create');
Route::post('/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');

// Profil pengguna
Route::get('/cekview', function () {
    return [
        'view_exists' => view()->exists('profile'),
        'file_exists' => file_exists(resource_path('views/profile.blade.php')),
        'view_path' => resource_path('views'),
    ];
});


// Debug: cek keberadaan view
Route::get('/cekview', function () {
    return response()->json([
        'view_exists_profile' => view()->exists('profile'),
        'file_exists' => file_exists(resource_path('views/profile.blade.php')),
        'view_paths' => config('view.paths'),
    ]);
});


>>>>>>> Stashed changes
