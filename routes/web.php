<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// --- IMPORT CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BeritaController; 
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\PengumumanController;
// use App\Http\Controllers\Admin\KontakController; // <-- Dihapus, digabung ke Pengaduan
use App\Http\Controllers\Admin\SliderController; // <-- 1. IMPORT SLIDER CONTROLLER

// --- IMPORT CONTROLLER PUBLIC ---
use App\Http\Controllers\Public\PageController;

// --- IMPORT CONTROLLER AUTH KUSTOM KITA ---
use App\Http\Controllers\Auth\LoginController;

// --- IMPORT MIDDLEWARE (UNTUK SOLUSI 2) ---
use App\Http\Middleware\PreventBackHistory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === RUTE HALAMAN PUBLIK (TANPA LOGIN) ===
Route::get('/', [PageController::class, 'home'])->name('public.home');
Route::get('/profil', [PageController::class, 'profil'])->name('public.profil');
Route::get('/berita', [PageController::class, 'berita'])->name('public.berita');
Route::get('/berita/{id}', [PageController::class, 'showBerita'])->name('public.berita.detail');
Route::get('/layanan-publik', [PageController::class, 'layanan'])->name('public.layanan');
Route::get('/galeri', [PageController::class, 'galeri'])->name('public.galeri');
Route::get('/pengumuman', [PageController::class, 'pengumuman'])->name('public.pengumuman');
Route::get('/kontak', [PageController::class, 'kontak'])->name('public.kontak');
Route::post('/kontak', [PageController::class, 'storeKontak'])->name('public.kontak.store');


// === RUTE AUTENTIKASI KUSTOM (LOGIN/LOGOUT) ===
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// === GRUP UNTUK SEMUA HALAMAN ADMIN ===
//
// === PERUBAHAN DI SINI ===
// Mengganti alias 'no-cache' dengan path class lengkap
//
Route::middleware(['auth', 'role:admin,berita', PreventBackHistory::class])->prefix('admin')->group(function () {
    
    // --- Rute yang bisa diakses KEDUA role (Admin & Berita) ---
    Route::resource('berita', BeritaController::class)->parameter('berita', 'berita');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // --- Rute yang HANYA bisa diakses oleh 'admin' ---
    Route::middleware('role:admin')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('galeri', GaleriController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::resource('karyawan', KaryawanController::class);
        
        // Mengganti get() dengan resource() agar mencakup 'index' dan 'destroy'
        Route::resource('pengaduan', PengaduanController::class)->only([
            'index', 'destroy'
        ]);
        
        // Mengarahkan 'kontak.index' ke PengaduanController untuk fix error sidebar
        Route::get('kontak', [PengaduanController::class, 'index'])->name('kontak.index');

        // <-- 2. TAMBAHKAN RUTE BARU INI UNTUK SLIDER -->
        Route::resource('slider', SliderController::class);
        Route::patch('slider/{slider}/toggle', [SliderController::class, 'toggleStatus'])->name('slider.toggle');
        // <-- AKHIR RUTE BARU -->
    
    }); // Akhir grup 'role:admin'

}); // Akhir grup 'auth'


// require __DIR__.'/auth.php'; // Biarkan ini ter-komentar (dihapus)