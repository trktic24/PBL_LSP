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
            $table->foreignId('id_data_sertifikasi_asesi')
              ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            // isi kolom Database daftar_hadir_asesi
            $table->boolean('hadir')->default(false)->comment('jika 1(hadir), jika 0(tidak hadir)');
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
