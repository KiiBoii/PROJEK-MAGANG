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

                {{-- Kolom Role --}}
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

                {{-- Input Foto Profil --}}
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Profil (Opsional)</label>
                    
                    @if($karyawan->foto)
                        <div class="mb-2">
                            {{-- ▼▼▼ PERBAIKAN: Hapus 'storage/' . ▼▼▼ --}}
                            <img src="{{ asset($karyawan->foto) }}" alt="Foto {{ $karyawan->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <small class="text-muted d-block mb-2">Foto saat ini. Upload baru untuk menggantinya.</small>
                    @endif
                    
                    <input class="form-control" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg">
                    <small class="text-muted">Max: 2MB. Format: JPG, JPEG, PNG.</small>
                </div>

                {{-- ▼▼▼ BLOK PASSWORD DIPERBARUI ▼▼▼ --}}
                <div class="card bg-light mb-3 border-0">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3"><i class="bi bi-lock"></i> Ganti Password (Opsional)</h6>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                {{-- autocomplete="new-password" ditambahkan agar browser tidak autofill password lama --}}
                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" placeholder="Biarkan kosong jika tetap menggunakan password lama">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                {{-- autocomplete="new-password" ditambahkan --}}
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password baru (hanya jika diisi)">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">Jika kolom di atas kosong, password tidak akan berubah.</small>
                        </div>
                    </div>
                </div>
                {{-- ▲▲▲ AKHIR BLOK PASSWORD ▲▲▲ --}}

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

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 20px;">Update Admin</button>
                    <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary px-4" style="border-radius: 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi generik untuk toggle password
        function setupPasswordToggle(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(toggleId);

            if (passwordInput && toggleButton) {
                toggleButton.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    if (type === 'password') {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            }
        }

        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation');
    });
</script>
@endpush