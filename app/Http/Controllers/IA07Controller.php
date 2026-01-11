<?php

namespace App\Http\Controllers;

use App\Models\Ia07;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataSertifikasiAsesi;
use App\Models\JenisTUK;

class IA07Controller extends Controller
{
    /**
     * Sync pertanyaan dari Template ke Database IA07.
     * Rules:
     * 1. Jika data belum ada, buat baru.
     * 2. Jika data sudah ada tapi jawaban masih kosong, update pertanyaan (jika template berubah).
     * 3. Jika data sudah ada dan jawaban terisi, LOCK (jangan ubah).
     */
    private function ensureQuestionsSynced($sertifikasi)
    {
        $idSertifikasi = $sertifikasi->id_data_sertifikasi_asesi;
        $skema = $sertifikasi->jadwal->skema;
        $unitKompetensiList = $skema->unitKompetensi;

        // 1. Ambil Template
        $template = MasterFormTemplate::where('id_skema', $skema->id_skema)
            ->where('id_jadwal', $sertifikasi->id_jadwal)
            ->where('form_code', 'FR.IA.07')
            ->first();

        // Fallback ke template master (tanpa jadwal)
        if (!$template) {
            $template = MasterFormTemplate::where('id_skema', $skema->id_skema)
                ->whereNull('id_jadwal')
                ->where('form_code', 'FR.IA.07')
                ->first();
        }

        // Parse Template Questions: Expected format { "unit_id": ["q1", "q2"] }
        $templateQuestions = [];
        if ($template && !empty($template->content)) {
            $templateQuestions = $template->content;
        }

        // Ambil existing IA07
        $existingIA07 = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();

        // 2. Iterasi per Unit
        foreach ($unitKompetensiList as $unit) {
            $unitId = $unit->id_unit_kompetensi;
            $questionsForUnit = $templateQuestions[$unitId] ?? []; // Array string pertanyaan

            // Filter existing IA07 records for this unit
            // Note: Since we just added id_unit_kompetensi, existing records might have NULL.
            // We might need to rely on order or update them if possible, but for new flow:
            $currentUnitRecords = $existingIA07->where('id_unit_kompetensi', $unitId); 
            
            // If strictly new implementation, we assume we want to match index based on array
            // BUT: Database ID is persistent.
            // Strategy: Check by index i.
            
            // Let's re-query to match by index if needed, or simply iterate.
            // To properly sync by index (0, 1, 2...), we need a consistent way to identify which DB record corresponds to which Template Index.
            // Since there is no "index" column, we rely on creation order or ID order for that Unit.
            
            $sortedRecords = $currentUnitRecords->sortBy('id_ia07')->values();

            foreach ($questionsForUnit as $index => $qText) {
                if (isset($sortedRecords[$index])) {
                    // CASE: Record Exists
                    $record = $sortedRecords[$index];
                    
                    // CHECK: Is it locked? (Has Answer)
                    $isLocked = !empty($record->jawaban_asesi) || !is_null($record->pencapaian);

                    if (!$isLocked) {
                        // UPDATE if text changed
                        if ($record->pertanyaan !== $qText) {
                            $record->update(['pertanyaan' => $qText]);
                        }
                    }
                } else {
                    // CASE: New Question from Template
                    Ia07::create([
                        'id_data_sertifikasi_asesi' => $idSertifikasi,
                        'id_unit_kompetensi' => $unitId,
                        'pertanyaan' => $qText,
                        'pencapaian' => null,
                        'jawaban_asesi' => null,
                        'jawaban_diharapkan' => 'Lihat Kunci Jawaban'
                    ]);
                }
            }
        }
    }

