<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\PembinaController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes dengan Middleware Auth
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('role:admin');
    
    // Guru Dashboard
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard')->middleware('role:guru');
    
    // Siswa Dashboard
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard')->middleware('role:siswa');

    // Staf Dashboard
    Route::get('/staf/dashboard', [StafController::class, 'dashboard'])->name('staf.dashboard')->middleware('role:staf');

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
