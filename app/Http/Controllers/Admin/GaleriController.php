<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri; // Import Model Galeri
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file
use Illuminate\Support\Facades\Auth; // <-- 1. IMPORT 'Auth' facade

class GaleriController extends Controller
{
    // Definisikan daftar bidang di satu tempat agar konsisten
    private $bidangList = [
        'Bidang Infrastruktur TIK',
        'Bidang Statistik',
        'Bidang Aptika',
        'Bidang IKP',
        'Bidang Persandian',
        'Komisi Informasi Riau',
        'Sekretariat Diskomfotik'
    ];

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
        // Kirim daftar bidang yang sudah didefinisikan
        return view('admin.galeri.create', ['bidangList' => $this->bidangList]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'bidang' => 'required|string|max:255', // Validasi bidang
            'foto_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_path')) {
            $validated['foto_path'] = $request->file('foto_path')->store('galeri_images', 'public');
        }

        // --- 2. INI ADALAH PERBAIKANNYA ---
        // Menambahkan ID user yang sedang login ke data yang akan disimpan
        $validated['user_id'] = Auth::id();
        // ------------------------------

        Galeri::create($validated); // 'bidang' dan 'user_id' akan otomatis tersimpan

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
        // Kirim daftar bidang dan galeri yang akan diedit
        return view('admin.galeri.edit', [
            'galeri' => $galeri,
            'bidangList' => $this->bidangList
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'bidang' => 'required|string|max:255', // Validasi bidang
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_path')) {
            // Hapus foto lama jika ada
            if ($galeri->foto_path) {
                Storage::disk('public')->delete($galeri->foto_path);
            }
            // Simpan foto baru
            $validated['foto_path'] = $request->file('foto_path')->store('galeri_images', 'public');
        }

        // (Opsional: Lacak siapa yang terakhir meng-update)
        // $validated['user_id'] = Auth::id();

        $galeri->update($validated); // 'bidang' akan otomatis ter-update

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