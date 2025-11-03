<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// --- TAMBAHKAN SEMUA CONTROLLER ADMIN DI SINI ---
use App\Http\Controllers\Admin\BeritaController; 
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\KaryawanController;    // Ditambahkan untuk UI KARYAWAN.jpg
use App\Http\Controllers\Admin\PengumumanController;  // Ditambahkan untuk item sidebar "Pengumuman"

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda.
|
*/

// Halaman depan publik (Biarkan, atau redirect ke login)
Route::get('/', function () {
    // return view('welcome'); 
    
    // Saran: Jika ini HANYA aplikasi admin, redirect ke login
    return redirect()->route('login');
});


// === GRUP UNTUK SEMUA HALAMAN ADMIN ===
// Semua route di dalam grup ini dilindungi oleh login (auth)
// dan memiliki awalan URL /admin (contoh: /admin/berita)
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    // Halaman Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Pastikan file ini ada
    })->name('dashboard'); // nama route: admin.dashboard

    // Halaman Profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // --- RUTE CRUD MODUL ANDA ---

    // 1. Berita (Sesuai BERITA.jpg)
    // Ini akan membuat route: berita.index, berita.create, berita.store, 
    // berita.show, berita.edit, berita.update, berita.destroy
Route::resource('berita', BeritaController::class)->parameter('berita', 'berita');

    // 2. Galeri (Sesuai Galerry-1.jpg)
    Route::resource('galeri', GaleriController::class);

    // 3. Pengumuman (Sesuai spesifikasi & sidebar)
    Route::resource('pengumuman', PengumumanController::class);

    // 4. Karyawan / Pengelolaan Admin (Sesuai KARYAWAN.jpg)
    Route::resource('karyawan', KaryawanController::class);

    // 5. Pengaduan Masyarakat (Sesuai Pengaduan Masrayrakat.jpg)
    // UI ini sepertinya hanya menampilkan data (Read-Only), 
    // jadi kita hanya butuh route 'index'.
    Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');

    // (Route untuk "Laporan Kontak" bisa ditambahkan di sini jika perlu)

});


// Ini adalah file yang berisi route untuk login, register, logout, dll.
// Biarkan di baris terakhir.
require __DIR__.'/auth.php';