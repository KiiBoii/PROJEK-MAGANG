<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kontaks', function (Blueprint $table) {
            // Menambahkan kolom balasan dan balasan_at setelah isi_pengaduan
            $table->text('balasan')->nullable()->after('isi_pengaduan');
            $table->timestamp('balasan_at')->nullable()->after('balasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontaks', function (Blueprint $table) {
            $table->dropColumn(['balasan', 'balasan_at']);
        });
    }
};