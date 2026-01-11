<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\ListForm;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\MasterTUK;

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
                'url' => null, 
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.APL.01'])
            ],
            [
                'code' => 'FR.APL.02', 
                'name' => 'Asesmen Mandiri', 
                'db_field' => 'apl_02', 
                'checked' => (bool)$configDB->apl_02, 
                'url' => route('admin.skema.detail.edit_kelompok', ['id_kelompok' => $skema->kelompokPekerjaan->first()->id_kelompok_pekerjaan ?? 0]),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.APL.02'])
            ],
            // FASE 2
            [
                'code' => 'FR.MAPA.01', 
                'name' => 'Merencanakan Aktivitas dan Proses Asesmen', 
                'db_field' => 'fr_mapa_01', 
                'checked' => (bool)$configDB->fr_mapa_01, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.MAPA.01']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.MAPA.01'])
            ],
            [
                'code' => 'FR.MAPA.02', 
                'name' => 'Peta Instrumen Asesmen', 
                'db_field' => 'fr_mapa_02', 
                'checked' => (bool)$configDB->fr_mapa_02, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.MAPA.02']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.MAPA.02'])
            ],
            // FASE 3 (IA)
            [
                'code' => 'FR.IA.01', 
                'name' => 'Ceklis Observasi Aktivitas di Tempat Kerja', 
                'db_field' => 'fr_ia_01', 
                'checked' => (bool)$configDB->fr_ia_01, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.01']), 
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.01'])
            ], 
            [
                'code' => 'FR.IA.02', 
                'name' => 'Tugas Praktik Demonstrasi', 
                'db_field' => 'fr_ia_02', 
                'checked' => (bool)$configDB->fr_ia_02, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.02']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.02'])
            ],
            [
                'code' => 'FR.IA.03', 
                'name' => 'Pertanyaan Untuk Mendukung Observasi', 
                'db_field' => 'fr_ia_03', 
                'checked' => (bool)$configDB->fr_ia_03, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.03']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.03'])
            ], 
            [
                'code' => 'FR.IA.04', 
                'name' => 'Ceklis Verifikasi Portofolio', 
                'db_field' => 'fr_ia_04', 
                'checked' => (bool)$configDB->fr_ia_04, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.04']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.04'])
            ],
            [
                'code' => 'FR.IA.05', 
                'name' => 'Pertanyaan Tertulis Pilihan Ganda', 
                'db_field' => 'fr_ia_05', 
                'checked' => (bool)$configDB->fr_ia_05, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.05']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.05'])
            ],
            [
                'code' => 'FR.IA.06', 
                'name' => 'Pertanyaan Tertulis Esai', 
                'db_field' => 'fr_ia_06', 
                'checked' => (bool)$configDB->fr_ia_06, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.06']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.06'])
            ],
            [
                'code' => 'FR.IA.07', 
                'name' => 'Pertanyaan Lisan', 
                'db_field' => 'fr_ia_07', 
                'checked' => (bool)$configDB->fr_ia_07, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.07']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.07'])
            ],
            [
                'code' => 'FR.IA.08', 
                'name' => 'Ceklis Verifikasi Pihak Ketiga', 
                'db_field' => 'fr_ia_08', 
                'checked' => (bool)$configDB->fr_ia_08, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.08']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.08'])
            ],
            [
                'code' => 'FR.IA.09', 
                'name' => 'Pertanyaan Wawancara', 
                'db_field' => 'fr_ia_09', 
                'checked' => (bool)$configDB->fr_ia_09, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.09']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.09'])
            ],
            [
                'code' => 'FR.IA.10', 
                'name' => 'Klarifikasi Bukti Pihak Ketiga', 
                'db_field' => 'fr_ia_10', 
                'checked' => (bool)$configDB->fr_ia_10, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.10']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.10'])
            ],
            [
                'code' => 'FR.IA.11', 
                'name' => 'Ceklis Meninjau Instrumen Asesmen', 
                'db_field' => 'fr_ia_11', 
                'checked' => (bool)$configDB->fr_ia_11, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.11']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.IA.11'])
            ],
            // FASE 4 (Keputusan & Laporan)
            [
                'code' => 'FR.AK.01', 
                'name' => 'Persetujuan Asesmen dan Kerahasiaan', 
                'db_field' => 'fr_ak_01', 
                'checked' => (bool)$configDB->fr_ak_01, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.01']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.01'])
            ],
            [
                'code' => 'FR.AK.02', 
                'name' => 'Rekaman Asesmen Kompetensi', 
                'db_field' => 'fr_ak_02', 
                'checked' => (bool)$configDB->fr_ak_02, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.02']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.02'])
            ],
            [
                'code' => 'FR.AK.03', 
                'name' => 'Umpan Balik dan Catatan Asesmen', 
                'db_field' => 'fr_ak_03', 
                'checked' => (bool)$configDB->fr_ak_03, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.03']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.03'])
            ],
            [
                'code' => 'FR.AK.04', 
                'name' => 'Banding Asesmen', 
                'db_field' => 'fr_ak_04', 
                'checked' => (bool)$configDB->fr_ak_04, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.04']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.04'])
            ],
            [
                'code' => 'FR.AK.05', 
                'name' => 'Laporan Asesmen', 
                'db_field' => 'fr_ak_05', 
                'checked' => (bool)$configDB->fr_ak_05, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.05']),
                'admin_url' => route('admin.skema.form.ak05_list', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.06', 
                'name' => 'Meninjau Proses Asesmen', 
                'db_field' => 'fr_ak_06', 
                'checked' => (bool)$configDB->fr_ak_06, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.06']),
                'admin_url' => route('admin.ak06.show', ['id_skema' => $skema->id_skema])
            ],
            [
                'code' => 'FR.AK.07', 
                'name' => 'Ceklis Penyesuaian Wajar dan Beralasan', 
                'db_field' => 'fr_ak_07', 
                'checked' => (bool)$configDB->fr_ak_07, 
                'url' => route('admin.skema.template.list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.07']),
                'admin_url' => route('admin.skema.form.asesi_list', ['id_skema' => $skema->id_skema, 'form_code' => 'FR.AK.07'])
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
            'fr_ak_01', 'fr_ak_02', 'fr_ak_03', 'fr_ak_04', 'fr_ak_05', 'fr_ak_06', 'fr_ak_07',
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

        // Map data recursively for Alpine.js
        $mappedUnits = $kelompok->unitKompetensi->sortBy('urutan')->values()->map(function($unit) {
            
            // Map Elements
            $elements = $unit->elemen->sortBy('id_elemen')->values()->map(function($elemen) {
                
                // Map Kriteria
                $kriteria = $elemen->kriteria->sortBy('id_kriteria')->values()->map(function($kuk) {
                    $mapTipe = fn($t) => $t === 'aktivitas' ? 'K' : 'demonstrasi';
                    return [
                        'id' => $kuk->id_kriteria, 
                        'text' => $kuk->kriteria, 
                        'bukti' => $kuk->standar_industri_kerja, 
                        'is_kompeten' => $mapTipe($kuk->tipe),
                        'no_kriteria' => $kuk->no_kriteria
                    ];
                });

                // If no kriteria, provide one empty default
                if ($kriteria->isEmpty()) {
                    $kriteria = collect([
                        ['id' => null, 'text' => '', 'bukti' => '', 'is_kompeten' => 'demonstrasi', 'no_kriteria' => '1.1']
                    ]);
                }

                return [
                    'id' => $elemen->id_elemen,
                    'name' => $elemen->elemen,
                    'kriteria' => $kriteria
                ];
            });

            // If no elements, provide one empty default
            if ($elements->isEmpty()) {
                $elements = collect([
                    [
                        'id' => null, 
                        'name' => '', 
                        'kriteria' => collect([
                            ['id' => null, 'text' => '', 'bukti' => '', 'is_kompeten' => 'demonstrasi', 'no_kriteria' => '1.1']
                        ])
                    ]
                ]);
            }

            return [
                'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                'kode_unit' => $unit->kode_unit,
                'judul_unit' => $unit->judul_unit,
                'expanded' => true,
                'elements' => $elements
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
            'units.*.elements' => 'nullable|array',
            'units.*.elements.*.name' => 'nullable|string',
            'units.*.elements.*.kriteria' => 'nullable|array',
            'units.*.elements.*.kriteria.*.text' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $kelompok = KelompokPekerjaan::findOrFail($id_kelompok);
            
            // 1. Update Nama Kelompok
            $kelompok->update([
                'nama_kelompok_pekerjaan' => $request->nama_kelompok_pekerjaan
            ]);

            // 2. Proses Unit Kompetensi (Sync)
            $existingUnitIds = $kelompok->unitKompetensi->pluck('id_unit_kompetensi')->toArray();
            $submittedUnitIds = [];

            foreach ($request->units as $unitData) {
                $unit = null;
                
                if (!empty($unitData['id'])) {
                    $unit = UnitKompetensi::find($unitData['id']);
                }

                if ($unit) {
                    $unit->update([
                        'kode_unit' => $unitData['kode_unit'],
                        'judul_unit' => $unitData['judul_unit'],
                    ]);
                } else {
                    $unit = $kelompok->unitKompetensi()->create([
                        'kode_unit' => $unitData['kode_unit'],
                        'judul_unit' => $unitData['judul_unit'],
                        'urutan' => 1, // Logic urutan bisa diperbaiki jika perlu
                    ]);
                }
                $submittedUnitIds[] = $unit->id_unit_kompetensi;

                // --- Proses Elemen ---
                $existingElementIds = $unit->elemen->pluck('id_elemen')->toArray();
                $submittedElementIds = [];

                if (!empty($unitData['elements']) && is_array($unitData['elements'])) {
                    foreach ($unitData['elements'] as $elemIndex => $elemData) {
                        // Skip jika nama elemen kosong
                        if (empty($elemData['name'])) continue;

                        $elemen = null;
                        if (!empty($elemData['id'])) {
                            $elemen = \App\Models\Elemen::find($elemData['id']);
                        }

                        if ($elemen) {
                            $elemen->update(['elemen' => $elemData['name']]);
                        } else {
                            $elemen = $unit->elemen()->create(['elemen' => $elemData['name']]);
                        }
                        $submittedElementIds[] = $elemen->id_elemen;

                        // --- Proses Kriteria ---
                        $existingKriteriaIds = $elemen->kriteria->pluck('id_kriteria')->toArray();
                        $submittedKriteriaIds = [];

                        if (!empty($elemData['kriteria']) && is_array($elemData['kriteria'])) {
                            foreach ($elemData['kriteria'] as $kukIndex => $kukData) {
                                // Skip jika teks kriteria kosong
                                if (empty($kukData['text'])) continue;

                                // Tipe logic
                                $tipe = ($kukData['is_kompeten'] ?? 'demonstrasi') === 'K' ? 'aktivitas' : 'demonstrasi';
                                // No Kriteria logic: Use input or default to index
                                $noKriteria = $kukData['no_kriteria'] ?? ($elemIndex + 1) . '.' . ($kukIndex + 1);

                                $kuk = null;
                                if (!empty($kukData['id'])) {
                                    $kuk = \App\Models\KriteriaUnjukKerja::find($kukData['id']);
                                }

                                if ($kuk) {
                                    $kuk->update([
                                        'kriteria' => $kukData['text'],
                                        'no_kriteria' => $noKriteria,
                                        'standar_industri_kerja' => $kukData['bukti'] ?? null,
                                        'tipe' => $tipe
                                    ]);
                                } else {
                                    $kuk = $elemen->kriteria()->create([
                                        'kriteria' => $kukData['text'],
                                        'no_kriteria' => $noKriteria,
                                        'standar_industri_kerja' => $kukData['bukti'] ?? null,
                                        'tipe' => $tipe
                                    ]);
                                }
                                $submittedKriteriaIds[] = $kuk->id_kriteria;
                            }
                        }

                        // Delete orphaned Kriteria
                        $kriteriaToDelete = array_diff($existingKriteriaIds, $submittedKriteriaIds);
                        if (!empty($kriteriaToDelete)) {
                            \App\Models\KriteriaUnjukKerja::destroy($kriteriaToDelete);
                        }
                    }
                }

                // Delete orphaned Elements
                $elementsToDelete = array_diff($existingElementIds, $submittedElementIds);
                if (!empty($elementsToDelete)) {
                    \App\Models\Elemen::destroy($elementsToDelete);
                }
            }

            // 3. Hapus unit yang tidak ada di form submit (orphaned Units)
            $unitsToDelete = array_diff($existingUnitIds, $submittedUnitIds);
            if (!empty($unitsToDelete)) {
                UnitKompetensi::destroy($unitsToDelete);
            }

            DB::commit();
            return redirect()->route('admin.skema.detail', $kelompok->id_skema)
                             ->with('success', "Kelompok Pekerjaan dan Unit Kompetensi Skema '{$kelompok->skema->nama_skema}' (ID: {$kelompok->id_skema}) berhasil diperbarui.");

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

    /**
     * Menampilkan daftar asesi untuk form tertentu (generic list view).
     */
    public function showFormAsesiList($id_skema, $form_code)
    {
        $skema = Skema::findOrFail($id_skema);

        // mapping form_code -> config
        $mapping = [
            'FR.APL.01' => ['route' => 'APL_01_1', 'label' => 'FR.APL.01', 'name' => 'Permohonan Sertifikasi Kompetensi'],
            'FR.APL.02' => ['route' => 'apl02.view', 'label' => 'FR.APL.02', 'name' => 'Asesmen Mandiri'],
            'FR.MAPA.01' => ['route' => 'mapa01.index', 'label' => 'FR.MAPA.01', 'name' => 'Merencanakan Aktivitas dan Proses Asesmen'],
            'FR.MAPA.02' => ['route' => 'mapa02.show', 'label' => 'FR.MAPA.02', 'name' => 'Peta Instrumen Asesmen'],
            'FR.IA.01' => ['route' => 'admin.ia01.admin.view', 'label' => 'FR.IA.01', 'name' => 'Ceklis Observasi Aktivitas di Tempat Kerja'],
            'FR.IA.02' => ['route' => 'ia02.show', 'label' => 'FR.IA.02', 'name' => 'Tugas Praktik Demonstrasi'],
            'FR.IA.03' => ['route' => 'admin.ia03.index', 'label' => 'FR.IA.03', 'name' => 'Pertanyaan Untuk Mendukung Observasi'],
            'FR.IA.04' => ['route' => 'fria04a.show', 'label' => 'FR.IA.04', 'name' => 'Ceklis Verifikasi Portofolio'],
            'FR.IA.05' => ['route' => 'ia05.asesor', 'label' => 'FR.IA.05', 'name' => 'Pertanyaan Tertulis Pilihan Ganda'],
            'FR.IA.06' => ['route' => 'asesor.ia06.edit', 'label' => 'FR.IA.06', 'name' => 'Pertanyaan Tertulis Esai'],
            'FR.IA.07' => ['route' => 'ia07.asesor', 'label' => 'FR.IA.07', 'name' => 'Pertanyaan Lisan'],
            'FR.IA.08' => ['route' => 'ia08.show', 'label' => 'FR.IA.08', 'name' => 'Ceklis Verifikasi Pihak Ketiga'],
            'FR.IA.09' => ['route' => 'ia09.edit', 'label' => 'FR.IA.09', 'name' => 'Pertanyaan Wawancara'],
            'FR.IA.10' => ['route' => 'fr-ia-10.create', 'label' => 'FR.IA.10', 'name' => 'Klarifikasi Bukti Pihak Ketiga'],
            'FR.IA.11' => ['route' => 'admin.ia11.index', 'label' => 'FR.IA.11', 'name' => 'Ceklis Meninjau Instrumen Asesmen'],
            'FR.AK.01' => ['route' => 'ak01.create', 'label' => 'FR.AK.01', 'name' => 'Persetujuan Asesmen dan Kerahasiaan'],
            'FR.AK.02' => ['route' => 'ak02.edit', 'label' => 'FR.AK.02', 'name' => 'Rekaman Asesmen Kompetensi'],
            'FR.AK.03' => ['route' => 'ak03.create', 'label' => 'FR.AK.03', 'name' => 'Umpan Balik dan Catatan Asesmen'],
            'FR.AK.04' => ['route' => 'ak04.create', 'label' => 'FR.AK.04', 'name' => 'Banding Asesmen'],
            'FR.AK.05' => ['route' => 'admin.laporan.asesi.view', 'label' => 'FR.AK.05', 'name' => 'Laporan Asesmen'],
            'FR.AK.07' => ['route' => 'fr-ak-07.create', 'label' => 'FR.AK.07', 'name' => 'Ceklis Penyesuaian Wajar dan Beralasan'],
        ];

        $config = $mapping[$form_code] ?? [
            'route' => 'admin.asesor.assessment.detail',
            'label' => $form_code,
            'name' => 'Assessment Form'
        ];

        $sortColumn = request('sort', 'created_at');
        $sortDirection = request('direction', 'desc');

        // Fetch Asesi through Jadwal
        $query = DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.masterTuk',
            'jadwal.asesor'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        // Add sorting logic
        if ($sortColumn == 'nama_lengkap') {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->orderBy('asesi.nama_lengkap', $sortDirection)
                  ->select('data_sertifikasi_asesi.*');
        } elseif ($sortColumn == 'asesor') {
            $query->join('jadwal', 'data_sertifikasi_asesi.id_jadwal', '=', 'jadwal.id_jadwal')
                  ->join('asesor', 'jadwal.id_asesor', '=', 'asesor.id_asesor')
                  ->orderBy('asesor.nama_lengkap', $sortDirection)
                  ->select('data_sertifikasi_asesi.*');
        } elseif ($sortColumn == 'status') {
            // Sorting by status: rekomendasi_apl02 then rekomendasi_hasil_asesmen_AK02
            $query->orderBy('rekomendasi_apl02', $sortDirection)
                  ->orderBy('rekomendasi_hasil_asesmen_AK02', $sortDirection);
        } else {
            // Default sort (e.g. created_at)
            $query->orderBy('data_sertifikasi_asesi.' . $sortColumn, $sortDirection);
        }

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        // Mock objects for view context
        $user = auth()->user();
        $asesor = new Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'perPage' => request('per_page', 10),
            'targetRoute' => $config['route'],
            'buttonLabel' => $config['label'],
            'formName' => $config['name'],
        ]);
    }

    /**
     * Tampilkan daftar Jadwal untuk AK.05 (Laporan Keseluruhan per Jadwal)
     */
    public function showAk05JadwalList($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        
        // Ambil jadwal yang:
        // 1. Memiliki skema $id_skema
        // 2. Memiliki setidaknya 1 asesi (dataSertifikasiAsesi)
        $jadwalList = \App\Models\Jadwal::with(['asesor', 'masterTuk'])
            ->where('id_skema', $id_skema)
            ->whereHas('dataSertifikasiAsesi')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('Admin.master.skema.ak05_jadwal_list', [
            'skema' => $skema,
            'jadwalList' => $jadwalList,
        ]);
    }

    /**
     * Tampilkan daftar Jadwal untuk Template (Per Asesor context)
     */
    public function showTemplateJadwalList($id_skema, $form_code)
    {
        $skema = Skema::findOrFail($id_skema);
        
        // mapping form_code -> config untuk label/nama
        $mapping = [
            'FR.MAPA.01' => ['label' => 'FR.MAPA.01', 'name' => 'Merencanakan Aktivitas dan Proses Asesmen'],
            'FR.MAPA.02' => ['label' => 'FR.MAPA.02', 'name' => 'Peta Instrumen Asesmen'],
            'FR.IA.01' => ['label' => 'FR.IA.01', 'name' => 'Ceklis Observasi Aktivitas di Tempat Kerja'],
            'FR.IA.02' => ['label' => 'FR.IA.02', 'name' => 'Tugas Praktik Demonstrasi'],
            'FR.IA.03' => ['label' => 'FR.IA.03', 'name' => 'Pertanyaan Untuk Mendukung Observasi'],
            'FR.IA.04' => ['label' => 'FR.IA.04', 'name' => 'Ceklis Verifikasi Portofolio'],
            'FR.IA.05' => ['label' => 'FR.IA.05', 'name' => 'Pertanyaan Tertulis Pilihan Ganda'],
            'FR.IA.06' => ['label' => 'FR.IA.06', 'name' => 'Pertanyaan Tertulis Esai'],
            'FR.IA.07' => ['label' => 'FR.IA.07', 'name' => 'Pertanyaan Lisan'],
            'FR.IA.08' => ['label' => 'FR.IA.08', 'name' => 'Ceklis Verifikasi Pihak Ketiga'],
            'FR.IA.09' => ['label' => 'FR.IA.09', 'name' => 'Pertanyaan Wawancara'],
            'FR.IA.10' => ['label' => 'FR.IA.10', 'name' => 'Klarifikasi Bukti Pihak Ketiga'],
            'FR.IA.11' => ['label' => 'FR.IA.11', 'name' => 'Ceklis Meninjau Instrumen Asesmen'],
            'FR.AK.01' => ['label' => 'FR.AK.01', 'name' => 'Persetujuan Asesmen dan Kerahasiaan'],
            'FR.AK.02' => ['label' => 'FR.AK.02', 'name' => 'Rekaman Asesmen Kompetensi'],
            'FR.AK.03' => ['label' => 'FR.AK.03', 'name' => 'Umpan Balik dan Catatan Asesmen'],
            'FR.AK.04' => ['label' => 'FR.AK.04', 'name' => 'Banding Asesmen'],
            'FR.AK.05' => ['label' => 'FR.AK.05', 'name' => 'Laporan Asesmen'],
            'FR.AK.06' => ['label' => 'FR.AK.06', 'name' => 'Meninjau Proses Asesmen'],
            'FR.AK.07' => ['label' => 'FR.AK.07', 'name' => 'Ceklis Penyesuaian Wajar dan Beralasan'],
        ];

        $config = $mapping[$form_code] ?? ['label' => $form_code, 'name' => 'Formulir'];

        // Ambil jadwal yang:
        // 1. Memiliki skema $id_skema
        // 2. Memiliki setidaknya 1 asesi (dataSertifikasiAsesi)
        $jadwalList = \App\Models\Jadwal::with(['asesor', 'masterTuk'])
            ->where('id_skema', $id_skema)
            ->whereHas('dataSertifikasiAsesi')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('Admin.master.skema.template_jadwal_list', [
            'skema' => $skema,
            'jadwalList' => $jadwalList,
            'form_code' => $form_code,
            'formConfig' => $config
        ]);
    }
}
