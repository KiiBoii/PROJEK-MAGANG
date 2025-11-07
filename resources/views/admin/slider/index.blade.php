@extends('layouts.admin')

@push('styles')
<style>
    /* Perbarui style gambar agar pas di card */
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Manajemen Slider</h3>
        <a href="{{ route('slider.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
            Tambah Slide Baru
        </a>
    </div>

    {{-- 
        ===== BLOK ALERT SUKSES DIHAPUS DARI SINI =====
        (Karena sudah ditangani oleh layouts/admin.blade.php)
    --}}

    {{-- === 1. FORM FILTER === --}}
    <div class="card shadow-sm rounded-3 border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('slider.index') }}" method="GET" class="d-flex align-items-center">
                <label for="halaman" class="form-label me-2 mb-0">Filter:</label>
                <select class="form-select" name="halaman" id="halaman" style="width: 250px;" onchange="this.form.submit()">
                    <option value="">Semua Halaman</option>
                    {{-- Loop dari $halamanList dan tandai $selectedHalaman --}}
                    @foreach ($halamanList as $halaman)
                        <option value="{{ $halaman }}" {{ $selectedHalaman == $halaman ? 'selected' : '' }}>
                            {{ ucfirst($halaman) }}
                        </option>
                    @endforeach
                </select>
                {{-- Tampilkan tombol Reset hanya jika filter aktif --}}
                @if ($selectedHalaman)
                    <a href="{{ route('slider.index') }}" class="btn btn-link ms-2">Reset</a>
                @endif
            </form>
        </div>
    </div>
    {{-- === AKHIR FORM FILTER === --}}


    {{-- === KONTEN KARTU === --}}
    <div class="row">
        @forelse ($sliders as $slide)
            {{-- Tampilkan 3 card per baris di desktop (lg-4), 2 di tablet (md-6) --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm rounded-3 border-0 h-100">
                    <img src="{{ asset('storage/' . $slide->gambar) }}" alt="{{ $slide->judul }}" class="slider-card-img">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $slide->judul }}</h5>
                        <p class="card-text text-muted">{{ $slide->keterangan }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-secondary">{{ $slide->halaman }}</span>
                            
                            {{-- Tombol Toggle Status --}}
                            <form action="{{ route('slider.toggle', $slide->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                @if ($slide->is_visible)
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill" data-bs-toggle="tooltip" title="Klik untuk sembunyikan">
                                        Tampil
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-secondary btn-sm rounded-pill" data-bs-toggle="tooltip" title="Klik untuk tampilkan">
                                        Sembunyi
                                    </button>
                                @endif
                            </form>
                        </div>

                        {{-- Tombol Aksi (didorong ke bawah) --}}
                        <div class="mt-auto">
                            <hr>
                            <div class="d-flex justify-content-between">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('slider.edit', $slide->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100 me-1">Edit</a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('slider.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slide ini?');" class="d-inline w-100 ms-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    {{-- Pesan disesuaikan berdasarkan filter --}}
                    @if ($selectedHalaman)
                        Tidak ada data slider untuk halaman "{{ $selectedHalaman }}".
                    @else
                        Belum ada data slider. Klik "Tambah Slide Baru" untuk memulai.
                    @endif
                </div>
            </div>
        @endforelse
    </div>
    {{-- === AKHIR KONTEN KARTU === --}}

</div>
@endsection
