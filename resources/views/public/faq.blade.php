@extends('layouts.public')

{{-- Menetapkan Judul Halaman --}}
@section('title', 'Pusat Bantuan (FAQ)')

{{-- 1. CSS KUSTOM DITAMBAHKAN (SLIDER, KARTU, AOS, BACK-TO-TOP) --}}
@push('styles')
    {{-- [BARU] Tambahkan CSS AOS (Animate On Scroll) --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
    /* === [BARU] CSS SLIDER (Diambil dari contoh) === */
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
    /* === AKHIR CSS SLIDER === */


    /* === CSS UNTUK KARTU KONTAK (Sudah ada) === */
    .contact-card-link {
        display: block;
        background-color: #f8f9fa; /* Warna abu-abu sangat muda */
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.25rem;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .contact-card-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .contact-card-link i {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: var(--primary-color);
        vertical-align: middle;
    }
    /* === AKHIR CSS KARTU KONTAK === */

    /* Styling accordion (Sudah ada) */
    .accordion-item {
        border: none;
        border-radius: 8px !important;
        margin-bottom: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        overflow: hidden; /* Penting untuk border-radius */
    }
    .accordion-button {
        font-weight: 600;
        color: var(--dark-blue);
    }
    .accordion-button:not(.collapsed) {
        background-color: var(--primary-color);
        color: white;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
    }

    /* ▼▼▼ [BARU] STYLE TOMBOL BACK TO TOP ▼▼▼ */
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
    /* ▲▲▲ AKHIR STYLE TOMBOL BACK TO TOP ▲▲▲ */
</style>
@endpush

@section('content')

<!-- === [BARU] BAGIAN SLIDER === -->
<div class="container my-5" data-aos="fade-in" data-aos-duration="1000">
    
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
                        <p>{{ Str::limit(strip_tags($slider->keterangan), 100) }}</p>
                    </div>
                </div>
            @empty
                {{-- Fallback jika tidak ada slider --}}
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x450/007bff/white?text=Pusat+Bantuan" class="d-block w-100" alt="Slider 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Pusat Bantuan (FAQ)</h5>
                        <p>Temukan jawaban atas pertanyaan Anda di sini.</p>
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
<!-- === AKHIR BAGIAN SLIDER === -->


<div class="container my-5">
    
    {{-- Judul Halaman (Sudah ada) --}}
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12">
            <h2 class="section-title">
                Pusat Bantuan (FAQ)
            </h2>
        </div>
    </div>

    <div class="row">
        
        {{-- KOLOM UTAMA: DAFTAR FAQ (ACCORDION) --}}
        <div class="col-lg-8" data-aos="fade-right" data-aos-delay="100">
            
            <p class="text-muted mb-4 fs-5" style="max-width: 90%;">
                Temukan jawaban cepat untuk pertanyaan yang sering diajukan seputar layanan kami.
            </p>
            
            <div class="accordion" id="faqAccordion">
                
                {{-- 
                    CATATAN: 
                    Ini adalah data statis berdasarkan contoh Anda.
                    Nantinya, Anda bisa mengganti ini dengan loop Blade, contoh:
                    @foreach($faqs as $faq)
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                           ...
                           <h5>{{ $faq->pertanyaan }}</h5>
                           ...
                           <div>{{ $faq->jawaban }}</div>
                           ...
                        </div>
                    @endforeach
                --}}
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Bagaimana cara mendaftar Bantuan Sosial?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Pendaftaran Bantuan Sosial dilakukan melalui Data Terpadu Kesejahteraan Sosial (DTKS). Silakan mendaftar ke kantor desa/lurah setempat dengan membawa dokumen kependudukan seperti KTP dan Kartu Keluarga (KK) untuk diusulkan ke dalam sistem DTKS.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="250">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Apa saja syarat untuk mendapatkan rehabilitasi sosial?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Syarat utama adalah termasuk dalam kategori Pemerlu Pelayanan Kesejahteraan Sosial (PPKS), seperti anak terlantar, lansia, penyandang disabilitas, atau korban bencana. Anda dapat mengajukan permohonan ke Dinas Sosial setempat untuk dievaluasi lebih lanjut.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Bagaimana cara melaporkan pengaduan?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Anda dapat menggunakan tautan "Kirim Masukan" di samping untuk diarahkan ke halaman kontak kami. Selain itu, Anda juga bisa mengirimkan email langsung ke <a href="mailto:info@dinsos.riau.go.id">info@dinsos.riau.go.id</a> atau menghubungi kami melalui media sosial yang tertera di bagian bawah situs.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="350">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Di mana saya bisa mengunduh dokumen publikasi?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Semua dokumen publikasi seperti laporan, peraturan, dan pengumuman tersedia di menu <a href="{{ route('public.layanan') }}#content-dokumen">Layanan Publik</a> pada bagian "Dokumen Publikasi".
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- KOLOM SIDEBAR: HUBUNGI KAMI --}}
        <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Butuh Bantuan Lain?</h4>
                    <p class="text-muted mb-4">
                        Jika Anda tidak menemukan jawaban atas pertanyaan Anda, jangan ragu untuk menghubungi kami.
                    </p>
                    
                    <a href="{{ route('public.kontak') }}" class="contact-card-link mb-3">
                        <i class="bi bi-pencil-square"></i>
                        <span>Kirim Masukan</span>
                    </a>
                    
                    <a href="mailto:info@dinsos.riau.go.id" class="contact-card-link">
                        <i class="bi bi-envelope-fill"></i>
                        <span>Email Kontak</span>
                    </a>

                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- ▼▼▼ [BARU] HTML TOMBOL BACK TO TOP ▼▼▼ --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>
{{-- ▲▲▲ AKHIR HTML TOMBOL BACK TO TOP ▲▲▲ --}}

@endsection

{{-- [BARU] Tambahkan JS di akhir body --}}
@push('scripts')
    {{-- [BARU] Tambahkan JS AOS (Animate On Scroll) --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    {{-- [BARU] Inisialisasi AOS --}}
    <script>
        // Tunggu hingga seluruh halaman (termasuk gambar) dimuat
        window.addEventListener('load', function() {
            AOS.init({
                duration: 800,   // Durasi animasi dalam milidetik
                once: true,      // [BERBEDA] Animasi hanya terjadi SEKALI
                offset: 100,     // Jarak (px) dari bagian bawah layar sebelum animasi dimulai
                easing: 'ease-out-cubic', // Jenis easing
            });
        });
    </script>

    {{-- ▼▼▼ [BARU] SCRIPT UNTUK TOMBOL BACK TO TOP ▼▼▼ --}}
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

