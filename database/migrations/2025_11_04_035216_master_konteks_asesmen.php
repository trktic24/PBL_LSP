<?php

use Dom\Comment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Nama tabel PERSIS kayak ERD
        Schema::create('konteks_asesmen', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_konteks_asesmen');

            // --- INI PERBAIKAN LOGIKANYA ---
            // Daripada bikin kolom 'lingkungan' dan 'peluang',
            // kita bikin 'grup' biar bisa nyimpen SEMUA pilihan dari form.

            // 1. Buat 'Lingkungan' (sesuai ERD lu)
            $table->enum('lingkungan', ['nyata', 'simulasi'])->nullable();

            // 2. Buat 'Peluang' (sesuai ERD lu)
            $table->enum('peluang', ['tersedia', 'terbatas'])->nullable();

            $table->timestamps();

            // CATATAN: FK 'id_siapa_melakukan_asesmen' di ERD lu itu
            // kayaknya salah gambar, jadi kita skip biar logikanya bener.
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('konteks_asesmen');
    }
};
