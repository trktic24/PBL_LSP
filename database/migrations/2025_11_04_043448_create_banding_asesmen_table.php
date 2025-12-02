<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'banding'.
     */
    public function up(): void
    {
        Schema::create('banding', function (Blueprint $table) {
            $table->id('id_banding'); // Primary Key

            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Data TUK (Boolean, default false)
            $table->boolean('tuk_sewaktu')->default(false);
            $table->boolean('tuk_tempatkerja')->default(false);
            $table->boolean('tuk_mandiri')->default(false);
            
            // Data Ya/Tidak (Enum)
            $table->enum('ya_tidak_1', ['Ya', 'Tidak']);
            $table->enum('ya_tidak_2', ['Ya', 'Tidak']);
            $table->enum('ya_tidak_3', ['Ya', 'Tidak']);

            $table->text('alasan_banding');
            $table->date('tanggal_pengajuan_banding')->default(DB::raw('CURRENT_DATE'));
            $table->longText('tanda_tangan_asesi');

            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('banding');
    }
};