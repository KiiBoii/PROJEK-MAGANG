<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pengumuman;
use App\Models\Kontak;
use App\Models\Slider; 
use App\Models\Dokumen;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon; 

class PageController extends Controller
{
    /**
     * Halaman Beranda
     */
    public function home()
    {
        $sliders = Slider::where('halaman', 'home')->where('is_visible', true)->latest()->get();
        
        $semuaBeritaBaru = Berita::with('user')
                                    ->whereNull('tag') 
                                    ->where('is_visible', true) // <-- DITAMBAHKAN
                                    ->latest()
                                    ->take(6)
                                    ->get();
                                    
        $beritaUtama = $semuaBeritaBaru->first();
        $beritaLainnya = $semuaBeritaBaru->slice(1);
        
        return view('public.home', compact('sliders', 'beritaUtama', 'beritaLainnya'));
    }

    /**
     * Halaman Profil
     */
    public function profil()
    {
        $sliders = Slider::where('halaman', 'profil')->where('is_visible', true)->latest()->get();
        return view('public.profil', compact('sliders'));
    }

    /**
     * [BARU] Halaman Profil Kepala Dinas
     */
    public function profilKadis()
    {
        // [SARAN]
        // Nanti Anda bisa mengambil data Kadis secara dinamis dari database
        // $kadis = ProfilPejabat::where('jabatan', 'Kepala Dinas')->first();
        
        // Kirim data ke view
        return view('public.profil-kadis' /*, [
            'kadis' => $kadis 
        ]*/);
    }

    /**
     * Halaman Berita
     */
    public function berita()
    {
        $sliders = Slider::where('halaman', 'berita')->where('is_visible', true)->latest()->get();
        
        // Ambil 6 Berita TERBARU yang BUKAN topik (tag = null) untuk "Hot News"
        $hot_news = Berita::with('user')
                            ->whereNull('tag') // Hanya berita biasa
                            ->where('is_visible', true) // <-- DITAMBAHKAN
                            ->latest()
                            ->take(6)
                            ->get();
        
        // Ambil ID Berita 'hot' untuk dikecualikan
        $hot_news_ids = $hot_news->pluck('id');

        // Ambil sisa Berita yang BUKAN topik (tag = null) untuk "Berita Lainnya"
        $beritas = Berita::with('user')
                            ->whereNull('tag') // Hanya berita biasa
                            ->where('is_visible', true) // <-- DITAMBAHKAN
                            ->whereNotIn('id', $hot_news_ids) // Lewati berita 'hot'
                            ->latest()
                            ->paginate(9); // Ambil 9 per halaman
        
        // Ambil 5 Berita TERBARU yang MEMILIKI TAG (ini adalah Topik Lainnya)
        $topik_lainnya = Berita::with('user')
                                    ->whereNotNull('tag') // HANYA berita yang punya tag
                                    ->where('is_visible', true) // <-- DITAMBAHKAN
                                    ->latest()
                                    ->take(5) // Ambil 5 topik terbaru
                                    ->get();

        return view('public.berita', compact('sliders', 'hot_news', 'beritas', 'topik_lainnya'));
    }
    

    /**
     * Halaman Semua Topik
     */
    /**
     * Halaman Semua Topik
     */
    public function topik()
    {
        // 1. Ambil 3 Berita 'Topik' terbaru untuk slider (Ini sudah benar)
        $sliders = Berita::with('user')
                            ->whereNotNull('tag') // HANYA berita yang punya tag
                            ->where('is_visible', true) // <-- DITAMBAHKAN
                            ->latest()
                            ->take(3) // Ambil 3 saja
                            ->get();
        
        // 2. Konten Utama: Ambil SEMUA berita yang punya TAG
        $semua_topik = Berita::with('user')
                                ->whereNotNull('tag') // HANYA berita yang punya tag
                                ->where('is_visible', true) // <-- DITAMBAHKAN
                                // ▼▼▼ PASTIKAN BARIS DI BAWAH INI DIHAPUS ▼▼▼
                                // ->whereNotIn('id', $sliders->pluck('id')) 
                                // ▲▲▲ JANGAN GUNAKAN whereNotIn() ▲▲▲
                                ->latest()
                                ->paginate(9); // Paginasi 9 item per halaman
        
        // 3. Sidebar: Ambil 5 Berita terbaru TANPA tag (Ini sudah benar)
        $berita_terbaru_sidebar = Berita::with('user')
                                        ->whereNull('tag') // Hanya berita biasa
                                        ->where('is_visible', true) // <-- DITAMBAHKAN
                                        ->latest()
                                        ->take(5) // Ambil 5 berita terbaru
                                        ->get();

        // 4. Kirim data ke view (Ini sudah benar)
        return view('public.berita-topik', compact('sliders', 'semua_topik', 'berita_terbaru_sidebar'));
    }

