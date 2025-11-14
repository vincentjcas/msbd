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

// Legacy register route (redirect ke pilihan)
Route::get('/register', function() {
    return redirect()->route('register.siswa');
})->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes dengan Middleware Auth
Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('role:admin');
    
    // Admin - Verifikasi Guru (DISABLED - Guru langsung aktif tanpa approval)
    // Route::get('/admin/verifikasi-guru', [AdminController::class, 'verifikasiGuru'])->name('admin.verifikasi-guru')->middleware('role:admin');
    // Route::post('/admin/verifikasi-guru/{id}/approve', [AdminController::class, 'approveGuru'])->name('admin.approve-guru')->middleware('role:admin');
    // Route::post('/admin/verifikasi-guru/{id}/reject', [AdminController::class, 'rejectGuru'])->name('admin.reject-guru')->middleware('role:admin');
    
    // Admin - Verifikasi Siswa Baru (untuk siswa dengan NIS tidak terdaftar di data master)
    Route::get('/admin/verifikasi-siswa', [AdminController::class, 'verifikasiSiswa'])->name('admin.verifikasi-siswa')->middleware('role:admin');
    Route::post('/admin/verifikasi-siswa/{id}/approve', [AdminController::class, 'approveSiswa'])->name('admin.approve-siswa')->middleware('role:admin');
    Route::post('/admin/verifikasi-siswa/{id}/reject', [AdminController::class, 'rejectSiswa'])->name('admin.reject-siswa')->middleware('role:admin');
    
    // Guru Dashboard
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard')->middleware('role:guru');
    
    // Siswa Dashboard
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard')->middleware('role:siswa');
    
    // Siswa Presensi
    Route::post('/siswa/presensi/submit', [SiswaController::class, 'submitAbsen'])->name('siswa.presensi.submit')->middleware('role:siswa');
    Route::get('/siswa/presensi/status', [SiswaController::class, 'statusAbsenHariIni'])->name('siswa.presensi.status')->middleware('role:siswa');
    
    // Kepala Sekolah Dashboard
    Route::get('/kepala-sekolah/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('kepala_sekolah.dashboard')->middleware('role:kepala_sekolah');

    // Pembina Dashboard
    Route::get('/pembina/dashboard', [PembinaController::class, 'dashboard'])->name('pembina.dashboard')->middleware('role:pembina');
    
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
