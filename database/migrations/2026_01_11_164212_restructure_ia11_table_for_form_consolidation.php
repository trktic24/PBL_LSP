<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration consolidates the fields from the FR.IA.11 form into the main 'ia11' table,
     * simplifying the structure by removing related tables for checklist items and other details.
     */
    public function up(): void
    {
        // 1. Drop obsolete tables first to avoid foreign key issues.
        Schema::dropIfExists('pencapaian_performa_ia11');
        Schema::dropIfExists('pencapaian_spesifikasi_ia11');
        Schema::dropIfExists('spesifikasi_produk_ia11');
        Schema::dropIfExists('bahan_produk_ia11');
        Schema::dropIfExists('spesifikasi_teknis_ia11');
        // Master data tables `spesifikasi_ia11` and `performa_ia11` are kept in case they are used elsewhere.

        // 2. Add new columns to the 'ia11' table to match the form fields.
        Schema::table('ia11', function (Blueprint $table) {
            // Header fields
            $table->string('tuk_type')->nullable()->after('id_data_sertifikasi_asesi');
            $table->date('tanggal_asesmen')->nullable()->after('tuk_type');

            // Rancangan Produk / Data Teknis fields
            $table->text('spesifikasi_umum')->nullable()->after('rancangan_produk');
            $table->string('dimensi_produk')->nullable()->after('spesifikasi_umum');
            $table->string('bahan_produk')->nullable()->after('dimensi_produk');
            $table->text('spesifikasi_teknis')->nullable()->after('bahan_produk');

            // Checklist fields
            // For each item, a nullable boolean represents 'Ya' (true), 'Tidak' (false), or not selected (null).
            // Spesifikasi Produk
            $table->boolean('h1a_hasil')->nullable()->comment('Hasil: Ukuran produk sesuai rencana');
            $table->boolean('p1a_pencapaian')->nullable()->comment('Pencapaian: Ukuran produk sesuai rencana');
            $table->boolean('h1b_hasil')->nullable()->comment('Hasil: Estetika/penampilan produk');
            $table->boolean('p1b_pencapaian')->nullable()->comment('Pencapaian: Estetika/penampilan produk');
            // Performa Produk
            $table->boolean('h2a_hasil')->nullable()->comment('Hasil: Kebersihan dan kerapian produk');
            $table->boolean('p2a_pencapaian')->nullable()->comment('Pencapaian: Kebersihan dan kerapian produk');
            // Keselamatan dan Keamanan
            $table->boolean('h3a_hasil')->nullable()->comment('Hasil: Kesesuaian dengan gambar kerja');
            $table->boolean('p3a_pencapaian')->nullable()->comment('Pencapaian: Kesesuaian dengan gambar kerja');
            $table->boolean('h3b_hasil')->nullable()->comment('Hasil: Kerapian dan kerapatan sambungan');
            $table->boolean('p3b_pencapaian')->nullable()->comment('Pencapaian: Kerapian dan kerapatan sambungan');
            $table->boolean('h3c_hasil')->nullable()->comment('Hasil: Pemasangan perlengkapan');
            $table->boolean('p3c_pencapaian')->nullable()->comment('Pencapaian: Pemasangan perlengkapan');

            // Rekomendasi Asesor fields
            $table->string('rekomendasi_kelompok')->nullable();
            $table->string('rekomendasi_unit')->nullable();

            // Tanda Tangan fields
            $table->text('ttd_asesi')->nullable();
            $table->text('ttd_asesor')->nullable();

            // Catatan Asesor
            $table->text('catatan_asesor')->nullable();

            // Penyusun & Validator fields
            $table->string('penyusun_nama_1')->nullable();
            $table->string('penyusun_nomor_met_1')->nullable();
            $table->text('penyusun_ttd_1')->nullable();
            $table->string('penyusun_nama_2')->nullable();
            $table->string('penyusun_nomor_met_2')->nullable();
            $table->text('penyusun_ttd_2')->nullable();
            $table->string('validator_nama_1')->nullable();
            $table->string('validator_nomor_met_1')->nullable();
            $table->text('validator_ttd_1')->nullable();
            $table->string('validator_nama_2')->nullable();
            $table->string('validator_nomor_met_2')->nullable();
            $table->text('validator_ttd_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method restores the previous database structure by re-creating the dropped tables
     * and removing the columns added to the 'ia11' table.
     */
    public function down(): void
    {
        // 1. Drop the columns added in the 'up' method.
        Schema::table('ia11', function (Blueprint $table) {
            $table->dropColumn([
                'tuk_type', 'tanggal_asesmen', 'spesifikasi_umum', 'dimensi_produk', 'bahan_produk',
                'spesifikasi_teknis', 'h1a_hasil', 'p1a_pencapaian', 'h1b_hasil', 'p1b_pencapaian',
                'h2a_hasil', 'p2a_pencapaian', 'h3a_hasil', 'p3a_pencapaian', 'h3b_hasil', 'p3b_pencapaian',
                'h3c_hasil', 'p3c_pencapaian', 'rekomendasi_kelompok', 'rekomendasi_unit', 'ttd_asesi',
                'ttd_asesor', 'catatan_asesor', 'penyusun_nama_1', 'penyusun_nomor_met_1', 'penyusun_ttd_1',
                'penyusun_nama_2', 'penyusun_nomor_met_2', 'penyusun_ttd_2', 'validator_nama_1',
                'validator_nomor_met_1', 'validator_ttd_1', 'validator_nama_2', 'validator_nomor_met_2', 'validator_ttd_2'
            ]);
        });

        // 2. Re-create the dropped tables with their original schema.
        Schema::create('spesifikasi_produk_ia11', function (Blueprint $table) {
            $table->id('id_spesifikasi_produk_ia11');
            $table->unsignedBigInteger('id_ia11')->unique();
            $table->string('dimensi_produk')->nullable();
            $table->string('berat_produk')->nullable();
            $table->timestamps();
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
        });

        Schema::create('spesifikasi_teknis_ia11', function (Blueprint $table) {
            $table->id('id_spesifikasi_teknis_ia11');
            $table->unsignedBigInteger('id_ia11');
            $table->text('data_teknis')->nullable();
            $table->timestamps();
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
        });

        Schema::create('bahan_produk_ia11', function (Blueprint $table) {
            $table->id('id_bahan_produk_ia11');
            $table->unsignedBigInteger('id_ia11');
            $table->string('nama_bahan');
            $table->timestamps();
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
        });

        Schema::create('pencapaian_spesifikasi_ia11', function (Blueprint $table) {
            $table->bigIncrements('id_pencapaian_spesifikasi_ia11');
            $table->unsignedBigInteger('id_ia11');
            $table->unsignedBigInteger('id_spesifikasi_ia11');
            $table->boolean('hasil_reviu')->default(false);
            $table->text('catatan_temuan')->nullable();
            $table->timestamps();
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
            $table->foreign('id_spesifikasi_ia11')->references('id_spesifikasi_ia11')->on('spesifikasi_ia11')->onDelete('cascade');
            $table->unique(['id_ia11', 'id_spesifikasi_ia11'], 'ia11_spesifikasi_unique');
            $table->index('id_ia11');
            $table->index('id_spesifikasi_ia11');
        });

        Schema::create('pencapaian_performa_ia11', function (Blueprint $table) {
            $table->bigIncrements('id_pencapaian_performa_ia11');
            $table->unsignedBigInteger('id_ia11');
            $table->unsignedBigInteger('id_performa_ia11');
            $table->boolean('hasil_reviu');
            $table->text('catatan_temuan')->nullable();
            $table->timestamps();
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
            $table->foreign('id_performa_ia11')->references('id_performa_ia11')->on('performa_ia11')->onDelete('restrict');
            $table->unique(['id_ia11', 'id_performa_ia11'], 'ia11_performa_unique');
            $table->index('id_ia11');
            $table->index('id_performa_ia11');
        });
    }
};