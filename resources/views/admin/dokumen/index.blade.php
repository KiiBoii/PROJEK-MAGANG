@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Pengelolaan Dokumen Publikasi</h3>
        <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary">
            Tambah Dokumen
        </a>
    </div>

    {{-- Hapus duplikasi notifikasi di sini. Layout utama sudah menangani notifikasi --}}
    

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
                        {{-- Gunakan $dokumens->items() saat menggunakan pagination manual atau sesuaikan jika perlu --}}
                        {{-- Jika menggunakan paginate() standar, $loop->iteration perlu disesuaikan --}}
                        @forelse($dokumens as $dokumen)
                        <tr id="dokumen-{{ $dokumen->id }}"> {{-- Tambahkan ID untuk JS --}}
                            {{-- Penomoran untuk pagination: ($dokumens->currentPage() - 1) * $dokumens->perPage() + $loop->iteration --}}
                            <td>{{ ($dokumens->currentPage() - 1) * $dokumens->perPage() + $loop->iteration }}</td>
                            <td>{{ $dokumen->judul }}</td>
                            <td>{{ $dokumen->keterangan }}</td>
                            <td>{{ $dokumen->file_name }}</td>
                            <td>{{ $dokumen->created_at->format('d M Y') }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.dokumen.edit', $dokumen->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                                <a href="{{ $dokumen->download_url }}" class="btn btn-sm btn-success" target="_blank">Download</a>
                                
                                {{-- Pastikan form memiliki ID unik jika menggunakan JS --}}
                                <form action="{{ route('admin.dokumen.destroy', $dokumen->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini? File akan dihapus permanen.')" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada dokumen yang diunggah.</td>
                        </tr>
@endforelse
                    </tbody>
                </table>
            </div>

            {{-- 🔸 GUNAKAN PAGINATION CUSTOM (TELAH DITAMBAHKAN) --}}
            <div class="d-flex justify-content-center mt-4">
                {{-- Menggunakan $dokumens (sesuai variabel di controller) --}}
                {!! $dokumens->withQueryString()->links('vendor.pagination.custom-circle') !!}
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Anda mungkin perlu menginisialisasi DataTables jika Anda menggunakannya --}}
{{-- 
<script>
    $(document).ready(function() {
        // Hati-hati: DataTables bawaan mungkin konflik dengan pagination Laravel.
        // Jika Anda menggunakan DataTables untuk sorting/search sisi klien, pastikan pagination DataTables dimatikan
        // atau gunakan DataTables server-side processing.
        // 
        // $('#dokumenTable').DataTable({
        //     "paging": false, // Matikan paging DataTables agar pagination Laravel bekerja
        //     "info": false 
        // });
    });
</script> 
--}}
@endpush