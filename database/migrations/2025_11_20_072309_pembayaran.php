<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            
            // Relasi ke pendaftaran asesi
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // --- KOLOM WAJIB MIDTRANS ---
            // 1. Order ID: Kunci utama buat Midtrans (Harus Unik per transaksi)
            // Format biasanya: INV-ID_SERTIFIKASI-TIMESTAMP (misal: INV-50-173200123)
            $table->string('order_id')->unique(); 
            
            // 2. Jumlah Bayar
            $table->decimal('amount', 10, 2); 

            // 3. Status Transaksi
            // (pending, settlement, expire, cancel, deny)
            $table->string('status_transaksi')->default('pending');

            // 4. Snap Token: Kunci buat nampilin Popup Pembayaran
            $table->string('snap_token')->nullable();

            // --- KOLOM PELENGKAP (Opsional tapi Berguna) ---
            $table->string('jenis_pembayaran')->nullable(); // gopay, bank_transfer, dll
            $table->string('bank')->nullable(); // bca, bni, dll
            $table->string('va_number')->nullable(); // nomor virtual account (kalo ada)
            $table->string('pdf_url')->nullable(); // Link instruksi bayar dari Midtrans
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};