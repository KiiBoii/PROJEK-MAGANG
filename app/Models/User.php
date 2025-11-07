<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// <-- 1. IMPORT MODEL-MODEL YANG AKAN DIHUBUNGKAN -->
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pengumuman;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan',
        'departemen',
        'telepon',
        'alamat',
        'role', // <-- 2. TAMBAHKAN 'role' AGAR BISA DISIMPAN -->
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * === 3. TAMBAHKAN RELASI INI UNTUK DASHBOARD ===
     * Menghubungkan User ke konten yang mereka buat.
     */
    public function beritas()
    {
        return $this->hasMany(Berita::class);
    }

    public function galeriFotos()
    {
        // Nama fungsi ini bebas, tapi pastikan relasinya ke Model Galeri
        return $this->hasMany(Galeri::class); 
    }

    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class);
    }
}