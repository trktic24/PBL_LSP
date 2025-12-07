<?php

namespace App\Http\Controllers\Asesi\IA11;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Import semua Models TRANSAKSI dan DETAIL yang dibutuhkan
use App\Models\IA11\IA11;
use App\Models\IA11\SpesifikasiProdukIA11;
use App\Models\IA11\BahanProdukIA11;
use App\Models\IA11\SpesifikasiTeknisIA11;
// Model Pivot M:M tidak perlu di-import jika menggunakan relasi IA11->pencapaianSpesifikasi()->create()
// use App\Models\IA11\PencapaianSpesifikasiIA11;
// use App\Models\IA11\PencapaianPerformaIA11;
use App\Models\DataSertifikasiAsesi;

class IA11Controller extends Controller
{
    // --- CREATE ---
    public function store(Request $request)
    {
        // 1. Validasi Input (WAJIB)
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'nama_produk' => 'required|string|max:255',
            'spesifikasi_umum' => 'required|array',
            'bahan_produk' => 'required|array',
            'spesifikasi_teknis' => 'required|array',
            'pencapaian_spesifikasi' => 'required|array',
            'pencapaian_performa' => 'required|array',
            'rekomendasi' => 'nullable|string|max:50',
            // Tambahkan validasi untuk fields lain di IA11 yang dikirim
            'rancangan_produk' => 'nullable|string',
            'standar_industri' => 'nullable|string',
            'tanggal_pengoperasian' => 'nullable|date',
            'gambar_produk' => 'nullable|string', // Asumsi ini adalah path/URL gambar
        ]);

        DB::beginTransaction();

        try {
            // STEP 1: Simpan Data IA11 (Header)
            $path = $request->gambar_produk ? 'images/' . $request->gambar_produk : null;
            $ia11 = IA11::create([
                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                'rancangan_produk' => $request->rancangan_produk,
                'nama_produk' => $request->nama_produk,
                'standar_industri' => $request->standar_industri,
                'tanggal_pengoperasian' => $request->tanggal_pengoperasian,
                'gambar_produk' => $path, // pakai path baru
                'rekomendasi' => $request->rekomendasi,
            ]);

            // STEP 2: Simpan Relasi 1:1 (Spesifikasi Produk Umum)
            $ia11->spesifikasiProduk()->create([
                'dimensi_produk' => $request->spesifikasi_umum['dimensi_produk'] ?? null,
                'berat_produk' => $request->spesifikasi_umum['berat_produk'] ?? null,
            ]);

            // STEP 3: Simpan Relasi 1:M (Bahan Produk dan Spesifikasi Teknis)
            $bahanData = collect($request->bahan_produk)
                ->map(function ($item) {
                    return ['nama_bahan' => $item['nama_bahan']];
                })
                ->toArray();
            $ia11->bahanProduk()->createMany($bahanData);

            $teknisData = collect($request->spesifikasi_teknis)
                ->map(function ($item) {
                    return ['data_teknis' => $item['data_teknis']];
                })
                ->toArray();
            $ia11->spesifikasiTeknis()->createMany($teknisData);

            // STEP 4: Simpan Relasi M:M (Pencapaian Spesifikasi & Performa)
            // Menggunakan relasi HasMany (lebih bersih dan otomatis set id_ia11)
            foreach ($request->pencapaian_spesifikasi as $pencapaian) {
                $ia11->pencapaianSpesifikasi()->create([
                    'id_spesifikasi_ia11' => $pencapaian['id_spesifikasi_ia11'],
                    'hasil_reviu' => $pencapaian['hasil_reviu'] ?? null,
                    'catatan_temuan' => $pencapaian['catatan_temuan'] ?? null,
                ]);
            }

            foreach ($request->pencapaian_performa as $pencapaian) {
                $ia11->pencapaianPerforma()->create([
                    'id_performa_ia11' => $pencapaian['id_performa_ia11'],
                    'hasil_reviu' => $pencapaian['hasil_reviu'] ?? null,
                    'catatan_temuan' => $pencapaian['catatan_temuan'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Data IA.11 berhasil disimpan!', 'id' => $ia11->id_ia11], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan IA.11: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan data.', 'error' => $e->getMessage()], 500);
        }
    }

    // --- READ ---
    public function show($id_data_sertifikasi_asesi)
    {
        // Panggil data IA11 berdasarkan FK (id_data_sertifikasi_asesi)
        $ia11 = IA11::with([
            'spesifikasiProduk',
            'bahanProduk',
            'spesifikasiTeknis',
            // Eager loading bersarang: Pivot -> Master
            'pencapaianSpesifikasi.spesifikasiItem',
            'pencapaianPerforma.performaItem',
            // Eager loading data header asesi
            'dataSertifikasiAsesi.asesi',
            'dataSertifikasiAsesi.jadwal.asesor',
            'dataSertifikasiAsesi.jadwal.skema',
            'dataSertifikasiAsesi.jadwal.jenisTuk',
            'dataSertifikasiAsesi.jadwal.tuk',
            // Relasi ini sudah di-eager load di atas, tapi tidak masalah diulang untuk memastikan
            'dataSertifikasiAsesi.jadwal.skema.kelompokPekerjaan',
            'dataSertifikasiAsesi.jadwal.skema.kelompokPekerjaan.unitKompetensi',
        ])
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->first();

        // Jika data IA11 belum ada, inisiasi dengan data kosong (NULL) dan ambil data header asesi
        if (!$ia11) {
            $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk', 'jadwal.tuk'])->find($id_data_sertifikasi_asesi);

            if (!$sertifikasi) {
                return response()->json(['message' => 'Data Sertifikasi Asesi tidak ditemukan.'], 404);
            }

            // Inisialisasi variabel kosong agar view tidak error
            $ia11 = new IA11([
                'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                'rancangan_produk' => null,
                'nama_produk' => null,
                'standar_industri' => null,
                'tanggal_pengoperasian' => null,
                'gambar_produk' => null,
                'rekomendasi' => null,
            ]);
            $ia11->setRelation('spesifikasiProduk', null);
            $ia11->setRelation('bahanProduk', collect());
            $ia11->setRelation('spesifikasiTeknis', collect());
            $ia11->setRelation('pencapaianSpesifikasi', collect());
            $ia11->setRelation('pencapaianPerforma', collect());
        } else {
            $sertifikasi = $ia11->dataSertifikasiAsesi;
        }

        // Ambil data header untuk view
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor;
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        // Data yang sudah dimuat (sudah ada di $ia11)
        $spesifikasi = $ia11->pencapaianSpesifikasi;
        $performa = $ia11->pencapaianPerforma;

        $trackerUrl = $sertifikasi->jadwal?->id_jadwal ? '/tracker/' . $sertifikasi->jadwal->id_jadwal : '/dashboard';

        // Ganti 'ia11.show' menjadi 'ia11.IA11' sesuai nama file blade
        return view('ia11.IA11', compact('ia11', 'sertifikasi', 'asesi', 'asesor', 'skema', 'jenisTuk', 'tuk', 'tanggal', 'spesifikasi', 'performa', 'trackerUrl'));
    }

    // --- UPDATE ---
    public function update(Request $request, $id)
    {
        // ... (Validasi tetap sama)
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'spesifikasi_umum' => 'required|array',
            'bahan_produk' => 'required|array',
            'spesifikasi_teknis' => 'required|array',
            'pencapaian_spesifikasi' => 'required|array',
            'pencapaian_performa' => 'required|array',
            'rekomendasi' => 'nullable|string|max:50',
            // Tambahkan validasi untuk fields lain di IA11 yang dikirim
            'rancangan_produk' => 'nullable|string',
            'standar_industri' => 'nullable|string',
            'tanggal_pengoperasian' => 'nullable|date',
            'gambar_produk' => 'nullable|string',
        ]);

        $ia11 = IA11::find($id);
        if (!$ia11) {
            return response()->json(['message' => 'Data IA.11 tidak ditemukan.'], 404);
        }

        DB::beginTransaction();
        try {
            $path = $request->gambar_produk ? 'images/' . $request->gambar_produk : $ia11->gambar_produk;
            // STEP 1: Update Data IA11 (Header)
            $ia11->update([
                'rancangan_produk' => $request->rancangan_produk,
                'nama_produk' => $request->nama_produk,
                'standar_industri' => $request->standar_industri,
                'tanggal_pengoperasian' => $request->tanggal_pengoperasian,
                'gambar_produk' => $path, // <-----
                'rekomendasi' => $request->rekomendasi,
            ]);

            // STEP 2: Update Relasi 1:1 (Spesifikasi Produk Umum) - menggunakan updateOrCreate
            $ia11->spesifikasiProduk()->updateOrCreate(
                ['id_ia11' => $ia11->id_ia11],
                [
                    'dimensi_produk' => $request->spesifikasi_umum['dimensi_produk'] ?? null,
                    'berat_produk' => $request->spesifikasi_umum['berat_produk'] ?? null,
                ],
            );

            // STEP 3: Update Relasi 1:M (Bahan Produk dan Spesifikasi Teknis) - Strategi: Hapus & Buat Baru

            // Bahan Produk
            $ia11->bahanProduk()->delete();
            $bahanData = collect($request->bahan_produk)
                ->map(function ($item) {
                    return ['nama_bahan' => $item['nama_bahan']];
                })
                ->toArray();
            $ia11->bahanProduk()->createMany($bahanData);

            // Spesifikasi Teknis
            $ia11->spesifikasiTeknis()->delete();
            $teknisData = collect($request->spesifikasi_teknis)
                ->map(function ($item) {
                    return ['data_teknis' => $item['data_teknis']];
                })
                ->toArray();
            $ia11->spesifikasiTeknis()->createMany($teknisData);

            // STEP 4: Update Relasi M:M (Pencapaian Spesifikasi & Performa) - Strategi: Hapus & Buat Baru

            // Pencapaian Spesifikasi
            // Menggunakan relasi hasMany (dari IA11 Model)
            $ia11->pencapaianSpesifikasi()->delete();
            foreach ($request->pencapaian_spesifikasi as $pencapaian) {
                // Menggunakan relasi HasMany->create() lebih disarankan
                $ia11->pencapaianSpesifikasi()->create([
                    'id_spesifikasi_ia11' => $pencapaian['id_spesifikasi_ia11'],
                    'hasil_reviu' => $pencapaian['hasil_reviu'] ?? null,
                    'catatan_temuan' => $pencapaian['catatan_temuan'] ?? null,
                ]);
            }

            // Pencapaian Performa
            $ia11->pencapaianPerforma()->delete();
            foreach ($request->pencapaian_performa as $pencapaian) {
                $ia11->pencapaianPerforma()->create([
                    'id_performa_ia11' => $pencapaian['id_performa_ia11'],
                    'hasil_reviu' => $pencapaian['hasil_reviu'] ?? null,
                    'catatan_temuan' => $pencapaian['catatan_temuan'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Data IA.11 berhasil diperbarui!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui IA.11: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memperbarui data.', 'error' => $e->getMessage()], 500);
        }
    }

    // --- DELETE ---
    // Logika sudah benar, karena onDelete('cascade') seharusnya menangani semua relasi.
    public function destroy($id)
    {
        $ia11 = IA11::find($id);
        if (!$ia11) {
            return response()->json(['message' => 'Data IA.11 tidak ditemukan.'], 404);
        }

        DB::beginTransaction();
        try {
            // Menghapus data IA11 header. Semua detail akan terhapus karena onDelete('cascade').
            $ia11->delete();

            DB::commit();
            return response()->json(['message' => 'Data IA.11 dan semua detailnya berhasil dihapus!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus IA.11: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus data.', 'error' => $e->getMessage()], 500);
        }
    }
}
