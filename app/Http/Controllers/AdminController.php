<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\KepalaSekolah;
use App\Models\Pembina;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\BackupLog;
use App\Services\LogActivityService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $logActivity;

    public function __construct(LogActivityService $logActivity)
    {
        $this->logActivity = $logActivity;
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalGuru = User::where('role', 'guru')->where('status_aktif', 1)->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKelas = Kelas::count();
        $pendingVerifikasi = User::where('role', 'guru')->where('status_aktif', 0)->count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalGuru', 'totalSiswa', 'totalKelas', 'pendingVerifikasi'));
    }

    public function users()
    {
        $users = User::with(['guru', 'siswa', 'kepalaSekolah', 'pembina'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $kelas = Kelas::all();
        return view('admin.users.create', compact('kelas'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,kepala_sekolah,pembina,guru,siswa',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'status_aktif' => 1,
            ]);

            if ($request->role === 'guru') {
                Guru::create([
                    'id_user' => $user->id_user,
                    'nip' => $request->nip,
                    'mata_pelajaran' => $request->mata_pelajaran,
                    'jabatan' => $request->jabatan,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);
            } elseif ($request->role === 'siswa') {
                Siswa::create([
                    'id_user' => $user->id_user,
                    'id_kelas' => $request->id_kelas,
                    'nis' => $request->nis,
                    'nisn' => $request->nisn,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'nama_ortu' => $request->nama_ortu,
                    'no_hp_ortu' => $request->no_hp_ortu,
                ]);
            } elseif ($request->role === 'kepala_sekolah') {
                KepalaSekolah::create([
                    'id_user' => $user->id_user,
                    'nip' => $request->nip,
                    'periode_mulai' => $request->periode_mulai,
                    'periode_selesai' => $request->periode_selesai,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);
            } elseif ($request->role === 'pembina') {
                Pembina::create([
                    'id_user' => $user->id_user,
                    'nip' => $request->nip,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);
            }

            $this->logActivity->logCrud('create', auth()->user()->id_user, 'users', $user->id_user);

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        $user = User::with(['guru', 'siswa', 'kepalaSekolah', 'pembina'])->findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.users.edit', compact('user', 'kelas'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => 'required|unique:users,username,' . $id . ',id_user',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'no_hp' => 'nullable|string|max:15',
            'status_aktif' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'status_aktif' => $request->status_aktif,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if ($user->role === 'guru' && $user->guru) {
                $user->guru->update([
                    'nip' => $request->nip,
                    'mata_pelajaran' => $request->mata_pelajaran,
                    'jabatan' => $request->jabatan,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);
            } elseif ($user->role === 'siswa' && $user->siswa) {
                $user->siswa->update([
                    'id_kelas' => $request->id_kelas,
                    'nis' => $request->nis,
                    'nisn' => $request->nisn,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'nama_ortu' => $request->nama_ortu,
                    'no_hp_ortu' => $request->no_hp_ortu,
                ]);
            }

            $this->logActivity->logCrud('update', auth()->user()->id_user, 'users', $user->id_user);

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->role === 'siswa' && $user->siswa) {
                db_procedure()->hapusSiswa($user->siswa->id_siswa);
            } else {
                $user->delete();
            }

            $this->logActivity->logCrud('delete', auth()->user()->id_user, 'users', $id);

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function jadwal()
    {
        $jadwal = Jadwal::with(['kelas', 'guru.user'])->orderBy('hari')->get();
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function createJadwal()
    {
        $kelas = Kelas::all();
        $guru = Guru::with('user')->get();
        return view('admin.jadwal.create', compact('kelas', 'guru'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:guru,id_guru',
            'mata_pelajaran' => 'required|string|max:100',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = Jadwal::create($request->all());
        $this->logActivity->logCrud('create', auth()->user()->id_user, 'jadwal_pelajaran', $jadwal->id_jadwal);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function updateJadwal(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:guru,id_guru',
            'mata_pelajaran' => 'required|string|max:100',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal->update($request->all());
        $this->logActivity->logCrud('update', auth()->user()->id_user, 'jadwal_pelajaran', $jadwal->id_jadwal);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diupdate');
    }

    public function deleteJadwal($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        
        $this->logActivity->logCrud('delete', auth()->user()->id_user, 'jadwal_pelajaran', $id);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dihapus');
    }

    public function backup()
    {
        $backups = BackupLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.backup', compact('backups'));
    }

    public function createBackup()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            $command = sprintf(
                'mysqldump -h %s -u %s -p%s %s > %s',
                config('database.connections.mysql.host'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $backupPath
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0 && file_exists($backupPath)) {
                $fileSize = filesize($backupPath);
                
                BackupLog::create([
                    'nama_file' => $filename,
                    'ukuran_file' => $fileSize,
                    'lokasi_file' => $backupPath,
                    'dibuat_oleh' => auth()->user()->id_user,
                    'keterangan' => 'Backup database otomatis',
                ]);

                $this->logActivity->log('backup', auth()->user()->id_user, 'Backup database berhasil dibuat: ' . $filename);

                return redirect()->route('admin.backup')->with('success', 'Backup berhasil dibuat');
            } else {
                return back()->with('error', 'Gagal membuat backup');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function logAktivitas()
    {
        $logs = \App\Models\LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.log-aktivitas', compact('logs'));
    }

    public function verifikasiGuru()
    {
        $pendingGuru = User::where('role', 'guru')
            ->where('status_aktif', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.verifikasi-guru', compact('pendingGuru'));
    }

    public function approveGuru($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->role !== 'guru' || $user->status_aktif == 1) {
                return redirect()->back()->with('error', 'User tidak valid untuk diverifikasi');
            }

            // Aktifkan user
            $user->update(['status_aktif' => 1]);

            // Buat record guru jika belum ada
            if (!$user->guru) {
                Guru::create([
                    'id_user' => $user->id_user,
                    'nip' => 'NIP' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT),
                ]);
            }

            $this->logActivity->log('approve_guru', auth()->user()->id_user, 'Approve pendaftaran guru: ' . $user->nama_lengkap);

            DB::commit();
            return redirect()->route('admin.verifikasi-guru')->with('success', 'Pendaftaran guru ' . $user->nama_lengkap . ' berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal approve guru: ' . $e->getMessage());
        }
    }

    public function rejectGuru(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->role !== 'guru' || $user->status_aktif == 1) {
                return redirect()->back()->with('error', 'User tidak valid untuk ditolak');
            }

            $namaGuru = $user->nama_lengkap;
            $alasan = $request->input('alasan', 'Tidak memenuhi kriteria');

            // Log sebelum hapus
            $this->logActivity->log('reject_guru', auth()->user()->id_user, 'Reject pendaftaran guru: ' . $namaGuru . '. Alasan: ' . $alasan);

            // Hapus user
            $user->delete();

            DB::commit();
            return redirect()->route('admin.verifikasi-guru')->with('success', 'Pendaftaran guru ' . $namaGuru . ' ditolak dan dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal reject guru: ' . $e->getMessage());
        }
    }
}
