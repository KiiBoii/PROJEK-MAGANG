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

    public function index()
    {
        $dokumens = Dokumen::latest()->paginate(10); 
        
        return view('admin.dokumen.index', compact('dokumens'));
    }


    public function create()
    {
        return view('admin.dokumen.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:50000', 
        ]);

        if ($request->hasFile('dokumen_file')) {
            $file = $request->file('dokumen_file');
            
            // UPDATED: Simpan ke disk 'public_uploads'
            $path = $file->store('dokumen_publikasi', 'public_uploads');
            
            Dokumen::create([
                'judul' => $validated['judul'],
                'keterangan' => $validated['keterangan'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(), 
            ]);
        }

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }


    public function edit(Dokumen $dokumen)
    {
        return view('admin.dokumen.edit', compact('dokumen'));
    }


    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:50000', 
        ]);

        if ($request->hasFile('dokumen_file')) {
            if ($dokumen->file_path) {
                // UPDATED: Hapus dari disk 'public_uploads'
                Storage::disk('public_uploads')->delete($dokumen->file_path);
            }
            
            $file = $request->file('dokumen_file');
            // UPDATED: Simpan ke disk 'public_uploads'
            $path = $file->store('dokumen_publikasi', 'public_uploads');

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


    public function destroy(Dokumen $dokumen)
    {
        try {
            if ($dokumen->file_path) {
                // UPDATED: Hapus dari disk 'public_uploads'
                Storage::disk('public_uploads')->delete($dokumen->file_path);
            }
            
            $dokumen->forceDelete(); 

            return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.dokumen.index')->with('error', 'Gagal menghapus dokumen. Cek Foreign Key atau Database Error.');
        }
    }
}