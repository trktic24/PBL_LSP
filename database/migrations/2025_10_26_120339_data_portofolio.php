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
        Schema::create('portofolio', function (Blueprint $table) {
            $table->id('id_portofolio');
            $table->foreignId('id_asesi')->constrained('asesi', 'id_asesi')->onUpdate('cascade')->onDelete('restrict');
            $table->string('persyaratan_dasar')->comment('Sertakan dokumen');
            $table->string('persyaratan_administratif')->comment('Sertakan dokumen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portofolio');
    }
};
