<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fr_mapa01s', function (Blueprint $table) {
            $table->id();

            // 1. Pendekatan Asesmen
            $table->json('asesi_pendekatan')->nullable(); // Checkbox
            $table->string('tujuan_sertifikasi')->nullable(); // Radio
            $table->json('konteks_lingkungan')->nullable(); // Checkbox
            $table->json('konteks_peluang')->nullable(); // Checkbox
            $table->json('konteks_pelaku')->nullable(); // Checkbox
            $table->json('konfirmasi_relevan')->nullable(); // Checkbox

            // 2. Perencanaan Asesmen (Tabel Unit)
            // Unit 1
            $table->json('unit_kompetensi')->nullable();
            $table->text('unit1_bukti')->nullable();
            $table->json('unit1_jenis_bukti')->nullable();
            $table->json('unit1_metode')->nullable();
            // Unit 2
            $table->text('unit2_bukti')->nullable();
            $table->json('unit2_jenis_bukti')->nullable();
            $table->json('unit2_metode')->nullable();

            // 3. Modifikasi & Kontekstualisasi
            $table->text('karakteristik_kandidat')->nullable();
            $table->text('kebutuhan_kontekstualisasi')->nullable();
            $table->text('saran_paket')->nullable();
            $table->text('penyesuaian_perangkat')->nullable();
            $table->json('penyusun')->nullable();

            // Tanda Tangan (Simpan Nama saja dulu)
            $table->string('ttd_manajer_lsp')->nullable();
            $table->string('ttd_master_asesor')->nullable();
            $table->string('ttd_manajer_pelatihan')->nullable();
            $table->string('ttd_supervisor')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fr_mapa01s');
    }
};  