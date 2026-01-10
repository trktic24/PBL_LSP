use App\Models\FrIa04a;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;

class FrIa04aController extends Controller
{
    // 2. TAMBAHKAN INI DI DALAM CLASS
    use AuthorizesRequests; 

    // ... function index, store, dll tetap sama ...

    public function store(Request $request)
    {
        // Sekarang kode ini tidak akan error lagi
        $this->authorize('create', FrIa04a::class);
        
        // ... codingan kamu selanjutnya ...
    }

    public function update(Request $request, FrIa04a $frIa04a)
    {
        // Kode ini juga akan aman
        $this->authorize('update', $frIa04a);

        // ... codingan kamu selanjutnya ...
    }
    
    // ...

    // ... method store, update, dll ...

    /**
     * CETAK PDF FR.IA.04
     */
    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = \App\Models\DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi' // Untuk header unit
        ])->findOrFail($id_data_sertifikasi_asesi);

        // 2. Ambil Poin (Tugas/Kegiatan)
        $points = \App\Models\PoinIA04A::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();

        // 3. Ambil Respon (Jawaban/Validasi), keyBy ID Poin biar gampang dipanggil di View
        $responses = \App\Models\ResponIA04A::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get()
            ->keyBy('id_poin_ia04A');

        // 4. Ambil Unit Kompetensi (Untuk Header)
        $units = $sertifikasi->jadwal->skema->kelompokPekerjaan->flatMap->unitKompetensi;

        // 5. Render PDF
        $pdf = Pdf::loadView('pdf.ia_04', [
            'sertifikasi' => $sertifikasi,
            'points' => $points,
            'responses' => $responses,
            'units' => $units,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $namaAsesi = preg_replace('/[^A-Za-z0-9\-]/', '_', $sertifikasi->asesi->nama_lengkap);
        return $pdf->stream('FR_IA_04_' . $namaAsesi . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Verifikasi Portofolio) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.IA.04')
                                    ->first();
        
        // Default values if no template exists
        $points = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia04', [
            'skema' => $skema,
            'points' => $points
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'points' => 'required|array',
            'points.*.nama' => 'required|string',
            'points.*.kriteria' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.IA.04'],
            ['content' => $request->points]
        );

        return redirect()->back()->with('success', 'Templat IA-04 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.IA.04 (Admin Master View) - DEPRECATED for management
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'responApl2Ia01',
            'responBuktiAk01',
            'lembarJawabIa05',
            'komentarAk05'
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

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'fria04a.show',
            'buttonLabel' => 'FR.IA.04',
        ]);
    }
}
   