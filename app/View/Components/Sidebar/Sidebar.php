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
        // 0. CEK ROUTE PARAMETER (Prioritas Utama)
        // Jika data tidak dipassing secara eksplisit, coba ambil dari URL
        if (!$skema && !$asesi && !$jadwal) {
            $idSertifikasi = request()->route('id_sertifikasi') ?? request()->route('id');
            
            if ($idSertifikasi) {
                // Coba cari DataSertifikasiAsesi
                $sertifikasi = \App\Models\DataSertifikasiAsesi::with([
                    'asesi.user', 
                    'jadwal.skema'
                ])->find($idSertifikasi);

                if ($sertifikasi) {
                    $this->asesi = $sertifikasi->asesi;
                    $this->jadwal = $sertifikasi->jadwal;
                    if ($this->jadwal) {
                        $this->skema = $this->jadwal->skema;
                    }
                }
            }
        }

        // 1. ASESI (Fallback)
        if (!$this->asesi) {
            if ($asesi) {
                $this->asesi = $asesi;
            } else {
                // Jika tidak dipassing, coba ambil dari Auth User
                $user = Auth::user();
                if ($user && $user->role && $user->role->nama_role === 'asesi') { 
                     $this->asesi = Asesi::where('id_user', $user->id_user)->first();
                } 
                elseif ($user && $user->role && $user->role->nama_role === 'asesor') {
                    // Opsional: Ambil asesi pertama untuk preview jika masih null
                    // $this->asesi = Asesi::first(); 
                }
            }
        }

        // 2. SKEMA (Fallback)
        if (!$this->skema) {
            if ($skema) {
                $this->skema = $skema;
            } else {
                // Coba ambil dari Asesi yang sudah didapat
                if ($this->asesi) {
                    // Coba cari sertifikasi terakhir asesi ini untuk dapat skema
                    $lastSertifikasi = \App\Models\DataSertifikasiAsesi::where('id_asesi', $this->asesi->id_asesi)
                                        ->with('jadwal.skema')
                                        ->latest()
                                        ->first();
                    
                    if ($lastSertifikasi && $lastSertifikasi->jadwal && $lastSertifikasi->jadwal->skema) {
                        $this->skema = $lastSertifikasi->jadwal->skema;
                        // Jika jadwal juga belum ada, ambil dari sini
                        if (!$this->jadwal) {
                            $this->jadwal = $lastSertifikasi->jadwal;
                        }
                    } else {
                        $this->skema = Skema::first(); 
                    }
                } else {
                     $this->skema = Skema::first();
                }
            }
        }

        // 3. JADWAL (Fallback)
        if (!$this->jadwal) {
            if ($jadwal) {
                $this->jadwal = $jadwal;
            } else {
                // Ambil jadwal pertama sebagai default terakhir
                $this->jadwal = Jadwal::first();
            }
        }
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
