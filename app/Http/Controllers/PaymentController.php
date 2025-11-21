<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Pembayaran;
use App\Models\DataSertifikasiAsesi;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Request $request, $id_sertifikasi)
    {
        $user = Auth::user();
        
        if (!$user->asesi) {
            return redirect()->back()->with('error', 'Data Asesi tidak ditemukan.');
        }

        // 1. Cari Data Sertifikasi
        try {
            $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema'])->findOrFail($id_sertifikasi);
        } catch (\Exception $e) {
            return redirect('/tracker')->with('error', 'Data sertifikasi tidak ditemukan.');
        }

        $idJadwal = $sertifikasi->id_jadwal;

        if ($sertifikasi->id_asesi != $user->asesi->id_asesi) {
            return redirect("/tracker/{$idJadwal}")->with('error', 'Akses ditolak.');
        }

        // =================================================================
        // [PERBAIKAN LOGIKA] JIKA SUDAH LUNAS -> KE HALAMAN SUKSES
        // =================================================================
        if ($sertifikasi->status_sertifikasi == DataSertifikasiAsesi::STATUS_PEMBAYARAN_LUNAS) {
            
            // Cari data pembayaran yang statusnya SUKSES (settlement/capture)
            $successPayment = Pembayaran::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                ->whereIn('status_transaksi', ['settlement', 'capture'])
                ->latest()
                ->first();

            if ($successPayment) {
                // Redirect ke halaman 'Pembayaran Berhasil' membawa Order ID
                return redirect()->route('pembayaran_diproses', [
                    'order_id' => $successPayment->order_id,
                    'transaction_status' => $successPayment->status_transaksi,
                    'status_code' => '200'
                ]);
            }

            // Fallback kalau datanya gak ketemu (aneh sih), balikin ke tracker
            return redirect("/tracker/{$idJadwal}")->with('success', 'Pembayaran sudah lunas.');
        }
        // =================================================================


        $skema = $sertifikasi->jadwal->skema;
        $harga = $skema->harga ?? 0;
        
        if ($harga <= 0) {
            return redirect("/tracker/{$idJadwal}")->with('error', 'Harga skema belum diatur.');
        }

        // ... (SISA KODE KE BAWAH TETAP SAMA, JANGAN DIUBAH) ...
        // Dari baris '$newOrderId = ...' sampai catch Exception biarkan saja.
        
        // Agar tidak copy-paste terlalu panjang dan error, 
        // pastikan sisa kode di bawah ini tetap ada di file kamu:
        
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
                'finish'   => route('pembayaran_diproses'),
                'unfinish' => route('payment.cancel'),
                'error'    => route('payment.cancel'),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $existingPayment = Pembayaran::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                ->where('status_transaksi', 'pending')
                ->first();

            if ($existingPayment) {
                $existingPayment->update([
                    'order_id'   => $newOrderId,
                    'snap_token' => $snapToken,
                    'amount'     => $harga
                ]);
            } else {
                Pembayaran::create([
                    'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                    'order_id'         => $newOrderId,
                    'amount'           => $harga,
                    'status_transaksi' => 'pending',
                    'snap_token'       => $snapToken,
                    'jenis_pembayaran' => 'midtrans_snap'
                ]);
            }

            if($sertifikasi->status_sertifikasi != DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR){
                $sertifikasi->status_sertifikasi = DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR;
                $sertifikasi->save();
            }

            return redirect("https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect("/tracker/{$idJadwal}")->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function processed(Request $request)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('transaction_status');
        $statusCode = $request->query('status_code');
        
        $user = Auth::user();
        $asesi = $user->asesi; // Data Asesi untuk Sidebar
        
        // Variabel Default
        $idJadwal = null;
        $idSertifikasi = null; // <--- INI KITA BUTUHKAN

        // Cari Data Berdasarkan Order ID dari Midtrans
        if ($orderId) {
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();
            
            if ($pembayaran) {
                // Ambil ID Sertifikasi langsung dari tabel pembayaran
                $idSertifikasi = $pembayaran->id_data_sertifikasi_asesi;

                // Ambil ID Jadwal (lewat relasi sertifikasi)
                if ($pembayaran->sertifikasi) {
                    $idJadwal = $pembayaran->sertifikasi->id_jadwal;
                }
            }
        }

        // Kirim semua data ke View
        return view('pembayaran.pembayaran_berhasil', [ 
            'order_id'      => $orderId,
            'status'        => $status,
            'status_code'   => $statusCode,
            'id_jadwal'     => $idJadwal,
            'id_sertifikasi'=> $idSertifikasi, 
            'asesi'         => $asesi
        ]);
    }

    public function paymentCancel(Request $request)
    {
        $orderId = $request->query('order_id');
        if ($orderId) {
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();
            if ($pembayaran && $pembayaran->sertifikasi) {
                return redirect("/tracker/" . $pembayaran->sertifikasi->id_jadwal);
            }
        }
        return redirect('/tracker');
    }
}