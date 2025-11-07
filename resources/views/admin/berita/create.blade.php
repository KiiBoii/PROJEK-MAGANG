@extends('layouts.admin')

{{-- 1. Tambahkan CSS untuk Summernote --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
    <style>
        /* Mengatur tinggi editor summernote */
        .note-editor.note-frame {
            border-radius: 0.375rem; /* Menyesuaikan radius bootstrap */
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
            <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                </div>

                {{-- 
                    2. Ganti <textarea> biasa dengan id "summernote" 
                    PENTING: Pastikan di halaman detail berita (berita_detail.blade.php),
                    Anda menampilkan ini menggunakan {!! $berita->isi !!} (bukan {{ $berita->isi }})
                    agar format HTML-nya terbaca.
                --}}
                <div class="mb-3">
                    <label for="summernote" class="form-label">Isi Berita</label>
                    <textarea class="form-control" id="summernote" name="isi" rows="10" required>{{ old('isi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Berita</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                    <small class="text-muted">Max 2MB. Format: JPG, PNG, GIF</small>
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Simpan Berita</button>
                <a href="{{ route('berita.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- 3. Tambahkan Script untuk Summernote --}}
@push('scripts')
    {{-- Summernote memerlukan jQuery. Pastikan jQuery dimuat SEBELUM summernote. --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    {{-- Pastikan layout admin Anda sudah memuat Bootstrap 5 JS (biasanya di layouts/admin.blade.php) 
         karena Summernote BS5 membutuhkannya.
    --}}
    
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote pada ID #summernote
            $('#summernote').summernote({
                placeholder: 'Tulis isi berita lengkap di sini...',
                tabsize: 2,
                height: 250, // Atur tinggi editor
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