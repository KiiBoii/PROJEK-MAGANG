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
        /* Menghilangkan panah dropdown default */
        .navbar-main .nav-item.dropdown > .nav-link::after {
            display: none;
        }
        /* Style untuk item di dalam dropdown */
        .navbar-main .dropdown-menu {
            padding: 0;
            border: none;
        }
        .navbar-main .dropdown-item {
            color: var(--dark-blue);
            font-weight: 500;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        .navbar-main .dropdown-item:hover {
            background-color: var(--primary-color);
            color: #ffffff;
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
        .footer-center-content .logo-img {
            max-height: 70px; 
            margin-bottom: 1rem;
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
                    <img src="{{ asset('images/logo_warna.png') }}" alt="Logo Dinas Sosial Riau">
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
                        
                        {{-- ▼▼▼ INI BAGIAN YANG DIUBAH ▼▼▼ --}}
<li class="nav-item dropdown">
    <a class="nav-link {{ request()->routeIs('public.layanan') ? 'active' : '' }}" href="{{ route('public.layanan') }}#content-bantuan">
        Layanan Publik
    </a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ route('public.layanan') }}#content-dokumen">
                Dokumen Publikasi
            </a>
        </li>
    </ul>
</li>
                        {{-- ▲▲▲ AKHIR BAGIAN YANG DIUBAH ▲▲▲ --}}

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.galeri') ? 'active' : '' }}" href="{{ route('public.galeri') }}">Galeri</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.pengumuman') ? 'active' : '' }}" href="{{ route('public.pengumuman') }}">Pengumuman</a>
                        </li>
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
                            <span>Jl. Jend. Sudirman No. 123, Pekanbaru, Riau, 28282.</span>
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
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.655254298878!2d101.44929817447432!3d0.5180173636858576!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ad835f1604d3%3A0x700b4483c5414e68!2sDINAS%20KOMUNIKASI%20INFORMATIKA%20DAN%20STATISTIK%20PROVINSI%20RIAU!5e0!3m2!1sid!2sid!4v1762230007609!5m2!1sid!2sid" 
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
                
                {{-- === BARIS YANG DIPERBARUI === --}}
                <img src="{{ asset('images/logo.png') }}" alt="Logo Dinsos Riau" class="logo-img rounded-circle">
                {{-- === AKHIR PERUBAHAN === --}}

                <div class="logo-text">DINAS SOSIAL PROVINSI RIAU</div>
                
                <p class="footer-copyright">
                    Copyright © {{ date('Y') }} Dinas Sosial Provinsi Riau. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>