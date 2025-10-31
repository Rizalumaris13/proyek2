<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SMA NU Tenajar Kidul</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <header>
        <div class="logo">SMA NU TENAJAR KIDUL</div>
        <nav class="menu">
            <a href="#">Beranda</a>
            <a href="#">Profil</a>
            <a href="#">Informasi</a>
            <a href="#">Kontak</a>
        </nav>
    </header>

    <main class="container">
        <div class="left">
            <h1>SELAMAT DATANG<br>DI SMA NU TENAJAR KIDUL</h1>
            <p>Masuk ke akun Anda untuk melanjutkan.<br>Jika belum punya akun, klik tombol “Daftar”.</p>
        </div>

        <div class="right">
            <form class="login-form" method="POST" action="#">
                @csrf
                <h2>LOGIN</h2>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <div class="options">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#" class="forgot">forgot password?</a>
                </div>

                <button type="submit" class="btn">Login</button>

                <p class="register-text">Belum punya akun?<a href="#">Daftar</a></p>
            </form>
        </div>
    </main>

    <footer>
        <p>© 2025 SMA NU Tenajar Kidul. All rights reserved.</p>
    </footer>
</body>
</html>
