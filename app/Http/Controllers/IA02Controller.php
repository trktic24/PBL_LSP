<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
use App\Models\ia02;
// use App\Models\SkenarioIa02; // Removed as it doesn't exist
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
=======
use App\Models\IA02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
>>>>>>> Database

class IA02Controller extends Controller
{
    /**
<<<<<<< HEAD
     * Menampilkan form FR.IA.02 yang sudah terisi data
     * (Tahap 4 - Menghubungkan ke View)
     *
     * @param string $id (id_data_sertifikasi_asesi)
     * @return View
     */
    public function show(string $id): View
    {
        // 1. Cari data sertifikasi utama berdasarkan ID
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.asesor',
            'jadwal.skema.unitKompetensi', // Relasi ke Unit Kompetensi via Skema
            'jadwal.tuk'
        ])
            ->findOrFail($id);

        // 2. Cari data skenario IA.02 yang sudah ada, atau buat objek baru jika belum
        $skenario = ia02::firstOrNew([
            'id_data_sertifikasi_asesi' => $id
        ]);

        // 3. Ambil data unit kompetensi untuk ditampilkan di tabel
        $unitKompetensis = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        // 4. Kirim semua data ke View
        return view('frontend.FR_IA_02', [
            'sertifikasi' => $sertifikasi,
            'skenario' => $skenario,
            'jadwal' => $sertifikasi->jadwal,
            'asesi' => $sertifikasi->asesi,
            'skema' => $sertifikasi->jadwal->skema,
            'unitKompetensis' => $unitKompetensis,
=======
     * Menampilkan halaman FR IA.02
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        // Ambil data sertifikasi beserta relasinya
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema',
            'jadwal.skema.asesor',
            'jadwal.skema.kelompokPekerjaans.UnitKompetensis'
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()
                ->route('daftar_asesi')
                ->with('error', 'Data Sertifikasi tidak ditemukan.');
        }

        // Ambil data IA02 untuk sertifikasi ini (jika sudah ada)
        $ia02 = IA02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->first();

        // Role: yang boleh input adalah admin (1) & superadmin (4)
        $isAdmin = in_array(Auth::user()->role_id, [1, 4]);

        // Fix: Ambil Unit Kompetensi via KelompokPekerjaan (karena relasi Skema->UnitKompetensi broken)
        $daftarUnitKompetensi = collect();
        if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema) {
            foreach ($sertifikasi->jadwal->skema->kelompokPekerjaans as $kp) {
                foreach ($kp->unitKompetensis as $uk) {
                    $daftarUnitKompetensi->push($uk);
                }
            }
        }

        return view('frontend.FR_IA_02', [
            'sertifikasi'          => $sertifikasi,
            'ia02'                 => $ia02,
            'isAdmin'              => $isAdmin,
            'daftarUnitKompetensi' => $daftarUnitKompetensi,
>>>>>>> Database
        ]);
    }

    /**
<<<<<<< HEAD
     * Menyimpan data dari form FR.IA.02
     *
     * @param Request $request
     * @param string $id (id_data_sertifikasi_asesi)
     * @return RedirectResponse
     */
    public function store(Request $request, string $id): RedirectResponse
    {
        // 1. Validasi data yang masuk dari form
        $validated = $request->validate([
            'skenario' => 'required|string',
            'peralatan' => 'required|string',
            'waktu' => 'required|string|max:100',
        ]);

        // 2. Gunakan updateOrCreate()
        ia02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id],
            [
                'skenario' => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu' => $validated['waktu'],
            ]
        );

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()
            ->with('success', 'Data Skenario FR.IA.02 berhasil disimpan!');
    }

    public function cetakPDF($id)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema.asesor',
            'jadwal.skema.unitKompetensi.kelompokPekerjaan' // Pastikan relasi ini ada di model Skema/Unit
        ])->findOrFail($id);

        // 2. Ambil Data Skenario (IA02)
        $skenario = ia02::where('id_data_sertifikasi_asesi', $id)->first();

        // 3. Ambil Unit Kompetensi
        $unitKompetensis = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_02', [
            'sertifikasi'     => $sertifikasi,
            'skenario'        => $skenario,
            'unitKompetensis' => $unitKompetensis
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_02_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }

=======
     * Menyimpan atau mengupdate data IA.02
     */
    public function store(Request $request, string $id_sertifikasi)
    {
        // Hanya admin (1) atau superadmin (4)
        if (! in_array(Auth::user()->role_id, [1, 4])) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGUBAH DATA INI.');
        }

        $validated = $request->validate([
            'skenario'  => 'required|string',
            'peralatan' => 'required|string',
            'waktu'     => 'required|string|max:100',
        ]);

        IA02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi],
            [
                'skenario'  => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu'     => $validated['waktu'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Data Instruksi Demonstrasi berhasil disimpan.');
    }
>>>>>>> Database
}
