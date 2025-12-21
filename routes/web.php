<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KehadiranStatistikController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PresensiOtomatisController;

Route::get('/fix-cache', function () {
   Artisan::call('optimize:clear');
   Artisan::call('config:clear');
   Artisan::call('cache:clear');
   Artisan::call('view:clear');
   Artisan::call('route:clear');
   return "<pre>" . Artisan::output() . "Semua cache berhasil dibersihkan.</pre>";
});
Route::get('/landingpage', [LandingController::class, 'index'])->name('home');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');

Route::get('/register', [AuthController::class,'showRegister'])->name('register');
Route::post('/register', [AuthController::class,'register'])->name('register.post');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Presensi & Profil
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::view('/profil', 'profil')->name('profil');

   
    Route::resource('siswa', SiswaController::class);
});
Route::middleware(['auth'])->group(function () {

    Route::get('/kehadiran/manual/{kelas_id?}', [KehadiranController::class, 'index'])->name('kehadiran.index');

    Route::post('/kehadiran/store', [KehadiranController::class, 'store'])
        ->name('kehadiran.store');

    // Statistik Kehadiran
    Route::get('/kehadiran/statistik', [KehadiranStatistikController::class, 'index']);
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
Route::post('/guru/store', [GuruController::class, 'store'])->name('guru.store');
Route::get('/test-py', function() {
    $output = [];
    $returnCode = 0;
    
    exec('py --version 2>&1', $output, $returnCode);
    
    return response()->json([
        'command' => 'py --version',
        'success' => $returnCode === 0,
        'output' => implode("\n", $output),
        'return_code' => $returnCode
    ]);
});
