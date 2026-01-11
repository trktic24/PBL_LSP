<?php

namespace App\Http\Controllers\Asesi\IA02;

use Carbon\Carbon;
use App\Models\Ia02;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Validator;

class Ia02AsesiController extends Controller
{
    /**
     * ============================================================
     * [WEB] Menampilkan halaman IA.02 (ASESI - READ ONLY)
     * ============================================================
     */
    public function index($id_data_sertifikasi_asesi)
    {
        // 1. Ambil data sertifikasi
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi:id_asesi,nama_lengkap,tanda_tangan',
            'jadwal.asesor:id_asesor,nama_lengkap,nomor_regis,tanda_tangan',
            'jadwal.skema:id_skema,nama_skema,nomor_skema,gambar',
            'jadwal.skema.unitKompetensi',
            'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
            'jadwal:id_jadwal,id_asesor,id_skema,id_jenis_tuk,tanggal_pelaksanaan',
        ])->findOrFail($id_data_sertifikasi_asesi);

        // 2. AMBIL DATA IA.02 (LOGIC ONE-TO-MANY)
        // Pakai get() supaya semua list skenario terambil.
        // Hasilnya adalah COLLECTION (Array of Objects), bukan single object.
        $daftarIa02 = Ia02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
                          ->orderBy('created_at', 'asc') // Urutkan dari tugas pertama
                          ->get();

        // [LOGIC SINKRONISASI] 
        // Jika data Asesi MASIH KOSONG, ambil dari Master Template (Preview Mode)
        if ($daftarIa02->isEmpty()) {
            $template = \App\Models\MasterFormTemplate::where('id_skema', $dataSertifikasi->jadwal->id_skema)
                ->where('id_jadwal', $dataSertifikasi->jadwal->id_jadwal)
                ->where('form_code', 'FR.IA.02')
                ->first();

            // Fallback ke Default Skema jika Template Jadwal kosong
            if (!$template) {
                $template = \App\Models\MasterFormTemplate::where('id_skema', $dataSertifikasi->jadwal->id_skema)
                    ->whereNull('id_jadwal')
                    ->where('form_code', 'FR.IA.02')
                    ->first();
            }

            // Jika template ditemukan, buat Mock/Temporary Object agar Asesi melihat soalnya
            if ($template && isset($template->content)) {
                $content = $template->content;
                
                // Buat Object IA02 Temporary (Tanpa Save ke Database)
                $tempIa02 = new Ia02([
                    'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                    'skenario'  => $content['skenario'] ?? '',
                    'peralatan' => $content['peralatan'] ?? '',
                    'waktu'     => $content['waktu'] ?? '02:00:00',
                ]);

                // Masukkan ke Collection
                $daftarIa02->push($tempIa02);
            }
        }

        // 3. Persiapan data pendukung view
        $daftarUnitKompetensi = $dataSertifikasi->jadwal?->skema?->unitKompetensi ?? collect();
        $asesi = $dataSertifikasi->asesi;

        return view('asesi.IA_02.IA_02', [
            'sertifikasi' => $dataSertifikasi,
            'asesi' => $asesi,
            'daftarUnitKompetensi' => $daftarUnitKompetensi,
            'daftarIa02' => $daftarIa02, // Kirim sebagai LIST (Collection)
        ]);
    }

    /**
     * ============================================================
     * [WEB] Menyimpan data IA.02 (ASESOR - FORM SUBMISSION)
     * ============================================================
     */
    public function store(Request $request, $id_data_sertifikasi_asesi)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'skenario'  => 'required|string',
            'peralatan' => 'required|string',
            'waktu'     => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()
                ->with('error', 'Gagal menyimpan: Harap isi semua kolom wajib.');
        }

        try {
            // 2. SIMPAN DENGAN LOGIC 'CREATE' (ADD NEW)
            // Karena One-to-Many, kita asumsikan Asesor mau Nambah Skenario baru.
            // (Kecuali kalau ada fitur Edit, harus kirim id_ia02 hidden input)
            
            Ia02::create([
                'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                'skenario'  => $request->skenario,
                'peralatan' => $request->peralatan,
                'waktu'     => $request->waktu . ':00', 
            ]);

            return back()->with('success', 'Skenario Tugas Praktik (IA.02) berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan IA02: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * ============================================================
     * [API] Mengambil data IA.02 (JSON)
     * ============================================================
     */
    public function apiDetail($id_data_sertifikasi_asesi)
    {
        try {
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'asesi:id_asesi,nama_lengkap,tanda_tangan',
                'jadwal.asesor:id_asesor,nama_lengkap,nomor_regis,tanda_tangan',
                'jadwal.skema:id_skema,nama_skema,nomor_skema',
                'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
            ])->findOrFail($id_data_sertifikasi_asesi);

            // Load All Records
            $ia02List = Ia02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

            $tanggalAssesmen = Carbon::parse($dataSertifikasi->jadwal->tanggal_assesmen)->isoFormat('D MMMM YYYY');
            $jenisTukName = $dataSertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-';

            return response()->json([
                'success' => true,
                'data' => [
                    'asesi' => [
                        'nama_lengkap' => $dataSertifikasi->asesi->nama_lengkap,
                        'tanda_tangan' => $dataSertifikasi->asesi->tanda_tangan,
                    ],
                    'asesor' => [
                        'nama_lengkap' => $dataSertifikasi->jadwal->asesor->nama_lengkap ?? '-',
                        'nomor_regis'  => $dataSertifikasi->jadwal->asesor->nomor_regis ?? '-',
                        'tanda_tangan' => $dataSertifikasi->jadwal->asesor->tanda_tangan ?? '-',
                    ],
                    'skema' => $dataSertifikasi->jadwal->skema,
                    'tanggal_assesmen' => $tanggalAssesmen,
                    'tuk'   => $jenisTukName,
                    'ia02'  => $ia02List, // Mengirim ARRAY of Objects
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('API IA02 GAGAL: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function next($id_data_sertifikasi_asesi)
    {
        return redirect()->route('asesi.ia03.index', $id_data_sertifikasi_asesi);
    }
}