<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Presensi Siswa — Sistem Presensi Cerdas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
:root{
  --brand:#0f3a80;
  --accent:#3f7bff;
  --bg:#f5f6f8;
  --success:#28a745;
  --danger:#dc3545;
}

body{
  margin:0;
  background:var(--bg);
  font-family:'Inter',sans-serif;
}

/* ================= HEADER ================= */
.topbar{
  background:var(--brand);
  color:#fff;
  padding:14px 22px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

/* ================= LAYOUT ================= */
.app{
  padding:28px;
  display:grid;
  grid-template-columns:240px 1fr;
  gap:24px;
}

/* ================= SIDEBAR ================= */
.sidebar-card{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(15,58,128,.06);
}

.nav-link{
  display:block;
  font-weight:600;
  color:#333;
  padding:10px;
  border-radius:8px;
  margin-bottom:6px;
  text-decoration:none;
}

.nav-link.active{
  background:linear-gradient(90deg,var(--accent),#2f63d6);
  color:#fff;
}

/* ================= CONTENT ================= */
.panel{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 18px rgba(15,58,128,.06);
  margin-bottom:20px;
}

/* ================= KAMERA SECTION ================= */
.camera-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.camera-status {
  font-size: 0.9rem;
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 600;
}

.status-disconnected {
  background: #f8d7da;
  color: var(--danger);
}

.status-connected {
  background: #d4edda;
  color: var(--success);
}

.camera-container {
  width: 100%;
  height: 320px;
  border-radius: 12px;
  overflow: hidden;
  position: relative;
  background: #1a1a1a;
}

.camera-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: #999;
  font-size: 18px;
}

.camera-icon {
  font-size: 60px;
  margin-bottom: 15px;
  opacity: 0.5;
}

.camera-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.camera-controls {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(0,0,0,0.8));
  padding: 20px;
  display: flex;
  justify-content: center;
  gap: 10px;
}

