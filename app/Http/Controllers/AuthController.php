<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;

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
     * Tampilkan form register guru
     */
    public function showRegisterGuruForm()
    {
        return view('auth.register-guru');
    }

    /**
     * Tampilkan form register siswa
     */
    public function showRegisterSiswaForm()
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('auth.register-siswa', compact('kelas'));
    }

    /**
     * Proses register guru baru (tanpa approval admin)
     */
    public function registerGuru(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:guru,nip|max:30',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|regex:/^[0-9]{10,15}$/|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'mata_pelajaran' => 'required|array|min:1',
            'mata_pelajaran.*' => 'required|string',
            'password' => 'required|confirmed|min:6',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP harus berisi 10-15 digit angka.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'agama.required' => 'Agama wajib dipilih.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib dipilih.',
            'mata_pelajaran.min' => 'Pilih minimal 1 mata pelajaran.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        DB::beginTransaction();
        try {
            // Generate nama_lengkap dari NIP (bisa diupdate nanti)
            $namaLengkap = 'Guru-' . $request->nip;

            // Generate username dari NIP
            $username = 'guru_' . $request->nip;

            // Buat user - GURU LANGSUNG AKTIF (status_aktif = 1)
            $user = User::create([
                'username' => $username,
                'nama_lengkap' => $namaLengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru',
                'status_aktif' => 1, // ✅ Langsung aktif tanpa approval
            ]);

            // Convert array mata pelajaran ke JSON
            $mataPelajaranJson = json_encode($request->mata_pelajaran);

            // Buat data guru
            Guru::create([
                'id_user' => $user->id_user,
                'nip' => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'mata_pelajaran' => $mataPelajaranJson,
                'no_hp' => $request->no_hp,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses register siswa baru
     */
    public function registerSiswa(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswa,nis|max:20',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|regex:/^[0-9]{10,15}$/|max:20',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'sekolah_asal' => 'required|string|max:200',
            'alamat' => 'required|string|max:500',
            'password' => 'required|confirmed|min:6',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP harus berisi 10-15 digit angka.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'agama.required' => 'Agama wajib dipilih.',
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_kelas.exists' => 'Kelas tidak valid.',
            'sekolah_asal.required' => 'Sekolah asal wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        DB::beginTransaction();
        try {
            // Generate nama_lengkap dari NIS (bisa diupdate nanti)
            $namaLengkap = 'Siswa-' . $request->nis;

            // Generate username dari NIS
            $username = 'siswa_' . $request->nis;

            // Buat user - siswa langsung aktif
            $user = User::create([
                'username' => $username,
                'nama_lengkap' => $namaLengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'status_aktif' => 1,
            ]);

            // Buat data siswa
            Siswa::create([
                'id_user' => $user->id_user,
                'nis' => $request->nis,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'id_kelas' => $request->id_kelas,
                'sekolah_asal' => $request->sekolah_asal,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form register (LEGACY - backward compatibility)
     */
    public function showRegisterForm()
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('auth.register', compact('kelas'));
    }

    /**
     * Proses register user baru (LEGACY - backward compatibility)
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
        Siswa::create([
            'id_user' => $user->id_user,
            'nis' => 'NIS' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT), // Generate NIS otomatis
            'id_kelas' => $request->id_kelas, // Simpan kelas yang dipilih
        ]);
    } elseif ($request->role === 'guru') {
        Guru::create([
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
