<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('fr_ia_04as', function (Blueprint $table) {
        $table->id();
        // Foreign Key ke User (Asesi yang buat)
        $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
        
        // Data Isian Asesi
        $table->text('skenario');
        $table->string('waktu_skenario');
        $table->text('hasil_demonstrasi');
        $table->string('waktu_demonstrasi');
        $table->text('umpan_balik')->nullable();

        // Data Isian Asesor (Tanda Tangan / Validasi)
        $table->foreignId('asesor_id')->nullable()->constrained('users', 'id_user'); // Siapa yang ttd
        $table->string('tanda_tangan_asesor')->nullable(); // Bisa berupa path gambar atau boolean status
        $table->dateTime('tanggal_tanda_tangan')->nullable();
        
        $table->timestamps();
    });
}  /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fr_ia04as');
    }
};
