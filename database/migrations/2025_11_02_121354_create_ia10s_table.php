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
        Schema::create('ia10s', function (Blueprint $table) {
            $table->id();

            // Info dari form (Source 2 & 6)
            $table->string('nama_asesi')->nullable();
            $table->string('nama_asesor')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('workplace')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();

            // Pertanyaan Ya/Tidak (Source 7)
            $table->string('q1')->nullable(); // Pilihan 'ya' or 'tidak'
            $table->string('q2')->nullable();
            $table->string('q3')->nullable();
            $table->string('q4')->nullable();
            $table->string('q5')->nullable();
            $table->string('q6')->nullable();

            // Pertanyaan Terbuka (Source 8)
            $table->text('relation')->nullable();
            $table->text('duration')->nullable();
            $table->text('proximity')->nullable();
            $table->text('experience')->nullable();

            // Kesimpulan (Source 9)
            $table->text('consistency')->nullable();
            $table->text('training_needs')->nullable();
            $table->text('other_comments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia10s');
    }
};
