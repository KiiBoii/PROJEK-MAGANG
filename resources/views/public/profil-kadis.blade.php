@extends('layouts.public')

@push('styles')
    {{-- Tambahkan AOS CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    <style>
        /* [BARU] Style khusus untuk halaman profil kadis */
        .profile-pic {
            width: 100%;
            max-width: 350px;
            height: auto;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* [DIHAPUS] CSS untuk .profile-details dihapus karena sudah tidak dipakai */
        
        .profile-section-title {
            font-weight: 700;
            color: #0d47a1; /* Biru primer */
            border-bottom: 2px solid #0d47a1;
            padding-bottom: 8px;
            margin-bottom: 20px;
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

{{-- Header sederhana untuk judul halaman --}}
<div class="py-5 bg-light" data-aos="fade-in">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <small class="text-primary fw-bold text-uppercase">Profil Pejabat</small>
                <h1 class="fw-bold display-5" style="color: #0d47a1;">Profil Kepala Dinas</h1>
                <p class="lead text-muted">Mengenal lebih dekat pimpinan Dinas Sosial Provinsi Riau.</p>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        
        {{-- [ANIMASI] Slide dari kanan --}}
        <div class="col-lg-4" data-aos="fade-right" data-aos-duration="800">
            {{-- Kita buat sticky agar foto tetap terlihat saat di-scroll --}}
            <div class="card border-0 shadow-sm p-3 position-sticky" style="top: 100px;">
                
                {{-- [SARAN] Ganti placeholder ini dengan foto Kadis yang sesungguhnya --}}
                <img src="https://placehold.co/400x400/e0e0e0/333?text=FOTO+KADIS" class="profile-pic img-fluid mx-auto" alt="Foto Kepala Dinas">
                
                <div class="card-body text-center">
                    <h4 class="fw-bold mt-3 mb-1">Nama Kepala Dinas, S.H., M.Si.</h4>
                    <p class="text-muted mb-3">Kepala Dinas Sosial Provinsi Riau</p>
                    
                    {{-- [SARAN] Ganti link # dengan link media sosial yang sesungguhnya --}}
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill me-2" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill mx-2" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill" title="Facebook"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- [ANIMASI] Fade up --}}
        <div class="col-lg-8" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            
            <section id="sambutan" class="mb-5">
                <h3 class="profile-section-title">Kata Sambutan</h3>
                <p class="text-muted fst-italic">
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse potenti. Nulla facilisi. Praesent sed felis metus. Vestibulum ac mauris pretium, consequat eros vitae, volutpat neque. Mauris pretium, nisl sed facilisis eleifend, eros felis mollis ante, ac tincidunt lacus felis non
                    erat. Suspendisse potenti."
                </p>
                <p class="text-muted">
                    Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum. 
                    Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus.
                </p>
            </section>

            {{-- [DIUBAH] Mengganti format <dl> menjadi <ul> agar simetris --}}
            <section id="biodata" class="mb-5">
                <h3 class="profile-section-title">Biodata Lengkap</h3>
                
                {{-- [SARAN] Idealnya, ganti data statis ini dengan data dinamis dari database --}}
                <ul class="list-group list-group-flush">
                    <li class="list-group-item ps-0">
                        <i class="bi bi-person-fill text-primary me-2"></i>
                        <strong>Nama Lengkap</strong> - Nama Kepala Dinas, S.H., M.Si.
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-person-badge-fill text-primary me-2"></i>
                        <strong>NIP</strong> - 19700101 199503 1 001 (Contoh)
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-award-fill text-primary me-2"></i>
                        <strong>Pangkat/Gol. Ruang</strong> - Pembina Utama Muda (IV/c) (Contoh)
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-calendar-event-fill text-primary me-2"></i>
                        <strong>Tempat, Tgl Lahir</strong> - Pekanbaru, 01 Januari 1970
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-moon-stars-fill text-primary me-2"></i> {{-- Ikon netral --}}
                        <strong>Agama</strong> - Islam
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        <strong>Masa Jabatan</strong> - 2023 - Sekarang
                    </li>
                </ul>
            </section>

            <section id="pendidikan" class="mb-5">
                <h3 class="profile-section-title">Riwayat Pendidikan</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item ps-0">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                        <strong>S2 Magister Sains (M.Si.)</strong> - Universitas Gadjah Mada (Contoh)
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                        <strong>S1 Sarjana Hukum (S.H.)</strong> - Universitas Riau (Contoh)
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                        <strong>SMA Negeri 1 Pekanbaru</strong> (Contoh)
                    </li>
                </ul>
            </section>

            <section id="jabatan">
                <h3 class="profile-section-title">Riwayat Jabatan</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item ps-0">
                        <i class="bi bi-briefcase-fill text-primary me-2"></i>
                        <strong>Kepala Dinas Sosial Provinsi Riau</strong> (2023 - Sekarang)
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-briefcase-fill text-primary me-2"></i>
                        <strong>Sekretaris Dinas Sosial Provinsi Riau</strong> (2020 - 2023) (Contoh)
                    </li>
                    <li class="list-group-item ps-0">
                        <i class="bi bi-briefcase-fill text-primary me-2"></i>
                        <strong>Kepala Bidang ...</strong> (2017 - 2020) (Contoh)
                    </li>
                </ul>
            </section>
        </div>
        
    </div>
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
        // Inisialisasi AOS
        window.addEventListener('load', function () {
            AOS.init({
                duration: 800,
                once: false, // Animasi tetap berjalan saat scroll
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