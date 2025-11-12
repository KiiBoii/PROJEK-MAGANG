@extends('layouts.public')

{{-- CSS Kustom (DITAMBAHKAN STYLE SLIDER, PAGINASI, DAN LIGHTBOX) --}}
@push('styles')
<style>
    /* === 1. CSS SLIDER (DARI HOME.BLADE.PHP) === */
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

    /* 2. Style untuk filter sidebar (List) */
    .filter-sidebar .list-group-item {
        border: none;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        background-color: transparent; 
        text-align: left;
        width: 100%;
    }
    .filter-sidebar .list-group-item:last-child {
        border-bottom: none;
    }
    .filter-sidebar .list-group-item {
        transition: all 0.2s ease;
        font-weight: 500;
        color: #212529;
    }
    .filter-sidebar .list-group-item.active {
        color: var(--primary-color);
        font-weight: 700;
        border-right: 3px solid var(--primary-color);
    }
    .filter-sidebar .list-group-item:not(.active):hover {
        color: var(--primary-color);
    }

    /* 3. Style Kartu Galeri */
    .card-news-hover {
        position: relative;
        overflow: hidden;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        background-color: #e0e0e0;
        margin-bottom: 1rem; /* Jarak untuk masonry */
        cursor: pointer; /* [UBAH] Tambahkan cursor pointer */
    }

    .card-news-hover .card-img-top {
        transition: transform 0.4s ease;
        width: 100%;
        height: auto; 
        object-fit: cover;
    }

    .card-news-hover:hover .card-img-top {
        transform: scale(1.05);
    }
    .card-hover-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 60%);
        display: flex;
        align-items: flex-end;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.4s ease;
        /* [UBAH] Pastikan caption tidak bisa diklik */
        pointer-events: none; 
    }
    .card-news-hover:hover .card-hover-caption {
        opacity: 1;
    }
    .card-hover-caption h6 {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0;
        line-height: 1.4;
        font-size: 0.9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        transform: translateY(15px);
        transition: transform 0.4s ease 0.1s;
    }
    .card-news-hover:hover .card-hover-caption h6 {
        transform: translateY(0);
    }
    .gallery-bidang-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 5;
        background-color: rgba(0, 123, 255, 0.9);
        color: white;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    /* 4. CSS UNTUK ISOTOPE GRID */
    .gallery-grid-row {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }
    .grid-item {
        width: 33.333%; /* 3 kolom */
        padding: 0.5rem; 
    }
    .grid-item .card-news-hover {
        margin-bottom: 0; 
    }

    @media (max-width: 991.98px) {
        .grid-item {
            width: 50%; /* 2 kolom di tablet */
        }
    }
    @media (max-width: 575.98px) {
        .grid-item {
            width: 100%; /* 1 kolom di mobile */
        }
    }

    /* 5. CSS KUSTOM UNTUK PAGINASI (GAYA LINGKARAN) */
    .pagination-circles {
        margin-top: 1.5rem;
    }
    .pagination-circles .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.75rem; /* Jarak antar item (panah dan lingkaran) */
        padding-left: 0;
        list-style: none;
        margin: 0;
    }
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
    .pagination-circles .page-item:first-child .page-link,
    .pagination-circles .page-item:last-child .page-link {
        background-color: transparent; /* Panah tidak punya background lingkaran */
        border: none; /* Panah tidak punya border */
        color: #000; /* Warna panah hitam seperti di gambar */
        font-size: 1.5rem; /* Membuat panah lebih besar */
    }
    .pagination-circles .page-item.disabled:first-child .page-link,
    .pagination-circles .page-item.disabled:last-child .page-link {
        color: #ccc; /* Panah disabled jadi abu-abu */
        background-color: transparent;
        border: none;
    }
    .pagination-circles .page-item:not(:first-child):not(:last-child):not(.active) .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }
    .pagination-circles .page-item.active .page-link {
        transform: scale(1.15); /* Sedikit lebih besar */
        background-color: #e0e0e0; /* Latar abu-abu sedikit lebih gelap */
        border: 1px solid #a0a0a0; /* Border lebih tegas */
        z-index: 1;
    }
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
    /* ▲▲▲ AKHIR CSS KUSTOM PAGINASI ▲▲▲ */


    /* ▼▼▼ [BARU] 6. CSS KUSTOM UNTUK LIGHTBOX ▼▼▼ */
    .lightbox-overlay {
        display: none; /* Sembunyi by default */
        position: fixed;
        z-index: 9999; /* Paling depan */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9); /* Latar "gelap" */
        
        /* Efek "buram" untuk browser modern */
        @supports (backdrop-filter: blur(5px)) {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        
        overflow-y: auto; /* Izinkan scroll jika gambar sangat tinggi */
        text-align: center;
        padding: 2rem 1rem;
        cursor: pointer; /* Menunjukkan bisa diklik untuk menutup */
    }
    .lightbox-content-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 4rem); /* Ambil tinggi penuh layar dikurangi padding */
    }
    .lightbox-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 85vh; /* Batas tinggi gambar */
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.5);
        animation: zoomIn 0.3s ease; /* Animasi saat muncul */
        cursor: default; /* Kembalikan cursor normal di gambar */
    }
    .lightbox-caption {
        margin: 10px auto 0;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        font-weight: 500;
        font-size: 1rem;
        animation: zoomIn 0.3s ease;
        cursor: default; /* Kembalikan cursor normal di caption */
    }
    .lightbox-close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
        z-index: 10000;
        line-height: 1;
    }
    .lightbox-close:hover,
    .lightbox-close:focus {
        color: #bbb;
        text-decoration: none;
    }
    /* Animasi zoom sederhana */
    @keyframes zoomIn {
        from {transform: scale(0.8); opacity: 0;}
        to {transform: scale(1); opacity: 1;}
    }
    /* ▲▲▲ AKHIR CSS LIGHTBOX ▲▲▲ */
    
    {{-- ▼▼▼ [BARU] STYLE TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
    #backToTopBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000; /* z-index lebih rendah dari lightbox (9999) tapi di atas konten (10) */
        visibility: hidden; /* Sembunyi secara default */
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s ease-in-out;
        
        /* Menggunakan style Bootstrap */
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
    {{-- ▲▲▲ AKHIR STYLE TOMBOL BACK TO TOP ▲▲▲ --}}

</style>
@endpush

@section('content')

<div class="container my-5">

    {{-- Slider --}}
    <div id="gallerySlider" class="carousel slide mb-5 news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            @foreach($galeris->take(6) as $foto_slide)
                <button type="button" data-bs-target="#gallerySlider" data-bs-slide-to="{{ $loop->index }}" 
                        class="@if($loop->first) active @endif" 
                        aria-current="@if($loop->first) true @endif" 
                        aria-label="Slide {{ $loop->iteration }}">
                </button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @forelse($galeris->take(6) as $foto_slide)
            <div class="carousel-item @if($loop->first) active @endif">
                <img src="{{ $foto_slide->foto_path ? asset('storage/' . $foto_slide->foto_path) : 'https://placehold.co/1200x450/e0e0e0/999?text=Galeri' }}" class="d-block w-100" alt="{{ $foto_slide->judul_kegiatan }}">
                
                <div class="carousel-caption d-none d-md-block">
                    {{-- [UBAH] Hapus stretched-link dari slider agar tidak bentrok --}}
                    <a href="#" class="text-decoration-none text-white">
                        <h5>{{ $foto_slide->judul_kegiatan }}</h5>
                    </a>
                    <p>{{ $foto_slide->bidang }}</p>
                </div>
            </div>
            @empty
            <div class="carousel-item active">
                <img src="https://placehold.co/1200x450/17a2b8/white?text=Galeri+Kegiatan" class="d-block w-100" alt="Placeholder">
                <div class="carousel-caption d-none d-md-block">
                    <h5>GALERI KEGIATAN</h5>
                    <p>Dokumentasi kegiatan Dinas Sosial Provinsi Riau.</p>
                </div>
            </div>
            @endforelse
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#gallerySlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#gallerySlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <div class="row">

        <div class="col-lg-3">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4 filter-sidebar">
                    <h5 class="fw-bold mb-3" style="color: var(--dark-blue);">Filter Bidang</h5>
                    
                    <div class="list-group list-group-flush" id="gallery-filter-buttons">
                        
                        <button type="button" class="list-group-item list-group-item-action active" data-filter="*">
                            Seluruh Kegiatan Bidang
                        </button>

                        @foreach($bidangList as $bidang)
                            @php $slug = Str::slug($bidang); @endphp
                            
                            <button type="button" class="list-group-item list-group-item-action" data-filter=".{{ $slug }}">
                                {{ $bidang }}
                            </button>
                        @endforeach
                        
                        @if($bidangList->isEmpty())
                            <button type="button" class="list-group-item list-group-item-action" data-filter=".bidang-aptika">Bidang Aptika</button>
                            <button type="button" class="list-group-item list-group-item-action" data-filter=".bidang-ikp">Bidang IKP</button>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h2 class="section-title">
                Semua Kegiatan
            </h2>
            
            <div class="row gallery-grid-row" id="gallery-grid">
                
                @forelse($galeris as $foto)
                    @php $slug = Str::slug($foto->bidang); @endphp

                    <div class="grid-item {{ $slug }}">
                        {{-- ▼▼▼ [UBAH] Wrapper card sekarang menjadi pemicu lightbox ▼▼▼ --}}
                        <div class="card card-news-hover lightbox-trigger" 
                             href="{{ $foto->foto_path ? asset('storage/' . $foto->foto_path) : 'https://placehold.co/800x600/e0e0e0/999?text=Foto' }}"
                             data-caption="{{ $foto->judul_kegiatan }}"> 
                        {{-- ▲▲▲ AKHIR UBAHAN ▲▲▲ --}}
                            
                            @php
                                $fotoPath = $foto->foto_path ? asset('storage/' . $foto->foto_path) : 'https://placehold.co/300x250/e0e0e0/999?text=Foto';
                            @endphp

                            <img src="{{ $fotoPath }}" class="card-img-top" alt="{{ $foto->judul_kegiatan }}">
                            
                            @if($foto->bidang)
                                <span class="gallery-bidang-badge">{{ $foto->bidang }}</span>
                            @endif

                            {{-- [DIHAPUS] Link stretched-link dihapus karena card-nya sekarang adalah link --}}
                            {{-- <a href="#" class="stretched-link" aria-label="{{ $foto->judul_kegiatan }}"></a> --}}

                            <div class="card-hover-caption">
                                <h6>{{ $foto->judul_kegiatan }}</h6>
                            </div>
                        </div>
                    </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-secondary text-center">
                        Belum ada foto kegiatan untuk bidang ini.
                    </div>
                </div>
                @endforelse
            </div>

            {{-- PAGINASI KUSTOM (GAYA LINGKARAN) --}}
            <div class="pagination-circles mt-5">
                {!! $galeris->withQueryString()->->links('vendor.pagination.custom-circle') !!}
            </div>

        </div>
    </div>
</div>

{{-- ▼▼▼ [BARU] HTML UNTUK MODAL LIGHTBOX KUSTOM ▼▼▼ --}}
<div id="imageLightbox" class="lightbox-overlay">
    <span class="lightbox-close">&times;</span>
    <div class="lightbox-content-wrapper">
        <img class="lightbox-content" id="lightboxImage" alt="Gambar Galeri yang Diperbesar">
    </div>
    <div class="lightbox-caption" id="lightboxCaption"></div>
</div>
{{-- ▲▲▲ AKHIR HTML LIGHTBOX ▲▲▲ --}}

{{-- ▼▼▼ [BARU] HTML TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>
{{-- ▲▲▲ AKHIR HTML TOMBOL BACK TO TOP ▲▲▲ --}}

@endsection

{{-- JavaScript untuk ISOTOPE & LIGHTBOX BARU --}}
@push('scripts')
{{-- 1. Load library Isotope.js (sudah termasuk Masonry) --}}
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

{{-- 2. Script inisialisasi --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- Inisialisasi Isotope ---
        var grid = document.getElementById('gallery-grid');
        var iso;

        var images = grid.querySelectorAll('img');
        var loadedImages = 0;
        var totalImages = images.length;

        function initIsotope() {
            iso = new Isotope(grid, {
                itemSelector: '.grid-item',
                layoutMode: 'masonry',
                percentPosition: true,
                masonry: {
                    columnWidth: '.grid-item'
                },
                transitionDuration: '0.6s', 
                hiddenStyle: {
                    opacity: 0,
                    transform: 'scale(0.8)'
                },
                visibleStyle: {
                    opacity: 1,
                    transform: 'scale(1)'
                }
            });
        }

        // Tunggu semua gambar dimuat sebelum menjalankan Isotope
        if (totalImages === 0) {
            initIsotope();
        } else {
            images.forEach(function(img) {
                if (img.complete) {
                    imageLoaded();
                } else {
                    img.addEventListener('load', imageLoaded);
                    img.addEventListener('error', imageLoaded);
                }
            });
        }

        function imageLoaded() {
            loadedImages++;
            if (loadedImages === totalImages) {
                initIsotope(); 
            }
        }

        // Logika untuk Tombol Filter
        var filterButtonGroup = document.getElementById('gallery-filter-buttons');
        if (filterButtonGroup) {
            filterButtonGroup.addEventListener('click', function(event) {
                if (!event.target.matches('button')) {
                    return;
                }
                
                var filterValue = event.target.getAttribute('data-filter');
                if (iso) {
                    iso.arrange({ filter: filterValue }); 
                }

                var buttons = filterButtonGroup.querySelectorAll('button');
                buttons.forEach(function(btn) {
                    btn.classList.remove('active');
                });
                event.target.classList.add('active');
            });
        }
        // --- Akhir Inisialisasi Isotope ---


        // ▼▼▼ [BARU] LOGIKA UNTUK LIGHTBOX ▼▼▼
        
        // 1. Ambil elemen-elemen lightbox
        const lightbox = document.getElementById('imageLightbox');
        const lightboxImg = document.getElementById('lightboxImage');
        const lightboxCap = document.getElementById('lightboxCaption');
        const closeBtn = document.querySelector('.lightbox-close');

        // 2. Ambil SEMUA pemicu (card) dari dalam grid
        //    Kita gunakan #gallery-grid sebagai parent untuk event delegation
        const gridContainer = document.getElementById('gallery-grid');
        if (gridContainer) {
            gridContainer.addEventListener('click', function(e) {
                // Cari apakah yang diklik (atau parent-nya) adalah .lightbox-trigger
                const trigger = e.target.closest('.lightbox-trigger');
                
                if (trigger) {
                    e.preventDefault(); // Hentikan aksi default (jika ada)
                    
                    // Ambil data dari elemen card yang diklik
                    const imgSrc = trigger.getAttribute('href');
                    const imgCaption = trigger.getAttribute('data-caption');

                    if (imgSrc && imgSrc !== '#') {
                        lightboxImg.src = imgSrc; // Set sumber gambar
                        lightboxCap.textContent = imgCaption; // Set caption
                        lightbox.style.display = 'block'; // Tampilkan lightbox
                        document.body.style.overflow = 'hidden'; // Mencegah body scroll
                    }
                }
            });
        }

        // 3. Fungsi untuk menutup lightbox
        function closeLightbox() {
            lightbox.style.display = 'none';
            lightboxImg.src = ''; // Kosongkan src agar tidak "flash" gambar lama
            document.body.style.overflow = 'auto'; // Kembalikan body scroll
        }

        // 4. Event listener untuk tombol close (X)
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // Hentikan event klik agar tidak lari ke overlay
                closeLightbox();
            });
        }

        // 5. Event listener untuk klik di luar gambar (di overlay gelap)
        if (lightbox) {
            lightbox.addEventListener('click', function(e) {
                // Hanya tutup jika yang diklik adalah overlay-nya, BUKAN gambar/caption
                if (e.target === lightbox) { 
                    closeLightbox();
                }
            });
        }

        // 6. Event listener untuk tombol 'Escape'
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape" && lightbox.style.display === 'block') {
                closeLightbox();
            }
        });
        
        // ▲▲▲ AKHIR LOGIKA LIGHTBOX ▲▲▲
        
        {{-- ▼▼▼ [BARU] SCRIPT UNTUK TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
        var mybutton = document.getElementById("backToTopBtn");

        if (mybutton) {
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
        }
        {{-- ▲▲▲ AKHIR SCRIPT TOMBOL BACK TO TOP ▲▲▲ --}}

    });
</script>
@endpush