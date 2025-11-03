@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Pengaduan Masyarakat</h3>
    <p class="text-muted">"Masukan atau keluhan yang disampaikan masyarakat mengenai pekerjaan atau pelayanan yang diterima. Feedback berguna untuk menemukan masalah, melakukan perbaikan, dan meningkatkan kepuasan masyarakat."</p>

    <div class="row">
        @foreach ($pengaduans as $pengaduan)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body text-center pb-0">
                    @if ($pengaduan->foto_pengadu)
                        <img src="{{ asset('storage/' . $pengaduan->foto_pengadu) }}" alt="{{ $pengaduan->nama }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        {{-- Placeholder jika tidak ada foto --}}
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="text-muted fs-5"><i class="bi bi-person"></i></span>
                        </div>
                    @endif
                    <h5 class="card-title mb-1">{{ $pengaduan->nama }}</h5>
                    <p class="card-text text-muted mb-3">{{ $pengaduan->status_pengirim ?? 'Umum' }}</p>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    <p class="card-text fst-italic">"{{ Str::limit($pengaduan->isi_pengaduan, 120) }}"</p>
                    <small class="text-muted float-end">{{ $pengaduan->created_at->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach

        @if($pengaduans->isEmpty())
            <div class="col-12">
                <p class="text-center text-muted">Belum ada pengaduan masyarakat.</p>
            </div>
        @endif
    </div>
</div>
@endsection