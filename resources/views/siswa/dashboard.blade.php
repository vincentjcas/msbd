<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .navbar {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 1.5rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .welcome-card h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .welcome-card p {
            color: #666;
        }
        .role-badge {
            background: #3498db;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: #3498db;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .stat-card p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Siswa Dashboard</h1>
        <div class="user-info">
            <span>{{ auth()->user()->name }}</span>
            <span class="role-badge">{{ strtoupper(auth()->user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Selamat Datang di Siswa Dashboard!</h2>
            <p>Halo <strong>{{ auth()->user()->name }}</strong>, Anda login sebagai <strong>Siswa</strong>.</p>
            <p>Dari sini Anda dapat mengakses materi pembelajaran, tugas, dan jadwal kelas.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>6</h3>
                <p>Mata Pelajaran</p>
            </div>
            <div class="stat-card">
                <h3>12</h3>
                <p>Tugas Pending</p>
            </div>
            <div class="stat-card">
                <h3>25</h3>
                <p>Materi Tersedia</p>
            </div>
            <div class="stat-card">
                <h3>85%</h3>
                <p>Nilai Rata-rata</p>
            </div>
        </div>
    </div>

    <!-- 🔥 SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Oke',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Selamat Datang!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Mulai',
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

</body>
</html>
