@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Judul Halaman Utama Sesuai UI --}}
    <h3 class="mb-4">Page Berita</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">List Berita</h4>
        {{-- Tombol tanpa inline style, akan mengikuti style global .btn-primary --}}
        <a href="{{ route('berita.create') }}" class="btn btn-primary">
            <i class="bi bi-upload me-1"></i> Upload Berita
        </a>
    </div>

    {{-- Filter ini adalah visual (jika Anda ingin membuatnya berfungsi, kita perlu logika di Controller) --}}
    <div class="d-flex mb-3">
        <div class="dropdown me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuTanggal" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-calendar3 me-1"></i> Tanggal
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuTanggal">
                <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                <li><a class="dropdown-item" href="#">7 Hari Terakhir</a></li>
                <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuTag" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-tag me-1"></i> Tag Berita
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuTag">
                <li><a class="dropdown-item" href="#">Info</a></li>
                <li><a class="dropdown-item" href="#">Layanan</a></li>
                <li><a class="dropdown-item" href="#">Kegiatan</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        {{-- Menggunakan @forelse untuk menangani jika data kosong --}}
        @forelse ($beritas as $berita)
        <div class="col-md-4 mb-4">
            {{-- Class card disederhanakan, style akan diambil dari layout utama --}}
            <div class="card h-100"> 
                
                {{-- Cek jika ada gambar, jika tidak tampilkan placeholder --}}
                @if($berita->gambar)
                    <img src="{{ asset('storage/'. $berita->gambar) }}" class="card-img-top" alt="{{ $berita->judul }}" style="height: 200px; object-fit: cover;">
                @else
                    {{-- Placeholder jika tidak ada gambar --}}
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <span class="text-muted"><i class="bi bi-image-fill fs-3"></i></span>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $berita->judul }}</h5>
                    
                    {{-- Tanggal dipindahkan ke sini dan diratakan ke kanan sesuai UI --}}
                    <small class="text-muted d-block text-end">{{ $berita->created_at->format('Y/m/d') }}</small>
                    
                    {{-- mt-2 ditambahkan untuk spasi setelah tanggal --}}
                    <p class="card-text text-muted mt-2">{{ Str::limit($berita->isi, 100) }}</p>
                    
                    <hr class="mt-auto"> {{-- mt-auto mendorong tombol ke bawah --}}
                    
                    <div class="d-flex justify-content-between">
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            {{-- Class ini sudah benar sesuai UI (rounded-pill) --}}
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                        {{-- Tombol Edit --}}
                        <a href="{{ route('berita.edit', $berita->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Pesan jika tidak ada berita --}}
        <div class="col-12">
            <div class="alert alert-secondary text-center" role="alert">
                Belum ada berita yang dipublikasikan.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

