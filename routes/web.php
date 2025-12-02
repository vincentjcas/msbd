<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DbReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\PembinaController;

Route::get('/', function () {
    return view('homepage');
});

// DB report viewer (requires generate:db-report run first)
Route::get('/admin/db-report', [DbReportController::class, 'index'])->name('admin.db_report');

// Authentication Routes
use App\Http\Controllers\ForgotPasswordController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot-password.send-otp');
Route::get('/forgot-password/verify/{token}', [ForgotPasswordController::class, 'showOtpForm'])->name('forgot-password.verify-form');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot-password.verify-otp');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset-password.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.submit');

// Register Routes - Terpisah untuk Guru dan Siswa
Route::get('/register/guru', [AuthController::class, 'showRegisterGuruForm'])->name('register.guru');
Route::post('/register/guru', [AuthController::class, 'registerGuru'])->name('register.guru.submit');
Route::get('/register/siswa', [AuthController::class, 'showRegisterSiswaForm'])->name('register.siswa');
Route::post('/register/siswa', [AuthController::class, 'registerSiswa'])->name('register.siswa.submit');

// API Route untuk cek NIS siswa
Route::get('/api/check-nis/{nis}', [AuthController::class, 'checkNis'])->name('api.check-nis');

// API Route untuk get jadwal by kelas dan hari
Route::get('/api/jadwal', [SiswaController::class, 'getJadwal'])->middleware('auth')->name('api.jadwal');

