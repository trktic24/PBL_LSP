<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fr_mapa01s', function (Blueprint $table) {
            $table->unsignedBigInteger('id_data_sertifikasi_asesi')->nullable()->after('id');
            $table->foreign('id_data_sertifikasi_asesi', 'fk_mapa01_sertifikasi')->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fr_mapa01s', function (Blueprint $table) {
            $table->dropForeign('fk_mapa01_sertifikasi');
            $table->dropColumn('id_data_sertifikasi_asesi');
        });
    }
};
