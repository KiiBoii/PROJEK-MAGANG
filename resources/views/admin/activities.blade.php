@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Semua Aktivitas Terbaru</h3>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Daftar Aktivitas</div>
                <div class="card-body">

                    {{-- ▼▼▼ FORM FILTER ▼▼▼ --}}
                    <form method="GET" action="{{ route('admin.dashboard.activities') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label for="day" class="form-label small">Hari</label>
                                <select name="day" id="day" class="form-select form-select-sm">
                                    <option value="">Semua Hari</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ $filters['day'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="month" class="form-label small">Bulan</label>
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
                                <label for="year" class="form-label small">Tahun</label>
                                <select name="year" id="year" class="form-select form-select-sm">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" {{ $filters['year'] == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                                <a href="{{ route('admin.dashboard.activities') }}" class="btn btn-outline-secondary btn-sm ms-2" title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                    <hr>
                    {{-- ▲▲▲ AKHIR FORM FILTER ▲▲▲ --}}


                    <ul class="list-group list-group-flush">
                        {{-- Loop data yang sudah dipaginasi --}}
                        @forelse($allActivities as $activity)
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi {{ $activity->icon }} fs-4 text-primary me-3"></i>
                                <div>
                                    <a href="{{ $activity->route }}" class="text-decoration-none text-dark stretched-link">
                                        <strong>{{ $activity->userName }}</strong> 
                                        {{ $activity->jenis_aktivitas == 'Pengaduan Baru' ? 'mengirim' : 'menambahkan' }} 
                                        <em>{{ Str::limit($activity->judul_aktivitas, 50) }}</em>
                                    </a>
                                    <small class="d-block text-muted">
                                        {{ $activity->created_at->diffForHumans() }} 
                                        ({{ $activity->created_at->format('d M Y, H:i') }})
                                    </small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                {{-- ▼▼▼ Pesan disesuaikan jika ada filter ▼▼▼ --}}
                                @if(request()->has('day') || request()->has('month') || request()->has('year'))
                                    Tidak ada aktivitas yang ditemukan untuk filter ini.
                                @else
                                    Belum ada aktivitas terbaru.
                                @endif
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Tampilkan Link Pagination (LAMA DIHAPUS) --}}
                {{-- Blok @if ($allActivities->hasPages()) ... @endif telah dihapus dari sini --}}

            </div>
        </div>
    </div>

    {{-- ▼▼▼ PERUBAHAN: PAGINATION CUSTOM DITAMBAHKAN DI SINI ▼▼▼ --}}
    {{-- 🔸 GUNAKAN PAGINATION CUSTOM --}}
    <div class="d-flex justify-content-center mt-4">
        {{-- Menggunakan variabel $allActivities --}}
        {!! $allActivities->withQueryString()->links('vendor.pagination.custom-circle') !!}
    </div>
    {{-- ▲▲▲ AKHIR PAGINATION CUSTOM ▲▲▲ --}}

</div>
@endsection