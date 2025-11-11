<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- Import model User
use Illuminate\Support\Facades\Hash; // <-- Import Hash untuk password

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat/Update User ADMIN (Menggunakan updateOrCreate)
        User::updateOrCreate(
            ['email' => 'admin@dinsos.com'], // <-- Cari berdasarkan email ini
            [ // <-- Jika ditemukan, update. Jika tidak, buat dengan data ini.
                'name' => 'Admin Dinsos',
                'role' => 'admin',
                'email_verified_at' => now(), 
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Buat/Update User REDAKTUR (Menggunakan updateOrCreate)
        User::updateOrCreate(
            ['email' => 'berita@dinsos.com'], // <-- Tetap cari berdasarkan email ini
            [ // <-- Data untuk di-update atau di-create
                'name' => 'Redaktur Dinsos', // <-- Nama diubah agar sesuai
                'role' => 'redaktur', // <-- [PERUBAHAN] Role diubah ke 'redaktur'
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
            ]
        );
    }
}