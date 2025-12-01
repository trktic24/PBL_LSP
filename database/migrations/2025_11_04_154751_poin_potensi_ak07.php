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
    // 1. Tabel Master: Poin Potensi (Untuk Bagian A)
    Schema::create('poin_potensi_AK07', function (Blueprint $table) {
        $table->id('id_poin_potensi_AK07');
        $table->text('deskripsi_potensi'); // Isi teks: "Hasil pelatihan...", "Pekerja...", dll
        $table->timestamps();
    });

    // 2. Tabel Master: Persyaratan Modifikasi (Untuk Soal Q1-Q7)
    Schema::create('persyaratan_modifikasi_AK07', function (Blueprint $table) {
        $table->id('id_persyaratan_modifikasi_AK07');
        $table->text('pertanyaan_karakteristik'); // Isi teks: "Keterbatasan bahasa...", "Asesmen verbal..."
        $table->timestamps();
    });

    // 3. Tabel Master: Opsi Keterangan (Untuk Checkbox di sebelah kanan Q1-Q7)
    Schema::create('catatan_keterangan_AK07', function (Blueprint $table) {
        $table->id('id_catatan_keterangan_AK07');
        // Kita butuh kolom ini agar tahu opsi ini milik soal nomor berapa (Q1, Q2, atau Q3...)
        $table->foreignId('id_persyaratan_modifikasi_AK07')->constrained('persyaratan_modifikasi_AK07', 'id_persyaratan_modifikasi_AK07');
        $table->text('isi_opsi'); // Isi teks: "Pembaca", "Penerjemah", "Braille"
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('catatan_keterangan_AK07');
    Schema::dropIfExists('persyaratan_modifikasi_AK07');
    Schema::dropIfExists('poin_potensi_AK07');
}
};
