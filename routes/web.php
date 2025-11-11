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
use App\Http\Controllers\Admin\SliderController; 
use App\Http\Controllers\Admin\DokumenController;
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
Route::get('/profil-kepala-dinas', [PageController::class, 'profilKadis'])->name('public.profil.kadis');
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
// Menggunakan prefix 'admin' dan nama 'admin.'
Route::middleware(['auth', 'role:admin,redaktur', PreventBackHistory::class])
    ->prefix('admin')
    ->name('admin.') // <-- Menambahkan 'admin.' sebagai awalan nama
    ->group(function () {
    
    
    // --- Rute yang bisa diakses KEDUA role (Admin & Redaktur) ---
    Route::resource('berita', BeritaController::class)->parameter('berita', 'berita');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // ▼▼▼ BLOK PERBAIKAN DASHBOARD ▼▼▼
    // URL: /admin/dashboard
    // NAMA: admin.dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
    
    // URL: /admin/dashboard/chart-data
    // NAMA: admin.dashboard.chartData
    Route::get('dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chartData');
    
    // URL: /admin/dashboard/activities
    // NAMA: admin.dashboard.activities
    Route::get('dashboard/activities', [DashboardController::class, 'allActivities'])->name('dashboard.activities');
    
    // URL: /admin/dashboard/contributors
    // NAMA: admin.dashboard.contributors
    Route::get('dashboard/contributors', [DashboardController::class, 'allContributors'])->name('dashboard.contributors');
    // ▲▲▲ AKHIR BLOK PERBAIKAN ▲▲▲


    // --- Rute yang HANYA bisa diakses oleh 'admin' ---
    Route::middleware('role:admin')->group(function () {
        
        // PERBAIKAN: Hapus 'Admin\' karena sudah di-import di atas
        Route::resource('dokumen', DokumenController::class)
            ->parameters(['dokumen' => 'dokumen'])
            ->names('dokumen'); // <-- Nama sudah otomatis jadi 'admin.dokumen'

        
        Route::resource('galeri', GaleriController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::resource('karyawan', KaryawanController::class);
        
        Route::resource('pengaduan', PengaduanController::class)->only([
            'index', 'destroy'
        ]);
        
        Route::get('kontak', [PengaduanController::class, 'index'])->name('kontak.index');

        Route::resource('slider', SliderController::class);
        Route::patch('slider/{slider}/toggle', [SliderController::class, 'toggleStatus'])->name('slider.toggle');
    
    }); // Akhir grup 'role:admin'

}); // Akhir grup 'auth'