<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri; // Import Model Galeri
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galeris = Galeri::latest()->get();
        return view('admin.galeri.index', compact('galeris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'foto_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar wajib saat create
        ]);

        if ($request->hasFile('foto_path')) {
            $validated['foto_path'] = $request->file('foto_path')->store('galeri_images', 'public');
        }

        Galeri::create($validated);

        return redirect()->route('galeri.index')->with('success', 'Foto kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Galeri $galeri)
    {
        // Opsional: Jika Anda ingin halaman detail galeri
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar boleh kosong saat update
        ]);

        if ($request->hasFile('foto_path')) {
            if ($galeri->foto_path) {
                Storage::disk('public')->delete($galeri->foto_path);
            }
            $validated['foto_path'] = $request->file('foto_path')->store('galeri_images', 'public');
        }

        $galeri->update($validated);

        return redirect()->route('galeri.index')->with('success', 'Foto kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galeri $galeri)
    {
        if ($galeri->foto_path) {
            Storage::disk('public')->delete($galeri->foto_path);
        }
        $galeri->delete();

        return redirect()->route('galeri.index')->with('success', 'Foto kegiatan berhasil dihapus.');
    }
}