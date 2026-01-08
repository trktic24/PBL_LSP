<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class Ak01Controller extends Controller
{
    // 1. MENAMPILKAN FORM AK-01
    public function create($id_sertifikasi)
    {
        // Ambil data sertifikasi berdasarkan ID dan pastikan milik user yang login
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.asesor', 'asesi'])
            ->where('id_asesi', Auth::user()->asesi->id_asesi)
            ->findOrFail($id_sertifikasi);

        $asesi = $sertifikasi->asesi;

        // Kirim variabel $sertifikasi dan $asesi ke View
        return view('frontend.FR_AK_01', compact('sertifikasi', 'asesi'));
    }

    // 2. MENYIMPAN PERSETUJUAN (STORE)
    public function store(Request $request, $id_sertifikasi)
    {
        // Cari data sertifikasi
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);

        // Disini logikanya: Update status atau simpan ke tabel khusus AK01
        // Contoh sederhana: Kita anggap AK-01 selesai jika sudah diklik
        // Jika Anda punya tabel 'respon_ak01', tambahkan kode simpannya di sini.
        
        // Contoh update status (opsional, sesuaikan dengan flow Anda)
        // $sertifikasi->status_sertifikasi = 'asesmen_mandiri_selesai'; 
        // $sertifikasi->save();

        return redirect()->route('jadwal.index')->with('success', 'Persetujuan Asesmen (AK-01) berhasil disetujui.');
    }

    /**
     * Menampilkan Template Form FR.AK.01 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new \App\Models\Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.profile_asesor.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'ak01.create',
            'buttonLabel' => 'FR.AK.01',
        ]);
    }
}