<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Izin;
use App\Models\LaporanAktivitas;
use App\Models\EvaluasiKepsek;
use App\Models\Views\VGrafikKehadiranHarian;
use App\Models\Views\VGrafikKehadiranSiswaHarian;
use App\Models\Views\VRekapPresensiGuruStaf;
use App\Models\Views\VRekapPresensiSiswa;
use App\Services\DatabaseProcedureService;
use App\Services\LogActivityService;

class KepalaSekolahController extends Controller
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
        $totalGuru = User::where('role', 'guru')->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalPembina = User::where('role', 'pembina')->count();
        $izinPending = Izin::where('status_approval', 'pending')->count();

        return view('kepala_sekolah.dashboard', compact('totalGuru', 'totalSiswa', 'totalPembina', 'izinPending'));
    }

    public function grafikKehadiran(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $grafikGuru = VGrafikKehadiranHarian::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        $grafikSiswa = VGrafikKehadiranSiswaHarian::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        return view('kepala_sekolah.grafik-kehadiran', compact('grafikGuru', 'grafikSiswa', 'startDate', 'endDate'));
    }

    public function rekapPresensi(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tipe = $request->input('tipe', 'guru');

        if ($tipe === 'guru') {
            $rekap = VRekapPresensiGuruStaf::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->get();
        } else {
            $rekap = VRekapPresensiSiswa::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->get();
        }

        return view('kepala_sekolah.rekap-presensi', compact('rekap', 'bulan', 'tahun', 'tipe'));
    }

    public function izin()
    {
        $izinList = Izin::with(['user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('kepala_sekolah.izin', compact('izinList'));
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

    public function laporan()
    {
        $laporan = LaporanAktivitas::with(['pembina.user', 'guru.user'])
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(20);

        return view('kepala_sekolah.laporan', compact('laporan'));
    }

    public function reviewLaporan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:reviewed,approved,rejected',
            'catatan_kepsek' => 'nullable|string',
        ]);

        $laporan = LaporanAktivitas::findOrFail($id);
        $laporan->update([
            'status' => $request->status,
            'catatan_kepsek' => $request->catatan_kepsek,
            'reviewed_at' => now(),
        ]);

        $this->logActivity->logCrud('update', auth()->user()->id_user, 'laporan_aktivitas', $id);

        return redirect()->back()->with('success', 'Laporan berhasil direview');
    }

    public function createEvaluasi(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|in:catatan,rekomendasi,evaluasi',
            'isi_evaluasi' => 'required|string',
            'id_target_user' => 'required|exists:users,id_user',
        ]);

        $evaluasi = EvaluasiKepsek::create([
            'id_laporan' => $id,
            'id_target_user' => $request->id_target_user,
            'tipe' => $request->tipe,
            'isi_evaluasi' => $request->isi_evaluasi,
            'created_by' => auth()->user()->id_user,
        ]);

        $this->logActivity->logCrud('create', auth()->user()->id_user, 'evaluasi_kepsek', $evaluasi->id_evaluasi);

        return redirect()->back()->with('success', 'Evaluasi berhasil ditambahkan');
    }

    public function downloadRekap(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $rekap = $this->dbProcedure->rekapPresensiBulanan($bulan, $tahun, null);

        return view('kepala_sekolah.download-rekap', compact('rekap', 'bulan', 'tahun'));
    }
}
