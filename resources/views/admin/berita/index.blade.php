@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Page Berita</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">List Berita</h4>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
            <i class="bi bi-upload me-1"></i> Upload Berita
        </a>
    </div>

    {{-- ▼▼▼ FILTER ▼▼▼ --}}
    <div class="d-flex mb-3">
        <div class="dropdown me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuTanggal" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-calendar3 me-1"></i> 
                {{ request('tanggal') ? ucwords(str_replace('_', ' ', request('tanggal'))) : 'Tanggal' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuTanggal">
                <li><a class="dropdown-item" href="{{ route('admin.berita.index', array_merge(request()->query(), ['tanggal' => 'hari_ini'])) }}">Hari Ini</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.berita.index', array_merge(request()->query(), ['tanggal' => '7_hari'])) }}">7 Hari Terakhir</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.berita.index', array_merge(request()->query(), ['tanggal' => 'bulan_ini'])) }}">Bulan Ini</a></li>
            </ul>
        </div>

        <div class="dropdown me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuTag" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-tag me-1"></i> 
                {{ request('tag') ? ucfirst(request('tag')) : 'Tag Berita' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuTag">
                <li><a class="dropdown-item" href="{{ route('admin.berita.index', array_merge(request()->query(), ['tag' => 'info'])) }}">Info</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.berita.index', array_merge(request()->query(), ['tag' => 'layanan'])) }}">Layanan</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.berita.index', array_merge(request()->query(), ['tag' => 'kegiatan'])) }}">Kegiatan</a></li>
            </ul>
        </div>

        @if(request('tanggal') || request('tag'))
        <a href="{{ route('admin.berita.index') }}" class="btn btn-light">
            <i class="bi bi-x-circle"></i> Reset Filter
        </a>
        @endif
    </div>
    {{-- ▲▲▲ AKHIR FILTER ▲▲▲ --}}

    <div class="row">
        @forelse ($beritas as $berita)
        <div class="col-md-4 mb-4">
            <div class="card h-100"> 
                @if($berita->gambar)
                    <img src="{{ asset('storage/'. $berita->gambar) }}" class="card-img-top" alt="{{ $berita->judul }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <span class="text-muted"><i class="bi bi-image-fill fs-3"></i></span>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $berita->judul }}</h5>
                    
                    {{-- ▼▼▼ PERUBAHAN: Penambahan Tombol Toggle Status ▼▼▼ --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        
                        @if($berita->tag)
                            <span class="badge 
                                @if($berita->tag == 'info') bg-info text-dark
                                @elseif($berita->tag == 'layanan') bg-success
                                @elseif($berita->tag == 'kegiatan') bg-warning text-dark
                                @endif
                                align-self-start" style="font-size: 0.75rem;"> {{-- Menghapus mb-2 dari sini --}}
                                Topik: {{ ucfirst($berita->tag) }}
                            </span>
                        @else
                            <span></span> {{-- Placeholder agar justify-content-between berfungsi --}}
                        @endif

                        {{-- Tombol Toggle Status (BARU) --}}
                        {{-- Pastikan Anda sudah menambah kolom 'is_visible' (boolean) ke tabel 'beritas' --}}
                        <form action="{{ route('admin.berita.toggle', $berita->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            @if ($berita->is_visible)
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
                    {{-- ▲▲▲ AKHIR PERUBAHAN ▲▲▲ --}}

                    <small class="text-muted d-block text-end">{{ $berita->created_at->format('Y/m/d') }}</small>
                    <p class="card-text text-muted mt-2">{{ Str::limit(strip_tags($berita->isi), 100) }}</p>

                    <hr class="mt-auto"> 
                    <div class="d-flex justify-content-between">
                        
                        {{-- ▼▼▼ Tombol Pemicu Modal ▼▼▼ --}}
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $berita->id }}">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                        
                        {{-- ▼▼▼ Tombol Edit ▼▼▼ --}}
                        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>

            {{-- ▼▼▼ DESAIN BARU: Modal Konfirmasi Hapus ▼▼▼ --}}
            <div class="modal fade" id="deleteModal{{ $berita->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $berita->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    
                    {{-- Konten Modal dengan Desain Baru --}}
                    <div class="modal-content">
                        
                        {{-- Header: Dibuat borderless, hanya berisi tombol close --}}
                        <div class="modal-header border-bottom-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        {{-- Body: Konten terpusat dengan ikon peringatan --}}
                        <div class="modal-body text-center pt-0">
                            {{-- Ikon Peringatan --}}
                            <div class="text-danger mb-3" style="font-size: 3.5rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <h4 class="mb-3">Anda Yakin?</h4>
                            <p class="text-muted">Anda akan menghapus berita:</p>
                            <p class="fw-bold mb-0">"{{ $berita->judul }}"</p>
                            <p class="text-danger small mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                        
                        {{-- Footer: Dibuat borderless dan terpusat --}}
                        <div class="modal-footer border-top-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            
                            {{-- Form Hapus --}}
                            <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    {{-- ▲▲▲ AKHIR DESAIN BARU KONTEN MODAL ▲▲▲ --}}

                </div>
            </div>
            {{-- ▲▲▲ AKHIR MODAL ▲▲▲ --}}

        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-secondary text-center" role="alert">
                Tidak ada berita yang ditemukan.
                @if(request('tanggal') || request('tag'))
                    <a href="{{ route('admin.berita.index') }}" class="alert-link">Reset filter</a>
                @else
                    Belum ada berita yang dipublikasikan.
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- 🔸 GUNAKAN PAGINATION CUSTOM --}}
    <div class="d-flex justify-content-center mt-4">
        {!! $beritas->withQueryString()->links('vendor.pagination.custom-circle') !!}
    </div>

</div>
@endsection