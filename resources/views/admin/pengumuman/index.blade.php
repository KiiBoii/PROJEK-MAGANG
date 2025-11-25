@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">List Pengumuman</h3>
    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Tambah Pengumuman
    </a>
</div>

<div class="row">
    @forelse ($pengumumans as $pengumuman)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm rounded-3 border-0 h-100">
            
            @if ($pengumuman->gambar)
                {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                <img src="{{ asset($pengumuman->gambar) }}" class="card-img-top" alt="{{ $pengumuman->judul }}" style="height: 200px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-megaphone fs-1 text-muted"></i>
                </div>
            @endif

            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $pengumuman->judul }}</h5>
                
                <div class="card-text text-muted small">
                    {!! Str::limit($pengumuman->isi, 100) !!}
                </div>

                <div class="mt-auto">
                    <hr>
                    <small class="text-muted d-block mb-2">
                        Dibuat oleh: <strong>{{ $pengumuman->user->name ?? 'Sistem' }}</strong>
                        <br>
                        {{ $pengumuman->created_at->format('d/m/Y H:i') }}
                    </small>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pengumuman->id }}">
                            Hapus
                        </button>
                        <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="deleteModal{{ $pengumuman->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $pengumuman->id }}" aria-hidden="true">
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
                    <p class="text-muted">Anda akan menghapus pengumuman:</p>
                    <p class="fw-bold mb-0">"{{ $pengumuman->judul }}"</p>
                    <p class="text-danger small mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" class="m-0">
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
        <p class="text-center text-muted">Belum ada pengumuman.</p>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {!! $pengumumans->withQueryString()->links('vendor.pagination.custom-circle') !!}
</div>
@endsection