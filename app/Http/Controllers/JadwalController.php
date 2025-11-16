<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Skema;
use App\Models\MasterTuk;
use App\Models\Asesor;
use App\Models\JenisTuk;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua jadwal dengan relasi skema dan masterTuk
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'asesi'])
                        ->orderBy('tanggal_pelaksanaan', 'desc')
                        ->get();
        
        // Auto-update status berdasarkan jumlah peserta & tanggal
        foreach($jadwal as $item) {
            $this->updateStatusJadwal($item);
        }
        
        return view('landing_page.jadwal', compact('jadwal'));
    }
    
    /**
     * Update status jadwal otomatis berdasarkan kondisi
     */
    private function updateStatusJadwal($jadwal)
    {
        $jumlahPeserta = $jadwal->asesi->count();
        $sekarang = Carbon::now();
        
        // Jika sudah lewat tanggal pelaksanaan -> SELESAI
        if($jadwal->tanggal_pelaksanaan->isPast()) {
            if($jadwal->Status_jadwal != 'Selesai') {
                $jadwal->update(['Status_jadwal' => 'Selesai']);
            }
            return;
        }
        
        // Jika peserta sudah 25 orang (kuota maksimal) -> FULL
        if($jumlahPeserta >= $jadwal->kuota_maksimal) {
            if($jadwal->Status_jadwal != 'Full') {
                $jadwal->update(['Status_jadwal' => 'Full']);
            }
            return;
        }
        
        // Jika sudah lewat tanggal penutupan pendaftaran tapi peserta < 25 -> FULL
        if($jadwal->tanggal_selesai->isPast() && $jumlahPeserta < $jadwal->kuota_maksimal) {
            if($jadwal->Status_jadwal != 'Full') {
                $jadwal->update(['Status_jadwal' => 'Full']);
            }
            return;
        }
        
        // Jika masih dalam periode pendaftaran dan peserta < 25 -> DIBUKA
        if($jadwal->tanggal_mulai->isPast() && $jadwal->tanggal_selesai->isFuture() && $jumlahPeserta < $jadwal->kuota_maksimal) {
            if($jadwal->Status_jadwal != 'Dibuka') {
                $jadwal->update(['Status_jadwal' => 'Dibuka']);
            }
            return;
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data untuk dropdown
        $skema = Skema::all();
        $tuk = MasterTuk::all();
        $asesor = Asesor::all();
        $jenisTuk = JenisTuk::all();
        
        return view('jadwal.create', compact('skema', 'tuk', 'asesor', 'jenisTuk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis_tuk' => 'required',
            'id_tuk' => 'required',
            'id_skema' => 'required',
            'id_asesor' => 'required',
            'kuota_maksimal' => 'required|integer|min:1',
            'kuota_minimal' => 'required|integer|min:1',
            'sesi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_pelaksanaan' => 'required|date',
            'Status_jadwal' => 'required|in:Dibuka,Full,Selesai,Akan datang',
        ]);

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * REDIRECT LOGIC: 
     * - Status "Dibuka" -> ke form pendaftaran peserta
     * - Status "Full" atau "Selesai" -> tampilkan alert tidak bisa dibuka
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['jenisTuk', 'masterTuk', 'skema', 'asesor'])
                        ->findOrFail($id);
        
        // Update status dulu sebelum cek
        $this->updateStatusJadwal($jadwal);
        
        // Jika status DIBUKA -> redirect ke halaman pendaftaran
        if($jadwal->Status_jadwal == 'Dibuka') {
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
        
        // Cek status (logika ini sudah ada di file Anda, ini bagus)
        $this->updateStatusJadwal($jadwal);
        
        // Jika bukan status DIBUKA (atau Terjadwal), redirect kembali
        // Sesuaikan 'Dibuka' dengan status Anda, misal 'Terjadwal'
        if(!in_array($jadwal->Status_jadwal, ['Dibuka', 'Terjadwal'])) {
            return redirect()->route('jadwal.index')
                            ->with('error', 'Pendaftaran untuk jadwal ini sudah ditutup atau belum dibuka.');
        }
        
        $jumlahPeserta = $jadwal->asesi->count(); // Anda menggunakan relasi 'asesi', bukan 'peserta'
        $sisaKuota = $jadwal->kuota_maksimal - $jumlahPeserta;
        
        // 🟦 INI ADALAH PERUBAHAN UTAMA 🟦
        // Mengarahkan ke file view yang Anda inginkan
        return view('landing_page.detail.detail_jadwal', compact('jadwal', 'jumlahPeserta', 'sisaKuota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        // Ambil data untuk dropdown
        $skema = Skema::all();
        $tuk = MasterTuk::all();
        $asesor = Asesor::all();
        $jenisTuk = JenisTuk::all();
        
        return view('jadwal.edit', compact('jadwal', 'skema', 'tuk', 'asesor', 'jenisTuk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $validated = $request->validate([
            'id_jenis_tuk' => 'required',
            'id_tuk' => 'required',
            'id_skema' => 'required',
            'id_asesor' => 'required',
            'kuota_maksimal' => 'required|integer|min:1',
            'kuota_minimal' => 'required|integer|min:1',
            'sesi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_pelaksanaan' => 'required|date',
            'Status_jadwal' => 'required|in:Dibuka,Full,Selesai,Akan datang',
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil dihapus!');
    }
}