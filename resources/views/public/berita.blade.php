@extends('layouts.public')

{{-- 1. CSS KUSTOM (Saya tambahkan CSS Paginasi di sini) --}}
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
    /* Tambahan untuk teks judul di HOT NEWS agar bisa resize otomatis */
.card-hover-caption h6 {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 0;
    line-height: 1.4;
    transform: translateY(15px);
    transition: transform 0.4s ease 0.1s, font-size 0.2s ease;
    font-size: clamp(0.8rem, 1vw + 0.6rem, 1rem); /* batas aman awal */
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3; /* maksimal 3 baris */
    -webkit-box-orient: vertical;
}

/* ▼▼▼ [BARU] CSS KUSTOM UNTUK PAGINASI (GAYA LINGKARAN) ▼▼▼ */
/* Wrapper untuk memberi scope pada style kita */
.pagination-circles {
    margin-top: 1.5rem;
}

/* Container utama pagination */
.pagination-circles .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.75rem; /* Jarak antar item (panah dan lingkaran) */
    padding-left: 0;
    list-style: none;
    margin: 0;
}

/* Styling untuk SEMUA item, termasuk angka dan panah */
.pagination-circles .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px; /* Ukuran lingkaran */
    height: 42px; /* Ukuran lingkaran */
    border-radius: 50%; /* Membuatnya bulat */
    border: 1px solid #e0e0e0; /* Border abu-abu muda */
    background-color: #f0f0f0; /* Latar belakang abu-abu seperti gambar */
    color: #333; /* Warna angka */
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    position: relative; /* Diperlukan untuk ::after */
}

/* Styling KHUSUS untuk Panah (Previous/Next) */
.pagination-circles .page-item:first-child .page-link,
.pagination-circles .page-item:last-child .page-link {
    background-color: transparent; /* Panah tidak punya background lingkaran */
    border: none; /* Panah tidak punya border */
    color: #000; /* Warna panah hitam seperti di gambar */
    font-size: 1.5rem; /* Membuat panah lebih besar */
}

/* Panah yang dinonaktifkan */
.pagination-circles .page-item.disabled:first-child .page-link,
.pagination-circles .page-item.disabled:last-child .page-link {
    color: #ccc; /* Panah disabled jadi abu-abu */
    background-color: transparent;
    border: none;
}

/* Styling untuk Angka Halaman yang di-hover */
.pagination-circles .page-item:not(:first-child):not(:last-child):not(.active) .page-link:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
}

/* Styling untuk Halaman AKTIF (seperti '2' di gambar) */
.pagination-circles .page-item.active .page-link {
    transform: scale(1.15); /* Sedikit lebih besar */
    background-color: #e0e0e0; /* Latar abu-abu sedikit lebih gelap */
    border: 1px solid #a0a0a0; /* Border lebih tegas */
    z-index: 1;
}

/* Underline untuk halaman AKTIF */
.pagination-circles .page-item.active {
    position: relative;
}
.pagination-circles .page-item.active::after {
    content: '';
    position: absolute;
    bottom: -12px; /* Jarak underline dari lingkaran */
    left: 50%;
    transform: translateX(-50%);
    width: 70%; /* Lebar underline */
    height: 4px; /* Ketebalan underline */
    background-color: #b0b0b0; /* Warna underline abu-abu */
    border-radius: 2px;
}

/* Styling untuk '...' (ellipsis) jika muncul */
.pagination-circles .page-item.disabled:not(:first-child):not(:last-child) span.page-link {
    background-color: #f0f0f0;
    border-color: #e0e0e0;
    color: #333;
}

/* Sembunyikan teks "Previous" & "Next" dari screen reader */
.pagination-circles .page-link span[aria-hidden="true"] {
    display: none;
}
.pagination-circles .page-link .visually-hidden {
    display: none;
}
/* ▲▲▲ AKHIR CSS KUSTOM PAGINASI ▲▲▲ */

