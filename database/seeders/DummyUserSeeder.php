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
        // Buat satu user baru
        User::create([
            'name' => 'Admin Dinsos',
            'email' => 'admin@dinsos.com',
            'email_verified_at' => now(), // <-- Langsung verifikasi emailnya
            'password' => Hash::make('12345678'), // <-- Passwordnya '12345678'
        ]);
    }
}