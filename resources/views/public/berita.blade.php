@extends('layouts.public')

{{-- 1. CSS KUSTOM (Tidak ada perubahan) --}}
@push('styles')
<style>
    /* === CSS BARU UNTUK SLIDER === */
    .news-slider .carousel-item {
        height: 450px; /* Atur tinggi slider */
        background-color: #555; /* Warna fallback jika gambar error */
    }

    .news-slider .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Pastikan gambar mengisi area tanpa distorsi */
    }

    /* Overlay gradient gelap di atas gambar slider agar teks terbaca */
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
        bottom: 0; /* Posisikan caption di bawah */
        z-index: 10;
        text-align: left; /* Teks rata kiri */
        padding: 2rem 1.5rem;
        width: 80%; /* Batasi lebar caption */
        left: 5%; /* Posisikan caption agak ke tengah */
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


    /* Style baru untuk kartu "HOT NEWS" dengan efek hover */
    .card-news-hover {
        position: relative;
        overflow: hidden; /* Sembunyikan caption yg mungkin keluar */
        border: none;
        border-radius: 12px; /* Sesuai style .card-news lama */
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        background-color: #e0e0e0; /* Latar belakang jika gambar tidak ada */
    }

    .card-news-hover .card-img-top {
        transition: transform 0.4s ease;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Efek zoom pada gambar saat hover */
    .card-news-hover:hover .card-img-top {
        transform: scale(1.05);
    }

    /* Caption yang akan muncul */
    .card-hover-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%; /* Penuhi seluruh kartu */
        
        /* Gradient gelap dari bawah ke atas */
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 60%);
        
        display: flex;
        align-items: flex-end; /* Ratakan teks ke bawah */
        padding: 1rem;
        
        opacity: 0; /* Sembunyi secara default */
        transition: opacity 0.4s ease;
    }

    .card-news-hover:hover .card-hover-caption {
        opacity: 1; /* Tampilkan saat hover */
    }

    .card-hover-caption h6 {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0;
        line-height: 1.4;
        /* Efek transisi teks muncul dari bawah */
        transform: translateY(15px);
        transition: transform 0.4s ease 0.1s;
    }

    .card-news-hover:hover .card-hover-caption h6 {
        transform: translateY(0);
    }

    /* Tinggi kustom untuk grid "HOT NEWS" agar ASIMETRIS */
    .hot-news-small-top {
        height: 200px; 
    }
    .hot-news-small-bottom {
        height: 160px; 
    }
    .hot-news-large {
        /* Total tinggi: 200px + 160px + 1rem (margin-bottom) */
        height: calc(200px + 160px + 1rem); 
    }

    /* Style untuk placeholder jika berita tidak ada */
    .card-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background-color: #f0f0f0;
        border-radius: 12px;
        color: #aaa;
    }
</style>
@endpush


@section('content')

