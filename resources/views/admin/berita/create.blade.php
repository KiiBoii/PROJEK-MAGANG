@extends('layouts.admin')

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

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Berita</label>
                    <textarea class="form-control" id="isi" name="isi" rows="8" required>{{ old('isi') }}</textarea>
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