<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 

class GaleriController extends Controller
{
    private $bidangList = [
        'Bidang Infrastruktur TIK',
        'Bidang Statistik',
        'Bidang Aptika',
        'Bidang IKP',
        'Bidang Persandian',
        'Komisi Informasi Riau',
        'Sekretariat Diskomfotik'
    ];


    public function index()
    {
        $galeris = Galeri::latest()->paginate(9);
        return view('admin.galeri.index', compact('galeris'));
    }


    public function create()
    {
        return view('admin.galeri.create', ['bidangList' => $this->bidangList]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'bidang' => 'required|string|max:255', 
            'foto_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_path')) {
            // UPDATED: Menggunakan disk 'public_uploads'
            $validated['foto_path'] = $request->file('foto_path')->store('galeri_images', 'public_uploads');
        }

        $validated['user_id'] = Auth::id();

        Galeri::create($validated); 

        return redirect()->route('admin.galeri.index')->with('success', 'Foto kegiatan berhasil ditambahkan.');
    }

    public function show(Galeri $galeri)
    {
        return view('admin.galeri.show', compact('galeri'));
    }


    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', [
            'galeri' => $galeri,
            'bidangList' => $this->bidangList
        ]);
    }


    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'bidang' => 'required|string|max:255', 
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_path')) {
            if ($galeri->foto_path) {
                // UPDATED: Hapus dari disk 'public_uploads'
                Storage::disk('public_uploads')->delete($galeri->foto_path);
            }
            // UPDATED: Simpan ke disk 'public_uploads'
            $validated['foto_path'] = $request->file('foto_path')->store('galeri_images', 'public_uploads');
        }

        $galeri->update($validated); 

        return redirect()->route('admin.galeri.index')->with('success', 'Foto kegiatan berhasil diperbarui.');
    }

 
    public function destroy(Galeri $galeri)
    {
        if ($galeri->foto_path) {
            // UPDATED: Hapus dari disk 'public_uploads'
            Storage::disk('public_uploads')->delete($galeri->foto_path);
        }
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto kegiatan berhasil dihapus.');
    }
}