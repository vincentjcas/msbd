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
        $siswa = auth()->user()->siswa;
        $jadwalHariIni = Jadwal::where('id_kelas', $siswa->id_kelas)
            ->where('hari', now()->locale('id')->dayName)
            ->with(['guru.user'])
            ->get();

        $bulan = date('m');
        $tahun = date('Y');
        $persentaseKehadiran = $this->dbFunction->hitungPersentaseKehadiran(auth()->user()->id_user, $bulan, $tahun);

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

        return Storage::disk('public')->download($materi->file_path, $materi->file_name);
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
}