    /**
     * Menampilkan halaman Form FR.IA.07 (khusus Asesor, menggunakan view tunggal FR_IA_07.blade.php).
     */
    public function index($idSertifikasi)
    {
        // 1. Ambil Data
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.skema.unitKompetensi',
            'jadwal.jenisTuk'
        ])->findOrFail($idSertifikasi);

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor;
        $skema = $sertifikasi->jadwal->skema;
        $jadwal = $sertifikasi->jadwal;

        // 2. SYNC LOGIC
        $this->ensureQuestionsSynced($sertifikasi);

        // 3. Ambil Data IA07 dari DB (Grouped by Unit)
        // Structure for View: [ unit_id => [ 'unit' => UnitObject, 'questions' => [Ia07Objects] ] ]
        $ia07Records = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();
        
        $dataIA07 = [];
        foreach ($skema->unitKompetensi as $unit) {
            $questions = $ia07Records->where('id_unit_kompetensi', $unit->id_unit_kompetensi);
            $dataIA07[] = [
                'unit' => $unit,
                'questions' => $questions
            ];
        }

        $jenisTukOptions = JenisTUK::pluck('jenis_tuk', 'id_jenis_tuk');

        // Fallbacks
        $asesi = $asesi ?? (object) ['nama_lengkap' => 'Nama Asesi (DB KOSONG)'];
        $asesor = $asesor ?? (object) ['nama_lengkap' => 'Nama Asesor (DB KOSONG)', 'nomor_regis' => 'MET.000.000000.2019'];
        $skema = $skema ?? (object) ['nama_skema' => 'SKEMA KOSONG', 'nomor_skema' => 'N/A'];

        return view('frontend.FR_IA_07', compact('asesi', 'asesor', 'skema', 'jenisTukOptions', 'jadwal', 'sertifikasi', 'dataIA07'));
    }

    /**
     * Menyimpan data dari Form FR.IA.07.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_tuk' => 'required',
            'tanggal_pelaksanaan' => 'required|date',
            'umpan_balik_asesi' => 'nullable|string',
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
        ]);

        $idSertifikasi = $request->input('id_data_sertifikasi_asesi');

        DB::beginTransaction();
        try {
            // 1. Update Profile Cert (TUK, Date, etc if needed - currently just stored locally or related tables)
            // Note: In real app, update Jadwal or Sertifikasi date/TUK if required.
            
            // 2. Save Questions
            // Format input: jawaban[id_ia07] = text, keputusan[id_ia07] = K/BK
            $jawabans = $request->input('jawaban', []);
            $keputusans = $request->input('keputusan', []);

            foreach ($jawabans as $idIA07 => $textJawaban) {
                $status = $keputusans[$idIA07] ?? null;
                $isKompeten = ($status === 'K');
                
                Ia07::where('id_ia07', $idIA07)->update([
                    'jawaban_asesi' => $textJawaban,
                    'pencapaian' => $status ? ($isKompeten ? 1 : 0) : null
                ]);
            }

            // 3. Save Umpan Balik (Assuming stored in a generic feedback table or just ignored for now as per schema limitations, 
            // OR if DataSertifikasiAsesi has a field. For now, we commit the questions.)
            
            DB::commit();
            return redirect()->route('ia07.cetak', $idSertifikasi)->with('success', 'Penilaian FR.IA.07 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    /**
     * [MASTER] Menampilkan editor tamplate
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::with('unitKompetensi')->findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.IA.07')
                                    ->first();
        
        // Structure: { unit_id: [q1, q2] }
        $questions = $template ? $template->content : [];
        
        // Ensure structure is object/array
        if (is_string($questions)) $questions = json_decode($questions, true);
        if (!$questions) $questions = [];

        return view('Admin.master.skema.template.ia07', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'questions' => $questions
        ]);
    }

    /**
     * [MASTER] Simpan/Update template
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        // Debugging: Ensure input is array
        // Input name: questions[unit_id][]
        
        $data = $request->input('questions', []);
        
        // Cleanup empty values
        foreach ($data as $unitId => $qs) {
            $data[$unitId] = array_values(array_filter($qs, fn($q) => !empty(trim($q))));
        }

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.IA.07'
            ],
            ['content' => $data]
        );

        return redirect()->back()->with('success', 'Templat IA-07 berhasil diperbarui.');
    }

    public function cetakPDF($idSertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            'jadwal.skema.unitKompetensi'
        ])->findOrFail($idSertifikasi);

        // Ensure Sync before printing
        $this->ensureQuestionsSynced($sertifikasi);

        // Get Data
        $ia07Records = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();
        
        // Group for PDF
        $unitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi;
        $dataIA07 = [];
        foreach ($unitKompetensi as $unit) {
            $questions = $ia07Records->where('id_unit_kompetensi', $unit->id_unit_kompetensi);
            if ($questions->isNotEmpty()) {
                $dataIA07[] = [
                    'unit' => $unit,
                    'questions' => $questions
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.ia_07', [
            'sertifikasi' => $sertifikasi,
            'dataIA07' => $dataIA07, // Passing structured data
            'unitKompetensi' => $unitKompetensi
        ]);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('FR_IA_07_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }

    // Obsolete methods removed/replaced
}