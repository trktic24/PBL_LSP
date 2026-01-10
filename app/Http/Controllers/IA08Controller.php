<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\DataPortofolio;
use App\Models\BuktiPortofolioIA08IA09;
use App\Models\IA08;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IA08Controller extends Controller
{
    public function show($id_sertifikasi_asesi)
    {
        $data = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema',
            'jadwal.asesor',
            'jadwal.jenisTuk',
        ])->findOrFail($id_sertifikasi_asesi);

        $kelompokPekerjaan = KelompokPekerjaan::where(
            'id_skema',
            $data->jadwal->skema->id_skema
        )->get();

        $unitKompetensi = UnitKompetensi::whereIn(
            'id_kelompok_pekerjaan',
            $kelompokPekerjaan->pluck('id_kelompok_pekerjaan')
        )
            ->orderBy('urutan')
            ->get();

        $buktiPortofolio = DataPortofolio::where(
            'id_data_sertifikasi_asesi',
            $id_sertifikasi_asesi
        )
            ->with('buktiPortofolioIA08IA09')
            ->get()
            ->map(function ($item) {

                $ia08 = $item->buktiPortofolioIA08IA09->first();

                $item->array_valid   = $ia08 ? explode(', ', $ia08->is_valid)   : [];
                $item->array_asli    = $ia08 ? explode(', ', $ia08->is_asli)    : [];
                $item->array_terkini = $ia08 ? explode(', ', $ia08->is_terkini) : [];
                $item->array_memadai = $ia08 ? explode(', ', $ia08->is_memadai) : [];

                return $item;
            });


        // 🔑 Ambil data IA08 (untuk recall UI rekomendasi)
        $ia08 = IA08::where(
            'id_data_sertifikasi_asesi',
            $id_sertifikasi_asesi
        )->first();

        // 🔒 Lock form jika data sudah tersimpan atau readonly (admin)
        $isLocked = $ia08 || request()->get('is_readonly', false);

        return view(
            'frontend.IA_08.IA_08',
            compact(
                'buktiPortofolio',
                'kelompokPekerjaan',
                'unitKompetensi',
                'ia08',
                'isLocked'
            ) + [
                'id_data_sertifikasi_asesi' => $id_sertifikasi_asesi,
                'skema'    => $data->jadwal->skema,
                'jenisTuk' => $data->jadwal->jenisTuk,
                'asesor'   => $data->jadwal->asesor,
                'asesi'    => $data->asesi,
                'data_sesi' => [
                    'tanggal_asesmen' => $data->jadwal->tanggal_pelaksanaan
                        ? date('Y-m-d', strtotime($data->jadwal->tanggal_pelaksanaan))
                        : now()->format('Y-m-d'),
                ],
            ]
        );
    }

    public function store(Request $request)
    {
        // ===============================
        // 🔒 ADMIN READ-ONLY (DARI MIDDLEWARE)
        // ===============================
        if ($request->get('is_readonly')) {
            abort(403, 'Admin tidak diperbolehkan menyimpan data IA-08.');
        }

        // ===============================
        // VALIDASI DASAR
        // ===============================
        $request->validate([
            'id_portofolio' => 'required|array',
            'id_data_sertifikasi_asesi' => 'required',
            'rekomendasi' => 'required|in:kompeten,perlu observasi lanjut',
        ]);

        // ===============================
        // 🔒 CEGAH SUBMIT ULANG
        // ===============================
        $existingIA08 = IA08::where(
            'id_data_sertifikasi_asesi',
            $request->id_data_sertifikasi_asesi
        )->first();

        if ($existingIA08) {
            return back()->withErrors([
                'locked' => 'Data IA-08 sudah disimpan dan tidak dapat diubah kembali.',
            ]);
        }

        // ===============================
        // VALIDASI CHECKBOX BUKTI PORTOFOLIO
        // ===============================
        foreach ($request->id_portofolio as $id_portofolio) {
            $groups = [
                'valid'   => $request->valid[$id_portofolio]   ?? [],
                'asli'    => $request->asli[$id_portofolio]    ?? [],
                'terkini' => $request->terkini[$id_portofolio] ?? [],
                'memadai' => $request->memadai[$id_portofolio] ?? [],
            ];

            foreach ($groups as $values) {
                if (empty($values)) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'bukti_portofolio' =>
                                'Semua bukti portofolio WAJIB diisi (Valid, Asli, Terkini, dan Memadai).',
                        ]);
                }
            }
        }

        // ===============================
        // SIMPAN DATA (TRANSACTION)
        // ===============================
        DB::transaction(function () use ($request) {

            $ia08 = IA08::create([
                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                'rekomendasi' => $request->rekomendasi,

                'kelompok_pekerjaan' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                        ? $request->kelompok_pekerjaan
                        : null,

                'unit_kompetensi' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                        ? $request->unit_kompetensi
                        : null,

                'elemen' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                        ? $request->elemen
                        : null,

                'kuk' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                        ? $request->kuk
                        : null,
            ]);

            foreach ($request->id_portofolio as $id_portofolio) {
                BuktiPortofolioIA08IA09::create([
                    'id_portofolio' => $id_portofolio,
                    'id_ia08' => $ia08->id_ia08,

                    'is_valid'   => collect($request->valid[$id_portofolio])->implode(', '),
                    'is_asli'    => collect($request->asli[$id_portofolio])->implode(', '),
                    'is_terkini' => collect($request->terkini[$id_portofolio])->implode(', '),
                    'is_memadai' => collect($request->memadai[$id_portofolio])->implode(', '),

                    'daftar_pertanyaan_wawancara' =>
                        $request->pertanyaan[$id_portofolio] ?? null,

                    'kesimpulan_jawaban_asesi' =>
                        $request->kesimpulan[$id_portofolio] ?? null,
                ]);
            }
        });

        return back()->with(
            'success',
            'Verifikasi portofolio & rekomendasi asesor berhasil disimpan.'
        );
    }
}