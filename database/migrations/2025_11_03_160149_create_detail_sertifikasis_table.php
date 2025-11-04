<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_sertifikasis', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('id_skema')
                  ->constrained('skema', 'id_skema')
                  ->onDelete('cascade');

            $table->string('deskripsi_detail'); // Teks bullet point

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_sertifikasis');
    }
};