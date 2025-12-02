@extends('layouts.app-sidebar-asesi')

@section('content')

@php
    // 1. AMBIL LEVEL REAL DARI MODEL (ACCESSOR YANG KAMU BUAT)
    $level = $dataSertifikasi->level_status; 

    // 2. DEFINISI BATAS LEVEL (Sesuai Logic Model Kamu)
    $LVL_APL01_SUBMIT = 10;
    $LVL_APL01_VERIF = 20; // Asumsi APL01 diterima
    $LVL_APL02_VERIF    = 30; // APL02 diterima
    $LVL_SIAP_ASESMEN = 40; // AK01 Valid / Siap Ujian
    $LVL_SELESAI_IA   = 90; // Jawaban IA10 lengkap (Asesmen Selesai)
    $LVL_LULUS        = 100; // Sertifikat Terbit

    // 3. HELPER FUNCTION UNTUK STATUS VISUAL
    // Return: 'DONE' (Hijau), 'ACTIVE' (Kuning/Biru), 'LOCKED' (Abu)
    function getStepStatus($currentLevel, $targetDone, $targetActive) {
        if ($currentLevel >= $targetDone) return 'DONE';
        if ($currentLevel >= $targetActive) return 'ACTIVE';
        return 'LOCKED';
    }
@endphp

