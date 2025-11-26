<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinas Sosial Provinsi Riau - @yield('title', 'Beranda')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; /* Biru Primer (sesuai UI) */
            --secondary-color: #17a2b8; /* Biru Sekunder/Teal (sesuai UI) */
            --dark-blue: #0d47a1; /* Biru Tua untuk teks */
            --footer-bg: #111111; /* Latar belakang footer (sangat gelap seperti gambar) */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6; /* Latar belakang abu-abu muda */
        }

        /* Navbar Styling */
        .navbar-top {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 0.5rem 1rem;
        }
        .navbar-top .social-icons a {
            color: #6c757d;
            margin-left: 10px;
            transition: color 0.3s;
        }
        .navbar-top .social-icons a:hover {
            color: var(--primary-color);
        }
        .navbar-brand img {
            max-height: 50px;
        }
        .navbar-main {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .navbar-main .nav-item {
            margin: 0 5px;
        }
        .navbar-main .nav-link {
            color: var(--dark-blue);
            font-weight: 500;
            padding: 1rem 0.75rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        .navbar-main .nav-link:hover,
        .navbar-main .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        
        /* CSS UNTUK DROPDOWN HOVER */
        .navbar-main .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: -3px; /* Sesuaikan agar menempel */
            border-radius: 0 0 0.5rem 0.5rem; /* Sudut membulat di bawah */
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }
        /* Menambahkan ikon panah ke dropdown level 1 */
        .navbar-main .nav-item.dropdown .nav-link::after {
            display: inline-block;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
        }
        /* Style untuk item di dalam dropdown */
        .navbar-main .dropdown-menu {
            padding: 0;
            border: none;
            /* UKURAN DROPDOWN LEVEL 1 */
            min-width: 280px; 
            max-width: 320px; 
        }
        .navbar-main .dropdown-item {
            color: var(--dark-blue);
            font-weight: 500;
            padding: 0.5rem 1rem; /* Padding dikurangi */
            transition: all 0.2s ease;
            white-space: normal; 
            font-size: 0.85rem; /* FONT SIZE DIPERKECIL LAGI UNTUK LEVEL 1 */
        }
        .navbar-main .dropdown-item:hover {
            background-color: var(--primary-color);
            color: #ffffff;
        }
        
        /* === STYLE UNTUK DROPDOWN MULTI-LEVEL (LAYANAN PUBLIK & PPID) === */
        .dropdown-submenu {
            position: relative;
        }
        /* Tampilkan Sub-menu Level 2/3 saat hover */
        .dropdown-submenu:hover > .dropdown-menu {
            display: block !important; /* DITERAPKAN: Memastikan submenu tampil saat hover */
        }
        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%; 
            margin-top: -6px;
            margin-left: -1px;
            border-radius: .25rem;
            display: none !important; /* DITERAPKAN: Memastikan submenu tersembunyi secara default */
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
            /* LEBAR SUBMENU */
            min-width: 300px; 
            max-width: 400px; 
        }

        /* PERUBAHAN FONT SIZE KHUSUS UNTUK ITEM DI LEVEL 2 */
        .dropdown-submenu > .dropdown-menu .dropdown-item {
            font-size: 0.8rem; /* FONT SIZE DIPERKECIL LAGI UNTUK LEVEL 2 */
            padding: 0.5rem 1rem; /* Padding dikurangi */
        }

        /* Tambahkan panah kanan untuk submenu */
        .dropdown-submenu > a::after {
            display: block;
            content: "\f285"; /* Ikon Chevron Right dari Bootstrap Icons */
            font-family: 'bootstrap-icons';
            float: right;
            margin-top: 2px;
            color: #6c757d;
        }
        .dropdown-submenu:hover > a::after {
            color: #ffffff; /* Ubah warna panah saat hover */
        }
        /* === AKHIR STYLE DROPDOWN MULTI-LEVEL === */

        /* Style untuk Header di Dropdown */
        .dropdown-header {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--dark-blue);
            padding: 0.5rem 1rem;
            text-transform: uppercase;
        }


        /* Section Title Styling */
        .section-title {
            font-weight: 700;
            color: var(--dark-blue);
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background-color: var(--primary-color);
            margin: 10px auto 0;
            border-radius: 2px;
        }

        /* Card News Styling */
        .card-news {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .card-news:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.12);
        }
        .card-news .card-date {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* === FOOTER STYLING === */
        .footer-dark {
            background-color: var(--footer-bg);
            color: #adb5bd; 
            padding: 4rem 0 2rem 0; 
            border-top: 4px solid var(--primary-color); 
        }
        .footer-dark h5 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative; 
            padding-bottom: 10px; 
        }
        .footer-dark h5::after {
            content: '';
            display: block;
            width: 100%; 
            height: 1px;
            background-color: #6c757d; 
            position: absolute;
            bottom: 0;
            left: 0;
        }
        .footer-dark .profile-list {
            list-style: none;
            padding-left: 0;
        }
        .footer-dark .profile-list li {
            display: flex;
            align-items: flex-start; 
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        .footer-dark .profile-list i {
            font-size: 1.1rem;
            margin-right: 12px;
            margin-top: 3px;
            color: #ffffff; 
        }
        .footer-dark .map-placeholder {
            width: 100%;
            height: 200px; 
            border-radius: 8px;
            object-fit: cover;
            border: 0; 
        }
        .footer-dark .social-icons-footer a {
            display: inline-block;
            width: 44px; 
            height: 44px; 
            line-height: 44px; 
            text-align: center;
            border-radius: 8px; 
            background-color: #333; 
            color: #adb5bd; 
            font-size: 1.25rem; 
            margin: 0 5px; 
            transition: all 0.3s;
        }
        .footer-dark .social-icons-footer a:hover {
            background-color: var(--primary-color);
            color: #ffffff;
        }
        .footer-center-content {
            text-align: center;
            border-top: 1px solid #333; 
            padding-top: 2rem;
            margin-top: 3rem; 
        }
        
        /* UPDATE UKURAN LOGO FOOTER */
        .footer-center-content .logo-img {
            max-height: 50px; /* DIPERKECIL dari 70px menjadi 50px */
            margin-bottom: 1rem;
            width: auto; 
            border-radius: 0; 
        }
        
        .footer-center-content .logo-text {
            color: #fff;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }
        .footer-center-content .footer-tagline {
            font-style: italic;
            color: #adb5bd;
            font-size: 0.9rem;
            max-width: 700px; 
            margin: 0 auto 1.5rem auto;
        }
        .footer-center-content .footer-copyright {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <header>
        <div class="navbar-top">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-none d-md-block">
                    <i class="bi bi-envelope-fill text-primary me-1"></i> <small>info@dinsos.riau.go.id</small>
                    <i class="bi bi-telephone-fill text-primary ms-3 me-1"></i> <small>(0761) 123-456</small>
                </div>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-main sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('public.home') }}">
                    <img src="{{ asset('images/logoweb.png') }}" alt="Logo Dinas Sosial Riau">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto text-center">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.profil') ? 'active' : '' }}" href="{{ route('public.profil') }}">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.berita') ? 'active' : '' }}" href="{{ route('public.berita') }}">Berita</a>
                        </li>
                        
                        {{-- ▼▼▼ MENU PPID DROPDOWN (STRUKTUR BARU SESUAI GAMBAR) ▼▼▼ --}}
<li class="nav-item dropdown">
    <a class="nav-link 
        {{ request()->routeIs('public.ppid.*') || request()->is('ppid/*') ? 'active' : '' }}" 
        href="#" id="ppidDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        PPID
    </a>
    <ul class="dropdown-menu" aria-labelledby="ppidDropdown">
                                
                                {{-- DIUBAH: Daftar Informasi Publik 2025 dengan tautan ke route --}}
                                <li><a class="dropdown-item" href="{{ route('public.ppid.daftar_info_2025') }}">Daftar Informasi Publik 2025</a></li> 
                                
                                <li><a class="dropdown-item" href="{{ route('public.ppid.maklumat') }}">Maklumat Layanan Informasi</a></li>
                                <li><a class="dropdown-item" href="{{ route('public.ppid.pengaduan_wewenang') }}">Pengaduan Penyalahgunaan Wewenang</a></li>
                                <li><a class="dropdown-item" href="{{ route('public.ppid.laporan_ppid') }}">Laporan PPID</a></li>
                                
                                {{-- Layanan Informasi (Level 2/Submenu) --}}
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="#">Layanan Informasi</a>
                                    <ul class="dropdown-menu">
                                        {{-- ISI BARU SESUAI GAMBAR DITERAPKAN --}}
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.formulir_permohonan') }}">Formulir Permohonan Informasi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.alur_sengketa') }}">Alur Penyelesaian Sengketa Informasi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.alur_hak_pengajuan') }}">Alur Hak Dan Tata Cara Pengajuan Keberatan Dan Pengajuan Sengketa Informasi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.alur_tata_cara') }}">Alur Tata Cara Dan Hak Permohonan Informasi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.formulir_keberatan') }}">Formulir Keberatan</a></li>
                                    </ul>
                                </li>
                                
                                {{-- Jenis Informasi (Level 2/Submenu) --}}
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="#">Jenis Informasi</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.info_berkala') }}">Informasi Berkala</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.info_serta_merta') }}">Informasi Serta Merta</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.info_setiap_saat') }}">Informasi Setiap Saat</a></li>
                                    </ul>
                                </li>
                                {{-- Surat Keputusan (Level 2/Submenu) --}}
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="#">Surat Keputusan</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.sk_terbaru') }}">SK Terbaru</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.arsip_sk') }}">Arsip SK</a></li>
                                    </ul>
                                </li>
                                
                                <li><a class="dropdown-item" href="{{ route('public.ppid.info_publik_lain') }}">Informasi Publik Lain</a></li>
                                <li><a class="dropdown-item" href="{{ route('public.ppid.jumlah_permohonan') }}">Jumlah Permohonan Informasi</a></li>
                                
                            </ul>
                        </li>
                        {{-- ▲▲▲ AKHIR MENU PPID DROPDOWN ▲▲▲ --}}
                        
                        {{-- ▼▼▼ MENU LAYANAN PUBLIK (DIKEMBALIKAN KE STRUKTUR SEDERHANA) ▼▼▼ --}}
