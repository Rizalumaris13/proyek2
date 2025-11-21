<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KehadiranController;


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
Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
Route::post('/guru/store', [GuruController::class, 'store'])->name('guru.store');
