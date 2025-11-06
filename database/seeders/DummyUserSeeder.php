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
        // 1. Buat User ADMIN (Gunakan firstOrCreate)
        User::firstOrCreate(
            ['email' => 'admin@dinsos.com'], // <-- Cari berdasarkan email ini
            [ // <-- Jika tidak ada, buat dengan data ini
                'name' => 'Admin Dinsos',
                'role' => 'admin',
                'email_verified_at' => now(), 
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Buat User BERITA (Gunakan firstOrCreate)
        User::firstOrCreate(
            ['email' => 'berita@dinsos.com'], // <-- Cari berdasarkan email ini
            [ // <-- Jika tidak ada, buat dengan data ini
                'name' => 'Penulis Berita',
                'role' => 'berita',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
            ]
        );
    }
}