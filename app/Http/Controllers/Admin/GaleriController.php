<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri; // Import Model Galeri
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file
use Illuminate\Support\Facades\Auth; // <-- IMPORT 'Auth' facade

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


    public function index()
    {
        // PERUBAHAN: Mengganti get() dengan paginate(9) sesuai permintaan
        $galeris = Galeri::latest()->paginate(9);
        return view('admin.galeri.index', compact('galeris'));
    }


    public function create()
    {
        // Kirim daftar bidang yang sudah didefinisikan
        return view('admin.galeri.create', ['bidangList' => $this->bidangList]);
    }


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

        //2. INI ADALAH PERBAIKANNYA ---
        // Menambahkan ID user yang sedang login ke data yang akan disimpan
        $validated['user_id'] = Auth::id();
        // ------------------------------

        Galeri::create($validated); // 'bidang' dan 'user_id' akan otomatis tersimpan

        // ▼▼▼ PERBAIKAN 1 ▼▼▼
        return redirect()->route('admin.galeri.index')->with('success', 'Foto kegiatan berhasil ditambahkan.');
    }

    public function show(Galeri $galeri)
    {
        // Opsional: Jika Anda ingin halaman detail galeri
        return view('admin.galeri.show', compact('galeri'));
    }


    public function edit(Galeri $galeri)
    {
        // Kirim daftar bidang dan galeri yang akan diedit
        return view('admin.galeri.edit', [
            'galeri' => $galeri,
            'bidangList' => $this->bidangList
        ]);
    }


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
        // $validated['user_id'] = Auth::id(); // Jika ingin mencatat updater terakhir

        $galeri->update($validated); // 'bidang' akan otomatis ter-update

        // ▼▼▼ PERBAIKAN 2 ▼▼▼
        return redirect()->route('admin.galeri.index')->with('success', 'Foto kegiatan berhasil diperbarui.');
    }

 
    public function destroy(Galeri $galeri)
    {
        if ($galeri->foto_path) {
            Storage::disk('public')->delete($galeri->foto_path);
        }
        $galeri->delete();

        // ▼▼▼ PERBAIKAN 3 ▼▼▼
        return redirect()->route('admin.galeri.index')->with('success', 'Foto kegiatan berhasil dihapus.');
    }
}