<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 1. Import semua Model yang kita perlukan
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kontak; // Asumsi "Pengaduan" disimpan di tabel Kontak
use App\Models\Pengumuman;
use Illuminate\Support\Facades\DB; // Untuk query chart
use Illuminate\Support\Carbon;     // Untuk mengelola tanggal

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // === 1. DATA UNTUK CARD STATISTIK ===
        
        $totalBerita = Berita::count();
        $totalGaleri = Galeri::count();
        $totalPengaduan = Kontak::count();
        $totalPengumuman = Pengumuman::count();

        
        // === 2. DATA UNTUK GRAFIK ===
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = Berita::whereYear('created_at', $month->year)
                           ->whereMonth('created_at', $month->month)
                           ->count();
        }
        $chartLabels = $labels;
        $chartData = $data;
        

        // === 3. DATA AKTIVITAS TERBARU (DIPERBARUI) ===

        // Ambil 5 data terbaru, DAN ambil data 'user' yang terkait
        // 'with('user')' -> Eager load relasi 'user' yang kita buat di Model
        $beritaActivities = Berita::with('user')->latest()->take(5)->get()->map(function($item) {
            $item->jenis_aktivitas = 'Berita Baru';
            $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-newspaper'; // Ikon Bootstrap
            $item->route = route('berita.index'); // Link ke index
            
            // Cek jika relasi user ada, jika tidak, tampilkan 'Sistem'
            $item->userName = $item->user->name ?? 'Sistem'; // <-- Ambil nama user
            return $item;
        });

        $galeriActivities = Galeri::with('user')->latest()->take(5)->get()->map(function($item) {
            $item->jenis_aktivitas = 'Galeri Baru';
            $item->judul_aktivitas = $item->judul_kegiatan;
            $item->icon = 'bi-images';
            $item->route = route('galeri.index');
            $item->userName = $item->user->name ?? 'Sistem'; // <-- Ambil nama user
            return $item;
        });

        $pengumumanActivities = Pengumuman::with('user')->latest()->take(5)->get()->map(function($item) {
            $item->jenis_aktivitas = 'Pengumuman Baru';
            $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-megaphone';
            $item->route = route('pengumuman.index');
            $item->userName = $item->user->name ?? 'Sistem'; // <-- Ambil nama user
            return $item;
        });

        $pengaduanActivities = Kontak::latest()->take(5)->get()->map(function($item) {
            $item->jenis_aktivitas = 'Pengaduan Baru';
            $item->judul_aktivitas = 'Pesan dari ' . $item->nama;
            $item->icon = 'bi-chat-left-text';
            $item->route = route('pengaduan.index'); 
            $item->userName = $item->nama; // <-- Ambil nama dari pengaduan (guest)
            return $item;
        });

        // Gabungkan semua koleksi aktivitas
        $allActivities = $beritaActivities
            ->merge($galeriActivities)
            ->merge($pengumumanActivities)
            ->merge($pengaduanActivities);

        // Urutkan berdasarkan tanggal dibuat (terbaru dulu) dan ambil 5 teratas
        $recentActivities = $allActivities->sortByDesc('created_at')->take(5);


        // === 4. KIRIM SEMUA DATA KE VIEW ===
        return view('admin.dashboard', compact(
            'totalBerita',
            'totalGaleri',
            'totalPengaduan',
            'totalPengumuman',
            'chartLabels',
            'chartData',
            'recentActivities' // <-- Kirim aktivitas terbaru ke view
        ));
    }
}