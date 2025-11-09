<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Kontak;
use App\Models\Pengumuman;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // === 1. DATA CARD STATISTIK ===
        $totalBerita = Berita::count();
        $totalGaleri = Galeri::count();
        $totalPengaduan = Kontak::count();
        $totalPengumuman = Pengumuman::count();
        $totalSlider = Slider::count();

        // === 2. DATA UNTUK GRAFIK ===
        
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;


;

        $earliestYear = $currentYear - 4; // Ini akan membuat range 5 tahun (misal: 2025 - 2021)

        
        // Buat range tahun (misal: [2025, 2024, 2023, 2022, 2021])
        $availableYears = range($currentYear, $earliestYear); 
        
        $availableMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $availableMonths[] = [
                'value' => $m,
                'name' => Carbon::create(null, $m)->format('F')
            ];
        }

        // Siapkan data default untuk chart (Bulanan, Tahun Ini)
        $chartLabels = [];
        $chartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $chartLabels[] = Carbon::create(null, $month)->format('M');
            $chartData[] = Berita::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
        }

        // === 3. DATA AKTIVITAS TERBARU ===
        $beritaActivities = Berita::with('user')->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Berita Baru';
            $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-newspaper';
            $item->route = route('berita.index');
            $item->userName = $item->user->name ?? 'Sistem';
            return $item;
        });

        $galeriActivities = Galeri::with('user')->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Galeri Baru';
            $item->judul_aktivitas = $item->judul_kegiatan;
            $item->icon = 'bi-images';
            $item->route = route('galeri.index');
            $item->userName = $item->user->name ?? 'Sistem';
            return $item;
        });

        $pengumumanActivities = Pengumuman::with('user')->latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Pengumuman Baru';
            $item->judul_aktivitas = $item->judul;
            $item->icon = 'bi-megaphone';
            $item->route = route('pengumuman.index');
            $item->userName = $item->user->name ?? 'Sistem';
            return $item;
        });

        $pengaduanActivities = Kontak::latest()->take(5)->get()->map(function ($item) {
            $item->jenis_aktivitas = 'Pengaduan Baru';
            $item->judul_aktivitas = 'Pesan dari ' . $item->nama;
            $item->icon = 'bi-chat-left-text';
            $item->route = route('pengaduan.index');
            $item->userName = $item->nama;
            return $item;
        });

        $allActivities = $beritaActivities
            ->merge($galeriActivities)
            ->merge($pengumumanActivities)
            ->merge($pengaduanActivities);
        
        $recentActivities = $allActivities->sortByDesc('created_at')->take(5);

        // === 4. KIRIM SEMUA DATA KE VIEW ===
        return view('admin.dashboard', compact(
            'totalBerita',
            'totalGaleri',
            'totalPengaduan',
            'totalPengumuman',
            'totalSlider',
            'chartLabels',
            'chartData',
            'availableYears',
            'currentYear',
            'availableMonths',
            'currentMonth',
            'recentActivities' 
        ));
    }

    /**
     * Method AJAX (getChartData)
     * (Tidak perlu diubah)
     */
    public function getChartData(Request $request)
    {
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
                    $data[] = Berita::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->whereDay('created_at', $day)
                        ->count();
                }
                break;

            case 'tahunan':
                // PERBARUAN: Logika Tahunan di controller AJAX juga harus disamakan
                $currentYear = Carbon::now()->year;
                // $earliestYear = $currentYear - 4; // Ambil 5 tahun
                // Ambil tahun terlama dari DB, atau paksa 5 tahun jika tidak ada
                $earliestDbYear = Berita::min(DB::raw('YEAR(created_at)'));
                $earliestYear = $earliestDbYear ? min($earliestDbYear, $currentYear - 4) : $currentYear - 4;
                
                $title .= "Tahunan ($earliestYear - $currentYear)";

                // Dibuat dinamis dari tahun terlama
                for ($y = $earliestYear; $y <= $currentYear; $y++) { 
                    $labels[] = $y;
                    $data[] = Berita::whereYear('created_at', $y)->count();
                }
                break;
            
            case 'bulanan':
            default:
                $title .= "Bulanan (Tahun $year)";
                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = Carbon::create(null, $m)->format('M');
                    $data[] = Berita::whereYear('created_at', $year)
                        ->whereMonth('created_at', $m)
                        ->count();
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'title' => $title,
        ]);
    }
}