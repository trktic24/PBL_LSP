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
        Schema::create('bukti_kelengkapan', function (Blueprint $table) {
            $table->id('id_bukti_kelengkapan');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // isi model DB 
            $table->enum('status_kelengkapan', ['memenuhi', 'tidak_memenuhi', 'tidak_ada']);
            $table->string('bukti_kelengkapan')->comment('Sertakan dokumen');
<<<<<<< HEAD
            $table->boolean('status_validasi')->default(false);
=======
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_kelengkapan');
    }
};