{{-- ▼▼▼ [BARU] STYLE TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
#backToTopBtn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    visibility: hidden; /* Sembunyi secara default */
    opacity: 0;
    transition: visibility 0.3s, opacity 0.3s ease-in-out;
    
    /* Menggunakan style Bootstrap */
    padding: 0.5rem 1rem; /* Sesuaikan padding untuk btn-lg */
    font-size: 1.25rem; /* Sesuaikan font-size untuk btn-lg */
    width: 58px; /* Pastikan bulat sempurna untuk btn-lg */
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#backToTopBtn.show {
    visibility: visible;
    opacity: 1;
}
{{-- ▲▲▲ AKHIR STYLE TOMBOL BACK TO TOP ▲▲▲ --}}

</style>
@endpush


@section('content')

<div class="container my-5">

    {{-- 
        ===== BAGIAN SLIDER (BERITA UNGGULAN) =====
        Logika ini sekarang mengambil dari $hot_news (berita biasa terbaru)
        Anda mungkin ingin menggantinya agar mengambil dari $sliders
        tapi saya biarkan dulu sesuai kode asli Anda.
    --}}
    <div id="newsSlider" class="carousel slide mb-5 news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            {{-- PERHATIAN: $hot_news sekarang BUKAN data slider, tapi data berita --}}
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
                {{-- PERBAIKAN: Hapus 'storage/' . --}}
                <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/1200x450/e0e0e0/999?text=Berita+Slider' }}" class="d-block w-100" alt="{{ $hot->judul }}">
                
                <div class="carousel-caption d-none d-md-block">
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
            
            {{-- Grid "HOT NEWS" (Tidak berubah, ini sudah mengambil $hot_news) --}}
            <div class="row mb-4">
                <div class="col-lg-4 mb-3 hot-news-large"> 
                    @if(isset($hot_news[0]))
                        @php $hot = $hot_news[0]; @endphp
                        <div class="card card-news-hover h-100">
                             {{-- PERBAIKAN: Hapus 'storage/' . --}}
                            <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/400x600/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
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
                                     {{-- PERBAIKAN: Hapus 'storage/' . --}}
                                    <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
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
                                     {{-- PERBAIKAN: Hapus 'storage/' . --}}
                                    <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
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
                                     {{-- PERBAIKAN: Hapus 'storage/' . --}}
                                    <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
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
                                     {{-- PERBAIKAN: Hapus 'storage/' . --}}
                                    <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
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
                                     {{-- PERBAIKAN: Hapus 'storage/' . --}}
                                    <img src="{{ $hot->gambar ? asset($hot->gambar) : 'https://placehold.co/400x250/e0e0e0/999?text=Hot+News' }}" class="card-img-top" alt="{{ $hot->judul }}">
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


            <h2 class="section-title">BERITA LAINNYA</h2>
            
            {{-- Grid "BERITA LAINNYA" (Tidak berubah, ini sudah mengambil $beritas) --}}
            <div class="row">
                @forelse($beritas as $berita)
                <div class="col-md-4 mb-4">
                    <div class="card card-news h-100"> 
                        @if($berita->gambar)
                             {{-- PERBAIKAN: Hapus 'storage/' . --}}
                            <img src="{{ asset($berita->gambar) }}" class="card-img-top" alt="{{ $berita->judul }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://placehold.co/400x200/e0e0e0/999?text=Berita" class="card-img-top" alt="Placeholder" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body p-3">
                            <span class="badge bg-primary mb-2">Berita</span>
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
                        Belum ada berita lainnya yang dipublikasikan.
                    </div>
                </div>
                @endforelse
            </div>

        </div> 
        
        <div class="col-lg-4">
            <div class="card" style="border: 1px solid #e0e0e0;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color: var(--dark-blue);">TOPIK LAINNYA</h5>
                    
                    {{-- ▼▼▼ PERBARUAN: Ganti @for dengan @forelse ▼▼▼ --}}
                    <ul class="list-unstyled">
                        @forelse($topik_lainnya as $topik)
                        <li class="mb-3 border-bottom pb-3">
                            <a href="{{ route('public.berita.detail', $topik->id) }}" class="d-flex text-decoration-none text-dark">
                                 {{-- PERBAIKAN: Hapus 'storage/' . --}}
                                <img src="{{ $topik->gambar ? asset($topik->gambar) : 'https://placehold.co/70x70/e0e0e0/999?text=Topik' }}" 
                                     alt="{{ $topik->judul }}" 
                                     style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                <div class="ms-3">
                                    {{-- Tambahkan badge tag --}}
                                    <span class="badge 
                                        @if($topik->tag == 'info') bg-info text-dark
                                        @elseif($topik->tag == 'layanan') bg-success
                                        @elseif($topik->tag == 'kegiatan') bg-warning text-dark
                                        @endif
                                        mb-1" style="font-size: 0.7rem;">
                                        {{ ucfirst($topik->tag) }}
                                    </span>
                                    <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                        {{ $topik->judul }}
                                    </h6>
                                    <small class="text-muted">{{ $topik->created_at->format('d F Y') }}</small>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="mb-3 text-muted small">
                            Belum ada topik lainnya yang dipublikasikan.
                        </li>
                        @endforelse
                    </ul>
                    {{-- ▲▲▲ AKHIR PERBARUAN ▲▲▲ --}}

                    {{-- ▼▼▼ [BARU] Tombol Lihat Selengkapnya ▼▼▼ --}}
                    <div class="text-center mt-3">
                        {{-- Pastikan Anda sudah membuat route 'public.berita.topik' --}}
                        <a href="{{ route('public.berita.topik') }}" class="btn btn-outline-primary w-100 btn-sm">
                            Lihat Semua Topik
                            <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                    {{-- ▲▲▲ AKHIR Tombol Lihat Selengkapnya ▲▲▲ --}}

                </div>
            </div>
        </div> 
    </div> 
    
    {{-- ▼▼▼ [BARU] PAGINASI KUSTOM (GAYA LINGKARAN) ▼▼▼ --}}
    {{-- Saya ganti div lama dengan div .pagination-circles --}}
    <div class="pagination-circles">
        {{-- Saya tambahkan withQueryString() untuk jaga-jaga jika ada filter --}}
        {!! $beritas->withQueryString()->links('vendor.pagination.custom-circle') !!}
    </div>
    {{-- ▲▲▲ AKHIR PAGINASI KUSTOM ▲▲▲ --}}
    
</div>

{{-- ▼▼▼ [BARU] HTML TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>
{{-- ▲▲▲ AKHIR HTML TOMBOL BACK TO TOP ▲▲▲ --}}

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const adjustFontSize = (element, maxHeight) => {
        let fontSize = parseFloat(window.getComputedStyle(element).fontSize);
        while (element.scrollHeight > maxHeight && fontSize > 10) {
            fontSize -= 0.5;
            element.style.fontSize = fontSize + "px";
        }
    };

    document.querySelectorAll(".card-hover-caption h6").forEach(el => {
        const caption = el.closest(".card-hover-caption");
        if (caption) {
            const availableHeight = caption.clientHeight * 0.35; // hanya bagian bawah yang dipakai
            adjustFontSize(el, availableHeight);
        }
    });
});
</script>

{{-- ▼▼▼ [BARU] SCRIPT UNTUK TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mybutton = document.getElementById("backToTopBtn");

        // Tampilkan/sembunyikan tombol
        window.onscroll = function() {
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            
            // Tampilkan tombol setelah scroll 300px
            if (scrollTop > 300) { 
                mybutton.classList.add("show");
            } else {
                mybutton.classList.remove("show");
            }
        };

        // Scroll ke atas saat diklik
        mybutton.onclick = function(e) {
            e.preventDefault(); // Mencegah URL berubah menjadi #
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Animasi scroll halus
            });
        }
    });
</script>
{{-- ▲▲▲ AKHIR SCRIPT TOMBOL BACK TO TOP ▲▲▲ --}}

@endpush

@endsection