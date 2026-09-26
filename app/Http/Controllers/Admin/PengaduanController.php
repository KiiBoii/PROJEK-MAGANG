<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar pengaduan dengan filter status.
     */
    public function index(Request $request)
    {
        // Ambil status dari query parameter (default: semua/null)
        $status = $request->get('status');

        // Query dasar
        $query = Kontak::latest();

        // Jika ada status yang dipilih (bukan 'semua'), filter datanya
        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        // Pagination 20 item per halaman
        $pengaduans = $query->paginate(20)->withQueryString();

        // Hitung jumlah data untuk badge di tab navigasi
        $counts = [
            'semua'    => Kontak::count(),
            'diajukan' => Kontak::where('status', 'diajukan')->count(),
            'diproses' => Kontak::where('status', 'diproses')->count(),
            'diterima' => Kontak::where('status', 'diterima')->count(),
            'selesai'  => Kontak::where('status', 'selesai')->count(),
            'ditolak'  => Kontak::where('status', 'ditolak')->count(),
        ];

        return view('admin.pengaduan.index', compact('pengaduans', 'counts', 'status'));
    }

    /**
     * Update status pengaduan.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diproses,diterima,ditolak,selesai',
        ]);

        $pengaduan = Kontak::findOrFail($id);
        
        $pengaduan->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui menjadi ' . ucfirst($request->status));
    }

    /**
     * Menyimpan / memperbarui balasan admin.
     */
    public function balas(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string',
            'status'  => 'nullable|in:diajukan,diproses,diterima,ditolak,selesai',
        ]);

        $pengaduan = Kontak::findOrFail($id);

        $updateData = [
            'balasan'    => $request->balasan,
            'balasan_at' => now(),
        ];

        // Opsional: Jika admin juga memilih perubahan status pada form balasan
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        $pengaduan->update($updateData);

        return redirect()->back()->with('success', 'Balasan pengaduan berhasil disimpan.');
    }

    /**
     * Menghapus balasan admin.
     */
    public function hapusBalasan($id)
    {
        $pengaduan = Kontak::findOrFail($id);

        $pengaduan->update([
            'balasan'    => null,
            'balasan_at' => null,
        ]);

        return redirect()->back()->with('success', 'Balasan pengaduan berhasil dihapus.');
    }

    /**
     * Menghapus seluruh data pengaduan.
     */
    public function destroy($id)
    {
        $pengaduan = Kontak::findOrFail($id);

        // Hapus foto pengaduan (jika ada)
        if ($pengaduan->foto_pengaduan) {
            Storage::disk('public_uploads')->delete($pengaduan->foto_pengaduan);
            if (Storage::disk('public')->exists($pengaduan->foto_pengaduan)) {
                Storage::disk('public')->delete($pengaduan->foto_pengaduan);
            }
        }

        $pengaduan->delete();

        return redirect()->back()->with('success', 'Pengaduan berhasil dihapus.');
    }
}