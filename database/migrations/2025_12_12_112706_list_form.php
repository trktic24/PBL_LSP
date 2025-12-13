<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_form', function (Blueprint $table) {
            $table->id('id_form');
            // Hubungkan ke tabel skema
            $table->foreignId('id_skema')->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('cascade');

            // --- FASE 1: PERMOHONAN ---
            $table->boolean('apl_01')->default(true); // Permohonan (Wajib biasanya)
            $table->boolean('apl_02')->default(true); // Asesmen Mandiri

            // --- FASE 2: ASESMEN / UJI KOMPETENSI ---
            // Metode Observasi Langsung
            $table->boolean('fr_ia_01')->default(true); // Ceklis Observasi Aktivitas
            $table->boolean('fr_ia_02')->default(true); // Tugas Praktek Demonstrasi
            $table->boolean('fr_ia_03')->default(true); // Pertanyaan Untuk Mendukung Observasi

            // Metode Tes Tulis/Lisan
            $table->boolean('fr_ia_05')->default(true); // Pertanyaan Tertulis Pilihan Ganda
            $table->boolean('fr_ia_06')->default(true); // Pertanyaan Tertulis Esai
            $table->boolean('fr_ia_07')->default(true); // Pertanyaan Lisan

            // Metode Lain
            $table->boolean('fr_ia_04')->default(true); // Ceklis Verifikasi Portofolio
            $table->boolean('fr_ia_08')->default(true); // Ceklis Verifikasi Pihak Ketiga
            $table->boolean('fr_ia_09')->default(true); // Pertanyaan Wawancara
            $table->boolean('fr_ia_10')->default(true); // Klarifikasi Bukti Pihak Ketiga
            $table->boolean('fr_ia_11')->default(true); // Ceklis Meninjau Instrumen Asesmen

            // --- FASE 3: KEPUTUSAN & UMPAN BALIK ---
            $table->boolean('fr_ak_01')->default(true); // Persetujuan Asesmen
            $table->boolean('fr_ak_02')->default(true); // Rekaman Asesmen
            $table->boolean('fr_ak_03')->default(true); // Umpan Balik
            $table->boolean('fr_ak_04')->default(true); // Banding Asesmen
            $table->boolean('fr_ak_05')->default(true); // Laporan Asesmen
            $table->boolean('fr_ak_06')->default(true); // Meninjau Proses Asesmen

            // --- FASE 4: ADMINISTRASI (MAPA) ---
            $table->boolean('fr_mapa_01')->default(true); // Merencanakan Aktivitas
            $table->boolean('fr_mapa_02')->default(true); // Peta Instrumen

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_form');
    }
};