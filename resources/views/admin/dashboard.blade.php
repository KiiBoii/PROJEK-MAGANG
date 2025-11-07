@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Dashboard Admin</h3>

    <div class="row">
        {{-- Card Statistik --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Ganti angka statis dengan variabel --}}
                            <div class="fs-5 fw-bold">{{ $totalBerita }}</div>
                            <div class="text-uppercase small">Berita</div>
                        </div>
                        <i class="bi bi-newspaper fs-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    <a href="{{ route('berita.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Ganti angka statis dengan variabel --}}
                            <div class="fs-5 fw-bold">{{ $totalGaleri }}</div>
                            <div class="text-uppercase small">Galeri Foto</div>
                        </div>
                        <i class="bi bi-images fs-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    <a href="{{ route('galeri.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Ganti angka statis dengan variabel --}}
                            <div class="fs-5 fw-bold">{{ $totalPengaduan }}</div>
                            <div class="text-uppercase small">Pengaduan</div>
                        </div>
                        <i class="bi bi-chat-left-text fs-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    <a href="{{ route('pengaduan.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Ganti angka statis dengan variabel --}}
                            <div class="fs-5 fw-bold">{{ $totalPengumuman }}</div>
                            <div class="text-uppercase small">Pengumuman</div>
                        </div>
                        <i class="bi bi-megaphone fs-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    <a href="{{ route('pengumuman.index') }}" class="text-white text-decoration-none small stretched-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Grafik --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Grafik Berita Dibuat (6 Bulan Terakhir)</div>
                <div class="card-body" style="position: relative; height: 350px;">
                    <canvas id="contentChart"></canvas> 
                </div>
            </div>
        </div>

        {{-- === KARTU AKTIVITAS DIPERBARUI === --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Aktivitas Terbaru</div>
                <div class="card-body">
                    {{-- Ganti <ul> statis dengan loop dinamis --}}
                    <ul class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi {{ $activity->icon }} fs-4 text-primary me-3"></i>
                                <div>
                                    <a href="{{ $activity->route }}" class="text-decoration-none text-dark stretched-link">
                                        {{-- Tampilkan nama user (dari $activity->userName) --}}
                                        <strong>{{ $activity->userName }}</strong> 
                                        {{ $activity->jenis_aktivitas == 'Pengaduan Baru' ? 'mengirim' : 'menambahkan' }} 
                                        <em>{{ Str::limit($activity->judul_aktivitas, 35) }}</em>
                                    </a>
                                    <small class="d-block text-muted">
                                        {{-- Menampilkan waktu (misal: "5 menit yang lalu") --}}
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
                </div>
            </div>
        </div>
        {{-- === AKHIR KARTU AKTIVITAS === --}}
    </div>

</div>
@endsection

@push('scripts')
{{-- 1. Load Chart.js (PENTING) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Mengambil canvas
        const ctx = document.getElementById('contentChart').getContext('2d');
        
        // 2. Mengambil data dari Controller (Blade -> JS)
        const labels = {!! json_encode($chartLabels) !!};
        const chartData = {!! json_encode($chartData) !!};

        // 3. Konfigurasi data chart
        const data = {
            labels: labels,
            datasets: [{
                label: 'Jumlah Berita Dibuat',
                data: chartData, // <-- Data dinamis
                fill: true,
                borderColor: 'rgb(0, 123, 255)',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.1
            }]
        };

        // 4. Konfigurasi opsi chart
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        // Memastikan sumbu Y hanya menampilkan angka bulat (1, 2, 3)
                        ticks: {
                            stepSize: 1 
                        }
                    }
                }
            }
        };

        // 5. Membuat chart
        const myChart = new Chart(
            ctx,
            config
        );
    });
</script>
@endpush