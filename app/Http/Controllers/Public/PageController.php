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
                                     ->where('is_visible', true) 
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
     * Halaman Profil Kepala Dinas
     */
    public function profilKadis()
    {
        return view('public.profil-kadis');
    }

    /**
     * Halaman Berita
     */
    public function berita()
    {
        $sliders = Slider::where('halaman', 'berita')->where('is_visible', true)->latest()->get();
        
        // Ambil 6 Berita TERBARU yang BUKAN topik (tag = null) untuk "Hot News"
        $hot_news = Berita::with('user')
                            ->whereNull('tag') 
                            ->where('is_visible', true) 
                            ->latest()
                            ->take(6)
                            ->get();
        
        $hot_news_ids = $hot_news->pluck('id');

        // Ambil sisa Berita yang BUKAN topik (tag = null) untuk "Berita Lainnya"
        $beritas = Berita::with('user')
                            ->whereNull('tag') 
                            ->where('is_visible', true) 
                            ->whereNotIn('id', $hot_news_ids) 
                            ->latest()
                            ->paginate(9); 
        
        // Ambil 5 Berita TERBARU yang MEMILIKI TAG (Topik Lainnya)
        $topik_lainnya = Berita::with('user')
                                     ->whereNotNull('tag') 
                                     ->where('is_visible', true) 
                                     ->latest()
                                     ->take(5) 
                                     ->get();

        return view('public.berita', compact('sliders', 'hot_news', 'beritas', 'topik_lainnya'));
    }
    

    /**
     * Halaman Semua Topik
     */
    public function topik()
    {
        $sliders = Berita::with('user')
                            ->whereNotNull('tag') 
                            ->where('is_visible', true) 
                            ->latest()
                            ->take(3) 
                            ->get();
        
        $semua_topik = Berita::with('user')
                                     ->whereNotNull('tag') 
                                     ->where('is_visible', true) 
                                     ->latest()
                                     ->paginate(9); 
        
        $berita_terbaru_sidebar = Berita::with('user')
                                         ->whereNull('tag') 
                                         ->where('is_visible', true) 
                                         ->latest()
                                         ->take(5) 
                                         ->get();

        return view('public.berita-topik', compact('sliders', 'semua_topik', 'berita_terbaru_sidebar'));
    }

    /**
     * Halaman Galeri
     */
    public function galeri(Request $request) 
    {
        $sliders = Slider::where('halaman', 'galeri')->where('is_visible', true)->latest()->get();
        $bidangList = Galeri::whereNotNull('bidang')->where('bidang', '!=', '')->distinct()->pluck('bidang');
        
        $query = Galeri::with('user'); 

        if ($request->has('bidang') && $request->bidang != '') {
            $query->where('bidang', $request->bidang);
        }

        $galeris = $query->latest()->paginate(9)->withQueryString(); 

        return view('public.galeri', compact('sliders', 'galeris', 'bidangList'));
    }

    /**
     * Halaman Layanan Publik
     */
    public function layanan(Request $request)
    {
        $sliders = Slider::where('halaman', 'layanan')->where('is_visible', true)->latest()->get();
        
        $query = Dokumen::query(); 

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
        
        $pengumumans = Pengumuman::with('user')
                             ->latest()
                             ->paginate(10);
        
        return view('public.pengumuman', compact('sliders', 'pengumumans'));
    }

    /**
     * Halaman FAQ (Pusat Bantuan)
     */
    public function faq()
    {
        $sliders = Slider::where('halaman', 'faq') 
                          ->where('is_visible', true)
                          ->latest()
                          ->get();
        
        return view('public.faq', compact('sliders'));
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
            // ▼▼▼ UPDATED: Menggunakan 'public_uploads' agar masuk ke folder public ▼▼▼
            $validated['foto_pengaduan'] = $request->file('foto_pengaduan')->store('pengaduan_images', 'public_uploads');
        }

        Kontak::create($validated);
        return redirect()->route('public.kontak')->with('success', 'Pengaduan Anda telah berhasil terkirim. Terima kasih!');
    }
    
    /**
     * Halaman Detail Berita
     */
    public function showBerita($id) 
    {
        $berita = Berita::with('user')->where('is_visible', true)->findOrFail($id); 

        $topik_lainnya = Berita::where('id', '!=', $id) 
                                     ->where('is_visible', true) 
                                     ->inRandomOrder() 
                                     ->take(5) // Diambil 5 saja cukup untuk sidebar
                                     ->get();
                                     
        return view('public.berita.detail', compact('berita', 'topik_lainnya')); 
    }

    // =========================================================================
    // METHOD UNTUK HALAMAN PPID (DAFTAR INFORMASI PUBLIK)
    // =========================================================================

    public function ppidDaftarInfo2025() { return view('public.ppid.daftar_info_2025'); }
    public function ppidMaklumat() { return view('public.ppid.maklumat'); }
    public function ppidPengaduanWewenang() { return view('public.ppid.pengaduan_wewenang'); }
    public function ppidLaporanPpid() { return view('public.ppid.laporan_ppid'); }
    public function ppidFormulirPermohonan() { return view('public.ppid.formulir_permohonan'); }
    public function ppidAlurSengketa() { return view('public.ppid.alur_sengketa'); }
    public function ppidAlurHakPengajuan() { return view('public.ppid.alur_hak_pengajuan'); }
    public function ppidAlurTataCara() { return view('public.ppid.alur_tata_cara'); }
    public function ppidFormulirKeberatan() { return view('public.ppid.formulir_keberatan'); }
    public function ppidInfoBerkala() { return view('public.ppid.info_berkala'); }
    public function ppidInfoSertaMerta() { return view('public.ppid.info_serta_merta'); }
    public function ppidInfoSetiapSaat() { return view('public.ppid.info_setiap_saat'); }
    public function ppidSKTerbaru() { return view('public.ppid.sk_terbaru'); }
    public function ppidArsipSK() { return view('public.ppid.arsip_sk'); }
    public function ppidInfoPublikLain() { return view('public.ppid.info_publik_lain'); }
    public function ppidJumlahPermohonan() { return view('public.ppid.jumlah_permohonan'); }

    // Method untuk 12 Layanan Teknis
    public function ppidLansiaPanti() { return view('public.layanan.RehabilitasiSosialLansiaPanti'); }
    public function ppidAnakPanti() { return view('public.layanan.RehabilitasiSosialAnakPanti'); }
    public function ppidDisabilitasPanti() { return view('public.layanan.RehabilitasiDisabilitasPanti'); }
    public function ppidDisabilitasMental() { return view('public.layanan.RehabilitasiDisabilitasMental'); }
    public function ppidGelandangPengemis() { return view('public.layanan.RehabilitasiGelandangPengemis'); }
    public function ppidStandarPelayananABH() { return view('public.layanan.StandarPelayananAnakBentukHukum'); }
    public function ppidPenangananBencana() { return view('public.layanan.PenangananKorbanBencana'); }
    public function ppidIzinPengangkatanAnak() { return view('public.layanan.PemberianIzinPengangkatanAnak'); }
    public function ppidTandaDaftarLKS() { return view('public.layanan.PenerbitanTandaDaftarLKS'); }
    public function ppidPemulanganImigran() { return view('public.layanan.PemulanganWargaImigran'); }
    public function ppidPengaduanMonitoringPKH() { return view('public.layanan.PengaduanMonitoringPKH'); }
    public function ppidPertimbanganTeknisUGBPUB() { return view('public.layanan.PertimbanganTeknisUGBPUB'); }
}