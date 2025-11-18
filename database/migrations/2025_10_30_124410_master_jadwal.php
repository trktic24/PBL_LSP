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
<<<<<<< HEAD
            $table->integer('kuota_maksimal')->comment('jumlah maksimal peserta');
            $table->integer('kuota_minimal')->nullable()->default(15)->comment('jumlah minimal peserta');
            $table->integer('sesi')->comment('daftar Sesi');
            $table->dateTime('tanggal_mulai')->comment('tanggal Mulai pendaftaran');
            $table->dateTime('tanggal_selesai')->comment('tanggal Selesai pendaftaran');
            $table->date('tanggal_pelaksanaan')->comment('tanggal pelaksanaan');
            $table->time('waktu_mulai')->comment('Waktu Mulai pelaksanaan');
            $table->enum('Status_jadwal', ['Terjadwal', 'Selesai', 'Dibatalkan'])->comment('Status jadwal saat ini');
=======
            $table->integer('sesi')->comment('daftar Sesi');
            $table->dateTime('tanggal_mulai')->comment('tanggal Mulai pendaftaran');
            $table->dateTime('tanggal_selesai')->comment('tanggal Selesai pendaftaran');
            $table->dateTime('tanggal_pelaksanaan')->comment('tanggal pelaksanaan');
            $table->string('Status_jadwal')->comment('Status bisa berupa: Terjadwal, Selesai, Dibatalkan');
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
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
