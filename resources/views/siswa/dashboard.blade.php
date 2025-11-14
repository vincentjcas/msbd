@extends('layouts.dashboard')

@section('title', 'Siswa Dashboard')

@section('content')
<div class="welcome-card">
    <h2><i class="fas fa-user-graduate"></i> Selamat Datang, {{ get_first_name() }}!</h2>
    <p>Halo <strong>{{ auth()->user()->nama_lengkap }}</strong>, selamat datang di dashboard Siswa.</p>
    @if(auth()->user()->siswa && auth()->user()->siswa->kelas)
    <p style="margin-top: 0.5rem;">
        <strong>Kelas:</strong> 
        <span style="background: linear-gradient(135deg, #0369a1 0%, #06b6d4 0%, #14b8a6 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem;">
            {{ auth()->user()->siswa->kelas->nama_kelas }} - {{ auth()->user()->siswa->kelas->jurusan }}
        </span>
    </p>
    @endif
    <p style="margin-top: 0.5rem; color: #ffffff;">Anda dapat mengisi kehadiran, mengajukan izin, dan mengakses materi pembelajaran.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-value">--%</div>
        <div class="stat-label">Kehadiran Bulan Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-value">--</div>
        <div class="stat-label">Materi Tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-value">--</div>
        <div class="stat-label">Pengajuan Izin</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-value">--</div>
        <div class="stat-label">Jadwal Hari Ini</div>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-tasks"></i> Fitur Siswa</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- 1. Isi Absen Harian -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-check-circle"></i> Isi Absen Harian
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Mengisi absen harian berdasarkan jadwal yang sedang aktif (manual)
            </p>
            <button class="btn btn-primary btn-sm" onclick="isiAbsen()">
                <i class="fas fa-hand-paper"></i> Isi Absen Sekarang
            </button>
        </div>

        <!-- 2. Lihat Jadwal -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-calendar"></i> Lihat Jadwal
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Melihat jadwal harian dan status kehadiran rapat
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur jadwal akan tersedia')">
                <i class="fas fa-eye"></i> Lihat Jadwal
            </button>
        </div>

        <!-- 3. Ajukan Izin -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-file-medical"></i> Ajukan Izin
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Mengajukan izin ketidakhadiran dengan bukti (foto atau surat)
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur ajukan izin akan tersedia')">
                <i class="fas fa-plus-square"></i> Ajukan Izin
            </button>
        </div>

        <!-- 4. Download Materi -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-download"></i> Materi Pembelajaran
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Mengunduh materi pembelajaran berdasarkan mata pelajaran
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur download materi akan tersedia')">
                <i class="fas fa-folder-open"></i> Lihat Materi
            </button>
        </div>

        <!-- 5. Kehadiran Bulanan -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-chart-pie"></i> Persentase Kehadiran
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Melihat persentase kehadiran per bulan
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur kehadiran akan tersedia')">
                <i class="fas fa-chart-bar"></i> Lihat Data
            </button>
        </div>

        <!-- 6. Status Izin -->
        <div style="padding: 1.5rem; background: #f7fafc; border-radius: 10px; border-left: 4px solid #0369a1;">
            <h4 style="color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-info-circle"></i> Status Izin
            </h4>
            <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                Melihat status pengajuan izin dan verifikasi guru
            </p>
            <button class="btn btn-primary btn-sm" onclick="alert('Fitur status izin akan tersedia')">
                <i class="fas fa-list-alt"></i> Cek Status
            </button>
        </div>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-calendar-day"></i> Jadwal Pelajaran Hari Ini</h3>
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <p>Tidak ada jadwal pelajaran hari ini</p>
    </div>
</div>

<div class="content-section">
    <h3 class="section-title"><i class="fas fa-book-open"></i> Materi Terbaru</h3>
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <p>Belum ada materi pembelajaran yang diunggah</p>
    </div>
</div>

<script>
function isiAbsen() {
    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    Swal.fire({
        title: 'Konfirmasi Kehadiran',
        html: `
            <p style="margin-bottom: 1rem;">Tanggal: <strong>${tanggal}</strong></p>
            <p style="margin-bottom: 1rem;">Anda akan mengisi kehadiran untuk hari ini</p>
            <label style="display: block; margin-bottom: 0.5rem; text-align: left;">Status Kehadiran:</label>
            <select id="statusAbsen" class="swal2-input" style="width: 80%;">
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
            </select>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Kirim Absen',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const status = document.getElementById('statusAbsen').value;
            return { status: status };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: Kirim ke backend
            const statusText = result.value.status === 'hadir' ? 'Hadir' : 
                              result.value.status === 'izin' ? 'Izin' : 'Sakit';
            Swal.fire('Berhasil!', `Kehadiran Anda tercatat sebagai: ${statusText}`, 'success');
        }
    });
}
</script>

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
@endsection