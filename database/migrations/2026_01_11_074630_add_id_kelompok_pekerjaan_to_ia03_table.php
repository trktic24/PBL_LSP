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
        Schema::table('ia03', function (Blueprint $table) {
            // Tambah kolom id_kelompok_pekerjaan setelah id_data_sertifikasi_asesi
            $table->unsignedBigInteger('id_kelompok_pekerjaan')->nullable()->after('id_data_sertifikasi_asesi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ia03', function (Blueprint $table) {
            $table->dropColumn('id_kelompok_pekerjaan');
        });
    }
};