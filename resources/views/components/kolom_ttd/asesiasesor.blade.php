{{-- 
    File: components/ttd-display.blade.php (MODE STATIS DUMMY - SUDAH DIPERBAIKI)
--}}

@php
    // --- DATA DUMMY INTERNAL (DIRUBAH AGAR ADA URL GAMBAR) ---
    // ... (Variabel Dummy tetap sama) ...
    $namaAsesiDummy  = "John Doe (Data Statis)";
    $ttdAsesiDummy   = "https://via.placeholder.com/150x50?text=TTD+Asesi+DUMMY"; // Asumsi ini URL TTD Asesi
    $namaAsesorDummy = "Ajeng Febria H. (Data Statis)";
    $regMetDummy     = "MET-12345 (Statis)";
    $tanggalDummy    = "01-10-2025";
    $ttdAsesorImageDummy = "https://via.placeholder.com/150x50?text=TTD+Asesor+DUMMY"; 

    // ... (Logika $showAsesi, $showAsesor, $cols, $renderDisplay tetap sama) ...
    $showAsesi = $showAsesi ?? true; 
    $showAsesor = $showAsesor ?? true;
    $cols = ($showAsesi && $showAsesor) ? 2 : 1; 

    $renderDisplay = function ($value, $isImage = false) {
        $finalValue = $value ?? '-'; 
        
        if ($isImage && $finalValue !== '-') {
            // LOGIKA GAMBAR:
            return "<img src='{$finalValue}' alt='Tanda Tangan' class='h-12 w-auto object-contain'>";
        } else {
            // LOGIKA TEKS:
            return "<div class='text-sm p-1 w-full'>$finalValue</div>";
        }
    };
@endphp

<div class="mt-8">
    <h3 class="font-semibold text-gray-700 mb-3">Pencatatan dan Validasi (Statis)</h3>

    <div class="grid grid-cols-1 md:grid-cols-{{ $cols }} gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">

        {{-- BAGIAN ASESI --}}
        @if ($showAsesi)
            <div class="space-y-3">
                <h4 class="font-medium text-gray-800">Asesi</h4>
                <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama</span>
                    <span class="font-medium">:</span>
                    {!! $renderDisplay($namaAsesiDummy) !!} 

                    {{-- TANGGAL: Teks biasa, tetap di baris yang sama --}}
                    <span class="font-medium text-gray-700">Tanggal</span>
                    <span class="font-medium">:</span>
                    {!! $renderDisplay($tanggalDummy) !!}                    


                    {{-- PERUBAHAN ASESI: TTD --}}
                    {{-- Judul Label tetap di baris ini --}}
                    <span class="font-medium text-gray-700">Tanda Tangan</span>
                    <span class="font-medium">:</span>
                    
                </div>
            </div>
        @endif

        {{-- BAGIAN ASESOR --}}
        @if ($showAsesor)
            <div class="space-y-3">
                <h4 class="font-medium text-gray-800">Asesor</h4>
                <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama</span>
                    <span class="font-medium">:</span>
                    {!! $renderDisplay($namaAsesorDummy) !!}

                    <span class="font-medium text-gray-700">No. Reg. MET.</span>
                    <span class="font-medium">:</span>
                    {!! $renderDisplay($regMetDummy) !!}

                    {{-- TANGGAL ASESOR: Teks biasa, tetap di baris yang sama --}}
                    <span class="font-medium text-gray-700">Tanggal</span>
                    <span class="font-medium">:</span>
                    {!! $renderDisplay($tanggalDummy) !!} 

                    {{-- PERUBAHAN ASESOR: Tanda Tangan --}}
                    {{-- Judul Label tetap di baris ini --}}
                    <span class="font-medium text-gray-700">Tanda Tangan</span>
                    <span class="font-medium">:</span>
                </div>
            </div>
        @endif
        
    </div>
</div>