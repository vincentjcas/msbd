@extends('layouts.dashboard')

@section('title', 'Roster Jadwal')

@section('content')
<div class="welcome-card">
    <h2><i class="fas fa-calendar-alt"></i> Roster Jadwal Pelajaran</h2>
    @if($siswa && $siswa->kelas)
    <p style="margin-top: 0.5rem;">
        <strong>Kelas:</strong>
        <span style="background: linear-gradient(135deg, #0369a1 0%, #06b6d4 0%, #14b8a6 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem;">
            {{ $siswa->kelas->nama_kelas }} - {{ $siswa->kelas->jurusan }}
        </span>
    </p>
    @endif
</div>

@if($jadwalPerHari->isEmpty())
<div class="content-section">
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <p>Belum ada jadwal pelajaran yang tersedia untuk kelas Anda</p>
    </div>
</div>
@else
<div class="content-section">
    @foreach($jadwalPerHari as $hari => $jadwalList)
    <div style="margin-bottom: 2rem;">
        <h3 class="section-title" style="background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%); color: white; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <i class="fas fa-calendar-day"></i> {{ $hari }}
        </h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                    <tr>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #cbd5e1; color: #1e293b; font-weight: 600;">
                            <i class="fas fa-clock"></i> Jam
                        </th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #cbd5e1; color: #1e293b; font-weight: 600;">
                            <i class="fas fa-book"></i> Mata Pelajaran
                        </th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #cbd5e1; color: #1e293b; font-weight: 600;">
                            <i class="fas fa-chalkboard-teacher"></i> Guru Pengajar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwalList as $jadwal)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 1rem; color: #475569;">
                            <span style="background: #f1f5f9; padding: 0.4rem 0.75rem; border-radius: 6px; display: inline-block; font-weight: 500;">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </span>
                        </td>
                        <td style="padding: 1rem; color: #1e293b; font-weight: 500;">
                            {{ $jadwal->mata_pelajaran }}
                        </td>
                        <td style="padding: 1rem; color: #475569;">
                            @if($jadwal->guru && $jadwal->guru->user)
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-user-circle" style="color: #0369a1;"></i>
                                    {{ $jadwal->guru->user->nama_lengkap }}
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Guru belum ditentukan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endif

<div style="margin-top: 2rem;">
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<style>
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.empty-state i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state p {
    font-size: 1.1rem;
    color: #64748b;
}

@media (max-width: 768px) {
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.75rem !important;
    }
    
    .section-title {
        font-size: 1rem;
    }
}
</style>
@endsection
