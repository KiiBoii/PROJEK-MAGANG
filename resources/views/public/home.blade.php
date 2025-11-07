@extends('layouts.public')

{{-- 1. CSS KUSTOM (Tidak ada perubahan) --}}
@push('styles')
<style>
    /* Mengambil style dari halaman berita agar sama persis */
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

    .news-slider .carousel-caption h5 {
        font-size: 2rem; 
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .news-slider .carousel-caption p {
        font-size: 1rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
</style>
@endpush


@section('content')

<!-- 1. Bagian Slider/Hero (TELAH DIPERBARUI MENJADI DINAMIS) -->
<div class="container my-5">
    
    <div id="heroSlider" class="carousel slide news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" 
         style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <!-- Indicators (Dibuat dinamis) -->
        <div class="carousel-indicators">
            @foreach($sliders as $slider)
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
        
        <!-- Slides (Dibuat dinamis) -->
        <div class="carousel-inner">
            @forelse($sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $slider->gambar) }}" class="d-block w-100" alt="{{ $slider->judul }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $slider->judul }}</h5>
                        <p>{{ $slider->keterangan }}</p>
                    </div>
                </div>
            @empty
                {{-- Fallback jika tidak ada slider yang 'tampil' --}}
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x450/007bff/white?text=Selamat+Datang" class="d-block w-100" alt="Slider 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Selamat Datang di Dinas Sosial Riau</h5>
                        <p>Melayani dengan hati, menjangkau seluruh lapisan masyarakat.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Tampilkan tombol navigasi hanya jika slide lebih dari 1 --}}
        @if($sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>

</div> <!-- Penutup container slider -->


<!-- 2. Bagian Sambutan Kepala Dinas (Tidak diubah) -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <small class="text-primary fw-bold text-uppercase">Profil</small>
            <h2 class="fw-bold mb-3" style="color: #0d47a1;">KEPALA DINAS</h2>
            
            <div class="d-flex mb-3">
                <img src="https://placehold.co/150x150/e0e0e0/333?text=FOTO+KADIS" class="rounded-circle" alt="Foto Kepala Dinas" style="width: 150px; height: 150px; object-fit: cover;">
                <div class="ms-4">
                    <h4 class="fw-bold mb-1">Nama Kepala Dinas, S.H., M.Si.</h4>
                    <p class="text-muted mb-2">Kepala Dinas Sosial Provinsi Riau</p>
                    <p class="text-muted small">
                        Tempat/Tgl Lahir: Pekanbaru, 01 Januari 1970<br>
                        Masa Jabatan: 2023 - Sekarang
                    </p>
                    <div class="d-flex">
                        <a href="#" class="btn btn-primary btn-sm rounded-pill me-2">Baca Lebih Lanjut</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill mx-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mt-4">Kata Sambutan</h5>
            <p class="text-muted">"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse potenti. Nulla facilisi. Praesent sed felis metus. Vestibulum ac mauris pretium, consequat eros vitae, volutpat neque. Mauris pretium, nisl sed facilisis eleifend, eros felis mollis ante, ac tincidunt lacus felis non
            erat. Suspendisse potenti."</p>
        </div>
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3">Video Kegiatan Kepala Dinas</h5>
            <div class="ratio ratio-16x9 rounded-3 shadow-sm">
                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=example" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<!-- 3. Bagian Berita Terbaru (Tidak diubah) -->
<div class="py-5" style="background-color: #ffffff;">
    <div class="container">
        <h2 class="section-title">Berita Terbaru</h2>
        
        @if($beritaUtama)
        <div class="card mb-5 shadow-lg border-0">
            <div class="row g-0">
                <div class="col-md-6">
                    @if($beritaUtama->gambar)
                        <img src="{{ asset('storage/' . $beritaUtama->gambar) }}" class="img-fluid rounded-start" alt="{{ $beritaUtama->judul }}" style="height: 400px; width: 100%; object-fit: cover;">
                    @else
                        <img src="https://placehold.co/600x400/e0e0e0/999?text=Berita+Utama" class="img-fluid rounded-start" alt="Placeholder" style="height: 400px; width: 100%; object-fit: cover;">
                    @endif
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="card-body p-lg-5">
                        <h3 class="card-title fw-bold mb-3">{{ $beritaUtama->judul }}</h3>
                        <p class="card-date text-muted"><i class="bi bi-calendar3 me-2"></i>{{ $beritaUtama->created_at->format('d F Y') }}</p>
                        <p class="card-text">{{ Str::limit(strip_tags($beritaUtama->isi), 250) }}</p>
                        
                        <a href="{{ route('public.berita.detail', $beritaUtama->id) }}" class="btn btn-primary rounded-pill mt-3">
                            Baca Selengkapnya <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Berita Lainnya --}}
        <div class="p-4 rounded-3" style="background-color: var(--primary-color);">
            <h4 class="text-white fw-bold mb-3 text-center">Berita Lainnya</h4>
            <div class="row">
                
                @forelse($beritaLainnya->take(5) as $berita)
                <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                    <div class="card card-news h-100">
                        @if($berita->gambar)
                            <img src="{{ asset('storage/' . $berita->gambar) }}" class="card-img-top" alt="{{ $berita->judul }}" style="height: 220px; object-fit: cover;">
                        @else
                            <img src="https://placehold.co/300x220/e0e0e0/999?text=Berita" class="card-img-top" alt="Placeholder" style="height: 220px; object-fit: cover;">
                        @endif
                        <div class="card-body p-3">
                            
                            <h6 class="card-title small fw-bold text-center">
                                <a href="{{ route('public.berita.detail', $berita->id) }}" class="text-decoration-none text-primary stretched-link">
                                    {{ Str::limit($berita->judul, 50) }}
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-white text-center">Belum ada berita lainnya.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection