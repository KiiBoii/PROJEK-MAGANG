@extends('layouts.admin')

{{-- 1. Tambahkan CSS untuk Summernote --}}
@push('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css"
          integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />

    <style>
        /* Style Summernote agar seragam dan modern */
        .note-editor.note-frame {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
        }
        .note-editor.note-frame:focus-within {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .note-editable {
            min-height: 250px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Edit Berita: "{{ $berita->judul }}"</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm rounded-3 border-0">
        <div class="card-body">
            <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text" class="form-control" id="judul" name="judul"
                           value="{{ old('judul', $berita->judul) }}" required>
                </div>

                {{-- Isi Berita --}}
                <div class="mb-3">
                    <label for="summernote" class="form-label">Isi Berita</label>
                    <textarea class="form-control" id="summernote" name="isi" rows="8" required>{{ old('isi', $berita->isi) }}</textarea>
                </div>

                {{-- Gambar --}}
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Berita (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                    <small class="text-muted">Max 2MB. Format: JPG, PNG, GIF</small>

                    @if ($berita->gambar)
                        <div class="mt-3">
                            <p class="fw-semibold mb-1">Gambar saat ini:</p>
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($berita->gambar) }}" alt="{{ $berita->judul }}"
                                 class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                {{-- ▼▼▼ PERBARUAN: TAMBAHKAN BLOK INI ▼▼▼ --}}
                <div class="mb-3">
                    <label for="tag" class="form-label">Topik (Opsional)</label>
                    <select class="form-select" id="tag" name="tag">
                        {{-- Logika old('tag', $berita->tag) akan mengambil tag yang tersimpan di DB --}}
                        <option value="" {{ old('tag', $berita->tag) == '' ? 'selected' : '' }}>-- Tidak ada (Berita Biasa) --</option>
                        <option value="info" {{ old('tag', $berita->tag) == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="layanan" {{ old('tag', $berita->tag) == 'layanan' ? 'selected' : '' }}>Layanan</option>
                        <option value="kegiatan" {{ old('tag', $berita->tag) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    </select>
                    <small class="text-muted">Jika diisi, berita ini akan diprioritaskan di 'Topik Lainnya' pada halaman user.</small>
                </div>
                {{-- ▲▲▲ AKHIR PERBARUAN ▲▲▲ --}}

                {{-- PERBARUAN: Tombol disamakan dengan create.blade.php --}}
                <button type="submit" class="btn btn-primary">Update Berita</button>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- 3. Tambahkan Script untuk Summernote --}}
@push('scripts')
    {{-- jQuery sudah ada di layout admin, jadi tidak perlu ditambahkan ulang --}}

    {{-- Summernote JS (versi Bootstrap 5) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"
            integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Tulis isi berita lengkap di sini...',
                tabsize: 2,
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush