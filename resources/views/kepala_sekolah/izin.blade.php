@extends('layouts.dashboard')

@section('content')
<style>

    .izin-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
    }

    .section-desc {
        color: #718096;
        margin-bottom: 2rem;
    }

    /* CARD HORIZONTAL */
    .izin-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.07);
        display: flex;
        padding: 1.5rem;
        gap: 1.5rem;
        margin-bottom: 1.8rem;
        transition: .25s ease;
        align-items: flex-start;
    }

    .izin-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }

    /* LEFT SIDE (Avatar + Name) */
    .izin-left {
        width: 180px;
        text-align: center;
    }

    .avatar-box {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        margin: 0 auto 0.7rem;
        background: #dbeafe;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2.2rem;
        color: #1e40af;
        font-weight: 700;
    }

    .name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .role {
        font-size: .9rem;
        color: #475569;
        margin-top: 2px;
    }

    /* RIGHT SIDE (Detail informasi) */
    .izin-right {
        flex: 1;
    }

    .top-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .jenis-izin {
        font-size: 1rem;
        font-weight: 700;
        color: #334155;
    }

    .tanggal {
        font-size: .9rem;
        color: #64748b;
    }

    /* Data rows */
    .data-row {
        margin-bottom: 14px;
    }

    .label {
        font-size: .9rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 4px;
        display: block;
    }

    .value-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: .65rem 1rem;
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }

    .btn-link {
        text-decoration: none;
        color: #0369a1;
        font-weight: 600;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        background: #dbeafe;
        border-left: 4px solid #1e40af;
        padding: 2rem;
        font-weight: 600;
        border-radius: 8px;
        color: #1e3a8a;
    }

</style>

<div class="izin-wrapper">

    <h2 class="section-title">Lihat Pengajuan Izin</h2>
    <p class="section-desc">Pantau semua pengajuan izin siswa dan guru</p>

    @forelse($izinList as $izin)
    <div class="izin-card">

        <!-- LEFT SIDE -->
        <div class="izin-left">

            <!-- Avatar inisial -->
            <div class="avatar-box">
                {{ strtoupper(substr($izin->user->nama_lengkap ?? $izin->user->name, 0, 1)) }}
            </div>

            <div class="name">
                {{ $izin->user->nama_lengkap ?? $izin->user->name }}
            </div>

            <div class="role">{{ ucfirst($izin->user->role) }}</div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="izin-right">

            <!-- Top Row -->
            <div class="top-row">
                <div class="jenis-izin">
                    {{ $izin->alasan === 'Sakit' ? 'Sakit' : 'Izin' }}
                </div>

                <div class="tanggal">
                    {{ $izin->tanggal ? \Carbon\Carbon::parse($izin->tanggal)->format('d M Y') : '-' }}
                </div>
            </div>

            <!-- Data Row -->
            <div class="data-row">
                <label class="label">Jenis Izin</label>
                <div class="value-box">
                    {{ $izin->alasan === 'Sakit' ? 'Sakit' : 'Izin' }}
                </div>
            </div>

            @if($izin->alasan !== 'Sakit')
            <div class="data-row">
                <label class="label">Alasan / Keterangan</label>
                <div class="value-box">{{ $izin->alasan }}</div>
            </div>
            @endif

            @if($izin->bukti_file)
            <div class="data-row">
                <label class="label">Bukti File</label>
                <div class="value-box">
                    <a class="btn-link" href="{{ asset('storage/' . $izin->bukti_file) }}" target="_blank">Lihat Bukti</a>
                </div>
            </div>
            @endif

        </div>

    </div>
    @empty

    <div class="empty-state">Tidak ada permohonan izin</div>

    @endforelse

    @if($izinList->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $izinList->links() }}
    </div>
    @endif

</div>

@endsection
