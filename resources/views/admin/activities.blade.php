@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Semua Aktivitas Terbaru</h3>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Daftar Aktivitas</h5>
                </div>
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
                        @forelse($allActivities as $activity)
                            <li class="list-group-item d-flex align-items-center py-3">
                                <div class="me-3">
                                    <i class="bi {{ $activity->icon }} fs-3 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ $activity->route }}" class="text-decoration-none text-dark stretched-link fw-bold">
                                            {{ $activity->userName }}
                                        </a>
                                        <small class="text-muted">{{ $activity->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div class="text-muted small">
                                        {{ $activity->jenis_aktivitas == 'Pengaduan Baru' ? 'mengirim' : 'menambahkan' }} 
                                        <span class="fst-italic">"{{ Str::limit($activity->judul_aktivitas, 80) }}"</span>
                                    </div>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                @if(request()->has('day') || request()->has('month') || request()->has('year'))
                                    Tidak ada aktivitas yang ditemukan untuk filter ini.
                                @else
                                    Belum ada aktivitas terbaru.
                                @endif
                            </li>
                        @endforelse
                    </ul>

                </div>
                
                {{-- 🔸 GUNAKAN PAGINATION CUSTOM --}}
                @if($allActivities->hasPages())
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-center py-3">
                        {!! $allActivities->withQueryString()->links('vendor.pagination.custom-circle') !!}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection