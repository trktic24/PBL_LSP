<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umpan_balik_ia06', function (Blueprint $table) {
            $table->id('id_umpan_balik_ia06');

            // Terhubung ke Asesi, bukan ke Soal
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Isi Feedback
            $table->text('umpan_balik')->nullable()->comment('Catatan dari asesor untuk asesi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umpan_balik_ia06');
    }
};