<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            [
                'code' => 'FR.APL.01', 
                'name' => 'Permohonan Sertifikasi Kompetensi', 
                'db_field' => 'apl_01', 
                'checked' => (bool)$configDB->apl_01, 
                'url' => route('admin.apl01.show', ['id_skema' => $skema->id_skema]), 
                'admin_url' => route('admin.apl01.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.APL.02', 
                'name' => 'Asesmen Mandiri', 
                'db_field' => 'apl_02', 
                'checked' => (bool)$configDB->apl_02, 
                'url' => route('admin.skema.detail.edit_kelompok', ['id_kelompok' => $skema->kelompokPekerjaan->first()->id_kelompok_pekerjaan ?? 0]),
                'admin_url' => route('admin.apl02.show', ['id_skema' => $skema->id_skema])
            ],
            // FASE 2
            [
                'code' => 'FR.MAPA.01', 
                'name' => 'Merencanakan Aktivitas dan Proses Asesmen', 
                'db_field' => 'fr_mapa_01', 
                'checked' => (bool)$configDB->fr_mapa_01, 
                'url' => route('admin.skema.template.mapa01', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.mapa01.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.MAPA.02', 
                'name' => 'Peta Instrumen Asesmen', 
                'db_field' => 'fr_mapa_02', 
                'checked' => (bool)$configDB->fr_mapa_02, 
                'url' => route('admin.skema.template.mapa02', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.mapa02.show', ['id_skema' => $skema->id_skema])
            ],
            // FASE 3 (IA)
            [
                'code' => 'FR.IA.01', 
                'name' => 'Ceklis Observasi Aktivitas di Tempat Kerja', 
                'db_field' => 'fr_ia_01', 
                'checked' => (bool)$configDB->fr_ia_01, 
                'url' => route('admin.skema.template.ia01', ['id_skema' => $skema->id_skema]), 
                'admin_url' => route('admin.ia01.show', ['id_skema' => $skema->id_skema])
            ], 
            [
                'code' => 'FR.IA.02', 
                'name' => 'Tugas Praktik Demonstrasi', 
                'db_field' => 'fr_ia_02', 
                'checked' => (bool)$configDB->fr_ia_02, 
                'url' => route('admin.skema.template.ia02', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia02.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.03', 
                'name' => 'Pertanyaan Untuk Mendukung Observasi', 
                'db_field' => 'fr_ia_03', 
                'checked' => (bool)$configDB->fr_ia_03, 
                'url' => route('admin.skema.template.ia03', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia03.show', ['id_skema' => $skema->id_skema])
            ], 
            [
                'code' => 'FR.IA.04', 
                'name' => 'Ceklis Verifikasi Portofolio', 
                'db_field' => 'fr_ia_04', 
                'checked' => (bool)$configDB->fr_ia_04, 
                'url' => route('admin.skema.template.ia04', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia04.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.05', 
                'name' => 'Pertanyaan Tertulis Pilihan Ganda', 
                'db_field' => 'fr_ia_05', 
                'checked' => (bool)$configDB->fr_ia_05, 
                'url' => route('admin.skema.template.ia05', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia05.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.06', 
                'name' => 'Pertanyaan Tertulis Esai', 
                'db_field' => 'fr_ia_06', 
                'checked' => (bool)$configDB->fr_ia_06, 
                'url' => route('admin.skema.template.ia06', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia06.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.07', 
                'name' => 'Pertanyaan Lisan', 
                'db_field' => 'fr_ia_07', 
                'checked' => (bool)$configDB->fr_ia_07, 
                'url' => route('admin.skema.template.ia07', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia07.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.08', 
                'name' => 'Ceklis Verifikasi Pihak Ketiga', 
                'db_field' => 'fr_ia_08', 
                'checked' => (bool)$configDB->fr_ia_08, 
                'url' => route('admin.skema.template.ia08', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia08.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.09', 
                'name' => 'Pertanyaan Wawancara', 
                'db_field' => 'fr_ia_09', 
                'checked' => (bool)$configDB->fr_ia_09, 
                'url' => route('admin.skema.template.ia09', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia09.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.10', 
                'name' => 'Klarifikasi Bukti Pihak Ketiga', 
                'db_field' => 'fr_ia_10', 
                'checked' => (bool)$configDB->fr_ia_10, 
                'url' => route('admin.skema.template.ia10', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia10.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.IA.11', 
                'name' => 'Ceklis Meninjau Instrumen Asesmen', 
                'db_field' => 'fr_ia_11', 
                'checked' => (bool)$configDB->fr_ia_11, 
                'url' => route('admin.skema.template.ia11', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ia11.show', ['id_skema' => $skema->id_skema])
            ],
            // FASE 4 (Keputusan & Laporan)
            [
                'code' => 'FR.AK.01', 
                'name' => 'Persetujuan Asesmen dan Kerahasiaan', 
                'db_field' => 'fr_ak_01', 
                'checked' => (bool)$configDB->fr_ak_01, 
                'url' => route('admin.skema.template.ak01', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ak01.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.02', 
                'name' => 'Rekaman Asesmen Kompetensi', 
                'db_field' => 'fr_ak_02', 
                'checked' => (bool)$configDB->fr_ak_02, 
                'url' => route('admin.skema.template.ak02', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ak02.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.03', 
                'name' => 'Umpan Balik dan Catatan Asesmen', 
                'db_field' => 'fr_ak_03', 
                'checked' => (bool)$configDB->fr_ak_03, 
                'url' => route('admin.skema.template.ak03', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ak03.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.04', 
                'name' => 'Banding Asesmen', 
                'db_field' => 'fr_ak_04', 
                'checked' => (bool)$configDB->fr_ak_04, 
                'url' => route('admin.skema.template.ak04', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ak04.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.05', 
                'name' => 'Laporan Asesmen', 
                'db_field' => 'fr_ak_05', 
                'checked' => (bool)$configDB->fr_ak_05, 
                'url' => route('admin.skema.template.ak05', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ak05.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.06', 
                'name' => 'Meninjau Proses Asesmen', 
                'db_field' => 'fr_ak_06', 
                'checked' => (bool)$configDB->fr_ak_06, 
                'url' => route('admin.skema.template.ak06', ['id_skema' => $skema->id_skema]),
                'admin_url' => route('admin.ak06.show', ['id_skema' => $skema->id_skema])
            ],
        ];

        return view('Admin.master.skema.detail_skema', [
            'skema' => $skema,
            'formConfig' => $formConfig // Kirim data yang sudah di-format
        ]);
    }

    /**
     * Update Form Configuration (ListForm).
     */
    public function updateListForm(Request $request, $id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
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

        return redirect()->back()->with('success', "Konfigurasi Formulir untuk Skema '{$skema->nama_skema}' (ID: {$skema->id_skema}) berhasil diperbarui.");
    }

    /**
     * Form Tambah Kelompok & Unit.
     */
    public function createKelompok($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        return view('Admin.master.skema.add_kelompokpekerjaan', compact('skema'));
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
            return redirect()->route('admin.skema.detail', $id_skema)
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
        // Eager load unitKompetensi and their elements with kriteria
        $kelompok = KelompokPekerjaan::with(['unitKompetensi.elemen.kriteria'])->findOrFail($id_kelompok);
        $skema = $kelompok->skema;

        // Map APL02 data (Elemen 1 & its KUKs) into a plain array for Alpine.js
        $mappedUnits = $kelompok->unitKompetensi->map(function($unit) {
            $firstElemen = $unit->elemen->sortBy('id_elemen')->first();
            
            // Map 3 KUKs (sesuai permintaan "3 edit")
            $kuks = $firstElemen ? $firstElemen->kriteria->sortBy('id_kriteria')->values() : collect();
            
            // Map 'aktivitas' to 'K' for UI, others to 'demonstrasi'
            $mapTipe = fn($t) => $t === 'aktivitas' ? 'K' : 'demonstrasi';

            return [
                'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                'kode_unit' => $unit->kode_unit,
                'judul_unit' => $unit->judul_unit,
                'expanded' => true,
                'elemen_1_name' => $firstElemen ? $firstElemen->elemen : '',
                'kriteria_1' => $kuks->get(0) ? [
                    'id' => $kuks[0]->id_kriteria, 
                    'text' => $kuks[0]->kriteria, 
                    'bukti' => $kuks[0]->standar_industri_kerja, 
                    'is_kompeten' => $mapTipe($kuks[0]->tipe)
                ] : ['id' => null, 'text' => '', 'bukti' => '', 'is_kompeten' => 'demonstrasi'],
                'kriteria_2' => $kuks->get(1) ? [
                    'id' => $kuks[1]->id_kriteria, 
                    'text' => $kuks[1]->kriteria, 
                    'bukti' => $kuks[1]->standar_industri_kerja, 
                    'is_kompeten' => $mapTipe($kuks[1]->tipe)
                ] : ['id' => null, 'text' => '', 'bukti' => '', 'is_kompeten' => 'demonstrasi'],
                'kriteria_3' => $kuks->get(2) ? [
                    'id' => $kuks[2]->id_kriteria, 
                    'text' => $kuks[2]->kriteria, 
                    'bukti' => $kuks[2]->standar_industri_kerja, 
                    'is_kompeten' => $mapTipe($kuks[2]->tipe)
                ] : ['id' => null, 'text' => '', 'bukti' => '', 'is_kompeten' => 'demonstrasi'],
            ];
        });

        return view('Admin.master.skema.edit_kelompokpekerjaan', compact('kelompok', 'skema', 'mappedUnits'));
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
            'units.*.elemen_1_name' => 'nullable|string',
            'units.*.kriteria_1' => 'nullable|array',
            'units.*.kriteria_1.text' => 'nullable|string',
            'units.*.kriteria_1.bukti' => 'nullable|string',
            'units.*.kriteria_1.is_kompeten' => 'nullable|string',
            'units.*.kriteria_2' => 'nullable|array',
            'units.*.kriteria_2.text' => 'nullable|string',
            'units.*.kriteria_2.bukti' => 'nullable|string',
            'units.*.kriteria_2.is_kompeten' => 'nullable|string',
            'units.*.kriteria_3' => 'nullable|array',
            'units.*.kriteria_3.text' => 'nullable|string',
            'units.*.kriteria_3.bukti' => 'nullable|string',
            'units.*.kriteria_3.is_kompeten' => 'nullable|string',
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
                if (!empty($unitData['id'])) {
                    // Update Existing Unit
                    $unit = UnitKompetensi::find($unitData['id']);
                    if ($unit) {
                        $unit->update([
                            'kode_unit' => $unitData['kode_unit'],
                            'judul_unit' => $unitData['judul_unit'],
                        ]);
                        $submittedIds[] = $unit->id_unit_kompetensi;

                        // CRUD Elemen Pertama & KUK untuk APL-02
                        $firstElemen = $unit->elemen()->orderBy('id_elemen')->first();
                        if (!$firstElemen) {
                            $firstElemen = $unit->elemen()->create(['elemen' => $unitData['elemen_1_name'] ?? '']);
                        } else {
                            $firstElemen->update(['elemen' => $unitData['elemen_1_name'] ?? '']);
                        }

                        // Sync 3 KUKs
                        $this->syncKuk($firstElemen, $unitData['kriteria_1'] ?? null, '1.1');
                        $this->syncKuk($firstElemen, $unitData['kriteria_2'] ?? null, '1.2');
                        $this->syncKuk($firstElemen, $unitData['kriteria_3'] ?? null, '1.3');
                    }
                } else {
                    // Create New Unit
                    $newUnit = $kelompok->unitKompetensi()->create([
                        'kode_unit' => $unitData['kode_unit'],
                        'judul_unit' => $unitData['judul_unit'],
                        'urutan' => 1,
                    ]);
                    $submittedIds[] = $newUnit->id_unit_kompetensi;

                    // Create Elemen Pertama & 3 KUK default
                    $newElemen = $newUnit->elemen()->create(['elemen' => $unitData['elemen_1_name'] ?? '']);
                    $this->syncKuk($newElemen, $unitData['kriteria_1'] ?? null, '1.1');
                    $this->syncKuk($newElemen, $unitData['kriteria_2'] ?? null, '1.2');
                    $this->syncKuk($newElemen, $unitData['kriteria_3'] ?? null, '1.3');
                }
            }

            // 3. Hapus unit yang tidak ada di form submit (berarti dihapus user)
            $idsToDelete = array_diff($existingIds, $submittedIds);
            UnitKompetensi::destroy($idsToDelete);

            DB::commit();
            return redirect()->route('admin.skema.detail', $kelompok->id_skema)
                             ->with('success', "Kelompok Pekerjaan dan Unit Kompetensi Skema'{$kelompok->skema->nama_skema}' (ID: {$kelompok->id_skema}) berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper to sync KUK data.
     */
    private function syncKuk($elemen, $data, $no_kriteria)
    {
        if (empty($data['text'])) return;

        // Map UI 'K' back to 'aktivitas' (enum)
        $tipe = ($data['is_kompeten'] ?? 'demonstrasi') === 'K' ? 'aktivitas' : 'demonstrasi';

        if (!empty($data['id'])) {
            $kuk = \App\Models\KriteriaUnjukKerja::find($data['id']);
            if ($kuk) {
                $kuk->update([
                    'kriteria' => $data['text'],
                    'no_kriteria' => $no_kriteria,
                    'standar_industri_kerja' => $data['bukti'] ?? null,
                    'tipe' => $tipe
                ]);
                return;
            }
        }

        $elemen->kriteria()->create([
            'no_kriteria' => $no_kriteria,
            'kriteria' => $data['text'],
            'tipe' => $tipe,
            'standar_industri_kerja' => $data['bukti'] ?? null
        ]);
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

        return redirect()->route('admin.skema.detail', $idSkema)
                         ->with('success', "Kelompok Pekerjaan dan semua unit kompetensi Skema'{$nama}' (ID: {$idSkema}) berhasil dihapus.");
    }

    public function destroyUnit(Request $request, $id_unit)
    {
        $unit = UnitKompetensi::findOrFail($id_unit);
        $unit->delete();

        $noUrut = $request->query('no', '-');

        return back()->with('success', "Unit Kompetensi No. {$noUrut} berhasil dihapus.");
    }

}