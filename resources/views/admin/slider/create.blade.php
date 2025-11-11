@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Tambah Slide Baru</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm rounded-3 border-0">
        <div class="card-body">
            <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="halaman" class="form-label">Tampilkan di Halaman</label>
                    <select class="form-select" id="halaman" name="halaman" required>
                        <option value="" selected disabled>-- Pilih Halaman --</option>
                        {{-- PERBAIKAN: Variabel yang diterima dari controller adalah $bidangList --}}
                        {{-- Jika Anda memilih Opsi 1 di atas, Anda bisa tetap menggunakan $halamanList --}}
                        
                        @foreach($bidangList as $halaman) {{-- <== BARIS INI DIPERBAIKI --}}
                            <option value="{{ $halaman }}" {{ old('halaman') == $halaman ? 'selected' : '' }}>
                                {{ ucfirst($halaman) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Slide</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan (Sub-teks)</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Slider (Wajib)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                    <small class="text-muted">Max 2MB. Rekomendasi ukuran 1920x450 piksel.</small>
                </div>
                
                <div class="mb-3">
                    <label for="is_visible" class="form-label">Status</label>
                    <select class="form-select" id="is_visible" name="is_visible" required>
                        <option value="1" {{ old('is_visible', '1') == '1' ? 'selected' : '' }}>Tampilkan</option>
                        <option value="0" {{ old('is_visible') == '0' ? 'selected' : '' }}>Sembunyikan</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Simpan Slide</button>
                <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection