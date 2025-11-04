@extends('layouts.public')

{{-- 1. CSS KUSTOM DITAMBAHKAN (SAMA SEPERTI HALAMAN BERITA) --}}
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
    
    <div id="profilHeader" class="news-slider" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        {{-- Div ini meniru struktur 'carousel-item' --}}
        <div class="carousel-item active"> 
            
            <img src="https://placehold.co/1920x500/333/fff?text=Tentang+Kami" class="d-block w-100" alt="Profil Header">
            
            {{-- Div ini meniru struktur 'carousel-caption' --}}
            <div class="carousel-caption d-none d-md-block">
                <h1 class="text-white">PROFIL</h1> {{-- Menggunakan H1 untuk SEO --}}
                <p class="text-white-50">Visi, Misi, dan Struktur Organisasi Dinas Sosial Provinsi Riau.</p>
            </div>
        </div>
    </div>

</div> <div class="container my-5">
    <div class="row align-items-center">
        <div class="col-lg-7">
            <small class="text-primary fw-bold text-uppercase">Tentang Kami</small>
            <h2 class="section-title text-start ps-0" style="margin-left: 0; margin-bottom: 1.5rem;">Dinas Sosial Provinsi Riau</h2>
            <p class="text-muted">
                Dinas Sosial Provinsi Riau merupakan unsur pelaksana urusan pemerintahan di bidang sosial yang menjadi kewenangan Daerah. 
                Dinas Sosial mempunyai tugas membantu Gubernur melaksanakan urusan pemerintahan bidang sosial yang menjadi kewenangan Daerah dan 
                Tugas Pembantuan yang diberikan kepada Daerah Provinsi.
            </p>
            <p class="text-muted">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse potenti. Nulla facilisi. Praesent sed felis metus. 
                Vestibulum ac mauris pretium, consequat eros vitae, volutpat neque. Mauris pretium, nisl sed facilisis eleifend.
            </p>
            <ul class="list-unstyled text-muted">
                <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Melaksanakan program rehabilitasi sosial.</li>
                <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Melaksanakan program perlindungan dan jaminan sosial.</li>
                <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Melaksanakan program pemberdayaan sosial dan penanganan fakir miskin.</li>
            </ul>
        </div>
        <div class="col-lg-5">
            <img src="https://placehold.co/600x400/e0e0e0/999?text=Foto+Gedung" class="img-fluid rounded-3 shadow-sm" alt="Gedung Dinas Sosial Riau">
        </div>
    </div>
</div>

<div class="py-5" style="background-color: #ffffff;">
    <div class="container">
        <h2 class="section-title">Visi dan Misi</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <div class="card h-100 shadow-sm border-0" style="border-top: 4px solid var(--primary-color);">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-eye-fill display-4 text-primary mb-3"></i>
                        <h4 class="fw-bold">VISI</h4>
                        <p class="text-muted">"Terwujudnya Kesejahteraan Sosial Masyarakat di Provinsi Riau yang Berkeadilan dan Merata."</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card h-100 shadow-sm border-0" style="border-top: 4px solid var(--primary-color);">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-bullseye display-4 text-primary mb-3"></i>
                        <h4 class="fw-bold">MISI</h4>
                        <ul class="list-unstyled text-muted text-start">
                            <li>1. Meningkatkan kualitas pelayanan sosial.</li>
                            <li>2. Mengembangkan program pemberdayaan sosial.</li>
                            <li>3. Memperkuat jaring pengaman sosial.</li>
                            <li>4. Meningkatkan partisipasi masyarakat dalam pembangunan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h2 class="section-title">Struktur Organisasi</h2>
    <div class="text-center">
        <img src="https://placehold.co/1200x800/e0e0e0/999?text=Struktur+Organisasi+Placeholder" class="img-fluid rounded-3 shadow-sm" alt="Struktur Organisasi Dinas Sosial Riau">
        <p class="text-muted mt-2">Bagan Struktur Organisasi Dinas Sosial Provinsi Riau Tahun 2025</p>
    </div>
</div>

@endsection