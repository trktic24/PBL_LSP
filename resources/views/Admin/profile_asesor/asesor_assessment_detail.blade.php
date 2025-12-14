<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Asesmen Asesi | LSP Polines</title> 

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="text-gray-800">

  {{-- PERBAIKAN: Menggunakan Navbar Admin --}}
  <x-navbar.navbar_admin />
  
  {{-- Layout Utama Flex --}}
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- 1. Panggil Component Sidebar Asesor --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    {{-- Konten Utama --}}
    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-12 border border-gray-100 min-h-full">
        
        {{-- Header Page --}}
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ route('admin.asesor.daftar_asesi', ['id_asesor' => $asesor->id_asesor, 'id_jadwal' => $dataSertifikasi->jadwal->id_jadwal]) }}" class="absolute left-0 top-1 text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Detail Asesmen</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola penilaian dan verifikasi untuk asesi ini.</p>
            </div>
        </div>

        @php
            // =========================================================================
            // 1. SETUP LOGIKA UTAMA (Diadaptasi dari Tracker Asesi)
            // =========================================================================

            $level = $dataSertifikasi->level_status;

            // Logika bypass level jika status teks sudah disetujui
            if ($dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui') {
                if ($level < 40) {
                    $level = 40;
                }
            }

            // Cek Final (Sudah ada keputusan AK.02)
            $isFinalized = ($level >= 100);

            // =========================================================================
            // 2. HELPER FUNCTION (Logic Only)
            // =========================================================================

            // Helper State Tombol Verifikasi
            function btnState($currentLevel, $requiredLevel, $isFinalized) {
                // Untuk Asesor, meskipun finalized, biasanya masih bisa lihat/edit (opsional). 
                // Tapi kita samakan logikanya: jika finalized, disable (kecuali reset).
                if ($isFinalized) return 'bg-gray-100 text-gray-400 pointer-events-none cursor-not-allowed';
                
                if ($currentLevel >= $requiredLevel) {
                    return 'bg-blue-100 text-blue-600 hover:bg-blue-200 cursor-pointer';
                } else {
                    return 'bg-gray-100 text-gray-400 pointer-events-none cursor-not-allowed';
                }
            }

            // Helper State Tombol PDF
            function pdfState($currentLevel, $requiredLevel) {
                if ($currentLevel >= $requiredLevel) {
                    return 'bg-gray-100 text-gray-700 hover:bg-gray-200 cursor-pointer';
                } else {
                    return 'bg-gray-100 text-gray-300 pointer-events-none cursor-not-allowed';
                }
            }

            // Helper Warna Icon Timeline
            function iconColor($status) {
                if ($status == 'DONE') return 'bg-green-500 text-white';
                if ($status == 'ACTIVE') return 'bg-yellow-400 text-white';
                return 'bg-gray-200 text-gray-400';
            }

            // =========================================================================
            // 3. CEK STATUS PER ITEM (UNTUK AK.02 LOCK)
            // =========================================================================
            
            // Cek ketersediaan relasi (menggunakan eager loading dari controller)
            
            // IA.05
            $ia05Done = $dataSertifikasi->lembarJawabIa05()->whereNotNull('pencapaian_ia05')->exists();
            $stIa05 = $ia05Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

            // IA.10
            $ia10Done = $dataSertifikasi->ia10()->exists(); // Pastikan relasi 'ia10' ada di model
            $stIa10 = $ia10Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

            // IA.02
            $ia02Done = $dataSertifikasi->ia02()->exists();
            $stIa02 = $ia02Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

            // IA.06 (Asumsi relasi ia06Answers atau ia06)
            $ia06Done = $dataSertifikasi->ia06Answers()->whereNotNull('pencapaian')->exists(); 
            $stIa06 = $ia06Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

            // IA.07
            $ia07Done = $dataSertifikasi->ia07()->whereNotNull('pencapaian')->exists();
            $stIa07 = $ia07Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

            $allIADone = ($ia05Done && $ia10Done && $ia02Done && $ia06Done && $ia07Done);

            // Helper Checkmark (modified to fit inside ball)
            if (!function_exists('renderCheckmark')) {
                function renderCheckmark() {
                    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>';
                }
            }
        @endphp

        <div class="max-w-6xl mx-auto">

            {{-- SECTION 1: PRA-ASESMEN --}}
            <section id="pra-asesmen">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Pra Asesmen</h2>
                    
                    <ol class="relative z-10 border-l-2 border-gray-200 ml-4 space-y-8">
                        
                        {{-- ITEM 1: FR.APL.01 --}}
                        <li class="mb-10 ml-8 relative">
                            @php $isApl01Done = $dataSertifikasi->rekomendasi_apl01 == 'diterima'; @endphp
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $isApl01Done ? '!border-green-500 bg-green-500' : 'bg-yellow-400' }}">
                                @if($isApl01Done) 
                                    {!! renderCheckmark() !!}
                                @else 
                                    <span class="font-bold text-[10px] text-white">01</span> 
                                @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                                FR.APL.01 - Permohonan Sertifikasi
                                @if($isApl01Done)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Diterima</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Menunggu</span>
                                @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                   class="{{ btnState(100, 0, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-check-double"></i> Verifikasi
                                </a>
                                <a href="{{ route('apl01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" 
                                   class="{{ pdfState(100, 0) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- ITEM 2: FR.MAPA.01 --}}
                        <li class="mb-10 ml-8 relative">
                            @php $isMapa01Done = $level >= 20; @endphp
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $isMapa01Done ? '!border-green-500 bg-green-500' : 'bg-yellow-400' }}">
                                @if($isMapa01Done) {!! renderCheckmark() !!} @else <span class="font-bold text-[10px] text-white">M1</span> @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                                FR.MAPA.01 - Merencanakan Aktivitas
                                @if($isMapa01Done)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Diterima</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Menunggu</span>
                                @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                   class="{{ btnState(100, 0, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-check-double"></i> Verifikasi
                                </a>
                                <a href="{{ route('mapa01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState(100, 0) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- ITEM 3: FR.MAPA.02 --}}
                        <li class="mb-10 ml-8 relative">
                            @php $isMapa02Done = $level >= 20; @endphp
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $isMapa02Done ? '!border-green-500 bg-green-500' : ($level >= 10 ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($isMapa02Done) 
                                    {!! renderCheckmark() !!} 
                                @else 
                                    <span class="font-bold text-[10px] {{ $level >= 10 ? 'text-white' : 'text-gray-500' }}">M2</span> 
                                @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $level < 10 ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.MAPA.02 - Peta Instrumen
                                @if($isMapa02Done)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Diterima</span>
                                @elseif($level >= 10)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Menunggu</span>
                                @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                   class="{{ btnState($level, 20, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-check-double"></i> Verifikasi
                                </a>
                                <a href="{{ route('mapa02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 20) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- ITEM 4: FR.APL.02 --}}
                        <li class="mb-10 ml-8 relative">
                            @php $isApl02Done = $level >= 30; @endphp
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $isApl02Done ? '!border-green-500 bg-green-500' : ($level >= 20 ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($isApl02Done) 
                                    {!! renderCheckmark() !!} 
                                @else 
                                    <span class="font-bold text-[10px] {{ $level >= 20 ? 'text-white' : 'text-gray-500' }}">02</span> 
                                @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $level < 20 ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.APL.02 - Asesmen Mandiri
                                @if($dataSertifikasi->rekomendasi_apl02 == 'diterima')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Diterima</span>
                                @elseif($level >= 20)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Menunggu</span>
                                @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                   class="{{ btnState($level, 20, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-check-double"></i> Verifikasi
                                </a>
                                <a href="{{ route('apl02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 20) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- ITEM 5: FR.AK.01 --}}
                        <li class="mb-10 ml-8 relative">
                            @php $ak01Done = ($level >= 40 || $dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui'); @endphp
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $ak01Done ? '!border-green-500 bg-green-500' : ($level >= 30 ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($ak01Done) 
                                    {!! renderCheckmark() !!} 
                                @else 
                                    <span class="font-bold text-[10px] {{ $level >= 30 ? 'text-white' : 'text-gray-500' }}">AK1</span> 
                                @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $level < 30 ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.AK.01 - Persetujuan & Kerahasiaan
                                @if($ak01Done)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Diterima</span>
                                @elseif($level >= 30)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Menunggu</span>
                                @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                    class="bg-blue-100 text-blue-600 hover:bg-blue-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-eye"></i> Tinjau
                                </a>
                                <a href="{{ route('ak01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 30) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                    </ol>
                </div>
            </section>

            {{-- SECTION 2: ASESMEN REAL --}}
            <section id="asesmen" class="mt-8 transition-all duration-300 {{ $level < 40 ? 'opacity-60' : '' }}">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">
                        Pelaksanaan Asesmen
                        @if($level < 40) <span class="text-sm font-normal text-red-500 ml-2 inline-block">(Terkunci: Selesaikan Pra Asesmen)</span> @endif
                    </h2>
                    
                    <ol class="relative z-10 border-l-2 border-gray-200 ml-4 space-y-8">

                        {{-- IA.05 --}}
                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $stIa05 == 'DONE' ? '!border-green-500 bg-green-500' : ($stIa05 == 'ACTIVE' ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($stIa05 == 'DONE') 
                                    {!! renderCheckmark() !!} 
                                @else 
                                    <span class="font-bold text-[10px] {{ $stIa05 == 'ACTIVE' ? 'text-white' : 'text-gray-500' }}">IA.05</span> 
                                @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $stIa05 == 'LOCKED' ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.IA.05 - Pertanyaan Tertulis
                                @if($stIa05 == 'DONE')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span>
                                @elseif($stIa05 == 'ACTIVE')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Belum Dinilai</span>
                                @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('FR_IA_05_C', $asesi->id_asesi) }}"
                                   class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-pen-nib"></i> Nilai
                                </a>
                                <a href="{{ route('ia05.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- IA.10 --}}
                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $stIa10 == 'DONE' ? '!border-green-500 bg-green-500' : ($stIa10 == 'ACTIVE' ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($stIa10 == 'DONE') {!! renderCheckmark() !!} @else <span class="font-bold text-[10px] {{ $stIa10 == 'ACTIVE' ? 'text-white' : 'text-gray-500' }}">IA.10</span> @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $stIa10 == 'LOCKED' ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.IA.10 - Verifikasi Pihak Ketiga
                                @if($stIa10 == 'DONE') <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span> @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('fr-ia-10.create', $asesi->id_asesi) }}"
                                   class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-pen-nib"></i> Verifikasi
                                </a>
                                <a href="{{ route('ia10.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- IA.02 --}}
                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $stIa02 == 'DONE' ? '!border-green-500 bg-green-500' : ($stIa02 == 'ACTIVE' ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($stIa02 == 'DONE') {!! renderCheckmark() !!} @else <span class="font-bold text-[10px] {{ $stIa02 == 'ACTIVE' ? 'text-white' : 'text-gray-500' }}">IA.02</span> @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $stIa02 == 'LOCKED' ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.IA.02 - Tugas Praktik Demonstrasi
                                @if($stIa02 == 'DONE') <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span> @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('fr-ia-02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                   class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-pen-nib"></i> Verifikasi
                                </a>
                                <a href="{{ route('ia02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- IA.06 --}}
                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $stIa06 == 'DONE' ? '!border-green-500 bg-green-500' : ($stIa06 == 'ACTIVE' ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($stIa06 == 'DONE') {!! renderCheckmark() !!} @else <span class="font-bold text-[10px] {{ $stIa06 == 'ACTIVE' ? 'text-white' : 'text-gray-500' }}">IA.06</span> @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $stIa06 == 'LOCKED' ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.IA.06 - Pertanyaan Lisan
                                @if($stIa06 == 'DONE') <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span> @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('asesor.ia06.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                   class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-pen-nib"></i> Verifikasi
                                </a>
                                <a href="{{ route('ia06.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- IA.07 --}}
                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $stIa07 == 'DONE' ? '!border-green-500 bg-green-500' : ($stIa07 == 'ACTIVE' ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($stIa07 == 'DONE') {!! renderCheckmark() !!} @else <span class="font-bold text-[10px] {{ $stIa07 == 'ACTIVE' ? 'text-white' : 'text-gray-500' }}">IA.07</span> @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ $stIa07 == 'LOCKED' ? 'text-gray-400' : 'text-gray-900' }}">
                                FR.IA.07 - Daftar Pertanyaan Lisan
                                @if($stIa07 == 'DONE') <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span> @endif
                            </h3>
                            
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('FR_IA_07') }}"
                                   class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-pen-nib"></i> Verifikasi
                                </a>
                                <a href="{{ route('ia07.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </li>

                        {{-- AK.02 (KEPUTUSAN) --}}
                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $isFinalized ? '!border-green-500 bg-green-500' : ($allIADone ? 'bg-yellow-400' : 'bg-gray-200') }}">
                                @if($isFinalized) 
                                    {!! renderCheckmark() !!} 
                                @else 
                                    <span class="font-bold text-[10px] {{ $allIADone ? 'text-white' : 'text-gray-500' }}">AK.02</span> 
                                @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold {{ !$allIADone ? 'text-gray-400' : 'text-gray-900' }}">
                                Keputusan Asesmen (AK.02)
                            </h3>

                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('asesor.ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                   class="{{ btnState($allIADone ? 100 : 0, 100, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                   <i class="fas fa-gavel"></i> Keputusan
                                </a>
                                <a href="{{ route('ak02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank"
                                    class="{{ pdfState($isFinalized ? 100 : 0, 100) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>

                            {{-- Status Validator/Final --}}
                            @if($dataSertifikasi->status_validasi == 'valid')
                                <div class="mt-4 p-4 bg-green-100 border border-green-400 rounded-lg shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-green-500 text-white rounded-full p-1">
                                            <i class="fas fa-check w-4 h-4 text-center"></i>
                                        </div>
                                        <div>
                                            <p class="text-green-800 font-bold text-sm">SELESAI & TERVALIDASI</p>
                                            <p class="text-xs text-green-700">Keputusan telah disetujui oleh Validator.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($isFinalized)
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-blue-800 font-bold text-sm">✅ Keputusan telah dikirim ke Validator.</p>
                                    <p class="text-xs text-blue-600">Menunggu validasi.</p>
                                </div>
                            @elseif($allIADone)
                                <p class="text-xs text-yellow-600 mt-2 font-semibold">Silakan isi keputusan asesmen.</p>
                            @else
                                <p class="text-xs text-red-400 mt-2 italic">Selesaikan penilaian pada semua form di atas terlebih dahulu.</p>
                            @endif
                        </li>

                    </ol>
                </div>
            </section>

        </div>
      </div>
    </main>
  </div>
</body>
</html>