<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('respon_hasil_ak03', function (Blueprint $table) {
        $table->id('id_respon_hasil_ak03');
        
        // Relasi ke Tabel Data Sertifikasi Asesi (Pastikan tabel ini sudah ada!)
        // Jika nama tabelnya 'data_sertifikasi_asesi' dan PK-nya 'id_data_sertifikasi_asesi'
        $table->unsignedBigInteger('id_data_sertifikasi_asesi');
        $table->foreign('id_data_sertifikasi_asesi')->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')->onDelete('cascade');

        // Relasi ke Tabel Master Poin AK03 (Pertanyaan)
        $table->unsignedBigInteger('id_poin_ak03');
        $table->foreign('id_poin_ak03')->references('id_poin_ak03')->on('poin_ak03')->onDelete('cascade');

        // Menyimpan Jawaban
        $table->enum('hasil', ['ya', 'tidak']); // Sesuai radio button
        $table->text('catatan')->nullable();    // Sesuai input text
        
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_hasil_ak03');
    }
};
