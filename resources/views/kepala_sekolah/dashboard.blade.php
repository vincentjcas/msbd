@extends('layouts.dashboard')

@section('content')
<style>
    .welcome-section {
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 50%, #14b8a6 100%);
        padding: 2.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(3, 105, 161, 0.3);
        margin-bottom: 2rem;
        color: white;
    }
    .welcome-section h1 {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }
    .welcome-section p {
        font-size: 1.05rem;
        opacity: 0.95;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .stat-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }
    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }
    .link-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
        display: flex;
        gap: 1rem;
    }
    .link-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .link-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .link-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }
    .link-desc {
        font-size: 0.85rem;
        color: #718096;
    }
</style>

<div class="welcome-section">
    <h1>Selamat Datang, {{ explode(' ', auth()->user()->nama_lengkap ?? auth()->user()->name)[0] }}!</h1>
    <p>Pantau semua aspek operasional sekolah dari dashboard kepala sekolah</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Total Guru -->
    <div class="stat-card">
        <div class="stat-label">Total Guru</div>
        <div class="stat-number" style="color: #2563eb;">{{ $totalGuru }}</div>
    </div>

    <!-- Total Siswa -->
    <div class="stat-card">
        <div class="stat-label">Total Siswa</div>
        <div class="stat-number" style="color: #0891b2;">{{ $totalSiswa }}</div>
    </div>

    <!-- Total Pembina -->
    <div class="stat-card">
        <div class="stat-label">Total Pembina</div>
        <div class="stat-number" style="color: #14b8a6;">{{ $totalPembina }}</div>
    </div>

    <!-- Izin Pending -->
    <div class="stat-card">
        <div class="stat-label">Izin Menunggu</div>
        <div class="stat-number" style="color: #ea580c;">{{ $izinPending }}</div>
    </div>
</div>

<!-- Quick Links -->
<div style="margin-top: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1.5rem;">Menu Utama</h2>
    <div class="quick-links">
        <!-- Grafik Kehadiran -->
        <a href="{{ route('kepala_sekolah.grafik-kehadiran') }}" class="link-card">
            <div class="link-icon" style="background: #dbeafe; color: #2563eb;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <div class="link-title">Grafik Kehadiran</div>
                <div class="link-desc">Analisis tren kehadiran</div>
            </div>
        </a>

        <!-- Rekap Presensi -->
        <a href="{{ route('kepala_sekolah.rekap-presensi') }}" class="link-card">
            <div class="link-icon" style="background: #cffafe; color: #0891b2;">
                <i class="fas fa-table"></i>
            </div>
            <div>
                <div class="link-title">Rekap Presensi</div>
                <div class="link-desc">Laporan bulanan</div>
            </div>
        </a>

        <!-- Manajemen Izin -->
        <a href="{{ route('kepala_sekolah.izin') }}" class="link-card">
            <div class="link-icon" style="background: #ccfbf1; color: #14b8a6;">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="link-title">Manajemen Izin</div>
                <div class="link-desc">Approve/Reject izin</div>
            </div>
        </a>

        <!-- Laporan Aktivitas -->
        <a href="{{ route('kepala_sekolah.laporan') }}" class="link-card">
            <div class="link-icon" style="background: #fed7aa; color: #ea580c;">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <div class="link-title">Laporan Aktivitas</div>
                <div class="link-desc">Review laporan pembina</div>
            </div>
        </a>

        <!-- Download Rekap -->
        <a href="{{ route('kepala_sekolah.download-rekap') }}" class="link-card">
            <div class="link-icon" style="background: #e0e7ff; color: #6366f1;">
                <i class="fas fa-download"></i>
            </div>
            <div>
                <div class="link-title">Download Rekap</div>
                <div class="link-desc">Export laporan presensi</div>
            </div>
        </a>
    </div>
</div>
@endsection