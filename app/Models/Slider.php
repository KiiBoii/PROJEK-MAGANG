<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'keterangan',
        'gambar',
        'halaman',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean', // Otomatis ubah 1/0 menjadi true/false
    ];
}