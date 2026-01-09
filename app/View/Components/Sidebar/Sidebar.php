<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Skema;
use App\Models\Asesi;
use App\Models\Jadwal;
use App\Models\Asesor;

class Sidebar extends Component
{
    public $skema;
    public $asesi;
    public $jadwal;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($skema = null, $asesi = null, $jadwal = null)
    {
        // 1. SET PROPERTI DARI ARGUMEN (Prioritas Utama)
        $this->skema = $skema;
        $this->asesi = $asesi;
        $this->jadwal = $jadwal;

        // 2. JIKA DATA BELUM LENGKAP, COBA AMBIL DARI ROUTE PARAMETER
        if (!$this->asesi || !$this->jadwal) {
            $idSertifikasi = request()->route('id_sertifikasi') ?? request()->route('id');
            
            if ($idSertifikasi) {
                // Coba cari DataSertifikasiAsesi
                $sertifikasi = \App\Models\DataSertifikasiAsesi::with([
                    'asesi.user', 
                    'jadwal.skema'
                ])->find($idSertifikasi);

                if ($sertifikasi) {
                    // Hanya set jika belum ada
                    if (!$this->asesi) $this->asesi = $sertifikasi->asesi;
                    if (!$this->jadwal) $this->jadwal = $sertifikasi->jadwal;
                    if (!$this->skema && $this->jadwal) $this->skema = $this->jadwal->skema;
                }
            }
        }

        // 3. ASESI (Fallback ke Auth User)
        if (!$this->asesi) {
            $user = Auth::user();
            if ($user && $user->role && $user->role->nama_role === 'asesi') { 
                 $this->asesi = Asesi::where('id_user', $user->id_user)->first();
            } 
        }

        // 4. JIKA SKEMA MASIH KOSONG, COBA AMBIL DARI ASESI
        if (!$this->skema && $this->asesi) {
            // Coba cari sertifikasi terakhir asesi ini
            $lastSertifikasi = \App\Models\DataSertifikasiAsesi::where('id_asesi', $this->asesi->id_asesi)
                                ->with('jadwal.skema')
                                ->latest()
                                ->first();
            
            if ($lastSertifikasi && $lastSertifikasi->jadwal && $lastSertifikasi->jadwal->skema) {
                $this->skema = $lastSertifikasi->jadwal->skema;
                if (!$this->jadwal) $this->jadwal = $lastSertifikasi->jadwal;
            } else {
                // Fallback terakhir: Skema Pertama
                $this->skema = Skema::first(); 
            }
        }
        
        // 5. JIKA MASIH KOSONG KARENA DB BARU/TESTING
        if (!$this->skema) $this->skema = Skema::first();
        if (!$this->jadwal) $this->jadwal = Jadwal::first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar.sidebar');
    }
}
