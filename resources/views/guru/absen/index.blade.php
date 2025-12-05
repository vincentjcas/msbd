@extends('layouts.dashboard')

@section('title', 'Absen Siswa')

@section('content')
<style>
    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    
    .btn-icon-circle {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-control:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
    }

    .modal-scrollable .modal-body {
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 class="mb-2" style="font-weight: 700; color: #1f2937;">
                        <i class="fas fa-clipboard-check" style="color: #3b82f6;"></i> Kelola Absen Siswa
                    </h3>
                    <p class="text-muted">Buat dan kelola absen untuk setiap mata pelajaran yang Anda ampu</p>
                </div>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="{{ route('guru.data-kehadiran') }}" 
                       style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;"
                       onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';"
                       onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                        <i class="fas fa-eye"></i> Lihat Data
                    </a>
                    <x-dashboard-button />
                </div>
            </div>
        </div>
    </div>

    @if($mapels->isEmpty())
        <div class="alert alert-info border-0" style="background-color: #dbeafe; border-radius: 8px;">
            <i class="fas fa-info-circle"></i> 
            <strong>Belum ada mata pelajaran.</strong> Anda tidak memiliki jadwal mengajar saat ini.
        </div>
    @else
        <div class="row">
            @foreach($mapels as $index => $mapel)
                @php
                    // Warna gradient untuk setiap card
                    $gradients = [
                        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                        'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                        'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
                    ];
                    $gradient = $gradients[$index % count($gradients)];
                @endphp
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden; transition: all 0.3s ease; display: flex;">
                        <!-- Left: Content -->
                        <div style="flex: 1; padding: 2rem;">
                            <h5 style="font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; font-size: 1.1rem;">
                                {{ $mapel->mata_pelajaran ?? 'N/A' }}
                            </h5>
                            <p class="mb-3" style="color: #6b7280; font-size: 0.9rem;">
                                <i class="fas fa-users"></i> Kelas {{ $mapel->kelas->nama_kelas ?? 'N/A' }}
                            </p>

                            <!-- Guru Info -->
                            <div style="background-color: #f3f4f6; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                                <small style="color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">
                                    GURU
                                </small>
                                <p class="mb-0" style="color: #1f2937; font-weight: 600; font-size: 0.9rem;">
                                    {{ $mapel->guru->nama_lengkap ?? auth()->user()->nama_lengkap }}
                                </p>
                            </div>

                            <!-- Stats -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small style="color: #6b7280; font-size: 0.8rem;">Pertemuan</small>
                                    <div style="font-size: 1.3rem; font-weight: 700; color: #3b82f6;">
                                        {{ $mapel->total_absens ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small style="color: #6b7280; font-size: 0.8rem;">Jadwal</small>
                                    <div style="font-size: 1.3rem; font-weight: 700; color: #10b981;">
                                        {{ $mapel->jadwal_count ?? 0 }}
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('guru.absen.create', ['mata_pelajaran' => $mapel->mata_pelajaran, 'id_kelas' => $mapel->id_kelas]) }}" 
                               class="btn btn-sm"
                               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; font-weight: 600; padding: 0.65rem 1.5rem; border-radius: 6px; text-decoration: none; display: inline-block; transition: all 0.3s ease; width: 100%;">
                                <i class="fas fa-plus-circle"></i> Buat Absen
                            </a>
                        </div>

                        <!-- Right: Banner dengan Gradient -->
                        <div style="background: {{ $gradient }}; width: 140px; min-height: 220px; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                            <div style="position: absolute; opacity: 0.15; font-size: 5rem; color: white;">
                                <i class="fas fa-book"></i>
                            </div>
                            <div style="position: relative; text-align: center; color: white;">
                                <i class="fas fa-chalkboard" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                                <small style="font-weight: 600; font-size: 0.75rem;">{{ date('Y/m') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .card {
        transition: all 0.3s ease !important;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.4);
    }

    @media (max-width: 768px) {
        .col-lg-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

@endsection
