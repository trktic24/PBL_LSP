<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel).
     */
    public function up(): void
    {
        Schema::create('ia09_forms', function (Blueprint $table) {
            $table->id();
            
            // --- Foreign Keys (DEKLARASI KOLOM: Menggunakan BIGINT) ---
            // Harus BIGINT karena tabel asesi menggunakan $table->id()
            $table->unsignedBigInteger('asesi_id');
            $table->unsignedBigInteger('asesor_id');
            $table->unsignedBigInteger('skema_id');

            // --- Data Utama Form ---
            $table->date('tanggal_asesmen'); 
            $table->string('tuk'); 
            $table->string('rekomendasi_asesor', 2)->nullable();
            $table->text('catatan_asesor')->nullable();

            // --- Kolom Denormalisasi (JSON) ---
            $table->json('questions')->nullable(); 
            $table->json('units')->nullable();
            $table->timestamps();

            // --- Foreign Keys (PERBAIKAN FINAL: Nama Tabel TUNGGAL & Kunci Kustom) ---
            // Merujuk ke tabel TUNGGAL ('asesi') dan kunci kustom ('id_asesi')
            $table->foreign('asesi_id')->references('id_asesi')->on('asesi')->onDelete('cascade');
            // Asumsi pola yang sama untuk Asesor dan Skema
            $table->foreign('asesor_id')->references('id_asesor')->on('asesor')->onDelete('cascade');
            $table->foreign('skema_id')->references('id_skema')->on('skema')->onDelete('cascade');
        });
    }

    /**
     * Balikkan migrasi (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('ia09_forms');
    }
};