@extends('layouts.dashboard')

@section('title', 'Presensi - ' . ($mapelName ?? 'N/A'))

@section('content')
<div class="container-fluid">
    <!-- Navigation Buttons -->
    <div style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <a href="{{ route('siswa.dashboard') }}" 
           style="padding: 0.75rem 1.5rem; background-color: #3b82f6; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; font-size: 0.9375rem; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.5rem;"
           onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
            <i class="fas fa-home"></i> Beranda
        </a>
        <a href="{{ route('siswa.absen.index') }}" 
           style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; font-size: 0.9375rem; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.5rem;"
           onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
            <i class="fas fa-list"></i> Presensi Kelas
        </a>
        <button style="padding: 0.75rem 1.5rem; background-color: #10b981; color: white; border-radius: 0.5rem; border: none; font-weight: 500; font-size: 0.9375rem; cursor: default; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-book"></i> {{ $mapelName ?? 'N/A' }}
        </button>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="mb-1">
                <i class="fas fa-clipboard-list"></i> Presensi - 
                <strong>{{ $mapelName ?? 'N/A' }}</strong>, 
                <strong>Kelas {{ $jadwal?->kelas?->nama_kelas ?? 'N/A' }}</strong>
            </h3>
            <p class="text-muted mb-0">
                <i class="fas fa-user-tie"></i> Guru: <strong>{{ $guruName ?? 'N/A' }}</strong>
            </p>
        </div>
    </div>

    <!-- Main Content -->
    @if($absens->isEmpty())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Belum ada pertemuan.</strong> Guru belum membuat absen untuk mata pelajaran ini.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 6%; text-align: center;">No</th>
                            <th style="width: 16%;">Deskripsi</th>
                            <th style="width: 14%;">Tanggal Pertemuan</th>
                            <th style="width: 18%;">Jam dan Jenis Presensi</th>
                            <th style="width: 24%;">Topik Pembelajaran</th>
                            <th style="width: 12%; text-align: center;">Kehadiran</th>
                            <th style="width: 10%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absens as $index => $absen)
                            @php
                                $pertemuanNumber = $index + 1;
                                $now = now();
                                $isOpen = $now >= $absen->jam_buka && $now <= $absen->jam_tutup;
                                // Gunakan collection yang sudah di-load, jangan query baru
                                $absenSiswa = $absen->absenSiswas->firstWhere('id_siswa', \Illuminate\Support\Facades\Auth::user()->siswa->id_siswa);

                                $statusColors = [
                                    'hadir' => 'success',
                                    'tidak_hadir' => 'secondary',
                                    'izin' => 'warning',
                                    'sakit' => 'info'
                                ];
                                $statusLabels = [
                                    'hadir' => 'Hadir',
                                    'tidak_hadir' => 'Belum Diisi',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit'
                                ];
                                
                                // Button aktif HANYA jika belum pernah di-submit (waktu_absen NULL)
                                $canPresent = $isOpen && $absenSiswa && $absenSiswa->waktu_absen === null;
                                
                                // Status display: jika tidak_hadir tapi ada waktu_absen, tampilkan "Absen" (diubah guru)
                                $displayStatus = $absenSiswa->status;
                                if ($absenSiswa->status === 'tidak_hadir' && $absenSiswa->waktu_absen !== null) {
                                    $displayStatus = 'absen_edited'; // Custom key untuk label
                                }
                            @endphp
                            <tr class="align-middle" style="border-bottom: 1px solid #e5e7eb;">
                                <!-- No -->
                                <td style="text-align: center; font-weight: 600; color: #1f2937;">
                                    {{ $pertemuanNumber }}
                                </td>

                                <!-- Deskripsi -->
                                <td>
                                    <span style="font-weight: 600; color: #374151;">
                                        <i class="fas fa-calendar-alt"></i> Pertemuan {{ $pertemuanNumber }}
                                    </span>
                                </td>

                                <!-- Tanggal Pertemuan -->
                                <td>
                                    <small style="color: #6b7280;">
                                        {{ $absen->jam_buka->format('d/m/Y') }}
                                    </small>
                                </td>

                                <!-- Jam dan Jenis Presensi -->
                                <td>
                                    @if($absen->jam_buka && $absen->jam_tutup)
                                        <small style="color: #374151; font-weight: 500;">
                                            {{ $absen->jam_buka->format('H:i') }} - {{ $absen->jam_tutup->format('H:i') }}
                                            <br>
                                            <span style="color: #0ea5e9; cursor: pointer;">Mandiri</span>
                                        </small>
                                    @else
                                        <small style="color: #d1d5db;">-</small>
                                    @endif
                                </td>

                                <!-- Topik Pembelajaran -->
                                <td>
                                    <small style="color: #6b7280;">
                                        @if($absen->keterangan)
                                            {{ Str::limit($absen->keterangan, 40) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </small>
                                </td>

                                <!-- Kehadiran Status -->
                                <td style="text-align: center;">
                                    @if($absenSiswa)
                                        @php
                                            $labelKey = $displayStatus;
                                            if ($displayStatus === 'absen_edited') {
                                                $labelText = 'Absen';
                                                $colorKey = 'tidak_hadir';
                                            } else {
                                                $labelText = $statusLabels[$displayStatus] ?? ucfirst($displayStatus);
                                                $colorKey = $displayStatus;
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$colorKey] ?? 'secondary' }}">
                                            {{ $labelText }}
                                        </span>
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Belum Diisi</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td style="text-align: center;">
                                    @if($canPresent)
                                        <!-- Tombol AKTIF - Belum pernah submit -->
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="markPresent({{ $absen->id }}, this)">
                                            <i class="fas fa-check"></i> Hadir
                                        </button>
                                    @elseif(!$isOpen)
                                        <!-- Presensi sudah tutup -->
                                        <button class="btn btn-sm btn-outline-secondary" disabled 
                                                style="opacity: 0.6;">
                                            <i class="fas fa-clock"></i> Tutup
                                        </button>
                                    @else
                                        <!-- Sudah pernah submit (oleh siswa atau guru ubah) - TERKUNCI -->
                                        <button class="btn btn-sm btn-outline-secondary" disabled 
                                                style="opacity: 0.6;">
                                            <i class="fas fa-lock"></i> Terkunci
                                        </button>
                                    @endif
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
    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item.active {
        color: #6b7280;
    }

    .table {
        margin-bottom: 0;
    }

    .table-light {
        background-color: #f3f4f6;
        font-weight: 600;
        color: #374151;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
        transition: background-color 0.15s ease-in-out;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        font-weight: 600;
    }

    .btn-sm {
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .btn-sm:not(:disabled):hover {
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .align-middle {
        vertical-align: middle;
    }

    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .table {
            font-size: 0.85rem;
        }

        th, td {
            padding: 0.6rem 0.4rem !important;
        }

        .btn-sm {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.7rem;
        }
    }
</style>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const storeUrl = '{{ route("siswa.absen.store") }}';
    
    console.log('Store URL:', storeUrl);
    console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
    
    function markPresent(absenId, button) {
        // Disable button sementara dan show loading
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

        console.log('Sending request to:', storeUrl);
        console.log('Absen ID:', absenId);

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                absen_id: absenId,
                status: 'hadir'
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response type:', response.type);
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.log('Error response body:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Disable button permanen dan ubah style
                button.disabled = true;
                button.style.opacity = '0.6';
                button.innerHTML = '<i class="fas fa-lock"></i> Terkunci';
                button.className = 'btn btn-sm btn-outline-secondary';

                // Show success message
                showNotification('Absen berhasil dicatat sebagai Hadir', 'success');

                // Update status badge
                const row = button.closest('tr');
                const statusBadge = row.querySelector('.badge');
                if (statusBadge) {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.textContent = 'Hadir';
                }
            } else {
                throw new Error(data.message || 'Gagal mencatat absen');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('Error: ' + error.message, 'danger');
        });
    }

    function showNotification(message, type = 'success') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(alertDiv);

        // Auto remove after 4 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 4000);
    }
</script>

@endsection
