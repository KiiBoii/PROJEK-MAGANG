@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Pengelolaan Dokumen Publikasi</h3>
        <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary">
            Tambah Dokumen
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dokumenTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Dokumen</th>
                            <th>Keterangan</th>
                            <th>Nama File</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumens as $dokumen)
                        <tr id="dokumen-{{ $dokumen->id }}">
                            <td>{{ ($dokumens->currentPage() - 1) * $dokumens->perPage() + $loop->iteration }}</td>
                            <td>{{ $dokumen->judul }}</td>
                            <td>{{ $dokumen->keterangan }}</td>
                            <td>{{ $dokumen->file_name }}</td>
                            <td>{{ $dokumen->created_at->format('d M Y') }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.dokumen.edit', $dokumen->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                                
                                {{-- ▼▼▼ PERBAIKAN: Ganti download_url dengan asset() ▼▼▼ --}}
                                <a href="{{ asset($dokumen->file_path) }}" class="btn btn-sm btn-success" target="_blank">Download</a>
                                {{-- ▲▲▲ SELESAI PERBAIKAN ▲▲▲ --}}
                                
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $dokumen->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Konfirmasi Hapus --}}
                        <div class="modal fade" id="deleteModal{{ $dokumen->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $dokumen->id }}" aria-hidden="true">
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
                                        <p class="text-muted">Anda akan menghapus dokumen:</p>
                                        <p class="fw-bold mb-0">"{{ $dokumen->judul }}"</p>
                                        <p class="text-danger small mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer border-top-0 justify-content-center pb-4">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.dokumen.destroy', $dokumen->id) }}" method="POST" class="m-0">
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
                        <tr>
                            <td colspan="6" class="text-center">Belum ada dokumen yang diunggah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {!! $dokumens->withQueryString()->links('vendor.pagination.custom-circle') !!}
            </div>
            
        </div>
    </div>
</div>
@endsection