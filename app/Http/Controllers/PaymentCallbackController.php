<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

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
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 4. Logika Status Midtrans
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->update(['status_transaksi' => 'challenge']);
                    } else {
                        $payment->update(['status_transaksi' => 'settlement']);
                    }
                }
            } else if ($transaction == 'settlement') {
                // --- PEMBAYARAN SUKSES ---
                $payment->update(['status_transaksi' => 'settlement']);
                
                // UPDATE STATUS SERTIFIKASI DI SINI
                $sertifikasi = DataSertifikasiAsesi::find($payment->id_data_sertifikasi_asesi);
                if ($sertifikasi) {
                    // Pastikan pakai Konstanta dari Model biar aman
                    $sertifikasi->status_sertifikasi = DataSertifikasiAsesi::STATUS_PEMBAYARAN_LUNAS;
                    $sertifikasi->save();
                }

            } else if ($transaction == 'pending') {
                $payment->update(['status_transaksi' => 'pending']);
            } else if ($transaction == 'deny') {
                $payment->update(['status_transaksi' => 'deny']);
            } else if ($transaction == 'expire') {
                $payment->update(['status_transaksi' => 'expire']);
            } else if ($transaction == 'cancel') {
                $payment->update(['status_transaksi' => 'cancel']);
            }

            return response()->json(['message' => 'Callback received successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}