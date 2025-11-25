@extends('layouts.admin')

@push('styles')
<style>
    .slider-card-img {
        width: 100%;
        height: 200px; 
        object-fit: cover;
        border-top-left-radius: 0.375rem; 
        border-top-right-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Manajemen Slider</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">List Slide</h4>
        <a href="{{ route('admin.slider.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Slide Baru
        </a>
    </div>

    <div class="d-flex mb-3">
        <div class="dropdown me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuHalaman" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-tag me-1"></i> 
                {{ $selectedHalaman ? ucfirst($selectedHalaman) : 'Tag Halaman' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuHalaman">
                @foreach ($halamanList as $halaman)
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.slider.index', array_merge(request()->query(), ['halaman' => $halaman])) }}">
                            {{ ucfirst($halaman) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        @if($selectedHalaman)
        <a href="{{ route('admin.slider.index') }}" class="btn btn-light">
            <i class="bi bi-x-circle"></i> Reset Filter
        </a>
        @endif
    </div>

    <div class="row">
        @forelse ($sliders as $slide)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm rounded-3 border-0">
                    
                    {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                    <img src="{{ asset($slide->gambar) }}" alt="{{ $slide->judul }}" class="slider-card-img">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $slide->judul }}</h5>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary">
                                {{ ucfirst($slide->halaman) }}
                            </span>
                            
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

                        <small class="text-muted d-block text-end">{{ $slide->created_at->format('Y/m/d') }}</small>

                        <p class="card-text text-muted mt-2">
                            {{ Str::limit(strip_tags($slide->keterangan), 100) }}
                        </p>

                        <div class="mt-auto">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill w-100 me-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $slide->id }}">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                                
                                <a href="{{ route('admin.slider.edit', $slide->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100 ms-1">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Konfirmasi Hapus --}}
            <div class="modal fade" id="deleteModal{{ $slide->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $slide->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center pt-0">
                            <div class="text-danger mb-3" style="font-size: 3.5rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <h4 class="mb-3">Anda Yakin?</h4>
                            <p class="text-muted">Anda akan menghapus slide:</p>
                            <p class="fw-bold mb-0">"{{ $slide->judul }}"</p>
                            <p class="text-danger small mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="modal-footer border-top-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.slider.destroy', $slide->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal --}}

        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center" role="alert">
                    @if ($selectedHalaman)
                        Tidak ada data slider untuk halaman "{{ $selectedHalaman }}".
                        <a href="{{ route('admin.slider.index') }}" class="alert-link">Reset filter</a>
                    @else
                        Belum ada data slider. Klik "Tambah Slide Baru" untuk memulai.
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {!! $sliders->withQueryString()->links('vendor.pagination.custom-circle') !!}
    </div>

</div>
@endsection