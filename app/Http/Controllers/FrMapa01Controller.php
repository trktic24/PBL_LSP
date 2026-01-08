<?php

namespace App\Http\Controllers;

use App\Models\FrMapa01;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FrMapa01Controller extends Controller
{
    // public function index()
    // {
    //     return view('frontend.FR_MAPA_01');
    // }

    public function index($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);
        
        // Return View Bagian 1
        return view('frontend.FR_MAPA_01', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal,            
        ]);
    }    

    public function store(Request $request)
    {
        // 1. Ambil semua data input
        // Kita buang _token dan _method karena tidak perlu disimpan di DB
        $data = $request->except(['_token', '_method']);

        // 2. Simpan ke database
        // Pastikan Model sudah di-set $guarded = ['id'] atau $fillable lengkap
        FrMapa01::create($data);

        // 3. Kembali ke halaman form dengan pesan sukses
        return redirect()->route('mapa01.index', $data['id_data_sertifikasi_asesi'])->with('success', 'Data FR.MAPA.01 berhasil disimpan!');
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema'
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Data Form MAPA 01
        $mapa01 = FrMapa01::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // 3. Render PDF
        // Pastikan nama view sesuai dengan nama file yang kamu buat (misal: 'pdf.mapa01')
        $pdf = Pdf::loadView('pdf.mapa01', [
            'sertifikasi' => $sertifikasi,
            'mapa01'      => $mapa01
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_MAPA_01_' . $sertifikasi->id_data_sertifikasi_asesi . '.pdf');
    }

    /**
     * Menampilkan Template Form MAPA-01 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        // 1. Filter Asesi by Skema & Pagination
        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        // Simple Search
        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        // 2. Dummy Objects for View Compatibility
        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0; 
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        
        // Mock Relations
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
            'targetRoute' => 'mapa01.index',
            'buttonLabel' => 'FR.MAPA.01'
        ]);
    }
}