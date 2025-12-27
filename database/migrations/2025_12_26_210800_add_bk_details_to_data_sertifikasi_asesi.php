<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Menambahkan kolom detail "Belum Kompeten" untuk IA01
     */
    public function up(): void
    {
        Schema::table('data_sertifikasi_asesi', function (Blueprint $table) {
            $table->text('bk_unit_ia01')->nullable()->after('feedback_ia01')->comment('Unit Kompetensi yang belum kompeten');
            $table->text('bk_elemen_ia01')->nullable()->after('bk_unit_ia01')->comment('Elemen yang belum kompeten');
            $table->text('bk_kuk_ia01')->nullable()->after('bk_elemen_ia01')->comment('Nomor KUK yang belum kompeten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_sertifikasi_asesi', function (Blueprint $table) {
            $table->dropColumn(['bk_unit_ia01', 'bk_elemen_ia01', 'bk_kuk_ia01']);
        });
    }
};
