<?php

namespace App\Http\Controllers;

use App\Models\Ia07;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\JenisTUK;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MasterFormTemplate;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Route;

class IA07Controller extends Controller
{
    public function index($idSertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi', 
            'jadwal.asesor', 
            'jadwal.skema.unitKompetensi', 
            'jadwal.jenisTuk'
        ])->findOrFail($idSertifikasi);

        $daftarSoal = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)
                          ->orderBy('id_ia07', 'asc')->get();

        return view('frontend.FR_IA_07', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $sertifikasi->asesi,
            'asesor' => $sertifikasi->jadwal->asesor,
            'skema' => $sertifikasi->jadwal->skema,
            'units' => $sertifikasi->jadwal->skema->unitKompetensi,
            'daftarSoal' => $daftarSoal
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Umpan balik udah dibuang)
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required',
            'penilaian' => 'required|array',
            'jawaban_asesi' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 2. Loop Simpan Jawaban & Penilaian
            foreach ($request->penilaian as $idSoal => $statusKompeten) {
                $textJawaban = $request->jawaban_asesi[$idSoal] ?? null;

                Ia07::where('id_ia07', $idSoal)->update([
                    'pencapaian' => $statusKompeten,
                    'jawaban_asesi' => $textJawaban,
                    'updated_at' => now(), // Trigger timestamp biar kedetect DONE
                ]);
            }

            DB::commit();

            // 3. Redirect ke Tracker
            return redirect('/asesor/tracker/' . $request->id_data_sertifikasi_asesi)
                ->with('success', 'Penilaian FR.IA.07 Berhasil Disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            'jadwal.skema.unitKompetensi' // Ambil unit untuk ditampilkan di tabel awal
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Daftar Pertanyaan & Jawaban (IA07)
        // Ambil Data IA07 milik asesi ini
        $ia07 = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();

        // [AUTO-LOAD TEMPLATE & STATIC FALLBACK]
        if ($ia07->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->where('id_jadwal', $sertifikasi->id_jadwal)
                                        ->where('form_code', 'FR.IA.07')
                                        ->first();
            
            if (!$template) {
                $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                            ->whereNull('id_jadwal')
                                            ->where('form_code', 'FR.IA.07')
                                            ->first();
            }
            
            $defaultQuestions = [
                "Sebutkan komponen utama dari unit kompetensi yang sedang diuji.",
                "Apa yang Anda lakukan jika terjadi kendala teknis saat pelaksanaan tugas?",
                "Bagaimana cara memastikan keselamatan kerja selama proses berlangsung?"
            ];

            $questions = ($template && !empty($template->content)) ? $template->content : $defaultQuestions;

            foreach ($questions as $qText) {
                Ia07::create([
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'pertanyaan' => $qText,
                    'jawaban_asesi' => '',
                    'pencapaian' => null
                ]);
            }
            // Refresh collection
            $ia07 = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();
        }

        // 3. Ambil Unit Kompetensi (Fallback ke collection kosong jika null)
        $unitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_07', [
            'sertifikasi' => $sertifikasi,
            'daftar_pertanyaan' => $ia07,
            'unitKompetensi' => $unitKompetensi
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_07_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Pertanyaan Lisan) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::with('unitKompetensi')->findOrFail($id_skema);
        
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.IA.07')
                                    ->first();
        
        // Ambil konten JSON. 
        // Kalau kosong, kasih array kosong []
        // Kalau format lama (grouping), kita reset jadi [] biar gak error.
        $rawContent = $template ? $template->content : [];
        
        // Pastikan formatnya array list biasa (bukan key-value unit)
        // Cek sederhana: kalau arraynya punya key string (grouping lama), kita reset aja.
        $questions = array_values($rawContent); // Paksa jadi indexed array

        return view('Admin.master.skema.template.ia07', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'questions' => $questions
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'questions' => 'nullable|array',
            'questions.*.pertanyaan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // A. RAPIKAN DATA INPUT
            $rawQuestions = $request->questions ?? [];
            $cleanQuestions = array_values($rawQuestions); 

            // B. SIMPAN KE MASTER TEMPLATE (Backup)
            MasterFormTemplate::updateOrCreate(
                [
                    'id_skema' => $id_skema, 
                    'id_jadwal' => $id_jadwal,
                    'form_code' => 'FR.IA.07'
                ],
                ['content' => $cleanQuestions]
            );

            // C. DISTRIBUSI KE ASESI (NUCLEAR OPTION: DELETE ALL -> RE-INSERT)
            $paraPeserta = DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)->get();

            if ($paraPeserta->count() > 0) {
                foreach ($paraPeserta as $peserta) {
                    
                    // 1. BACKUP NILAI LAMA (Biar nilai asesor gak ilang kalau soalnya sama)
                    // Kita simpan mapping: "Pertanyaan" => "Isi Jawaban Lama"
                    $dataLama = Ia07::where('id_data_sertifikasi_asesi', $peserta->id_data_sertifikasi_asesi)
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [trim($item->pertanyaan) => [
                                'pencapaian' => $item->pencapaian,
                                'jawaban_asesi' => $item->jawaban_asesi
                            ]];
                        });

                    // 2. HAPUS SEMUA SOAL LAMA (Bersih-bersih)
                    Ia07::where('id_data_sertifikasi_asesi', $peserta->id_data_sertifikasi_asesi)->delete();

                    // 3. INSERT SOAL BARU
                    foreach ($cleanQuestions as $item) {
                        $pertanyaanBersih = trim($item['pertanyaan']);
                        $nilaiBackup = $dataLama[$pertanyaanBersih] ?? null;

                        // --- LOGIC PENENTUAN NILAI ---
                        // Jika ada backup (soal lama), pakai nilai lama.
                        // Jika soal baru (backup null), PAKSA PAKAI 0 (biar gak error DB Strict).
                        
                        $pencapaianFix = $nilaiBackup ? $nilaiBackup['pencapaian'] : 0; 
                        $jawabanFix = $nilaiBackup ? $nilaiBackup['jawaban_asesi'] : null;

                        Ia07::create([
                            'id_data_sertifikasi_asesi' => $peserta->id_data_sertifikasi_asesi,
                            'pertanyaan' => $pertanyaanBersih,
                            'jawaban_diharapkan' => $item['kunci'] ?? '-',
                            
                            'pencapaian' => $pencapaianFix, // <--- INI KUNCINYA (Gak boleh NULL)
                            'jawaban_asesi' => $jawabanFix,
                            
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Soal berhasil disimpan (Reset & Update).');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Template Form FR.IA.07 (Admin Master View) - DEPRECATED for management
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        // Mock data sertifikasi
        $sertifikasi = new \App\Models\DataSertifikasiAsesi();
        $sertifikasi->id_data_sertifikasi_asesi = 0;
        
        $asesi = new \App\Models\Asesi(['nama_lengkap' => 'Template Master']);
        $sertifikasi->setRelation('asesi', $asesi);
        
        $jadwal = new \App\Models\Jadwal(['tanggal_pelaksanaan' => now()]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('asesor', new \App\Models\Asesor(['nama_lengkap' => 'Nama Asesor']));
        $jadwal->setRelation('jenisTuk', new \App\Models\JenisTUK(['jenis_tuk' => 'Tempat Kerja']));
        $sertifikasi->setRelation('jadwal', $jadwal);

        $this->cetakPDF(0); // This will trigger default questions for ID 0 if we handle it

        $units = $skema->unitKompetensi->map(function ($unit) {
            return [
                'code' => $unit->kode_unit,
                'title' => $unit->judul_unit
            ];
        });

        return view('frontend.FR_IA_07', [
            'asesi' => $asesi,
            'asesor' => $jadwal->asesor,
            'skema' => $skema,
            'units' => $units,
            'jenisTukOptions' => \App\Models\JenisTUK::pluck('jenis_tuk', 'id_jenis_tuk'),
            'jadwal' => $jadwal,
            'sertifikasi' => $sertifikasi,
            'isMasterView' => true,
        ]);
    }
}