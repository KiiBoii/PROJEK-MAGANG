@extends('layouts.admin')

@section('content')



<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Pengelolaan Admin</h3>
    <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 20px;">
        Tambah Admin
    </a>
</div>

<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <form action="{{ route('admin.karyawan.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label small">Cari Nama / Email</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Masukkan nama atau email..." value="{{ $currentFilters['search'] ?? '' }}">
                </div>
                <div class="col-md-5">
                    <label for="role_filter" class="form-label small">Filter Role</label>
                    <select name="role_filter" id="role_filter" class="form-select">
                        <option value="">Semua Role (Admin & Redaktur)</option>
                        @foreach ($roleList as $roleKey => $roleName)
                            <option value="{{ $roleKey }}" {{ ($currentFilters['role_filter'] ?? '') == $roleKey ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                    </select>
                </div>
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

<div class="row">
    @forelse ($karyawans as $karyawan)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-3 border-0 h-100">
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
                    {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                    <img src="{{ asset($karyawan->foto) }}" 
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
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $karyawan->id }}">
                        Hapus
                    </button>
                    <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="deleteModal{{ $karyawan->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $karyawan->id }}" aria-hidden="true">
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
                    <p class="text-muted">Anda akan menghapus pengguna:</p>
                    <p class="fw-bold mb-0">"{{ $karyawan->name }}"</p>
                    <p class="text-danger small mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="m-0">
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
    @endforelse

</div>

<div class="d-flex justify-content-center mt-4">
    {!! $karyawans->withQueryString()->links('vendor.pagination.custom-circle') !!}
</div>
@endsection