<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Galeri extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul_kegiatan',
        'foto_path',
        'bidang',
        'user_id',
    ];
        public function user()
    {
        // 'user_id' adalah foreign key di tabel 'beritas'
        return $this->belongsTo(User::class, 'user_id');
    }
}