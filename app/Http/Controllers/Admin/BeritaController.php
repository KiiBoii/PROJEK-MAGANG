<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth; 

class BeritaController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $isRedaktur = ($user->role == 'redaktur');

        $query = Berita::query();

        if ($isRedaktur) {
            $query->where('user_id', $user->id);
        }

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
            $query->where('tag', $tag);
        }

        $beritas = $query->with('user')->latest()->paginate(9);
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($beritas);
        }

        return view('admin.berita.index', compact('beritas'));
    }


    public function create()
    {
        return view('admin.berita.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tag' => 'nullable|string|in:info,layanan,kegiatan', 
        ]);

        if ($request->hasFile('gambar')) {
            // UPDATED: Menggunakan disk 'public_uploads'
            $validated['gambar'] = $request->file('gambar')->store('berita_images', 'public_uploads');
        }

        $validated['user_id'] = Auth::id(); 

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }


    public function show(Berita $berita)
    {
        return view('admin.berita.show', compact('berita'));
    }


    public function edit(Berita $berita)
    {
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan mengedit berita ini.');
        }

        return view('admin.berita.edit', compact('berita'));
    }

    
    public function update(Request $request, Berita $berita)
    {
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan memperbarui berita ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tag' => 'nullable|string|in:info,layanan,kegiatan', 
        ]);

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                // UPDATED: Hapus dari disk 'public_uploads'
                Storage::disk('public_uploads')->delete($berita->gambar);
            }
            // UPDATED: Simpan ke disk 'public_uploads'
            $validated['gambar'] = $request->file('gambar')->store('berita_images', 'public_uploads');
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    
    public function destroy(Request $request, Berita $berita)
    {
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Anda tidak diizinkan menghapus berita ini.'], 403); 
            }
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan menghapus berita ini.');
        }

        if ($berita->gambar) {
            // UPDATED: Hapus dari disk 'public_uploads'
            Storage::disk('public_uploads')->delete($berita->gambar);
        }

        $berita->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Berita berhasil dihapus.']);
        }

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function toggleStatus(Berita $berita)
    {
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan mengubah status berita ini.');
        }

        $berita->is_visible = !$berita->is_visible;
        $berita->save();

        return back()->with('success', 'Status berita berhasil diubah.');
    }
}