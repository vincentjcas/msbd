@extends('layouts.dashboard')

@section('title', 'Data Kehadiran')

@section('content')
<div style="padding: 2rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 4px; height: 32px; background: linear-gradient(180deg, #0ea5e9 0%, #06b6d4 100%); border-radius: 2px;"></div>
                <div>
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #1f2937; margin: 0;">Data Kehadiran</h2>
                    <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.9375rem;">Melihat data kehadiran per kelas atau per bulan</p>
                </div>
            </div>
            <x-dashboard-button />
        </div>
    </div>

    <!-- Grid Mata Pelajaran -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @forelse($mapels as $mapel)
            <a href="{{ route('guru.data-kehadiran-pertemuan', $mapel) }}" style="text-decoration: none; color: inherit;">
                <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; transition: all 0.3s ease; cursor: pointer; height: 100%;"
                     onmouseover="this.style.boxShadow='0 10px 15px rgba(0,0,0,0.15)'; this.style.transform='translateY(-4px)';"
                     onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                    
                    <!-- Header dengan Background Gradient -->
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-book" style="font-size: 1.5rem;"></i>
                            {{ $mapel }}
                        </h3>
                    </div>

                    <!-- Content -->
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 500;">MATA PELAJARAN</p>
                                <p style="font-weight: 600; color: #1f2937; font-size: 1rem; margin: 0;">{{ $mapel }}</p>
                            </div>

                            <div style="padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <button style="width: 100%; padding: 0.75rem 1rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease;"
                                        onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.15)'"
                                        onmouseout="this.style.boxShadow='none'">
                                    <i class="fas fa-eye"></i> Lihat Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem;">
                <p style="color: #9ca3af; font-size: 1.125rem;">Belum ada mata pelajaran yang tersedia</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
