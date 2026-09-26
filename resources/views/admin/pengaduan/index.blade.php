@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4 mb-3">Daftar Pengaduan Masyarakat</h3>
    
    {{-- TAB NAVIGASI STATUS --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <ul class="nav nav-tabs card-header-tabs" id="statusTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == null || request('status') == 'semua' ? 'active fw-bold text-primary' : 'text-muted' }}" 
                       href="{{ route('admin.pengaduan.index', ['status' => 'semua']) }}">
                        Semua <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['semua'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'diajukan' ? 'active fw-bold text-primary' : 'text-muted' }}" 
                       href="{{ route('admin.pengaduan.index', ['status' => 'diajukan']) }}">
                        Diajukan <span class="badge bg-info text-dark rounded-pill ms-1">{{ $counts['diajukan'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'diproses' ? 'active fw-bold text-primary' : 'text-muted' }}" 
                       href="{{ route('admin.pengaduan.index', ['status' => 'diproses']) }}">
                        Diproses <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $counts['diproses'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'diterima' ? 'active fw-bold text-primary' : 'text-muted' }}" 
                       href="{{ route('admin.pengaduan.index', ['status' => 'diterima']) }}">
                        Diterima <span class="badge bg-primary rounded-pill ms-1">{{ $counts['diterima'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'selesai' ? 'active fw-bold text-primary' : 'text-muted' }}" 
                       href="{{ route('admin.pengaduan.index', ['status' => 'selesai']) }}">
                        Selesai <span class="badge bg-success rounded-pill ms-1">{{ $counts['selesai'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'ditolak' ? 'active fw-bold text-primary' : 'text-muted' }}" 
                       href="{{ route('admin.pengaduan.index', ['status' => 'ditolak']) }}">
                        Ditolak <span class="badge bg-danger rounded-pill ms-1">{{ $counts['ditolak'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">


            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%">No</th>
                            <th scope="col" style="width: 15%">Tanggal</th>
                            <th scope="col" style="width: 20%">Pelapor</th>
                            <th scope="col" style="width: 30%">Judul / Isi Laporan</th>
                            <th scope="col" style="width: 10%">Status</th>
                            <th scope="col" style="width: 20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengaduans as $index => $item)
                        <tr>
                            <td>{{ $pengaduans->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $item->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->foto_pengaduan)
                                        <img src="{{ asset($item->foto_pengaduan) }}" alt="Foto" class="rounded-circle me-2 border" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2 border" style="width: 40px; height: 40px; font-weight: bold;">
                                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $item->nama }}</span>
                                        <small class="text-muted" style="font-size: 0.8rem;">{{ $item->no_hp ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate text-muted" style="max-width: 250px;">
                                    {{ $item->isi_pengaduan }}
                                </span>
                                @if($item->balasan)
                                    <div><small class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-reply-fill"></i> Sudah Dibalas</small></div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($item->status) {
                                        'diajukan' => 'bg-secondary',
                                        'diproses' => 'bg-warning text-dark',
                                        'diterima' => 'bg-primary',
                                        'selesai' => 'bg-success',
                                        'ditolak' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">
                                    {{ $item->status ?? 'Diajukan' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    {{-- TOMBOL DETAIL & BALAS --}}
                                    <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}" title="Detail & Balas">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    {{-- DROPDOWN UBAH STATUS --}}
                                    <div class="btn-group">
                                        <button type="button" 
                                                class="btn btn-sm btn-warning dropdown-toggle" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false" 
                                                data-bs-boundary="viewport"
                                                data-bs-popper-config='{"strategy":"fixed"}'
                                                title="Ubah Status">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li><h6 class="dropdown-header">Ubah Status</h6></li>
                                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status-diproses-{{ $item->id }}').submit();">Diproses</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status-diterima-{{ $item->id }}').submit();">Diterima</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status-selesai-{{ $item->id }}').submit();">Selesai</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('status-ditolak-{{ $item->id }}').submit();">Ditolak</a></li>
                                        </ul>
                                    </div>

                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('admin.pengaduan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data pengaduan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- FORM UPDATE STATUS HIDDEN --}}
                                <form id="status-diproses-{{ $item->id }}" action="{{ route('admin.pengaduan.update-status', $item->id) }}" method="POST" class="d-none">
                                    @csrf @method('PATCH') <input type="hidden" name="status" value="diproses">
                                </form>
                                <form id="status-diterima-{{ $item->id }}" action="{{ route('admin.pengaduan.update-status', $item->id) }}" method="POST" class="d-none">
                                    @csrf @method('PATCH') <input type="hidden" name="status" value="diterima">
                                </form>
                                <form id="status-selesai-{{ $item->id }}" action="{{ route('admin.pengaduan.update-status', $item->id) }}" method="POST" class="d-none">
                                    @csrf @method('PATCH') <input type="hidden" name="status" value="selesai">
                                </form>
                                <form id="status-ditolak-{{ $item->id }}" action="{{ route('admin.pengaduan.update-status', $item->id) }}" method="POST" class="d-none">
                                    @csrf @method('PATCH') <input type="hidden" name="status" value="ditolak">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted bg-light">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-inbox fs-1 mb-2 text-secondary"></i>
                                    <span>Belum ada data pengaduan {{ $status != 'semua' ? 'dengan status '.ucfirst($status) : '' }}.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {!! $pengaduans->withQueryString()->links('vendor.pagination.custom-circle') !!}
            </div>
        </div>
    </div>
</div>

{{-- MODAL AREA ADMIN --}}
@foreach($pengaduans as $item)
    @php
        $statusClassModal = match($item->status) {
            'diajukan' => 'bg-secondary',
            'diproses' => 'bg-warning text-dark',
            'diterima' => 'bg-primary',
            'selesai' => 'bg-success',
            'ditolak' => 'bg-danger',
            default => 'bg-secondary'
        };
    @endphp
    <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">Detail & Balasan Pengaduan #{{ $item->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5 mb-3 text-center">
                            @if($item->foto_pengaduan)
                                <img src="{{ asset($item->foto_pengaduan) }}" class="img-fluid rounded shadow-sm border" alt="Bukti Foto" style="max-height: 300px; width: 100%; object-fit: contain; background-color: #f8f9fa;">
                                <div class="mt-2">
                                    <a href="{{ asset($item->foto_pengaduan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-zoom-in"></i> Lihat Ukuran Penuh
                                    </a>
                                </div>
                            @else
                                <div class="d-flex flex-column justify-content-center align-items-center h-100 bg-light rounded border py-5">
                                    <i class="bi bi-image-alt fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada foto dilampirkan</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-7">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="35%">Status</td>
                                    <td>: <span class="badge {{ $statusClassModal }}">{{ strtoupper($item->status ?? 'Diajukan') }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal</td>
                                    <td>: {{ $item->created_at->timezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><hr class="my-1"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Pelapor</td>
                                    <td class="fw-bold">: {{ $item->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td>: {{ $item->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. HP</td>
                                    <td>: {{ $item->no_hp ?? '-' }}</td>
                                </tr>
                            </table>
                            <div class="card bg-light border-0 mt-2 mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><i class="bi bi-chat-left-text me-2"></i>Isi Laporan</h6>
                                    <p class="card-text text-dark" style="white-space: pre-line; text-align: justify;">{{ $item->isi_pengaduan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- RIWAYAT BALASAN SAAT INI & TOMBOL HAPUS BALASAN --}}
                    @if($item->balasan)
                        <div class="card border-success bg-light mb-4 shadow-sm">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-2">
                                <span><i class="bi bi-check-circle-fill me-1"></i> Tanggapan Terkirim</span>
                                <small>{{ $item->balasan_at ? \Carbon\Carbon::parse($item->balasan_at)->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB' : '' }}</small>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $item->balasan }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-end">
                                <form action="{{ route('admin.pengaduan.hapus-balasan', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus balasan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus Balasan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- FORMULIR BALASAN / EDIT BALASAN --}}
                    <div class="bg-white p-3 border rounded shadow-sm">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-reply-all-fill me-2 text-primary"></i>{{ $item->balasan ? 'Ubah Balasan Admin' : 'Kirim Balasan Admin' }}</h6>
                        <form action="{{ route('admin.pengaduan.balas', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <textarea name="balasan" class="form-control" rows="4" placeholder="Tuliskan tanggapan atau balasan resmi untuk pengadu di sini..." required>{{ old('balasan', $item->balasan) }}</textarea>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <label class="form-label small text-muted mb-1">Ubah Status Sekaligus:</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">-- Tetap ({{ ucfirst($item->status) }}) --</option>
                                        <option value="diproses" {{ $item->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="diterima" {{ $item->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                        <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="col-md-6 text-md-end pt-3 pt-md-0">
                                    <button type="submit" class="btn btn-primary btn-sm px-4">
                                        <i class="bi bi-send me-1"></i> {{ $item->balasan ? 'Perbarui Balasan' : 'Simpan & Kirim Balasan' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection