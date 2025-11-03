<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; /* Biru terang */
            --secondary-color: #6c757d; /* Abu-abu */
            --accent-color: #17a2b8; /* Teal */
            --sidebar-bg: #f4f7f6; /* Latar belakang sidebar (Abu-abu sangat muda) */
            --sidebar-text: #ffffff; /* Teks putih (untuk hover) */
            --sidebar-link-text: #0056b3; /* Teks biru (default) */
            --sidebar-active-bg: #007bff; /* Biru terang (untuk active/hover) */
            --header-bg: #ffffff; /* Putih untuk header */
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa; /* Warna latar belakang umum */
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* === PERUBAHAN CSS SIDEBAR (TANPA BOX PUTIH) === */
        .sidebar {
            width: 250px; 
            background-color: var(--sidebar-bg); /* Latar belakang sidebar terang (#f4f7f6) */
            padding-top: 20px;
            flex-shrink: 0;
            transition: all 0.3s;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar .sidebar-header {
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .sidebar-header h4 {
            color: var(--sidebar-link-text); /* Teks header jadi biru */
            margin-bottom: 0;
            font-weight: 600;
        }

        /* Tampilan Default Link (Permintaan Anda: TANPA Box Putih) */
        .sidebar .nav-link {
            font-weight: 500;
            background-color: transparent; /* DIUBAH: Tidak ada background putih */
            color: var(--sidebar-link-text); /* TETAP: Teks biru */
            box-shadow: none; /* DIUBAH: Tidak ada drop shadow */
            
            padding: 12px 25px; /* Padding seragam, sedikit indent kiri */
            display: flex;
            align-items: center;
            /* Kita gunakan radius di kanan, agar saat hover terlihat "penuh" */
            border-radius: 0 25px 25px 0; 
            margin-right: 15px; /* Margin di kanan agar radius terlihat */
            margin-top: 6px;
            margin-bottom: 6px;
            transition: all 0.3s ease;
        }

        /* Tampilan Hover & Active (Permintaan Anda) */
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active-bg); /* TETAP: Background biru */
            color: var(--sidebar-text); /* TETAP: Teks putih */
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3); /* Shadow biru saat hover */
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        /* Tombol Logout (dibuat agar tidak terpengaruh style link) */
        .sidebar .logout-link .nav-link {
            background-color: transparent;
            box-shadow: none;
            color: var(--secondary-color);
            margin-right: 0; /* Tidak perlu margin kanan */
        }
        .sidebar .logout-link .nav-link:hover {
            background-color: #e9ecef;
            color: #212529;
            transform: none;
            box-shadow: none;
        }
        /* === AKHIR PERUBAHAN CSS SIDEBAR === */


        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        /* Navbar Styling (Topbar) */
        .navbar-admin {
            background-color: var(--header-bg);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .navbar-brand-custom {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        /* Card Styling (General) */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #eee;
            border-radius: 12px 12px 0 0;
            padding: 15px 20px;
            font-weight: 600;
            color: #333;
        }
        .card-body {
            padding: 20px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 25px;
            padding: 8px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 25px;
            padding: 8px 25px;
            font-weight: 500;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }
        .btn-outline-danger, .btn-outline-secondary {
            border-radius: 25px;
            padding: 5px 15px;
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
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h4>Admin Dinsos Riau</h4> {{-- Ganti dengan nama aplikasi Anda --}}
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
                    <a class="nav-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}" href="{{ route('pengaduan.index') }}">
                        <i class="bi bi-chat-left-text-fill"></i> Pengaduan Masyarakat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
                        <i class="bi bi-people-fill"></i> Pengelolaan Admin
                    </a>
                </li>
                {{-- Contoh item menu tambahan --}}
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
                    </a>
                </li> --}}
            </ul>

            <ul class="list-unstyled components mt-auto pb-4"> {{-- Untuk Logout di bawah --}}
                <li class="logout-link"> {{-- Class ditambahkan untuk styling --}}
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        {{-- Button diubah agar sesuai dengan styling logout --}}
                        <button type="submit" class="nav-link btn btn-link text-start w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <div id="content" class="main-content">
            <nav class="navbar navbar-expand-lg navbar-light navbar-admin">
                <div class="container-fluid">
                    <a class="navbar-brand navbar-brand-custom" href="{{ route('dashboard') }}">DINSOS RIAU</a> {{-- Nama Aplikasi Anda --}}
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
                </div>
            </nav>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts') 

    <script>
        // Contoh sederhana untuk toggle sidebar (jika Anda ingin ada tombol toggle)
        // document.getElementById('sidebarToggle').addEventListener('click', function() {
        //     document.querySelector('.wrapper').classList.toggle('active');
        // });
        
        // Menandai link sidebar yang aktif berdasarkan URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                
                // Jangan tandai 'active' pada tombol logout
                if (link.closest('.logout-link')) {
                    return; 
                }

                if (link.href === window.location.href) { // Cek URL lengkap
                    link.classList.add('active');
                } else if (link.getAttribute('href') !== '#' && currentPath.startsWith(link.getAttribute('href')) && link.getAttribute('href') !== "{{ route('dashboard') }}") {
                     // Jika halaman saat ini adalah child dari link sidebar (misal /admin/berita/create)
                     // Jangan tandai dashboard sebagai active jika kita di halaman child lain
                    link.classList.add('active');
                } else if (currentPath === "{{ route('dashboard') }}" && link.href === "{{ route('dashboard') }}") {
                    // Penanganan khusus untuk dashboard
                    link.classList.add('active');
                }

                // Logika baru untuk menangani 'berita.*' dsb
                const routeName = link.dataset.routeName; // Kita perlu menambahkan data-route-name di HTML
                if (routeName && currentPath.startsWith('/admin/'L + routeName)) {
                     // link.classList.add('active'); // Ini akan lebih baik jika kita pakai perbandingan route di blade
                }
            });

            // Logika Pengecekan Aktif yang Lebih Baik (menggunakan Blade)
            // Script di atas (document.querySelectorAll) sudah cukup baik berkat Blade helper:
            // {{ request()->routeIs('berita.*') ? 'active' : '' }}
            // Jadi, kita tidak perlu JS yang rumit.
        });
    </script>
</body>
</html>