<div class="max-w-6xl mx-auto">

    {{-- HEADER INFO --}}
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800">Pra Asesmen</h1>
    </div>

    {{-- SECTION 1: PRA-ASESMEN --}}
    <section id="pra-asesmen">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="relative">
                
                {{-- GARIS VERTIKAL UTAMA (Background) --}}
                <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                {{-- ====================================================================== --}}
                {{-- ITEM 1: FR.APL.01 --}}
                {{-- ====================================================================== --}}
                @php $stAPL01 = getStepStatus($level, 20, $LVL_APL01_SUBMIT); @endphp
                
                <div class="relative pl-20 pb-8 group">
                    {{-- Garis Hijau Overlay (Progress) --}}
                    @if($stAPL01 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif

                    {{-- ICON --}}
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stAPL01 == 'DONE' ? 'bg-green-500 text-white' : ($stAPL01 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($stAPL01 == 'DONE') 
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold text-xs">01</span>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-gray-800">FR.APL.01 - Permohonan Sertifikasi Kompetensi</h3>
                            <div class="flex space-x-2 ml-4">
                                {{-- Tombol Dinamis --}}
                                <a href="{{ route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi) }}" class="bg-blue-100 text-blue-600 text-xs font-bold py-1 px-3 rounded-md">Lihat File</a>
                            <a href="#" 
                            class="bg-red-600 text-white text-xs font-bold py-1 px-3 rounded-md hover:bg-red-700 transition flex items-center gap-1">
                                
                                {{-- Ikon SVG Dokumen --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                
                                <span>Lihat PDF</span>
                            </a>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        @if($dataSertifikasi->rekomendasi_apl01 == 'diterima')
                            <p class="text-xs text-green-500 mt-1">Diterima</p>
                        @elseif($dataSertifikasi->rekomendasi_apl01 == 'tidak diterima')
                            <p class="text-xs text-red-500 mt-1">Tidak Diterima</p>
                        @else
                            <p class="text-xs text-yellow-300 mt-1">Menunggu Verifikasi</p>
                        @endif
                    </div>
                </div>

                {{-- ====================================================================== --}}
                {{-- ITEM 2: FR.APL.02 --}}
                {{-- ====================================================================== --}}
                @php $stAPL02 = getStepStatus($level, $LVL_APL01_VERIF, $LVL_APL02_VERIF); @endphp

                <div class="relative pl-20 pb-8 group">
                    @if($stAPL02 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif

                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stAPL02 == 'DONE' ? 'bg-green-500 text-white' : ($stAPL02 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($stAPL02 == 'DONE') 
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold text-xs">02</span>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold {{ $stAPL02 == 'LOCKED' ? 'text-gray-400' : 'text-gray-800' }}">FR.APL.02 - Asesmen Mandiri</h3>
                            <div class="flex flex-col gap-2 ml-4"> {{-- Container Vertikal --}}
                                
                                {{-- BARIS 1: Tombol Lihat File & PDF (Horizontal) --}}
                                <div class="flex gap-2">
                                    {{-- Tombol 1: Lihat File --}}
                                    <a href="#" class="bg-blue-100 text-blue-600 text-xs font-bold py-1 px-3 rounded-md text-center">
                                        Lihat File
                                    </a>

                                    {{-- Tombol 2: Lihat PDF --}}
                                    <a href="#" class="bg-red-600 text-white text-xs font-bold py-1 px-3 rounded-md hover:bg-red-700 transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span>Lihat PDF</span>
                                    </a>
                                </div>

                                {{-- BARIS 2: Tombol Verifikasi (Full Width) --}}
                                {{-- w-full akan membuatnya selebar container di atasnya --}}
                                <a href="#" class="w-full block bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-1 rounded-md text-center transition shadow-sm">
                                    Verifikasi
                                </a>

                            </div>
                        </div>
                        {{-- Status Badge --}}
                        @if($dataSertifikasi->rekomendasi_apl01 == 'diterima')
                            <p class="text-xs text-green-500">Diterima</p>
                        @elseif($dataSertifikasi->rekomendasi_apl01 == 'tidak diterima')
                            <p class="text-xs text-red-500">Tidak Diterima</p>
                        @else
                            <p class="text-xs text-yellow-300">Menunggu Verifikasi</p>
                        @endif                        
                    </div>
                </div>

                {{-- ====================================================================== --}}
                {{-- ITEM 3: FR.MAPA.01 --}}
                {{-- ====================================================================== --}}
                @php $stMAPA01 = getStepStatus($level, $LVL_APL01_VERIF, $LVL_APL02_VERIF); @endphp
                
                <div class="relative pl-20 pb-8 group">
                    {{-- Garis Hijau Overlay (Progress) --}}
                    @if($stMAPA01 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif

                    {{-- ICON --}}
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stMAPA01 == 'DONE' ? 'bg-green-500 text-white' : ($stMAPA01 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($stMAPA01 == 'DONE') 
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold text-xs">01</span>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-gray-800">FR.MAPA.01 - Merencanakan Aktivitas dan Proses Asesmen</h3>
                            <div class="flex space-x-2 ml-4">
                                {{-- Tombol Dinamis --}}
                                <a href="{{ route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}" class="bg-blue-100 text-blue-600 text-xs font-bold py-1 px-3 rounded-md">Lihat File</a>
                            <a href="#" 
                            class="bg-red-600 text-white text-xs font-bold py-1 px-3 rounded-md hover:bg-red-700 transition flex items-center gap-1">
                                
                                {{-- Ikon SVG Dokumen --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                
                                <span>Lihat PDF</span>
                            </a>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        @if($dataSertifikasi->rekomendasi_apl01 == 'diterima')
                            <p class="text-xs text-green-500 mt-1">Diterima</p>
                        @elseif($dataSertifikasi->rekomendasi_apl01 == 'tidak diterima')
                            <p class="text-xs text-red-500 mt-1">Tidak Diterima</p>
                        @else
                            <p class="text-xs text-yellow-300 mt-1">Menunggu Verifikasi</p>
                        @endif
                    </div>
                </div> 
                
                {{-- ====================================================================== --}}
                {{-- ITEM 4: FR.MAPA.02 --}}
                {{-- ====================================================================== --}}
                @php $stMAPA02 = getStepStatus($level, $LVL_APL01_VERIF, $LVL_APL02_VERIF); @endphp
                
                <div class="relative pl-20 pb-8 group">
                    {{-- Garis Hijau Overlay (Progress) --}}
                    @if($stMAPA02 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif

                    {{-- ICON --}}
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stMAPA02 == 'DONE' ? 'bg-green-500 text-white' : ($stMAPA01 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($stMAPA02 == 'DONE') 
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold text-xs">01</span>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-gray-800">FR.MAPA.02 - Peta Instrumen Asesmen</h3>
                            <div class="flex space-x-2 ml-4">
                                {{-- Tombol Dinamis --}}
                                <a href="{{ route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}" class="bg-blue-100 text-blue-600 text-xs font-bold py-1 px-3 rounded-md">Lihat File</a>
                            <a href="#" 
                            class="bg-red-600 text-white text-xs font-bold py-1 px-3 rounded-md hover:bg-red-700 transition flex items-center gap-1">
                                
                                {{-- Ikon SVG Dokumen --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                
                                <span>Lihat PDF</span>
                            </a>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        @if($dataSertifikasi->rekomendasi_apl01 == 'diterima')
                            <p class="text-xs text-green-500 mt-1">Diterima</p>
                        @elseif($dataSertifikasi->rekomendasi_apl01 == 'tidak diterima')
                            <p class="text-xs text-red-500 mt-1">Tidak Diterima</p>
                        @else
                            <p class="text-xs text-yellow-300 mt-1">Menunggu Verifikasi</p>
                        @endif
                    </div>
                </div>                 

                {{-- ====================================================================== --}}
                {{-- ITEM 5: FR.AK.01 (Persetujuan) --}}
                {{-- ====================================================================== --}}
                @php $stAK01 = getStepStatus($level, $LVL_SIAP_ASESMEN, $LVL_APL02_VERIF); @endphp

                <div class="relative pl-20 pb-8 group">
                    @if($stAK01 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif

                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stAK01 == 'DONE' ? 'bg-green-500 text-white' : ($stAK01 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($stAK01 == 'DONE') 
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold text-xs">AK.01</span>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold {{ $stAK01 == 'LOCKED' ? 'text-gray-400' : 'text-gray-800' }}">FR.AK.01 - Persetujuan & Kerahasiaan</h3>
                            <div class="flex flex-col gap-2 ml-4"> {{-- Container Vertikal --}}
                                
                                {{-- BARIS 1: Tombol Lihat File & PDF (Horizontal) --}}
                                <div class="flex gap-2">
                                    {{-- Tombol 1: Lihat File --}}
                                    <a href="#" class="bg-blue-100 text-blue-600 text-xs font-bold py-1 px-3 rounded-md text-center">
                                        Lihat File
                                    </a>

                                    {{-- Tombol 2: Lihat PDF --}}
                                    <a href="#" class="bg-red-600 text-white text-xs font-bold py-1 px-3 rounded-md hover:bg-red-700 transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span>Lihat PDF</span>
                                    </a>
                                </div>

                                {{-- BARIS 2: Tombol Verifikasi (Full Width) --}}
                                {{-- w-full akan membuatnya selebar container di atasnya --}}
                                <a href="#" class="w-full block {{ $stAK01 == 'ACTIVE' ? 'bg-blue-100 text-blue-600' : 'bg-gray-300 text-gray-600 pointer-events-none cursor-not-allowed' }} bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-1 rounded-md text-center transition shadow-sm">
                                    Verifikasi
                                </a>

                            </div>
                        </div>
                    {{-- Status Badge --}}
                    @if($dataSertifikasi->responbuktiAk01->contains('respon', 'Valid'))
                        <p class="text-xs text-green-500 mt-1">Diterima</p>
                    @elseif($dataSertifikasi->rekomendasi_apl01 == 'Tidak Memenuhi')
                        <p class="text-xs text-red-500 mt-1">Tidak Diterima</p>
                    @else
                        <p class="text-xs text-yellow-300 mt-1">Menunggu Verifikasi</p>                        
                    </div>
                    @endif                    
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 2: ASESMEN REAL (IA.xx) --}}
    <section id="asesmen" class="mt-12">
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">Asesmen</h1>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="relative">
                <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                {{-- LOGIC: Tombol Asesmen terbuka jika Level >= 40 (Siap Asesmen) --}}
                {{-- Selesai jika Level >= 90 --}}
                @php 
                    $stAsesmen = getStepStatus($level, $LVL_SELESAI_IA, $LVL_SIAP_ASESMEN); 
                    $isAsesmenActive = ($level >= $LVL_SIAP_ASESMEN);
                @endphp

                {{-- ITEM: FR.IA.01 dst --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stAsesmen == 'DONE' ? 'bg-green-500 text-white' : ($isAsesmenActive ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        <span class="font-bold text-xs">IA.05</span>
                    </div>

                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold {{ !$isAsesmenActive ? 'text-gray-400' : 'text-gray-800' }}">FR.IA.05 - Pertanyaan Tertulis Pilihan Ganda</h3>
                            <div class="flex flex-col gap-2 ml-4"> {{-- Container Vertikal --}}
                                
                                {{-- BARIS 1: Tombol Lihat File & PDF (Horizontal) --}}
                                <div class="flex gap-2">
                                    {{-- Tombol 1: Lihat File --}}
                                    <a href="{{ route('FR_IA_05_A', $asesi->id_asesi) }}" class="{{ $isAsesmenActive ? ' bg-gray-300 text-gray-600' : 'bg-blue-100 text-blue-600 ' }} text-xs font-bold py-1 px-3 rounded-md text-center">
                                        Lihat File
                                    </a>

                                    {{-- Tombol 2: Lihat PDF --}}
                                    <a href="#" class="{{ $isAsesmenActive ? 'bg-gray-300 text-gray-600 pointer-events-none cursor-not-allowed' : 'bg-red-600 text-white hover:bg-red-700' }} text-xs font-bold py-1 px-3 rounded-md transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span>Lihat PDF</span>
                                    </a>
                                </div>

                                {{-- BARIS 2: Tombol Verifikasi (Full Width) --}}
                                {{-- w-full akan membuatnya selebar container di atasnya --}}
                                <a href="#" class="w-full block {{ $isAsesmenActive ? 'bg-gray-300 text-gray-600 pointer-events-none cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }} text-xs font-bold py-1 rounded-md text-center transition shadow-sm">
                                    Lakukan Penilaian
                                </a>

                            </div>
                        </div>
                        
                        @if($stAsesmen == 'DONE')
                            <p class="text-xs text-green-600 font-bold mt-1">Asesmen telah selesai. Silahkan lakukan penilaian</p>
                        @elseif($isAsesmenActive)
                             <p class="text-xs text-blue-600 mt-1">Asesi sedang menjalankan asesmen</p>
                        @endif
                    </div>
                </div>

                {{-- ITEM: KEPUTUSAN AKHIR (AK.02) --}}
                {{-- Muncul jika Level >= 90 --}}
                @php $stAK02 = getStepStatus($level, 100, 90); @endphp
                <div class="relative pl-20">
                     <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $stAK02 == 'DONE' ? 'bg-green-500 text-white' : ($stAK02 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        <span class="font-bold text-xs">AK.02</span>
                    </div>
                    
                    <div>
                         <h3 class="text-lg font-semibold {{ $stAK02 == 'LOCKED' ? 'text-gray-400' : 'text-gray-800' }}">Keputusan Asesmen (AK.02)</h3>
                         @if($stAK02 == 'DONE')
                            <div class="mt-2">
                                @if($dataSertifikasi->rekomendasi_hasil_asesmen_AK02 == 'kompeten')
                                    <span class="text-green-600 font-bold text-lg">✅ KOMPETEN</span>
                                @else
                                    <span class="text-red-600 font-bold text-lg">❌ BELUM KOMPETEN</span>
                                @endif
                            </div>
                         @elseif($stAK02 == 'ACTIVE')
                            <p class="text-xs text-yellow-600 font-bold">Menunggu keputusan asesor...</p>
                         @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection