<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hash password
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // [BARU] Import Storage untuk mengelola file

class KaryawanController extends Controller
{
    // === TAMBAHAN: DEFINISI DAFTAR ROLE ===
    private $roleList = [
        'admin' => 'Admin Utama',
        'berita' => 'Admin Konten Berita',
        // Tambahkan role lain jika diperlukan di sini
    ];



    public function index()
    {
        $karyawans = User::latest()->get(); // Ambil semua user
        return view('admin.karyawan.index', compact('karyawans'));
    }


    public function create()
    {
        // === PERUBAHAN: KIRIM DAFTAR ROLE KE VIEW ===
        return view('admin.karyawan.create', [
            'roleList' => $this->roleList
        ]);
    }


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
            // Simpan file di 'storage/app/public/foto_karyawan'
            // dan simpan path-nya (foto_karyawan/namafile.jpg) ke database
            $validated['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
        }

        User::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Admin/Karyawan berhasil ditambahkan.');
    }

    public function show(User $karyawan)
    {
        return view('admin.karyawan.show', compact('karyawan'));
    }


    public function edit(User $karyawan)
    {
        // === PERUBAHAN: KIRIM DAFTAR ROLE KE VIEW EDIT ===
        $roleList = $this->roleList;
        return view('admin.karyawan.edit', compact('karyawan', 'roleList'));
    }


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

        return redirect()->route('karyawan.index')->with('success', 'Data Admin/Karyawan berhasil diperbarui.');
    }


    public function destroy(User $karyawan)
    {
        // [BARU] Hapus foto dari storage sebelum menghapus data user
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Admin/Karyawan berhasil dihapus.');
    }
}