.camera-btn {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-connect {
  background: linear-gradient(90deg, var(--accent), #2f63d6);
  color: white;
}

.btn-connect:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(63, 123, 255, 0.3);
}

.btn-disconnect {
  background: var(--danger);
  color: white;
}

.btn-disconnect:hover {
  background: #c82333;
}

.btn-capture {
  background: var(--success);
  color: white;
}

.btn-capture:hover {
  background: #218838;
}

.connection-info {
  margin-top: 15px;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 8px;
  font-size: 0.85rem;
}

.connection-info h6 {
  margin: 0 0 8px 0;
  font-weight: 600;
}

.connection-info ul {
  margin: 0;
  padding-left: 20px;
}

/* ================= KELAS SECTION ================= */
.kelas-item{
  background:#f8f9fb;
  border:1px solid #eee;
  border-radius:10px;
  padding:16px;
  margin-bottom:15px;
}

.kelas-title{
  font-weight:700;
}

.kelas-actions{
  display:flex;
  gap:10px;
  margin-top:15px;
}

/* ================= FOOTER ================= */
footer{
  background:var(--brand);
  color:#fff;
  text-align:center;
  padding:10px;
  font-size:13px;
}

/* =============== OVERLAY (DESKTOP OFF) =============== */
.sidebar-overlay{
  display:none;
}

/* =====================================================
   MOBILE MODE (SAMA PERSIS KAYAK DASHBOARD)
===================================================== */
@media (max-width:768px){

  .app{
    grid-template-columns:1fr;
    padding:16px;
  }

  aside.sidebar{
    position:fixed;
    top:0;
    left:-280px;
    width:260px;
    height:100vh;
    z-index:1050;
    transition:left .3s ease;
  }

  body.sidebar-open aside.sidebar{
    left:0;
  }

  .sidebar-card{
    height:100%;
    border-radius:0;
  }

  .sidebar-overlay{
    display:block;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    z-index:1040;
    opacity:0;
    pointer-events:none;
    transition:opacity .3s;
  }

  body.sidebar-open .sidebar-overlay{
    opacity:1;
    pointer-events:auto;
  }
  
  .camera-container {
    height: 250px;
  }
  
  .camera-controls {
    padding: 15px;
    flex-wrap: wrap;
  }
  
  .camera-btn {
    padding: 8px 15px;
    font-size: 0.9rem;
  }
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="topbar">
  <div class="d-flex align-items-center gap-3">
    <img src="{{ asset('images/logo.png') }}" style="height:34px">
    <div>
      <h5 class="m-0 fw-bold">Sistem Presensi Cerdas</h5>
      <small>SMA NU Tenajar Kidul</small>
    </div>
  </div>

  <div class="d-none d-md-flex align-items-center gap-3">
      <div style="font-weight:600">
        Halo, {{ Auth::user()->name ?? 'Admin' }}
      </div>

      <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-white">
        Logout
      </a>
    </div>
    <!-- HAMBURGER (MOBILE ONLY) -->
    <button class="btn btn-link text-white d-md-none"
            onclick="toggleSidebar()"
            style="font-size:22px;text-decoration:none">
      ☰
    </button>
  </div>
</header>

<!-- ================= APP ================= -->
<div class="app">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <nav class="nav flex-column">
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <a class="nav-link active" href="/presensi">Presensi Siswa</a>
        <a class="nav-link" href="/siswa">Data Siswa</a>
        <a class="nav-link" href="/kehadiran/statistik">Statistik Kehadiran</a>
        <a class="nav-link" href="/profil">Profil</a>
      </nav>
    </div>
  </aside>

  <!-- ===== CONTENT ===== -->
  <main>
    <div class="panel">
  <div class="camera-header">
    <div class="fw-bold">Presensi Otomatis - ESP32-CAM</div>
    <div id="statusIndicator" class="camera-status status-disconnected">
      🔴 ESP32-CAM Terputus
    </div>
  </div>
  
  <div class="camera-container" id="cameraContainer">
    <div class="camera-placeholder" id="cameraPlaceholder">
      <div class="camera-icon">🤖</div>
      <div>ESP32-CAM Offline</div>
      <small class="mt-2">IP: <span id="espIp">192.168.1.100</span></small>
    </div>
    
    <img id="esp32Stream" class="camera-preview" style="display:none;">
    
    <div class="camera-controls" id="cameraControls" style="display:none;">
      <button class="camera-btn btn-capture" onclick="captureFromESP32()">
        <span>📸</span> Ambil Foto & Presensi
      </button>
      <button class="camera-btn btn-disconnect" onclick="disconnectESP32()">
        <span>❌</span> Putuskan
      </button>
    </div>
  </div>
  
  <div class="connection-info mt-3">
    <h6>🔌 Koneksi ESP32-CAM:</h6>
    <div class="input-group mb-2">
      <span class="input-group-text">IP Address</span>
      <input type="text" id="esp32Ip" class="form-control" 
             value="192.168.1.100" placeholder="IP ESP32-CAM">
      <button class="btn btn-outline-primary" onclick="testConnection()">
        Test
      </button>
    </div>
    <small class="text-muted">
      Pastikan ESP32-CAM dan komputer dalam jaringan WiFi yang sama
    </small>
  </div>
  
  <div class="text-center mt-3">
    <button class="camera-btn btn-connect" onclick="connectToESP32()" id="connectBtn">
      <span>🔗</span> Hubungkan ke ESP32-CAM
    </button>
  </div>
</div>
    <!-- KELAS SECTION (TIDAK DIUBAH) -->
    <div class="panel">
      <div class="fw-bold mb-2">Kelas Yang Anda Ampu</div>
      <div class="text-muted mb-3">{{ now()->translatedFormat('l, d F Y') }}</div>

      @foreach($kelas as $k)
      <div class="kelas-item">
        <div class="kelas-title">{{ $k->nama_kelas }}</div>
        <small class="text-muted">
          Jumlah siswa: {{ $k->siswa_count ?? $k->siswa()->count() }}
        </small>

        <div class="kelas-actions">
          <a href="{{ route('kehadiran.index',['kelas_id'=>$k->id]) }}"
             class="btn btn-secondary btn-sm">
            Presensi Manual
          </a>
          <button class="btn btn-primary btn-sm">
            Generate Face Recognition
          </button>
        </div>
      </div>
      @endforeach
    </div>

  </main>
</div>

<!-- ===== OVERLAY (HARUS DI LUAR .app) ===== -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<footer>
© {{ date('Y') }} SMA NU Tenajar Kidul
</footer>

<script>
// Toggle Sidebar
function toggleSidebar(){
  document.body.classList.toggle('sidebar-open');
}

let esp32Ip = "192.168.1.100";
let streamInterval = null;

function connectToESP32() {
  const placeholder = document.getElementById('cameraPlaceholder');
  const streamImg = document.getElementById('esp32Stream');
  const controls = document.getElementById('cameraControls');
  const statusIndicator = document.getElementById('statusIndicator');
  const connectBtn = document.getElementById('connectBtn');
  
  // Ambil IP dari input
  esp32Ip = document.getElementById('esp32Ip').value.trim();
  if (!esp32Ip) {
    alert('Masukkan IP Address ESP32-CAM!');
    return;
  }
  
  // Tampilkan loading
  placeholder.innerHTML = '<div class="camera-icon">⏳</div><div>Menghubungkan ke ESP32...</div>';
  connectBtn.disabled = true;
  connectBtn.innerHTML = '<span>⏳</span> Menghubungkan...';
  
  // Test connection first
  fetch(`http://${esp32Ip}/capture`, { mode: 'no-cors' })
    .then(() => {
      // Jika berhasil, mulai stream
      startStreaming();
      
      placeholder.style.display = 'none';
      streamImg.style.display = 'block';
      controls.style.display = 'flex';
      
      statusIndicator.className = 'camera-status status-connected';
      statusIndicator.innerHTML = '🟢 ESP32-CAM Terhubung';
      
      connectBtn.style.display = 'none';
    })
    .catch(error => {
      console.error('Connection failed:', error);
      placeholder.innerHTML = `
        <div class="camera-icon">❌</div>
        <div>Gagal terhubung ke ESP32</div>
        <small class="mt-2">IP: ${esp32Ip}</small>
        <small>Pastikan ESP32 aktif dan jaringan sama</small>
      `;
      
      statusIndicator.className = 'camera-status status-disconnected';
      statusIndicator.innerHTML = '🔴 Gagal Terhubung';
      
      connectBtn.disabled = false;
      connectBtn.innerHTML = '<span>🔄</span> Coba Lagi';
    });
}

function startStreaming() {
  const streamImg = document.getElementById('esp32Stream');
  const streamUrl = `http://${esp32Ip}/stream`;
  
  // MJPEG Stream
  streamImg.src = streamUrl;
  
  // Atau alternatif: polling gambar setiap 100ms
  if (streamInterval) clearInterval(streamInterval);
  
  streamInterval = setInterval(() => {
    const timestamp = new Date().getTime();
    streamImg.src = `http://${esp32Ip}/capture?t=${timestamp}`;
  }, 100); // 10 FPS
}

function disconnectESP32() {
  const placeholder = document.getElementById('cameraPlaceholder');
  const streamImg = document.getElementById('esp32Stream');
  const controls = document.getElementById('cameraControls');
  const statusIndicator = document.getElementById('statusIndicator');
  const connectBtn = document.getElementById('connectBtn');
  
  // Stop stream
  if (streamInterval) {
    clearInterval(streamInterval);
    streamInterval = null;
  }
  streamImg.src = '';
  
  // Reset UI
  streamImg.style.display = 'none';
  controls.style.display = 'none';
  placeholder.style.display = 'flex';
  placeholder.innerHTML = `
    <div class="camera-icon">🤖</div>
    <div>ESP32-CAM Terputus</div>
    <small class="mt-2">IP: ${esp32Ip}</small>
  `;
  
  // Update status
  statusIndicator.className = 'camera-status status-disconnected';
  statusIndicator.innerHTML = '🔴 ESP32-CAM Terputus';
  
  connectBtn.style.display = 'block';
  connectBtn.disabled = false;
  connectBtn.innerHTML = '<span>🔗</span> Hubungkan ke ESP32-CAM';
}

async function captureFromESP32() {
  const statusIndicator = document.getElementById('statusIndicator');
  
  try {
    statusIndicator.innerHTML = '⏳ Mengambil foto...';
    
    // Ambil foto dari ESP32
    const response = await fetch(`http://${esp32Ip}/capture`);
    const blob = await response.blob();
    
    // Kirim ke server Laravel untuk face recognition
    const formData = new FormData();
    formData.append('image', blob, 'face_esp32.jpg');
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('device', 'esp32-cam');
    
    statusIndicator.innerHTML = '⏳ Memproses wajah...';
    
    // Kirim ke endpoint Laravel
    const laravelResponse = await fetch('/api/face-recognition', {
      method: 'POST',
      body: formData
    });
    
    const result = await laravelResponse.json();
    
    if (result.success) {
      statusIndicator.innerHTML = '✅ Presensi berhasil!';
      alert(`Presensi berhasil untuk: ${result.student_name}`);
      
      // Tampilkan info siswa
      showAttendanceSuccess(result);
    } else {
      statusIndicator.innerHTML = '❌ Wajah tidak dikenali';
      alert('Wajah tidak dikenali. Silakan coba lagi atau gunakan presensi manual.');
    }
    
  } catch (error) {
    console.error('Error:', error);
    statusIndicator.innerHTML = '❌ Error capture';
    alert('Gagal mengambil foto dari ESP32. Pastikan koneksi stabil.');
  }
  
  // Kembalikan status setelah 3 detik
  setTimeout(() => {
    statusIndicator.innerHTML = '🟢 ESP32-CAM Terhubung';
  }, 3000);
}

function testConnection() {
  const ip = document.getElementById('esp32Ip').value.trim();
  if (!ip) return;
  
  const statusIndicator = document.getElementById('statusIndicator');
  statusIndicator.innerHTML = '⏳ Testing connection...';
  
  // Coba akses endpoint capture
  fetch(`http://${ip}/capture`, { 
    mode: 'no-cors',
    cache: 'no-cache'
  })
  .then(() => {
    statusIndicator.innerHTML = '✅ ESP32 ditemukan!';
    setTimeout(() => {
      statusIndicator.innerHTML = '🔴 ESP32-CAM Terputus';
    }, 2000);
  })
  .catch(() => {
    statusIndicator.innerHTML = '❌ ESP32 tidak ditemukan';
    setTimeout(() => {
      statusIndicator.innerHTML = '🔴 ESP32-CAM Terputus';
    }, 2000);
  });
}

function showAttendanceSuccess(data) {
  // Tampilkan modal atau notifikasi
  const modalHtml = `
    <div class="attendance-success">
      <h5>✅ Presensi Berhasil!</h5>
      <p><strong>Siswa:</strong> ${data.student_name}</p>
      <p><strong>NIS:</strong> ${data.student_nis}</p>
      <p><strong>Kelas:</strong> ${data.class_name}</p>
      <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
      <button onclick="this.parentElement.remove()" class="btn btn-sm btn-primary">
        Tutup
      </button>
    </div>
  `;
  
  // Tambahkan ke DOM
  const div = document.createElement('div');
  div.innerHTML = modalHtml;
  div.style.position = 'fixed';
  div.style.top = '20px';
  div.style.right = '20px';
  div.style.zIndex = '9999';
  div.style.padding = '15px';
  div.style.background = 'white';
  div.style.borderRadius = '10px';
  div.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
  document.body.appendChild(div);
  
  // Auto remove setelah 5 detik
  setTimeout(() => {
    if (div.parentElement) {
      div.remove();
    }
  }, 5000);
}

// Auto-detect ESP32 di jaringan (optional)
async function scanNetwork() {
  // Ini hanya contoh sederhana
  const baseIp = '192.168.1.';
  
  for (let i = 1; i <= 254; i++) {
    const ip = baseIp + i;
    
    // Skip current device
    if (ip === window.location.hostname) continue;
    
    try {
      await fetch(`http://${ip}/capture`, { 
        mode: 'no-cors',
        timeout: 100 
      });
      
      // Jika berhasil, mungkin ini ESP32
      console.log('Found potential ESP32 at:', ip);
      document.getElementById('esp32Ip').value = ip;
      break;
    } catch (e) {
      // Continue scanning
    }
  }
}

// Jalankan scan saat halaman load (opsional)
// document.addEventListener('DOMContentLoaded', scanNetwork);
</script>
</script>

</body>
</html>