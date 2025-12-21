
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceRecognitionController;
use App\Http\Controllers\ESP32Controller;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // ==================== FACE RECOGNITION API ====================
    Route::prefix('face-recognition')->group(function () {
        Route::get('/status', [FaceRecognitionController::class, 'status']);
        Route::get('/install-dependencies', [FaceRecognitionController::class, 'installDependencies']);
        Route::post('/train', [FaceRecognitionController::class, 'train']);
        Route::get('/training-progress', [FaceRecognitionController::class, 'trainingProgress']);
        Route::post('/start', [FaceRecognitionController::class, 'startFlask']);
        Route::post('/stop', [FaceRecognitionController::class, 'stopFlask']);
        Route::get('/stream-status', [FaceRecognitionController::class, 'streamStatus']);
        
        // Face Dataset Management
        Route::post('/upload-face', [FaceRecognitionController::class, 'uploadFace']);
        Route::delete('/delete-face/{siswaId}', [FaceRecognitionController::class, 'deleteFace']);
        Route::get('/dataset-info', [FaceRecognitionController::class, 'datasetInfo']);
        
        // Camera Management
        Route::post('/camera/connect', [FaceRecognitionController::class, 'connectCamera']);
        Route::post('/camera/disconnect', [FaceRecognitionController::class, 'disconnectCamera']);
        Route::get('/camera/list', [FaceRecognitionController::class, 'listCameras']);
        
        // Attendance via Face Recognition
        Route::post('/attendance/capture', [FaceRecognitionController::class, 'captureAndRecognize']);
        Route::post('/attendance/manual', [FaceRecognitionController::class, 'manualAttendance']);
    });
    
    // ==================== ESP32-CAM API ====================
    Route::prefix('esp32')->group(function () {
        Route::post('/register', [ESP32Controller::class, 'registerDevice']);
        Route::post('/connect', [ESP32Controller::class, 'connect']);
        Route::post('/disconnect', [ESP32Controller::class, 'disconnect']);
        Route::get('/status', [ESP32Controller::class, 'status']);
        Route::post('/capture', [ESP32Controller::class, 'capture']);
        Route::post('/stream-start', [ESP32Controller::class, 'startStream']);
        Route::post('/stream-stop', [ESP32Controller::class, 'stopStream']);
        Route::get('/devices', [ESP32Controller::class, 'listDevices']);
    });
    
    // ==================== ATTENDANCE API ====================
    Route::prefix('attendance')->group(function () {
        // Presensi
        Route::post('/check-in', [PresensiController::class, 'checkIn']);
        Route::post('/check-out', [PresensiController::class, 'checkOut']);
        Route::post('/manual', [PresensiController::class, 'manualAttendance']);
        
        // Reports
        Route::get('/today', [PresensiController::class, 'todayAttendance']);
        Route::get('/by-date/{date}', [PresensiController::class, 'attendanceByDate']);
        Route::get('/by-student/{siswaId}', [PresensiController::class, 'attendanceByStudent']);
        Route::get('/by-class/{kelasId}', [PresensiController::class, 'attendanceByClass']);
        Route::get('/summary/{period}', [PresensiController::class, 'attendanceSummary']);
        
        // Statistics
        Route::get('/stats/daily', [PresensiController::class, 'dailyStats']);
        Route::get('/stats/monthly', [PresensiController::class, 'monthlyStats']);
        Route::get('/stats/class', [PresensiController::class, 'classStats']);
    });
    
    // ==================== STUDENTS API ====================
    Route::prefix('students')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::get('/{id}', [SiswaController::class, 'show']);
        Route::post('/', [SiswaController::class, 'store']);
        Route::put('/{id}', [SiswaController::class, 'update']);
        Route::delete('/{id}', [SiswaController::class, 'destroy']);
        
        // Face Registration
        Route::post('/{id}/register-face', [SiswaController::class, 'registerFace']);
        Route::get('/{id}/face-status', [SiswaController::class, 'faceStatus']);
        Route::delete('/{id}/delete-face', [SiswaController::class, 'deleteFace']);
        
        // For Face Recognition System
        Route::get('/for-face-recognition', [SiswaController::class, 'forFaceRecognition']);
        Route::get('/labels', [SiswaController::class, 'faceLabels']);
    });
    
    // ==================== CLASSES API ====================
    Route::prefix('classes')->group(function () {
        Route::get('/', [KelasController::class, 'index']);
        Route::get('/{id}', [KelasController::class, 'show']);
        Route::get('/{id}/students', [KelasController::class, 'students']);
        Route::get('/{id}/attendance-today', [KelasController::class, 'attendanceToday']);
        Route::get('/{id}/attendance-stats', [KelasController::class, 'attendanceStats']);
    });
    
    // ==================== SYSTEM API ====================
    Route::prefix('system')->group(function () {
        Route::get('/health', function() {
            return response()->json([
                'status' => 'healthy',
                'timestamp' => now()->toDateTimeString(),
                'services' => [
                    'laravel' => app()->version(),
                    'php' => PHP_VERSION,
                    'database' => config('database.default'),
                    'storage' => disk_free_space(storage_path()) > 10485760, // 10MB free
                ]
            ]);
        });
        
        Route::get('/storage-info', function() {
            $pythonPath = storage_path('app/python-face-recognition');
            
            return response()->json([
                'python_path' => $pythonPath,
                'exists' => file_exists($pythonPath),
                'is_dir' => is_dir($pythonPath),
                'size' => file_exists($pythonPath) ? $this->getDirSize($pythonPath) : 0,
                'free_space' => disk_free_space(storage_path()),
                'total_space' => disk_total_space(storage_path())
            ]);
        });
    });
});