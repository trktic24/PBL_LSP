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
        Schema::create('respon_diperlukan_penyesuaian_AK07', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_diperlukan_penyesuaian_AK07');

            // --- INI LOGIKA PIVOT (PENYIMPAN CENTANG) ---

            // FK 1: Siapa yang ngisi
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'resp_ak07_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // FK 2: BARIS mana yang dicentang
            $table->foreignId('id_persyaratan_modifikasi_AK07');
            $table->foreign('id_persyaratan_modifikasi_AK07', 'resp_ak07_syarat_fk')
                  ->references('id_persyaratan_modifikasi_AK07')->on('Persyaratan_Modifikasi_AK07')
                  ->onUpdate('cascade')->onDelete('cascade');

            // FK 3: KOLOM (Keterangan) mana yang dicentang
            // Kita buat nullable() karena mungkin dia cuma nyentang "Ya/Tidak"
            $table->foreignId('id_catatan_keterangan_AK07')->nullable();
            $table->foreign('id_catatan_keterangan_AK07', 'resp_ak07_catatan_fk')
                  ->references('id_catatan_keterangan_AK07')->on('catatan_keterangan_AK07')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- Kolom sisa dari ERD ---

            // Ini buat nyimpen centang "Ya / Tidak"
            $table->boolean('respon_penyesuaian')->nullable();

            // Ini buat nyimpen teks "Lainnya..."
            $table->text('respon_catatan_keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_diperlukan_penyesuaian_AK07');
    }
};
