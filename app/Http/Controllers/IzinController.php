<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Views\VStatusIzinSiswa;
use App\Services\DatabaseProcedureService;
use App\Services\LogActivityService;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    protected $dbProcedure;
    protected $logActivity;

    public function __construct(DatabaseProcedureService $dbProcedure, LogActivityService $logActivity)
    {
        $this->dbProcedure = $dbProcedure;
        $this->logActivity = $logActivity;
    }

    /**
     * Tampilkan daftar izin (untuk Guru/Kepala Sekolah)
     */
    public function index()
    {
        $izinList = Izin::with(['user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('izin.index', compact('izinList'));
    }

    /**
     * Tampilkan status izin siswa
     */
    public function statusIzinSiswa()
    {
        $izinSiswa = VStatusIzinSiswa::orderBy('tanggal_pengajuan', 'desc')->get();
        return view('izin.status-siswa', compact('izinSiswa'));
    }

    /**
     * Approve atau reject izin*/
    public function approve(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        $idApprover = auth()->user()->id_user;

        // Gunakan stored procedure
        $this->dbProcedure->approveIzin(
            $id,
            $idApprover,
            $request->status,
            $request->catatan
        );

        // Log aktivitas
        $this->logActivity->logApprovalIzin($idApprover, $id, $request->status);

        return redirect()->back()->with('success', 'Status izin berhasil diupdate');
    }

    /**
     * Tampilkan izin pending (untuk approval) */
    public function pending()
    {
        $izinPending = Izin::pending()
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('izin.pending', compact('izinPending'));
    }
}
