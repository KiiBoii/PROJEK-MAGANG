@extends('layouts.public')

{{-- 1. CSS KUSTOM --}}
@push('styles')
    {{-- [BARU] Tambahkan CSS AOS (Animate On Scroll) --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

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

        /* [OPSIONAL] Sembunyikan elemen AOS sebelum terlihat untuk mencegah 'flash' */
        [data-aos] {
            opacity: 0;
            transition-property: opacity, transform;
        }

        /* ▼▼▼ STYLE TOMBOL BACK TO TOP ▼▼▼ */
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
        
        /* ▼▼▼ STYLE MODAL SURVEY ▼▼▼ */
        .modal-dialog-centered-custom {
            min-height: calc(100vh - 60px);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content-custom {
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .survey-image {
            width: 150px;
            height: 150px;
            margin-bottom: 1rem;
            /* Placeholder: ganti dengan gambar asli jika ada */
        }
        /* ▲▲▲ AKHIR STYLE MODAL SURVEY ▲▲▲ */
    </style>
@endpush


@section('content')

{{-- ▼▼▼ HTML MODAL SURVEY KEPUASAN MASYARAKAT ▼▼▼ --}}
{{-- Catatan: 'data-bs-backdrop="static"' dan 'data-bs-keyboard="false"' mencegah modal ditutup dengan klik di luar atau tombol ESC, memaksa pengguna berinteraksi dengan tombol tutup atau tombol 'Selengkapnya'. --}}
<div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered-custom">
        <div class="modal-content modal-content-custom p-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                
                {{-- Ilustrasi Placeholder --}}
                <img src="{{ asset('images/Miyabi_mcd.jpg') }}" alt="Ilustrasi Survey" class="survey-image img-fluid">

                <h3 class="fw-bold mb-3">Survey Kepuasan Masyarakat</h3>
                <p class="text-muted">Bantu kami dengan mengisi survey pelayanan Dinas Sosial Provinsi Riau di link di bawah sehingga kami dapat semakin meningkatkan pelayanan kami.</p>
                
                {{-- Tombol akan dihandle oleh JavaScript --}}
                <button type="button" id="btnSelengkapnya" class="btn btn-primary btn-lg rounded-pill px-5 mt-3">
                    Selengkapnya
                </button>
            </div>
        </div>
    </div>
</div>
{{-- ▲▲▲ AKHIR HTML MODAL SURVEY KEPUASAN MASYARAKAT ▲▲▲ --}}

{{-- [ANIMASI] Animasi fade-in saat halaman dimuat --}}
<div class="container my-5" data-aos="fade-in" data-aos-duration="1000">
    
    <div id="heroSlider" class="carousel slide news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" 
          style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            @foreach($sliders as $slider)
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
        
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

</div> <div class="container my-5">
    <div class="row align-items-center">
        {{-- [ANIMASI] Kolom kiri slide dari kanan --}}
        <div class="col-lg-8" data-aos="fade-right" data-aos-offset="100">
            <small class="text-primary fw-bold text-uppercase">Profil</small>
            <h2 class="fw-bold mb-3" style="color: #0d47a1;">KEPALA DINAS</h2>
            
            <div class="d-flex mb-3">
                <img src="{{ asset('images/Miyabi_mcd.jpg') }}" class="rounded-circle" alt="Foto Kepala Dinas" style="width: 150px; height: 150px; object-fit: cover;">
                <div class="ms-4">
                    <h4 class="fw-bold mb-1">Nama Kepala Dinas, S.H., M.Si.</h4>
                    <p class="text-muted mb-2">Kepala Dinas Sosial Provinsi Riau</p>
                    <p class="text-muted small">
                        Tempat/Tgl Lahir: Pekanbaru, 01 Januari 1970<br>
                        Masa Jabatan: 2023 - Sekarang
                    </p>
                    <div class="d-flex">
                        <a href="{{ route('public.profil.kadis') }}" class="btn btn-primary btn-sm rounded-pill me-2">Baca Lebih Lanjut</a>
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
        {{-- [ANIMASI] Kolom kanan slide dari kiri --}}
        <div class="col-lg-4" data-aos="fade-left" data-aos-offset="100" data-aos-delay="200">
            <h5 class="fw-bold mb-3">Video Kegiatan Kepala Dinas</h5>
            <div class="ratio ratio-16x9 rounded-3 shadow-sm">
<iframe src="https://www.youtube.com/embed/pFnJ97g-wSs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>            </div>
        </div>
    </div>
</div>

{{-- [ANIMASI] Seluruh bagian berita fade-up --}}
<div class="py-5" style="background-color: #ffffff;" data-aos="fade-up" data-aos-offset="100">
    <div class="container">
        <h2 class="section-title">Berita Terbaru</h2>
        
        @if($beritaUtama)
        {{-- [ANIMASI] Berita utama fade-up (sedikit delay) --}}
        <div class="card mb-5 shadow-lg border-0" data-aos="fade-up" data-aos-delay="100">
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
        {{-- [ANIMASI] Wrapper berita lainnya fade-up --}}
        <div class="p-4 rounded-3" style="background-color: var(--primary-color);" data-aos="fade-up" data-aos-delay="200">
            <h4 class="text-white fw-bold mb-3 text-center">Berita Lainnya</h4>
            <div class="row">
                
                @forelse($beritaLainnya->take(5) as $berita)
                {{-- [ANIMASI] Setiap kartu berita diberi animasi fade-up dengan delay bertingkat --}}
                <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 + 300 }}">
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

{{-- ▼▼▼ HTML TOMBOL BACK TO TOP ▼▼▼ --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>
{{-- ▲▲▲ AKHIR HTML TOMBOL BACK TO TOP ▲▲▲ --}}

@endsection

{{-- [BARU] Tambahkan JS di akhir body (stack 'scripts' harus ada di layout.public) --}}
@push('scripts')
    {{-- [BARU] Tambahkan JS AOS (Animate On Scroll) --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    {{-- [BARU] Inisialisasi AOS --}}
    <script>
        // Tunggu hingga seluruh halaman (termasuk gambar) dimuat
        window.addEventListener('load', function() {
            AOS.init({
                duration: 800,   // Durasi animasi dalam milidetik
                once: false,     // [DIUBAH] Animasi terjadi setiap kali di-scroll (atas/bawah)
                offset: 100,     // Jarak (px) dari bagian bawah layar sebelum animasi dimulai
                easing: 'ease-out-cubic', // Jenis easing
            });
        });
    </script>

    {{-- ▼▼▼ SCRIPT UNTUK MODAL SURVEY DAN TOMBOL BACK TO TOP (Dihapus SessionStorage) ▼▼▼ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- LOGIKA POP-UP SURVEY ---
            const surveyModal = new bootstrap.Modal(document.getElementById('surveyModal'));
            const btnSelengkapnya = document.getElementById('btnSelengkapnya');
            
            // GANTI DENGAN LINK GOOGLE FORM ASLI ANDA
            const surveyLink = "https://docs.google.com/forms/d/e/1FAIpQLScJp881sQ5eI8Yd1O2vT1Q9y0x-g5vO0x1hN0x/viewform"; 

            // TAMPILKAN MODAL SETIAP KALI HALAMAN DIMUAT (Refresh atau kembali ke Home)
            setTimeout(() => {
                surveyModal.show();
            }, 500); 

            // Aksi saat tombol "Selengkapnya" diklik
            btnSelengkapnya.addEventListener('click', function() {
                // Tutup modal
                surveyModal.hide();
                // Arahkan ke link Google Form
                window.open(surveyLink, '_blank');
            });


            // --- LOGIKA BACK TO TOP BUTTON ---
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
    {{-- ▲▲▲ AKHIR SCRIPT MODAL SURVEY DAN TOMBOL BACK TO TOP ▲▲▲ --}}
@endpush