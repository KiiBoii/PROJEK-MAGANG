@extends('layouts.admin')

@push('styles')
<style>
    /* Perbarui style gambar agar pas di card (Sama seperti sebelumnya) */
    .slider-card-img {
        width: 100%;
        height: 200px; /* Atur tinggi gambar card */
        object-fit: cover;
        border-top-left-radius: 0.375rem; /* Sesuaikan dengan radius card Bootstrap */
        border-top-right-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- ▼▼▼ PERUBAHAN 1: HEADER & TOMBOL (Gaya "Berita") ▼▼▼ --}}
    <h3 class="mb-4">Manajemen Slider</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">List Slide</h4>
        <a href="{{ route('admin.slider.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Slide Baru
        </a>
    </div>
    {{-- ▲▲▲ AKHIR PERUBAHAN 1 ▲▲▲ --}}


    {{-- ▼▼▼ PERUBAHAN 2: FILTER DROPDOWN (Gaya "Berita") ▼▼▼ --}}
    <div class="d-flex mb-3">
        <div class="dropdown me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuHalaman" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-tag me-1"></i> 
                {{-- Logika ini diambil dari controller Anda, sudah benar --}}
                {{ $selectedHalaman ? ucfirst($selectedHalaman) : 'Tag Halaman' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuHalaman">
                {{-- Loop dari $halamanList (dari Controller) --}}
                @foreach ($halamanList as $halaman)
                    <li>
                        {{-- Menggunakan array_merge seperti di file Berita --}}
                        <a class="dropdown-item" href="{{ route('admin.slider.index', array_merge(request()->query(), ['halaman' => $halaman])) }}">
                            {{ ucfirst($halaman) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Tombol Reset (Seperti di file Berita) --}}
        @if($selectedHalaman)
        <a href="{{ route('admin.slider.index') }}" class="btn btn-light">
            <i class="bi bi-x-circle"></i> Reset Filter
        </a>
        @endif
    </div>
    {{-- ▲▲▲ AKHIR PERUBAHAN 2 ▲▲▲ --}}


    {{-- === KONTEN KARTU (Dengan Tata Letak Baru) === --}}
    <div class="row">
        @forelse ($sliders as $slide)
            <div class="col-lg-4 col-md-6 mb-4">
                {{-- Menambahkan class shadow-sm, rounded-3, border-0 dari file lama Anda --}}
                <div class="card h-100 shadow-sm rounded-3 border-0">
                    
                    <img src="{{ asset('storage/' . $slide->gambar) }}" alt="{{ $slide->judul }}" class="slider-card-img">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $slide->judul }}</h5>
                        
                        {{-- ▼▼▼ PERUBAHAN 3: Tata Letak Card (Gaya "Berita") ▼▼▼ --}}

                        {{-- Baris untuk Badge Halaman & Tombol Toggle Status --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary">
                                {{ ucfirst($slide->halaman) }}
                            </span>
                            
                            {{-- Tombol Toggle Status (dari file Slider lama) --}}
                            <form action="{{ route('admin.slider.toggle', $slide->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                @if ($slide->is_visible)
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill" data-bs-toggle="tooltip" title="Klik untuk sembunyikan" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                        Tampil
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-secondary btn-sm rounded-pill" data-bs-toggle="tooltip" title="Klik untuk tampilkan" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                        Sembunyi
                                    </button>
                                @endif
                            </form>
                        </div>

                        {{-- Tanggal (diambil dari file Berita) --}}
                        <small class="text-muted d-block text-end">{{ $slide->created_at->format('Y/m/d') }}</small>

                        {{-- INI PERMINTAAN ANDA: Memotong Teks (Gaya "Berita") --}}
                        <p class="card-text text-muted mt-2">
                            {{ Str::limit(strip_tags($slide->keterangan), 100) }}
                        </p>

                        {{-- Tombol Aksi (didorong ke bawah, Gaya "Berita") --}}
                        <div class="mt-auto">
                            <hr>
                            <div class="d-flex justify-content-between">
                                {{-- Tombol Hapus (Gaya "Berita") --}}
                                <form action="{{ route('admin.slider.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slide ini?');" class="d-inline w-100 me-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                
                                {{-- Tombol Edit (Gaya "Berita") --}}
                                <a href="{{ route('admin.slider.edit', $slide->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100 ms-1">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                            </div>
                        </div>
                        {{-- ▲▲▲ AKHIR PERUBAHAN 3 ▲▲▲ --}}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                {{-- ▼▼▼ PERUBAHAN 4: Alert Kosong (Gaya "Berita") ▼▼▼ --}}
                <div class="alert alert-secondary text-center" role="alert">
                    @if ($selectedHalaman)
                        Tidak ada data slider untuk halaman "{{ $selectedHalaman }}".
                        <a href="{{ route('admin.slider.index') }}" class="alert-link">Reset filter</a>
                    @else
                        Belum ada data slider. Klik "Tambah Slide Baru" untuk memulai.
                    @endif
                </div>
                {{-- ▲▲▲ AKHIR PERUBAHAN 4 ▲▲▲ --}}
            </div>
        @endforelse
    </div>
    {{-- === AKHIR KONTEN KARTU === --}}

    {{-- === PAGINATION (Sudah sama) === --}}
    <div class="d-flex justify-content-center mt-4">
        {!! $sliders->withQueryString()->links('vendor.pagination.custom-circle') !!}
    </div>

</div>
@endsection