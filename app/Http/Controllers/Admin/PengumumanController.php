<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman; // Import Model Pengumuman
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--  IMPORT 'Auth' facade
use Illuminate\Support\Facades\Storage; // <--  IMPORT 'Storage' facade

class PengumumanController extends Controller
{

    public function index()
    {
        //Tambahkan with('user') untuk mengambil data user (Karyawan)
        // Ini untuk memperbaiki N+1 Query di halaman index
        
        // PERUBAHAN: Mengganti get() dengan paginate(9) untuk pagination
        $pengumumans = Pengumuman::with('user')->latest()->paginate(9); 
        
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    
    public function store(Request $request)
    {
        // Tambahkan validasi 'gambar'
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Boleh kosong
        ]);

        //Tambahkan logic upload gambar
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pengumuman_images', 'public');
        }

        // Menambahkan ID user yang sedang login ke data yang akan disimpan
        $validated['user_id'] = Auth::id();
        
        Pengumuman::create($validated);

        // ▼▼▼ PERBAIKAN 1 ▼▼▼
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }


    public function show(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.show', compact('pengumuman'));
    }


    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }


    public function update(Request $request, Pengumuman $pengumuman)
    {
        //Tambahkan validasi 'gambar'
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Boleh kosong
        ]);
        
        //Tambahkan logic update gambar (termasuk hapus gambar lama)
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($pengumuman->gambar) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            // Simpan gambar baru
            $validated['gambar'] = $request->file('gambar')->store('pengumuman_images', 'public');
        }

        $pengumuman->update($validated);

        // ▼▼▼ PERBAIKAN 2 ▼▼▼
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }


    public function destroy(Pengumuman $pengumuman)
    {
        // 8. Tambahkan logic hapus gambar dari storage
        if ($pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        // ▼▼▼ PERBAIKAN 3 ▼▼▼
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}