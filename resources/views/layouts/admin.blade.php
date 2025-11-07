<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; /* Biru terang */
            --secondary-color: #6c757d; /* Abu-abu */
            --sidebar-bg: #ffffff; /* DIUBAH: Sidebar Putih Bersih */
            --sidebar-text: #ffffff; /* Teks putih (untuk hover) */
            --sidebar-link-text: #343a40; /* DIUBAH: Teks link default (abu-abu gelap) */
            --sidebar-link-hover-text: #ffffff; /* Teks link saat hover (putih) */
            --sidebar-active-bg: #007bff; /* Biru terang (untuk active/hover) */
            --header-bg: #ffffff; /* Putih untuk header */
            --content-bg: #f8f9fa; /* Warna latar belakang umum */
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--content-bg);
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* === CSS SIDEBAR (Desain Putih Sesuai UI) === */
        .sidebar {
            width: 250px; 
            background-color: var(--sidebar-bg); /* DIUBAH: Latar belakang putih */
            flex-shrink: 0;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Shadow lebih halus */
            z-index: 1000;
        }
        .sidebar .sidebar-header {
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            background-color: var(--primary-color); /* Latar belakang header sidebar */
            border-bottom: 1px solid #dee2e6;
        }
        .sidebar .sidebar-header h4 {
            color: #ffffff; /* Teks header jadi putih */
            margin-bottom: 0;
            font-weight: 600;
        }

        .sidebar .nav-link {
            font-weight: 500;
            background-color: transparent;
            color: var(--sidebar-link-text); /* DIUBAH: Teks abu-abu gelap */
            box-shadow: none;
            
            padding: 12px 25px;
            display: flex;
            align-items: center;
            border-radius: 0 50px 50px 0; /* Bulat di kanan */
            margin-right: 15px; 
            margin-top: 4px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-link-hover-text); /* Teks jadi putih */
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-link-hover-text); /* Teks jadi putih */
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 12px; /* Jarak icon lebih pas */
            font-size: 1.1rem;
        }
        
        .sidebar .logout-link .nav-link {
            background-color: transparent;
            box-shadow: none;
            color: var(--secondary-color);
            margin-right: 0;
        }
        .sidebar .logout-link .nav-link:hover {
            background-color: #e9ecef;
            color: #212529;
            transform: none;
            box-shadow: none;
        }
        /* === AKHIR CSS SIDEBAR === */


        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            width: calc(100% - 250px); /* Lebar sisa */
            transition: all 0.3s ease-in-out;
        }

        /* Navbar Styling (Topbar Transparan) */
        .main-header {
            background-color: transparent; /* DIUBAH: Transparan */
            box-shadow: none;
            padding: 10px 0; /* Padding vertikal */
            margin-bottom: 20px;
        }
        .main-header .page-title {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .navbar-brand-custom {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        .sidebar-toggle {
            font-size: 1.5rem;
            color: var(--primary-color);
            display: none; /* Sembunyi di desktop */
        }

        /* Card Styling (General) */
        .card {
            border: none;
            border-radius: 16px; /* DIUBAH: Lebih bulat */
            box-shadow: 0 6px 15px rgba(0,0,0,0.07); /* Shadow lebih halus */
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 16px 16px 0 0;
            padding: 1.25rem;
            font-weight: 600;
            color: #333;
        }
        .card-body {
            padding: 1.25rem;
        }
        
        /* Button Styling (Konsisten) */
        .btn {
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 25px;
            padding: 8px 25px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 25px;
            padding: 8px 25px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }
        .btn-outline-danger, .btn-outline-secondary {
            border-radius: 25px;
            padding: 5px 15px;
        }
        .btn-light {
            border-radius: 8px;
        }
        .btn-light:hover {
            background-color: #e9ecef;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        /* Other elements */
        h3, h4, h5 {
            color: #343a40;
            font-weight: 600;
        }
        .alert {
            border-radius: 8px;
        }
        
        /* CSS RESPONSIVE (MOBILE) */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed; /* Sidebar jadi melayang */
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(-100%); /* Sembunyi di kiri */
            }
            .sidebar.active {
                transform: translateX(0); /* Munculkan sidebar */
            }
            .main-content {
                width: 100%; /* Konten jadi full width */
            }
            .sidebar-toggle {
                display: block; /* Tampilkan tombol toggle */
            }
            .navbar-text.d-none {
                display: none !important; /* Sembunyikan 'Halo, Admin' di mobile */
            }
        }
        
        /* Overlay untuk background saat sidebar mobile aktif */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none; /* Sembunyi by default */
        }
        .sidebar-overlay.active {
            display: block; /* Tampilkan saat sidebar aktif */
        }
        
    </style>
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h4>DINSOS RIAU</h4>
            </div>
            <ul class="list-unstyled components">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}" href="{{ route('berita.index') }}">
                        <i class="bi bi-newspaper"></i> Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('galeri.*') ? 'active' : '' }}" href="{{ route('galeri.index') }}">
                        <i class="bi bi-images"></i> Galeri Kegiatan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengumuman.*') ? 'active' : '' }}" href="{{ route('pengumuman.index') }}">
                        <i class="bi bi-megaphone-fill"></i> Pengumuman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengaduan.index') ? 'active' : '' }}" href="{{ route('pengaduan.index') }}">
                        <i class="bi bi-chat-left-text-fill"></i> Pengaduan Masuk
                    </a>
                </li>
                
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
                        <i class="bi bi-people-fill"></i> Pengelolaan Admin
                    </a>
                </li>
                <!-- ... Link menu lainnya ... -->

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('slider.index') ? 'active' : '' }}" href="{{ route('slider.index') }}">
        <i class="bi bi-collection-play-fill me-2"></i>
        Manajemen Slider
    </a>
</li>

<!-- ... Link menu lainnya ... -->
            </ul>

            <ul class="list-unstyled components mt-auto pb-4">
                <li class="logout-link">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        
        <!-- Overlay untuk mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div id="content" class="main-content">
            <header class="main-header d-flex justify-content-between align-items-center">
                
                <!-- Tombol Toggle Mobile -->
                <button class="btn sidebar-toggle" id="sidebarToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                
                <!-- Judul Halaman (Sesuai UI Karyawan.jpg) -->
                <span class="page-title d-none d-lg-block">
                    {{-- Ini bisa dibuat dinamis nanti --}}
                    ADMIN DASHBOARD / PAGES
                </span>
                
                <!-- Profile Dropdown -->
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3 d-none d-md-block">
                        Halo, <strong>{{ Auth::user()->name }}</strong>
                    </span>
                    <div class="dropdown">
                        <a class="d-block link-dark text-decoration-none dropdown-toggle" href="#" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        Sign out
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts') 

    <script>
        // SCRIPT BARU UNTUK TOGGLE SIDEBAR MOBILE
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                // Event untuk tombol toggle
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });

                // Event untuk overlay (klik di luar sidebar)
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>

