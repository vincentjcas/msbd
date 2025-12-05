@extends('layouts.dashboard')

@section('title', 'Jadwal Aktif')

@section('content')
<div class="header-section" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1><i class="fas fa-calendar-alt"></i> Jadwal Pembelajaran Aktif</h1>
        <p>Lihat jadwal pembelajaran semua guru dan kelas</p>
    </div>
    <x-dashboard-button />
</div>

@if($jadwalHariIni->count() > 0)
<div style="background: #e0f2fe; border-left: 4px solid #0369a1; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
    <h3 style="color: #0369a1; margin-top: 0;"><i class="fas fa-calendar-day"></i> Jadwal Hari Ini ({{ $hariIni }})</h3>
    <p style="color: #06b6d4; margin: 0.5rem 0 0 0;">{{ $jadwalHariIni->count() }} kelas sedang berlangsung</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    @foreach($jadwalHariIni as $j)
    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #22c55e;">
        <h4 style="color: #2d3748; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-book"></i> {{ $j->mata_pelajaran ?? 'N/A' }}
        </h4>
        <p style="color: #718096; margin: 0.5rem 0;"><strong>Kelas:</strong> {{ $j->kelas->nama_kelas ?? 'N/A' }}</p>
        <p style="color: #718096; margin: 0.5rem 0;"><strong>Guru:</strong> {{ $j->guru->nama_guru ?? 'N/A' }}</p>
        <p style="color: #718096; margin: 0.5rem 0;"><strong>Jam:</strong> {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}</p>
    </div>
    @endforeach
</div>
@endif

<div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <h3 style="margin-bottom: 1.5rem; color: #2d3748;">Jadwal Lengkap</h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f7fafc; border-bottom: 2px solid #0369a1;">
                <tr>
                    <th style="padding: 1rem; text-align: left; color: #2d3748; font-weight: 600;">Mata Pelajaran</th>
                    <th style="padding: 1rem; text-align: left; color: #2d3748; font-weight: 600;">Kelas</th>
                    <th style="padding: 1rem; text-align: left; color: #2d3748; font-weight: 600;">Guru</th>
                    <th style="padding: 1rem; text-align: center; color: #2d3748; font-weight: 600;">Hari</th>
                    <th style="padding: 1rem; text-align: center; color: #2d3748; font-weight: 600;">Jam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $j)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 1rem;"><strong>{{ $j->mata_pelajaran ?? 'N/A' }}</strong></td>
                        <td style="padding: 1rem;">{{ $j->kelas->nama_kelas ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">{{ $j->guru->nama_guru ?? 'N/A' }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="background: #0369a1; color: white; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">
                                {{ $j->hari }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #718096;">
                            Tidak ada jadwal pembelajaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .header-section {
        margin-bottom: 2rem;
    }

    .header-section h1 {
        font-size: 1.8rem;
        color: #2d3748;
        margin: 0 0 0.5rem 0;
    }

    .header-section p {
        color: #718096;
        margin: 0;
    }
</style>
@endsection
