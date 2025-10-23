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
        Schema::create('asesor', function (Blueprint $table) {
            // Sesuai permintaan: id_asesor (primary) (bigint)
            $table->id('id_asesor'); 
            
            // Sesuai permintaan: id_skema (Foreign) (bigint) & id_user (Foreign) (bigint)
            // Ini adalah cara modern Laravel untuk membuat foreign key (unsignedBigInteger + constraint)
            // Pastikan Anda sudah memiliki tabel 'skema' dan 'users'
            $table->foreignId('id_skema')->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_user')->constrained('users')->onUpdate('cascade')->onDelete('restrict');

            // Atribut lainnya
            $table->string('nomor_regis', 50)->nullable()->unique();
            $table->string('nama_lengkap'); // Asumsi nama lengkap wajib diisi
            $table->string('nik', 16)->nullable()->unique();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->boolean('jenis_kelamin')->nullable()->comment('1 untuk Laki-laki, 0 untuk Perempuan');
            $table->text('alamat_rumah')->nullable();
            $table->text('tempat_tinggal')->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('NPWP', 25)->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('norek', 30)->nullable();
            $table->string('pekerjaan')->nullable();

            // Atribut untuk path file
            $table->string('ktp')->nullable()->comment('Path ke file KTP');
            $table->string('pas_foto')->nullable()->comment('Path ke file pas foto');
            $table->string('NPWP_foto')->nullable()->comment('Path ke file foto NPWP');
            $table->string('rekening_foto')->nullable()->comment('Path ke file foto buku rekening');
            $table->string('CV')->nullable()->comment('Path ke file CV');
            $table->string('ijazah')->nullable()->comment('Path ke file ijazah');
            $table->string('sertifikat_asesor')->nullable()->comment('Path ke file sertifikat');
            $table->string('sertifikasi_kompetensi')->nullable()->comment('Path ke file sertifikasi');

            // Standar timestamp seperti di tabel users
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor');
    }
};