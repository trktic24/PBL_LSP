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
        // (Kode Anda yang lain di sini)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;


        // 2. SIAPKAN DATA TRANSAKSI (CONTOH)
        // (Kode Anda yang lain di sini)
        $transaction_details = [
            'order_id' => 'LSP-' . uniqid(), // ID Invoice/Order (WAJIB UNIK)
            'gross_amount' => 150000, // Total harga (contoh: Rp 150.000)
        ];

        // 3. (OPSIONAL) SIAPKAN DATA CUSTOMER
        // (Kode Anda yang lain di sini)
        $customer_details = [
            'first_name'    => "Budi",
            'last_name'     => "Utomo",
            'email'         => "budi.utomo@contoh.com",
            'phone'         => "081234567890",
        ];

        // 4. (OPSIONAL) SIAPKAN DATA ITEM/PRODUK
        // (Kode Anda yang lain di sini)
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
            // 'enabled_payments' => ['gopay', 'shopeepay', 'bca_va'],

            // ▼▼▼ BLOK TAMBAHAN UNTUK FIX REDIRECT ▼▼▼
            'callbacks' => [
                // URL untuk tombol "Back to Merchant" (Selesai Bayar)
                'finish' => route('pembayaran_diproses'),
                
                // URL untuk tombol "Keluar halaman ini" (Batal/Unfinish)
                'unfinish' => route('pembayaran_diproses'),
            ]
            // ▲▲▲ SELESAI ▲▲▲
        ];


        try {
            // 6. MINTA PAYMENT URL DARI MIDTRANS
            // (Kode Anda yang lain di sini)
            $paymentUrl = Snap::getSnapUrl($params);

            // 7. ARAHKAN USER KE HALAMAN PEMBAYARAN
            // (Kode Anda yang lain di sini)
            return redirect($paymentUrl);

        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return "Error: " . $e->getMessage();
        }
    }

    // Pastikan Anda juga memiliki method processed() dan success()
    // yang sudah kita bahas sebelumnya
    public function processed(Request $request)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('transaction_status');
        $statusCode = $request->query('status_code');

        return view('pembayaran/pembayaran_diproses', [
            'order_id' => $orderId,
            'status' => $status,
            'status_code' => $statusCode
        ]);
    }
}