// Legacy register route (redirect ke pilihan)
Route::get('/register', function() {
    return redirect()->route('register.siswa');
})->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes dengan Middleware Auth
Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('role:admin');
    
    // Admin - Verifikasi Guru
    Route::get('/admin/verifikasi-guru', [AdminController::class, 'verifikasiGuru'])->name('admin.verifikasi-guru')->middleware('role:admin');
    Route::post('/admin/verifikasi-guru/{id}/approve', [AdminController::class, 'approveGuru'])->name('admin.approve-guru')->middleware('role:admin');
    Route::post('/admin/verifikasi-guru/{id}/reject', [AdminController::class, 'rejectGuru'])->name('admin.reject-guru')->middleware('role:admin');
    
    // Admin - Verifikasi Siswa Baru (untuk siswa dengan NIS tidak terdaftar di data master)
    Route::get('/admin/verifikasi-siswa', [AdminController::class, 'verifikasiSiswa'])->name('admin.verifikasi-siswa')->middleware('role:admin');
    Route::post('/admin/verifikasi-siswa/{id}/approve', [AdminController::class, 'approveSiswa'])->name('admin.approve-siswa')->middleware('role:admin');
    Route::post('/admin/verifikasi-siswa/{id}/reject', [AdminController::class, 'rejectSiswa'])->name('admin.reject-siswa')->middleware('role:admin');
    
    // Admin - File Materi
    Route::get('/admin/file-materi', [AdminController::class, 'fileMateri'])->name('admin.file-materi')->middleware('role:admin');
    Route::delete('/admin/file-materi/{id}', [AdminController::class, 'deleteMateri'])->name('admin.file-materi.delete')->middleware('role:admin');
    
    // Admin - Kegiatan Sekolah
    Route::get('/admin/kegiatan', [AdminController::class, 'kegiatan'])->name('admin.kegiatan')->middleware('role:admin');
    Route::get('/admin/kegiatan/create', [AdminController::class, 'createKegiatan'])->name('admin.kegiatan.create')->middleware('role:admin');
    Route::post('/admin/kegiatan', [AdminController::class, 'storeKegiatan'])->name('admin.kegiatan.store')->middleware('role:admin');
    Route::get('/admin/kegiatan/{id}/edit', [AdminController::class, 'editKegiatan'])->name('admin.kegiatan.edit')->middleware('role:admin');
    Route::put('/admin/kegiatan/{id}', [AdminController::class, 'updateKegiatan'])->name('admin.kegiatan.update')->middleware('role:admin');
    Route::delete('/admin/kegiatan/{id}', [AdminController::class, 'deleteKegiatan'])->name('admin.kegiatan.delete')->middleware('role:admin');
    
    // Admin - Pengajuan Izin
    Route::get('/admin/pengajuan-izin', [AdminController::class, 'pengajuanIzin'])->name('admin.pengajuan-izin')->middleware('role:admin');
    Route::post('/admin/pengajuan-izin/{id}/approve', [AdminController::class, 'approveIzin'])->name('admin.pengajuan-izin.approve')->middleware('role:admin');
    Route::post('/admin/pengajuan-izin/{id}/reject', [AdminController::class, 'rejectIzin'])->name('admin.pengajuan-izin.reject')->middleware('role:admin');
    
    // Guru Dashboard
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard')->middleware('role:guru');
    Route::get('/guru/server-time', [GuruController::class, 'getServerTime'])->name('guru.server-time')->middleware('role:guru');
    Route::post('/guru/absen-masuk', [GuruController::class, 'absenMasuk'])->name('guru.absen-masuk')->middleware('role:guru');
    Route::post('/guru/absen-keluar', [GuruController::class, 'absenKeluar'])->name('guru.absen-keluar')->middleware('role:guru');
    
    // Guru - Materi Pembelajaran
    Route::get('/guru/materi', [GuruController::class, 'materi'])->name('guru.materi')->middleware('role:guru');
    Route::get('/guru/materi/create', [GuruController::class, 'createMateri'])->name('guru.materi.create')->middleware('role:guru');
    Route::post('/guru/materi', [GuruController::class, 'storeMateri'])->name('guru.materi.store')->middleware('role:guru');
    Route::delete('/guru/materi/{id}', [GuruController::class, 'deleteMateri'])->name('guru.materi.delete')->middleware('role:guru');
    
    // Guru - Tugas
    Route::get('/guru/tugas', [GuruController::class, 'tugas'])->name('guru.tugas')->middleware('role:guru');
    Route::get('/guru/tugas/create', [GuruController::class, 'createTugas'])->name('guru.tugas.create')->middleware('role:guru');
    Route::post('/guru/tugas', [GuruController::class, 'storeTugas'])->name('guru.tugas.store')->middleware('role:guru');
    Route::get('/guru/tugas/{id}', [GuruController::class, 'detailTugas'])->name('guru.tugas.detail')->middleware('role:guru');
    Route::post('/guru/tugas/{id}/nilai', [GuruController::class, 'nilaiTugas'])->name('guru.tugas.nilai')->middleware('role:guru');
    Route::delete('/guru/tugas/{id}', [GuruController::class, 'deleteTugas'])->name('guru.tugas.delete')->middleware('role:guru');
    
    // Siswa Dashboard
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard')->middleware('role:siswa');
    // Materi Pembelajaran - daftar materi dan download
    Route::get('/siswa/materi', [SiswaController::class, 'materi'])->name('siswa.materi')->middleware('role:siswa');
    Route::get('/siswa/materi/{id}/download', [SiswaController::class, 'downloadMateri'])->name('siswa.materi.download')->middleware('role:siswa');

    // Tugas siswa - daftar, detail dan submit
    Route::get('/siswa/tugas', [SiswaController::class, 'tugas'])->name('siswa.tugas')->middleware('role:siswa');
    Route::get('/siswa/tugas/{id}', [SiswaController::class, 'detailTugas'])->name('siswa.tugas.detail')->middleware('role:siswa');
    Route::post('/siswa/tugas/{id}/submit', [SiswaController::class, 'submitTugas'])->name('siswa.tugas.submit')->middleware('role:siswa');

    // Izin siswa - form dan submit
    Route::get('/siswa/izin/buat', [SiswaController::class, 'ajukanIzin'])->name('siswa.izin.create')->middleware('role:siswa');
    Route::post('/siswa/izin/submit', [SiswaController::class, 'submitAjukanIzin'])->name('siswa.izin.submit')->middleware('role:siswa');
    
    // Siswa Presensi
    Route::post('/siswa/presensi/submit', [SiswaController::class, 'submitAbsen'])->name('siswa.presensi.submit')->middleware('role:siswa');
    Route::get('/siswa/presensi/status', [SiswaController::class, 'statusAbsenHariIni'])->name('siswa.presensi.status')->middleware('role:siswa');
    
    // Kepala Sekolah Dashboard
    Route::get('/kepala-sekolah/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('kepala_sekolah.dashboard')->middleware('role:kepala_sekolah');
    
    // Kepala Sekolah - Grafik Kehadiran
    Route::get('/kepala-sekolah/grafik-kehadiran', [KepalaSekolahController::class, 'grafikKehadiran'])->name('kepala_sekolah.grafik-kehadiran')->middleware('role:kepala_sekolah');
    
    // Kepala Sekolah - Rekap Presensi
    Route::get('/kepala-sekolah/rekap-presensi', [KepalaSekolahController::class, 'rekapPresensi'])->name('kepala_sekolah.rekap-presensi')->middleware('role:kepala_sekolah');
    
    // Kepala Sekolah - Lihat Pengajuan Izin (Read-only)
    Route::get('/kepala-sekolah/izin', [KepalaSekolahController::class, 'izin'])->name('kepala_sekolah.izin')->middleware('role:kepala_sekolah');
    
    // Guru - Lihat Pengajuan Izin (hanya siswa di kelas yang diampu)
    Route::get('/guru/izin', [GuruController::class, 'izin'])->name('guru.izin')->middleware('role:guru');
    
    // Kepala Sekolah - Laporan Aktivitas
    Route::get('/kepala-sekolah/laporan', [KepalaSekolahController::class, 'laporan'])->name('kepala_sekolah.laporan')->middleware('role:kepala_sekolah');
    Route::post('/kepala-sekolah/laporan/{id}/review', [KepalaSekolahController::class, 'reviewLaporan'])->name('kepala_sekolah.laporan.review')->middleware('role:kepala_sekolah');
    Route::post('/kepala-sekolah/laporan/{id}/evaluasi', [KepalaSekolahController::class, 'createEvaluasi'])->name('kepala_sekolah.evaluasi.create')->middleware('role:kepala_sekolah');
    
    // Kepala Sekolah - Download Rekap
    Route::get('/kepala-sekolah/download-rekap', [KepalaSekolahController::class, 'downloadRekap'])->name('kepala_sekolah.download-rekap')->middleware('role:kepala_sekolah');

    // Pembina Dashboard
    Route::get('/pembina/dashboard', [PembinaController::class, 'dashboard'])->name('pembina.dashboard')->middleware('role:pembina');
    
    // Pembina - Statistik Kehadiran
    Route::get('/pembina/statistik-kehadiran', [PembinaController::class, 'statistikKehadiran'])->name('pembina.statistik-kehadiran')->middleware('role:pembina');
    Route::get('/pembina/statistik-kehadiran/kelas/{id}', [PembinaController::class, 'statistikKehadiranKelas'])->name('pembina.statistik-kehadiran.kelas')->middleware('role:pembina');
    
    // Pembina - Data Presensi (Read-only)
    Route::get('/pembina/presensi', [PembinaController::class, 'dataPresensi'])->name('pembina.presensi')->middleware('role:pembina');
    Route::get('/pembina/presensi/kelas/{id}', [PembinaController::class, 'dataPresensiKelas'])->name('pembina.presensi.kelas')->middleware('role:pembina');
    
    // Pembina - Jadwal Aktif
    Route::get('/pembina/jadwal', [PembinaController::class, 'jadwalAktif'])->name('pembina.jadwal')->middleware('role:pembina');
    
    // Pembina - Materi Pembelajaran
    Route::get('/pembina/materi', [PembinaController::class, 'materiPembelajaran'])->name('pembina.materi')->middleware('role:pembina');
    Route::get('/pembina/materi/{id}/download', [PembinaController::class, 'downloadMateri'])->name('pembina.materi.download')->middleware('role:pembina');
    
    // Pembina - Manajemen File Materi
    Route::get('/pembina/file-materi', [PembinaController::class, 'fileMateri'])->name('pembina.file-materi')->middleware('role:pembina');
    Route::post('/pembina/file-materi/upload', [PembinaController::class, 'uploadFileMateri'])->name('pembina.file-materi.upload')->middleware('role:pembina');
    Route::delete('/pembina/file-materi/{id}', [PembinaController::class, 'deleteFileMateri'])->name('pembina.file-materi.delete')->middleware('role:pembina');
    
    // Pembina - Catatan & Rekomendasi
    Route::get('/pembina/laporan-aktivitas', [PembinaController::class, 'laporanAktivitas'])->name('pembina.laporan-aktivitas')->middleware('role:pembina');
    Route::post('/pembina/catatan', [PembinaController::class, 'saveCatatan'])->name('pembina.catatan.save')->middleware('role:pembina');
        
    // Default Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'guru') {
            return redirect()->route('guru.dashboard');
        } elseif ($user->role == 'siswa') {
            return redirect()->route('siswa.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});
