@extends('layouts.admin')

@section('content')

{{-- Blok untuk menampilkan notifikasi sukses --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Pengelolaan Admin</h3>
    <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Tambah Admin
    </a>
</div>

{{-- [BARU] FORM FILTER & PENCARIAN --}}
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <form action="{{ route('admin.karyawan.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                {{-- Filter Pencarian --}}
                <div class="col-md-5">
                    <label for="search" class="form-label small">Cari Nama / Email</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           class="form-control" 
                           placeholder="Masukkan nama atau email..." 
                           value="{{ $currentFilters['search'] ?? '' }}">
                </div>

                {{-- Filter Role --}}
                <div class="col-md-5">
                    <label for="role_filter" class="form-label small">Filter Role</label>
                    <select name="role_filter" id="role_filter" class="form-select">
                        <option value="">Semua Role (Admin & Redaktur)</option>
                        @foreach ($roleList as $roleKey => $roleName)
                            <option value="{{ $roleKey }}" 
                                    {{ ($currentFilters['role_filter'] ?? '') == $roleKey ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-secondary w-100 me-2">Filter</button>
                    <a href="{{ route('admin.karyawan.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- [SELESAI] FORM FILTER --}}


<div class="row">
    {{-- Kita menggunakan @forelse untuk penanganan data kosong yang lebih bersih --}}
    @forelse ($karyawans as $karyawan)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-3 border-0 h-100"> {{-- h-100 untuk tinggi card yg sama --}}
            <div class="card-body text-center" style="position: relative;">
                
                <span style="position: absolute; top: 1rem; right: 1rem; font-size: 0.75rem;" 
                      class="badge 
                             @if($karyawan->role == 'admin') bg-danger
                             @elseif($karyawan->role == 'redaktur') bg-info text-dark
                             @else bg-secondary
                             @endif">
                    {{ ucfirst($karyawan->role) }}
                </span>

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
                <p class="card-text text-muted mb-2">{{ $karyawan->jabatan ?? 'N/A' }}</p>
                <hr>
                
                <ul class="list-unstyled text-start small">
                    <li><strong>Email:</strong> {{ $karyawan->email }}</li>
                    <li><strong>Departemen:</strong> {{ $karyawan->departemen ?? 'N/A' }}</li>
                    <li><strong>Telepon:</strong> {{ $karyawan->telepon ?? 'N/A' }}</li>
                </ul>
                
                <div class="d-flex justify-content-between mt-3" style="position: absolute; bottom: 1rem; left: 1rem; right: 1rem;">
                    <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                    </form>
                    <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- [DIUBAH] Pesan jika tidak ada data --}}
    @empty
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                @if (!empty($currentFilters['search']) || !empty($currentFilters['role_filter']))
                    <p class="text-muted mb-0">Tidak ditemukan admin/karyawan yang sesuai dengan filter pencarian Anda.</p>
                @else
                    <p class="text-muted mb-0">Belum ada data admin/karyawan.</p>
                @endif
            </div>
        </div>
    </div>
    @endforelse {{-- <--- Akhir dari @forelse --}}

</div> {{-- Akhir dari .row --}}
{{-- 🔸 PAGINATION CUSTOM DITAMBAHKAN DI SINI 🔸 --}}
<div class="d-flex justify-content-center mt-4">
    {!! $karyawans->withQueryString()->links('vendor.pagination.custom-circle') !!}
</
@endsection