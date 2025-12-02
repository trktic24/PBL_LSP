<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

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

        // 1. Cari Data
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
        // [PERBAIKAN LOGIKA 1] CEK APAKAH SUDAH LUNAS ATAU LEBIH TINGGI
        // =================================================================
        
        // Cek Level Status saat ini
        $currentLevel = $sertifikasi->progres_level; // Menggunakan accessor di Model
        $levelLunas   = 30; // Sesuai model kamu (STATUS_PEMBAYARAN_LUNAS)

        // Jika status sudah LUNAS atau LEBIH TINGGI (misal sudah asesmen),
        // Jangan biarkan dia bayar lagi. Lempar ke halaman 'Pembayaran Berhasil'
        if ($currentLevel >= $levelLunas) {
            
            // Cari data pembayaran yang sukses buat ditampilkan di receipt
            $successPayment = Pembayaran::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                ->whereIn('status_transaksi', ['settlement', 'capture'])
                ->latest()
                ->first();

            if ($successPayment) {
                return redirect()->route('pembayaran_diproses', [
                    'order_id' => $successPayment->order_id,
                    'transaction_status' => $successPayment->status_transaksi,
                    'status_code' => '200'
                ]);
            }

            // Fallback kalau data pembayaran di DB gak ketemu tapi status sertifikasi udah lunas
            return redirect("/tracker/{$idJadwal}")->with('success', 'Pembayaran Anda sudah lunas.');
        }
        
        // =================================================================

        $skema = $sertifikasi->jadwal->skema;
        $harga = $skema->harga ?? 0;
        
        if ($harga <= 0) {
            return redirect("/tracker/{$idJadwal}")->with('error', 'Harga skema belum diatur.');
        }

        // ... (Kode Generate Order ID, Param Midtrans, Snap Token TETAP SAMA) ...
        
        // Biar tidak kepanjangan, saya tulis singkat bagian yang tidak berubah:
        // [START KODE LAMA]
        $newOrderId = 'LSP-' . $sertifikasi->id_data_sertifikasi_asesi . '-' . time(); 

        $params = [
            'transaction_details' => ['order_id' => $newOrderId, 'gross_amount' => (int) $harga],
            'customer_details' => [
                'first_name' => $user->asesi->nama_lengkap,
                'email' => $user->email,
                'phone' => $user->asesi->nomor_hp ?? '08123456789',
            ],
            'item_details' => [[
                'id' => 'SKEMA-' . $skema->id_skema,
                'price' => (int) $harga,
                'quantity' => 1,
                'name' => substr($skema->nama_skema, 0, 50) 
            ]],
            'callbacks' => [
                'finish'   => route('pembayaran_diproses'),
                'unfinish' => route('payment.cancel'),
                'error'    => route('payment.cancel'),
            ]
        ];
        // [END KODE LAMA]

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan/Update DB Pembayaran (Tetap sama)
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

            // =================================================================
            // [PERBAIKAN LOGIKA 2] UPDATE STATUS HANYA JIKA BELUM LUNAS
            // =================================================================
            // Pastikan kita tidak menurunkan status yang sudah tinggi
            if($sertifikasi->status_sertifikasi == DataSertifikasiAsesi::STATUS_PENDAFTARAN_SELESAI || 
               $sertifikasi->status_sertifikasi == DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR) {
                
                $sertifikasi->status_sertifikasi = DataSertifikasiAsesi::STATUS_TUNGGU_VERIFIKASI_BAYAR;
                $sertifikasi->save();
            }
            // Jika status sudah lunas atau lebih tinggi, biarkan saja (jangan diubah jadi menunggu bayar)

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
        $sertifikasi = null;
        $idJadwal = null;
        $idSertifikasi = null; 
        $pembayaran = null;

        // Cari Data Berdasarkan Order ID dari Midtrans
        if ($orderId) {
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();
            
            if ($pembayaran) {
                // Ambil ID Sertifikasi langsung dari tabel pembayaran
                $idSertifikasi = $pembayaran->id_data_sertifikasi_asesi;

                $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema'])->find($idSertifikasi);

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
            'sertifikasi'   => $sertifikasi, 
            'asesi'         => $asesi,
            'pembayaran'    => $pembayaran,
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
    public function downloadInvoice($id_sertifikasi) 
    {
        $user = Auth::user();

        // 1. Cari Data Pembayaran berdasarkan ID Sertifikasi
        // ... (kode query tetap sama)
        $payment = Pembayaran::with([
            'sertifikasi.asesi.user', 
            'sertifikasi.jadwal.skema' 
        ])
        ->where('id_data_sertifikasi_asesi', $id_sertifikasi)
        ->whereIn('status_transaksi', ['settlement', 'capture'])
        ->latest()
        ->firstOrFail();

        // 2. Validasi Keamanan (Double Check)
        // ... (kode validasi tetap sama)
        if (!$payment->sertifikasi || $payment->sertifikasi->id_asesi !== $user->asesi->id_asesi) {
             abort(403, 'Unauthorized action.');
        }

        // 3. Siapkan data untuk View PDF
        $data = [
            'payment' => $payment,
            // Shortcut biar di view gak kepanjangan ngetiknya
            'asesi' => $payment->sertifikasi->asesi,
            'skema' => $payment->sertifikasi->jadwal->skema,
            
            // <--- INI YANG DITAMBAHKAN (SOLUSI ERRORNYA)
            'id_jadwal' => $payment->sertifikasi->id_jadwal,
        ];

        // 4. Load View dan render jadi PDF
        // ... (kode render tetap sama)
        $pdf = Pdf::loadView('pdf.invoice_pembayaran', $data);
        $pdf->setPaper('A4', 'portrait');

        // 5. Download file PDF
        // ... (kode download tetap sama)
        $fileName = 'Invoice_' . $payment->order_id . '.pdf';
        return $pdf->download($fileName);
    }
}