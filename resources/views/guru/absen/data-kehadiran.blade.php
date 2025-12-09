@extends('layouts.dashboard')

@section('title', 'Data Kehadiran')

@section('content')
<div style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('guru.absen.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Header -->
    <div class="text-center mb-5">
        <div style="display: inline-block; background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); padding: 1rem 2rem; border-radius: 1rem; margin-bottom: 1.5rem; box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);">
            <i class="fas fa-chart-bar" style="font-size: 3rem; color: white;"></i>
        </div>
        <h2 class="mb-3" style="font-weight: 800; color: #1f2937; font-size: 2.5rem;">
            Data Kehadiran
        </h2>
        <p class="text-muted" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Melihat data kehadiran per kelas atau per bulan</p>
    </div>

    <!-- Grid Mata Pelajaran -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; justify-items: center;">
        @forelse($mapels as $mapel)
            <a href="{{ route('guru.data-kehadiran-pertemuan', $mapel) }}" style="text-decoration: none; color: inherit; width: 100%; max-width: 400px;">
                <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; cursor: pointer; height: 100%;"
                     onmouseover="this.style.boxShadow='0 12px 30px rgba(59,130,246,0.2)'; this.style.transform='translateY(-8px)';"
                     onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                    
                    <!-- Header dengan Background Gradient -->
                    <div style="padding: 2rem; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; text-align: center;">
                        <i class="fas fa-book" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0; letter-spacing: 0.5px;">
                            {{ $mapel }}
                        </h3>
                    </div>

                    <!-- Content -->
                    <div style="padding: 2rem; background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%);">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <p style="color: #9ca3af; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Mata Pelajaran</p>
                            <p style="font-weight: 700; color: #1f2937; font-size: 1.25rem; margin: 0;">{{ $mapel }}</p>
                        </div>

                        <button style="width: 100%; padding: 1rem 1.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.75rem; transition: all 0.3s ease; font-size: 1.05rem; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);"
                                onmouseover="this.style.boxShadow='0 6px 20px rgba(245, 158, 11, 0.4)'; this.style.transform='scale(1.02)';"
                                onmouseout="this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.3)'; this.style.transform='scale(1)';">
                            <i class="fas fa-eye"></i> Lihat Data
                        </button>
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
