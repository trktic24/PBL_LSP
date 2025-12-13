<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Skema;
use App\Models\MasterTUK;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Display a listing of jadwal with filters, search and pagination.
     */
    public function index(Request $request)
    {
        $query = Jadwal::with(['skema', 'masterTuk', 'asesi']);

        // Global search: cari di nama skema, nama lokasi TUK, atau Status_jadwal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('skema', function ($q2) use ($search) {
                    $q2->where('nama_skema', 'like', "%{$search}%");
                })
                ->orWhereHas('masterTuk', function ($q3) use ($search) {
                    $q3->where('nama_lokasi', 'like', "%{$search}%");
                })
                ->orWhere('Status_jadwal', 'like', "%{$search}%");
            });
        }

        // Filter skema (by nama_skema strings passed)
        if ($request->filled('skema')) {
            $skemaNames = (array) $request->skema;
            $skemaIds = Skema::whereIn('nama_skema', $skemaNames)->pluck('id_skema')->toArray();
            if (!empty($skemaIds)) {
                $query->whereIn('id_skema', $skemaIds);
            }
        }

        // Filter TUK (by nama_lokasi strings passed)
        if ($request->filled('tuk')) {
            $tukNames = (array) $request->tuk;
            $tukIds = MasterTUK::whereIn('nama_lokasi', $tukNames)->pluck('id_tuk')->toArray();
            if (!empty($tukIds)) {
                $query->whereIn('id_tuk', $tukIds);
            }
        }

        // Filter status
        if ($request->filled('status')) {
            $statuses = (array) $request->status;
            $query->whereIn('Status_jadwal', $statuses);
        }

        // Filter tanggal pelaksanaan range
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_pelaksanaan', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_pelaksanaan', '<=', $request->tgl_selesai);
        }

        // Order & paginate (20 per halaman)
        $jadwal = $query->orderBy('tanggal_pelaksanaan', 'asc')
                       ->paginate(20)
                       ->appends($request->except('page'));

        // Update status otomatis
        foreach ($jadwal as $item) {
            $this->updateStatusJadwal($item);
        }

        /**
         * PERBAIKAN DI SINI
         * ------------------
         * Ubah dari pluck() → all() supaya hasilnya bukan string,
         * tetapi objek model sehingga di Blade bisa pakai $sk->nama_skema
         */
        $listSkema = Skema::selectRaw('MIN(id_skema) as id_skema, TRIM(LOWER(nama_skema)) as nama_skema')
            ->groupBy('nama_skema')
            ->orderBy('nama_skema')
            ->get();


        $listTuk = MasterTUK::selectRaw('MIN(id_tuk) as id_tuk, TRIM(LOWER(nama_lokasi)) as nama_lokasi')
            ->groupBy('nama_lokasi')
            ->orderBy('nama_lokasi')
            ->get();


        return view('landing_page.jadwal', compact('jadwal', 'listSkema', 'listTuk'));
    }

    /**
     * Update status jadwal otomatis berdasarkan kondisi
     */
    private function updateStatusJadwal(Jadwal $jadwal)
    {
        $tanggalPelaksanaan = $jadwal->tanggal_pelaksanaan ? Carbon::parse($jadwal->tanggal_pelaksanaan) : null;
        $tanggalMulai = $jadwal->tanggal_mulai ? Carbon::parse($jadwal->tanggal_mulai) : null;
        $tanggalSelesai = $jadwal->tanggal_selesai ? Carbon::parse($jadwal->tanggal_selesai) : null;
        $jumlahPeserta = $jadwal->relationLoaded('asesi') ? $jadwal->asesi->count() : $jadwal->asesi()->count();

        if ($tanggalPelaksanaan && $tanggalPelaksanaan->isPast()) {
            if ($jadwal->Status_jadwal !== 'Selesai') {
                $jadwal->update(['Status_jadwal' => 'Selesai']);
            }
            return;
        }

        if (!is_null($jadwal->kuota_maksimal) && $jumlahPeserta >= $jadwal->kuota_maksimal) {
            if ($jadwal->Status_jadwal !== 'Full') {
                $jadwal->update(['Status_jadwal' => 'Full']);
            }
            return;
        }

        if ($tanggalSelesai && $tanggalSelesai->isPast() && $jumlahPeserta < ($jadwal->kuota_maksimal ?? PHP_INT_MAX)) {
            if ($jadwal->Status_jadwal !== 'Full') {
                $jadwal->update(['Status_jadwal' => 'Full']);
            }
            return;
        }

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai->isPast() && $tanggalSelesai->isFuture() && $jumlahPeserta < ($jadwal->kuota_maksimal ?? PHP_INT_MAX)) {
            if ($jadwal->Status_jadwal !== 'Terjadwal') {
                $jadwal->update(['Status_jadwal' => 'Terjadwal']);
            }
            return;
        }
    }

    /**
     * Show single jadwal (detail/admin view)
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['jenisTuk', 'masterTuk', 'skema', 'asesor', 'asesi'])
                        ->findOrFail($id);

        $this->updateStatusJadwal($jadwal);

        if ($jadwal->Status_jadwal === 'Terjadwal') {
            return redirect()->route('jadwal.detail', $id);
        }

        return view('jadwal.show', compact('jadwal'));
    }

    /**
     * Halaman detail jadwal untuk pendaftaran peserta
     */
    public function detail($id)
    {
        $jadwal = Jadwal::with(['jenisTuk', 'masterTuk', 'skema', 'asesor', 'asesi'])
                        ->findOrFail($id);

        $this->updateStatusJadwal($jadwal);

        if (!in_array($jadwal->Status_jadwal, ['Terjadwal'])) {
            return redirect()->route('jadwal.index')
                             ->with('error', 'Pendaftaran untuk jadwal ini sudah ditutup atau belum dibuka.');
        }

        $jumlahPeserta = $jadwal->asesi->count();
        $sisaKuota = $jadwal->kuota_maksimal - $jumlahPeserta;

        return view('landing_page.detail.detail_jadwal', compact('jadwal', 'jumlahPeserta', 'sisaKuota'));
    }
}