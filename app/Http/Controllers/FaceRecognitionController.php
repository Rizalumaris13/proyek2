<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FaceRecognitionController extends Controller
{
    private $pythonPath;
    private $basePath;
    
    public function __construct()
    {
        $this->pythonPath = storage_path('app/python-face-recognition');
        $this->basePath = base_path();
    }
    
    /**
     * Get system status
     */
    public function status(Request $request)
    {
        $status = [
            'python' => $this->checkPython(),
            'model' => $this->checkModel(),
            'dataset' => $this->checkDataset(),
            'flask' => $this->checkFlask(),
            'camera' => $this->checkCamera(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $status,
            'paths' => [
                'python' => $this->pythonPath,
                'model' => $this->pythonPath . '/model/trainer.yml',
                'dataset' => $this->pythonPath . '/known_faces',
            ]
        ]);
    }
    
    /**
     * Train face recognition model
     */
    public function train(Request $request)
    {
        $scriptPath = $this->pythonPath . '/train.py';
        
        if (!file_exists($scriptPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Training script not found'
            ], 404);
        }
        
        // Cek Python
        $python = $this->getPythonCommand();
        if (!$python) {
            return response()->json([
                'success' => false,
                'message' => 'Python not found on system'
            ], 400);
        }
        
        // Jalankan training
        $process = new Process([$python, $scriptPath], $this->pythonPath);
        $process->setTimeout(600); // 10 menit
        $process->start();
        
        // Simpan PID untuk tracking
        $pid = $process->getPid();
        Storage::put('python/training.pid', $pid);
        
        return response()->json([
            'success' => true,
            'message' => 'Training started',
            'pid' => $pid,
            'monitor_url' => url('/api/face-recognition/training-progress')
        ]);
    }
    
    /**
     * Start Flask server
     */
    public function start(Request $request)
    {
        $scriptPath = $this->pythonPath . '/app.py';
        
        if (!file_exists($scriptPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Flask app not found'
            ], 404);
        }
        
        // Cek apakah sudah running
        if ($this->checkFlask()) {
            return response()->json([
                'success' => false,
                'message' => 'Flask server is already running'
            ], 400);
        }
        
        // Camera source dari request atau default
        $cameraSource = $request->input('camera', '0');
        $port = $request->input('port', 5000);
        
        // Command untuk start Flask
        $python = $this->getPythonCommand();
        $command = sprintf(
            'cd %s && %s %s --host 0.0.0.0 --port %d --camera "%s" > %s 2>&1 & echo $!',
            escapeshellarg($this->pythonPath),
            $python,
            escapeshellarg($scriptPath),
            $port,
            escapeshellarg($cameraSource),
            escapeshellarg($this->pythonPath . '/logs/flask_server.log')
        );
        
        // Jalankan di background
        $pid = shell_exec($command);
        $pid = trim($pid);
        
        // Simpan PID
        Storage::put('python/flask.pid', $pid);
        
        // Tunggu sebentar untuk inisialisasi
        sleep(2);
        
        return response()->json([
            'success' => true,
            'message' => 'Face recognition server started',
            'pid' => $pid,
            'url' => 'http://localhost:' . $port,
            'stream_url' => 'http://localhost:' . $port . '/video'
        ]);
    }
    
    /**
     * Stop Flask server
     */
    public function stop(Request $request)
    {
        $pid = Storage::get('python/flask.pid');
        
        if ($pid) {
            // Kill process
            $process = new Process(['kill', '-9', $pid]);
            $process->run();
            
            Storage::delete('python/flask.pid');
        }
        
        // Juga coba kill dengan nama
        $process = new Process(['pkill', '-f', 'app.py']);
        $process->run();
        
        return response()->json([
            'success' => true,
            'message' => 'Face recognition server stopped'
        ]);
    }
    
    /**
     * Connect to ESP32-CAM
     */
    public function connectESP32(Request $request)
    {
        $validated = $request->validate([
            'ip' => 'required|ipv4',
            'port' => 'sometimes|integer'
        ]);
        
        $ip = $validated['ip'];
        $port = $validated['port'] ?? 81;
        
        $streamUrl = "http://{$ip}:{$port}/stream";
        
        // Test connection
        $testProcess = new Process(['curl', '-s', '-o', '/dev/null', '-w', '%{http_code}', $streamUrl]);
        $testProcess->setTimeout(5);
        $testProcess->run();
        
        if ($testProcess->isSuccessful() && trim($testProcess->getOutput()) === '200') {
            // Update Flask camera source jika running
            if ($this->checkFlask()) {
                $this->updateCameraSource($streamUrl);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Connected to ESP32-CAM',
                'stream_url' => $streamUrl
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to connect to ESP32-CAM'
        ], 400);
    }
    
    /**
     * Upload face images for training
     */
    public function uploadFace(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|integer|exists:siswa,id',
            'images' => 'required|array',
            'images.*' => 'image|max:2048'
        ]);
        
        $siswaId = $validated['siswa_id'];
        $targetDir = $this->pythonPath . "/known_faces/{$siswaId}";
        
        // Buat folder jika belum ada
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $uploaded = [];
        $failed = [];
        
        foreach ($request->file('images') as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $targetDir . '/' . $filename;
            
            if ($image->move($targetDir, $filename)) {
                $uploaded[] = $filename;
            } else {
                $failed[] = $image->getClientOriginalName();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Face images uploaded',
            'uploaded' => count($uploaded),
            'failed' => count($failed),
            'total_faces' => count(glob($targetDir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE))
        ]);
    }
    
    /**
     * Get training progress
     */
    public function trainingProgress(Request $request)
    {
        $logFile = $this->pythonPath . '/logs/training.log';
        
        if (!file_exists($logFile)) {
            return response()->json([
                'success' => false,
                'message' => 'No training log found'
            ]);
        }
        
        // Baca log terakhir
        $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastLines = array_slice($logs, -20); // 20 line terakhir
        
        // Cek PID
        $pid = Storage::get('python/training.pid');
        $isRunning = $pid && $this->isProcessRunning($pid);
        
        return response()->json([
            'success' => true,
            'running' => $isRunning,
            'logs' => $lastLines,
            'pid' => $pid
        ]);
    }
    
    // ==================== HELPER METHODS ====================
    
    private function checkPython()
    {
        $python = $this->getPythonCommand();
        $process = new Process([$python, '--version']);
        $process->run();
        
        return $process->isSuccessful();
    }
    
    
