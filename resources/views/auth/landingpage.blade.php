{{-- resources/views/landingpage.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>SMA NU Tenajar Kidul</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --brand:#102e63;
      --accent:#ffd166;
      --muted:#080808;
      --bg:#fff;
    }
    *{box-sizing:border-box}
    body{font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial;background:var(--bg);margin:0;color:#0d1b2a}
    a{color:inherit}
    header{display:flex;align-items:center;justify-content:space-between;padding:18px 30px;position:sticky;top:0;z-index:60; background:#ebebeb }
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{height:54px}
    nav{display:flex;gap:18px;align-items:center}
    nav a{font-weight:700;opacity:0.95; margin-left: 30px;}
    .hero{
      min-height:360px;display:flex;align-items:center;justify-content:center;text-align:center;position:relative;overflow:hidden;
      background-size:cover;background-position:center;
    }
    .hero::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,0.7), rgba(255,255,255,0.85));}
    .hero .inner{position:relative;z-index:2;color:#0d1b2a;padding:20px}
    .hero h1{margin:0;font-size:28px;font-weight:800;color:var(--brand);letter-spacing:0.6px}
    .hero p{margin-top:8px;color:var(--muted);font-weight:600}
    .wrap{max-width:1150px;margin:40px auto;padding:0 18px}

    /* kegiatan */
    .events{display:flex;gap:18px;align-items:end;justify-content:center;flex-wrap:nowrap;overflow:visible;transform:translateY(-20px)}
    .card{background:#fff;border-radius:4px;padding:0;box-shadow:none;text-align:left;width:220px;}
    .card .imgwrap{height:300px;overflow:hidden;border-radius:4px 4px 0 0;display:flex;align-items:flex-end}
    .card img{width:100%;height:100%;object-fit:cover;display:block}
    .card .title{padding:10px 6px ;color:#102e63;font-size:13px;text-align: center;}

    /* profile */
    .profile{display:flex;gap:22px;align-items:center;flex-wrap:wrap;margin-top:48px}
    .profile .left{flex:0 0 380px}
    .profile img{width:100%;height:280px;object-fit:cover;border-radius:8px}
    .profile .right{flex:1;min-width:220px}

    /* gallery */
    .gallery-grid{display:flex;gap:20px;justify-content:center;align-items:flex-end;margin-top:20px;flex-wrap:wrap}
    .gimg{width:350px;height:340px;object-fit:cover;border-radius:6px;box-shadow:0 12px 36px rgba(0,0,0,0.06)}

    footer{background:var(--brand);color:#102e63;padding:48px 18px;margin-top:48px}

    /* responsive */
    @media (max-width:980px){
      .profile img{height:200px}
      .hero{min-height:260px}
      .events{flex-wrap:wrap;transform:none}
      .card{width:45%}
      .gallery-grid{gap:18px}
      .gimg{width:30vw;height:40vw}
    }
    
.footer {
    width: 100%;
    background: #0f2f63;
    color: #ffffff;
    margin-top: 50px;
}

.footer-top {
    max-width: 1200px;              
    margin: 0 auto;
    padding: 40px clamp(16px, 4vw, 48px);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 30px;
}


.footer h4 {
    margin-bottom: 12px;
    font-size: 16px;
    text-align: center;
    border-bottom: 2px solid rgba(255,255,255,0.3);
    padding-bottom: 6px;
}

.footer p,
.footer a {
    font-size: 14px;
    color: rgba(255,255,255,0.85);
    text-decoration: none;
}

.footer a:hover {
    color: #ffffff;
    text-decoration: underline;
}

.footer-bottom {
    text-align: center;
    padding: 15px 10px;
    background: #0b244d;
    font-size: 13px;
    color: rgba(255,255,255,0.75);
    width: 100%;
}

.footer-icon img {
    width: 18px;
    height: 18px;
    object-fit: contain;
}

  </style>
</head>
<body>

<header>
  <div class="brand">
    <a href="{{ route('home') }}"><img src="{{ asset('images/lo.png') }}" alt="logo"></a>
    <div>
      <div style="font-weight:900;color:var(--brand)">SMA NU Tenajar Kidul</div>
      <div style="font-size:12px;color:var(--muted)">Terampil · Aktual · Ulet</div>
    </div>
  </div>

  <nav>
    <a href="#home">Beranda</a>
    <a href="#profile">Profile Sekolah</a>
    <a href="#gallery">Galeri</a>
    <a href="#contact">Kontak</a>
  </nav>
</header>

{{-- hero: gunakan $hero dari controller --}}
<section id="home" class="hero" aria-label="hero"
   style="background: url('/images/1.jpg') center/cover no-repeat; height: 450px;">
  <div class="inner">
    <h1>Selamat Datang</h1>
    <p>di Website SMA NU Tenajar Kidul</p>
  </div>
</section>

<main class="wrap">
  {{-- kegiatan unggulan --}}
  <section aria-label="kegiatan" style="margin-top:40px">
    <h3 style="text-align:center;color:var(--brand);margin:0">Kegiatan Unggulan</h3>
    <p style="text-align:center;color:var(--muted);margin-top:8px">Beberapa kegiatan terbaik kami</p>

    <div class="events" style="margin-top:18px">
      {{-- Loop events yang dikirim dari controller --}}
      @if(!empty($events) && is_array($events))
        @foreach($events as $ev)
          <div class="card">
            <div class="imgwrap">
              <img src="{{ $ev['image'] }}" alt="{{ $ev['title'] }}">
            </div>
            <div class="title">{{ $ev['title'] }}</div>
          </div>
        @endforeach
      @else
        {{-- fallback manual jika $events kosong --}}
        <div class="card">
          <div class="imgwrap"><img src="{{ asset('images/keg1.jpg') }}" alt=""></div>
          <div class="title">Kegiatan contoh 1</div>
        </div>
        <div class="card">
          <div class="imgwrap"><img src="{{ asset('images/keg2.jpg') }}" alt=""></div>
          <div class="title">Kegiatan contoh 2</div>
        </div>
        <div class="card">
          <div class="imgwrap"><img src="{{ asset('images/keg3.jpg') }}" alt=""></div>
          <div class="title">Kegiatan contoh 3</div>
        </div>
      @endif
    </div>
  </section>

  {{-- profile --}}
  <div id="profile" class="profile">
    <div class="left">
        <img src="{{ asset('images/sekolah.jpg') }}" alt="Gedung Sekolah">
    </div>

    <div class="right">
      <h3 style="color:var(--brand);margin-top:0">Profile Sekolah</h3>
      <p style="color:var(--muted);text-align:justify">
       SMA NU Tenajar Kidul adalah lembaga pendidikan menengah atas yang berada di bawah naungan Lembaga Pendidikan Ma’arif NU. Sekolah ini berkomitmen untuk mencetak generasi muda yang cerdas, berprestasi, dan berakhlakul karimah melalui perpaduan antara pendidikan umum dan nilai-nilai keislaman ala Nahdlatul Ulama.

Dengan lingkungan belajar yang religius, disiplin, dan berkarakter, SMA NU Tenajar Kidul terus berupaya menciptakan lulusan yang berdaya saing tinggi, berwawasan luas, serta siap menghadapi tantangan zaman. Melalui berbagai kegiatan akademik dan ekstrakurikuler, sekolah ini menumbuhkan semangat belajar, 
kreativitas, dan kepedulian sosial pada setiap siswanya.
      </p>
      <p style="color:var(--muted);margin-top:8px"><strong>Visi :</strong> <br> "TAJUG" <br> "Mempersiapkan Pribadi yang Terampil, Aktual, Jenius, Ulet, Giat"</p>
      <p style="color:var(--muted);margin-top:8px"><strong>Misi :</strong><br> 1. Mempertahankan dan mengaktualisasikan faham Islam Ahlusunnah Walja'ah.<br>
                                                                           2. Mengintegrasikan akhlak mulia , iman dan ilmu pengetahuan dalam perilaku sehari-hari.<br>
                                                                           3. Menyiapkan peserta didik yang memiliki kompetisi keilmuan berbasis teknologi informasi dan komunikasi.<br>
                                                                           4. Menyiapkan peserta didik yang siap dan berkualitas untuk modal dalam dunia kerja.<br>
                                                                           5. Menyiapkan peserta didik yang disiplin dan bertanggung jawab serta siap dalam menghadapi tantangan di Masyarakat.</p>
    </div>
  </div>

  {{-- galeri --}}
  <div id="gallery" style="margin-top:36px">
    <h3 style="text-align:center;color:var(--brand)">Galeri Sekolah</h3>
    <p style="text-align:center;color:var(--muted)">Dokumentasi kegiatan & prestasi</p>

    <div class="gallery-grid">
      @if(!empty($galleries) && is_array($galleries))
        @foreach($galleries as $img)
          <img class="gimg" loading="lazy" src="{{ $img }}" alt="Galeri">
        @endforeach
      @else
        {{-- fallback --}}
        <img class="gimg" src="{{ asset('images/gal1.jpg') }}" alt="placeholder">
        <img class="gimg" src="{{ asset('images/gal2.jpg') }}" alt="placeholder">
        <img class="gimg" src="{{ asset('images/gal3.jpg') }}" alt="placeholder">
      @endif
    </div>
  </div>

<footer class="footer">
    <div class="footer-top">

    <!-- HUBUNGI KAMI -->
        <div>
            <h4>HUBUNGI KAMI</h4>
            <div class="footer-icon">
                <img src="https://cdn-icons-png.flaticon.com/512/724/724664.png" alt="Telp">
                <a href="tel:087708858370">0821-3001-1005</a>
            </div>

            <div class="footer-icon">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="Email">
                <a href="mailto:smanutenajarkidul@gmail.com">smanutenajarkidul@gmail.com</a>
            </div>
        </div>

        <!-- TAGS -->
        <div>
            <h4>TAGS</h4>
                <p>
                <a href="https://www.google.com/maps/search/?api=1&query=SMA+NU+Tenajar+Kidul"
                target="_blank">
                📍 Peta lokasi sekolah</a><br>
                <a href="/admin">Admin</a>
                </p>
        </div>


        <!-- IKUTI KAMI -->
        <div>
            <h4>IKUTI KAMI</h4>
            <div class="footer-icon">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook">
                <a href="https://www.facebook.com/smanutekid" target="_blank">SMA NU Tenajar Kidul</a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <a href="{{ route('register') }}">
        © {{ date('Y') }} SMA NU Tenajar Kidul. All rights reserved.
        </a>
    </div>
</footer>
</body>
</html>