<li class="nav-item dropdown">
    <a class="nav-link 
        {{ request()->routeIs('public.layanan') || request()->is('layanan-publik') || request()->is('ppid/daftar-informasi/*') ? 'active' : '' }}" 
        href="{{ route('public.layanan') }}" id="layananDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Layanan Publik
    </a>
    <ul class="dropdown-menu" aria-labelledby="layananDropdown">
                                
                                {{-- Item bawaan Layanan Publik --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('public.layanan') }}#content-bantuan">
                                        Pusat Bantuan (FAQ)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('public.layanan') }}#content-dokumen">
                                        Dokumen Publikasi
                                    </a>
                                </li>

                                {{-- TAMBAHAN BARU: LINK KE GOOGLE DRIVE --}}
                                <li>
                                    <a class="dropdown-item" href="https://drive.google.com/drive/folders/1ONJjk_QyIPJeZtNN0yx2X5oATgzEaQx_" target="_blank">
                                        RKA Dinas Social Provinsi
                                    </a>
                                </li>
                                
                                {{-- Submenu Daftar Layanan Teknis (12 item) --}}
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="#">Daftar Layanan Teknis</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.lansia') }}">1. Rehabilitasi Sosial dasar Lanjut Usia Telantar di Dalam Panti</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.anakpanti') }}">2. Rehabilitasi Sosial Dasar Anak Telantar dalam Panti</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.disabilitaspanti') }}">3. Rehabilitasi Sosial Dasar Penyandang Disabilitas Fisik, Sensorik Telantar di dalam Panti</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.disabilitasmental') }}">4. Rehabilitasi Sosial Dasar Penyandang Disabilitas Mental Telantar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.gelandangpengemis') }}">5. Rehabilitasi Sosial Gelandang dan Pengemis Dalam Panti</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.standarpelayananabh') }}">6. Standar Pelayanan Rehabilitasi Sosial Bagi Anak Nakal, Anak Berhadapan Hukum (ABH), Diluar Hiv/Aids dan Napza di Dalam Panti</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.penangananbencana') }}">7. Penanganan Perlindungan Sosial Korban Bencana Alam dan Bencana Sosial</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.izinpengangkatananak') }}">8. Pemberian Izin Pengangkatan Anak</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.tandadaftarlks') }}">9. PENERBITAN TANDA DAFTAR LEMBAGA KESEJAHTERAAN SOSIAL (LKS)</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.pemulanganimigran') }}">10. Pemulangan warga imigran korban tindak kekerasan dari titik debarkasi di daerah Provinsi untuk di pulangkan ke Daerah Kab/ Kota Asal</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.pengaduanmonitoringpkh') }}">11. Pengaduan Masyarakat, Monitoring dan Evaluasi Program Keluarga Harapan (PKH).</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.ppid.pertimbanganteknisugbpub') }}">12. Pertimbangan teknis Undian Gratis Berhadiah (UGB) dan Pengumpulan Uang atau Barang (PUB)</a></li>
                                    </ul>
                                </li>
                                
                            </ul>
                        </li>
                        {{-- ▲▲▲ AKHIR MENU LAYANAN PUBLIK ▲▲▲ --}}

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.galeri') ? 'active' : '' }}" href="{{ route('public.galeri') }}">Galeri</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.pengumuman') ? 'active' : '' }}" href="{{ route('public.pengumuman') }}">Pengumuman</a>
                        </li>
                        
                        {{-- ▼▼▼ MENU FAQ (LINK TERPISAH) - Dikembalikan seperti permintaan ▼▼▼ --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.faq') ? 'active' : '' }}" href="{{ route('public.faq') }}">FAQ</a>
                        </li>
                        {{-- ▲▲▲ AKHIR MENU FAQ (LINK TERPISAH) ▼▼▼ --}}

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.kontak') ? 'active' : '' }}" href="{{ route('public.kontak') }}">Kontak</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer-dark">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5>PROFIL</h5>
                    <ul class="profile-list">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jl. Jend. Sudirman No.239, Simpang Empat, Kec. Pekanbaru Kota, Kota Pekanbaru, Riau 28126</span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <span>info@dinsos.riau.go.id</span>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <span>(0761) 123-456</span>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5>LOKASI</h5>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63834.790313990954!2d101.43211943053905!3d0.48666664207182264!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5aebcc272c77b%3A0x7b45c35bb6d8f1b8!2sDinas%20Sosial%20Provinsi%20Riau!5e0!3m2!1sid!2sid!4v1762851919935!5m2!1sid!2sid" 
                            class="map-placeholder" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5>SOCIAL MEDIA</h5>
                    <div class="social-icons-footer mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-center-content">
                <p class="footer-tagline">
                    "Mewujudkan manajemen penyelenggaraan pemerintahan yang baik (good governance), efektif dan efisien, professional, transparan dan akuntabel."
                </p>
                
                {{-- UPDATE DISINI: Menambahkan class="logo-img" agar CSS max-height: 50px bekerja --}}
                <img src="{{ asset('images/logo_pemprov.png') }}" alt="Logo Pemprov Riau" class="logo-img">

                <div class="logo-text">DINAS SOSIAL PROVINSI RIAU</div>
                
                <p class="footer-copyright">
                    Copyright © {{ date('Y') }} Dinas Sosial Provinsi Riau. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Tambahkan kembali script hover multi-level untuk PPID --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script ini menangani hover untuk dropdown multi-level (dropdown-submenu)
            const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');

            dropdownSubmenus.forEach(function(el) {
                // Tampilkan submenu saat mouse enter
                el.addEventListener('mouseenter', function() {
                    const submenu = this.querySelector('.dropdown-menu');
                    if (submenu) {
                        submenu.style.display = 'block';
                    }
                });

                // Sembunyikan submenu saat mouse leave
                el.addEventListener('mouseleave', function() {
                    const submenu = this.querySelector('.dropdown-menu');
                    if (submenu) {
                        // Gunakan timeout agar ada sedikit jeda sebelum menghilang
                        setTimeout(() => {
                             // Hanya sembunyikan jika mouse tidak berada di atas submenu
                            if (!submenu.matches(':hover')) {
                                submenu.style.display = 'none';
                            }
                        }, 100); 
                    }
                });

                // Mencegah navigasi link utama sub-dropdown saat diklik
                const anchor = el.querySelector('a');
                if (anchor) {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                    });
                }
            });
        });
    </script>

    @stack('scripts')
    
</body>
</html>