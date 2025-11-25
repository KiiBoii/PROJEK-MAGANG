@extends('layouts.admin')

{{-- 1. PERBAIKAN: Ganti link CDN & perbarui style --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Menyesuaikan style editor agar rapi */
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
    <h3 class="mb-4">Edit Pengumuman: "{{ $pengumuman->judul }}"</h3>

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
            <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pengumuman</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                </div>

                {{-- ID 'summernote' sudah benar --}}
                <div class="mb-3">
                    <label for="summernote" class="form-label">Isi Pengumuman</label>
                    <textarea class="form-control" id="summernote" name="isi" rows="8" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar (Opsional)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                    <small class="text-muted">Max 2MB. Biarkan kosong jika tidak ingin mengubah.</small>
                    
                    @if($pengumuman->gambar)
                        <div class="mt-2">
                            <p>Gambar saat ini:</p>
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($pengumuman->gambar) }}" alt="{{ $pengumuman->judul }}" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif
                </div>

                {{-- 2. PERBAIKAN: Hapus inline style agar konsisten dengan layout --}}
                <button type="submit" class="btn btn-primary">Update Pengumuman</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- 3. PERBAIKAN: Hapus jQuery & ganti link JS Summernote --}}
@push('scripts')
    {{-- ▼▼▼ BARIS INI WAJIB DIHAPUS ▼▼▼ --}}
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}} 
    
    {{-- Menggunakan link JS dari CDN Cloudflare yang Anda temukan --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote
            $('#summernote').summernote({
                placeholder: 'Tulis isi pengumuman di sini...',
                tabsize: 2,
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });
    </script>
@endpush