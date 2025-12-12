<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use App\Models\ListForm;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailSkemaController extends Controller
{
    /**
     * TAB 1: Informasi Detail Skema
     * View: master.skema.detail_skema
     */
    public function index($id_skema)
    {
        // 1. Load Skema & Config
        $skema = Skema::with(['category', 'kelompokPekerjaan.unitKompetensi', 'listForm'])->findOrFail($id_skema);

        // 2. Cek/Buat Config Default
        if (!$skema->listForm) {
            ListForm::create(['id_skema' => $skema->id_skema]);
            $skema->refresh();
        }

        // 3. [PENTING] Mapping Data Database ke Array Frontend
        // Ini agar frontend tabel Anda tetap jalan tanpa mengubah desainnya
        $configDB = $skema->listForm;
        
        $formConfig = [
            // FASE 1
            ['code' => 'FR.APL.01', 'name' => 'Permohonan Sertifikasi Kompetensi', 'db_field' => 'apl_01', 'checked' => (bool)$configDB->apl_01],
            ['code' => 'FR.APL.02', 'name' => 'Asesmen Mandiri', 'db_field' => 'apl_02', 'checked' => (bool)$configDB->apl_02],
            // FASE 2
            ['code' => 'FR.MAPA.01', 'name' => 'Merencanakan Aktivitas dan Proses Asesmen', 'db_field' => 'fr_mapa_01', 'checked' => (bool)$configDB->fr_mapa_01],
            ['code' => 'FR.MAPA.02', 'name' => 'Peta Instrumen Asesmen', 'db_field' => 'fr_mapa_02', 'checked' => (bool)$configDB->fr_mapa_02],
            // FASE 3 (IA)
            ['code' => 'FR.IA.01', 'name' => 'Ceklis Observasi Aktivitas di Tempat Kerja', 'db_field' => 'fr_ia_01', 'checked' => (bool)$configDB->fr_ia_01],
            ['code' => 'FR.IA.02', 'name' => 'Tugas Praktik Demonstrasi', 'db_field' => 'fr_ia_02', 'checked' => (bool)$configDB->fr_ia_02],
            ['code' => 'FR.IA.03', 'name' => 'Pertanyaan Untuk Mendukung Observasi', 'db_field' => 'fr_ia_03', 'checked' => (bool)$configDB->fr_ia_03],
            ['code' => 'FR.IA.04', 'name' => 'Ceklis Verifikasi Portofolio', 'db_field' => 'fr_ia_04', 'checked' => (bool)$configDB->fr_ia_04],
            ['code' => 'FR.IA.05', 'name' => 'Pertanyaan Tertulis Pilihan Ganda', 'db_field' => 'fr_ia_05', 'checked' => (bool)$configDB->fr_ia_05],
            ['code' => 'FR.IA.06', 'name' => 'Pertanyaan Tertulis Esai', 'db_field' => 'fr_ia_06', 'checked' => (bool)$configDB->fr_ia_06],
            ['code' => 'FR.IA.07', 'name' => 'Pertanyaan Lisan', 'db_field' => 'fr_ia_07', 'checked' => (bool)$configDB->fr_ia_07],
            ['code' => 'FR.IA.08', 'name' => 'Ceklis Verifikasi Pihak Ketiga', 'db_field' => 'fr_ia_08', 'checked' => (bool)$configDB->fr_ia_08],
            ['code' => 'FR.IA.09', 'name' => 'Pertanyaan Wawancara', 'db_field' => 'fr_ia_09', 'checked' => (bool)$configDB->fr_ia_09],
            ['code' => 'FR.IA.10', 'name' => 'Klarifikasi Bukti Pihak Ketiga', 'db_field' => 'fr_ia_10', 'checked' => (bool)$configDB->fr_ia_10],
            ['code' => 'FR.IA.11', 'name' => 'Ceklis Meninjau Instrumen Asesmen', 'db_field' => 'fr_ia_11', 'checked' => (bool)$configDB->fr_ia_11],
            // FASE 4 (AK)
            ['code' => 'FR.AK.01', 'name' => 'Persetujuan Asesmen dan Kerahasiaan', 'db_field' => 'fr_ak_01', 'checked' => (bool)$configDB->fr_ak_01],
            ['code' => 'FR.AK.02', 'name' => 'Rekaman Asesmen Kompetensi', 'db_field' => 'fr_ak_02', 'checked' => (bool)$configDB->fr_ak_02],
            ['code' => 'FR.AK.03', 'name' => 'Umpan Balik dan Catatan Asesmen', 'db_field' => 'fr_ak_03', 'checked' => (bool)$configDB->fr_ak_03],
            ['code' => 'FR.AK.04', 'name' => 'Banding Asesmen', 'db_field' => 'fr_ak_04', 'checked' => (bool)$configDB->fr_ak_04],
            ['code' => 'FR.AK.05', 'name' => 'Laporan Asesmen', 'db_field' => 'fr_ak_05', 'checked' => (bool)$configDB->fr_ak_05],
            ['code' => 'FR.AK.06', 'name' => 'Meninjau Proses Asesmen', 'db_field' => 'fr_ak_06', 'checked' => (bool)$configDB->fr_ak_06],
        ];

        return view('master.skema.detail_skema', [
            'skema' => $skema,
            'formConfig' => $formConfig // Kirim data yang sudah di-format
        ]);
    }

    /**
     * Update Form Configuration (ListForm).
     */
    public function updateListForm(Request $request, $id_skema)
    {
        // Define all the boolean fields in your ListForm table
        $fields = [
            'apl_01', 'apl_02',
            'fr_ia_01', 'fr_ia_02', 'fr_ia_03', 'fr_ia_04', 'fr_ia_05',
            'fr_ia_06', 'fr_ia_07', 'fr_ia_08', 'fr_ia_09', 'fr_ia_10', 'fr_ia_11',
            'fr_ak_01', 'fr_ak_02', 'fr_ak_03', 'fr_ak_04', 'fr_ak_05', 'fr_ak_06',
            'fr_mapa_01', 'fr_mapa_02'
        ];

        $dataToUpdate = [];
        foreach ($fields as $field) {
            // Checkboxes only send a value if checked. $request->has() checks for presence.
            $dataToUpdate[$field] = $request->has($field);
        }

        // Update or create the record
        ListForm::updateOrCreate(
            ['id_skema' => $id_skema],
            $dataToUpdate
        );

        return redirect()->back()->with('success', 'Konfigurasi Formulir berhasil diperbarui.');
    }

    /**
     * Form Tambah Kelompok & Unit.
     */
    public function createKelompok($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        return view('master.skema.add_kelompokpekerjaan', compact('skema'));
    }

    /**
     * Simpan Kelompok & Unit.
     */
    public function storeKelompok(Request $request, $id_skema)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_kelompok_pekerjaan' => 'required|string|max:255',
            'units' => 'required|array|min:1', // Wajib ada minimal 1 unit
            'units.*.kode_unit' => 'required|string|max:50',
            'units.*.judul_unit' => 'required|string|max:255',
        ], [
            'units.required' => 'Harap tambahkan minimal satu Unit Kompetensi.',
            'units.*.kode_unit.required' => 'Kode unit tidak boleh kosong.',
            'units.*.judul_unit.required' => 'Judul unit tidak boleh kosong.',
        ]);

        // 2. Gunakan Transaksi Database (Agar aman, semua tersimpan atau tidak sama sekali)
        DB::beginTransaction();
        try {
            $skema = Skema::findOrFail($id_skema);

            // A. Simpan Kelompok Pekerjaan
            $kelompok = $skema->kelompokPekerjaan()->create([
                'nama_kelompok_pekerjaan' => $request->nama_kelompok_pekerjaan
            ]);

            // B. Simpan Semua Unit Kompetensi (Looping)
            foreach ($request->units as $index => $unitData) {
                $kelompok->unitKompetensi()->create([
                    'kode_unit'  => $unitData['kode_unit'],
                    'judul_unit' => $unitData['judul_unit'],
                    'urutan'     => $index + 1, // Urutan otomatis berdasarkan input
                ]);
            }

            DB::commit(); // Simpan permanen jika sukses

            // 3. Redirect dengan Pesan Sukses (Ini yang memicu Pop-up Hijau)
            return redirect()->route('skema.detail', $id_skema)
                             ->with('success', 'Kelompok Pekerjaan dan Unit Kompetensi berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Edit Kelompok.
     */
    public function editKelompok($id_kelompok)
    {
        $kelompok = KelompokPekerjaan::with('unitKompetensi')->findOrFail($id_kelompok);
        $skema = $kelompok->skema; // Ambil parent skema untuk breadcrumb/info
        
        return view('master.skema.edit_kelompokpekerjaan', compact('kelompok', 'skema'));
    }

    /**
     * Update Kelompok & Unit (Sinkronisasi).
     */
    public function updateKelompok(Request $request, $id_kelompok)
    {
        $request->validate([
            'nama_kelompok_pekerjaan' => 'required|string|max:255',
            'units' => 'required|array|min:1',
            'units.*.kode_unit' => 'required|string|max:50',
            'units.*.judul_unit' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $kelompok = KelompokPekerjaan::findOrFail($id_kelompok);
            
            // 1. Update Nama Kelompok
            $kelompok->update([
                'nama_kelompok_pekerjaan' => $request->nama_kelompok_pekerjaan
            ]);

            // 2. Proses Unit Kompetensi (Sync)
            $existingIds = $kelompok->unitKompetensi->pluck('id_unit_kompetensi')->toArray();
            $submittedIds = [];

            foreach ($request->units as $unitData) {
                if (isset($unitData['id'])) {
                    // Update Existing Unit
                    $unit = UnitKompetensi::find($unitData['id']);
                    if ($unit) {
                        $unit->update([
                            'kode_unit' => $unitData['kode_unit'],
                            'judul_unit' => $unitData['judul_unit'],
                        ]);
                        $submittedIds[] = $unit->id_unit_kompetensi;
                    }
                } else {
                    // Create New Unit
                    $newUnit = $kelompok->unitKompetensi()->create([
                        'kode_unit' => $unitData['kode_unit'],
                        'judul_unit' => $unitData['judul_unit'],
                        'urutan' => 1, // Atau logic urutan lain
                    ]);
                    $submittedIds[] = $newUnit->id_unit_kompetensi;
                }
            }

            // 3. Hapus unit yang tidak ada di form submit (berarti dihapus user)
            $idsToDelete = array_diff($existingIds, $submittedIds);
            UnitKompetensi::destroy($idsToDelete);

            DB::commit();
            return redirect()->route('skema.detail', $kelompok->id_skema)
                             ->with('success', "Kelompok Pekerjaan dan Unit Kompetensi Skema'{$kelompok->skema->nama_skema}' (ID: {$kelompok->id_skema}) berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus Kelompok (Cascade Delete Unit).
     */
    public function destroyKelompok($id_kelompok)
    {
        $kelompok = KelompokPekerjaan::findOrFail($id_kelompok);
        $idSkema = $kelompok->id_skema;
        $nama = $kelompok->nama_kelompok_pekerjaan;

        // Karena di migration sudah on delete cascade, unit otomatis terhapus
        $kelompok->delete();

        return redirect()->route('skema.detail', $idSkema)
                         ->with('success', "Kelompok Pekerjaan dan semua unit kompetensi Skema'{$nama}' (ID: {$idSkema}) berhasil dihapus.");
    }

    public function destroyUnit(Request $request, $id_unit)
    {
        $unit = UnitKompetensi::findOrFail($id_unit);
        $unit->delete();

        $noUrut = $request->query('no', '-');

        return back()->with('success', "Unit Kompetensi No. {$noUrut} berhasil dihapus.");
    }

    /**
     * TAB 2: Kelompok Pekerjaan & Unit Kompetensi
     * View: master.skema.detail_kelompokpekerjaan
     */
    public function kelompok($id_skema)
    {
        // Load skema beserta kelompok pekerjaan dan unit kompetensinya
        $skema = Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        // [PERBAIKAN] Arahkan ke view yang benar & hapus variable yang tidak ada
        return view('master.skema.detail_kelompokpekerjaan', compact('skema'));
    }

    /**
     * TAB 3: Bank Soal / Form Asesmen
     * View: master.skema.detail_banksoal
     */
    public function bankSoal($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        
        // Data dummy Form Asesmen (Nanti diganti database real)
        $formAsesmen = [
            ['kode' => 'FR.APL.01', 'nama' => 'Permohonan Sertifikasi Kompetensi', 'warna' => 'bg-blue-600'],
            ['kode' => 'FR.APL.02', 'nama' => 'Asesmen Mandiri', 'warna' => 'bg-blue-600'],
            ['kode' => 'FR.MAPA.01', 'nama' => 'Merencanakan Aktivitas dan Proses Asesmen', 'warna' => 'bg-green-600'],
            ['kode' => 'FR.AK.01', 'nama' => 'Persetujuan Asesmen dan Kerahasiaan', 'warna' => 'bg-yellow-500'],
            ['kode' => 'FR.IA.01', 'nama' => 'Ceklis Observasi Aktivitas di Tempat Kerja', 'warna' => 'bg-purple-600'],
            // ...
        ];

        return view('master.skema.detail_banksoal', compact('skema', 'formAsesmen'));
    }

}