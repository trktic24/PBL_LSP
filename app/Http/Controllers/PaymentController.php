<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Pembayaran; // <-- Model Pembayaran yang baru kita buat
use App\Models\DataSertifikasiAsesi;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans di construct biar rapi
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Method untuk membuat transaksi Midtrans.
     */
    public function createTransaction(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->asesi) {
            return redirect()->back()->with('error', 'Data Asesi tidak ditemukan.');
        }

        // 1. Cari Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::where('id_asesi', $user->asesi->id_asesi)
            ->whereIn('status_sertifikasi', [
                DataSertifikasiAsesi::STATUS_PENDAFTARAN_SELESAI,
                DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR
            ])
            ->with(['jadwal.skema']) 
            ->latest()
            ->first();

        if (!$sertifikasi) {
            return redirect('/tracker')->with('error', 'Tidak ada tagihan pembayaran aktif.');
        }

        // 2. Siapkan Data Harga & Item
        $skema = $sertifikasi->jadwal->skema;
        $harga = $skema->harga ?? 0;
        
        if ($harga <= 0) {
            return redirect('/tracker')->with('error', 'Harga skema belum diatur.');
        }

        // 3. GENRATE ORDER ID BARU (Wajib Unik setiap kali klik bayar)
        // Kita pakai timestamp biar kalau user klik ulang, ID-nya beda
        $newOrderId = 'LSP-' . $sertifikasi->id_data_sertifikasi_asesi . '-' . time(); 

        $transaction_details = [
            'order_id' => $newOrderId,
            'gross_amount' => (int) $harga,
        ];

        $customer_details = [
            'first_name'    => $user->asesi->nama_lengkap,
            'email'         => $user->email,
            'phone'         => $user->asesi->nomor_hp ?? '08123456789',
        ];

        $item_details = [
            [
                'id'       => 'SKEMA-' . $skema->id_skema,
                'price'    => (int) $harga,
                'quantity' => 1,
                'name'     => substr($skema->nama_skema, 0, 50) 
            ]
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details'    => $customer_details,
            'item_details'        => $item_details,
            'callbacks' => [
                'finish' => route('pembayaran_diproses'),
            ]
        ];

        try {
            // 4. Minta Token BARU ke Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // 5. CEK APAKAH SUDAH ADA DATA DI TABEL PEMBAYARAN?
            $existingPayment = Pembayaran::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                ->where('status_transaksi', 'pending') // Cari yang masih pending
                ->first();

            if ($existingPayment) {
                // [LOGIKA BARU]: UPDATE data lama dengan Order ID & Token BARU
                // Jadi kalau yang lama expired, kita timpa aja biar user bisa bayar ulang.
                $existingPayment->update([
                    'order_id'   => $newOrderId,
                    'snap_token' => $snapToken,
                    'amount'     => $harga
                ]);
            } else {
                // Kalau belum ada, bikin baru
                Pembayaran::create([
                    'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                    'order_id'         => $newOrderId,
                    'amount'           => $harga,
                    'status_transaksi' => 'pending',
                    'snap_token'       => $snapToken,
                    'jenis_pembayaran' => 'midtrans_snap'
                ]);
            }

            // 6. Pastikan Status Asesi = Menunggu Bayar
            if($sertifikasi->status_sertifikasi != DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR){
                $sertifikasi->status_sertifikasi = DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR;
                $sertifikasi->save();
            }

            // 7. Redirect ke Halaman Bayar (Token Baru)
            return redirect("https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/tracker')->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    // Halaman setelah user bayar / close popup
    public function processed(Request $request)
    {
        return view('pembayaran/pembayaran_diproses', [
            'order_id' => $request->query('order_id'),
            'status' => $request->query('transaction_status'),
            'status_code' => $request->query('status_code')
        ]);
    }
}