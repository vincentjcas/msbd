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


    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        $request->session()->regenerate();
        
        $user = Auth::user();

        // ✅ Cek apakah user sudah diaktifkan (untuk guru yang perlu approval)
        if (!$user->status_aktif) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda belum diaktifkan. Silakan tunggu persetujuan dari admin.',
            ])->withInput();
        }

        // ✅ Simpan pesan selamat datang dengan nama depan
        $firstName = explode(' ', $user->nama_lengkap)[0];
        session()->flash('success', "Selamat datang kembali, {$firstName}!");

        // ✅ Redirect otomatis sesuai role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard'); // Redirect ke admin dashboard lama
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
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Tampilkan form register
     */
    public function showRegisterForm()
    {
        $kelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('auth.register', compact('kelas'));
    }

    /**
     * Proses register user baru
     */
    public function register(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'role' => 'required|in:guru,siswa',
    ];

    // Jika role siswa, id_kelas wajib diisi
    if ($request->role === 'siswa') {
        $rules['id_kelas'] = 'required|exists:kelas,id_kelas';
    }

    $request->validate($rules, [
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
        'id_kelas.required' => 'Kelas wajib dipilih untuk siswa.',
        'id_kelas.exists' => 'Kelas yang dipilih tidak valid.',
    ]);

    // Buat user
    // Guru perlu approval, jadi status_aktif = 0 (pending)
    // Siswa langsung aktif, status_aktif = 1
    $user = User::create([
        'username' => $request->name,
        'nama_lengkap' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status_aktif' => $request->role === 'guru' ? 0 : 1,
    ]);

    // Buat data siswa/guru berdasarkan role
    if ($request->role === 'siswa') {
        \App\Models\Siswa::create([
            'id_user' => $user->id_user,
            'nis' => 'NIS' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT), // Generate NIS otomatis
            'id_kelas' => $request->id_kelas, // Simpan kelas yang dipilih
        ]);
    } elseif ($request->role === 'guru') {
        \App\Models\Guru::create([
            'id_user' => $user->id_user,
            'nip' => 'NIP' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT), // Generate NIP otomatis
        ]);
    }

    // ✅ Simpan pesan sukses ke session
    if ($request->role === 'guru') {
        session()->flash('success', "Pendaftaran berhasil! Akun Anda menunggu persetujuan admin.");
    } else {
        session()->flash('success', "Akun anda berhasil dibuat, {$user->nama_lengkap}!");
    }

    // ✅ Arahkan ke halaman login
    return redirect()->route('login');


    }
}
