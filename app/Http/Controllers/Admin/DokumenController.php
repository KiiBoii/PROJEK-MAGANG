<?php

// app/Http/Controllers/Admin/DokumenController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DokumenController extends Controller
{
    /**
     * Menampilkan daftar semua dokumen.
     */
    public function index()
    {
        // PERUBAHAN: Mengganti get() dengan paginate() agar pagination berfungsi
        // Angka 15 adalah jumlah item per halaman, bisa disesuaikan
        $dokumens = Dokumen::latest()->paginate(10); 
        
        return view('admin.dokumen.index', compact('dokumens'));
    }

    /**
     * Menampilkan form untuk membuat dokumen baru.
     */
    public function create()
    {
        return view('admin.dokumen.create');
    }

    /**
     * Menyimpan dokumen baru ke storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:50000', // Maks 50MB (sesuaikan)
        ]);

        if ($request->hasFile('dokumen_file')) {
            $file = $request->file('dokumen_file');
            
            // Simpan file dan dapatkan path-nya
            $path = $file->store('dokumen_publikasi', 'public');
            
            Dokumen::create([
                'judul' => $validated['judul'],
                'keterangan' => $validated['keterangan'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(), // Nama file asli
            ]);
        }

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Menampilkan form edit dokumen.
     */
    public function edit(Dokumen $dokumen)
    {
        return view('admin.dokumen.edit', compact('dokumen'));
    }

    /**
     * Memperbarui dokumen.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:50000', 
        ]);

        if ($request->hasFile('dokumen_file')) {
            if ($dokumen->file_path) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            $file = $request->file('dokumen_file');
            $path = $file->store('dokumen_publikasi', 'public');

            $dokumen->update([
                'judul' => $validated['judul'],
                'keterangan' => $validated['keterangan'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        } else {
             $dokumen->update([
                 'judul' => $validated['judul'],
                 'keterangan' => $validated['keterangan'],
             ]);
        }

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Menghapus dokumen.
     */
    public function destroy(Dokumen $dokumen)
    {
        try {
            // Hapus file dari storage
            if ($dokumen->file_path) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            // PERUBAHAN KRUSIAL: Menggunakan forceDelete() untuk mengatasi Soft Deletes atau hambatan Model
            $dokumen->forceDelete(); 

            return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            // Jika ada kesalahan database (misalnya Foreign Key Constraint)
            // Log error $e (opsional)
            return redirect()->route('admin.dokumen.index')->with('error', 'Gagal menghapus dokumen. Cek Foreign Key atau Database Error.');
        }
    }
}