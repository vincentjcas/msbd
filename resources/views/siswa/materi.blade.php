@extends('layouts.dashboard')

@section('title', 'Materi Pembelajaran')

@section('content')
<div class="content-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 class="section-title"><i class="fas fa-book"></i> Materi Pembelajaran</h3>
            <p style="color: #6b7280; margin-top: 0.5rem;">Daftar materi untuk kelas Anda. Klik <strong>Download</strong> untuk mengunduh materi.</p>
        </div>
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    @php
        use Illuminate\Support\Str;
    @endphp

    @if($materi->count() == 0)
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <p>Tidak ada materi pembelajaran untuk kelas Anda.</p>
        </div>
    @else
        {{-- Group by guru name as proxy for mata pelajaran (if mata pelajaran not available) --}}
        @php
            $grouped = $materi->groupBy(function($item) {
                return optional($item->guru)->user->nama_lengkap ?? 'Pengajar Lainnya';
            });
        @endphp

        @foreach($grouped as $groupName => $items)
            <div style="margin-bottom: 2rem;">
                <h4 style="margin-bottom: 1rem; color: #2d3748; font-size: 1.125rem; font-weight: 600;">{{ $groupName }}</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    @foreach($items as $m)
                        <div style="background: #ffffff; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 8px; transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                            <h5 style="margin: 0 0 0.75rem 0; font-size: 1.05rem; color: #111827;">{{ $m->judul ?? 'Untitled' }}</h5>
                            @if($m->deskripsi)
                                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">{{ Str::limit($m->deskripsi, 140) }}</p>
                            @endif
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <a class="btn btn-primary btn-sm" href="{{ route('siswa.materi.download', $m->id_materi) }}" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="fas fa-download"></i> Download
                                </a>

                                @if(!empty($m->link_eksternal))
                                    <a class="btn btn-secondary btn-sm" href="{{ $m->link_eksternal }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Buka Link
                                    </a>
                                @endif

                                <span style="margin-left:auto; color:#9ca3af; font-size:0.85rem;">
                                    {{ optional($m->uploaded_at)->format('d M Y') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div style="margin-top: 1.5rem;">
            {{ $materi->links() }}
        </div>
    @endif
</div>

@endsection
