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
        // 1. Ubah 'galeriss' menjadi 'galeris'
        Schema::table('galeris', function (Blueprint $table) {
            // 2. Tambahkan kolomnya terlebih dahulu dengan posisi setelah 'id'
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // 3. Definisikan foreign key-nya secara terpisah agar aman di MySQL
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah 'galeriss' menjadi 'galeris'
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};