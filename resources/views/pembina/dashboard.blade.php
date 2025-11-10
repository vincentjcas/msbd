<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembina Dashboard</title>
    <style>
        body{font-family:Arial, Helvetica, sans-serif;background:#f3f6f8}
        .nav{background:#16a085;color:#fff;padding:1rem;display:flex;justify-content:space-between}
        .container{max-width:1100px;margin:2rem auto;padding:1rem}
        .card{background:#fff;padding:1.5rem;border-radius:8px;box-shadow:0 6px 20px rgba(0,0,0,0.05)}
        .role{background:#16a085;color:white;padding:0.25rem 0.6rem;border-radius:12px;font-weight:600}
    </style>
</head>
<body>
    <div class="nav">
        <div>Pembina Dashboard</div>
        <div>
            <span>{{ auth()->user()->name }}</span>
            <span class="role">{{ strtoupper(auth()->user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;margin-left:8px">
                @csrf
                <button type="submit" style="background:transparent;border:1px solid rgba(255,255,255,0.2);color:#fff;padding:6px 10px;border-radius:6px;">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Selamat datang, {{ explode(' ', auth()->user()->nama_lengkap)[0] }}!</h2>
            <p>Anda masuk sebagai <strong>Pembina</strong>. Halaman ini bisa berisi tugas pembinaan, jadwal pembinaan, dan catatan kegiatan.</p>
        </div>
    </div>
</body>
</html>