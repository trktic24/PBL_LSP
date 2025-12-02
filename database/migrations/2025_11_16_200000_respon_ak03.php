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
        Schema::create('respon_ak03', function (Blueprint $table) {
            // PK
            $table->id('id_umpan_balik');

            // FK ke Data Sertifikasi (Siapa yang banding?)
            // Kita pake nama pendek 'ak03_sertifikasi_fk' biar gak error
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'ak03_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- 3 PERTANYAAN UTAMA (YA/TIDAK) ---
            // Kita pake boolean: 1 = Ya, 0 = Tidak
            
            // 1. Penjelasan proses asesmen
            $table->boolean('penjelasan_proses_asesmen')->nullable()
                  ->comment('Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi (ya/tidak)');

            // 2. Memahami standar kompetensi
            $table->boolean('memahami_standar_kompetensi')->nullable()
                  ->comment('Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya (ya/tidak)');

            // 3. Diskusi metode dengan asesor
            $table->boolean('diskusi_metode_dengan_asesor')->nullable()
                  ->comment('Asesor memberi kesempatan mendiskusikan/menegosiasikan metode, instrumen, dan sumber asesmen serta jadwal (ya/tidak)');

            // 4. Menggali bukti pendukung
            $table->boolean('menggali_bukti_pendukung')->nullable()
                ->comment('Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman saya (ya/tidak)');

            // 5. Kesempatan demos kompetensi
            $table->boolean('kesempatan_demos_kompetensi')->nullable()
                ->comment('Saya diberi kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen (ya/tidak)');

            // 6. Penjelasan keputusan asesmen
            $table->boolean('penjelasan_keputusan_asesmen')->nullable()
                ->comment('Saya mendapatkan penjelasan memadai mengenai keputusan asesmen (ya/tidak)');

            // 7 Umpan balik setelah asesmen
            $table->boolean('umpan_balik_setelah_asesmen')->nullable()
                ->comment('Asesor memberikan umpan balik yang mendukung setelah asesmen dan tindak lanjutnya (ya/tidak)');

            // 8 Mempelajari dokumen asesmen
            $table->boolean('mempelajari_dokumen_asesmen')->nullable()
                ->comment('Asesor bersama saya mempelajari semua dokumen asesmen serta menandatangani (ya/tidak)');

            // 9. jaminan kerahasiaan
            $table->boolean('jaminan_kerahasiaan')->nullable()
                ->comment('Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen (ya/tidak)');
            
            // 10. Komunikasi efektif asesor
            $table->boolean('komunikasi_efektif_asesor')->nullable()
                ->comment('Asesor menggunakan keterampilan komunikasi efektif selama asesmen (ya/tidak)');

            // --- ISIAN ALASAN BANDING ---
            // Di form ada kotak gede buat "Alasan", di ERD namanya 'catatan'
            // Kita pake text biar muat banyak.
            $table->text('catatan')->nullable()->comment('Catatan/komentar lainnya (apabila ada)');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_ak03');
    }
};
