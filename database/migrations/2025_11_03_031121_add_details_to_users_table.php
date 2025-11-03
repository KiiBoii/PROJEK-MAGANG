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
    Schema::table('users', function (Blueprint $table) {
        $table->string('jabatan')->nullable()->after('email'); // Contoh
        $table->string('departemen')->nullable()->after('jabatan');
        $table->string('telepon')->nullable()->after('departemen');
        $table->string('alamat')->nullable()->after('telepon');
        // Anda bisa tambahkan kolom lain sesuai kebutuhan UI (misal: 'status_kesehatan', 'file_dokumen')
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['jabatan', 'departemen', 'telepon', 'alamat']);
    });
}
// ...
};
