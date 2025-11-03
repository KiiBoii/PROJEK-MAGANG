<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang seharusnya digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'pengumumans'; // <-- TAMBAHKAN BARIS INI

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'isi',
    ];
}