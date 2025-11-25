@extends('layouts.admin')

@push('styles')
<style>
    .container-fluid {
        background-color: #f0f4f8;
        padding-top: 2rem;
        padding-bottom: 2rem;
        min-height: 100vh;
    }
    .pengaduan-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .pengaduan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }
    .pengaduan-card::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -80px;
        width: 250px;
        height: 250px;
        background: var(--bs-primary);
        opacity: 0.1;
        border-radius: 50%;
        z-index: 1;
        transition: all 0.4s ease;
    }
    .pengaduan-card:hover::before {
        transform: scale(1.2);
        opacity: 0.15;
    }
    .pengaduan-card .card-body {
        position: relative;
        z-index: 2;
    }
    .pengaduan-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--bs-primary);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }
    .pengaduan-card .card-title {
        color: var(--bs-primary);
        font-weight: 700;
    }
    .pengaduan-card .card-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .pengaduan-card .card-text {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .card-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    .card-actions .dropdown-toggle::after {
        display: none;
    }
    .card-actions .dropdown-menu {
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <h3 class="mb-2">Daftar Pengaduan Masyarakat</h3>
    <p class="text-muted mb-4">Daftar pesan, keluhan, dan masukan yang dikirim oleh publik.</p>
    


    <div class="row">
        @forelse ($pengaduans as $pengaduan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card pengaduan-card h-100">
                    <div class="card-body text-center d-flex flex-column">
                        
                        <div class="dropdown card-actions">
                            <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('admin.pengaduan.destroy', $pengaduan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i> Hapus
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        @if($pengaduan->foto_pengaduan)
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($pengaduan->foto_pengaduan) }}" class="pengaduan-avatar" alt="Foto Pengaduan">
                        @else
                            <img src="https://placehold.co/100x100/007bff/white?text={{ strtoupper(substr($pengaduan->nama, 0, 2)) }}" class="pengaduan-avatar" alt="Avatar">
                        @endif
                        
                        <h5 class="card-title mb-1">{{ $pengaduan->nama }}</h5>
                        <p class="card-subtitle mb-3">
                            {{ $pengaduan->email }}
                            @if($pengaduan->no_hp)
                                | {{ $pengaduan->no_hp }}
                            @endif
                        </p>

                        <p class="card-text text-muted">
                            "{{ Str::limit($pengaduan->isi_pengaduan, 150) }}"
                        </p>
                        
                        <div class="mt-auto">
                            <small class="d-block text-muted">{{ $pengaduan->created_at->format('d F Y, H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    Belum ada pesan atau pengaduan yang masuk.
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {!! $pengaduans->links() !!}
    </div>

</div>
@endsection