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
                            <div class="fs-5 fw-bold">120</div>
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
                            <div class="fs-5 fw-bold">85</div>
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
                            <div class="fs-5 fw-bold">30</div>
                            <div class="text-uppercase small">Pengaduan Baru</div>
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
                            <div class="fs-5 fw-bold">15</div>
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
        {{-- === INI PERBAIKANNYA === --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Grafik Data Pengunjung (Contoh)</div>
                {{-- Mengganti 'min-height: 250px' menjadi 'height: 350px' --}}
                <div class="card-body" style="position: relative; height: 350px;">
                    <canvas id="visitorChart"></canvas> 
                </div>
            </div>
        </div>

        {{-- Card Aktivitas Terbaru (Placeholder) --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Aktivitas Terbaru</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Admin X mengunggah berita baru "Hari Kemerdekaan". <small class="text-muted float-end">Baru saja</small></li>
                        <li class="list-group-item">Pengaduan baru dari Budi Santoso. <small class="text-muted float-end">1 jam lalu</small></li>
                        <li class="list-group-item">Admin Y memperbarui galeri kegiatan. <small class="text-muted float-end">Kemarin</small></li>
                        <li class="list-group-item">Pengumuman baru: "Libur Nasional". <small class="text-muted float-end">2 hari lalu</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- === SCRIPT UNTUK DUMMY DATA (BIARKAN SEPERTI INI) === --}}
@push('scripts')
<script>
    // Pastikan script berjalan setelah DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        
        // Mengambil canvas
        const ctx = document.getElementById('visitorChart').getContext('2d');
        
        // Data dummy
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: [65, 59, 80, 81, 56, 55, 40], // Data dummy
                fill: true, // Memberi warna di bawah garis
                borderColor: 'rgb(0, 123, 255)', // Warna garis (biru)
                backgroundColor: 'rgba(0, 123, 255, 0.1)', // Warna area di bawah garis
                tension: 0.1 // Membuat garis sedikit melengkung
            }]
        };

        // Konfigurasi chart
        const config = {
            type: 'line', // Tipe chart: line
            data: data,
            options: {
                responsive: true, // Membuat chart responsif
                maintainAspectRatio: false, // Membiarkan chart mengisi container
                scales: {
                    y: {
                        beginAtZero: true // Mulai sumbu Y dari 0
                    }
                }
            }
        };

        // Membuat chart
        const myChart = new Chart(
            ctx,
            config
        );
    });
</script>
@endpush