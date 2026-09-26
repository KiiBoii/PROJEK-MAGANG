<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar (biasanya jamak/plural)
    protected $table = 'kontaks';

    // TAMBAHKAN 'status' DI SINI
    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'isi_pengaduan',
        'foto_pengaduan',
        'status', // <--- Wajib ada agar bisa di-update
        'balasan',     // <--- WAJIB DITAMBAHKAN
        'balasan_at',  // <--- WAJIB DITAMBAHKAN
    ];

    // Opsional: Casting status agar selalu string
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}