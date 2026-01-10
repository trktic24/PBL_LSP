<?php

namespace App\Http\Controllers\Asesi\umpan_balik;

use App\Models\PoinAk03;
use Illuminate\Http\Request;
use App\Models\ResponHasilAk03;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Support\Facades\Auth;

class Ak03Controller extends Controller
{
    /**
     * MENAMPILKAN FORM
     */
    public function index($id)
    {
        $user = Auth::user();
        $asesi = $user->asesi;
        
        if (!$asesi) {
            abort(403, 'Akses ditolak. User bukan asesi.');
        }

        // 1. [PINDAH KE ATAS] Ambil Data Sertifikasi Lengkap Dulu
        // Kita butuh data ini entah dia sudah ngisi atau belum (buat sidebar/header)
        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $asesi->id_asesi)
            ->with([
                'jadwal.skema',     // Penting buat header/gambar skema
                'jadwal.asesor',
                'jadwal.jenisTuk',
                'asesi'
            ])
            ->firstOrFail();

        // 2. Cek apakah sudah mengisi
        $sudahIsi = ResponHasilAk03::where('id_data_sertifikasi_asesi', $id)->exists();

        if ($sudahIsi) {
            // Return ke view BERHASIL dengan data LENGKAP
            return view('asesi.tunggu_or_berhasil.berhasil', [
                'id_sertifikasi'     => $id,
                'id_jadwal_redirect' => $sertifikasi->id_jadwal,
                'asesi'              => $asesi,       // <--- INI SOLUSI ERRORNYA
                'sertifikasi'        => $sertifikasi  // <--- INI JUGA WAJIB
            ]); 
        }

        // 3. Jika Belum Mengisi, Tampilkan Form
        $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                    ->where('form_code', 'FR.AK.03')
                                    ->first();

        $komponen = PoinAk03::all();
        if ($template && isset($template->content['selected_points'])) {
            $komponen = $komponen->whereIn('id_poin_ak03', $template->content['selected_points']);
        }

        return view('asesi.umpan_balik.umpan_balik', [
            'komponen'    => $komponen,
            'sertifikasi' => $sertifikasi,
            'asesi'       => $asesi,
            'template'    => $template ? $template->content : null
        ]);
    }

    /**
     * MENYIMPAN JAWABAN (Store tetap sama seperti yang terakhir bener)
     */
    public function store(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'jawaban'           => 'required|array',
            'catatan_tambahan'  => 'nullable|string',
        ]);

        $user = Auth::user();
        $asesi = $user->asesi;

        // Ambil data sertifikasi untuk ID Jadwal
        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $asesi->id_asesi)
            ->first();

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data sertifikasi tidak ditemukan.');
        }

        $idJadwalUntukRedirect = $sertifikasi->id_jadwal;

        // Cek Double Submit
        if (ResponHasilAk03::where('id_data_sertifikasi_asesi', $id)->exists()) {
            return redirect()->route('asesi.tracker', ['id' => $idJadwalUntukRedirect])
                ->with('warning', 'Anda sudah mengisi umpan balik sebelumnya.');
        }

        // 2. Simpan Jawaban
        foreach ($request->jawaban as $id_poin => $data) {
            ResponHasilAk03::create([ 
                'id_data_sertifikasi_asesi' => $id,
                'id_poin_ak03'              => $id_poin,
                'hasil'   => $data['hasil'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);
        }

        // 3. Update Status Sertifikasi
        $sertifikasi->update([
            'catatan_asesi_AK03' => $request->catatan_tambahan,
            // Pastikan constant ini ada, atau ganti string manual 'umpan_balik_selesai'
            'status_sertifikasi' => DataSertifikasiAsesi::STATUS_UMPAN_BALIK_SELESAI 
        ]);

        // 4. Redirect
        return redirect()->route('asesi.tracker', ['id' => $idJadwalUntukRedirect]) 
            ->with('success', 'Umpan balik berhasil dikirim! Terima kasih.');
    }

    /**
     * [MASTER] Menampilkan editor template (Umpan Balik) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.AK.03')
                                    ->first();
        
        $allPoints = PoinAk03::all();
        $content = $template ? $template->content : [
            'selected_points' => $allPoints->pluck('id_poin_ak03')->toArray(),
            'catatan_tambahan' => ''
        ];

        return view('Admin.master.skema.template.ak03', [
            'skema' => $skema,
            'allPoints' => $allPoints,
            'content' => $content
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'content' => 'required|array',
            'content.selected_points' => 'required|array',
            'content.catatan_tambahan' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.AK.03'],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-03 berhasil diperbarui.');
    }
}