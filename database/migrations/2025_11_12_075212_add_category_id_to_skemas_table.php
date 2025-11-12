<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::table('skema', function (Blueprint $table) {
//             // Tambahkan kolom category_id sebagai kunci asing ke tabel categories
//             $table->foreignId('category_id')->nullable()->constrained('categories')->after('id_skema');
//         });
//     }

//     public function down(): void
//     {
//         Schema::table('skema', function (Blueprint $table) {
//             // Hapus kunci asing dan kolomnya saat rollback
//             $table->dropForeign(['category_id']);
//             $table->dropColumn('category_id');
//         });
//     }
// };