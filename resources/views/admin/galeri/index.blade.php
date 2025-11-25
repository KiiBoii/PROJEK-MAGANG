@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Galeri Kegiatan</h3>
    <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Upload Photo
    </a>
</div>

<div class="row">
    @foreach ($galeris as $foto)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm rounded-3 border-0 h-100">
            @if ($foto->foto_path)
                {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                <img src="{{ asset($foto->foto_path) }}" class="card-img-top" alt="{{ $foto->judul_kegiatan }}" style="height: 250px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                    <span class="text-muted">Tidak Ada Foto</span>
                </div>
            @endif
            <div class="card-body d-flex flex-column">
                
                <span class="badge bg-primary align-self-start mb-2">
                    {{ $foto->bidang ?? 'Umum' }}
                </span>

                <h5 class="card-title">{{ $foto->judul_kegiatan }}</h5>
                <small class="text-muted">{{ $foto->created_at->format('d/m/Y') }}</small>
                
                <div class="mt-auto"> 
                    <hr>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $foto->id }}">
                            Hapus
                        </button>
                        <a href="{{ route('admin.galeri.edit', $foto->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus --}}
        <div class="modal fade" id="deleteModal{{ $foto->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $foto->id }}" aria-hidden="true">
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
                        <p class="text-muted">Anda akan menghapus foto kegiatan:</p>
                        <p class="fw-bold mb-0">"{{ $foto->judul_kegiatan }}"</p>
                        <p class="text-danger small mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.galeri.destroy', $foto->id) }}" method="POST" class="m-0">
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
    </div>
    @endforeach

    @if($galeris->isEmpty())
        <div class="col-12">
            <p class="text-center text-muted">Belum ada foto kegiatan di galeri.</p>
        </div>
    @endif
</div>

<div class="d-flex justify-content-center mt-4">
    {!! $galeris->withQueryString()->links('vendor.pagination.custom-circle') !!}
</div>
@endsection