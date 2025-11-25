@extends('layouts.admin')

{{-- Tambahkan CSS kustom jika perlu untuk medali --}}
@push('styles')
<style>
    .rank-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 1rem;
        font-weight: bold;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .rank-1 { background-color: #ffc107; color: #000 !important; } /* Emas */
    .rank-2 { background-color: #c0c0c0; color: #000 !important; } /* Perak */
    .rank-3 { background-color: #cd7f32; } /* Perunggu */

    
    /* ▼▼▼ CSS UNTUK EFEK PODIUM (tetap sama) ▼▼▼ */
    .rank-podium {
        /* Selaraskan kolom ke bawah agar efek "lebih tinggi" terlihat */
        align-items: flex-end;
        /* Beri sedikit ruang di atas agar card-1 tidak terpotong */
        padding-top: 1.5rem; 
        min-height: 250px; /* Minimal tinggi agar efek podium selalu ada */
    }

    .card-rank-1 {
        /* Pastikan kolom #1 berada di atas #2 dan #3 */
        z-index: 10;
    }

    .card-rank-1 .card {
        /* Buat kartu 5% lebih besar */
        transform: scale(1.05); 
        /* Beri bayangan yang lebih menonjol */
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        /* Pastikan card-nya sendiri juga di atas */
        z-index: 10;
    }

    .card-rank-2 .card,
    .card-rank-3 .card {
        /* Pastikan kartu samping berada di "belakang" kartu utama */
        z-index: 1;
    }

    /* Untuk placeholder jika tidak ada rank 2 atau 3 */
    .rank-placeholder {
        visibility: hidden; /* Sembunyikan tapi tetap ambil ruang */
    }
    /* ▲▲▲ AKHIR CSS UNTUK EFEK PODIUM ▲▲▲ */

</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Peringkat Kontributor Berita</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <p class="text-muted">Peringkat diurutkan berdasarkan jumlah total berita yang telah di-upload.</p>
    
    {{-- ▼▼▼ BLOK FILTER BARU ▼▼▼ --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.dashboard.contributors') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="day" class="form-label small">Filter per Hari</label>
                        <select name="day" id="day" class="form-select form-select-sm">
                            <option value="">Semua Hari</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ $filters['day'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="month" class="form-label small">Filter per Bulan</label>
                        <select name="month" id="month" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach ($availableMonths as $month)
                                <option value="{{ $month['value'] }}" {{ $filters['month'] == $month['value'] ? 'selected' : '' }}>
                                    {{ $month['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="year" class="form-label small">Filter per Tahun</label>
                        <select name="year" id="year" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" {{ $filters['year'] == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary btn-sm w-100 me-2">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('admin.dashboard.contributors') }}" class="btn btn-outline-secondary btn-sm w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- ▲▲▲ AKHIR BLOK FILTER ▲▲▲ --}}


    {{-- ▼▼▼ BAGIAN TOP 3 (KARTU) - Disesuaikan untuk posisi tengah ▼▼▼ --}}
    <div class="row justify-content-center mb-4 rank-podium">
        
        {{-- Slot untuk Rank 2 --}}
        <div class="col-lg-4 col-md-6 mb-4 order-1 card-rank-2">
            @php $user2 = $top3Contributors->get(1); @endphp {{-- Ambil data rank 2 --}}
            @if($user2)
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4" style="position: relative;">
                        {{-- Medali Peringkat --}}
                        <span class="rank-badge rank-2">#2</span>
                        {{-- Foto Profil --}}
                        @if($user2->$fotoColumn)
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($user2->$fotoColumn) }}" alt="{{ $user2->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #c0c0c0;">
                        @else
                            <span class="badge bg-primary rounded-circle mb-3 d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 3rem; border: 4px solid #eee;">
                                {{ strtoupper(substr($user2->name, 0, 1)) }}
                            </span>
                        @endif
                        {{-- Nama dan Total --}}
                        <h5 class="card-title mb-1">{{ $user2->name }}</h5>
                        <p class="text-muted small">
                            Kontribusi 
                            @if($filters['day'] || $filters['month'] || $filters['year'])
                                (Difilter)
                            @else
                                (Total)
                            @endif
                        </p>
                        <h2 class="fw-bold text-primary">{{ $user2->total_berita }}</h2>
                    </div>
                </div>
            @else
                {{-- Placeholder jika tidak ada kontributor rank 2 --}}
                <div class="card h-100 rank-placeholder">
                    <div class="card-body"></div>
                </div>
            @endif
        </div>

        {{-- Slot untuk Rank 1 (Tengah) --}}
        <div class="col-lg-4 col-md-6 mb-4 order-2 card-rank-1">
            @php $user1 = $top3Contributors->first(); @endphp {{-- Ambil data rank 1 --}}
            @if($user1)
                <div class="card h-100 shadow-sm border-warning border-3">
                    <div class="card-body text-center p-4" style="position: relative;">
                        {{-- Medali Peringkat --}}
                        <span class="rank-badge rank-1"><i class="bi bi-trophy-fill"></i></span>
                        {{-- Foto Profil --}}
                        @if($user1->$fotoColumn)
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($user1->$fotoColumn) }}" alt="{{ $user1->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #ffc107;">
                        @else
                            <span class="badge bg-primary rounded-circle mb-3 d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 3rem; border: 4px solid #eee;">
                                {{ strtoupper(substr($user1->name, 0, 1)) }}
                            </span>
                        @endif
                        {{-- Nama dan Total --}}
                        <h5 class="card-title mb-1">{{ $user1->name }}</h5>
                        <p class="text-muted small">
                            Kontribusi 
                            @if($filters['day'] || $filters['month'] || $filters['year'])
                                (Difilter)
                            @else
                                (Total)
                            @endif
                        </p>
                        <h2 class="fw-bold text-primary">{{ $user1->total_berita }}</h2>
                    </div>
                </div>
            @else
                {{-- Placeholder jika tidak ada kontributor rank 1 --}}
                <div class="card h-100 text-center text-muted d-flex align-items-center justify-content-center border-0 shadow-sm">
                    <p>
                        @if($filters['day'] || $filters['month'] || $filters['year'])
                            Tidak ada kontributor pada periode ini.
                        @else
                            Belum ada kontributor.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        {{-- Slot untuk Rank 3 --}}
        <div class="col-lg-4 col-md-6 mb-4 order-3 card-rank-3">
            @php $user3 = $top3Contributors->get(2); @endphp {{-- Ambil data rank 3 --}}
            @if($user3)
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4" style="position: relative;">
                        {{-- Medali Peringkat --}}
                        <span class="rank-badge rank-3">#3</span>
                        {{-- Foto Profil --}}
                        @if($user3->$fotoColumn)
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($user3->$fotoColumn) }}" alt="{{ $user3->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #cd7f32;">
                        @else
                            <span class="badge bg-primary rounded-circle mb-3 d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 3rem; border: 4px solid #eee;">
                                {{ strtoupper(substr($user3->name, 0, 1)) }}
                            </span>
                        @endif
                        {{-- Nama dan Total --}}
                        <h5 class="card-title mb-1">{{ $user3->name }}</h5>
                        <p class="text-muted small">
                            Kontribusi 
                            @if($filters['day'] || $filters['month'] || $filters['year'])
                                (Difilter)
                            @else
                                (Total)
                            @endif
                        </p>
                        <h2 class="fw-bold text-primary">{{ $user3->total_berita }}</h2>
                    </div>
                </div>
            @else
                {{-- Placeholder jika tidak ada kontributor rank 3 --}}
                <div class="card h-100 rank-placeholder">
                    <div class="card-body"></div>
                </div>
            @endif
        </div>
    </div>
    {{-- ▲▲▲ AKHIR BAGIAN TOP 3 ▲▲▲ --}}

    {{-- ▼▼▼ BAGIAN SISA PERINGKAT (DAFTAR) - tetap sama ▼▼▼ --}}
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <span class="fw-bold">Peringkat Selanjutnya (4+)</span>
            @if($filters['day'] || $filters['month'] || $filters['year'])
                <span class="badge bg-info text-dark">Menampilkan Hasil Filter</span>
            @endif
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($remainingContributors as $index => $user)
                    @php $rank = $index + 4; @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary rounded-pill me-3" style="width: 35px; height: 35px; font-size: 1rem; display: flex; align-items: center; justify-content: center;">{{ $rank }}</span>
                            
                            @if($user->$fotoColumn)
                                {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                                <img src="{{ asset($user->$fotoColumn) }}" alt="{{ $user->name }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <span class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                            
                            <strong class="fs-5">{{ $user->name }}</strong>
                        </div>
                        <span class="fw-bold fs-5 text-dark">{{ $user->total_berita }} <small class="text-muted">Kontribusi</small></span>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">
                        @if($filters['day'] || $filters['month'] || $filters['year'])
                            Tidak ada kontributor lain pada periode ini.
                        @else
                            Tidak ada kontributor lain.
                        @endif
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
@endsection