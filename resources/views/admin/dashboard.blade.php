@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Dashboard Admin</h3>

    <div class="row">
        {{-- Card Berita (Selalu Tampil) --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-5 fw-bold">{{ $totalBerita }}</div>
                            <div class="text-uppercase small">Berita</div>
                        </div>
                        <i class="bi bi-newspaper fs-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    {{-- ▼▼▼ PERBAIKAN 1 ▼▼▼ --}}
                    <a href="{{ route('admin.berita.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        {{-- Tampilkan card berikut HANYA jika BUKAN redaktur --}}
        @if(Auth::user()->role != 'redaktur')
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-5 fw-bold">{{ $totalGaleri }}</div><div class="text-uppercase small">Galeri Foto</div></div><i class="bi bi-images fs-2"></i>
                    </div>
                </div>
                 <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    {{-- ▼▼▼ PERBAIKAN 2 ▼▼▼ --}}
                    <a href="{{ route('admin.galeri.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                 </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-5 fw-bold">{{ $totalPengaduan }}</div><div class="text-uppercase small">Pengaduan</div></div><i class="bi bi-chat-left-text fs-2"></i>
                    </div>
                </div>
                 <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    {{-- ▼▼▼ PERBAIKAN 3 ▼▼▼ --}}
                    <a href="{{ route('admin.pengaduan.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                 </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-5 fw-bold">{{ $totalPengumuman }}</div><div class="text-uppercase small">Pengumuman</div></div><i class="bi bi-megaphone fs-2"></i>
                    </div>
                </div>
                 <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    {{-- ▼▼▼ PERBAIKAN 4 ▼▼▼ --}}
                    <a href="{{ route('admin.pengumuman.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                 </div>
            </div>
        </div>

        @endif 
        {{-- ▲▲▲ AKHIR KONDISI ▲▲▲ --}}

    </div>

    <div class="row">
        {{-- Grafik --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <span id="chartTitle" class="fw-bold">Grafik Berita Bulanan (Tahun {{ $currentYear }})</span>
                    
                    <div class="row g-2 mt-2">
                        <div class="col-md-4">
                            <label for="chartFilterType" class="form-label small">Tipe Filter</label>
                            <select class="form-select form-select-sm" id="chartFilterType">
                                <option value="bulanan" selected>Bulanan (per Tahun)</option>
                                <option value="harian">Harian (per Bulan)</option>
                                <option value="tahunan">Tahunan (Semua)</option>
                            </select>
                        </div>
                        
                        {{-- Wrapper untuk dropdown Bulan --}}
                        <div class="col-md-3" id="monthFilterWrapper" style="display: none;">
                            <label for="chartMonth" class="form-label small">Bulan</label>
                            <select class="form-select form-select-sm" id="chartMonth">
                                
                                @foreach ($availableMonths as $month)
                                    <option value="{{ $month['value'] }}" {{ $month['value'] == $currentMonth ? 'selected' : '' }}>
                                        {{ $month['name'] }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- Wrapper untuk dropdown Tahun --}}
                        <div class="col-md-3" id="yearFilterWrapper">
                            <label for="chartYear" class="form-label small">Tahun</label>
                            <select class="form-select form-select-sm" id="chartYear">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary btn-sm w-100" id="applyChartFilter">Lihat</button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body" style="position: relative; height: 350px;">
                    <canvas id="contentChart"></canvas> 
                </div>
            </div>
        </div>

        {{-- ▼▼▼ KARTU AKTIVITAS (Tidak ada perubahan di sini, sudah benar) ▼▼▼ --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span id="activityCardTitle" class="fw-bold">Aktivitas Terbaru</span>
                    
                    @if(Auth::user()->role == 'admin')
                        <button class="btn btn-outline-primary btn-sm" id="toggleActivityRankBtn" 
                                data-bs-toggle="tooltip" title="Lihat Peringkat Berita">
                            <i class="bi bi-bar-chart-line"></i> Peringkat
                        </button>
                    @endif
                </div>

                <div class="card-body p-0" style="overflow: hidden; position: relative;">
                    
                    <div class="activity-flipper" style="display: flex; width: 200%; transition: transform 0.4s ease-in-out;">
                        
                        {{-- Panel 1: Aktivitas (Konten Asli) --}}
                        <div class="activity-panel" style="width: 50%; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;"> 
                            {{-- Daftar Aktivitas --}}
                            <ul class="list-group list-group-flush">
                                @forelse($recentActivities as $activity)
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi {{ $activity->icon }} fs-4 text-primary me-3"></i>
                                        <div>
                                            {{-- $activity->route sudah benar (dari controller) --}}
                                            <a href="{{ $activity->route }}" class="text-decoration-none text-dark stretched-link">
                                                <strong>{{ $activity->userName }}</strong> 
                                                {{ $activity->jenis_aktivitas == 'Pengaduan Baru' ? 'mengirim' : 'menambahkan' }} 
                                                <em>{{ Str::limit($activity->judul_aktivitas, 35) }}</em>
                                            </a>
                                            <small class="d-block text-muted">
                                                {{ $activity->created_at->diffForHumans() }} 
                                            </small>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">
                                        Belum ada aktivitas terbaru.
                                    </li>
                                @endforelse
                            </ul>
                            
                            {{-- Footer "Lihat Selengkapnya" (Aktivitas) --}}
                            <div class="text-center pt-3">
                                {{-- Route ini sudah benar --}}
                                <a href="{{ route('admin.dashboard.activities') }}" class="small text-decoration-none">
                                    Lihat Semua Aktivitas <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Panel 2: Peringkat Berita (Konten Baru) --}}
                        <div class="ranking-panel" style="width: 50%; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;">
                            {{-- Daftar Peringkat --}}
                            <ul class="list-group list-group-flush">
                                @if(Auth::user()->role == 'admin' && isset($topBeritaUsers))
                                    @forelse($topBeritaUsers as $index => $rank)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            
                                            @php $fotoColumn = 'foto'; @endphp

                                            @if($rank->$fotoColumn)
                                                {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                                                <img src="{{ asset($rank->$fotoColumn) }}" alt="{{ $rank->name }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <span class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    {{ strtoupper(substr($rank->name, 0, 1)) }}
                                                </span>
                                            @endif

                                            <div>
                                                <strong>{{ $rank->name }}</strong>
                                                <small class="d-block text-muted">Total Upload</small>
                                            </div>
                                        </div>
                                        <span class="fw-bold fs-5">{{ $rank->total_berita }}</span>
                                    </li>
                                    @empty
                                    <li class="list-group-item text-center text-muted">
                                        Belum ada data peringkat.
                                    </li>
                                    @endforelse
                                @endif
                            </ul>

                            {{-- Footer "Lihat Selengkapnya" (Peringkat) --}}
                            <div class="text-center pt-3">
                                {{-- Route ini sudah benar --}}
                                <a href="{{ route('admin.dashboard.contributors') }}" class="small text-decoration-none">
                                    Lihat Peringkat Penuh <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                    </div> 
                </div> 

                {{-- Footer Asli (Dihapus oleh JS) --}}
                <div class="card-footer text-center bg-white border-top-0 pt-0" id="activityFooter" style="display: none;">
                    {{-- Dikosongkan, karena link sudah dipindah ke dalam panel --}}
                </div>
                
            </div>
        </div>
        {{-- ▲▲▲ AKHIR MODIFIKASI KARTU AKTIVITAS ▲▲▲ --}}

    </div>

</div>
@endsection

{{-- Skrip JavaScript (Tidak ada perubahan di sini, tetap sama) --}}
@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // ▼▼▼ SCRIPT UNTUK KARTU GESER (FLIP) AKTIVITAS ▼▼▼
        const toggleBtn = document.getElementById('toggleActivityRankBtn');
        const flipper = document.querySelector('.activity-flipper');
        const activityTitle = document.getElementById('activityCardTitle');

        if (toggleBtn && flipper && activityTitle) {
            let isFlipped = false; 

            toggleBtn.addEventListener('click', function() {
                isFlipped = !isFlipped; 

                if (isFlipped) {
                    // Tampilkan Peringkat
                    flipper.style.transform = 'translateX(-50%)'; 
                    activityTitle.textContent = 'Top 5 Kontributor Berita';
                    this.innerHTML = '<i class="bi bi-clock-history"></i> Aktivitas'; 
                    this.setAttribute('data-bs-original-title', 'Lihat Aktivitas Terbaru'); 

                } else {
                    // Tampilkan Aktivitas
                    flipper.style.transform = 'translateX(0%)'; 
                    activityTitle.textContent = 'Aktivitas Terbaru';
                    this.innerHTML = '<i class="bi bi-bar-chart-line"></i> Peringkat'; 
                    this.setAttribute('data-bs-original-title', 'Lihat Peringkat Berita'); 
                }

                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    const oldTooltip = bootstrap.Tooltip.getInstance(this);
                    if (oldTooltip) {
                        oldTooltip.dispose();
                    }
                    new bootstrap.Tooltip(this);
                }
            });
            
            const activityFooter = document.getElementById('activityFooter');
            if(activityFooter) {
                activityFooter.remove(); // Hapus footer asli
            }
        }
        // ▲▲▲ AKHIR DARI SCRIPT KARTU GESER ▲▲▲


        // --- Script Chart (Kode Asli Anda) ---
        const chartCanvas = document.getElementById('contentChart');
        if (chartCanvas) { 

            const ctx = chartCanvas.getContext('2d'); 
            const chartTitleElem = document.getElementById('chartTitle'); 
            
            const filterTypeSelect = document.getElementById('chartFilterType');
            const monthSelect = document.getElementById('chartMonth');
            const yearSelect = document.getElementById('chartYear');
            const monthWrapper = document.getElementById('monthFilterWrapper');
            const yearWrapper = document.getElementById('yearFilterWrapper');
            const applyButton = document.getElementById('applyChartFilter');

            const initialLabels = {!! json_encode($chartLabels) !!};
            const initialData = {!! json_encode($chartData) !!};

            const chartConfig = {
                type: 'line',
                data: {
                    labels: initialLabels,
                    datasets: [{
                        label: 'Jumlah Berita Dibuat',
                        data: initialData,
                        fill: true,
                        borderColor: 'rgb(0, 123, 255)',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 } 
                        }
                    }
                }
            };

            let myChart = new Chart(ctx, chartConfig);

            async function fetchAndUpdateChart() {
                const filter = filterTypeSelect.value;
                const month = monthSelect.value;
                const year = yearSelect.value;

                const queryParams = new URLSearchParams({
                    filter: filter,
                    month: month,
                    year: year
                });
                
                try {
                    chartTitleElem.textContent = 'Memuat data...'; 
                    applyButton.disabled = true; 
                    
                    // Route ini sudah benar (admin.dashboard.chartData)
                    const response = await fetch(`{{ route('admin.dashboard.chartData') }}?${queryParams}`);
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data dari server.');
                    }
                    
                    const newData = await response.json();

                    myChart.data.labels = newData.labels;
                    myChart.data.datasets[0].data = newData.data;
                    myChart.update();
                    chartTitleElem.textContent = newData.title; 

                } catch (error) {
                    console.error('Error fetching chart data:', error);
                    chartTitleElem.textContent = 'Gagal memuat data chart.'; 
                } finally {
                    applyButton.disabled = false; 
                }
            }

            applyButton.addEventListener('click', fetchAndUpdateChart);

            filterTypeSelect.addEventListener('change', function () {
                const selectedFilter = this.value;
                
                if (selectedFilter === 'harian') {
                    monthWrapper.style.display = 'block';
                    yearWrapper.style.display = 'block';
                } else if (selectedFilter === 'bulanan') {
                    monthWrapper.style.display = 'none';
                    yearWrapper.style.display = 'block';
                } else if (selectedFilter === 'tahunan') {
                    monthWrapper.style.display = 'none';
                    yearWrapper.style.display = 'none';
                }
            });

        } 
    });
</script>
@endpush