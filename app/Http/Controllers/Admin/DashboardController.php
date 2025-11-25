<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kontak;
use App\Models\Pengumuman;
use App\Models\Slider;
use App\Models\User; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     * (Tidak ada perubahan di method ini)
     */
    public function index()
    {
        $user = Auth::user();
        $isRedaktur = ($user->role == 'redaktur');  
        
        // === 1. DATA CARD STATISTIK ===
        if ($isRedaktur) {
            $totalBerita = Berita::where('user_id', $user->id)->count(); 
            $totalGaleri = 0;
            $totalPengaduan = 0;
            $totalPengumuman = 0;
            $totalSlider = 0; 
        } else {
            $totalBerita = Berita::count();
            $totalGaleri = Galeri::count();
            $totalPengaduan = Kontak::count();
            $totalPengumuman = Pengumuman::count();
            $totalSlider = Slider::count();
        }

        // === 2. DATA UNTUK GRAFIK ===
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $earliestYear = $currentYear - 4; 
        $availableYears = range($currentYear, $earliestYear);   
        
        $availableMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $availableMonths[] = [
                'value' => $m,
                'name' => Carbon::create(null, $m)->format('F')
            ];
        }

        $chartLabels = [];
        $chartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $chartLabels[] = Carbon::create(null, $month)->format('M');
            
            $beritaChartQuery = Berita::whereYear('created_at', $currentYear)
                                      ->whereMonth('created_at', $month);
            
            if ($isRedaktur) {
                 $beritaChartQuery->where('user_id', $user->id);
            }
            
            $chartData[] = $beritaChartQuery->count();
        }

        // === 3. DATA AKTIVITAS TERBARU ===
        $beritaQuery = Berita::with('user');
        $galeriQuery = Galeri::with('user');
        $pengumumanQuery = Pengumuman::with('user');
        $pengaduanQuery = Kontak::query(); 

        if ($isRedaktur) {
            $beritaQuery->where('user_id', $user->id);
            $galeriQuery->where('user_id', $user->id); 
            $pengumumanQuery->where('user_id', $user->id);
            $pengaduanQuery->whereRaw('1 = 0'); 
        }
        
        $beritaActivities = $beritaQuery->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Berita Baru';
            $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-newspaper';
            $item->route = route('admin.berita.index');
            $item->userName = $item->user->name ?? 'Sistem';
            return $item;
        });
        
        $galeriActivities = $galeriQuery->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Galeri Baru';
            $item->judul_aktivitas = $item->judul_kegiatan;
            $item->icon = 'bi-images';
            $item->route = route('admin.galeri.index');
            $item->userName = $item->user->name ?? 'Sistem';
            return $item;
        });

        $pengumumanActivities = $pengumumanQuery->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Pengumuman Baru';
            $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-megaphone';
            $item->route = route('admin.pengumuman.index');
            $item->userName = $item->user->name ?? 'Sistem';
            return $item;
        });

        $pengaduanActivities = $pengaduanQuery->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Pengaduan Baru';
            $item->judul_aktivitas = 'Pesan dari ' . $item->nama;
            $item->icon = 'bi-chat-left-text';
            $item->route = route('admin.pengaduan.index');
            $item->userName = $item->nama;
            return $item;
        });

        $allActivities = $beritaActivities
            ->merge($galeriActivities)
            ->merge($pengumumanActivities)
            ->merge($pengaduanActivities);
        
        $recentActivities = $allActivities->sortByDesc('created_at')->take(5);

        // === 4. QUERY PERINGKAT BERITA (TOP 5 UNTUK DASHBOARD) ===
        $topBeritaUsers = [];
        if (Auth::user()->role == 'admin') { 
            
            $beritaTable = (new Berita)->getTable(); 
            $fotoColumn = 'foto'; 

            $topBeritaUsers = User::select('users.name', "users.{$fotoColumn}", DB::raw("COUNT({$beritaTable}.id) as total_berita"))
                ->join($beritaTable, 'users.id', '=', "{$beritaTable}.user_id")
                ->groupBy('users.id', 'users.name', "users.{$fotoColumn}") 
                ->orderByDesc('total_berita')
                ->take(5) 
                ->get();
        }

        // === 5. KIRIM SEMUA DATA KE VIEW ===
        return view('admin.dashboard', compact(
            'totalBerita', 'totalGaleri', 'totalPengaduan', 'totalPengumuman', 'totalSlider',
            'chartLabels', 'chartData', 'availableYears', 'currentYear', 'availableMonths', 'currentMonth',
            'recentActivities',
            'topBeritaUsers' 
        ));
    }

    /**
     * Method AJAX (getChartData)
     * (Tidak ada perubahan di method ini)
     */
    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $isRedaktur = ($user->role == 'redaktur');
        
        $filter = $request->input('filter', 'bulanan');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $labels = [];
        $data = [];
        $title = 'Grafik Berita ';

        switch ($filter) {
            case 'harian':
                $date = Carbon::create($year, $month, 1);
                $daysInMonth = $date->daysInMonth;
                $monthName = $date->format('F');
                $title .= "Harian ($monthName $year)";
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $labels[] = str_pad($day, 2, '0', STR_PAD_LEFT);
                    $query = Berita::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->whereDay('created_at', $day);
                    if ($isRedaktur) $query->where('user_id', $user->id);
                    $data[] = $query->count();
                }
                break;
            case 'tahunan':
                $currentYear = Carbon::now()->year;
                $earliestDbYear = Berita::min(DB::raw('YEAR(created_at)'));
                $earliestYear = $earliestDbYear ? min($earliestDbYear, $currentYear - 4) : $currentYear - 4;
                $title .= "Tahunan ($earliestYear - $currentYear)";
                for ($y = $earliestYear; $y <= $currentYear; $y++) { 
                    $labels[] = $y;
                    $query = Berita::whereYear('created_at', $y);
                    if ($isRedaktur) $query->where('user_id', $user->id);
                    $data[] = $query->count();
                }
                break;
            case 'bulanan':
            default:
                $title .= "Bulanan (Tahun $year)";
                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = Carbon::create(null, $m)->format('M');
                    $query = Berita::whereYear('created_at', $year)
                        ->whereMonth('created_at', $m);
                    if ($isRedaktur) $query->where('user_id', $user->id);
                    $data[] = $query->count();
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'title' => $title,
        ]);
    }


    /**
     * Menampilkan halaman semua aktivitas (untuk "Lihat Selengkapnya").
     * (Tidak ada perubahan di method ini)
     */
    public function allActivities(Request $request) 
    {
        $user = Auth::user();
        $isRedaktur = ($user->role == 'redaktur');

        $day = $request->input('day');
        $month = $request->input('month');
        $year = $request->input('year');

        // === LOGIKA PENGAMBILAN AKTIVITAS ===
        $beritaQuery = Berita::with('user');
        $galeriQuery = Galeri::with('user');
        $pengumumanQuery = Pengumuman::with('user');
        $pengaduanQuery = Kontak::query(); 

        if ($isRedaktur) {
            $beritaQuery->where('user_id', $user->id);
            $galeriQuery->where('user_id', $user->id); 
            $pengumumanQuery->where('user_id', $user->id);
            $pengaduanQuery->whereRaw('1 = 0'); 
        }

        if ($day) {
            $beritaQuery->whereDay('created_at', $day);
            $galeriQuery->whereDay('created_at', $day);
            $pengumumanQuery->whereDay('created_at', $day);
            $pengaduanQuery->whereDay('created_at', $day);
        }
        if ($month) {
            $beritaQuery->whereMonth('created_at', $month);
            $galeriQuery->whereMonth('created_at', $month);
            $pengumumanQuery->whereMonth('created_at', $month);
            $pengaduanQuery->whereMonth('created_at', $month);
        }
        if ($year) {
            $beritaQuery->whereYear('created_at', $year);
            $galeriQuery->whereYear('created_at', $year);
            $pengumumanQuery->whereYear('created_at', $year);
            $pengaduanQuery->whereYear('created_at', $year);
        }
        
        $beritaActivities = $beritaQuery->latest()->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Berita Baru'; $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-newspaper'; $item->route = route('admin.berita.index');
            $item->userName = $item->user->name ?? 'Sistem'; return $item;
        });

        $galeriActivities = $galeriQuery->latest()->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Galeri Baru'; $item->judul_aktivitas = $item->judul_kegiatan;
            $item->icon = 'bi-images'; $item->route = route('admin.galeri.index');
            $item->userName = $item->user->name ?? 'Sistem'; return $item;
        });

        $pengumumanActivities = $pengumumanQuery->latest()->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Pengumuman Baru'; $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-megaphone'; $item->route = route('admin.pengumuman.index');
            $item->userName = $item->user->name ?? 'Sistem'; return $item;
        });

        $pengaduanActivities = $pengaduanQuery->latest()->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Pengaduan Baru'; $item->judul_aktivitas = 'Pesan dari ' . $item->nama;
            $item->icon = 'bi-chat-left-text'; $item->route = route('admin.pengaduan.index');
            $item->userName = $item->nama; return $item;
        });
        
        $allActivitiesCollection = $beritaActivities
            ->merge($galeriActivities)
            ->merge($pengumumanActivities)
            ->merge($pengaduanActivities)
            ->sortByDesc('created_at'); 

        // === BUAT PAGINATION MANUAL ===
        $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $allActivitiesCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedActivities = new LengthAwarePaginator(
            $currentPageItems,
            $allActivitiesCollection->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $paginatedActivities->appends($request->query());

        $currentYear = Carbon::now()->year;
        $availableYears = range($currentYear, $currentYear - 4);
        $availableMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $availableMonths[] = [
                'value' => $m,
                'name' => Carbon::create(null, $m)->format('F')
            ];
        }
        $filters = ['day' => $day, 'month' => $month, 'year' => $year];

        return view('admin.activities', [
            'allActivities' => $paginatedActivities,
            'availableMonths' => $availableMonths,
            'availableYears' => $availableYears,
            'filters' => $filters
        ]);
    }

    
    // ▼▼▼ METHOD HALAMAN PERINGKAT LENGKAP (DIPERBARUI) ▼▼▼
    /**
     * Menampilkan halaman semua kontributor.
     */
    public function allContributors(Request $request) // <-- 1. Tambahkan Request $request
    {
        // Pastikan hanya admin yang bisa akses
        if (Auth::user()->role != 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // ▼▼▼ 2. Ambil nilai filter dari request ▼▼▼
        $day = $request->input('day');
        $month = $request->input('month');
        $year = $request->input('year');
        // ▲▲▲ Akhir tambahan 2 ▲▲▲

        $beritaTable = (new Berita)->getTable(); 
        $fotoColumn = 'foto';

        // ▼▼▼ 3. Mulai query builder (tanpa ->get()) ▼▼▼
        $query = User::select('users.name', "users.{$fotoColumn}", DB::raw("COUNT({$beritaTable}.id) as total_berita"))
            ->join($beritaTable, 'users.id', '=', "{$beritaTable}.user_id");

        // ▼▼▼ 4. Terapkan filter JIKA ada ▼▼▼
        // Kita perlu `{$beritaTable}.created_at` untuk menghindari error "ambiguous column"
        if ($day) {
            $query->whereDay("{$beritaTable}.created_at", $day);
        }
        if ($month) {
            $query->whereMonth("{$beritaTable}.created_at", $month);
        }
        if ($year) {
            $query->whereYear("{$beritaTable}.created_at", $year);
        }
        // ▲▲▲ Akhir tambahan 4 ▲▲▲

        // ▼▼▼ 5. Selesaikan query dan ->get() ▼▼▼
        $allRankedUsers = $query->groupBy('users.id', 'users.name', "users.{$fotoColumn}") 
            ->orderByDesc('total_berita')
            ->get();
        // ▲▲▲ Akhir perubahan 5 ▲▲▲

        // Pisahkan Top 3 dan sisanya
        $top3Contributors = $allRankedUsers->take(3);
        $remainingContributors = $allRankedUsers->skip(3);

        // ▼▼▼ 6. Siapkan data untuk dropdown filter di view ▼▼▼
        $currentYear = Carbon::now()->year;
        $availableYears = range($currentYear, $currentYear - 4); 
        $availableMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $availableMonths[] = [
                'value' => $m,
                'name' => Carbon::create(null, $m)->format('F')
            ];
        }
        // Simpan filter yang sedang aktif
        $filters = ['day' => $day, 'month' => $month, 'year' => $year];
        // ▲▲▲ Akhir tambahan 6 ▲▲▲
        
        // ▼▼▼ 7. Kirim data baru ke view ▼▼▼
        return view('admin.contributors', compact(
            'top3Contributors', 
            'remainingContributors', 
            'fotoColumn',
            'availableMonths',      // <-- Data baru
            'availableYears',       // <-- Data baru
            'filters'               // <-- Data baru
        ));
        // ▲▲▲ Akhir perubahan 7 ▲▲▲
    }
    // ▲▲▲ AKHIR METHOD BARU ▲▲A
}