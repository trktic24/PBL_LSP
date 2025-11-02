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
        Schema::create('data_sertifikasi_asesi', function (Blueprint $table) {
            $table->id('id_data_sertifikasi_asesi');
            $table->foreignId('id_asesi')->constrained('asesi', 'id_asesi')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onUpdate('cascade')->onDelete('restrict');
            // Isi kolom yang dibutuhkan untuk data sertifikasi asesi
            $table->enum('rekomendasi_apl01', ['diterima', 'tidak diterima'])->comment('Apakah asesi mendapatkan rekomendasi dari APL-01 untuk lanjut asesmen pada APL-02');
            $table->enum('tujuan_asesmen',['sertifikasi', 'PKT', 'rekognisi pmbelajaran sebelumnya', 'lainnya'])->comment('Tujuan asesmen asesi');
            $table->enum('rekomendasi_apl02', ['diterima', 'tidak diterima'])->comment('Apakah asesi mendapatkan rekomendasi dari APL-02 untuk sertifikasi');
            $table->date('tanggal_daftar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
