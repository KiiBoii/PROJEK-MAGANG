@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Pengelolaan Admin</h3>
    <a href="{{ route('karyawan.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Tambah Admin
    </a>
</div>

<div class="row">
    @foreach ($karyawans as $karyawan)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-3 border-0">
            {{-- [DIUBAH] Tambahkan position: relative agar badge bisa diposisikan --}}
            <div class="card-body text-center" style="position: relative;">
                
                {{-- [BARU] Badge Role di sudut kanan atas --}}
                <span style="position: absolute; top: 1rem; right: 1rem; font-size: 0.75rem;" 
                      class="badge 
                             @if($karyawan->role == 'admin') bg-danger
                             @elseif($karyawan->role == 'redaktur') bg-info text-dark
                             @else bg-secondary
                             @endif">
                    {{-- Tampilkan nama role dengan huruf kapital di awal --}}
                    {{ ucfirst($karyawan->role) }}
                </span>

                {{-- [DIUBAH] Tampilkan foto jika ada, jika tidak, tampilkan placeholder --}}
                @if ($karyawan->foto)
                    <img src="{{ asset('storage/' . $karyawan->foto) }}" 
                         class="rounded-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; object-fit: cover;" 
                         alt="{{ $karyawan->name }}">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="text-muted fs-5"><i class="bi bi-person"></i></span>
                    </div>
                @endif
                
                <h5 class="card-title mb-1">{{ $karyawan->name }}</h5>
                <p class="card-text text-muted mb-2">{{ $karyawan->jabatan ?? 'N/A' }}</p> {{-- Jabatan --}}
                <hr>
                
                <ul class="list-unstyled text-start small">
                    <li><strong>Email:</strong> {{ $karyawan->email }}</li>
                    <li><strong>Departemen:</strong> {{ $karyawan->departemen ?? 'N/A' }}</li>
                    <li><strong>Telepon:</strong> {{ $karyawan->telepon ?? 'N/A' }}</li>
                    {{-- Anda bisa tambahkan detail lain seperti tanggal bergabung, dll --}}
                </ul>
                
                <div class="d-flex justify-content-between mt-3">
                    <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                    </form>
                    <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($karyawans->isEmpty())
        <div class="col-12">
            <p class="text-center text-muted">Belum ada data admin/karyawan.</p>
        </div>
    @endif
</div>
@endsection