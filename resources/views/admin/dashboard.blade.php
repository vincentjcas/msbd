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

@if($pendingVerifikasi > 0)
<div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 1rem 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 6px rgba(251, 191, 36, 0.3);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-exclamation-circle" style="font-size: 2rem;"></i>
        <div>
            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 600;">Ada {{ $pendingVerifikasi }} Pendaftaran Guru Menunggu Verifikasi</h4>
            <p style="margin: 0.25rem 0 0 0; font-size: 0.9rem; opacity: 0.9;">Silakan verifikasi pendaftaran guru yang masuk</p>
        </div>
    </div>
    <a href="{{ route('admin.verifikasi-guru') }}" class="btn" style="background: white; color: #f59e0b; border: none; font-weight: 600; padding: 0.75rem 1.5rem;">
        <i class="fas fa-user-check"></i> Verifikasi Sekarang
    </a>
</div>
@endif

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

        <!-- 2. Verifikasi Guru -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #667eea; min-height: 200px; display: flex; flex-direction: column; position: relative;">
            @if($pendingVerifikasi > 0)
            <span style="position: absolute; top: 1rem; right: 1rem; background: #ef4444; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">
                {{ $pendingVerifikasi }}
            </span>
            @endif
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-user-check"></i> Verifikasi Pendaftaran Guru
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem; flex-grow: 1;">
                Verifikasi dan approve pendaftaran guru yang masuk ke sistem
            </p>
            <div>
                <a href="{{ route('admin.verifikasi-guru') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-clipboard-check"></i> Verifikasi Guru
                </a>
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
