@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Edit Slide</h3>

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
            <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="halaman" class="form-label">Tampilkan di Halaman</label>
                    <select class="form-select" id="halaman" name="halaman" required>
                        <option value="" disabled>-- Pilih Halaman --</option>
                        @foreach($halamanList as $halaman)
                            <option value="{{ $halaman }}" {{ old('halaman', $slider->halaman) == $halaman ? 'selected' : '' }}>
                                {{ ucfirst($halaman) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Slide</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $slider->judul) }}" required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan (Sub-teks)</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $slider->keterangan) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="gambar" class="form-label">Ganti Gambar (Opsional)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                    <small class="text-muted">Max 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    <div class="mt-2">
                        <p>Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $slider->gambar) }}" alt="{{ $slider->judul }}" style="max-width: 300px; border-radius: 8px;">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="is_visible" class="form-label">Status</label>
                    <select class="form-select" id="is_visible" name="is_visible" required>
                        <option value="1" {{ old('is_visible', $slider->is_visible) == 1 ? 'selected' : '' }}>Tampilkan</option>
                        <option value="0" {{ old('is_visible', $slider->is_visible) == 0 ? 'selected' : '' }}>Sembunyikan</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Update Slide</button>
                <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection