@props(['sertifikasi' => null])

@php
    // Ambil data dari $sertifikasi
    $asesi = $sertifikasi->asesi ?? null;
    $asesor = $sertifikasi->asesor ?? null; // Menggunakan accessor getAsesorAttribute di model DataSertifikasiAsesi
    $jadwal = $sertifikasi->jadwal ?? null;

    // Data Asesi
    $namaAsesi = $asesi->nama_lengkap ?? '-';
    $ttdAsesi = $asesi->tanda_tangan ?? null;
    
    // Data Asesor
    $namaAsesor = $asesor->nama_lengkap ?? '-';
    $noRegMet = $asesor->nomor_regis ?? '-';
    $ttdAsesor = $asesor->tanda_tangan ?? null;

    // Tanggal (Ambil dari jadwal tanggal pelaksanaan, atau hari ini jika null)
    $tanggal = $jadwal && $jadwal->tanggal_pelaksanaan 
        ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d-m-Y') 
        : date('d-m-Y');

    // Logic Tampilan
    $showAsesi = $showAsesi ?? true; 
    $showAsesor = $showAsesor ?? true;
    $cols = ($showAsesi && $showAsesor) ? 2 : 1; 
@endphp

<div class="border border-gray-200 shadow-md p-4 rounded-lg bg-white mt-8">
    <h3 class="text-lg font-semibold mb-4">Pencatatan dan Validasi</h3>

    <div class="flex flex-col gap-4">

        {{-- BAGIAN ASESI --}}
        @if ($showAsesi)
            <div>
                <h4 class="font-medium mb-2 text-base text-gray-800">Asesi</h4>
                <div class="grid grid-cols-[auto_auto_1fr] gap-x-2 gap-y-1 text-sm">
                    <div>Nama</div>
                    <div>:</div>
                    <div class="min-h-[1.5em] font-medium text-gray-900">{{ $namaAsesi }}</div>

                    <div>Tanggal</div>
                    <div>:</div>
                    <div class="min-h-[1.5em] text-gray-900">{{ $tanggal }}</div>

                    <div>Tanda Tangan</div>
                    <div>:</div>
                    <div class="h-16">
                        @if($ttdAsesi)
                            <img src="{{ asset('storage/' . $ttdAsesi) }}" alt="Tanda Tangan Asesi" class="h-full object-contain">
                        @else
                            <div class="border-b border-gray-300 h-full w-32 flex items-end text-xs text-gray-400 pb-1">Belum ada TTD</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- SEPARATOR JIKA KEDUANYA DITAMPILKAN --}}
        @if ($showAsesi && $showAsesor)
            <hr class="border-gray-200">
        @endif

        {{-- BAGIAN ASESOR --}}
        @if ($showAsesor)
            <div>
                <h4 class="font-medium mb-2 text-base text-gray-800">Asesor</h4>
                <div class="grid grid-cols-[auto_auto_1fr] gap-x-2 gap-y-1 text-sm">
                    <div>Nama</div>
                    <div>:</div>
                    <div class="min-h-[1.5em] font-medium text-gray-900">{{ $namaAsesor }}</div>

                    <div>No. Reg. MET.</div>
                    <div>:</div>
                    <div class="min-h-[1.5em] text-gray-900">{{ $noRegMet }}</div>

                    <div>Tanggal</div>
                    <div>:</div>
                    <div class="min-h-[1.5em] text-gray-900">{{ $tanggal }}</div>

                    <div>Tanda Tangan</div>
                    <div>:</div>
                    <div class="h-16">
                        @if($ttdAsesor)
                            <img src="{{ asset('storage/' . $ttdAsesor) }}" alt="Tanda Tangan Asesor" class="h-full object-contain">
                        @else
                            <div class="border-b border-gray-300 h-full w-32 flex items-end text-xs text-gray-400 pb-1">Belum ada TTD</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</div>