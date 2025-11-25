<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\PresensiSiswa;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Izin;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Views\VRekapPresensiSiswa;
use App\Models\Views\VStatistikKehadiranKelas;
use App\Services\DatabaseProcedureService;
use App\Services\LogActivityService;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    protected $dbProcedure;
    protected $logActivity;

    public function __construct(DatabaseProcedureService $dbProcedure, LogActivityService $logActivity)
    {
        $this->dbProcedure = $dbProcedure;
        $this->logActivity = $logActivity;
    }

    public function dashboard()
    {
        $guru = auth()->user()->guru;
        
        // Jika guru tidak ditemukan, redirect ke login
        if (!$guru) {
            return redirect()->route('login')->with('error', 'Data guru tidak ditemukan');
        }
        
        $jadwalHariIni = Jadwal::where('id_guru', $guru->id_guru)
            ->where('hari', now()->locale('id')->dayName)
            ->with('kelas')
            ->get();

        $totalKelas = Jadwal::where('id_guru', $guru->id_guru)
            ->distinct('id_kelas')
            ->count('id_kelas');

        $totalMateri = Materi::where('id_guru', $guru->id_guru)->count();
        $totalTugas = Tugas::where('id_guru', $guru->id_guru)->count();

        // Ambil presensi hari ini
        $today = date('Y-m-d');
        $userId = auth()->user()->id_user;
        $presensiHariIni = Presensi::where('id_user', $userId)
            ->where('tanggal', $today)
            ->first();

        // Tentukan status absen
        $statusAbsen = [
            'sudah_masuk' => $presensiHariIni && !is_null($presensiHariIni->jam_masuk),
            'sudah_keluar' => $presensiHariIni && !is_null($presensiHariIni->jam_keluar),
            'jam_masuk' => $presensiHariIni->jam_masuk ?? null,
            'jam_keluar' => $presensiHariIni->jam_keluar ?? null,
        ];

        $view = view('guru.dashboard', compact('jadwalHariIni', 'totalKelas', 'totalMateri', 'totalTugas', 'statusAbsen'));
        return response($view)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function getServerTime()
    {
        return response()->json([
            'jam_sekarang' => now()->format('H:i:s'),
            'timestamp' => now()->timestamp
        ]);
    }

    public function absenMasuk()
    {
        $today = date('Y-m-d');
        $userId = auth()->user()->id_user;
        $jamSekarang = now()->format('H:i:s'); // Gunakan Carbon timezone-aware

        try {
            // Cek apakah sudah absen masuk hari ini
            $presensiHariIni = Presensi::where('id_user', $userId)
                ->where('tanggal', $today)
                ->lockForUpdate()
                ->first();

            if ($presensiHariIni && !is_null($presensiHariIni->jam_masuk)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Anda sudah absen masuk pada jam ' . $presensiHariIni->jam_masuk . '. Silakan absen keluar terlebih dahulu sebelum absen masuk lagi.'
                ]);
            }

            // Buat atau update presensi - SELALU update dengan jam sekarang
            $presensi = Presensi::updateOrCreate(
                [
                    'id_user' => $userId,
                    'tanggal' => $today,
                ],
                [
                    'jam_masuk' => $jamSekarang,
                    'jam_keluar' => null,  // Reset jam keluar
                    'status' => 'hadir',
                ]
            );

            $this->logActivity->log('presensi_masuk', $userId, 'Absen masuk - ' . $jamSekarang);

            return response()->json([
                'success' => true,
                'message' => '✅ Absen masuk berhasil dicatat pada jam ' . $jamSekarang,
                'jam_masuk' => $jamSekarang,
                'jam_keluar' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function absenKeluar()
    {
        $today = date('Y-m-d');
        $userId = auth()->user()->id_user;
        $jamSekarang = now()->format('H:i:s'); // Gunakan Carbon timezone-aware

        try {
            // Cek presensi hari ini
            $presensiHariIni = Presensi::where('id_user', $userId)
                ->where('tanggal', $today)
                ->lockForUpdate()
                ->first();

            // Validasi: harus sudah absen masuk
            if (!$presensiHariIni || is_null($presensiHariIni->jam_masuk)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Anda belum absen masuk. Silakan absen masuk terlebih dahulu'
                ]);
            }

            // Validasi: belum absen keluar
            if (!is_null($presensiHariIni->jam_keluar)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Anda sudah absen keluar pada jam ' . $presensiHariIni->jam_keluar
                ]);
            }

            // Update dengan jam_keluar - SELALU update dengan jam sekarang
            $presensi = $presensiHariIni->update([
                'jam_keluar' => $jamSekarang,
            ]);

            $this->logActivity->log('presensi_keluar', $userId, 'Absen keluar - ' . $jamSekarang);

            return response()->json([
                'success' => true,
                'message' => '✅ Absen keluar berhasil dicatat pada jam ' . $jamSekarang,
                'jam_masuk' => $presensiHariIni->jam_masuk,
                'jam_keluar' => $jamSekarang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function presensiSiswa()
    {
        $guru = auth()->user()->guru;
        $jadwal = Jadwal::where('id_guru', $guru->id_guru)->with('kelas')->get();
        
        return view('guru.presensi-siswa', compact('jadwal'));
    }

    public function inputPresensiSiswa(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_pelajaran,id_jadwal',
            'tanggal' => 'required|date',
            'siswa' => 'required|array',
        ]);

        foreach ($request->siswa as $idSiswa => $status) {
            PresensiSiswa::updateOrCreate(
                [
                    'id_siswa' => $idSiswa,
                    'id_jadwal' => $request->id_jadwal,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $status,
                    'diinput_oleh' => auth()->user()->id_user,
                ]
            );
        }

        $this->logActivity->log('presensi_siswa', auth()->user()->id_user, 'Input presensi siswa');

        return redirect()->back()->with('success', 'Presensi siswa berhasil dicatat');
    }

    public function rekapPresensiSiswa(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $guru = auth()->user()->guru;
        $kelasIds = Jadwal::where('id_guru', $guru->id_guru)->pluck('id_kelas');

        $rekap = VRekapPresensiSiswa::whereIn('id_kelas', $kelasIds)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('guru.rekap-presensi-siswa', compact('rekap', 'bulan', 'tahun'));
    }

    public function statistikKelas($idKelas)
    {
        $bulan = request()->input('bulan', date('m'));
        $tahun = request()->input('tahun', date('Y'));

        $statistik = VStatistikKehadiranKelas::where('id_kelas', $idKelas)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $kelas = Kelas::findOrFail($idKelas);

        return view('guru.statistik-kelas', compact('statistik', 'kelas', 'bulan', 'tahun'));
    }

    public function materi()
    {
        $guru = auth()->user()->guru;
        $materi = Materi::where('id_guru', $guru->id_guru)
            ->with('kelas')
            ->orderBy('uploaded_at', 'desc')
            ->paginate(20);

        return view('guru.materi.index', compact('materi'));
    }

    public function createMateri()
    {
        $guru = auth()->user()->guru;
        
        // Ambil semua kelas yang tersedia
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('guru.materi.create', compact('kelas'));
    }

    public function storeMateri(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'mata_pelajaran' => 'required|string|max:100',
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('materi', $filename, 'public');

        $materi = Materi::create([
            'id_guru' => auth()->user()->guru->id_guru,
            'id_kelas' => $request->id_kelas,
            'judul_materi' => $request->judul,
            'mata_pelajaran' => $request->mata_pelajaran,
            'deskripsi' => $request->deskripsi,
            'file_materi' => $filename,
        ]);

        $this->logActivity->logCrud('create', auth()->user()->id_user, 'materi', $materi->id_materi);

        return redirect()->route('guru.materi')->with('success', 'Materi berhasil diupload');
    }

    public function deleteMateri($id)
    {
        $materi = Materi::findOrFail($id);
        
        if ($materi->id_guru !== auth()->user()->guru->id_guru) {
            abort(403);
        }

        if ($materi->file_materi && Storage::disk('public')->exists('materi/' . $materi->file_materi)) {
            Storage::disk('public')->delete('materi/' . $materi->file_materi);
        }

        $materi->delete();
        $this->logActivity->logCrud('delete', auth()->user()->id_user, 'materi', $id);

        return redirect()->route('guru.materi')->with('success', 'Materi berhasil dihapus');
    }

    public function tugas()
    {
        $guru = auth()->user()->guru;
        $tugas = Tugas::where('id_guru', $guru->id_guru)
            ->with(['kelas', 'pengumpulan'])
            ->orderBy('deadline', 'desc')
            ->get();

        return view('guru.tugas.index', compact('tugas'));
    }

    public function createTugas()
    {
        $guru = auth()->user()->guru;
        $kelas = Jadwal::where('id_guru', $guru->id_guru)
            ->with('kelas')
            ->get()
            ->pluck('kelas')
            ->unique('id_kelas')
            ->filter();

        // Jika guru belum punya jadwal, fallback ke semua kelas aktif
        if ($kelas->isEmpty()) {
            $kelas = \App\Models\Kelas::all();
        }

        return view('guru.tugas.create', compact('kelas'));
    }

    public function storeTugas(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'judul_tugas' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date|after:now',
        ]);

        $tugas = Tugas::create([
            'id_guru' => auth()->user()->guru->id_guru,
            'id_kelas' => $request->id_kelas,
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
        ]);

        $this->logActivity->logCrud('create', auth()->user()->id_user, 'tugas', $tugas->id_tugas);

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil ditambahkan');
    }

    public function detailTugas($id)
    {
        $tugas = Tugas::with(['kelas', 'pengumpulan.siswa.user'])->findOrFail($id);
        
        if ($tugas->id_guru !== auth()->user()->guru->id_guru) {
            abort(403);
        }

        return view('guru.tugas.detail', compact('tugas'));
    }

    public function nilaiTugas(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $pengumpulan = PengumpulanTugas::findOrFail($id);
        $pengumpulan->update([
            'nilai' => $request->nilai,
            'feedback_guru' => $request->feedback,
        ]);

        $this->logActivity->log('penilaian', auth()->user()->id_user, 'Menilai tugas ID: ' . $id);

        return redirect()->back()->with('success', 'Nilai berhasil diberikan');
    }

    public function deleteTugas($id)
    {
        $tugas = Tugas::findOrFail($id);
        
        // Check if user is owner
        if ($tugas->id_guru !== auth()->user()->guru->id_guru) {
            abort(403);
        }

        // Delete all pengumpulan first
        PengumpulanTugas::where('id_tugas', $id)->delete();
        
        // Delete tugas
        $tugas->delete();

        $this->logActivity->logCrud('delete', auth()->user()->id_user, 'tugas', $id);

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil dihapus');
    }

    public function izin()
    {
        $guru = auth()->user()->guru;
        $kelasIds = Jadwal::where('id_guru', $guru->id_guru)->pluck('id_kelas');
        $siswaIds = Siswa::whereIn('id_kelas', $kelasIds)->pluck('id_user');

        $izinList = Izin::whereIn('id_user', $siswaIds)
            ->with(['user.siswa', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('guru.izin', compact('izinList'));
    }

    public function approveIzin(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        $this->dbProcedure->approveIzin(
            $id,
            auth()->user()->id_user,
            $request->status,
            $request->catatan
        );

        $this->logActivity->logApprovalIzin(auth()->user()->id_user, $id, $request->status);

        return redirect()->back()->with('success', 'Status izin berhasil diupdate');
    }
}
