<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- DIPERLUKAN UNTUK FILTER
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pengumuman;
use App\Models\Kontak;
use App\Models\Slider; // <-- 1. IMPORT MODEL SLIDER
use Illuminate\Support\Facades\Storage; 

class PageController extends Controller
{
    /**
     * Halaman Beranda
     */
    public function home()
    {
        // Ambil slider khusus untuk halaman 'home'
        $sliders = Slider::where('halaman', 'home')->where('is_visible', true)->latest()->get();

        // --- Logika Berita (Tidak berubah) ---
        $semuaBeritaBaru = Berita::with('user')->latest()->take(6)->get();
        $beritaUtama = $semuaBeritaBaru->first();
        $beritaLainnya = $semuaBeritaBaru->slice(1);
        
        // Kirim $sliders ke view
        return view('public.home', compact('sliders', 'beritaUtama', 'beritaLainnya'));
    }

    /**
     * Halaman Profil
     */
    public function profil()
    {
        // Ambil slider khusus untuk halaman 'profil'
        $sliders = Slider::where('halaman', 'profil')->where('is_visible', true)->latest()->get();
        return view('public.profil', compact('sliders'));
    }

    /**
     * Halaman Berita
     */
    public function berita()
    {
        // Ambil slider khusus untuk halaman 'berita'
        $sliders = Slider::where('halaman', 'berita')->where('is_visible', true)->latest()->get();
        
        $hot_news = Berita::with('user')->latest()->take(6)->get();
        $hot_news_ids = $hot_news->pluck('id');
        $beritas = Berita::with('user')->whereNotIn('id', $hot_news_ids)
                                ->latest()
                                ->paginate(9);
        $beritas_ids = $beritas->pluck('id');
        $exclude_ids = $hot_news_ids->merge($beritas_ids);
        $topik_lainnya = Berita::whereNotIn('id', $exclude_ids)
                                ->inRandomOrder()
                                ->take(5)
                                ->get();

        // Kirim $sliders ke view
        return view('public.berita', compact('sliders', 'hot_news', 'beritas', 'topik_lainnya'));
    }

    /**
     * Halaman Galeri
     * (Versi Isotope - tanpa paginasi)
     */
    public function galeri(Request $request) 
    {
        // Ambil slider khusus untuk halaman 'galeri'
        $sliders = Slider::where('halaman', 'galeri')->where('is_visible', true)->latest()->get();

        $bidangList = Galeri::whereNotNull('bidang')
                            ->where('bidang', '!=', '')
                            ->distinct()
                            ->pluck('bidang');

        $query = Galeri::with('user');

        if ($request->has('bidang') && $request->bidang != '') {
            $query->where('bidang', $request->bidang);
        }

        $galeris = $query->latest()->get(); 

        // Kirim $sliders ke view
        return view('public.galeri', compact('sliders', 'galeris', 'bidangList'));
    }

    /**
     * Halaman Layanan Publik
     */
    public function layanan()
    {
        // Ambil slider khusus untuk halaman 'layanan'
        $sliders = Slider::where('halaman', 'layanan')->where('is_visible', true)->latest()->get();
        return view('public.layanan', compact('sliders'));
    }

    /**
     * Halaman Pengumuman
     */
    public function pengumuman()
    {
        // Ambil slider khusus untuk halaman 'pengumuman'
        $sliders = Slider::where('halaman', 'pengumuman')->where('is_visible', true)->latest()->get();

        $pengumumans = Pengumuman::with('user')->latest()->paginate(10);
        
        // Kirim $sliders ke view
        return view('public.pengumuman', compact('sliders', 'pengumumans'));
    }

    /**
     * Halaman Kontak
     */
    public function kontak()
    {
        // Ambil slider khusus untuk halaman 'kontak'
        $sliders = Slider::where('halaman', 'kontak')->where('is_visible', true)->latest()->get();
        return view('public.kontak', compact('sliders'));
    }

    /**
     * Method untuk MENYIMPAN data dari form kontak
     * === BAGIAN INI DIPERBARUI UNTUK PENGADUAN LENGKAP ===
     */
    public function storeKontak(Request $request)
    {
        // 6. Validasi data (menambahkan no_hp dan foto_pengaduan)
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20', // Validasi No HP
            'isi_pengaduan' => 'required|string',
            'foto_pengaduan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi Foto
        ]);

        // 7. Logic untuk upload foto (jika ada)
        if ($request->hasFile('foto_pengaduan')) {
            $validated['foto_pengaduan'] = $request->file('foto_pengaduan')->store('pengaduan_images', 'public');
        }

        // Simpan ke database menggunakan Model Kontak
        Kontak::create($validated);

        // Kembalikan ke halaman kontak dengan pesan sukses
        return redirect()->route('public.kontak')->with('success', 'Pengaduan Anda telah berhasil terkirim. Terima kasih!');
    }
    
    /**
     * Halaman Detail Berita
     */
    public function showBerita($id)
    {
        // 8. PERBARUI: Tambahkan with('user') untuk mengambil nama penulis
        $berita = Berita::with('user')->findOrFail($id);

        // 2. Ambil berita terkait
        $related_news = Berita::where('id', '!=', $id)
                                        ->latest()
                                        ->take(3)
                                        ->get();
                                        
        // 3. Kirim data ke view detail
        return view('public.berita_detail', compact('berita', 'related_news'));
    }
}