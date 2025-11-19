@extends('layouts.dashboard')

@section('title', 'File Materi')

@section('content')
<div class="welcome-card">
    <h2><i class="fas fa-folder-open"></i> File Materi Pembelajaran</h2>
    <p>Memantau dan mengelola file materi pembelajaran yang diunggah oleh guru</p>
</div>

<div class="content-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 class="section-title" style="margin: 0;"><i class="fas fa-list"></i> Daftar Materi</h3>
        <div style="color: #718096;">
            Total: <strong>{{ $materi->total() }}</strong> materi
        </div>
    </div>

    @if($materi->count() > 0)
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Guru</th>
                    <th style="width: 20%;">Judul Materi</th>
                    <th style="width: 10%;">Mata Pelajaran</th>
                    <th style="width: 10%;">Kelas</th>
                    <th style="width: 15%;">File/Link</th>
                    <th style="width: 10%;">Tanggal Upload</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materi as $index => $item)
                <tr>
                    <td>{{ $materi->firstItem() + $index }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $item->guru->user->nama_lengkap }}</div>
                        <small style="color: #718096;">NIP: {{ $item->guru->nip }}</small>
                    </td>
                    <td>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ $item->judul_materi }}</div>
                        @if($item->deskripsi)
                        <small style="color: #718096;">{{ Str::limit($item->deskripsi, 50) }}</small>
                        @endif
                    </td>
                    <td>{{ $item->mata_pelajaran }}</td>
                    <td>
                        <span class="badge badge-info">{{ $item->kelas->nama_kelas }}</span>
                    </td>
                    <td>
                        @if($item->file_materi)
                        <a href="{{ asset('storage/materi/' . $item->file_materi) }}" target="_blank" class="btn btn-sm" style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem;">
                            <i class="fas fa-file-download"></i> File
                        </a>
                        @endif
                        @if($item->link_eksternal)
                        <a href="{{ $item->link_eksternal }}" target="_blank" class="btn btn-sm" style="background: #10b981; color: white; padding: 0.25rem 0.75rem; margin-top: 0.25rem;">
                            <i class="fas fa-external-link-alt"></i> Link
                        </a>
                        @endif
                    </td>
                    <td>
                        <small style="color: #4a5568;">{{ \Carbon\Carbon::parse($item->uploaded_at)->format('d/m/Y') }}</small><br>
                        <small style="color: #718096;">{{ \Carbon\Carbon::parse($item->uploaded_at)->format('H:i') }}</small>
                    </td>
                    <td>
                        <form action="{{ route('admin.file-materi.delete', $item->id_materi) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background: #ef4444; color: white; padding: 0.25rem 0.75rem;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $materi->links() }}
    </div>
    @else
    <div style="padding: 3rem; text-align: center; background: #f7fafc; border-radius: 8px; color: #718096;">
        <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
        <p style="margin: 0; font-size: 1.1rem;">Belum ada materi yang diunggah</p>
    </div>
    @endif
</div>

<style>
.table-container {
    overflow-x: auto;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: background 0.2s;
}

.data-table tbody tr:hover {
    background: #f7fafc;
}

.data-table td {
    padding: 1rem;
    font-size: 0.875rem;
    color: #2d3748;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-info {
    background: #e0f2fe;
    color: #0369a1;
}
</style>
@endsection
