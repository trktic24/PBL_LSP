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
        Schema::create('banding_asesmen', function (Blueprint $table) {
            $table->id();
            
            // Kolom Data Asesmen
            $table->string('nama_asesi');
            $table->string('nama_asesor');
            $table->date('tanggal_asesmen');

            // Kolom Pertanyaan Ya/Tidak
            $table->boolean('proses_banding_dijelaskan')->comment('Apakah Proses Banding telah dijelaskan kepada Anda?');
            $table->boolean('diskusi_banding_dengan_asesor')->comment('Apakah Anda telah mendiskusikan Banding dengan Asesor?');
            $table->boolean('melibatkan_orang_lain')->comment('Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?');

            // Kolom Skema Sertifikasi
            $table->string('skema_sertifikasi');
            $table->string('no_skema_sertifikasi');

            // Kolom Alasan Banding
            $table->text('alasan_banding');
            
            // Kolom Tanda Tangan dan Tanggal (untuk record data)
            // Tanda tangan biasanya disimpan sebagai path file atau string base64 jika menggunakan tanda tangan digital sederhana
            $table->string('tanda_tangan_asesi')->nullable(); 
            $table->date('tanggal_pengajuan_banding');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banding_asesmen');
    }
};