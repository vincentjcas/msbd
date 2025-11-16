@extends('layouts.dashboard')

@section('content')
<style>
    .page-title { font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; }
    .page-desc { color: #718096; margin-bottom: 2rem; }
    .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
    .alert-error { background: #fee2e2; border-left: 4px solid #dc2626; color: #7f1d1d; }
    .alert-success { background: #dcfce7; border-left: 4px solid #16a34a; color: #166534; }
    .alert ul { margin-top: 0.5rem; list-style: none; padding-left: 0; }
    .alert li { margin-top: 0.25rem; }
    .izin-card { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 1.5rem; overflow: hidden; }
    .izin-header { padding: 1.5rem; color: white; }
    .izin-header.pending { background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); }
    .izin-header.approved { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); }
    .izin-header.rejected { background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); }
    .izin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
    .izin-info { flex: 1; }
    .izin-label { font-size: 0.85rem; opacity: 0.9; }
    .izin-value { font-weight: 600; font-size: 1rem; }
    .izin-status { display: inline-block; padding: 0.35rem 0.85rem; background: white; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    .izin-body { padding: 1.5rem; }
    .izin-section { margin-bottom: 1rem; }
    .izin-section-label { font-size: 0.9rem; font-weight: 600; color: #4a5568; margin-bottom: 0.5rem; }
    .izin-section-content { background: #f7fafc; padding: 1rem; border-radius: 8px; color: #2d3748; line-height: 1.6; }
    .form-group { margin-bottom: 1rem; }
    .form-label { font-size: 0.9rem; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 8px; font-size: 0.9rem; font-family: inherit; }
    .form-control:focus { outline: none; border-color: #0369a1; box-shadow: 0 0 0 3px rgba(3, 105, 161, 0.1); }
    .btn-group { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
    .btn-approve { background: #16a34a; color: white; }
    .btn-approve:hover { background: #15803d; transform: translateY(-1px); }
    .btn-reject { background: #dc2626; color: white; }
    .btn-reject:hover { background: #b91c1c; transform: translateY(-1px); }
    .empty-state { background: #dbeafe; border-left: 4px solid #0369a1; padding: 2rem; border-radius: 8px; text-align: center; }
    .empty-state-text { color: #1e3a8a; font-weight: 600; }
</style>

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Header -->
    <div class="page-title">Manajemen Izin</div>
    <div class="page-desc">Approve atau reject permohonan izin guru dan siswa</div>

    <!-- Alerts -->
    @if($errors->any())
        <div class="alert alert-error">
            <strong>Error:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <strong>✓ {{ session('success') }}</strong>
        </div>
    @endif

    <!-- Izin List -->
    @forelse($izinList as $izin)
        <div class="izin-card">
            <div class="izin-header {{ $izin->status_approval }}">
                <div class="izin-grid">
                    <div class="izin-info">
                        <div class="izin-label">Nama Pemohon</div>
                        <div class="izin-value">{{ $izin->user->nama_lengkap ?? $izin->user->name }}</div>
                        <div class="izin-label" style="margin-top: 0.5rem;">{{ ucfirst($izin->user->role) }}</div>
                    </div>
                    <div class="izin-info">
                        <div class="izin-label">Jenis Izin</div>
                        <div class="izin-value" style="text-transform: uppercase;">{{ $izin->jenis_izin }}</div>
                        <div class="izin-label" style="margin-top: 0.5rem;">{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($izin->tanggal_akhir)->format('d M Y') }}</div>
                    </div>
                    <div class="izin-info" style="text-align: right;">
                        <div class="izin-label">Status</div>
                        <div class="izin-status" style="background: white; color: #2d3748;">
                            {{ ucfirst(str_replace('_', ' ', $izin->status_approval)) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="izin-body">
                <!-- Keterangan -->
                <div class="izin-section">
                    <div class="izin-section-label">Keterangan</div>
                    <div class="izin-section-content">{{ $izin->keterangan }}</div>
                </div>

                <!-- Action Buttons -->
                @if($izin->status_approval == 'pending')
                    <form method="POST" action="{{ route('kepala_sekolah.izin.approve', $izin->id_izin) }}">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="2" class="form-control" placeholder="Masukkan catatan..."></textarea>
                        </div>

                        <div class="btn-group">
                            <button type="submit" name="status" value="approved" class="btn btn-approve">✓ Approve</button>
                            <button type="submit" name="status" value="rejected" class="btn btn-reject">✗ Reject</button>
                        </div>
                    </form>
                @else
                    <div style="background: #f7fafc; padding: 1rem; border-radius: 8px; border-left: 4px solid #cbd5e0;">
                        <div style="font-size: 0.9rem; color: #4a5568; margin-bottom: 0.5rem;">Diproses oleh:</div>
                        <div style="font-weight: 600; color: #2d3748;">{{ $izin->approver->nama_lengkap ?? $izin->approver->name ?? 'System' }}</div>
                        <div style="font-size: 0.85rem; color: #718096; margin-top: 0.5rem;">{{ $izin->updated_at->format('d M Y H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <p class="empty-state-text">Tidak ada permohonan izin</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($izinList->hasPages())
        <div style="margin-top: 2rem;">
            {{ $izinList->links() }}
        </div>
    @endif
</div>
@endsection