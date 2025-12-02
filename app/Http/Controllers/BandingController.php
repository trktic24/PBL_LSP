<?php

namespace App\Http\Controllers;

use App\Models\Banding;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;

class BandingController extends Controller
{
    /**
     * Tampilkan form Banding pertama.
     * $id = id DataSertifikasiAsesi
     */
    public function create()
    {
    // Ambil DataSertifikasiAsesi terakhir
    $dataSertifikasiAsesi = DataSertifikasiAsesi::with('asesi', 'jadwal.asesor', 'jadwal.skema')->latest('id_data_sertifikasi_asesi')->first(); // orderBy('id', 'desc')
        

    if (!$dataSertifikasiAsesi) {
        return redirect()->back()->with('error', 'Belum ada data sertifikasi asesi.');
    }

    $namaAsesi = $dataSertifikasiAsesi->asesi->nama_lengkap ?? '-';
    $namaAsesor = $dataSertifikasiAsesi->jadwal->asesor->nama_lengkap ?? '-';
    $noSkema = $dataSertifikasiAsesi->jadwal->skema->kode_unit ?? '-';
    $namaSkema = $dataSertifikasiAsesi->jadwal->skema->nama_skema ?? '-';

    return view('banding', compact('dataSertifikasiAsesi', 'namaAsesi', 'namaAsesor', 'noSkema', 'namaSkema'));
    }

    /**
     * Simpan Banding yang dikirim dari form frontend.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'required|integer|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'tuk_sewaktu' => 'nullable|boolean',
            'tuk_tempatkerja' => 'nullable|boolean',
            'tuk_mandiri' => 'nullable|boolean',
            'ya_tidak_1' => 'required|in:Ya,Tidak',
            'ya_tidak_2' => 'required|in:Ya,Tidak',
            'ya_tidak_3' => 'required|in:Ya,Tidak',
            'alasan_banding' => 'required|string',
            'tanggal_pengajuan_banding' => 'nullable|date', // bisa default sekarang
            'tanda_tangan_asesi' => 'nullable|string',
        ]);

        // Pastikan checkbox yang tidak dicentang menjadi 0
        $validated['tuk_sewaktu'] = $request->has('tuk_sewaktu') ? 1 : 0;
        $validated['tuk_tempatkerja'] = $request->has('tuk_tempatkerja') ? 1 : 0;
        $validated['tuk_mandiri'] = $request->has('tuk_mandiri') ? 1 : 0;

        // Jika tanggal tidak diisi, gunakan tanggal sekarang
        if (empty($validated['tanggal_pengajuan_banding'])) {
            $validated['tanggal_pengajuan_banding'] = now();
        }

        $banding = Banding::create($validated);

        return redirect()->route('terimakasih_banding', ['id' => $banding->id_banding]);
    }

    /**
     * Tampilkan detail Banding (opsional).
     */
    public function show($id)
    {
    // Ambil data banding terakhir
    $banding = Banding::with('dataSertifikasiAsesi.asesi')->findOrFail($id);

    if (!$banding) {
        return redirect()->back()->with('error', 'Belum ada data banding.');
    }

    return view('detail_banding', compact('banding'));
    }


    /**
     * Update dan Delete bisa ditambahkan jika diperlukan.
     */
}