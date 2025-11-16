@extends('layouts.dashboard')

@section('title', 'Materi Pembelajaran')

@section('content')
<div class="content-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 class="section-title" style="margin: 0;">
            <i class="fas fa-book"></i> Materi Pembelajaran
        </h3>
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <p style="color: #6b7280; margin-bottom: 1rem;">Daftar materi untuk kelas Anda. Klik <strong>Download</strong> untuk mengunduh atau <strong>Ajukan Pengumpulan Tugas</strong> untuk mengunggah jawaban tugas terkait.</p>

    @php
        use App\Models\Tugas;
        use Illuminate\Support\Str;
        $siswa = auth()->user()->siswa;
        // tugas yang tersedia untuk kelas (dipakai di modal submit)
        $tugasList = [];
        if ($siswa) {
            $tugasList = Tugas::where('id_kelas', $siswa->id_kelas)->orderBy('deadline', 'desc')->get();
        }
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
            <div style="margin-bottom: 1.25rem;">
                <h4 style="margin-bottom: 0.5rem;">{{ $groupName }}</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    @foreach($items as $m)
                        <div style="background: #ffffff; border: 1px solid #e5e7eb; padding: 1rem; border-radius: 8px;">
                            <h5 style="margin: 0 0 0.5rem 0; font-size: 1.05rem;">{{ $m->judul ?? $m->judul_materi ?? 'Untitled' }}</h5>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">{{ 
                                
                                Str::limit($m->deskripsi ?? '', 140)
                             }}</p>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <a class="btn btn-outline btn-sm" href="{{ route('siswa.materi.download', $m->id_materi) }}">
                                    <i class="fas fa-download"></i> Download
                                </a>

                                <button class="btn btn-primary btn-sm btn-open-submit" data-materi-id="{{ $m->id_materi }}">
                                    <i class="fas fa-upload"></i> Ajukan Pengumpulan Tugas
                                </button>

                                @if(!empty($m->link_eksternal))
                                    <a class="btn btn-secondary btn-sm" href="{{ $m->link_eksternal }}" target="_blank">Buka Link</a>
                                @endif

                                <span style="margin-left:auto; color:#9ca3af; font-size:0.85rem;">{{ optional($m->uploaded_at)->format('d-m-Y') ?? '-' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div style="margin-top: 1rem;">
            {{ $materi->links() }}
        </div>
    @endif
</div>

<!-- Modal for submit tugas -->
<div id="modal-submit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
    <div style="background:white; width:90%; max-width:520px; padding:1.25rem; border-radius:8px;">
        <h4 id="modal-title">Ajukan Pengumpulan Tugas</h4>
        <p style="color:#6b7280; font-size:0.95rem;">Pilih tugas yang ingin Anda kumpulkan kemudian unggah file jawaban (pdf/doc/docx/zip).</p>

        <form id="form-submit-tugas">
            <input type="hidden" name="materi_id" id="materi_id" value="">

            <div style="margin-bottom:0.75rem;">
                <label for="tugas_id">Pilih Tugas</label>
                <select id="tugas_id" name="tugas_id" class="form-control" style="width:100%; padding:0.5rem; border:1px solid #d1d5db; border-radius:4px;">
                    <option value="">-- Pilih Tugas --</option>
                    @foreach($tugasList as $t)
                        <option value="{{ $t->id_tugas }}">{{ $t->judul_tugas }} (Deadline: {{ optional($t->deadline)->format('d-m-Y H:i') }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label for="file">File Jawaban</label>
                <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.zip" class="form-control" style="width:100%;">
            </div>

            <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:0.5rem;">
                <button type="button" class="btn btn-outline" id="btn-cancel">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-submit');
    const materiInput = document.getElementById('materi_id');
    const btnCancel = document.getElementById('btn-cancel');
    const form = document.getElementById('form-submit-tugas');

    document.querySelectorAll('.btn-open-submit').forEach(btn => {
        btn.addEventListener('click', function() {
            const materiId = this.dataset.materiId;
            materiInput.value = materiId;
            modal.style.display = 'flex';
        });
    });

    btnCancel.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const tugasId = document.getElementById('tugas_id').value;
        const fileInput = document.getElementById('file');

        if (!tugasId) {
            Swal.fire({ icon: 'warning', title: 'Pilih tugas terlebih dahulu' });
            return;
        }

        if (!fileInput.files.length) {
            Swal.fire({ icon: 'warning', title: 'Pilih file jawaban terlebih dahulu' });
            return;
        }

        const fd = new FormData();
        fd.append('file', fileInput.files[0]);

        // Show loading
        Swal.fire({ title: 'Mengunggah...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetch(`/siswa/tugas/${tugasId}/submit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: fd
        })
        .then(response => {
            // If server redirects (302) fetch will follow; assume success if ok
            if (!response.ok) throw new Error('Gagal mengunggah (status ' + response.status + ')');
            return response.text();
        })
        .then(() => {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Tugas berhasil dikumpulkan' }).then(() => {
                window.location.href = '{{ route("siswa.tugas") }}';
            });
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
        })
        .finally(() => {
            modal.style.display = 'none';
        });
    });
});
</script>

@endsection
