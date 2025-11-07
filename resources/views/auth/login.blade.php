<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #3B82F6; /* Biru cerah dari gambar */
            --primary-dark: #2563EB;
            --form-bg: #ffffff;
            --page-bg: #ffffff; /* Latar belakang halaman utama jadi putih */
            --text-color: #374151; /* Abu-abu tua */
            --text-light: #6B7280; /* Abu-abu muda */
            --input-bg: #F3F4F6; /* Latar input abu-abu muda */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--page-bg);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .login-container-fluid {
            min-height: 100vh;
            padding: 0;
        }

        .login-row {
            min-height: 100vh;
        }

        /* Sisi Kiri (Biru) */
        .login-image-side {
            background-color: var(--primary-color);
            background-size: cover;
            background-position: center;
        }

        /* Sisi Kanan (Form Putih) */
        .login-form-side {
            background-color: var(--form-bg);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            overflow: hidden; /* Penting untuk efek gelombang */
            
            
        }
        
        .login-form-wrapper {
            max-width: 450px;
            width: 100%;
            z-index: 10;
        }
        
        /* EFEK GELOMBANG (Wave Effect) */
        @media (min-width: 992px) {
            .login-form-side::before {
                content: '';
                position: absolute;
                top: 50%;
                /* Dibuat lebih ke kanan (-900px) agar kurva lebih dalam */
                left: -900px; 
                transform: translateY(-50%);
                width: 2000px; /* Lingkaran besar */
                height: 2000px; /* Lingkaran besar */
                background-color: var(--form-bg); /* Warna gelombang = putih */
                border-radius: 50%;
                z-index: 1;
            }
            .login-form-wrapper {
                position: relative;
                z-index: 2; /* Pastikan form di atas gelombang */
            }
        }

        .login-logo {
            max-height: 80px;
            margin-bottom: 1.5rem;
        }

        .login-title {
            font-weight: 700;
            font-size: 2.25rem; /* Sedikit lebih besar */
            letter-spacing: 1px;
            color: var(--primary-dark); /* Warna biru tua */
        }
        
        .login-subtitle {
            color: var(--text-light);
            margin-bottom: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-light); /* Label abu-abu */
            text-transform: uppercase;
        }
        
        /* Grup Input Styling (Baru) */
        .input-group {
            border: 1px solid transparent; /* Untuk transisi */
            background-color: var(--input-bg);
            border-radius: 10px; /* Lebih bulat */
            box-shadow: 0 3px 8px rgba(0,0,0,0.03);
            overflow: hidden; /* Menjaga border-radius */
            transition: all 0.3s ease;
        }
        
        /* Efek focus-within pada grup */
        .input-group:focus-within {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,123,255,0.1);
        }

        .input-group .form-control {
            background-color: transparent; /* Input transparan */
            border: none; /* Tidak ada border */
            box-shadow: none;
            height: 50px;
            padding-left: 0.5rem;
        }
        
        .input-group .form-control::placeholder {
            color: #ADB5BD;
        }

        .input-group .input-group-text {
            background-color: transparent;
            border: none;
            color: var(--text-light);
        }

        /* Tombol Toggle Password (Dihapus, karena digabung ke input-group-text) */
        /* .password-toggle { ... } */
        
        .btn-login {
            /* Gradient sesuai gambar */
            background-image: linear-gradient(to right, #4F46E5, var(--primary-color));
            border: none;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 10px; /* Menyamai input */
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        .btn-login:hover {
            background-image: linear-gradient(to right, #4338CA, var(--primary-dark));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

    </style>
</head>
<body>

    <div class="container-fluid login-container-fluid">
        <div class="row g-0 login-row">

            <div class="col-lg-5 d-none d-lg-block login-image-side">
                {{-- Anda bisa meletakkan konten di sini jika mau --}}
            </div>

            <div class="col-lg-7 col-12 login-form-side">
                <div class="login-form-wrapper text-center">

                    <img src="https://placehold.co/150x80/3B82F6/white?text=LOGO" alt="Logo Diskominfo" class="login-logo">

                    <h1 class="login-title">WELCOME</h1>
                    <p class="login-subtitle">Silahkan login dengan menggunakan akun Anda</p>

                    <form method="POST" action="{{ route('login') }}" class="text-start">
                        @csrf

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="asepcowboy@email.co.id" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="•••••••••" required autocomplete="current-password">
                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-login">
                            {{ __('Log in') }}
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    // Toggle tipe input
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle ikon
                    // JS ini tetap berfungsi karena kita menargetkan <i> di dalam span
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }
        });
    </script>
</body>
</html>