<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 

class PengumumanController extends Controller
{

    public function index()
    {
        $pengumumans = Pengumuman::with('user')->latest()->paginate(9); 
        
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('gambar')) {
            // UPDATED: Menggunakan disk 'public_uploads'
            $validated['gambar'] = $request->file('gambar')->store('pengumuman_images', 'public_uploads');
        }

        $validated['user_id'] = Auth::id();
        
        Pengumuman::create($validated);

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
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
        
        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                // UPDATED: Hapus dari disk 'public_uploads'
                Storage::disk('public_uploads')->delete($pengumuman->gambar);
            }
            // UPDATED: Simpan ke disk 'public_uploads'
            $validated['gambar'] = $request->file('gambar')->store('pengumuman_images', 'public_uploads');
        }

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }


    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->gambar) {
            // UPDATED: Hapus dari disk 'public_uploads'
            Storage::disk('public_uploads')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}