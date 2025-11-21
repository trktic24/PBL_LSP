<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\DataSertifikasiAsesi;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            // 2. Tangkap Notifikasi dari Midtrans
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // 3. Cari Data Pembayaran di Database Kamu
            $payment = Pembayaran::where('order_id', $order_id)->first();

            if (!$payment) {
                return response()->json(['message' => 'Order ID not found'], 404);
            }

            // 4. Tentukan Status Baru
            $newStatus = null;

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $newStatus = 'challenge';
                    } else {
                        $newStatus = 'settlement'; // Sukses
                    }
                }
            } else if ($transaction == 'settlement') {
                $newStatus = 'settlement'; // Sukses (Transfer Bank, GoPay, dll)
            } else if ($transaction == 'pending') {
                $newStatus = 'pending';
            } else if ($transaction == 'deny') {
                $newStatus = 'deny';
            } else if ($transaction == 'expire') {
                $newStatus = 'expire';
            } else if ($transaction == 'cancel') {
                $newStatus = 'cancel';
            }

            // 5. UPDATE DATABASE
            if ($newStatus) {
                // Update Tabel Pembayaran
                $payment->update(['status_transaksi' => $newStatus]);

                // Update Tabel Data Sertifikasi Asesi (HANYA JIKA SUKSES)
                if ($newStatus == 'settlement') {
                    $sertifikasi = DataSertifikasiAsesi::find($payment->id_data_sertifikasi_asesi);
                    
                    if ($sertifikasi) {
                        // Ubah status sertifikasi agar Tracker terbuka
                        // Gunakan string 'pembayaran_lunas' sesuai enum/const kamu
                        $sertifikasi->status_sertifikasi = 'pembayaran_lunas'; 
                        $sertifikasi->save();
                        
                        Log::info("Pembayaran Lunas: Status sertifikasi ID {$sertifikasi->id_data_sertifikasi_asesi} diperbarui.");
                    }
                }
            }

            return response()->json(['message' => 'Callback received successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}