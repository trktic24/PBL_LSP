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
        Schema::create('detail_ia10', function (Blueprint $table) {
            $table->id('id_detail_ia10');

            // Foreign key ke ia10
            $table->foreignId('id_ia10')
                ->constrained('ia10', 'id_ia10')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Bagian form (pertanyaan terbuka)
            $table->text('isi_detail')->nullable()->comment('Isian detail IA10');
            $table->text('jawaban')->nullable()->comment('Jawaban detail IA10');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_ia10');
    }
};
