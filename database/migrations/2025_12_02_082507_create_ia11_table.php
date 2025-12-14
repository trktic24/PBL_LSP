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
        Schema::create('ia11', function (Blueprint $table) {
            $table->id('id_ia11'); // Primary Key

            // Kolom Relasi (Wajib ada datanya agar showSingle bisa mencari)
            $table->unsignedBigInteger('id_data_sertifikasi_asesi');
            $table->unsignedBigInteger('id_spesifikasi_produk_ia11')->nullable();

            // Kolom Form Normal (Bisa diisi Asesi)
            $table->string('nama_produk')->nullable();
            $table->string('standar_industri')->nullable();
            $table->date('tanggal_pengoperasian')->nullable();
            $table->string('gambar_produk')->nullable();
            
            // Kolom JSON untuk data Asesor dan data spesifik Asesi
            // Menggunakan longText atau json() disarankan
            $table->longText('rancangan_produk')->nullable(); 

            // Timestamps
            $table->timestamps();

            // Opsional: Foreign Key (sesuaikan dengan nama tabel yang benar)
            // $table->foreign('id_data_sertifikasi_asesi')->references('id_sertifikasi')->on('data_sertifikasi_asesi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia11');
    }
};