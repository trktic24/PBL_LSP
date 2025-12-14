@extends('layouts.app-sidebar-asesi')

@section('content')

    @php
        // =========================================================================
        // 1. SETUP LOGIKA UTAMA
        // =========================================================================

        // Ambil Level
        $level = $dataSertifikasi->level_status;

        // Cek apakah Asesmen sudah Final (Sudah ada keputusan AK.02)
        $isFinalized = ($level >= 100);

        // =========================================================================
        // 2. HELPER FUNCTION
        // =========================================================================

        // Helper Tombol Verifikasi
        function btnState($currentLevel, $requiredLevel, $isFinalized)
        {
            if ($isFinalized) {
                return 'bg-gray-200 text-gray-400 pointer-events-none cursor-not-allowed';
            }
            if ($currentLevel >= $requiredLevel) {
                return 'bg-blue-100 text-blue-600 hover:bg-blue-200 cursor-pointer';
            } else {
                return 'bg-gray-200 text-gray-400 pointer-events-none cursor-not-allowed';
            }
        }

        // Helper Tombol PDF
        function pdfState($currentLevel, $requiredLevel)
        {
            if ($currentLevel >= $requiredLevel) {
                return 'bg-red-600 text-white hover:bg-red-700 cursor-pointer';
            } else {
                return 'bg-gray-300 text-gray-500 pointer-events-none cursor-not-allowed';
            }
        }

        // Helper Warna Icon
        function iconColor($status)
        {
            if ($status == 'DONE')
                return 'bg-green-500 text-white';
            if ($status == 'ACTIVE')
                return 'bg-yellow-400 text-white';
            return 'bg-gray-200 text-gray-400';
        }

        // =========================================================================
        // 3. CEK STATUS PER ITEM (UNTUK AK.02 LOCK)
        // =========================================================================

        $ia05Done = $dataSertifikasi->lembarJawabIa05()->exists();
        $stIa05 = $ia05Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

        $ia10Done = $dataSertifikasi->ia10()->exists();
        $stIa10 = $ia10Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

        $ia02Done = $dataSertifikasi->ia02()->exists();
        $stIa02 = $ia02Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

        $ia06Done = $dataSertifikasi->ia06Answers()->count() > 0;
        $stIa06 = $ia06Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

        $ia07Done = $dataSertifikasi->ia07()->exists();
        $stIa07 = $ia07Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

        // Semua IA Harus Selesai agar AK.02 Terbuka
        $allIADone = ($ia05Done && $ia10Done && $ia02Done && $ia06Done && $ia07Done);
    @endphp

    <div class="max-w-6xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800">Pra Asesmen</h1>
            <p class="text-sm text-gray-500 mt-2">Selesaikan langkah secara berurutan untuk membuka tahap Asesmen.</p>
        </div>

        {{-- SECTION 1: PRA-ASESMEN --}}
        <section id="pra-asesmen">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="relative">
                    <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                    {{-- ITEM 1: FR.APL.01 --}}
                    <div class="relative pl-20 pb-8 group">
                        {{-- REVISI DISINI: Logika warna ikon diganti cek langsung ke 'diterima' --}}
                        <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                            {{ $dataSertifikasi->rekomendasi_apl01 == 'diterima' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                            
                            {{-- Icon Check (Jika diterima) atau Angka 01 (Jika belum) --}}
                            @if($dataSertifikasi->rekomendasi_apl01 == 'diterima') 
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 
                            @else 
                                <span class="font-bold text-xs">01</span> 
                            @endif
                        </div>

                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.APL.01 - Permohonan Sertifikasi</h3>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi) }}" class="text-xs font-bold py-1 px-3 rounded-md {{ btnState(100, 0, $isFinalized) }}">Verifikasi</a>
                                    <a href="{{ route('apl01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState(100, 0) }}"><span>Lihat PDF</span></a>
                                </div>
                            </div>
                            {{-- STATUS TEXT --}}
                            @if($dataSertifikasi->rekomendasi_apl01 == 'diterima') <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @elseif($dataSertifikasi->rekomendasi_apl01 == 'tidak diterima') <p class="text-xs text-red-500 mt-1 font-semibold">Tidak Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                    </div>

                    {{-- ITEM 2: FR.MAPA.01 --}}
                    <div class="relative pl-20 pb-8 group">
                        <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                            {{ $level >= 20 ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                            @if($level >= 20) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">M1</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.MAPA.01 - Merencanakan Aktivitas</h3>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="text-xs font-bold py-1 px-3 rounded-md {{ btnState(100, 0, $isFinalized) }}">Verifikasi</a>
                                    <a href="{{ route('mapa01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState(100, 0) }}">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($level >= 20)
                                <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                    </div>

                    {{-- ITEM 3: FR.MAPA.02 --}}
                    <div class="relative pl-20 pb-8 group">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                            {{ $level >= 20 ? 'bg-green-500 text-white' : ($level >= 10 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($level >= 20) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">M2</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold {{ $level < 10 ? 'text-gray-400' : 'text-gray-800' }}">
                                    FR.MAPA.02 - Peta Instrumen</h3>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="text-xs font-bold py-1 px-3 rounded-md {{ btnState($level, 20, $isFinalized) }}">Verifikasi</a>
                                    <a href="{{ route('mapa02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState($level, 20) }}">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($level >= 20)
                                <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                    </div>

                    {{-- ITEM 4: FR.APL.02 --}}
                    <div class="relative pl-20 pb-8 group">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                            {{ $level >= 30 ? 'bg-green-500 text-white' : ($level >= 20 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($level >= 30) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">02</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold {{ $level < 20 ? 'text-gray-400' : 'text-gray-800' }}">
                                    FR.APL.02 - Asesmen Mandiri</h3>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="text-xs font-bold py-1 px-3 rounded-md {{ btnState($level, 20, $isFinalized) }}">Verifikasi</a>
                                    <a href="{{ route('apl02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState($level, 20) }}">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($dataSertifikasi->rekomendasi_apl02 == 'diterima')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @elseif($level < 20) <p class="text-xs text-red-400 italic mt-1">Selesaikan APL.01 terlebih
                                dahulu.</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                    </div>

                    {{-- ITEM 5: FR.AK.01 --}}
                    <div class="relative pl-20 pb-8 group">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                            {{ $level >= 40 ? 'bg-green-500 text-white' : ($level >= 30 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($level >= 40) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">AK1</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold {{ $level < 30 ? 'text-gray-400' : 'text-gray-800' }}">
                                    FR.AK.01 - Persetujuan & Kerahasiaan</h3>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="text-xs font-bold py-1 px-3 rounded-md {{ btnState($level, 30, $isFinalized) }}">Verifikasi</a>
                                    <a href="{{ route('ak01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState($level, 30) }}">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($level >= 40)
                                <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @elseif($level < 30) <p class="text-xs text-red-400 italic mt-1">Selesaikan APL.02 terlebih
                                dahulu.</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 2: ASESMEN --}}
        <section id="asesmen" class="mt-12 transition-all duration-300 {{ $level < 40 ? 'opacity-60' : '' }}">

            <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">
                Asesmen
                @if($level < 40) <span class="text-sm font-normal text-red-500 block">(Terkunci: Selesaikan Pra
                Asesmen)</span> @endif
            </h1>

            <div class="bg-white rounded-2xl shadow-lg p-8 relative">
                <div class="relative">
                    <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                    {{-- ITEM: FR.IA.05 --}}
                    <div class="relative pl-20 pb-8">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa05) }}">
                            @if($stIa05 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">IA.05</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.IA.05 - Pertanyaan Tertulis</h3>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('FR_IA_05_A', $asesi->id_asesi) }}"
                                        class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md">Verifikasi</a>
                                    <a href="{{ route('ia05.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($stIa05 == 'DONE')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                            @elseif($stIa05 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                </p>
                            @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                        </div>
                    </div>

                    {{-- ITEM: FR.IA.10 --}}
                    <div class="relative pl-20 pb-8">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa10) }}">
                            @if($stIa10 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">IA.10</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.IA.10 - Verifikasi Pihak Ketiga</h3>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('fr-ia-10.create', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md">Verifikasi</a>
                                    <a href="{{ route('ia10.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($stIa10 == 'DONE')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                            @elseif($stIa10 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                </p>
                            @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                        </div>
                    </div>

                    {{-- ITEM: FR.IA.02 --}}
                    <div class="relative pl-20 pb-8">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa02) }}">
                            @if($stIa02 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">IA.02</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.IA.02 - Tugas Praktik Demonstrasi</h3>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('fr-ia-02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md">Verifikasi</a>
                                    <a href="{{ route('ia02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($stIa02 == 'DONE')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                            @elseif($stIa02 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                </p>
                            @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                        </div>
                    </div>

                    {{-- ITEM: FR.IA.06 --}}
                    <div class="relative pl-20 pb-8">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa06) }}">
                            @if($stIa06 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">IA.06</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.IA.06 - Pertanyaan Lisan</h3>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('asesor.ia06.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md">Verifikasi</a>
                                    <a href="{{ route('ia06.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($stIa06 == 'DONE')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                            @elseif($stIa06 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                </p>
                            @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                        </div>
                    </div>

                    {{-- ITEM: FR.IA.07 --}}
                    <div class="relative pl-20 pb-8">
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa07) }}">
                            @if($stIa07 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">IA.07</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.IA.07 - Daftar Pertanyaan Lisan</h3>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('FR_IA_07') }}"
                                        class="{{ btnState($level, 40, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md">Verifikasi</a>
                                    <a href="{{ route('ia07.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($stIa07 == 'DONE')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                            @elseif($stIa07 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                </p>
                            @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                        </div>
                    </div>

                    {{-- ITEM TERAKHIR: AK.02 (KEPUTUSAN) --}}
                    <div class="relative pl-20 pt-4 border-t mt-4">
                        <div class="absolute left-0 top-6 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white 
                            {{ $isFinalized ? 'bg-green-600 text-white' : ($allIADone ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                            <span class="font-bold text-xs">AK.02</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mt-2">Keputusan Asesmen (AK.02)</h3>
                            
                            {{-- PRIORITAS 1: SUDAH DIVALIDASI VALIDATOR (HIJAU TEBAL) --}}
                            @if($dataSertifikasi->status_validasi == 'valid')
                                <div class="mt-4 p-4 bg-green-100 border border-green-400 rounded-lg shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-green-500 text-white rounded-full p-1">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-green-800 font-bold text-lg">SELESAI & TERVALIDASI</p>
                                            <p class="text-sm text-green-700">Pekerjaan Anda telah diperiksa dan disetujui oleh Validator.</p>
                                        </div>
                                    </div>
                                </div>

                            {{-- PRIORITAS 2: SUDAH DIKIRIM TAPI BELUM DIVALIDASI (BIRU/INFO) --}}
                            @elseif($isFinalized)
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-blue-800 font-bold">✅ Keputusan telah dikirim ke Validator.</p>
                                    <p class="text-xs text-blue-600">Menunggu validasi</p>
                                </div>

                            {{-- PRIORITAS 3: BELUM SELESAI (TOMBOL MASIH AKTIF) --}}
                            @elseif($allIADone)
                                <p class="text-sm text-gray-500 mb-2">Pastikan seluruh instrumen asesmen (IA) telah dinilai sebelum mengisi keputusan ini.</p>
                                <a href="{{-- ISI ROUTE KE CONTROLLER CREATE AK.02 --}}" class="inline-block bg-indigo-600 text-white hover:bg-indigo-700 py-2 px-6 rounded-lg shadow-md transition cursor-pointer">
                                    Isi Keputusan Asesmen
                                </a>

                            {{-- PRIORITAS 4: BELUM BISA DIISI --}}
                            @else
                                <p class="text-sm text-gray-500 mb-2">Pastikan seluruh instrumen asesmen (IA) telah dinilai sebelum mengisi keputusan ini.</p>
                                <button class="inline-block bg-gray-300 text-gray-500 py-2 px-6 rounded-lg shadow-none cursor-not-allowed" disabled>
                                    Isi Keputusan Asesmen
                                </button>
                                <p class="text-xs text-red-400 mt-2 italic">Selesaikan penilaian pada semua form di atas terlebih dahulu.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection