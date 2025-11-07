<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request; // <-- 1. IMPORT REQUEST
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    // Definisikan daftar bidang di satu tempat agar konsisten
    private $halamanList = [
        'home', 
        'berita', 
        'galeri', 
        'profil', 
        'pengumuman', 
        'kontak', 
        'layanan'
    ];

    /**
     * Display a listing of the resource.
     * === DIPERBARUI UNTUK FILTER ===
     */
    public function index(Request $request) // <-- 2. TAMBAHKAN Request $request
    {
        $query = Slider::query(); // Mulai query builder

        // 3. Terapkan filter jika ada
        if ($request->filled('halaman')) {
            $query->where('halaman', $request->halaman);
        }

        $sliders = $query->latest()->get();
        
        // 4. Kirim $halamanList dan halaman yg dipilih ke view
        return view('admin.slider.index', [
            'sliders' => $sliders,
            'halamanList' => $this->halamanList,
            'selectedHalaman' => $request->halaman ?? '' // Kirim filter yg aktif
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kirim daftar bidang yang sudah didefinisikan
        return view('admin.slider.create', ['bidangList' => $this->halamanList]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'halaman' => 'required|string|in:' . implode(',', $this->halamanList),
            'is_visible' => 'required|boolean',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('slider_images', 'public');
        }

        Slider::create($validated);

        return redirect()->route('slider.index')->with('success', 'Slide berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        $halamanList = $this->halamanList;
        return view('admin.slider.edit', compact('slider', 'halamanList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'halaman' => 'required|string|in:' . implode(',', $this->halamanList),
            'is_visible' => 'required|boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Boleh kosong saat update
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($slider->gambar) {
                Storage::disk('public')->delete($slider->gambar);
            }
            // Upload gambar baru
            $validated['gambar'] = $request->file('gambar')->store('slider_images', 'public');
        }

        $slider->update($validated);

        return redirect()->route('slider.index')->with('success', 'Slide berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Hapus gambar
        if ($slider->gambar) {
            Storage::disk('public')->delete($slider->gambar);
        }
        
        // Hapus data
        $slider->delete();

        return redirect()->route('slider.index')->with('success', 'Slide berhasil dihapus.');
    }

    /**
     * Toggle status visibilitas slider.
     */
    public function toggleStatus(Slider $slider)
    {
        // Ganti status (dari true jadi false, atau false jadi true)
        $slider->is_visible = !$slider->is_visible;
        $slider->save();

        return back()->with('success', 'Status slider berhasil diubah.');
    }
}