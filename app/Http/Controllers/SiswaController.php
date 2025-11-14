<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Izin;
use App\Models\PresensiSiswa;
use App\Models\Views\VStatusIzinSiswa;
use App\Services\DatabaseFunctionService;
use App\Services\LogActivityService;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    protected $dbFunction;
    protected $logActivity;

    public function __construct(DatabaseFunctionService $dbFunction, LogActivityService $logActivity)
    {
        $this->dbFunction = $dbFunction;
        $this->logActivity = $logActivity;
    }

    public function dashboard()
{
    $user = auth()->user();
    $siswa = $user->siswa;

    if (!$siswa) {
        return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan atau belum dikaitkan dengan akun ini.');

    }

    $jadwalHariIni = Jadwal::where('id_kelas', $siswa->id_kelas)
        ->where('hari', now()->locale('id')->dayName)
        ->with(['guru.user'])
        ->get();

    $bulan = date('m');
    $tahun = date('Y');
    $persentaseKehadiran = $this->dbFunction->hitungPersentaseKehadiran($user->id_user, $bulan, $tahun);

    $totalMateri = Materi::where('id_kelas', $siswa->id_kelas)->count();
    $totalTugas = Tugas::where('id_kelas', $siswa->id_kelas)->count();
    $tugasSelesai = PengumpulanTugas::where('id_siswa', $siswa->id_siswa)->count();

    return view('siswa.dashboard', compact('jadwalHariIni', 'persentaseKehadiran', 'totalMateri', 'totalTugas', 'tugasSelesai'));
}



    public function jadwal()
    {
        $siswa = auth()->user()->siswa;
        $jadwal = Jadwal::where('id_kelas', $siswa->id_kelas)
            ->with(['guru.user'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
            ->get()
            ->groupBy('hari');

        return view('siswa.jadwal', compact('jadwal'));
    }

    public function presensi()
    {
        $siswa = auth()->user()->siswa;
        $presensi = PresensiSiswa::where('id_siswa', $siswa->id_siswa)
            ->with(['jadwal.kelas', 'jadwal.guru.user'])
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        $bulan = date('m');
        $tahun = date('Y');
        $persentase = $this->dbFunction->hitungPersentaseKehadiran(auth()->user()->id_user, $bulan, $tahun);

        return view('siswa.presensi', compact('presensi', 'persentase'));
    }

    public function materi()
    {
        $siswa = auth()->user()->siswa;
        $materi = Materi::where('id_kelas', $siswa->id_kelas)
            ->with(['guru.user'])
            ->orderBy('uploaded_at', 'desc')
            ->paginate(20);

        return view('siswa.materi', compact('materi'));
    }

    public function downloadMateri($id)
    {
        $materi = Materi::findOrFail($id);
        $siswa = auth()->user()->siswa;

        if ($materi->id_kelas !== $siswa->id_kelas) {
            abort(403);
        }

        $this->logActivity->log('download_materi', auth()->user()->id_user, 'Download materi: ' . $materi->judul);

        return response()->download(storage_path('app/public/' . $materi->file_path), $materi->file_name);
    }

    public function tugas()
    {
        $siswa = auth()->user()->siswa;
        $tugas = Tugas::where('id_kelas', $siswa->id_kelas)
            ->with(['guru.user'])
            ->withCount(['pengumpulan as sudah_mengumpulkan' => function ($query) use ($siswa) {
                $query->where('id_siswa', $siswa->id_siswa);
            }])
            ->orderBy('deadline', 'desc')
            ->paginate(20);

        return view('siswa.tugas', compact('tugas'));
    }

    public function detailTugas($id)
    {
        $tugas = Tugas::with(['guru.user'])->findOrFail($id);
        $siswa = auth()->user()->siswa;

        if ($tugas->id_kelas !== $siswa->id_kelas) {
            abort(403);
        }

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        return view('siswa.tugas-detail', compact('tugas', 'pengumpulan'));
    }

    public function submitTugas(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $tugas = Tugas::findOrFail($id);
        $siswa = auth()->user()->siswa;

        if ($tugas->id_kelas !== $siswa->id_kelas) {
            abort(403);
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('tugas', $filename, 'public');

        PengumpulanTugas::updateOrCreate(
            [
                'id_tugas' => $id,
                'id_siswa' => $siswa->id_siswa,
            ],
            [
                'file_path' => $path,
                'file_name' => $filename,
                'file_size' => $file->getSize(),
                'waktu_submit' => now(),
            ]
        );

        $this->logActivity->log('submit_tugas', auth()->user()->id_user, 'Submit tugas: ' . $tugas->judul_tugas);

        return redirect()->route('siswa.tugas')->with('success', 'Tugas berhasil dikumpulkan');
    }

    public function izin()
    {
        $izinList = VStatusIzinSiswa::where('id_siswa', auth()->user()->siswa->id_siswa)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('siswa.izin', compact('izinList'));
    }

    public function createIzin()
    {
        return view('siswa.izin-create');
    }

    public function storeIzin(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'id_user' => auth()->user()->id_user,
            'tanggal' => $request->tanggal,
            'alasan' => $request->alasan,
            'status_approval' => 'pending',
        ];

        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('izin', $filename, 'public');
            $data['bukti_file'] = $path;
        }

        $izin = Izin::create($data);
        $this->logActivity->log('submit_izin', auth()->user()->id_user, 'Mengajukan izin untuk tanggal: ' . $request->tanggal);

        return redirect()->route('siswa.izin')->with('success', 'Izin berhasil diajukan');
    }

    public function persentaseKehadiran()
    {
        $bulan = request()->input('bulan', date('m'));
        $tahun = request()->input('tahun', date('Y'));

        $persentase = $this->dbFunction->hitungPersentaseKehadiran(auth()->user()->id_user, $bulan, $tahun);

        return view('siswa.persentase-kehadiran', compact('persentase', 'bulan', 'tahun'));
    }

    /**
     * Submit absen siswa untuk jadwal hari ini
     * POST /siswa/presensi/submit
     */
    public function submitAbsen(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:hadir,izin,sakit',
                'keterangan' => 'nullable|string|max:255',
            ]);

            $user = auth()->user();
            $siswa = $user->siswa;

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            $today = now()->format('Y-m-d');
            $currentTime = now()->format('H:i:s');
            $dayName = now()->format('l'); // Monday, Tuesday, etc
            $dayMapping = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            ];
            $hari = $dayMapping[$dayName] ?? null;

            // Cek jadwal yang sedang aktif (berdasarkan jam pelajaran)
            $jadwalHariIni = Jadwal::where('id_kelas', $siswa->id_kelas)
                ->where('hari', $hari)
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();

            if (!$jadwalHariIni) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal pelajaran yang sedang aktif pada saat ini'
                ], 400);
            }

            // Cek apakah sudah pernah absen hari ini untuk jadwal ini
            $presensiExisting = PresensiSiswa::where('id_siswa', $siswa->id_siswa)
                ->where('id_jadwal', $jadwalHariIni->id_jadwal)
                ->where('tanggal', $today)
                ->first();

            if ($presensiExisting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah absen untuk mata pelajaran ini hari ini. Status: ' . ucfirst($presensiExisting->status),
                    'existing_status' => $presensiExisting->status,
                    'existing_at' => $presensiExisting->created_at->format('H:i:s')
                ], 409);
            }

            // Submit absen
            $presensi = PresensiSiswa::create([
                'id_siswa' => $siswa->id_siswa,
                'id_jadwal' => $jadwalHariIni->id_jadwal,
                'tanggal' => $today,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'status_verifikasi' => 'pending',
                'diinput_oleh_tipe' => 'siswa',
            ]);

            // Log activity
            $this->logActivity->log(
                'presensi_siswa_submit',
                $user->id_user,
                "Submit absen: {$request->status} untuk jadwal {$jadwalHariIni->id_jadwal}"
            );

            return response()->json([
                'success' => true,
                'message' => 'Absen berhasil dikirim! Status: ' . ucfirst($request->status),
                'data' => [
                    'id_presensi_siswa' => $presensi->id_presensi_siswa,
                    'status' => $presensi->status,
                    'status_verifikasi' => $presensi->status_verifikasi,
                    'tanggal' => $presensi->tanggal->format('d-m-Y'),
                    'jam_submit' => $presensi->created_at->format('H:i:s')
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lihat status absen siswa
     * GET /siswa/presensi/status
     */
    public function statusAbsenHariIni()
    {
        try {
            $user = auth()->user();
            $siswa = $user->siswa;

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            $today = now()->format('Y-m-d');
            $currentTime = now()->format('H:i:s');
            $dayName = now()->format('l'); // Monday, Tuesday, etc
            $dayMapping = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            ];
            $hari = $dayMapping[$dayName] ?? null;

            // Cek jadwal yang sedang aktif (berdasarkan jam pelajaran)
            $jadwalHariIni = Jadwal::where('id_kelas', $siswa->id_kelas)
                ->where('hari', $hari)
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();

            if (!$jadwalHariIni) {
                return response()->json([
                    'success' => true,
                    'sudah_absen' => false,
                    'message' => 'Tidak ada jadwal pelajaran yang sedang aktif pada saat ini',
                    'data' => null
                ]);
            }

            // Cek absen hari ini
            $absenHariIni = PresensiSiswa::where('id_siswa', $siswa->id_siswa)
                ->where('id_jadwal', $jadwalHariIni->id_jadwal)
                ->where('tanggal', $today)
                ->with('jadwal.guru.user')
                ->first();

            if ($absenHariIni) {
                return response()->json([
                    'success' => true,
                    'sudah_absen' => true,
                    'message' => 'Anda sudah melakukan absen hari ini',
                    'data' => [
                        'id_presensi_siswa' => $absenHariIni->id_presensi_siswa,
                        'status' => $absenHariIni->status,
                        'status_verifikasi' => $absenHariIni->status_verifikasi,
                        'mata_pelajaran' => $absenHariIni->jadwal->nama_matpel ?? 'N/A',
                        'guru' => $absenHariIni->jadwal->guru->user->nama_lengkap ?? 'N/A',
                        'jam_submit' => $absenHariIni->created_at->format('H:i:s'),
                        'verifikasi_at' => $absenHariIni->diverifikasi_at?->format('d-m-Y H:i:s')
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'sudah_absen' => false,
                'message' => 'Anda belum melakukan absen',
                'data' => [
                    'jadwal' => $jadwalHariIni->id_jadwal,
                    'nama_matpel' => $jadwalHariIni->nama_matpel ?? 'N/A',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
