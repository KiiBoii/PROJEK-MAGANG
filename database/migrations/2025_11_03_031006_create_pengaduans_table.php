<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// ...
public function up(): void
{
    Schema::create('pengaduans', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('status_pengirim')->nullable(); // Contoh: Mahasiswa, SMA, Umum
        $table->text('isi_pengaduan');
        $table->string('foto_pengadu')->nullable(); // Jika ada foto pengadu
        $table->timestamps();
    });
}
// ...

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
