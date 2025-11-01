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
        $jadwalHariIni = Jadwal::where('id_guru', $guru->id_guru)
            ->where('hari', now()->locale('id')->dayName)
            ->with('kelas')
            ->get();

        $totalKelas = Jadwal::where('id_guru', $guru->id_guru)
            ->distinct('id_kelas')
            ->count('id_kelas');

        $totalMateri = Materi::where('id_guru', $guru->id_guru)->count();
        $totalTugas = Tugas::where('id_guru', $guru->id_guru)->count();

        return view('guru.dashboard', compact('jadwalHariIni', 'totalKelas', 'totalMateri', 'totalTugas'));
    }

    public function presensi()
    {
        $today = date('Y-m-d');
        $presensi = Presensi::where('id_user', auth()->user()->id_user)
            ->where('tanggal', $today)
            ->first();

        return view('guru.presensi', compact('presensi'));
    }

    public function inputPresensi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        $this->dbProcedure->inputPresensiHarian(
            auth()->user()->id_user,
            $request->tanggal,
            $request->jam_masuk,
            $request->status,
            $request->keterangan
        );

        $this->logActivity->log('presensi', auth()->user()->id_user, 'Input presensi ' . $request->status);

        return redirect()->route('guru.presensi')->with('success', 'Presensi berhasil dicatat');
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
        $kelas = Jadwal::where('id_guru', $guru->id_guru)
            ->with('kelas')
            ->get()
            ->pluck('kelas')
            ->unique('id_kelas');

        return view('guru.materi.create', compact('kelas'));
    }

    public function storeMateri(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
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
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'file_name' => $filename,
            'file_size' => $file->getSize(),
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

        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();
        $this->logActivity->logCrud('delete', auth()->user()->id_user, 'materi', $id);

        return redirect()->route('guru.materi')->with('success', 'Materi berhasil dihapus');
    }

    public function tugas()
    {
        $guru = auth()->user()->guru;
        $tugas = Tugas::where('id_guru', $guru->id_guru)
            ->with('kelas')
            ->orderBy('deadline', 'desc')
            ->paginate(20);

        return view('guru.tugas.index', compact('tugas'));
    }

    public function createTugas()
    {
        $guru = auth()->user()->guru;
        $kelas = Jadwal::where('id_guru', $guru->id_guru)
            ->with('kelas')
            ->get()
            ->pluck('kelas')
            ->unique('id_kelas');

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
            'feedback' => $request->feedback,
        ]);

        $this->logActivity->log('penilaian', auth()->user()->id_user, 'Menilai tugas ID: ' . $id);

        return redirect()->back()->with('success', 'Nilai berhasil diberikan');
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
