<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config; // <-- Import class Config Midtrans
use Midtrans\Snap;   // <-- Import class Snap Midtrans

class PaymentController extends Controller
{
    /**
     * Method untuk membuat transaksi Midtrans.
     */
    public function createTransaction(Request $request)
    {
        // 1. SET KONFIGURASI MIDTRANS
        // Ambil konfigurasi dari file config/midtrans.php
        // (yang datanya mengambil dari .env)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;


        // 2. SIAPKAN DATA TRANSAKSI (CONTOH)
        // Nanti, data ini harusnya Anda ambil dari database atau session
        // berdasarkan sertifikasi yang dipilih user.
        $transaction_details = [
            'order_id' => 'LSP-' . uniqid(), // ID Invoice/Order (WAJIB UNIK)
            'gross_amount' => 150000, // Total harga (contoh: Rp 150.000)
        ];

        // 3. (OPSIONAL) SIAPKAN DATA CUSTOMER
        // Ini contoh, idealnya ambil dari data user yang login
        $customer_details = [
            'first_name'    => "Budi",
            'last_name'     => "Utomo",
            'email'         => "budi.utomo@contoh.com",
            'phone'         => "081234567890",
        ];

        // 4. (OPSIONAL) SIAPKAN DATA ITEM/PRODUK
        $item_details = [
            [
                'id'       => 'SERTIFIKASI-01', // ID produk/sertifikasi
                'price'    => 150000,
                'quantity' => 1,
                'name'     => 'Biaya Sertifikasi Skema A'
            ]
        ];

        // 5. GABUNGKAN SEMUA DATA UNTUK DIKIRIM KE MIDTRANS
        $params = [
            'transaction_details' => $transaction_details,
            'customer_details'    => $customer_details,
            'item_details'        => $item_details,
            // 'enabled_payments' => ['gopay', 'shopeepay', 'bca_va'], // (Opsional) Filter metode bayar
        ];


        try {
            // 6. MINTA PAYMENT URL DARI MIDTRANS
            // Midtrans akan mengembalikan URL halaman pembayaran (Snap URL)
            $paymentUrl = Snap::getSnapUrl($params);

            // 7. ARAHKAN USER KE HALAMAN PEMBAYARAN
            // User akan otomatis diarahkan ke halaman Midtrans
            return redirect($paymentUrl);

        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return "Error: " . $e->getMessage();
        }
    }
}