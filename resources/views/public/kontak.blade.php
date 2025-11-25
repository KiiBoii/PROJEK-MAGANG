@extends('layouts.public')

{{-- 1. CSS KUSTOM (Tidak ada perubahan) --}}
@push('styles')
<style>
    .news-slider .carousel-item {
        height: 450px; /* Atur tinggi slider */
        background-color: #555;
    }

    .news-slider .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Pastikan gambar mengisi area */
    }

    /* Overlay gradient gelap agar teks terbaca */
    .news-slider .carousel-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.6) 20%, rgba(0,0,0,0) 80%);
    }

    .news-slider .carousel-caption {
        bottom: 0;
        z-index: 10;
        text-align: left;
        padding: 2rem 1.5rem;
        width: 80%; 
        left: 5%; 
    }

    .news-slider .carousel-caption h5,
    /* Menargetkan h1 di dalam caption juga */
    .news-slider .carousel-caption h1 {
        font-size: 2.5rem; /* Dibuat sedikit lebih besar untuk Judul Halaman */
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .news-slider .carousel-caption p {
        font-size: 1.1rem; /* Subtitel dibuat lebih besar */
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
</style>
@endpush


@section('content')

<div class="container my-5">
    
    <div id="kontakHeader" class="carousel slide news-slider" data-bs-ride="{{ (isset($sliders) && $sliders->count() > 1) ? 'carousel' : 'false' }}"
         data-bs-pause="{{ (isset($sliders) && $sliders->count() > 1) ? 'false' : 'true' }}" 
         data-bs-interval="3000"
         style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            @if(isset($sliders) && $sliders->count() > 1)
                @foreach($sliders as $slider)
                    <button type="button" data-bs-target="#kontakHeader" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            @endif
        </div>
        
        <div class="carousel-inner">
            @forelse(isset($sliders) ? $sliders : [] as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                    <img src="{{ asset($slider->gambar) }}" class="d-block w-100" alt="{{ $slider->judul }}">
                    
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="text-white">{{ $slider->judul }}</h1>
                        <p class="text-white-50">{{ $slider->keterangan }}</p>
                    </div>
                </div>
            @empty
                {{-- Fallback jika tidak ada data slider yang tersedia --}}
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x450/007bff/white?text=LAYANAN+PENGADUAN" class="d-block w-100" alt="Kontak Header Fallback">
                    
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="text-white">LAYANAN PENGADUAN</h1>
                        <p class="text-white-50">Sampaikan laporan atau pengaduan Anda di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Tampilkan tombol navigasi hanya jika slide lebih dari 1 --}}
        @if(isset($sliders) && $sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#kontakHeader" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#kontakHeader" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
        
    </div>

</div> <div class="container my-5">
    <div class="card shadow-lg border-0" style="border-radius: 12px;">
        <div class="row g-0">
            <div class="col-lg-5" style="background-color: var(--primary-color); color: white; border-radius: 12px 0 0 12px;">
                <div class="p-4 p-md-5">
                    <h3 class="fw-bold text-white mb-4">Info Kontak</h3>
                    <p class="text-white-50">Jika Anda memerlukan sesuatu untuk ditanyakan, silakan hubungi kami melalui detail di bawah ini atau gunakan formulir di samping untuk mengirim pengaduan.</p>
                    <hr class="border-light">
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h5 class="text-white mb-1">Alamat</h5>
                            <p class="text-white-50 mb-0">Jl. Jend. Sudirman No. 123, Pekanbaru, Riau, 28282</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h5 class="text-white mb-1">Email</h5>
                            <p class="text-white-50 mb-0">info@dinsos.riau.go.id</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-telephone-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h5 class="text-white mb-1">Telepon</h5>
                            <p class="text-white-50 mb-0">(0761) 123-456</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4">Formulir Pengaduan Masyarakat</h4>
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Formulir sudah benar dengan enctype="multipart/form-data" --}}
                    <form action="{{ route('public.kontak.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Nama Lengkap Anda">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="email@anda.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- Field No HP --}}
                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">No. HP (WhatsApp)</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="0812xxxx">
                                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="isi_pengaduan" class="form-label">Isi Pengaduan / Pesan</label>
                            <textarea class="form-control @error('isi_pengaduan') is-invalid @enderror" id="isi_pengaduan" name="isi_pengaduan" rows="6" required placeholder="Tuliskan pesan atau detail pengaduan Anda di sini...">{{ old('isi_pengaduan') }}</textarea>
                            @error('isi_pengaduan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Field Upload Foto --}}
                        <div class="mb-3">
                            <label for="foto_pengaduan" class="form-label">Foto (Opsional)</label>
                            <input type="file" class="form-control @error('foto_pengaduan') is-invalid @enderror" id="foto_pengaduan" name="foto_pengaduan" accept="image/*">
                            <small class="text-muted">Max 2MB. Lampirkan foto jika diperlukan (misal: bukti, KTP, dll)</small>
                            @error('foto_pengaduan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fs-5 py-2">Kirim Pengaduan <i class="bi bi-send ms-1"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection