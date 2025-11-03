@extends('layouts.admin')

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
            <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pengumuman</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                </div>

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Pengumuman</label>
                    <textarea class="form-control" id="isi" name="isi" rows="8" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Update Pengumuman</button>
                <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection