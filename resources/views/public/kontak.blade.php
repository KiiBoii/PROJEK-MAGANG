@extends('layouts.public')

{{-- 1. CSS KUSTOM --}}
@push('styles')
<style>
    /* --- Style untuk Slider Header (Tetap) --- */
    .news-slider .carousel-item {
        height: 450px;
        background-color: #555;
    }
    .news-slider .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .news-slider .carousel-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.6) 20%, rgba(0,0,0,0) 80%);
    }
    .news-slider .carousel-caption {
        bottom: 0;
        z-index: 10;
        text-align: left;
        padding: 2rem 1.5rem;
        width: 80%; 
        left: 5%; 
    }
    .news-slider .carousel-caption h1 {
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .news-slider .carousel-caption p {
        font-size: 1.1rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
</style>
@endpush


@section('content')

{{-- BAGIAN 1: HEADER SLIDER --}}
<div class="container my-5">
    <div id="kontakHeader" class="carousel slide news-slider" data-bs-ride="{{ (isset($sliders) && $sliders->count() > 1) ? 'carousel' : 'false' }}"
         data-bs-pause="{{ (isset($sliders) && $sliders->count() > 1) ? 'false' : 'true' }}" 
         data-bs-interval="3000"
         style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            @if(isset($sliders) && $sliders->count() > 1)
                @foreach($sliders as $slider)
                    <button type="button" data-bs-target="#kontakHeader" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            @endif
        </div>
        
        <div class="carousel-inner">
            @forelse(isset($sliders) ? $sliders : [] as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset($slider->gambar) }}" class="d-block w-100" alt="{{ $slider->judul }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="text-white">{{ $slider->judul }}</h1>
                        <p class="text-white-50">{{ $slider->keterangan }}</p>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x450/007bff/white?text=LAYANAN+PENGADUAN" class="d-block w-100" alt="Kontak Header Fallback">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="text-white">LAYANAN PENGADUAN</h1>
                        <p class="text-white-50">Sampaikan laporan atau pengaduan Anda di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        @if(isset($sliders) && $sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#kontakHeader" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#kontakHeader" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
</div> 

{{-- BAGIAN 2: FORMULIR KONTAK --}}
<div class="container my-5">
    <div class="card shadow-lg border-0" style="border-radius: 12px;">
        <div class="row g-0">
            {{-- Kolom Kiri: Info Kontak --}}
            <div class="col-lg-5" style="background-color: var(--primary-color); color: white; border-radius: 12px 0 0 12px;">
                <div class="p-4 p-md-5">
                    <h3 class="fw-bold text-white mb-4">Info Kontak</h3>
                    <p class="text-white-50">Jika Anda memerlukan sesuatu untuk ditanyakan, silakan hubungi kami melalui detail di bawah ini atau gunakan formulir di samping untuk mengirim pengaduan.</p>
                    <hr class="border-light">
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h5 class="text-white mb-1">Alamat</h5>
                            <p class="text-white-50 mb-0">Jl. Jend. Sudirman No. 123, Pekanbaru, Riau, 28282</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h5 class="text-white mb-1">Email</h5>
                            <p class="text-white-50 mb-0">info@dinsos.riau.go.id</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-telephone-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h5 class="text-white mb-1">Telepon</h5>
                            <p class="text-white-50 mb-0">(0761) 123-456</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Form --}}
            <div class="col-lg-7">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4">Formulir Pengaduan Masyarakat</h4>
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('public.kontak.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Nama Lengkap Anda">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="email@anda.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">No. HP (WhatsApp)</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="0812xxxx">
                                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="isi_pengaduan" class="form-label">Isi Pengaduan / Pesan</label>
                            <textarea class="form-control @error('isi_pengaduan') is-invalid @enderror" id="isi_pengaduan" name="isi_pengaduan" rows="6" required placeholder="Tuliskan pesan atau detail pengaduan Anda di sini...">{{ old('isi_pengaduan') }}</textarea>
                            @error('isi_pengaduan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto_pengaduan" class="form-label">Foto (Wajib) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('foto_pengaduan') is-invalid @enderror" id="foto_pengaduan" name="foto_pengaduan" accept="image/*" required>
                            <small class="text-muted">Max 2MB. Wajib melampirkan foto bukti pengaduan.</small>
                            @error('foto_pengaduan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fs-5 py-2">Kirim Pengaduan <i class="bi bi-send ms-1"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BAGIAN 3: RIWAYAT PENGADUAN TERKINI (TAMPILAN TABEL SEPERTI ADMIN) --}}
<div class="container mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold" style="color: var(--dark-blue); border-left: 5px solid var(--primary-color); padding-left: 15px;">
            Riwayat Pengaduan Terkini
        </h3>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 300px; border-radius: 10px; overflow: hidden;">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light text-uppercase small fw-bold">
                        <tr>
                            <th scope="col" style="width: 5%;" class="text-center">No</th>
                            <th scope="col" style="width: 15%;">Tanggal</th>
                            <th scope="col" style="width: 20%;">Pelapor</th>
                            <th scope="col" style="width: 35%;">Isi Laporan</th>
                            <th scope="col" style="width: 15%;">Status</th>
                            <th scope="col" style="width: 10%;" class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat_pengaduan as $index => $item)
                        <tr>
                            <td class="text-center">{{ $riwayat_pengaduan->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $item->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Menampilkan Nama Saja --}}
                                    <span class="fw-bold text-dark">{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-inline-block text-muted" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $item->isi_pengaduan }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($item->status) {
                                        'diajukan' => 'bg-secondary',
                                        'diproses' => 'bg-warning text-dark',
                                        'diterima' => 'bg-primary',
                                        'selesai' => 'bg-success',
                                        'ditolak' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">
                                    {{ $item->status ?? 'Diajukan' }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{-- Tombol Detail (Modal) --}}
                                <button type="button" class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted bg-light">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-inbox fs-1 mb-2 text-secondary"></i>
                                    <span>Belum ada riwayat pengaduan. Jadilah yang pertama!</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ▼▼▼ TOMBOL NAVIGASI HALAMAN (PAGINATION) ▼▼▼ --}}
            <div class="d-flex justify-content-center mt-4 pb-4">
                {!! $riwayat_pengaduan->withQueryString()->links('vendor.pagination.custom-circle') !!}
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL (Sama seperti Admin, tapi info Kontak disembunyikan untuk privasi) --}}
@foreach($riwayat_pengaduan as $item)
    @php
        $statusClassModal = match($item->status) {
            'diajukan' => 'bg-secondary',
            'diproses' => 'bg-warning text-dark',
            'diterima' => 'bg-primary',
            'selesai' => 'bg-success',
            'ditolak' => 'bg-danger',
            default => 'bg-secondary'
        };
    @endphp
    <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">Detail Pengaduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Kolom Foto --}}
                        <div class="col-lg-5 mb-3 text-center">
                            @if($item->foto_pengaduan)
                                <img src="{{ asset($item->foto_pengaduan) }}" class="img-fluid rounded shadow-sm border" alt="Bukti Foto" style="max-height: 300px; width: 100%; object-fit: contain; background-color: #f8f9fa;">
                                <div class="mt-2">
                                    <a href="{{ asset($item->foto_pengaduan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-zoom-in"></i> Lihat Ukuran Penuh
                                    </a>
                                </div>
                            @else
                                <div class="d-flex flex-column justify-content-center align-items-center h-100 bg-light rounded border py-5">
                                    <i class="bi bi-image-alt fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada foto dilampirkan</p>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Kolom Info --}}
                        <div class="col-lg-7">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="35%">Status</td>
                                    <td>: <span class="badge {{ $statusClassModal }}">{{ strtoupper($item->status ?? 'Diajukan') }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal</td>
                                    <td>: {{ $item->created_at->timezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><hr class="my-1"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Pelapor</td>
                                    <td class="fw-bold">: {{ $item->nama }}</td>
                                </tr>
                                {{-- Info Email & HP disembunyikan untuk publik agar aman --}}
                            </table>
                            
                            <div class="card bg-light border-0 mt-2">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><i class="bi bi-chat-left-text me-2"></i>Isi Laporan</h6>
                                    <p class="card-text text-dark" style="white-space: pre-line; text-align: justify;">{{ $item->isi_pengaduan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection