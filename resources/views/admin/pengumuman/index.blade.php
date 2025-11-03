@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">List Pengumuman</h3>
    <a href="{{ route('pengumuman.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Tambah Pengumuman
    </a>
</div>

<div class="row">
    @foreach ($pengumumans as $pengumuman)
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body">
                <h5 class="card-title">{{ $pengumuman->judul }}</h5>
                <p class="card-text text-muted">{{ Str::limit($pengumuman->isi, 150) }}</p>
                <small class="text-muted">{{ $pengumuman->created_at->format('d/m/Y') }}</small>
                <hr>
                <div class="d-flex justify-content-between">
                    <form action="{{ route('pengumuman.destroy', $pengumuman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                    </form>
                    <a href="{{ route('pengumuman.edit', $pengumuman->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($pengumumans->isEmpty())
        <div class="col-12">
            <p class="text-center text-muted">Belum ada pengumuman.</p>
        </div>
    @endif
</div>
@endsection