@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Edit Foto Kegiatan: "{{ $galeri->judul_kegiatan }}"</h3>

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
            <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul_kegiatan" class="form-label">Judul Kegiatan</label>
                    <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan', $galeri->judul_kegiatan) }}" required>
                </div>

                {{-- === BLOK BARU UNTUK BIDANG === --}}
                <div class="mb-3">
                    <label for="bidang" class="form-label">Bidang Terkait</label>
                    <select class="form-select" id="bidang" name="bidang" required>
                        <option value="" disabled>-- Pilih Bidang --</option>
                        
                        {{-- Variabel $bidangList ini dikirim dari GaleriController@edit --}}
                        @forelse($bidangList ?? [] as $bidang)
                            {{-- 
                                Ini akan otomatis memilih bidang yang sudah tersimpan di $galeri->bidang,
                                atau menggunakan old('bidang') jika ada error validasi.
                            --}}
                            <option value="{{ $bidang }}" {{ old('bidang', $galeri->bidang) == $bidang ? 'selected' : '' }}>
                                {{ $bidang }}
                            </option>
                        @empty
                            {{-- Fallback jika $bidangList tidak terkirim dari controller --}}
                            <option value="Bidang Infrastruktur TIK">Bidang Infrastruktur TIK</option>
                            <option value="Bidang Statistik">Bidang Statistik</option>
                            <option value="Bidang Aptika">Bidang Aptika</option>
                            <option value="Bidang IKP">Bidang IKP</option>
                            <option value="Bidang Persandian">Bidang Persandian</option>
                            <option value="Komisi Informasi Riau">Komisi Informasi Riau</option>
                            <option value="Sekretariat Diskomfotik">Sekretariat Diskomfotik</option>
                        @endforelse
                    </select>
                </div>
                {{-- === AKHIR BLOK BARU === --}}

                <div class="mb-3">
                    <label for="foto_path" class="form-label">Ganti Foto (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" class="form-control" id="foto_path" name="foto_path" accept="image/*">
                    <small class="text-muted">Max 2MB. Format: JPG, PNG, GIF</small>
                    @if ($galeri->foto_path)
                        <div class="mt-2">
                            <p>Foto saat ini:</p>
                            <img src="{{ asset('storage/' . $galeri->foto_path) }}" alt="{{ $galeri->judul_kegiatan }}" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Update Foto</button>
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection