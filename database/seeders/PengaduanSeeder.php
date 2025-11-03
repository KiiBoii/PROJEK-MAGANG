<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaduan;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaduan::create([
            'nama' => 'Asep Suracep',
            'status_pengirim' => 'Mahasiswa',
            'isi_pengaduan' => 'Pelayanannya luar biasa! Responnya cepat dan masalah saya diselesaikan hanya dalam beberapa menit. Saya akan merekomendasikan service ini ke teman-teman.',
            // 'foto_pengadu' => 'path/to/asep.jpg' // Jika ada gambar default
        ]);

        Pengaduan::create([
            'nama' => 'Maman Resing',
            'status_pengirim' => 'Nganggur',
            'isi_pengaduan' => 'Secara keseluruhan bagus, tapi proses follow-up agak lambat. Saya harap nanti lebih responsif lagi ya.',
            // 'foto_pengadu' => 'path/to/maman.jpg'
        ]);

        // Tambahkan data dummy lainnya sesuai UI
    }
}