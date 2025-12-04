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
    <!-- Bulk Actions Toolbar -->
    <div id="bulkActionsToolbar" style="display: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; align-items: center; gap: 1rem;">
        <span id="selectedCount" style="font-weight: 600;">0 materi dipilih</span>
        <div style="display: flex; gap: 0.5rem; margin-left: auto;">
            <button onclick="bulkDownload()" class="btn btn-sm" style="background: #10b981; color: white; padding: 0.5rem 1rem;">
                <i class="fas fa-download"></i> Download Terpilih
            </button>
            <button onclick="bulkDelete()" class="btn btn-sm" style="background: #ef4444; color: white; padding: 0.5rem 1rem;">
                <i class="fas fa-trash"></i> Hapus Terpilih
            </button>
            <button onclick="clearSelection()" class="btn btn-sm" style="background: #64748b; color: white; padding: 0.5rem 1rem;">
                <i class="fas fa-times"></i> Batal
            </button>
        </div>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 3%;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="cursor: pointer; width: 18px; height: 18px;">
                    </th>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Guru</th>
                    <th style="width: 20%;">Judul Materi</th>
                    <th style="width: 10%;">Mata Pelajaran</th>
                    <th style="width: 10%;">Kelas</th>
                    <th style="width: 12%;">File/Link</th>
                    <th style="width: 10%;">Tanggal Upload</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materi as $index => $item)
                <tr>
                    <td>
                        <input type="checkbox" class="materi-checkbox" value="{{ $item->id_materi }}" 
                               data-file="{{ $item->file_materi }}" 
                               data-title="{{ $item->judul_materi }}"
                               onchange="updateSelection()" 
                               style="cursor: pointer; width: 18px; height: 18px;">
                    </td>
                    <td>{{ $materi->firstItem() + $index }}</td>
                    <td>
                        @if($item->guru && $item->guru->user)
                        <div style="font-weight: 600;">{{ $item->guru->user->nama_lengkap }}</div>
                        <small style="color: #718096;">NIP: {{ $item->guru->nip }}</small>
                        @else
                        <span style="color: #94a3b8; font-style: italic;">Guru tidak ditemukan</span>
                        @endif
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

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.materi-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateSelection();
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.materi-checkbox:checked');
    const toolbar = document.getElementById('bulkActionsToolbar');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');
    
    if (checkboxes.length > 0) {
        toolbar.style.display = 'flex';
        selectedCount.textContent = checkboxes.length + ' materi dipilih';
    } else {
        toolbar.style.display = 'none';
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.materi-checkbox');
    selectAll.checked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
}

function clearSelection() {
    document.querySelectorAll('.materi-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateSelection();
}

function bulkDownload() {
    const checkboxes = document.querySelectorAll('.materi-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih minimal 1 materi untuk didownload');
        return;
    }
    
    let filesDownloaded = 0;
    let noFileCount = 0;
    
    checkboxes.forEach((checkbox, index) => {
        const fileName = checkbox.dataset.file;
        if (fileName) {
            // Delay each download to prevent browser blocking
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = '/storage/materi/' + fileName;
                link.download = fileName;
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, index * 300); // 300ms delay between downloads
            filesDownloaded++;
        } else {
            noFileCount++;
        }
    });
    
    if (filesDownloaded > 0) {
        alert(`Download dimulai untuk ${filesDownloaded} file.${noFileCount > 0 ? ' (' + noFileCount + ' materi tidak memiliki file)' : ''}`);
    } else {
        alert('Tidak ada file yang bisa didownload dari materi yang dipilih');
    }
}

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.materi-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih minimal 1 materi untuk dihapus');
        return;
    }
    
    const materiIds = Array.from(checkboxes).map(cb => cb.value);
    const titles = Array.from(checkboxes).map(cb => cb.dataset.title);
    
    const confirmMsg = `Yakin ingin menghapus ${materiIds.length} materi berikut?\n\n${titles.slice(0, 5).join('\n')}${titles.length > 5 ? '\n... dan ' + (titles.length - 5) + ' lainnya' : ''}`;
    
    if (!confirm(confirmMsg)) {
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.file-materi.bulk-delete") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);
    
    materiIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'materi_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
