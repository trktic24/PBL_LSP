<?php

namespace App\Http\Controllers;

use App\Models\FrAk06;
use Illuminate\Http\Request;

class FrAk06Controller extends Controller
{
    public function index()
    {
        // Ganti 'frontend.fr_ak_06' sesuai nama file blade Anda nanti
        return view('frontend.fr_ak_06'); 
    }

    public function store(Request $request)
    {
        // 1. Ambil data kecuali token
        $data = $request->except(['_token', '_method']);

        // 2. Simpan ke database
        FrAk06::create($data);

        // 3. Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Formulir FR.AK.06 berhasil disimpan!');
    }

    /**
     * Menampilkan Template Form FR.AK.06 (Admin Master View)
     */
    public function adminShow($skema_id)
    {
        $skema = \App\Models\Skema::findOrFail($skema_id);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor'
        ])->whereHas('jadwal', function($q) use ($skema_id) {
            $q->where('id_skema', $skema_id);
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
            'targetRoute' => 'admin.ak06.show', // Placeholder
            'buttonLabel' => 'FR.AK.06',
        ]);
    }
}