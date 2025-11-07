<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Berita extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'user_id',
    ];

    /**
     * === 1. TAMBAHKAN FUNGSI INI ===
     * Mendefinisikan relasi bahwa Berita 'dimiliki oleh' (belongsTo) satu User.
     * Ini akan memperbaiki error 'RelationNotFoundException' Anda.
     */
    public function user()
    {
        // 'user_id' adalah foreign key di tabel 'beritas'
        return $this->belongsTo(User::class, 'user_id');
    }
}