<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK YAPIM BIRU-BIRU</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .nav-buttons {
            display: flex;
            gap: 1rem;
        }
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-login {
            background: transparent;
            border: 2px solid white;
            color: white;
        }
        .btn-register {
            background: white;
            color: #667eea;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .hero {
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .hero p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            color: white;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .feature-card h3 {
            margin-bottom: 1rem;
        }
        .feature-card p {
            opacity: 0.9;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo" style="display:flex; align-items:center; gap:0.5rem;">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMK YAPIM" style="height:40px; width:auto; border-radius:6px;">
            <span style="font-weight:700;">SMK YAPIM BIRU-BIRU</span>
        </div>
        <div class="nav-buttons">
            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        </div>
    </nav>

    <div class="hero">
        <h1>Sistem Informasi Akademik<br>SMK YAPIM BIRU-BIRU</h1>
        <p>Platform digital terpadu untuk mengelola kegiatan akademik, mempermudah proses pembelajaran, dan meningkatkan komunikasi antara guru, siswa, dan sekolah.</p>
    </div>

    <div class="features">
        <div class="feature-card">
            <i class="fas fa-user-graduate"></i>
            <h3>Portal Siswa</h3>
            <p>Akses mudah ke jadwal pelajaran, materi pembelajaran, dan informasi akademik lainnya.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Portal Guru</h3>
            <p>Kelola kelas, unggah materi, dan pantau perkembangan siswa dengan efisien.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-clipboard-check"></i>
            <h3>Presensi Digital</h3>
            <p>Sistem presensi digital yang terintegrasi untuk memudahkan pencatatan kehadiran.</p>
        </div>
    </div>
</body>
</html>