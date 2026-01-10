<?php

namespace App\Http\Controllers\Asesor\IA11;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\IA11\IA11;
use App\Models\DataSertifikasiAsesi;

class IA11Controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,asesor']);
    }

    // =====================================================
    // CREATE
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'nama_produk' => 'required|string|max:255',
            'spesifikasi_umum' => 'required|array',
            'bahan_produk' => 'required|array',
            'spesifikasi_teknis' => 'required|array',
            'pencapaian_spesifikasi' => 'required|array',
            'pencapaian_performa' => 'required|array',
            'rekomendasi' => 'nullable|string|max:50',
            'rancangan_produk' => 'nullable|string',
            'standar_industri' => 'nullable|string',
            'tanggal_pengoperasian' => 'nullable|date',
            'gambar_produk' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {

            $ia11 = IA11::create([
                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                'rancangan_produk' => $request->rancangan_produk,
                'nama_produk' => $request->nama_produk,
                'standar_industri' => $request->standar_industri,
                'tanggal_pengoperasian' => $request->tanggal_pengoperasian,
                'gambar_produk' => $request->gambar_produk ? 'images/'.$request->gambar_produk : null,
                'rekomendasi' => $request->rekomendasi,
            ]);

            $ia11->spesifikasiProduk()->create([
                'dimensi_produk' => $request->spesifikasi_umum['dimensi_produk'] ?? null,
                'berat_produk'   => $request->spesifikasi_umum['berat_produk'] ?? null,
            ]);

            $ia11->bahanProduk()->createMany(
                collect($request->bahan_produk)->map(fn($b)=>[
                    'nama_bahan' => $b['nama_bahan']
                ])->toArray()
            );

            $ia11->spesifikasiTeknis()->createMany(
                collect($request->spesifikasi_teknis)->map(fn($t)=>[
                    'data_teknis' => $t['data_teknis']
                ])->toArray()
            );

            foreach ($request->pencapaian_spesifikasi as $item) {
                $ia11->pencapaianSpesifikasi()->create([
                    'id_spesifikasi_ia11' => $item['id_spesifikasi_ia11'],
                    'hasil_reviu' => $item['hasil_reviu'] ?? null,
                    'catatan_temuan' => $item['catatan_temuan'] ?? null,
                ]);
            }

            foreach ($request->pencapaian_performa as $item) {
                $ia11->pencapaianPerforma()->create([
                    'id_performa_ia11' => $item['id_performa_ia11'],
                    'hasil_reviu' => $item['hasil_reviu'] ?? null,
                    'catatan_temuan' => $item['catatan_temuan'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json(['message'=>'IA.11 berhasil disimpan'],201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('IA11 STORE ERROR: '.$e->getMessage());
            return response()->json(['message'=>'Gagal menyimpan IA.11'],500);
        }
    }

    // =====================================================
    // READ
    // =====================================================
    public function show($id_data_sertifikasi_asesi)
    {
        $ia11 = IA11::with([
            'spesifikasiProduk',
            'bahanProduk',
            'spesifikasiTeknis',
            'pencapaianSpesifikasi.spesifikasiItem',
            'pencapaianPerforma.performaItem',
            'dataSertifikasiAsesi.asesi',
            'dataSertifikasiAsesi.jadwal.asesor',
            'dataSertifikasiAsesi.jadwal.skema',
            'dataSertifikasiAsesi.jadwal.masterTuk',
        ])
        ->where('id_data_sertifikasi_asesi',$id_data_sertifikasi_asesi)
        ->first();

        if (!$ia11) {
            $sertifikasi = DataSertifikasiAsesi::with(['asesi','jadwal'])->findOrFail($id_data_sertifikasi_asesi);

            $ia11 = new IA11(['id_data_sertifikasi_asesi'=>$id_data_sertifikasi_asesi]);
            $ia11->setRelation('dataSertifikasiAsesi',$sertifikasi);
            $ia11->setRelation('bahanProduk',collect());
            $ia11->setRelation('spesifikasiTeknis',collect());
            $ia11->setRelation('pencapaianSpesifikasi',collect());
            $ia11->setRelation('pencapaianPerforma',collect());
        }

        return view('asesor.IA11.index', compact('ia11'));
    }

    // =====================================================
    // UPDATE (FINAL)
    // =====================================================
    public function update(Request $request, $id_ia11)
    {
        $ia11 = IA11::findOrFail($id_ia11);

        DB::beginTransaction();
        try {

            $ia11->update($request->only([
                'nama_produk','standar_industri','tanggal_pengoperasian','rekomendasi'
            ]));

            if ($request->filled('spesifikasi_umum')) {
                $ia11->spesifikasiProduk()->updateOrCreate([],[
                    'dimensi_produk'=>$request->spesifikasi_umum['dimensi_produk'] ?? null,
                    'berat_produk'=>$request->spesifikasi_umum['berat_produk'] ?? null,
                ]);
            }

            if ($request->filled('bahan_produk')) {
                $ia11->bahanProduk()->delete();
                $ia11->bahanProduk()->createMany(
                    collect($request->bahan_produk)->map(fn($b)=>['nama_bahan'=>$b['nama_bahan']])->toArray()
                );
            }

            if ($request->filled('spesifikasi_teknis')) {
                $ia11->spesifikasiTeknis()->delete();
                $ia11->spesifikasiTeknis()->createMany(
                    collect($request->spesifikasi_teknis)->map(fn($t)=>['data_teknis'=>$t['data_teknis']])->toArray()
                );
            }

            foreach ($request->pencapaian_spesifikasi ?? [] as $item) {
                $ia11->pencapaianSpesifikasi()->updateOrCreate(
                    ['id_spesifikasi_ia11'=>$item['id_spesifikasi_ia11']],
                    ['hasil_reviu'=>$item['hasil_reviu'] ?? null,'catatan_temuan'=>$item['catatan_temuan'] ?? null]
                );
            }

            foreach ($request->pencapaian_performa ?? [] as $item) {
                $ia11->pencapaianPerforma()->updateOrCreate(
                    ['id_performa_ia11'=>$item['id_performa_ia11']],
                    ['hasil_reviu'=>$item['hasil_reviu'] ?? null,'catatan_temuan'=>$item['catatan_temuan'] ?? null]
                );
            }

            DB::commit();
            return response()->json(['message'=>'IA.11 berhasil diperbarui']);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('IA11 UPDATE ERROR: '.$e->getMessage());
            return response()->json(['message'=>'Gagal update IA.11'],500);
        }
    }

    // =====================================================
    // DELETE
    // =====================================================
    public function destroy($id_ia11)
    {
        IA11::findOrFail($id_ia11)->delete();
        return response()->json(['message'=>'IA.11 berhasil dihapus']);
    }

    // =====================================================
    // CETAK PDF
    // =====================================================
    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        $ia11 = IA11::with([
            'dataSertifikasiAsesi.asesi',
            'pencapaianSpesifikasi.spesifikasiItem',
            'pencapaianPerforma.performaItem',
        ])->where('id_data_sertifikasi_asesi',$id_data_sertifikasi_asesi)->firstOrFail();

        $pdf = Pdf::loadView('pdf.ia_11', compact('ia11'));
        return $pdf->stream('FR_IA_11.pdf');
    }
}
