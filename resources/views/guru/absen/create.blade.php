@extends('layouts.dashboard')

@section('title', 'Buat Absen - ' . ($mapelName ?? 'N/A'))

@section('content')
<style>
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

    .form-control:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 991px) {
        .col-lg-8, .col-lg-4 {
            margin-bottom: 1.5rem;
        }
    }
</style>

<div class="container-fluid" style="padding: 1.5rem;">
    <!-- Breadcrumb -->
    <div class="mb-2" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('guru.absen.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <x-dashboard-button />
    </div>

    <!-- Header -->
    <div class="mb-3">
        <h4 style="font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">
            <i class="fas fa-plus-circle" style="color: #3b82f6;"></i> Buat Absen
        </h4>
        <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">
            <strong>{{ $mapelName ?? 'N/A' }}</strong> • Kelas <strong>{{ $jadwal->kelas->nama_kelas ?? 'N/A' }}</strong>
        </p>
    </div>

    <div class="row g-3">
        <!-- Form Section -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
                <!-- Section Header -->
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem; color: white;">
                    <h6 class="mb-0" style="font-weight: 600; font-size: 0.95rem;">
                        <i class="fas fa-clock"></i> Pengaturan Waktu & Topik Absen
                    </h6>
                </div>

                <div class="card-body p-3">
                    <form action="{{ route('guru.absen.store') }}" method="POST">
                        @csrf
                        
                        <input type="hidden" name="mata_pelajaran" value="{{ $mapelName }}">
                        <input type="hidden" name="id_kelas" value="{{ $idKelas }}">
                        
                        <!-- Jam Buka -->
                        <div class="mb-3">
                            <h6 style="color: #1f2937; font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                <span class="btn-icon-circle" style="background: #dbeafe; color: #0ea5e9;">
                                    <i class="fas fa-play"></i>
                                </span>
                                Jam Buka Absen
                            </h6>
                            <input type="datetime-local" 
                                   class="form-control @error('jam_buka') is-invalid @enderror" 
                                   id="jam_buka" 
                                   name="jam_buka"
                                   value="{{ old('jam_buka') }}"
                                   style="border-radius: 6px; border: 1px solid #e5e7eb; padding: 0.5rem 0.75rem; font-size: 0.9rem; transition: all 0.3s;"
                                   required>
                            @error('jam_buka')
                                <small class="text-danger mt-1" style="display: block;">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">Kapan absen mulai dibuka</small>
                        </div>

                        <!-- Jam Tutup -->
                        <div class="mb-3">
                            <h6 style="color: #1f2937; font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                <span class="btn-icon-circle" style="background: #fee2e2; color: #ef4444;">
                                    <i class="fas fa-stop"></i>
                                </span>
                                Jam Tutup Absen
                            </h6>
                            <input type="datetime-local" 
                                   class="form-control @error('jam_tutup') is-invalid @enderror" 
                                   id="jam_tutup" 
                                   name="jam_tutup"
                                   value="{{ old('jam_tutup') }}"
                                   style="border-radius: 6px; border: 1px solid #e5e7eb; padding: 0.5rem 0.75rem; font-size: 0.9rem; transition: all 0.3s;"
                                   required>
                            @error('jam_tutup')
                                <small class="text-danger mt-1" style="display: block;">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">Kapan absen ditutup (harus lebih besar dari jam buka)</small>
                        </div>

                        <!-- Topik Pembelajaran -->
                        <div class="mb-3">
                            <h6 style="color: #1f2937; font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                <span class="btn-icon-circle" style="background: #fef3c7; color: #f59e0b;">
                                    <i class="fas fa-book"></i>
                                </span>
                                Topik Pembelajaran
                            </h6>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" 
                                      name="keterangan"
                                      rows="2"
                                      placeholder="Contoh: Pengenalan Database, SQL Dasar..."
                                      style="border-radius: 6px; border: 1px solid #e5e7eb; padding: 0.5rem 0.75rem; font-size: 0.9rem; resize: vertical; transition: all 0.3s;">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <small class="text-danger mt-1" style="display: block;">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">Materi/topik pembelajaran (opsional)</small>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                            <button type="submit" class="btn flex-grow-1"
                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; font-weight: 600; padding: 0.5rem; border-radius: 6px; font-size: 0.9rem; transition: all 0.3s; cursor: pointer;">
                                <i class="fas fa-check"></i> Buat Absen
                            </button>
                            <a href="{{ route('guru.absen.index') }}" class="btn btn-outline-secondary" 
                               style="padding: 0.5rem 1.5rem; font-weight: 600; border-radius: 6px; font-size: 0.9rem;">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Daftar Siswa -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding: 1rem; color: white;">
                    <h6 class="mb-0" style="font-weight: 600; font-size: 0.95rem;">
                        <i class="fas fa-users"></i> Daftar Siswa
                    </h6>
                </div>

                <!-- Content -->
                <div style="flex: 1; overflow-y: auto;">
                    @if($siswaKelas->isEmpty())
                        <div style="padding: 1.5rem; text-align: center;">
                            <i class="fas fa-inbox" style="font-size: 2rem; color: #d1d5db; margin-bottom: 0.75rem; display: block;"></i>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Tidak ada siswa</p>
                        </div>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach($siswaKelas as $index => $siswa)
                                <li style="border-bottom: 1px solid #f3f4f6; padding: 0.75rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                                    <div style="background: #e0e7ff; color: #4f46e5; width: 28px; height: 28px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <p class="mb-0" style="font-weight: 600; color: #1f2937; font-size: 0.85rem;">
                                            {{ $siswa->nama_lengkap ?? 'N/A' }}
                                        </p>
                                        <small style="color: #6b7280; font-size: 0.75rem;">{{ $siswa->nis ?? 'N/A' }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Footer -->
                @if(!$siswaKelas->isEmpty())
                    <div style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                        <small style="color: #6b7280; font-weight: 600; font-size: 0.8rem;">
                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                            Total: <strong>{{ $siswaKelas->count() }}</strong>
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>@endsection
