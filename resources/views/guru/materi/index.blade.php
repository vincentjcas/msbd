@extends('layouts.dashboard')

@section('content')
<div class="content-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 class="section-title">Materi Pembelajaran</h2>
            <p style="color: #718096; margin-top: 0.5rem;">Kelola materi pembelajaran untuk siswa</p>
        </div>
        <a href="{{ route('guru.materi.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Upload Materi Baru
        </a>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center;">
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($materi->count() > 0)
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: linear-gradient(to right, #0e7490, #14b8a6);">
                        <tr>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                                Judul Materi
                            </th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                                Kelas
                            </th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                                File
                            </th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                                Ukuran
                            </th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                                Tanggal Upload
                            </th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background: white; border-top: 1px solid #e5e7eb;">
                        @foreach($materi as $m)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 1rem 1.5rem;">
                                <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">{{ $m->judul }}</div>
                                @if($m->deskripsi)
                                    <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">{{ Str::limit($m->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.5rem; white-space: nowrap;">
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; background: #dbeafe; color: #1e40af;">
                                    {{ $m->kelas->nama_kelas }}
                                </span>
                            </td>
                            <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #6b7280;">
                                {{ $m->file_name }}
                            </td>
                            <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #6b7280;">
                                {{ $m->getFileSizeFormatted() }}
                            </td>
                            <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #6b7280;">
                                {{ \Carbon\Carbon::parse($m->uploaded_at)->format('d M Y H:i') }}
                            </td>
                            <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; font-weight: 500;">
                                <a href="{{ Storage::url($m->file_path) }}" target="_blank" style="color: #2563eb; margin-right: 0.75rem; text-decoration: none;" onmouseover="this.style.color='#1e3a8a'" onmouseout="this.style.color='#2563eb'">
                                    <svg style="width: 1.25rem; height: 1.25rem; display: inline;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete({{ $m->id_materi }})" style="color: #dc2626; background: none; border: none; cursor: pointer; padding: 0;" onmouseover="this.style.color='#991b1b'" onmouseout="this.style.color='#dc2626'">
                                    <svg style="width: 1.25rem; height: 1.25rem; display: inline;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $m->id_materi }}" action="{{ route('guru.materi.delete', $m->id_materi) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $materi->links() }}
        </div>
    @else
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 3rem; text-align: center;">
            <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Belum Ada Materi</h3>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Mulai upload materi pembelajaran untuk siswa Anda</p>
            <a href="{{ route('guru.materi.create') }}" class="btn btn-primary">
                Upload Materi Pertama
            </a>
        </div>
    @endif
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Materi?',
        text: 'File akan dihapus secara permanen dan tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
