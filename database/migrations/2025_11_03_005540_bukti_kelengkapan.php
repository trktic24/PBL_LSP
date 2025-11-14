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

<<<<<<< HEAD

            $table->string('jenis_dokumen');
            $table->string('keterangan')->nullable();
            // isi model DB 
            $table->enum('status_kelengkapan', ['memenuhi', 'tidak_memenuhi', 'tidak_ada'])->default('tidak_ada');
            $table->string('bukti_kelengkapan')->comment('Path/nama file dokumen');
=======
            // isi model DB 
            $table->enum('status_kelengkapan', ['memenuhi', 'tidak_memenuhi', 'tidak_ada']);
            $table->string('bukti_kelengkapan')->comment('Sertakan dokumen');
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
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