private function getPythonCommand()
{
    
    return 'py';
}

// Atau lebih robust:
private function findPythonCommand()
{
    $commands = ['py', 'python', 'python3'];
    
    foreach ($commands as $cmd) {
        $process = new Process([$cmd, '--version']);
        $process->run();
        
        if ($process->isSuccessful()) {
            return $cmd;
        }
    }
    
    return null; // Tidak ditemukan
}
    
    private function checkModel()
    {
        $modelFile = $this->pythonPath . '/model/trainer.yml';
        return file_exists($modelFile);
    }
    
    private function checkDataset()
    {
        $datasetDir = $this->pythonPath . '/known_faces';
        
        if (!file_exists($datasetDir)) {
            return 0;
        }
        
        $folders = scandir($datasetDir);
        $studentCount = 0;
        
        foreach ($folders as $folder) {
            if ($folder !== '.' && $folder !== '..' && is_dir($datasetDir . '/' . $folder)) {
                $studentCount++;
            }
        }
        
        return $studentCount;
    }
    
    private function checkFlask()
    {
        $process = new Process(['curl', '-s', 'http://localhost:5000/api/status']);
        $process->setTimeout(2);
        $process->run();
        
        return $process->isSuccessful();
    }
    
    private function checkCamera()
    {
        // Coba akses webcam
        $process = new Process(['ls', '/dev/video*']);
        $process->run();
        
        if ($process->isSuccessful()) {
            $output = trim($process->getOutput());
            return !empty($output);
        }
        
        return false;
    }
    
    private function updateCameraSource($source)
    {
        $url = 'http://localhost:5000/api/camera/connect';
        
        $process = new Process([
            'curl', '-s', '-X', 'POST', '-H', 'Content-Type: application/json',
            '-d', json_encode(['source' => $source]), $url
        ]);
        $process->run();
        
        return $process->isSuccessful();
    }
    
    private function isProcessRunning($pid)
    {
        $process = new Process(['ps', '-p', $pid]);
        $process->run();
        
        return $process->isSuccessful();
    }
}