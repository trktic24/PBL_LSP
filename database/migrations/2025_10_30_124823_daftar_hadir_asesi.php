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
        Schema::create('daftar_hadir_asesi', function (Blueprint $table) {
            $table->id('id_daftar_hadir_asesi');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom Database daftar_hadir_asesi
            $table->string('tanda_tangan_asesi')->comment('Path ke file tanda tangan asesi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_hadir_asesi');
    }
};
