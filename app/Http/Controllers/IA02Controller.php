<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IA02; // Pastikan nama Modelnya konsisten (IA02)
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class IA02Controller extends Controller
{
    /**
     * Menampilkan halaman FR IA.02
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        // 1. Ambil data sertifikasi beserta relasinya
        // Menggunakan relasi nested yang lengkap agar data tidak null
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.tuk',
            'jadwal.skema.asesor',
            // Kita coba panggil unitKompetensi via skema (sesuai perbaikan model sebelumnya)
            // Jika masih error, codingan di bawah akan menghandle manualnya.
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()
                ->route('asesor.jadwal.index')
                ->with('error', 'Data Sertifikasi tidak ditemukan.');
        }

        // 2. Ambil data IA02 untuk sertifikasi ini (jika sudah ada)
        $ia02 = IA02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->first();

        // 3. Cek Role (Hanya Admin & Superadmin yang bisa edit)
        // Jika user belum login/auth, default false
        $isAdmin = Auth::check() && in_array(Auth::user()->role_id, [1, 4]);

        // 4. Ambil Data Unit Kompetensi
        // Kita gunakan Collection kosong sebagai default
        $daftarUnitKompetensi = collect();

        // Logika Pengambilan Unit Kompetensi (Menggabungkan logika loop manual agar aman)
        if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema) {
            // Jika relasi hasManyThrough di Model Skema sudah benar, kita bisa pakai ini:
            if ($sertifikasi->jadwal->skema->unitKompetensi) {
                $daftarUnitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi;
            }
            // Fallback: Jika relasi langsung gagal, kita loop manual lewat kelompokPekerjaan
            elseif ($sertifikasi->jadwal->skema->kelompokPekerjaan) {
                foreach ($sertifikasi->jadwal->skema->kelompokPekerjaan as $kp) {
                    if ($kp->unitKompetensi) {
                        foreach ($kp->unitKompetensi as $uk) {
                            $daftarUnitKompetensi->push($uk);
                        }
                    }
                }
            }
        }

        // 5. Kirim ke View
        return view('frontend.FR_IA_02', [
            'sertifikasi' => $sertifikasi,
            'ia02' => $ia02,
            'skenario' => $ia02, // Alias biar view yang pake $skenario tetap jalan
            'isAdmin' => $isAdmin,
            'daftarUnitKompetensi' => $daftarUnitKompetensi,
            'unitKompetensis' => $daftarUnitKompetensi, // Alias juga
            'jadwal' => $sertifikasi->jadwal,
            'skema' => $sertifikasi->jadwal->skema,
            'asesi' => $sertifikasi->asesi,
        ]);
    }

    /**
     * Menyimpan atau mengupdate data IA.02
     */
    public function store(Request $request, string $id_sertifikasi)
    {
        // 1. Cek Hak Akses (Security)
        // Hanya admin (1) atau superadmin (4)
        if (!Auth::check() || !in_array(Auth::user()->role_id, [1, 4])) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGUBAH DATA INI.');
        }

        // 2. Validasi
        $validated = $request->validate([
            'skenario' => 'required|string',
            'peralatan' => 'required|string',
            'waktu' => 'required|string|max:100',
        ]);

        // 3. Simpan ke Database
        IA02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi],
            [
                'skenario' => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu' => $validated['waktu'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Data Instruksi Demonstrasi berhasil disimpan.');
    }

    /**
     * Mencetak PDF (Fungsi ini dikembalikan dari HEAD)
     */
    public function cetakPDF($id)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema.asesor',
            // Pastikan relasi ini sesuai dengan perbaikan Model Skema & KelompokPekerjaan
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id);

        // 2. Ambil Data Skenario (IA02)
        $skenario = IA02::where('id_data_sertifikasi_asesi', $id)->first();

        // 3. Ambil Unit Kompetensi (Menggunakan logika Model Skema yang baru)
        // Jika error, pastikan public function unitKompetensi() ada di Model Skema
        $unitKompetensis = collect();

        if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema) {
            // Coba ambil dari relasi hasManyThrough Skema
            $unitKompetensis = $sertifikasi->jadwal->skema->unitKompetensi;

            // Jika kosong, coba ambil manual loop
            if ($unitKompetensis->isEmpty() && $sertifikasi->jadwal->skema->kelompokPekerjaan) {
                foreach ($sertifikasi->jadwal->skema->kelompokPekerjaan as $kp) {
                    foreach ($kp->unitKompetensi as $uk) {
                        $unitKompetensis->push($uk);
                    }
                }
            }
        }

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_02', [
            'sertifikasi' => $sertifikasi,
            'skenario' => $skenario,
            'unitKompetensis' => $unitKompetensis
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_02_' . ($sertifikasi->asesi->nama_lengkap ?? 'Asesi') . '.pdf');
    }
}