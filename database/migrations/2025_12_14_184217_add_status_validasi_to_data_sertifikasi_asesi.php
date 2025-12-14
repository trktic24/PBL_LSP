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
        Schema::table('data_sertifikasi_asesi', function (Blueprint $table) {
        // Kita tambah kolom 'status_validasi'
        // nullable() penting agar data lama tidak error
        // default('pending') biar otomatis terisi pending saat dibuat
        $table->enum('status_validasi', ['pending', 'valid'])
              ->default('pending')
              ->nullable()
              ->after('rekomendasi_hasil_asesmen_AK02'); // Opsional: taruh setelah kolom ini biar rapi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_sertifikasi_asesi', function (Blueprint $table) {
            $table->dropColumn('status_validasi');
        });
    }
};
