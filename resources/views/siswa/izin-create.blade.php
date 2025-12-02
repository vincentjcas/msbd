@extends('layouts.dashboard')

@section('title', 'Ajukan Izin')

@section('content')
<div class="content-section">
    <h3 class="section-title"><i class="fas fa-file-medical"></i> Ajukan Izin</h3>

    <p style="color: #6b7280; margin-bottom: 1.5rem;">Ajukan izin atau surat sakit untuk ketidakhadiran Anda. Bukti (surat sakit atau surat izin) wajib diunggah dalam format PDF atau gambar (JPG/PNG) dengan ukuran maksimal 5 MB.</p>

    @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            <strong>Terjadi Kesalahan:</strong>
            <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siswa.izin.submit') }}" method="POST" enctype="multipart/form-data" id="form-ajukan-izin">
        @csrf

        <!-- Pilihan Tipe (Sakit / Izin) -->
        <div style="background: #f7fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #2d3748;">Tipe Ketidakhadiran</label>
            <div style="display: flex; gap: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="radio" name="tipe" value="sakit" required id="tipe-sakit" style="cursor: pointer;">
                    <span style="font-weight: 500;">Sakit</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="radio" name="tipe" value="izin" required id="tipe-izin" style="cursor: pointer;">
                    <span style="font-weight: 500;">Izin</span>
                </label>
            </div>
            @error('tipe')<p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>@enderror
        </div>

        <!-- Tanggal Izin -->
        <div style="margin-bottom: 1.5rem;">
            <label for="tanggal" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2d3748;">Tanggal Izin</label>
            <input type="date" id="tanggal" name="tanggal" required
                   value="{{ old('tanggal') }}"
                   min="{{ date('Y-m-d') }}"
                   style="width: 100%; padding: 0.85rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; background: white; cursor: pointer;">
            <p style="color: #6b7280; font-size: 0.85rem; margin-top: 0.25rem;">Pilih tanggal ketidakhadiran (hari ini atau yang akan datang)</p>
            @error('tanggal')<p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
        </div>

        <!-- Alasan (muncul hanya jika tipe "Izin") -->
        <div id="alasan-container" style="display: none; margin-bottom: 1.5rem;">
            <label for="alasan" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2d3748;">
                Alasan Izin <span style="color: #dc2626;">*</span>
            </label>
            <textarea id="alasan" name="alasan" 
                      placeholder="Jelaskan alasan izin Anda (contoh: Acara keluarga, Keperluan mendesak, dll.)"
                      maxlength="500"
                      style="width: 100%; padding: 0.85rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; resize: vertical; min-height: 100px;">{{ old('alasan') }}</textarea>
            <p style="color: #6b7280; font-size: 0.85rem; margin-top: 0.25rem;">Wajib diisi jika tipe izin. Maksimal 500 karakter</p>
            @error('alasan')<p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
        </div>

        <!-- Upload Bukti -->
        <div style="margin-bottom: 1.5rem;">
            <label for="bukti_file" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2d3748;">
                <span id="bukti-label">Upload Bukti</span>
            </label>
            <div style="border: 2px dashed #cbd5e0; border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s;" 
                 id="upload-area"
                 onclick="document.getElementById('bukti_file').click();">
                <input type="file" id="bukti_file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png" 
                       style="display: none;" required>
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📄</div>
                <p style="color: #4a5568; font-weight: 500; margin: 0;">Klik atau drag file ke sini</p>
                <p style="color: #718096; font-size: 0.9rem; margin: 0.5rem 0 0 0;">Format: PDF, JPG, PNG | Maks: 5 MB</p>
            </div>
            <p id="file-name" style="color: #059669; font-size: 0.9rem; margin-top: 0.5rem; display: none;"></p>
            @error('bukti_file')<p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>@enderror
        </div>

        <!-- Tombol Submit -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fas fa-paper-plane"></i> Ajukan Izin
            </button>
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>

    <!-- Tombol Aksi -->
    <div style="padding: 1.5rem; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; justify-content: flex-start; margin-top: 2rem; border-radius: 0 0 10px 10px;">
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<style>
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }
    .btn-primary {
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%);
        color: white;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(3, 105, 161, 0.4);
    }
    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #cbd5e0;
    }
    #upload-area:hover {
        background-color: #f0f9ff;
        border-color: #0369a1;
    }
    #upload-area.dragover {
        background-color: #e0f2fe;
        border-color: #0284c7;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSakit = document.getElementById('tipe-sakit');
        const tipeIzin = document.getElementById('tipe-izin');
        const alasanContainer = document.getElementById('alasan-container');
        const alasanInput = document.getElementById('alasan');
        const buktiLabel = document.getElementById('bukti-label');
        const buktiInput = document.getElementById('bukti_file');
        const uploadArea = document.getElementById('upload-area');
        const fileName = document.getElementById('file-name');

        // Toggle alasan field based on tipe
        tipeSakit.addEventListener('change', function() {
            if (this.checked) {
                alasanContainer.style.display = 'none';
                alasanInput.removeAttribute('required');
                alasanInput.value = '';
                buktiLabel.innerHTML = 'Upload Surat Sakit';
            }
        });

        tipeIzin.addEventListener('change', function() {
            if (this.checked) {
                alasanContainer.style.display = 'block';
                alasanInput.setAttribute('required', 'required');
                buktiLabel.innerHTML = 'Upload Bukti Izin <span style="color: #dc2626;">*</span>';
            }
        });

        // File upload handler
        buktiInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                
                if (file.size > 5 * 1024 * 1024) {
                    fileName.textContent = '❌ File terlalu besar (maks 5 MB)';
                    fileName.style.color = '#dc2626';
                    buktiInput.value = '';
                } else {
                    fileName.textContent = `✓ ${file.name} (${sizeMB} MB)`;
                    fileName.style.color = '#059669';
                    fileName.style.display = 'block';
                }
            }
        });

        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function() {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                buktiInput.files = files;
                const event = new Event('change', { bubbles: true });
                buktiInput.dispatchEvent(event);
            }
        });

        // Form validation
        document.getElementById('form-ajukan-izin').addEventListener('submit', function(e) {
            const tipe = document.querySelector('input[name="tipe"]:checked');
            if (!tipe) {
                e.preventDefault();
                Swal.fire({
                    title: 'Validasi Gagal',
                    text: 'Pilih tipe ketidakhadiran (Sakit atau Izin)',
                    icon: 'warning',
                    confirmButtonColor: '#0369a1'
                });
                return false;
            }

            if (tipe.value === 'izin') {
                const alasan = document.getElementById('alasan').value.trim();
                if (!alasan || alasan.length < 10) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Validasi Gagal',
                        text: 'Alasan izin wajib diisi minimal 10 karakter',
                        icon: 'warning',
                        confirmButtonColor: '#0369a1'
                    });
                    return false;
                }
            }

            if (!buktiInput.files.length) {
                e.preventDefault();
                Swal.fire({
                    title: 'Validasi Gagal',
                    text: 'Bukti (surat/foto) wajib diunggah',
                    icon: 'warning',
                    confirmButtonColor: '#0369a1'
                });
                return false;
            }

            // Show loading
            Swal.fire({
                title: 'Mengirim Izin...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
</script>

@endsection