    /**
     * Halaman Galeri
     */
    public function galeri(Request $request) 
    {
        $sliders = Slider::where('halaman', 'galeri')->where('is_visible', true)->latest()->get();
        $bidangList = Galeri::whereNotNull('bidang')->where('bidang', '!=', '')->distinct()->pluck('bidang');
        
        // Asumsi: Galeri juga punya kolom is_visible
        $query = Galeri::with('user')->where('is_visible', true); // <-- DITAMBAHKAN

        if ($request->has('bidang') && $request->bidang != '') {
            $query->where('bidang', $request->bidang);
        }

        // ▼▼▼ PERUBAHAN DI SINI ▼▼▼
        // Mengganti ->get() menjadi ->paginate() agar paginasi di view berfungsi.
        // Saya set 9 item per halaman, Anda bisa ubah angkanya.
        // $galeris = $query->latest()->get(); // <-- Kode lama
        $galeris = $query->latest()->paginate()->withQueryString(); // <-- Kode baru
        // ▲▲▲ AKHIR PERUBAHAN ▲▲▲

        return view('public.galeri', compact('sliders', 'galeris', 'bidangList'));
    }

    /**
     * Halaman Layanan Publik
     */
    public function layanan(Request $request)
    {

        $sliders = Slider::where('halaman', 'layanan')->where('is_visible', true)->latest()->get();
        
        // Asumsi: Dokumen juga punya kolom is_visible
        $query = Dokumen::query()->where('is_visible', true); // <-- DITAMBAHKAN

        if ($request->filled('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%')
                    ->orWhere('keterangan', 'like', '%' . $request->cari . '%');
        }
        $perPage = $request->input('per_page', 10);
        $dokumens = $query->latest()->paginate($perPage)->withQueryString();
        return view('public.layanan', compact('sliders', 'dokumens'));
    }

    /**
     * Halaman Pengumuman
     */
    public function pengumuman()
    {

        $sliders = Slider::where('halaman', 'pengumuman')->where('is_visible', true)->latest()->get();
        
        // Asumsi: Pengumuman juga punya kolom is_visible
        // ▼▼▼ PERUBAHAN DI SINI (10 -> 9) ▼▼▼
        $pengumumans = Pengumuman::with('user')
                                ->where('is_visible', true) // <-- DITAMBAHKAN
                                ->latest()
                                ->paginate(10);
        // ▲▲▲ AKHIR PERUBAHAN ▲▲▲
        
        return view('public.pengumuman', compact('sliders', 'pengumumans'));
    }

    /**
     * [BARU] Halaman FAQ (Pusat Bantuan)
     */
    public function faq()
    {
        // ▼▼▼ PERUBAHAN DI SINI ▼▼▼
        // Mengambil data slider untuk halaman 'faq'. 
        // Anda bisa ganti 'faq' dengan 'layanan' jika ingin slidernya sama
        // dengan halaman layanan.
        $sliders = Slider::where('halaman', 'faq')  // [OPSIONAL] Fallback ke slider 'layanan'
                         ->where('is_visible', true)
                         ->latest()
                         ->get();
        
        // Anda bisa juga mengambil data FAQ dinamis dari DB di sini
        // $faqs = FaqModel::where('is_visible', true)->get();
        
        return view('public.faq', compact('sliders' /*, 'faqs' */));
        // ▲▲▲ AKHIR PERUBAHAN ▲▲▲
    }

    /**
     * Halaman Kontak

     */
    public function kontak()
    {

        $sliders = Slider::where('halaman', 'kontak')->where('is_visible', true)->latest()->get();
        return view('public.kontak', compact('sliders'));
    }
    

    /**
     * Method untuk MENYIMPAN data dari form kontak
     */
    public function storeKontak(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20', 
            'isi_pengaduan' => 'required|string',
            'foto_pengaduan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('foto_pengaduan')) {
            $validated['foto_pengaduan'] = $request->file('foto_pengaduan')->store('pengaduan_images', 'public');
        }
        Kontak::create($validated);
        return redirect()->route('public.kontak')->with('success', 'Pengaduan Anda telah berhasil terkirim. Terima kasih!');
    }
    
    /**
     * Halaman Detail Berita
     */
    public function showBerita($id)
    {
        // Ini akan otomatis gagal (404) jika 'is_visible' = false
        $berita = Berita::with('user')->where('is_visible', true)->findOrFail($id); // <-- DITAMBAHKAN

        //Ambil 5 berita acak (apapun tag-nya)
        // "semua berita yg eksis (selain berita yang sedang dibuka user)"
        $topik_lainnya = Berita::where('id', '!=', $id) // Jangan tampilkan berita yang sedang dibaca
                                        ->where('is_visible', true) // <-- DITAMBAHKAN
                                        ->inRandomOrder() // Ambil acak dari semua berita
                                        ->take(20)
                                        ->get();
                                        
        return view('public.berita_detail', compact('berita', 'topik_lainnya')); 
    }
}