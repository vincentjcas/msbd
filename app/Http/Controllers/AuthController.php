<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
    ]);


    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();

        // ✅ Simpan pesan selamat datang (opsional)
        session()->flash('success', "Selamat datang kembali, {$user->nama_lengkap}!");

        // ✅ Redirect otomatis sesuai role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'guru':
                return redirect()->route('guru.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            case 'kepala_sekolah':
                return redirect()->route('kepala_sekolah.dashboard');
            case 'pembina':
                return redirect()->route('pembina.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}


    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Tampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses register user baru
     */
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'role' => 'required|in:admin,guru,siswa',
    ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'name.max' => 'Nama lengkap maksimal 255 karakter.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.confirmed' => 'Password tidak sama.',
        'password.min' => 'Panjang password harus 6 karakter atau lebih.',
        'role.required' => 'Role wajib dipilih.',
        'role.in' => 'Role tidak valid.',
    ]);

    // Buat user
    $user = User::create([
        'username' => $request->name,
        'nama_lengkap' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status_aktif' => 1,
    ]);

    // Buat data siswa/guru berdasarkan role
    if ($request->role === 'siswa') {
        \App\Models\Siswa::create([
            'id_user' => $user->id_user,
            'nis' => 'NIS' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT), // Generate NIS otomatis
        ]);
    } elseif ($request->role === 'guru') {
        \App\Models\Guru::create([
            'id_user' => $user->id_user,
            'nip' => 'NIP' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT), // Generate NIP otomatis
        ]);
    }

    // ✅ Simpan pesan sukses ke session
    session()->flash('success', "Akun anda berhasil dibuat, {$user->nama_lengkap}!");

    // ✅ Arahkan ke halaman login
    return redirect()->route('login');


    }
}
