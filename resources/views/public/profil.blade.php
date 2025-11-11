@extends('layouts.public')

@push('styles')
    {{-- Tambahkan AOS CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <style>
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

        .news-slider .carousel-caption h1 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .news-slider .carousel-caption p {
            font-size: 1.1rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        [data-aos] {
            opacity: 0;
            transition-property: opacity, transform;
        }
        
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
    <div id="profilHeader" class="carousel slide news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000"
         style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">

        <div class="carousel-indicators">
            @foreach($sliders as $slider)
                <button type="button" data-bs-target="#profilHeader" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @forelse($sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $slider->gambar) }}" class="d-block w-100" alt="{{ $slider->judul }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="text-white">{{ $slider->judul }}</h1>
                        <p class="text-white-50">{{ $slider->keterangan }}</p>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x500/333/fff?text=Tentang+Kami" class="d-block w-100" alt="Profil Header">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="text-white">PROFIL</h1>
                        <p class="text-white-50">Visi, Misi, dan Struktur Organisasi Dinas Sosial Provinsi Riau.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#profilHeader" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#profilHeader" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
</div>


<div class="container my-5" data-aos="fade-up" data-aos-duration="1000">
    <div class="row align-items-center">
        <div class="col-lg-7" data-aos="fade-right" data-aos-delay="100">
            <small class="text-primary fw-bold text-uppercase">Tentang Kami</small>
            <h2 class="section-title text-start ps-0 mb-4">Dinas Sosial Provinsi Riau</h2>
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
        <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
            <img src="https://awsimages.detik.net.id/community/media/visual/2020/12/29/tugu-zapin-ikon-kota-pekanbaru-1_169.jpeg?w=1200" class="img-fluid rounded-3 shadow-sm" alt="Gedung Dinas Sosial Riau">
        </div>
    </div>
</div>


<div class="py-5 bg-white" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
        <h2 class="section-title mb-5 text-center">Visi dan Misi</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-5" data-aos="fade-right" data-aos-delay="100">
                <div class="card h-100 shadow-sm border-0" style="border-top: 4px solid var(--primary-color);">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-eye-fill display-4 text-primary mb-3"></i>
                        <h4 class="fw-bold">VISI</h4>
                        <p class="text-muted">"Terwujudnya Kesejahteraan Sosial Masyarakat di Provinsi Riau yang Berkeadilan dan Merata."</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
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


<div class="container my-5 text-center" data-aos="zoom-in" data-aos-duration="1000">
    <h2 class="section-title mb-4">Struktur Organisasi</h2>
    <img src="https://diskominfotik.riau.go.id/wp-content/uploads/2025/10/Struktur-Organisasi-Diskominfotik-01-Oktober-2025.png" class="img-fluid rounded-3 shadow-sm" alt="Struktur Organisasi Dinas Sosial Riau">
    <p class="text-muted mt-3">Bagan Struktur Organisasi Dinas Sosial Provinsi Riau Tahun 2025</p>
</div>

{{-- ▼▼▼ [BARU] HTML TOMBOL BACK TO TOP (DITAMBAHKAN) ▼▼▼ --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>
{{-- ▲▲▲ AKHIR HTML TOMBOL BACK TO TOP ▲▲▲ --}}

@endsection


@push('scripts')
    {{-- Tambahkan JS AOS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        window.addEventListener('load', function () {
            AOS.init({
                duration: 800,
                once: false,
                offset: 100,
                easing: 'ease-out-cubic',
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