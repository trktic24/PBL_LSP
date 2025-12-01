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
        Schema::create('skema', function (Blueprint $table) {
            // Sesuai ERD: id_skema (PK)
            // Ini akan membuat kolom BIGINT UNSIGNED AUTO_INCREMENT
            $table->id('id_skema');
            $table->string('nomor_skema')->unique();

            // nama_skema (str)
            $table->string('nama_skema');

            // Deskripsi_skema (str) - Saya ubah jadi text
            // Alasan: Deskripsi biasanya panjang dan melebihi 255 karakter
            $table->text('deskripsi_skema');
            $table->bigInteger('harga')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            // SKKNI
            $table->string('SKKNI')->nullable()->comment('File pdf atau dokumen terkait SKKNI');

            // Gambar Skema
            $table->string('gambar')->nullable()->comment('path untuk unggah gambar');

            // Standar timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skema');
    }
};
