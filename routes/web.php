<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// --- IMPORT CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\BeritaController; 
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KontakController; // <-- 1. TAMBAHKAN INI

// --- IMPORT CONTROLLER PUBLIC ---
use App\Http\Controllers\Public\PageController;

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

// 2. UBAH RUTE POST INI
// Hapus/ganti rute 'public.storePengaduan' jika tidak terpakai
// Route::post('/kirim-pengaduan', [PageController::class, 'storePengaduan'])->name('public.storePengaduan'); 
Route::post('/kontak', [PageController::class, 'storeKontak'])->name('public.kontak.store'); // GANTI MENJADI INI


// === GRUP UNTUK SEMUA HALAMAN ADMIN ===
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Pastikan file ini ada
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // --- RUTE CRUD MODUL ADMIN ---
    Route::resource('berita', BeritaController::class)->parameter('berita', 'berita');
    Route::resource('galeri', GaleriController::class);
    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    
    // 3. TAMBAHKAN RUTE BARU INI UNTUK ADMIN
    Route::get('kontak', [KontakController::class, 'index'])->name('kontak.index');

});


// Ini adalah file yang berisi route untuk login, register, logout, dll.
require __DIR__.'/auth.php';

