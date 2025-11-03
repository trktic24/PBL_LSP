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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('id_jadwal');

            // foreign key ke manapun
            $table->foreignId('id_jenis_tuk')->constrained('jenis_tuk', 'id_jenis_tuk')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_tuk')->constrained('master_tuk', 'id_tuk')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_skema')->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_asesor')->constrained('asesor', 'id_asesor')->onUpdate('cascade')->onDelete('restrict');

            // Isi kolom
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_pelaksanaan');
            $table->string('Status_jadwal')->comment('Status bisa berupa: Terjadwal, Selesai, Dibatalkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
