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
            $table->id('id_ia11');

            // Kolom Relasi (M:1 ke Data Sertifikasi Asesi)
            $table->unsignedBigInteger('id_data_sertifikasi_asesi');
            
            // Kolom Form Normal
            $table->longText('rancangan_produk')->nullable(); 
            $table->string('nama_produk')->nullable();
            $table->string('standar_industri')->nullable();
            $table->date('tanggal_pengoperasian')->nullable();
            $table->string('gambar_produk')->nullable();
            
            // Timestamps
            $table->timestamps();

            $table->foreign('id_data_sertifikasi_asesi')
                  ->references('id_data_sertifikasi_asesi') 
                  ->on('data_sertifikasi_asesi')
                  ->onDelete('cascade');
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