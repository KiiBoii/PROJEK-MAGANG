@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Edit Admin/Karyawan: "{{ $karyawan->name }}"</h3>

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
            {{-- [DIUBAH] Tambahkan enctype="multipart/form-data" untuk upload file --}}
            <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $karyawan->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $karyawan->email) }}" required>
                </div>

                {{-- [BARU DITAMBAHKAN] Kolom untuk memilih Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label">Role (Peran)</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" disabled>-- Pilih Peran --</option>
                        @foreach($roleList as $key => $roleName)
                            <option value="{{ $key }}" {{ old('role', $karyawan->role) == $key ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- ========================================== --}}

                {{-- [BARU] Input untuk Foto Profil --}}
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Profil (Opsional)</label>
                    
                    {{-- [BARU] Tampilkan foto saat ini jika ada --}}
                    @if($karyawan->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="Foto {{ $karyawan->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <small class="text-muted">Upload file baru untuk mengganti foto di atas.</small>
                    @endif
                    
                    <input class="form-control mt-2" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg">
                    <small class="text-muted">Max: 2MB. Format: JPG, JPEG, PNG. Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $karyawan->jabatan) }}">
                </div>

                <div class="mb-3">
                    <label for="departemen" class="form-label">Departemen</label>
                    <input type="text" class="form-control" id="departemen" name="departemen" value="{{ old('departemen', $karyawan->departemen) }}">
                </div>

                <div class="mb-3">
                    <label for="telepon" class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $karyawan->telepon) }}">
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border: none; border-radius: 20px; padding: 10px 30px;">Update Admin</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary ms-2" style="border-radius: 20px; padding: 10px 30px;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection