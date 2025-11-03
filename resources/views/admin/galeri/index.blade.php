@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Galeri Kegiatan</h3>
    <a href="{{ route('galeri.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Upload Photo
    </a>
</div>

<div class="row">
    {{-- Filtering, Anda bisa tambahkan di sini jika perlu --}}
    {{-- <div class="col-12 mb-3 d-flex">
        <div class="dropdown me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownTanggal" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 15px;">
                Tanggal
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownTanggal">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownTagBerita" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 15px;">
                Tag Berita
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownTagBerita">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
            </ul>
        </div>
    </div> --}}

    @foreach ($galeris as $foto)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm rounded-3 border-0">
            @if ($foto->foto_path)
                <img src="{{ asset('storage/' . $foto->foto_path) }}" class="card-img-top" alt="{{ $foto->judul_kegiatan }}" style="height: 250px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                    <span class="text-muted">Tidak Ada Foto</span>
                </div>
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $foto->judul_kegiatan }}</h5>
                <small class="text-muted">{{ $foto->created_at->format('d/m/Y') }}</small>
                <hr>
                <div class="d-flex justify-content-between">
                    <form action="{{ route('galeri.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                    </form>
                    <a href="{{ route('galeri.edit', $foto->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($galeris->isEmpty())
        <div class="col-12">
            <p class="text-center text-muted">Belum ada foto kegiatan di galeri.</p>
        </div>
    @endif
</div>
@endsection