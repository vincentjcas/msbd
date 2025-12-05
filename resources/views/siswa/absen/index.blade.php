@extends('layouts.dashboard')

@section('title', 'Isi Absen')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h2 class="mb-1"><i class="fas fa-check-circle"></i> Isi Absen Siswa</h2>
                    <p class="text-muted">Pilih mata pelajaran untuk melihat dan mengisi absen</p>
                </div>
                <x-dashboard-button />
            </div>
        </div>
    </div>

    @if($mapels->isEmpty())
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> 
            <strong>Belum ada jadwal.</strong> Mata pelajaran Anda akan muncul di sini.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%; text-align: center;">Presensi</th>
                            <th style="width: 8%; text-align: center;">No</th>
                            <th style="width: 40%;">Mata Pelajaran</th>
                            <th style="width: 42%;">Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mapels as $index => $mapel)
                            <tr class="align-middle" style="border-bottom: 1px solid #e5e7eb;">
                                <!-- Presensi Icon -->
                                <td style="text-align: center;">
                                    <a href="{{ route('siswa.absen.show', $mapel->mata_pelajaran) }}" 
                                       class="btn btn-sm btn-primary"
                                       style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; color: white; text-decoration: none;">
                                        <i class="fas fa-list-check"></i>
                                    </a>
                                </td>

                                <!-- No -->
                                <td style="text-align: center; font-weight: 600; color: #1f2937;">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Mata Pelajaran -->
                                <td>
                                    <span style="font-weight: 600; color: #1e3a8a;">
                                        {{ $mapel->mata_pelajaran ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Guru -->
                                <td>
                                    <small style="color: #6b7280;">
                                        {{ $mapel->guru->nama_lengkap ?? 'N/A' }}
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        border-radius: 4px;
    }

    .card {
        border-radius: 8px;
    }

    .btn-primary {
        color: white;
        text-decoration: none;
    }

    .btn-primary:hover {
        color: white;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    .d-grid {
        display: grid;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .d-block {
        display: block;
    }
</style>
@endsection
