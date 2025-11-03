<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan; // Import Model Pengaduan
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource (READ-Only).
     */
    public function index()
    {
        $pengaduans = Pengaduan::latest()->get();
        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    // Tidak ada method create, store, edit, update, destroy karena READ-Only
}