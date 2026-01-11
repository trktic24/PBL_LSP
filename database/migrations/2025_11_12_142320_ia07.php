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
        Schema::create('ia07', function (Blueprint $table) {
            $table->id('id_ia07');

            $table->foreignId('id_data_sertifikasi_asesi')
                ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Relasi ke Unit Kompetensi
            $table->unsignedBigInteger('id_unit_kompetensi')->nullable();
            $table->foreign('id_unit_kompetensi', 'fk_ia07_unit_kompetensi')
                ->references('id_unit_kompetensi')
                ->on('unit_kompetensi')
                ->cascadeOnDelete();

            $table->text('pertanyaan');
            $table->text('jawaban_asesi')->nullable();
            $table->text('jawaban_diharapkan');

            // Nullable by design (belum dinilai)
            $table->boolean('pencapaian')
                ->nullable()
                ->comment('1 = Ya, 0 = Tidak, NULL = Belum dinilai');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia07');
    }
};