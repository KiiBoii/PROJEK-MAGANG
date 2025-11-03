<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon; // <-- TAMBAHKAN INI UNTUK FILTER TANGGAL

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Di-improve untuk menangani API (React) dan Filter
     */
    public function index(Request $request)
    {
        // Mulai query builder, BUKAN langsung ->get()
        $query = Berita::query();

        // --- PERUBAHAN 1: LOGIKA FILTER (DARI REACT) ---
        if ($request->filled('tanggal')) {
            $tanggal = $request->input('tanggal');
            if ($tanggal == 'hari_ini') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($tanggal == '7_hari') {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            } elseif ($tanggal == 'bulan_ini') {
                $query->whereMonth('created_at', Carbon::now()->month);
            }
        }
        
        if ($request->filled('tag')) {
            $tag = $request->input('tag');
            // Asumsi sederhana: kita cari tag di dalam 'isi'.
            // Jika Anda punya tabel 'tags' terpisah, logikanya akan berbeda.
            $query->where('isi', 'LIKE', '%' . $tag . '%');
        }
        // --- AKHIR LOGIKA FILTER ---

        // Ambil data SETELAH difilter
        $beritas = $query->latest()->get();

        // Cek jika ini adalah permintaan API dari React
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($beritas); // Kirim data sebagai JSON
        }

        // Jika tidak, tampilkan view Blade 
        // (Meskipun view Blade-nya sekarang akan diambil alih React)
        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new resource.
     * (Biarkan, ini untuk Blade)
     */
    public function create()
    {
        return view('admin.berita.create'); // Tampilkan form create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     * (Biarkan, ini untuk Blade)
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 'nullable' berarti boleh kosong
        ]);

        if ($request->hasFile('gambar')) {
            // Simpan gambar dan dapatkan path-nya
            $validated['gambar'] = $request->file('gambar')->store('berita_images', 'public');
        }

        Berita::create($validated);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Biarkan, ini untuk Blade)
     */
    public function show(Berita $berita)
    {
        return view('admin.berita.show', compact('berita')); // Jika Anda punya view 'show.blade.php'
    }

    /**
     * Show the form for editing the specified resource.
     * (Biarkan, ini untuk Blade)
     */
    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     * (Biarkan, ini untuk Blade)
     */
    public function update(Request $request, Berita $berita)
    {
        // Validasi data (gambar tidak 'required', karena mungkin tidak diubah)
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 'nullable'
        ]);

        // Cek apakah ada file gambar baru yang di-upload
        if ($request->hasFile('gambar')) {
            
            // 1. Hapus gambar lama (jika ada)
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }

            // 2. Simpan gambar baru dan update path di data validasi
            $validated['gambar'] = $request->file('gambar')->store('berita_images', 'public');
        }

        // Update data berita di database
        $berita->update($validated);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * (Sudah benar dari kode Anda)
     */
    public function destroy(Request $request, Berita $berita) // <-- Tambahkan Request $request
    {
        // 1. Hapus file gambar dari storage (jika ada)
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        // 2. Hapus data berita dari database
        $berita->delete();

        // PERUBAHAN 2: Cek jika ini adalah permintaan API dari React
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Berita berhasil dihapus.']);
        }

        // Jika tidak, redirect Blade seperti biasa
        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}

