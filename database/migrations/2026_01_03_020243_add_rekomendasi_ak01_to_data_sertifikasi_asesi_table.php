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
            $table->enum('rekomendasi_ak01', ['diterima', 'tidak diterima'])->nullable()->after('rekomendasi_mapa02');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_sertifikasi_asesi', function (Blueprint $table) {
            $table->dropColumn('rekomendasi_ak01');
        });
    }
};
