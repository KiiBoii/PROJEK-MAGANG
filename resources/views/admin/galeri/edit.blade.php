@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Edit Foto Kegiatan: "{{ $galeri->judul_kegiatan }}"</h3>

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
            <form action="{{ route('galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul_kegiatan" class="form-label">Judul Kegiatan</label>
                    <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan', $galeri->judul_kegiatan) }}" required>
                </div>

                <div class="mb-3">
                    <label for="foto_path" class="form-label">Ganti Foto (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" class="form-control" id="foto_path" name="foto_path" accept="image/*">
                    <small class="text-muted">Max 2MB. Format: JPG, PNG, GIF</small>
                    @if ($galeri->foto_path)
                        <div class="mt-2">
                            <p>Foto saat ini:</p>
                            <img src="{{ asset('storage/' . $galeri->foto_path) }}" alt="{{ $galeri->judul_kegiatan }}" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Update Foto</button>
                <a href="{{ route('galeri.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection