<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Jalankan migrasi.
//      */
//     public function up(): void
//     {
//         Schema::table('skema', function (Blueprint $table) {
//             // Tindakan utama: Hapus kolom 'category' yang berisi string
//             $table->dropColumn('category'); 
//         });
//     }

//     /**
//      * Batalkan migrasi.
//      */
//     public function down(): void
//     {
//         Schema::table('skemas', function (Blueprint $table) {
//             // Tambahkan kembali kolom 'category' jika rollback diperlukan
//             $table->string('category')->nullable();
//         });
//     }
// };