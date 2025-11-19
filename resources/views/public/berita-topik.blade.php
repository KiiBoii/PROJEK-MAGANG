@extends('layouts.public')

{{-- 1. CSS KUSTOM --}}
@push('styles')
<style>
    /* === CSS SLIDER === */
    .news-slider .carousel-item {
        height: 450px; 
        background-color: #555;
    }
    .news-slider .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .news-slider .carousel-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 25%, rgba(0,0,0,0) 100%);
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
    /* === AKHIR CSS SLIDER === */

    /* === CSS PAGINASI (GAYA LINGKARAN) === */
    .pagination-circles {
        margin-top: 1.5rem;
    }
    .pagination-circles .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.75rem;
        padding-left: 0;
        list-style: none;
        margin: 0;
    }
    .pagination-circles .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid #e0e0e0;
        background-color: #f0f0f0;
        color: #333;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        position: relative;
    }
    .pagination-circles .page-item:first-child .page-link,
    .pagination-circles .page-item:last-child .page-link {
        background-color: transparent;
        border: none;
        color: #000;
        font-size: 1.5rem;
    }
    .pagination-circles .page-item.disabled:first-child .page-link,
    .pagination-circles .page-item.disabled:last-child .page-link {
        color: #ccc;
        background-color: transparent;
        border: none;
    }
    .pagination-circles .page-item:not(:first-child):not(:last-child):not(.active) .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }
    .pagination-circles .page-item.active .page-link {
        transform: scale(1.15);
        background-color: #e0e0e0;
        border: 1px solid #a0a0a0;
        z-index: 1;
    }
    .pagination-circles .page-item.active {
        position: relative;
    }
    .pagination-circles .page-item.active::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 70%;
        height: 4px;
        background-color: #b0b0b0;
        border-radius: 2px;
    }
    .pagination-circles .page-item.disabled:not(:first-child):not(:last-child) span.page-link {
        background-color: #f0f0f0;
        border-color: #e0e0e0;
        color: #333;
    }
    .pagination-circles .page-link span[aria-hidden="true"] {
        display: none;
    }
    .pagination-circles .page-link .visually-hidden {
        display: none;
    }
    /* === AKHIR CSS PAGINASI === */

    /* === CSS TOMBOL BACK TO TOP === */
    #backToTopBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s ease-in-out;
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #backToTopBtn.show {
        visibility: visible;
        opacity: 1;
    }
    /* === AKHIR CSS TOMBOL BACK TO TOP === */
</style>
@endpush


@section('content')