<div class="container my-5">

    <div id="newsSlider" class="carousel slide mb-5 news-slider" data-bs-ride="carousel" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            @foreach($hot_news ?? [] as $hot)
                <button type="button" data-bs-target="#newsSlider" data-bs-slide-to="{{ $loop->index }}" 
                        class="@if($loop->first) active @endif" 
                        aria-current="@if($loop->first) true @endif" 
                        aria-label="Slide {{ $loop->iteration }}">
                </button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @forelse($hot_news ?? [] as $hot)
            <div class="carousel-item @if($loop->first) active @endif">
                <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/1200x450/e0e0e0/999?text=Berita+Slider' }}" class="d-block w-100" alt="{{ $hot->judul }}">
                
                <div class="carousel-caption d-none d-md-block">
                    {{-- LINK DIPERBARUI --}}
                    <a href="{{ route('public.berita.detail', $hot->id) }}" class="text-decoration-none text-white stretched-link">
                        <h5>{{ $hot->judul }}</h5>
                    </a>
                    <p>{{ Str::limit(strip_tags($hot->isi), 150) }}</p>
                </div>
            </div>
            @empty
            <div class="carousel-item active">
                <img src="https://placehold.co/1200x450/e0e0e0/999?text=Slider+Berita" class="d-block w-100" alt="Placeholder">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Belum Ada Berita Unggulan</h5>
                    <p>Silakan tambahkan berita baru untuk ditampilkan di sini.</p>
                </div>
            </div>
            @endforelse
        </div>

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

        <div class="col-lg-8">

            <h2 class="fw-bold mb-3" style="color: var(--dark-blue);">HOT NEWS</h2>
            
            <div class="row mb-4">
                
                <div class="col-lg-4 mb-3 hot-news-large"> 
                    @if(isset($hot_news[0]))
                        @php $hot = $hot_news[0]; @endphp
                        <div class="card card-news-hover h-100">
                            <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/400x600/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
                            {{-- LINK DIPERBARUI --}}
                            <a href="{{ route('public.berita.detail', $hot->id) }}" class="stretched-link"></a>
                            <div class="card-hover-caption">
                                <h6>{{ $hot->judul }}</h6>
                            </div>
                        </div>
                    @else
                        <div class="card-placeholder"><span>Berita 1</span></div>
                    @endif
                </div>

                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6 mb-3 hot-news-small-top">
                            @if(isset($hot_news[1]))
                                @php $hot = $hot_news[1]; @endphp
                                <div class="card card-news-hover h-100">
                                    <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
                                    {{-- LINK DIPERBARUI --}}
                                    <a href="{{ route('public.berita.detail', $hot->id) }}" class="stretched-link"></a>
                                    <div class="card-hover-caption">
                                        <h6>{{ $hot->judul }}</h6>
                                    </div>
                                </div>
                            @else
                                <div class="card-placeholder"><span>Berita 2</span></div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3 hot-news-small-top">
                            @if(isset($hot_news[2]))
                                @php $hot = $hot_news[2]; @endphp
                                <div class="card card-news-hover h-100">
                                    <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
                                    {{-- LINK DIPERBARUI --}}
                                    <a href="{{ route('public.berita.detail', $hot->id) }}" class="stretched-link"></a>
                                    <div class="card-hover-caption">
                                        <h6>{{ $hot->judul }}</h6>
                                    </div>
                                </div>
                            @else
                                <div class="card-placeholder"><span>Berita 3</span></div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 hot-news-small-bottom">
                            @if(isset($hot_news[3]))
                                @php $hot = $hot_news[3]; @endphp
                                <div class="card card-news-hover h-100">
                                    <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
                                    {{-- LINK DIPERBARUI --}}
                                    <a href="{{ route('public.berita.detail', $hot->id) }}" class="stretched-link"></a>
                                    <div class="card-hover-caption">
                                        <h6>{{ $hot->judul }}</h6>
                                    </div>
                                </div>
                            @else
                                <div class="card-placeholder"><span>Berita 4</span></div>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3 hot-news-small-bottom">
                            @if(isset($hot_news[4]))
                                @php $hot = $hot_news[4]; @endphp
                                <div class="card card-news-hover h-100">
                                    <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
                                    {{-- LINK DIPERBARUI --}}
                                    <a href="{{ route('public.berita.detail', $hot->id) }}" class="stretched-link"></a>
                                    <div class="card-hover-caption">
                                        <h6>{{ $hot->judul }}</h6>
                                    </div>
                                </div>
                            @else
                                <div class="card-placeholder"><span>Berita 5</span></div>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3 hot-news-small-bottom">
                            @if(isset($hot_news[5]))
                                @php $hot = $hot_news[5]; @endphp
                                <div class="card card-news-hover h-100">
                                    <img src="{{ $hot->gambar ? asset('storage/' . $hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
                                    {{-- LINK DIPERBARUI --}}
                                    <a href="{{ route('public.berita.detail', $hot->id) }}" class="stretched-link"></a>
                                    <div class="card-hover-caption">
                                        <h6>{{ $hot->judul }}</h6>
                                    </div>
                                </div>
                            @else
                                <div class="card-placeholder"><span>Berita 6</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- === AKHIR BAGIAN HOT NEWS === --}}


            <h2 class="section-title">BERITA LAINNYA</h2>
            
            <div class="row">
                @forelse($beritas as $berita)
                <div class="col-md-4 mb-4">
                    <div class="card card-news h-100"> 
                        @if($berita->gambar)
                            <img src="{{ asset('storage/' . $berita->gambar) }}" class="card-img-top" alt="{{ $berita->judul }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://placehold.co/400x200/e0e0e0/999?text=Berita" class="card-img-top" alt="Placeholder" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body p-3">
                            <span class="badge bg-primary mb-2">Berita</span>
                            <small class="text-muted d-block mb-1">{{ $berita->created_at->format('d F Y') }}</small>
                            <h5 class="card-title">
                                {{-- LINK DIPERBARUI --}}
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
                        Belum ada berita lainnya yang dipublikasikan.
                    </div>
                </div>
                @endforelse
            </div>

        </div> <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px; border: 1px solid #e0e0e0;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color: var(--dark-blue);">TOPIK LAINNYA</h5>
                    
                    <ul class="list-unstyled">
                        @for ($i = 0; $i < 5; $i++)
                        <li class="mb-3 border-bottom pb-3">
                            <a href="#" class="d-flex text-decoration-none text-dark">
                                <img src="https://placehold.co/70x70/333/555?text=Topik" alt="Placeholder" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                <div class="ms-3">
                                    <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                        Ini adalah placeholder untuk topik lainnya
                                    </h6>
                                    <small class="text-muted">04/11/2025</small>
                                </div>
                            </a>
                        </li>
                        @endfor
                    </ul>

                </div>
            </div>
        </div> </div> <div class="d-flex justify-content-center mt-4">
        {!! $beritas->links() !!}
    </div>
    
</div>

@endsection