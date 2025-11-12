<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import Model User
use Illuminate\Http\Request; // <-- DIPERLUKAN UNTUK FILTER
use Illuminate\Support\Facades\Hash; // Untuk hash password
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // Import Storage untuk mengelola file

class KaryawanController extends Controller
{
    // === DEFINISI DAFTAR ROLE ===
    private $roleList = [
        'admin' => 'Admin Utama',
        'redaktur' => 'Admin Konten Berita',
        // Tambahkan role lain jika diperlukan di sini
    ];


    /**
     * [KODE DIPERBARUI]
     * Menampilkan daftar karyawan dengan filter pencarian dan role.
     */
    public function index(Request $request) // <-- 1. Terima Request
    {
        $adminRoles = array_keys($this->roleList);
        
        // 2. Mulai query dasar (HANYA admin & redaktur)
        $query = User::whereIn('role', $adminRoles);

        // 3. Terapkan filter PENCARIAN (jika ada)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            // Mencari di kolom 'name' ATAU 'email'
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        // 4. Terapkan filter ROLE DROPDOWN (jika ada)
        if ($request->filled('role_filter') && array_key_exists($request->role_filter, $this->roleList)) {
            $query->where('role', $request->role_filter);
        }

        // 5. Eksekusi query dengan urutan terbaru
$karyawans = $query->latest()->paginate(9);

        // 6. Kirim data ke view, termasuk data untuk mengisi form filter
        return view('admin.karyawan.index', [
            'karyawans' => $karyawans,
            'roleList' => $this->roleList, // Untuk dropdown
            'currentFilters' => $request->only(['search', 'role_filter']) // Untuk 'mengingat' nilai filter
        ]);
    }


    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        // === PERUBAHAN: KIRIM DAFTAR ROLE KE VIEW ===
        return view('admin.karyawan.create', [
            'roleList' => $this->roleList
        ]);
    }


    /**
     * Menyimpan karyawan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
            'role' => ['required', Rule::in(array_keys($this->roleList))],
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            // [BARU] Validasi untuk foto
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $validated['password'] = Hash::make($validated['password']); // Hash password

        // [BARU] Logika untuk menyimpan foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
        }

        User::create($validated);

        // ▼▼▼ PERBAIKAN 1 ▼▼▼
        return redirect()->route('admin.karyawan.index')->with('success', 'Admin/Karyawan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik karyawan.
     */
    public function show(User $karyawan)
    {
        return view('admin.karyawan.show', compact('karyawan'));
    }


    /**
     * Menampilkan form untuk mengedit karyawan.
     */
    public function edit(User $karyawan)
    {
        // === PERUBAHAN: KIRIM DAFTAR ROLE KE VIEW EDIT ===
        $roleList = $this->roleList;
        return view('admin.karyawan.edit', compact('karyawan', 'roleList'));
    }


    /**
     * Memperbarui data karyawan di database.
     */
    public function update(Request $request, User $karyawan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $karyawan->id, // Abaikan email ini saat update
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
            'role' => ['required', Rule::in(array_keys($this->roleList))],
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            // [BARU] Validasi untuk foto
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->filled('password')) { // Jika password diisi, hash password baru
            $validated['password'] = Hash::make($validated['password']);
        } else { // Jika password tidak diisi, gunakan password lama
            unset($validated['password']);
        }

        // [BARU] Logika untuk update foto
        if ($request->hasFile('foto')) {
            // 1. Hapus foto lama jika ada
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
            // 2. Simpan foto baru dan update path di $validated
            $validated['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
        }

        $karyawan->update($validated);

        // ▼▼▼ PERBAIKAN 2 ▼▼▼
        return redirect()->route('admin.karyawan.index')->with('success', 'Data Admin/Karyawan berhasil diperbarui.');
    }


    /**
     * Menghapus karyawan dari database.
     */
    public function destroy(User $karyawan)
    {
        // [BARU] Hapus foto dari storage sebelum menghapus data user
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        // ▼▼▼ PERBAIKAN 3 ▼▼▼
        return redirect()->route('admin.karyawan.index')->with('success', 'Admin/Karyawan berhasil dihapus.');
    }
}