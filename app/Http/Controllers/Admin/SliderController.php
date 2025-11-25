<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    private $halamanList = [
        'home', 
        'berita', 
        'galeri', 
        'profil', 
        'pengumuman', 
        'kontak', 
        'layanan',
        'faq'
    ];


    public function index(Request $request) 
    {
        $query = Slider::query(); 

        if ($request->filled('halaman')) {
            $query->where('halaman', $request->halaman);
        }

        $sliders = $query->latest()->paginate(9);
        
        return view('admin.slider.index', [
            'sliders' => $sliders,
            'halamanList' => $this->halamanList,
            'selectedHalaman' => $request->halaman ?? '' 
        ]);
    }

 
    public function create()
    {
        return view('admin.slider.create', ['bidangList' => $this->halamanList]);
    }


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
            // UPDATED: Menggunakan disk 'public_uploads'
            $validated['gambar'] = $request->file('gambar')->store('slider_images', 'public_uploads');
        }

        Slider::create($validated);

        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil ditambahkan.');
    }

 
    public function edit(Slider $slider)
    {
        $halamanList = $this->halamanList;
        return view('admin.slider.edit', compact('slider', 'halamanList'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'halaman' => 'required|string|in:' . implode(',', $this->halamanList),
            'is_visible' => 'required|boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('gambar')) {
            if ($slider->gambar) {
                // UPDATED: Hapus dari disk 'public_uploads'
                Storage::disk('public_uploads')->delete($slider->gambar);
            }
            // UPDATED: Simpan ke disk 'public_uploads'
            $validated['gambar'] = $request->file('gambar')->store('slider_images', 'public_uploads');
        }

        $slider->update($validated);

        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil diperbarui.');
    }


    public function destroy(Slider $slider)
    {
        if ($slider->gambar) {
            // UPDATED: Hapus dari disk 'public_uploads'
            Storage::disk('public_uploads')->delete($slider->gambar);
        }
        
        $slider->delete();

        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil dihapus.');
    }

    public function toggleStatus(Slider $slider)
    {
        $slider->is_visible = !$slider->is_visible;
        $slider->save();

        return back()->with('success', 'Status slider berhasil diubah.');
    }
}