<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import

class BeritaController extends Controller
{

    public function index(Request $request)
    {
        // ▼▼▼ TAMBAHAN LOGIKA ROLE ▼▼▼
        $user = Auth::user();
        $isRedaktur = ($user->role == 'redaktur');
        // ▲▲▲ AKHIR TAMBAHAN ▲▲▲

        $query = Berita::query();

        // ▼▼▼ TAMBAHKAN KONDISI INI ▼▼▼
        // Jika yang login adalah redaktur, filter query agar hanya
        // menampilkan berita dengan user_id miliknya.
        if ($isRedaktur) {
            $query->where('user_id', $user->id);
        }
        // ▲▲▲ AKHIR TAMBAHAN ▲▲▲

        // --- Logika Filter Tanggal ---
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

        // Query ini sekarang sudah terfilter berdasarkan role (jika redaktur)
        // dan filter tanggal/tag.
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
            $validated['gambar'] = $request->file('gambar')->store('berita_images', 'public');
        }

        // Ini sudah benar, otomatis menyimpan user_id si pembuat
        $validated['user_id'] = Auth::id(); 

        Berita::create($validated);

        // ▼▼▼ PERBAIKAN 1 (Ini yang menyebabkan error Anda) ▼▼▼
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }


    public function show(Berita $berita)
    {
        return view('admin.berita.show', compact('berita'));
    }


    public function edit(Berita $berita)
    {
        // ▼▼▼ TAMBAHAN KEAMANAN ▼▼▼
        // Cek apakah user adalah redaktur dan apakah berita ini BUKAN miliknya
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            // Jika bukan, lempar kembali ke index dengan pesan error
            // ▼▼▼ PERBAIKAN 2 ▼▼▼
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan mengedit berita ini.');
        }
        // ▲▲▲ AKHIR TAMBAHAN ▲▲▲

        return view('admin.berita.edit', compact('berita'));
    }

    
    public function update(Request $request, Berita $berita)
    {
        // ▼▼▼ TAMBAHAN KEAMANAN ▼▼▼
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            // ▼▼▼ PERBAIKAN 3 ▼▼▼
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan memperbarui berita ini.');
        }
        // ▲▲▲ AKHIR TAMBAHAN ▲▲▲

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tag' => 'nullable|string|in:info,layanan,kegiatan', 
        ]);

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('berita_images', 'public');
        }

        $berita->update($validated);

        // ▼▼▼ PERBAIKAN 4 ▼▼▼
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    
    public function destroy(Request $request, Berita $berita)
    {
        // ▼▼▼ TAMBAHAN KEAMANAN ▼▼▼
        $user = Auth::user();
        if ($user->role == 'redaktur' && $berita->user_id != $user->id) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Anda tidak diizinkan menghapus berita ini.'], 403); // 403 Forbidden
            }
            // ▼▼▼ PERBAIKAN 5 ▼▼▼
            return redirect()->route('admin.berita.index')->with('error', 'Anda tidak diizinkan menghapus berita ini.');
        }
        // ▲▲▲ AKHIR TAMBAHAN ▲▲▲

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Berita berhasil dihapus.']);
        }

        // ▼▼▼ PERBAIKAN 6 ▼▼▼
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}