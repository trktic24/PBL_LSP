<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'banding'.
     */
    public public function up(): void
    {
        Schema::create('banding', function (Blueprint $table) {
            $table->id('id_banding'); // Primary Key

            // Foreign Key (FK) ke tabel 'asesmen'
            // Asumsi tabel 'asesmen' sudah ada dengan PK 'id_asesmen'
            $table->unsignedBigInteger('id_asesmen');
            $table->foreign('id_asesmen')->references('id_asesmen')->on('asesmen')->onDelete('cascade');
            
            // Foreign Key (FK) ke tabel 'asesi'
            // Asumsi tabel 'asesi' sudah ada dengan PK 'id_asesi'
            $table->unsignedBigInteger('id_asesi');
            $table->foreign('id_asesi')->references('id_asesi')->on('asesi')->onDelete('cascade');

            // Data TUK (Boolean, default false)
            $table->boolean('tuk_sewaktu')->default(false);
            $table->boolean('tuk_tempatkerja')->default(false);
            $table->boolean('tuk_mandiri')->default(false);
            
            // Data Ya/Tidak (Enum)
            $table->enum('ya_tidak_1', ['Ya', 'Tidak']);
            $table->enum('ya_tidak_2', ['Ya', 'Tidak']);
            $table->enum('ya_tidak_3', ['Ya', 'Tidak']);

            $table->text('alasan_banding');
            $table->date('tanggal_pengajuan_banding');

            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration (rollback).
     */
    public public function down(): void
    {
        Schema::dropIfExists('banding');
    }
};