@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="welcome-card">
    <h2><i class="fas fa-user-shield"></i> Selamat Datang, Admin!</h2>
    <p>Halo <strong>{{ auth()->user()->nama_lengkap }}</strong>, Anda memiliki akses penuh terhadap seluruh fitur dan database sistem.</p>
    <p>Anda dapat mengelola keamanan basis data, mengatur struktur dan data, serta melakukan monitoring sistem.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-value">{{ $totalGuru }}</div>
        <div class="stat-label">Total Guru</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-value">{{ $totalSiswa }}</div>
        <div class="stat-label">Total Siswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-school"></i>
        </div>
        <div class="stat-value">{{ $totalKelas }}</div>
        <div class="stat-label">Total Kelas</div>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-tasks"></i> Fitur Admin</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- 1. Akses Penuh Database -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-database"></i> Akses Database
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Memiliki akses penuh terhadap seluruh fitur dan database system
            </p>
            <div>
                <a href="{{ route('admin.db_report') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> Lihat Database
                </a>
            </div>
        </div>

        <!-- 2. Mengatur Struktur Data -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-sitemap"></i> Kelola Struktur & Data
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Menambah, mengedit, menghapus data pengguna (kepala sekolah, pembina, guru, siswa)
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur manajemen user akan tersedia')">
                    <i class="fas fa-cog"></i> Kelola Data
                </button>
            </div>
        </div>

        <!-- 3. Monitoring & Validasi -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-shield-alt"></i> Monitoring Sistem
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Validasi teknis terhadap data presensi untuk memastikan tidak ada error
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur monitoring akan tersedia')">
                    <i class="fas fa-chart-line"></i> Monitor
                </button>
            </div>
        </div>

        <!-- 4. Izin Digital -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-file-medical"></i> Pengajuan Izin
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Menyediakan dan mengatur fitur pengajuan izin digital (hadir, izin, sakit, alpha)
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur izin akan tersedia')">
                    <i class="fas fa-clipboard-list"></i> Kelola Izin
                </button>
            </div>
        </div>

        <!-- 5. Kegiatan Sekolah -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-calendar-alt"></i> Kegiatan Sekolah
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Menambah, memperbaruii, dan menghapus data kegiatan sekolah (rapat, ujian, acara resmi)
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur kegiatan akan tersedia')">
                    <i class="fas fa-plus-circle"></i> Kelola Kegiatan
                </button>
            </div>
        </div>

        <!-- 6. Jadwal Pelajaran -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-table"></i> Jadwal Pelajaran
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Mengatur dan membuat jadwal pelajaran (roster harian) untuk setiap kelas
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur jadwal akan tersedia')">
                    <i class="fas fa-edit"></i> Atur Jadwal
                </button>
            </div>
        </div>

        <!-- 7. File Materi -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-folder-open"></i> File Materi
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Memantau file materi pembelajaran yang diunggah guru sesuai ketentuan
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur materi akan tersedia')">
                    <i class="fas fa-search"></i> Lihat Materi
                </button>
            </div>
        </div>

        <!-- 8. Backup Database -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-hdd"></i> Backup Database
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Mengelola dan melakukan backup database secara berkala untuk keamanan data
            </p>
            <div>
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur backup akan tersedia')">
                    <i class="fas fa-download"></i> Backup Now
                </button>
            </div>
        </div>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-info-circle"></i> Informasi Sistem</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
        <div style="padding: 1rem; background: #f7fafc; border-radius: 8px;">
            <strong style="color: #4a5568;">Status Sistem:</strong>
            <span class="badge badge-success" style="margin-left: 0.5rem;">Active</span>
        </div>
        <div style="padding: 1rem; background: #f7fafc; border-radius: 8px;">
            <strong style="color: #4a5568;">Database:</strong>
            <span style="color: #2d3748; margin-left: 0.5rem;">MySQL Connected</span>
        </div>
        <div style="padding: 1rem; background: #f7fafc; border-radius: 8px;">
            <strong style="color: #4a5568;">Last Backup:</strong>
            <span style="color: #2d3748; margin-left: 0.5rem;">-</span>
        </div>
        <div style="padding: 1rem; background: #f7fafc; border-radius: 8px;">
            <strong style="color: #4a5568;">Laravel Version:</strong>
            <span style="color: #2d3748; margin-left: 0.5rem;">{{ app()->version() }}</span>
        </div>
    </div>
</div>
@endsection
