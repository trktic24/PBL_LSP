<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Skema; // Nanti kita buat model ini
use App\Models\UnitKompetensi; // Nanti kita buat model ini
use App\Models\Kuk; // Nanti kita buat model ini

class Ia01Controller extends Controller
{
    /**
     * Menampilkan halaman step form IA.01 berdasarkan urutan.
     */
    public function showStep(Request $request, $skema_id, $urutan)
    {
        // 1. Ambil Skema & Unit Kompetensi (UK) dari DB
        $skema = Skema::findOrFail($skema_id);
        $unitKompetensi = UnitKompetensi::where('skema_id', $skema_id)
                                        ->where('urutan', $urutan)
                                        ->firstOrFail(); // Kalo ga ada, 404

        // 2. Ambil semua KUK untuk UK tersebut
        $kuks = $unitKompetensi->kuks()->orderBy('nomor_kuk')->get();

        // 3. Ambil total steps untuk skema ini (buat tombol 'Selesai')
        $totalSteps = $skema->unitKompetensis()->count();

        // 4. Ambil data yang udah disimpen di session (biar isian 'back' gak hilang)
        $data_sesi = $request->session()->get("ia01.skema_{$skema_id}", []);

        // 5. Tentukan template mana yang mau dipakai
        if ($urutan == 1) {
            // Form pertama (FR.IA.01) yang ada data Asesor & tabel Ya/Tidak
            return view('frontend.IA_01.template_aktivitas', compact(
                'unitKompetensi',
                'kuks',
                'data_sesi',
                'totalSteps'
            ));
        } else {
            // Form sisanya (Demonstrasi) yang cuma tabel K/BK
            return view('frontend.IA_01.template_demonstrasi', compact(
                'unitKompetensi',
                'kuks',
                'data_sesi',
                'totalSteps'
            ));
        }
    }

    /**
     * Memvalidasi dan menyimpan data dari step form ke session.
     */
    public function storeStep(Request $request, $skema_id, $urutan)
    {
        // 1. Ambil Skema & UK
        $skema = Skema::findOrFail($skema_id);
        $unitKompetensi = UnitKompetensi::where('skema_id', $skema_id)
                                        ->where('urutan', $urutan)
                                        ->firstOrFail();

        // 2. Siapin aturan validasi dinamis
        $validationRules = [];

        // Validasi khusus untuk Step 1 (Form Asesor)
        if ($urutan == 1) {
            $validationRules['nama_asesor'] = 'required|string|max:255';
            $validationRules['tuk'] = 'required|in:sewaktu,tempat_kerja,mandiri';
            $validationRules['tanggal_asesmen'] = 'required|date';
        }

        // Validasi KUK (berlaku untuk semua step)
        $kukIds = $unitKompetensi->kuks()->pluck('id')->toArray();
        $validationRules['kuk'] = 'required|array';

        // Pastikan semua KUK di halaman ini diisi
        foreach ($kukIds as $id) {
            $validationRules["kuk.{$id}"] = ['required', Rule::in(['K', 'BK'])];
        }

        // 3. Eksekusi validasi
        $validatedData = $request->validate($validationRules, [
            'kuk.*.required' => 'Penilaian Kompeten (K) atau Belum Kompeten (BK) harus diisi untuk setiap KUK.',
            'nama_asesor.required' => 'Nama Asesor wajib diisi.',
            'tuk.required' => 'TUK wajib dipilih.',
            'tanggal_asesmen.required' => 'Tanggal asesmen wajib diisi.',
        ]);

        // 4. Ambil data lama dari session dan gabungkan (merge)
        $sessionKey = "ia01.skema_{$skema_id}";
        $oldData = $request->session()->get($sessionKey, []);

        // Gabungkan data KUK. Hati-hati, array KUK harus di-merge beda
        $oldKuks = $oldData['kuk'] ?? [];
        $newKuks = $validatedData['kuk'] ?? [];
        $validatedData['kuk'] = $oldKuks + $newKuks; // Pake operator '+' biar nge-merge array asosiatif

        $newData = array_merge($oldData, $validatedData);

        // 5. Simpan data gabungan ke session
        $request->session()->put($sessionKey, $newData);

        // 6. Cek ini step terakhir atau bukan
        $totalSteps = $skema->unitKompetensis()->count();

        if ($urutan < $totalSteps) {
            // Belum selesai, lanjut ke step berikutnya
            return redirect()->route('ia01.show', [
                'skema_id' => $skema_id,
                'urutan' => $urutan + 1
            ]);
        } else {
            // =======================================================
            // == SUDAH SELESAI! SAATNYA SIMPAN KE DATABASE ==
            // =======================================================

            // Ambil SEMUA data dari session
            $finalData = $request->session()->get($sessionKey);

            // TODO: Tulis logic lo untuk nyimpen $finalData ke database
            // Misal:
            // $asesmen = Asesmen::create([
            //     'skema_id' => $skema_id,
            //     'asesi_id' => auth()->id(), // Ambil ID asesi yg login
            //     'asesor_id' => $finalData['nama_asesor'], // Ini harusnya ID, bukan nama
            //     'tanggal' => $finalData['tanggal_asesmen'],
            //     'tuk' => $finalData['tuk'],
            //     'status' => 'selesai',
            // ]);

            // // Simpen hasil KUK-nya
            // foreach ($finalData['kuk'] as $kuk_id => $hasil) {
            //     HasilKuk::create([
            //         'asesmen_id' => $asesmen->id,
            //         'kuk_id' => $kuk_id,
            //         'hasil' => $hasil, // 'K' atau 'BK'
            //     ]);
            // }

            // Jangan lupa hapus session kalo udah selesai
            $request->session()->forget($sessionKey);

            // Redirect ke halaman dashboard atau 'selesai'
            return redirect()->route('home')->with('success', 'Asesmen IA.01 berhasil disimpan!');
        }
    }
}
