<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pengumuman;
use App\Models\Kontak; // <-- 1. Tambahkan Model Kontak

class PageController extends Controller
{
    /**
     * Halaman Beranda
     */
    public function home()
    {
        // --- PERBAIKAN LOGIKA ---
        // 1. Ambil 6 berita terbaru
        $semuaBeritaBaru = Berita::latest()->take(6)->get();

        // 2. Ambil 1 berita pertama sebagai Berita Utama
        //    (first() akan mengembalikan null jika koleksi kosong, ini aman)
        $beritaUtama = $semuaBeritaBaru->first();

        // 3. Ambil 5 berita sisanya (skip 1) sebagai Berita Lainnya
        //    (slice(1) akan mengembalikan koleksi kosong jika item kurang dari 2, ini aman)
        $beritaLainnya = $semuaBeritaBaru->slice(1);
        
        return view('public.home', compact('beritaUtama', 'beritaLainnya'));
    }

    /**
     * Halaman Profil
     */
    public function profil()
    {
        return view('public.profil');
    }

    /**
     * Halaman Berita
     */
    public function berita()
    {
        // Ambil 6 berita terbaru untuk "Hot News"
        $hot_news = Berita::latest()->take(6)->get();

        // Ambil ID dari hot_news
        $hot_news_ids = $hot_news->pluck('id');

        // Ambil berita lainnya (selain hot news) dengan paginasi (9 per halaman)
        $beritas = Berita::whereNotIn('id', $hot_news_ids)
                        ->latest()
                        ->paginate(9);

        // PERBAIKAN: Ambil 5 topik/berita lainnya secara acak,
        // yang juga BUKAN bagian dari $hot_news atau $beritas di halaman ini.
        $beritas_ids = $beritas->pluck('id');
        $exclude_ids = $hot_news_ids->merge($beritas_ids);

        $topik_lainnya = Berita::whereNotIn('id', $exclude_ids)
                                ->inRandomOrder() // Ambil acak
                                ->take(5)
                                ->get();

        return view('public.berita', compact('hot_news', 'beritas', 'topik_lainnya'));
    }

    /**
     * Halaman Galeri
     */
    public function galeri()
    {
        $galeris = Galeri::latest()->paginate(12); // 12 foto per halaman
        return view('public.galeri', compact('galeris'));
    }

    /**
     * Halaman Layanan Publik
     */
    public function layanan()
    {
        return view('public.layanan');
    }

    /**
     * Halaman Pengumuman
     */
    public function pengumuman()
    {
        $pengumumans = Pengumuman::latest()->paginate(10); // 10 pengumuman per halaman
        return view('public.pengumuman', compact('pengumumans'));
    }

    /**
     * Halaman Kontak
     */
    public function kontak()
    {
        return view('public.kontak');
    }

    /**
     * 2. TAMBAHKAN METHOD BARU INI
     * Method untuk MENYIMPAN data dari form kontak
     */
    public function storeKontak(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'isi_pengaduan' => 'required|string',
        ]);

        // Simpan ke database menggunakan Model Kontak
        Kontak::create($validated);

        // Kembalikan ke halaman kontak dengan pesan sukses
        return redirect()->route('public.kontak')->with('success', 'Pesan Anda telah berhasil terkirim. Terima kasih!');
    }
    /**
     * Halaman Detail Berita
     * (Method BARU yang Anda tambahkan)
     */
    public function showBerita($id)
    {
        // 1. Ambil berita yang sedang dibuka
        $berita = Berita::findOrFail($id);

        // 2. Ambil berita terkait (misal: 3 berita terbaru, BUKAN yg sedang dibaca)
        $related_news = Berita::where('id', '!=', $id) // <-- Kecualikan berita ini
                                    ->latest()
                                    ->take(3)
                                    ->get();
                                    
        // 3. Kirim data ke view detail
        return view('public.berita_detail', compact('berita', 'related_news'));
    }


    
}


