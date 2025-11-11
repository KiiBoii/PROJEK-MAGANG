@extends('layouts.admin')

{{-- 1. Tambahkan CSS (Menggunakan link baru Anda) --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Mengatur tinggi editor summernote */
        .note-editor.note-frame {
            border-radius: 0.375rem; /* Menyesuaikan radius bootstrap */
            border: 1px solid #ced4da; /* Menambahkan border default */
        }
        .note-editor.note-frame:focus-within {
             border-color: #007bff; /* Warna border saat fokus */
             box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .note-editable {
            min-height: 250px; /* Atur tinggi minimal editor */
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Tambah Berita Baru</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm rounded-3 border-0">
        <div class="card-body">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                </div>

                <div class="mb-3">
                    <label for="summernote" class="form-label">Isi Berita</label>
                    <textarea class="form-control" id="summernote" name="isi" rows="10" required>{{ old('isi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Berita</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                    <small class="text-muted">Max 2MB. Format: JPG, PNG, GIF</small>
                </div>

                {{-- ▼▼▼ PERBARUAN: TAMBAHKAN BLOK INI ▼▼▼ --}}
                <div class="mb-3">
                    <label for="tag" class="form-label">Topik (Opsional)</label>
                    <select class="form-select" id="tag" name="tag">
                        <option value="" {{ old('tag') == '' ? 'selected' : '' }}>-- Tidak ada (Berita Biasa) --</option>
                        <option value="info" {{ old('tag') == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="layanan" {{ old('tag') == 'layanan' ? 'selected' : '' }}>Layanan</option>
                        <option value="kegiatan" {{ old('tag') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    </select>
                    <small class="text-muted">Jika diisi, berita ini akan diprioritaskan di 'Topik Lainnya' pada halaman user.</small>
                </div>
                {{-- ▲▲▲ AKHIR PERBARUAN ▲▲▲ --}}

                <button type="submit" class="btn btn-primary">Simpan Berita</button>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- 3. Tambahkan Script (Menggunakan link baru Anda) --}}
@push('scripts')
    {{-- (jQuery sudah ada di layout admin.blade.php - Ini sudah benar) --}}
    
    {{-- Muat Summernote JS (Versi Bootstrap 5) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote
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