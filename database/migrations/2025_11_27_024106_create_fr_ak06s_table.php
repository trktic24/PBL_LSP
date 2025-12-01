<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fr_ak06s', function (Blueprint $table) {
            $table->id();

            // Informasi Dasar
            $table->string('nama_asesor')->nullable();
            $table->string('nama_peninjau')->nullable();
            $table->date('tanggal_peninjauan')->nullable();

            // Aspek yang ditinjau (Biasanya Checkbox Ya/Tidak)
            // Kita simpan sebagai String ('Ya'/'Tidak') atau JSON jika checkbox kompleks
            $table->string('prosedur_asesmen_sesuai')->nullable(); // Ya/Tidak
            $table->text('komentar_prosedur')->nullable();

            $table->string('rencana_asesmen_sesuai')->nullable(); // Ya/Tidak
            $table->text('komentar_rencana')->nullable();

            $table->string('pelaksanaan_asesmen_sesuai')->nullable(); // Ya/Tidak
            $table->text('komentar_pelaksanaan')->nullable();

            $table->string('keputusan_asesmen_sesuai')->nullable(); // Ya/Tidak
            $table->text('komentar_keputusan')->nullable();

            $table->string('umpan_balik_sesuai')->nullable(); // Ya/Tidak
            $table->text('komentar_umpan_balik')->nullable();

            // Rekomendasi
            $table->text('rekomendasi_perbaikan')->nullable();
            $table->text('aspek_dikembangkan')->nullable(); // Hal-hal yang perlu dikembangkan

            // Tanda Tangan (Simpan Nama)
            $table->string('ttd_peninjau')->nullable();
            $table->string('ttd_asesor')->nullable(); // Yang ditinjau

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fr_ak06s');
    }
};