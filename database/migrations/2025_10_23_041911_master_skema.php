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
        Schema::create('skema', function (Blueprint $table) {
            // Sesuai ERD: id_skema (PK)
            // Ini akan membuat kolom BIGINT UNSIGNED AUTO_INCREMENT
            $table->id('id_skema');
            $table->foreignId('category_id')->constrained('categories', 'id')->onUpdate('cascade')->onDelete('cascade')->nullable();

            // kode_unit (int) - Saya ubah jadi string
            // Alasan: Kode unit seringkali mengandung titik atau huruf (misal: J.620100.001.01)
            // Jika ini adalah foreign key ke tabel 'unit_kompetensi', sesuaikan
            $table->string('nomor_skema')->unique();

            // nama_skema (str)
            $table->string('nama_skema');

            // Deskripsi_skema (str) - Saya ubah jadi text
            // Alasan: Deskripsi biasanya panjang dan melebihi 255 karakter
            $table->text('deskripsi_skema');
            $table->bigInteger('harga')->nullable();


            // SKKNI
            $table->string('SKKNI')->comment('File pdf atau dokumen terkait SKKNI')->nullable();

            // Gambar Skema
            $table->string('gambar')->comment('path untuk unggah gambar')->nullable();

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
