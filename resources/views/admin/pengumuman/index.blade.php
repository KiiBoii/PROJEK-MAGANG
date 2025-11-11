@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">List Pengumuman</h3>
    {{-- ▼▼▼ PERBAIKAN 1 ▼▼▼ --}}
    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Tambah Pengumuman
    </a>
</div>

<div class="row">
    @forelse ($pengumumans as $pengumuman)
    {{-- Diubah ke col-lg-4 agar bisa memuat 3 per baris --}}
    <div class="col-lg-4 col-md-6 mb-4">
        {{-- Menambahkan h-100 agar tinggi card sama --}}
        <div class="card shadow-sm rounded-3 border-0 h-100">
            
            {{-- LOGIKA INI SUDAH BENAR --}}
            @if ($pengumuman->gambar)
                <img src="{{ asset('storage/' . $pengumuman->gambar) }}" class="card-img-top" alt="{{ $pengumuman->judul }}" style="height: 200px; object-fit: cover;">
            @else
                {{-- Placeholder jika tidak ada gambar --}}
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-megaphone fs-1 text-muted"></i>
                </div>
            @endif

            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $pengumuman->judul }}</h5>
                
                {{-- Menampilkan isi (HTML) dari Summernote, dibatasi 100 karakter --}}
                <div class="card-text text-muted small">
                    {!! Str::limit($pengumuman->isi, 100) !!}
                </div>

                {{-- Mendorong tombol ke bawah --}}
                <div class="mt-auto">
                    <hr>
                    {{-- MENAMPILKAN NAMA USER/KARYAWAN --}}
                    <small class="text-muted d-block mb-2">
                        Dibuat oleh: <strong>{{ $pengumuman->user->name ?? 'Sistem' }}</strong>
                        <br>
                        {{ $pengumuman->created_at->format('d/m/Y H:i') }}
                    </small>
                    <div class="d-flex justify-content-between">
                        {{-- ▼▼▼ PERBAIKAN 2 ▼▼▼ --}}
                        <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                        </form>
                        {{-- ▼▼▼ PERBAIKAN 3 ▼▼▼ --}}
                        <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p class="text-center text-muted">Belum ada pengumuman.</p>
    </div>
    @endforelse
</div>
@endsection