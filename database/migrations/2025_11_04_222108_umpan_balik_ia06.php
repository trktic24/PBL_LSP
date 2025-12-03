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
        Schema::create('umpan_balik_ia06', function (Blueprint $table) {
            $table->id('id_umpan_balik_ia06');

            // HAPUS baris foreign key ke soal_ia06 ini:
            // $table->foreignId('id_soal_ia06')...

            // CUKUP pertahankan relasi ke asesi:
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->text('umpan_balik')->nullable()->comment('Berisi umpan balik dari penilai untuk seluruh jawaban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umpan_balik_ia06');
    }
};
