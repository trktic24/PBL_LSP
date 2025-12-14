<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ak02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class Ak02Controller extends Controller
{
    public function edit($id_asesi)
    {
        // Ambil Data
        $asesi = DataSertifikasiAsesi::with([
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
            'asesi.user',
            'lembarJawabIa05' => function ($q) {
                $q->whereNotNull('pencapaian_ia05');
            },
            'ia06Answers' => function ($q) {
                $q->whereNotNull('pencapaian');
            },
            'ia07' => function ($q) {
                $q->whereNotNull('pencapaian');
            },
            'ia10',
            'ia02'
        ])->findOrFail($id_asesi);

        // --- PERBAIKAN LOGIKA VALIDASI DI SINI ---

        // Relasi HasMany (Mengembalikan Collection -> Aman pakai count())
        $ia05Done = $asesi->lembarJawabIa05->count() > 0;
        $ia06Done = $asesi->ia06Answers->count() > 0;
        $ia07Done = $asesi->ia07->count() > 0;

        // Relasi HasOne (Mengembalikan Object atau Null -> Cek tidak null)
        // JANGAN PAKAI ->count() > 0 untuk HasOne!
        $ia10Done = !is_null($asesi->ia10);
        $ia02Done = !is_null($asesi->ia02);

        $isFinalized = ($asesi->level_status >= 100);

        // Jika data belum lengkap, lempar kembali (kecuali sudah final)
        if (!$isFinalized && !($ia05Done && $ia06Done && $ia07Done && $ia10Done && $ia02Done)) {
            return redirect()->back()->with('error', 'Penilaian Asesmen (IA) belum lengkap. Mohon selesaikan penilaian IA terlebih dahulu.');
        }

        // Ambil data penilaian yang sudah ada
        $penilaianList = Ak02::where('id_data_sertifikasi_asesi', $id_asesi)
            ->get()
            ->keyBy('id_unit_kompetensi');

        return view('frontend.AK_02.FR_AK_02', compact('asesi', 'penilaianList'));
    }

    public function update(Request $request, $id_asesi)
    {
        $request->validate([
            'penilaian' => 'required|array',
            'global_kompeten' => 'nullable|in:Kompeten,Belum Kompeten',
        ]);

        // Ambil input global
        $globalKompeten = $request->input('global_kompeten');
        $globalTindakLanjut = $request->input('global_tindak_lanjut');
        $globalKomentar = $request->input('global_komentar');

        DB::beginTransaction();
        try {
            foreach ($request->penilaian as $idUnit => $data) {
                // Ambil checkbox (array)
                $bukti = isset($data['jenis_bukti']) ? $data['jenis_bukti'] : [];

                Ak02::updateOrCreate(
                    [
                        'id_unit_kompetensi' => $idUnit,
                        'id_data_sertifikasi_asesi' => $id_asesi,
                    ],
                    [
                        'jenis_bukti' => $bukti,
                        // Simpan input global ke setiap baris unit
                        'kompeten' => $globalKompeten,
                        'tindak_lanjut' => $globalTindakLanjut,
                        'komentar' => $globalKomentar,
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Rekaman Asesmen FR.AK.02 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakPDF($id_asesi)
    {
        // 1. Ambil Data Asesi Lengkap
        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
            'jadwal.skema.asesor',
            'jadwal.asesor',
        ])->findOrFail($id_asesi);

        // 2. Ambil Penilaian AK.02
        $ak02Data = Ak02::where('id_data_sertifikasi_asesi', $id_asesi)
            ->get()
            ->keyBy('id_unit_kompetensi');

        // 3. Mapping Data untuk View PDF
        // Header
        // Menggunakan optional chaining atau null coalescing untuk menghindari error jika relasi kosong
        $data['skema'] = $asesi->jadwal->skema->judul_skema ?? '-';
        $data['nomor_skema'] = $asesi->jadwal->skema->nomor_skema ?? '-';
        $data['tuk'] = $asesi->jadwal->tuk->nama_tuk ?? 'Tempat Kerja';
        $data['nama_asesor'] = $asesi->jadwal->asesor->nama_asesor ?? '-';
        $data['nama_asesi'] = $asesi->asesi->nama_lengkap ?? '-';
        $data['tanggal'] = date('d-m-Y');

        // Unit Kompetensi
        $data['unit_kompetensi'] = [];
        $allUnits = collect();

        if ($asesi->jadwal->skema->kelompokPekerjaan) {
            foreach ($asesi->jadwal->skema->kelompokPekerjaan as $kelompok) {
                if ($kelompok->unitKompetensi) {
                    $allUnits = $allUnits->merge($kelompok->unitKompetensi);
                    if (!isset($data['kelompok_pekerjaan'])) {
                        $data['kelompok_pekerjaan'] = $kelompok->nama_kelompok_pekerjaan;
                    }
                }
            }
        }

        foreach ($allUnits as $unit) {
            $data['unit_kompetensi'][] = [
                'kode' => $unit->kode_unit_kompetensi,
                'judul' => $unit->judul_unit_kompetensi,
            ];
        }

        // Bukti-Bukti
        $data['bukti_kompetensi'] = [];
        $firstRec = null;

        foreach ($allUnits as $unit) {
            $record = $ak02Data->get($unit->id_unit_kompetensi);
            if (!$firstRec && $record)
                $firstRec = $record;

            $jenisBukti = $record ? ($record->jenis_bukti ?? []) : [];

            $data['bukti_kompetensi'][] = [
                'unit' => $unit->judul_unit_kompetensi,
                'observasi' => in_array('observasi', $jenisBukti),
                'portofolio' => in_array('portofolio', $jenisBukti),
                'pihak_ketiga' => in_array('pihak_ketiga', $jenisBukti),
                'lisan' => in_array('lisan', $jenisBukti),
                'tertulis' => in_array('tertulis', $jenisBukti),
                'proyek' => in_array('proyek', $jenisBukti),
                'lainnya' => in_array('lainnya', $jenisBukti),
            ];
        }

        // Rekomendasi (Global)
        if ($firstRec) {
            $data['hasil_asesmen'] = ($firstRec->kompeten == 'Kompeten') ? 'kompeten' : 'belum_kompeten';
            $data['tindak_lanjut'] = $firstRec->tindak_lanjut;
            $data['komentar_asesor'] = $firstRec->komentar;
        } else {
            $data['hasil_asesmen'] = null;
            $data['tindak_lanjut'] = '-';
            $data['komentar_asesor'] = '-';
        }

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ak_02', ['data' => $data]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_AK_02_' . str_replace(' ', '_', $data['nama_asesi']) . '.pdf');
    }
}