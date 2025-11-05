<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/presensi', function () {
    return view('presensi-siswa');
});


Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');

Route::get('/register', [AuthController::class,'showRegister'])->name('register');
Route::post('/register', [AuthController::class,'register'])->name('register.post');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

// route dashboard (hanya contoh) — lindungi pakai middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
});