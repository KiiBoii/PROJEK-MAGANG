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

// --- IMPORT MIDDLEWARE ---
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
Route::get('/berita/topik', [PageController::class, 'topik'])->name('public.berita.topik');
Route::get('/berita/{id}', [PageController::class, 'showBerita'])->name('public.berita.detail');

Route::get('/layanan-publik', [PageController::class, 'layanan'])->name('public.layanan');
Route::get('/galeri', [PageController::class, 'galeri'])->name('public.galeri');
Route::get('/pengumuman', [PageController::class, 'pengumuman'])->name('public.pengumuman');
Route::get('/faq', [PageController::class, 'faq'])->name('public.faq');

Route::get('/kontak', [PageController::class, 'kontak'])->name('public.kontak');
Route::post('/kontak', [PageController::class, 'storeKontak'])->name('public.kontak.store');

// --- RUTE PPID ---
Route::prefix('ppid')->name('public.ppid.')->group(function () {
    Route::get('/daftar-informasi-2025', [PageController::class, 'ppidDaftarInfo2025'])->name('daftar_info_2025');
    Route::get('/maklumat', [PageController::class, 'ppidMaklumat'])->name('maklumat');
    Route::get('/pengaduan-wewenang', [PageController::class, 'ppidPengaduanWewenang'])->name('pengaduan_wewenang');
    Route::get('/laporan', [PageController::class, 'ppidLaporanPpid'])->name('laporan_ppid');
    Route::get('/formulir-permohonan', [PageController::class, 'ppidFormulirPermohonan'])->name('formulir_permohonan');
    Route::get('/alur-sengketa', [PageController::class, 'ppidAlurSengketa'])->name('alur_sengketa');
    Route::get('/alur-hak-pengajuan', [PageController::class, 'ppidAlurHakPengajuan'])->name('alur_hak_pengajuan');
    Route::get('/alur-tata-cara', [PageController::class, 'ppidAlurTataCara'])->name('alur_tata_cara');
    Route::get('/formulir-keberatan', [PageController::class, 'ppidFormulirKeberatan'])->name('formulir_keberatan');
    Route::get('/info-berkala', [PageController::class, 'ppidInfoBerkala'])->name('info_berkala');
    Route::get('/info-serta-merta', [PageController::class, 'ppidInfoSertaMerta'])->name('info_serta_merta');
    Route::get('/info-setiap-saat', [PageController::class, 'ppidInfoSetiapSaat'])->name('info_setiap_saat');
    Route::get('/sk-terbaru', [PageController::class, 'ppidSKTerbaru'])->name('sk_terbaru');
    Route::get('/arsip-sk', [PageController::class, 'ppidArsipSK'])->name('arsip_sk');
    Route::get('/info-publik-lain', [PageController::class, 'ppidInfoPublikLain'])->name('info_publik_lain');
    Route::get('/jumlah-permohonan', [PageController::class, 'ppidJumlahPermohonan'])->name('jumlah_permohonan');
});

Route::prefix('ppid/daftar-informasi')->name('public.ppid.')->group(function () {
    Route::get('/lansia-panti', [PageController::class, 'ppidLansiaPanti'])->name('lansia');
    Route::get('/anak-panti', [PageController::class, 'ppidAnakPanti'])->name('anakpanti');
    Route::get('/disabilitas-fisik-panti', [PageController::class, 'ppidDisabilitasPanti'])->name('disabilitaspanti');
    Route::get('/disabilitas-mental', [PageController::class, 'ppidDisabilitasMental'])->name('disabilitasmental');
    Route::get('/gelandang-pengemis', [PageController::class, 'ppidGelandangPengemis'])->name('gelandangpengemis');
    Route::get('/standar-pelayanan-abh', [PageController::class, 'ppidStandarPelayananABH'])->name('standarpelayananabh');
    Route::get('/penanganan-bencana', [PageController::class, 'ppidPenangananBencana'])->name('penangananbencana');
    Route::get('/izin-pengangkatan-anak', [PageController::class, 'ppidIzinPengangkatanAnak'])->name('izinpengangkatananak');
    Route::get('/tanda-daftar-lks', [PageController::class, 'ppidTandaDaftarLKS'])->name('tandadaftarlks');
    Route::get('/pemulangan-imigran', [PageController::class, 'ppidPemulanganImigran'])->name('pemulanganimigran');
    Route::get('/pengaduan-monitoring-pkh', [PageController::class, 'ppidPengaduanMonitoringPKH'])->name('pengaduanmonitoringpkh');
    Route::get('/pertimbangan-teknis-ugb-pub', [PageController::class, 'ppidPertimbanganTeknisUGBPUB'])->name('pertimbanganteknisugbpub');
});


// === RUTE AUTENTIKASI ===
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// === GRUP ADMIN ===
Route::middleware(['auth', 'role:admin,redaktur', PreventBackHistory::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    // Admin & Redaktur
    Route::resource('berita', BeritaController::class)->parameter('berita', 'berita');
    Route::patch('berita/{berita}/toggle', [BeritaController::class, 'toggleStatus'])->name('berita.toggle');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
    Route::get('dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chartData');
    Route::get('dashboard/activities', [DashboardController::class, 'allActivities'])->name('dashboard.activities');
    Route::get('dashboard/contributors', [DashboardController::class, 'allContributors'])->name('dashboard.contributors');

    // HANYA ADMIN
    Route::middleware('role:admin')->group(function () {
        
        Route::resource('dokumen', DokumenController::class)
            ->parameters(['dokumen' => 'dokumen'])
            ->names('dokumen');

        Route::resource('galeri', GaleriController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::resource('karyawan', KaryawanController::class);
        Route::resource('slider', SliderController::class);
        Route::patch('slider/{slider}/toggle', [SliderController::class, 'toggleStatus'])->name('slider.toggle');
        
        // ▼▼▼ PERBAIKAN RUTE PENGADUAN ▼▼▼
        Route::get('kontak', [PengaduanController::class, 'index'])->name('kontak.index'); // Alias
        
        // Route Resource (Index & Destroy)
        Route::resource('pengaduan', PengaduanController::class)->only(['index', 'destroy']);
        
        // Route Update Status
        Route::patch('pengaduan/{id}/update-status', [PengaduanController::class, 'updateStatus'])
            ->name('pengaduan.update-status');

        // Route Simpan / Edit Balasan Admin
        Route::patch('pengaduan/{id}/balas', [PengaduanController::class, 'balas'])
            ->name('pengaduan.balas');

        // Route Hapus Balasan Admin
        Route::delete('pengaduan/{id}/hapus-balasan', [PengaduanController::class, 'hapusBalasan'])
            ->name('pengaduan.hapus-balasan');
        // ▲▲▲ SELESAI PERBAIKAN ▼▼▼
    
    }); 
});