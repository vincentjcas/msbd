@extends('layouts.dashboard')

@section('title', 'File Materi Pembinaan')

@section('content')
<div class="header-section">
    <h1><i class="fas fa-folder"></i> File Materi Pembinaan</h1>
    <p>Kelola file materi untuk pembinaan guru dan siswa</p>
</div>

@if($errors->any())
<div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
    <ul style="margin: 0; padding-left: 1.5rem; color: #991b1b;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div style="background: #dcfce7; border-left: 4px solid #22c55e; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
    <p style="margin: 0; color: #166534;"><i class="fas fa-check-circle"></i> {{ session('success') }}</p>
</div>
@endif

<!-- Upload Form -->
<div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 2rem;">
    <h3 style="color: #2d3748; margin-top: 0;">
        <i class="fas fa-upload"></i> Upload File Baru
    </h3>
    
    <form action="{{ route('pembina.file-materi.upload') }}" method="POST" enctype="multipart/form-data" style="max-width: 500px;">
        @csrf
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #2d3748; font-weight: 500;">
                Pilih File
            </label>
            <div style="border: 2px dashed #0369a1; padding: 2rem; border-radius: 8px; text-align: center; cursor: pointer;" onclick="document.getElementById('fileInput').click();">
                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #0369a1; margin-bottom: 1rem;"></i>
                <p style="color: #718096; margin: 0;">Klik untuk memilih atau drag & drop file</p>
                <p style="color: #cbd5e0; font-size: 0.85rem; margin: 0.5rem 0 0 0;">Maksimal 50MB, format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP</p>
            </div>
            <input type="file" id="fileInput" name="file" required style="display: none;" onchange="updateFileName()">
            <p style="margin: 0.5rem 0 0 0; color: #718096; font-size: 0.9rem;" id="fileName"></p>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
            <i class="fas fa-upload"></i> Upload File
        </button>
    </form>
</div>

<!-- File List -->
<div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <h3 style="margin-bottom: 1.5rem; color: #2d3748;">File yang Tersedia</h3>
    
    @if($fileMateriBaru && count($fileMateriBaru) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
            @foreach($fileMateriBaru as $file)
                <div style="background: #f7fafc; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #0369a1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <i class="fas fa-file" style="color: #0369a1; font-size: 1.5rem;"></i>
                        <div style="flex: 1;">
                            <p style="margin: 0; font-weight: 500; color: #2d3748; word-break: break-word;">{{ $file['name'] }}</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem; color: #718096;">
                                {{ round($file['size'] / 1024, 2) }} KB
                            </p>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('pembina.materi.download', 0) }}" class="btn btn-sm" style="flex: 1; padding: 0.5rem; text-align: center; background: #0369a1; color: white; text-decoration: none; border-radius: 6px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <form action="{{ route('pembina.file-materi.delete', base64_encode($file['path'])) }}" method="POST" style="flex: 1;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="width: 100%; padding: 0.5rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="padding: 2rem; text-align: center; color: #718096; background: #f7fafc; border-radius: 8px;">
            <i class="fas fa-folder-open" style="font-size: 2rem; color: #cbd5e0; margin-bottom: 1rem;"></i>
            <p>Belum ada file materi yang diupload</p>
        </div>
    @endif
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

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%);
        color: white;
        font-weight: 500;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 105, 161, 0.3);
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #2d3748;
        font-weight: 500;
    }
</style>

<script>
    function updateFileName() {
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        if (fileInput.files && fileInput.files[0]) {
            fileName.textContent = 'File: ' + fileInput.files[0].name;
        }
    }
</script>
@endsection
