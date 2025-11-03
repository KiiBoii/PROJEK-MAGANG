<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hash password

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = User::latest()->get(); // Ambil semua user
        return view('admin.karyawan.index', compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' butuh field password_confirmation
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']); // Hash password

        User::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Admin/Karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $karyawan)
    {
        return view('admin.karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $karyawan)
    {
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $karyawan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $karyawan->id, // Abaikan email ini saat update
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        if ($request->filled('password')) { // Jika password diisi, hash password baru
            $validated['password'] = Hash::make($validated['password']);
        } else { // Jika password tidak diisi, gunakan password lama
            unset($validated['password']);
        }

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')->with('success', 'Data Admin/Karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $karyawan)
    {
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Admin/Karyawan berhasil dihapus.');
    }
}