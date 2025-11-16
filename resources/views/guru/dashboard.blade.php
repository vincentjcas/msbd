@extends('layouts.dashboard')

@section('title', 'Guru Dashboard')

@section('content')
<div class="welcome-card">
    <h2><i class="fas fa-chalkboard-teacher"></i> Selamat Datang, {{ get_first_name() }}!</h2>
    <p>Halo <strong>{{ auth()->user()->nama_lengkap }}</strong>, selamat datang di dashboard Guru.</p>
    <p>Anda dapat mencatat kehadiran, mengelola materi pembelajaran, dan memantau aktivitas siswa.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">--:--</div>
        <div class="stat-label">Jam Masuk Hari Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-school"></i>
        </div>
        <div class="stat-value">--</div>
        <div class="stat-label">Kelas Diampu</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-value">--</div>
        <div class="stat-label">Total Siswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-folder"></i>
        </div>
        <div class="stat-value">--</div>
        <div class="stat-label">Materi Diunggah</div>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-tasks"></i> Fitur Guru</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- 1. Absen Kehadiran -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-fingerprint"></i> Absen Kehadiran
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Mencatat jam masuk dan jam keluar setiap hari kerja
            </p>
            <button class="btn btn-primary btn-sm" onclick="absenMasuk()" style="margin-bottom: 10px">
                <i class="fas fa-sign-in-alt"></i> Absen Masuk
            </button>
            <button class="btn btn-secondary btn-sm" onclick="absenKeluar()">
                <i class="fas fa-sign-out-alt"></i> Absen Keluar
            </button>
        </div>

        <!-- 2. Absen Siswa -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-clipboard-check"></i> Absen Siswa
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Mencatat kehadiran siswa sesuai kelas yang diampu
            </p>
            <div style="margin-top: 42px;">
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur absen siswa akan tersedia')">
                    <i class="fas fa-check-square"></i> Isi Absen
                </button>
            </div>
        </div>

        <!-- 3. Konfirmasi Rapat Otomatis -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-users"></i> Konfirmasi Rapat
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Otomatis mengkonfirmasi kehadiran berdasarkan jadwal yang aktif
            </p>
            <div style="margin-top: 42px;">
                <span class="badge badge-success">Otomatis Terkonfirmasi</span>
            </div>
        </div>

        <!-- 4. Tolak Pengajuan Izin -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-times-circle"></i> Tolak Izin Siswa
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Menolak pengajuan izin siswa dengan notifikasi otomatis
            </p>
            <div style="margin-top: 42px;">
                <button class="btn btn-primary btn-sm" onclick="alert('Fitur tolak izin akan tersedia')">
                    <i class="fas fa-list"></i> Lihat Pengajuan
                </button>
            </div>
        </div>

        <!-- 5. Upload Materi -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-cloud-upload-alt"></i> Upload Materi
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Mengunggah materi pelajaran (PDF, PPT, DOCX, dll) sesuai mata pelajaran
            </p>
            <a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-upload"></i> Upload File
            </a>
        </div>

        <!-- 6. Lihat Data Kehadiran -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-chart-bar"></i> Data Kehadiran
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Melihat data kehadiran per kelas atau per bulan
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur data kehadiran akan tersedia')">
                <i class="fas fa-eye"></i> Lihat Data
            </button>
        </div>

        <!-- 7. Update/Hapus Materi -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-edit"></i> Kelola Materi
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Memperbarui atau menghapus file materi pembelajaran
            </p>
            <a href="{{ route('guru.materi') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-cog"></i> Kelola
            </a>
        </div>

        <!-- 8. Laporan Bulanan -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-file-alt"></i> Laporan Bulanan
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Melihat rekap kehadiran per bulan untuk evaluasi
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur laporan akan tersedia')">
                <i class="fas fa-download"></i> Download Laporan
            </button>
        </div>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-calendar-day"></i> Jadwal Mengajar Hari Ini</h3>
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <p>Tidak ada jadwal mengajar hari ini</p>
    </div>
</div>

<script>
function absenMasuk() {
    const now = new Date();
    const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    
    Swal.fire({
        title: 'Konfirmasi Absen Masuk',
        text: 'Anda akan absen masuk pada jam ' + time,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Absen Masuk',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Kirim ke backend
            Swal.fire('Berhasil!', 'Anda telah absen masuk pada ' + time, 'success');
        }
    });
}

function absenKeluar() {
    const now = new Date();
    const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    
    Swal.fire({
        title: 'Konfirmasi Absen Keluar',
        text: 'Anda akan absen keluar pada jam ' + time,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Absen Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Kirim ke backend
            Swal.fire('Berhasil!', 'Anda telah absen keluar pada ' + time, 'success');
        }
    });
}
</script>
@endsection