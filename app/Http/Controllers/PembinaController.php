<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\PresensiSiswa;
use App\Models\Jadwal;
use App\Models\JadwalStatus;
use App\Models\Materi;
use App\Models\LaporanAktivitas;
use App\Models\Views\VStatistikKehadiranKelas;
use App\Models\Views\VRekapPresensiGuruStaf;
use App\Services\DatabaseFunctionService;
use App\Services\LogActivityService;

class PembinaController extends Controller
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
        $statistik = VStatistikKehadiranKelas::all();
        
        $totalJadwal = Jadwal::count();
        $jadwalAktif = Jadwal::where('is_active', true)->count();
        $totalLaporan = LaporanAktivitas::count();
        $laporanPending = LaporanAktivitas::where('status', 'submitted')->count();

        return view('pembina.dashboard', compact('statistik', 'totalJadwal', 'jadwalAktif', 'totalLaporan', 'laporanPending'));
    }

    public function statistikKehadiran(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $statistikKelas = VStatistikKehadiranKelas::all();

        $rekapGuru = VRekapPresensiGuruStaf::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('pembina.statistik-kehadiran', compact('statistikKelas', 'rekapGuru', 'bulan', 'tahun'));
    }

    public function dataPresensi(Request $request)
    {
        $tipe = $request->input('tipe', 'guru');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        if ($tipe === 'guru') {
            $presensi = Presensi::with('user')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'desc')
                ->paginate(50);
        } else {
            $presensi = PresensiSiswa::with('siswa.user')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'desc')
                ->paginate(50);
        }

        return view('pembina.data-presensi', compact('presensi', 'tipe', 'startDate', 'endDate'));
    }

    public function statusJadwal()
    {
        $jadwal = Jadwal::with(['kelas', 'guru.user'])
            ->where('is_active', true)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        $jadwalStatus = JadwalStatus::with('jadwal')
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('pembina.status-jadwal', compact('jadwal', 'jadwalStatus'));
    }

    public function reviewMateri()
    {
        $materi = Materi::with(['jadwal.kelas', 'jadwal.guru.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $materiCompliance = [];
        foreach ($materi as $item) {
            $materiCompliance[$item->id_materi] = $this->dbFunction->checkMateriCompliance($item->id_materi);
        }

        return view('pembina.review-materi', compact('materi', 'materiCompliance'));
    }

    public function laporan()
    {
        $laporan = LaporanAktivitas::with(['pembina.user', 'guru.user'])
            ->where('id_pembina', auth()->user()->pembina->id_pembina ?? null)
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(20);

        return view('pembina.laporan', compact('laporan'));
    }

    public function detailLaporan($id)
    {
        $laporan = LaporanAktivitas::with(['pembina.user', 'guru.user', 'evaluasi'])
            ->findOrFail($id);

        if ($laporan->id_pembina !== auth()->user()->pembina->id_pembina ?? null) {
            abort(403, 'Unauthorized');
        }

        return view('pembina.detail-laporan', compact('laporan'));
    }

    public function createLaporan()
    {
        return view('pembina.create-laporan');
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'kegiatan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $laporan = LaporanAktivitas::create([
            'id_pembina' => auth()->user()->pembina->id_pembina,
            'id_guru' => $request->id_guru,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'kegiatan' => $request->kegiatan,
            'catatan' => $request->catatan,
            'status' => 'draft',
        ]);

        $this->logActivity->logCrud('create', auth()->user()->id_user, 'laporan_aktivitas', $laporan->id_laporan);

        return redirect()->route('pembina.laporan')->with('success', 'Laporan berhasil dibuat');
    }

    public function submitLaporan($id)
    {
        $laporan = LaporanAktivitas::findOrFail($id);

        if ($laporan->id_pembina !== auth()->user()->pembina->id_pembina ?? null) {
            abort(403, 'Unauthorized');
        }

        $laporan->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->logActivity->logCrud('update', auth()->user()->id_user, 'laporan_aktivitas', $id);

        return redirect()->back()->with('success', 'Laporan berhasil disubmit');
    }
}
