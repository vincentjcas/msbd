<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMK YAPIM BIRU-BIRU</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="yapim.png">
  <!-- Keep On Truckin font for the main heading -->
  <link href="https://fonts.googleapis.com/css2?family=Keep+On+Truckin&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-600 via-cyan-500 to-teal-600 min-h-screen flex flex-col items-center text-white">

  <!-- Navbar -->
  <header class="w-full flex justify-between items-center px-10 py-5 bg-gradient-to-r from-blue-700/90 to-cyan-600/90 backdrop-blur-lg shadow-xl border-b border-cyan-400/20">
    <div class="flex items-center gap-3">
      <img src="{{ asset('images/yapim.png') }}" alt="Logo Sekolah" class="w-10 h-10" style="height:40px; width: auto; border-radius:6px;">
      <h1 class="font-bold text-lg tracking-wide">SMK YAPIM BIRU-BIRU</h1>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('login') }}" class="bg-white/20 hover:bg-white/30 text-white font-semibold px-5 py-2 rounded-full transition-all duration-300 border border-white/20">
        Login
      </a>
      <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white font-semibold px-6 py-2 rounded-full shadow-lg transition-all duration-300">
        Register
      </a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="text-center mt-20 px-6 max-w-3xl">
    <h1 class="text-5xl md:text-6xl font-extrabold drop-shadow-2xl leading-snug text-white" style="font-family: 'Keep On Truckin', serif;">
      Sistem Informasi Akademik<br>
      <span class="text-blue-100">SMK YAPIM BIRU-BIRU</span>
    </h1>
    <p class="text-cyan-100 mt-6 text-lg leading-relaxed">
      Platform digital terpadu untuk mengelola kegiatan akademik, mempermudah proses pembelajaran,
      dan meningkatkan komunikasi antara guru, siswa, dan sekolah.
    </p>
  </section>

  <!-- Card Section -->
  <section class="flex justify-center flex-wrap gap-8 mt-16 mb-20 px-6">
    <!-- Portal Siswa -->
    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 w-80 text-center hover:scale-105 transition-transform duration-300 border border-white/20">
      <div class="text-5xl mb-4">🎓</div>
      <h3 class="text-white font-bold text-lg mb-3">Portal Siswa</h3>
      <p class="text-gray-100 text-sm leading-relaxed">
        Akses mudah ke jadwal pelajaran, materi pembelajaran, dan informasi akademik lainnya.
      </p>
    </div>

    <!-- Portal Guru -->
    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 w-80 text-center hover:scale-105 transition-transform duration-300 border border-white/20">
      <div class="text-5xl mb-4">👨‍🏫</div>
      <h3 class="text-white font-bold text-lg mb-3">Portal Guru</h3>
      <p class="text-gray-100 text-sm leading-relaxed">
        Kelola kelas, unggah materi, dan pantau perkembangan siswa dengan efisien.
      </p>
    </div>

    <!-- Presensi Digital -->
    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 w-80 text-center hover:scale-105 transition-transform duration-300 border border-white/20">
      <div class="text-5xl mb-4">📋</div>
      <h3 class="text-white font-bold text-lg mb-3">Presensi Digital</h3>
      <p class="text-gray-100 text-sm leading-relaxed">
        Sistem presensi digital yang terintegrasi untuk memudahkan pencatatan kehadiran.
      </p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center text-cyan-100 text-sm pb-6">
    © 2025 SMK YAPIM BIRU-BIRU. Semua hak dilindungi.
  </footer>

</body>
</html>
