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
    ]);

    $user = User::create([
        'username' => $request->name,
        'nama_lengkap' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status_aktif' => 1,
    ]);

    // ✅ Simpan pesan sukses ke session
    session()->flash('success', "Akun anda berhasil dibuat, {$user->nama_lengkap}!");

    // ✅ Arahkan ke halaman login
    return redirect()->route('login');


    }
}