<div class="container my-5">

    {{-- 
    ===== BAGIAN SLIDER =====
    ($sliders berisi 3 data Berita 'Topik' terbaru)
--}}
<div id="newsSlider" class="carousel slide mb-5 news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
    
    <div class="carousel-indicators">
        @foreach($sliders ?? [] as $slider)
            <button type="button" data-bs-target="#newsSlider" data-bs-slide-to="{{ $loop->index }}" 
                    class="@if($loop->first) active @endif" 
                    aria-current="@if($loop->first) true @endif" 
                    aria-label="Slide {{ $loop->iteration }}">
            </button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @forelse($sliders ?? [] as $slider)
        <div class="carousel-item @if($loop->first) active @endif">
            
            {{-- ▼▼▼ PERUBAHAN YANG DITERAPKAN ADA DI SINI ▼▼▼ --}}
            <a href="{{ route('public.berita.detail', $slider->id) }}">
                <img src="{{ $slider->gambar ? asset('storage/' . $slider->gambar) : 'https://placehold.co/1200x450/e0e0e0/999?text=Topik' }}" class="d-block w-100" alt="{{ $slider->judul }}">
            </a>
            {{-- ▲▲▲ AKHIR PERUBAHAN ▲▲▲ --}}

            <div class="carousel-caption d-none d-md-block">
                {{-- Judul slider bisa diklik, mengarah ke detail berita --}}
                <a href="{{ route('public.berita.detail', $slider->id) }}" class="text-decoration-none">
                    <h5 class="text-white">{{ $slider->judul }}</h5>
                </a>
                
                {{-- Mengambil ringkasan dari 'isi' berita --}}
                <p>{{ Str::limit(strip_tags($slider->isi), 150) }}</p>
            </div>
        </div>
        @empty
        <div class="carousel-item active">
            <img src="https://placehold.co/1200x450/e0e0e0/999?text=Slider+Berita" class="d-block w-100" alt="Placeholder">
            <div class="carousel-caption d-none d-md-block">
                <h5>Selamat Datang</h5>
                <p>Belum ada berita topik yang bisa ditampilkan di slider.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Tombol Prev/Next --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#newsSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#newsSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
    
    <div class="row">

        {{-- KOLOM KIRI (UTAMA) --}}
        <div class="col-lg-8">

            <h2 class="section-title">SEMUA TOPIK</h2>
            
            {{-- Grid "SEMUA TOPIK" --}}
            <div class="row">
                {{-- Loop ini mengambil dari $semua_topik --}}
                @forelse($semua_topik as $berita)
                <div class="col-md-4 mb-4">
                    {{-- Menggunakan style card-news yang sama dari berita.blade.php --}}
                    <div class="card card-news h-100"> 
                        @if($berita->gambar)
                            <img src="{{ asset('storage/' . $berita->gambar) }}" class="card-img-top" alt="{{ $berita->judul }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://placehold.co/400x200/e0e0e0/999?text=Topik" class="card-img-top" alt="Placeholder" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body p-3">
                            {{-- Tampilkan badge tag --}}
                            <span class="badge 
                                @if($berita->tag == 'info') bg-info text-dark
                                @elseif($berita->tag == 'layanan') bg-success
                                @elseif($berita->tag == 'kegiatan') bg-warning text-dark
                                @else bg-secondary
                                @endif
                                mb-2">
                                {{ ucfirst($berita->tag) }}
                            </span>
                            <small class="text-muted d-block mb-1">{{ $berita->created_at->format('d F Y') }}</small>
                            <h5 class="card-title">
                                <a href="{{ route('public.berita.detail', $berita->id) }}" class="text-decoration-none text-dark stretched-link">
                                    {{ $berita->judul }}
                                </a>
                            </h5>
                            <p class="card-text text-muted">{{ Str::limit(strip_tags($berita->isi), 100) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-secondary text-center">
                        Belum ada topik yang dipublikasikan.
                    </div>
                </div>
                @endforelse
            </div>

        </div> 
        
        {{-- KOLOM KANAN (SIDEBAR) --}}
        <div class="col-lg-4">
            <div class="card" style="border: 1px solid #e0e0e0;">
                <div class="card-body">
                    {{-- Sidebar ini diisi Berita Biasa (non-topik) terbaru --}}
                    <h5 class="fw-bold mb-3" style="color: var(--dark-blue);">BERITA TERBARU</h5>
                    
                    <ul class="list-unstyled">
                        {{-- Loop ini mengambil dari $berita_terbaru_sidebar --}}
                        @forelse($berita_terbaru_sidebar as $berita)
                        <li class="mb-3 border-bottom pb-3">
                            {{-- Menggunakan style list yang sama dari berita.blade.php --}}
                            <a href="{{ route('public.berita.detail', $berita->id) }}" class="d-flex text-decoration-none text-dark">
                                <img src="{{ $berita->gambar ? asset('storage/'. $berita->gambar) : 'https://placehold.co/70x70/e0e0e0/999?text=Berita' }}" 
                                     alt="{{ $berita->judul }}" 
                                     style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                <div class="ms-3">
                                    <span class="badge bg-primary mb-1" style="font-size: 0.7rem;">
                                        Berita
                                    </span>
                                    <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                        {{ $berita->judul }}
                                    </h6>
                                    <small class="text-muted">{{ $berita->created_at->format('d F Y') }}</small>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="mb-3 text-muted small">
                            Belum ada berita terbaru yang dipublikasikan.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div> 
    </div> 
    
    {{-- PAGINASI KUSTOM (GAYA LINGKARAN) --}}
    <div class="pagination-circles">
        {{-- Paginasi ini untuk $semua_topik --}}
        {!! $semua_topik->withQueryString()->links('vendor.pagination.custom-circle') !!}
    </div>
    
</div>

{{-- HTML TOMBOL BACK TO TOP --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>

@push('scripts')
{{-- SCRIPT UNTUK TOMBOL BACK TO TOP --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mybutton = document.getElementById("backToTopBtn");

        window.onscroll = function() {
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            if (scrollTop > 300) { 
                mybutton.classList.add("show");
            } else {
                mybutton.classList.remove("show");
            }
        };

        mybutton.onclick = function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    });
</script>
@endpush

@endsection