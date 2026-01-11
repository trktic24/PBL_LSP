@extends('layouts.app-sidebar-asesi')

@section('content')

    @php
        // =========================================================================
        // 1. SETUP LOGIKA UTAMA
        // =========================================================================

        // Ambil Level
        $level = $dataSertifikasi->level_status;

        // Jika status teks sudah disetujui, kita paksa variabel level jadi 40 
        // supaya bagian Asesmen di bawah otomatis terbuka.
        if ($dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui') {
            if ($level < 40) {
                $level = 40;
            }
        }

        // Cek apakah Asesmen sudah Final (Sudah ada keputusan AK.02)
        // Pastikan variabel $has_ak02_data dikirim dari Controller
        $isFinalized = ($level >= 100 && ($has_ak02_data ?? false));

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


        // [BARU] Helper Tombol Aksi Khusus Asesmen (IA.05 dst)
        function getActionBtn($status, $isFinalized)
        {
            // 1. Kalau Asesmen udah Final (AK.02 dikunci), tombol mati
            if ($isFinalized) {
                return [
                    'label' => 'Selesai',
                    'class' => 'bg-gray-200 text-gray-400 pointer-events-none cursor-not-allowed'
                ];
            }

            // 2. Kalau Status DONE (Sudah Dinilai) -> Tombol Kuning (Edit)
            if ($status == 'DONE') {
                return [
                    'label' => 'Edit Penilaian',
                    'class' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 cursor-pointer'
                ];
            }

            // 3. Kalau Status ACTIVE (Bisa Dinilai) -> Tombol Biru Solid (Lakukan)
            if ($status == 'ACTIVE') {
                return [
                    'label' => 'Lakukan Penilaian',
                    'class' => 'bg-blue-100 text-blue-600 hover:bg-blue-200 cursor-pointer'
                ];
            }

            // 4. Sisanya (LOCKED) -> Tombol Abu
            return [
                'label' => 'Terkunci',
                'class' => 'bg-gray-100 text-gray-400 pointer-events-none cursor-not-allowed'
            ];
        }

        // =========================================================================
        // 3. CEK STATUS PER ITEM (DYNAMIC LOGIC)
        // =========================================================================

        // Config Show/Hide (Default 1 jika null)
        $show = [
            'ia01' => $listForm->fr_ia_01 ?? 1,
            'ia02' => $listForm->fr_ia_02 ?? 1,
            'ia03' => $listForm->fr_ia_03 ?? 1,
            'ia04' => $listForm->fr_ia_04 ?? 1,
            'ia05' => $listForm->fr_ia_05 ?? 1,
            'ia06' => $listForm->fr_ia_06 ?? 1,
            'ia07' => $listForm->fr_ia_07 ?? 1,
            'ia08' => $listForm->fr_ia_08 ?? 1,
            'ia09' => $listForm->fr_ia_09 ?? 1,
            'ia10' => $listForm->fr_ia_10 ?? 1,
            'ia11' => $listForm->fr_ia_11 ?? 1,
        ];

        // --- CEK STATUS DONE ---
        // Gunakan optional() agar tidak error jika relasi belum ada di model

        // IA.01
        $ia01Done = optional($dataSertifikasi->ia01)->exists() ?? false;
        $stIa01 = $ia01Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia01Pass = $ia01Done || ($show['ia01'] != 1);

        // IA.02 (Existing)
        // Logic updated: IA02 is considered done if IA01 is done
       // Cukup cek apakah ia02 ada isinya (true) atau null (false)
        $ia02Done = $dataSertifikasi->ia02 && $dataSertifikasi->ia02->count() > 0;
        $stIa02 = $ia02Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia02Pass = $ia02Done || ($show['ia02'] != 1);

        // IA.03 (Tipe Data: Collection/List)
        // Kita cek: Datanya ada TIDAK null, DAN jumlahnya lebih dari 0
        $ia03Done = $dataSertifikasi->ia03 && $dataSertifikasi->ia03->count() > 0;
        $stIa03 = $ia03Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia03Pass = $ia03Done || ($show['ia03'] != 1);

        // IA.04 (Tipe Data: Single Object/Satu Data)
        // Kita cek: Cukup pastikan variabelnya ada isinya (bukan null)
        // JANGAN PAKAI count() DI SINI
        $ia04Done = $dataSertifikasi->ia04 ? true : false;
        $stIa04 = $ia04Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia04Pass = $ia04Done || ($show['ia04'] != 1);

        // IA.05 (Existing - Cek pencapaian_ia05)
        $ia05Done = $dataSertifikasi->lembarJawabIa05()->whereNotNull('pencapaian_ia05')->exists();
        $stIa05 = $ia05Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia05Pass = $ia05Done || ($show['ia05'] != 1);

        // IA.06 (Existing - Cek pencapaian)
        $ia06Done = $dataSertifikasi->ia06Answers()->whereNotNull('pencapaian')->exists();
        $stIa06 = $ia06Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia06Pass = $ia06Done || ($show['ia06'] != 1);

        // IA.07 (Existing - Cek pencapaian)
        $ia07Done = $dataSertifikasi->ia07()->whereNotNull('pencapaian')->exists();
        $stIa07 = $ia07Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia07Pass = $ia07Done || ($show['ia07'] != 1);

        // IA.08 (Log: Limit 1 -> Single Object)
        // Cek: Apakah variabel ada isinya?
        $ia08Done = $dataSertifikasi->ia08 && !empty($dataSertifikasi->ia08->rekomendasi);
        $stIa08 = $ia08Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia08Pass = $ia08Done || ($show['ia08'] != 1);

        // IA.09 (Log: Limit 1 -> Single Object)
        // Cek: Apakah variabel ada isinya?
       $ia09Done = $is_ia09_graded ?? false; 
        $stIa09 = $ia09Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia09Pass = $ia09Done || ($show['ia09'] != 1);

        // IA.10 (Biasanya Single, sesuaikan jika beda)
        // Jika IA.10 single object:
        $ia10Done = $dataSertifikasi->ia10 && 
                    $dataSertifikasi->ia10->details && 
                    $dataSertifikasi->ia10->details->count() > 0;

        $stIa10 = $ia10Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia10Pass = $ia10Done || ($show['ia10'] != 1);

        // IA.11 (Log: TIDAK ADA LIMIT -> Collection/List)
        // ERRORNYA DISINI: Jangan pakai exists(), pakai count()
        $ia11Done = $dataSertifikasi->ia11 && $dataSertifikasi->ia11->count() > 0;
        $stIa11 = $ia11Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
        $ia11Pass = $ia11Done || ($show['ia11'] != 1);


        // SYARAT AK.02 TERBUKA
        $allIADone = ($ia01Pass && $ia02Pass && $ia03Pass && $ia04Pass && $ia05Pass &&
            $ia06Pass && $ia07Pass && $ia08Pass && $ia09Pass && $ia10Pass && $ia11Pass);
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
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                                {{ $dataSertifikasi->rekomendasi_apl01 == 'diterima' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                            @if($dataSertifikasi->rekomendasi_apl01 == 'diterima')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else <span class="font-bold text-xs">01</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">FR.APL.01 - Permohonan Sertifikasi</h3>
                                <div class="flex space-x-2 ml-4">
                                    {{-- TOMBOL VERIFIKASI DIHAPUS --}}
                                    <a href="{{ route('apl01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState(100, 0) }}"><span>Lihat
                                            PDF</span></a>
                                </div>
                            </div>
                            @if($dataSertifikasi->rekomendasi_apl01 == 'diterima')
                                <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @elseif($dataSertifikasi->rekomendasi_apl01 == 'tidak diterima') <p
                                class="text-xs text-red-500 mt-1 font-semibold">Tidak Diterima</p>
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
                                    {{-- TOMBOL VERIFIKASI DIHAPUS --}}
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
                                    {{-- TOMBOL VERIFIKASI DIHAPUS --}}
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
                                    {{-- LOGIKA BARU: Jika diterima -> DISABLE --}}
                                    <a href="{{ route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="text-xs font-bold py-1 px-3 rounded-md {{ $dataSertifikasi->rekomendasi_apl02 == 'diterima' ? 'bg-gray-200 text-gray-400 pointer-events-none cursor-not-allowed' : btnState($level, 20, $isFinalized) }}">
                                        Verifikasi
                                    </a>
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
                        @php
                            $ak01Done = ($level >= 40 || $dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui');
                        @endphp
                        <div
                            class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                                {{ $ak01Done ? 'bg-green-500 text-white' : ($level >= 30 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($ak01Done) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> @else <span class="font-bold text-xs">AK1</span> @endif
                        </div>
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold {{ $level < 30 ? 'text-gray-400' : 'text-gray-800' }}">
                                    FR.AK.01 - Persetujuan & Kerahasiaan</h3>
                                <div class="flex gap-2 ml-4">
                                    {{-- LOGIKA BARU: Jika diterima -> DISABLE --}}
                                    <a href="{{ route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="text-xs font-bold py-1 px-3 rounded-md {{ $ak01Done ? 'bg-gray-200 text-gray-400 pointer-events-none cursor-not-allowed' : btnState($level, 20, $isFinalized) }}">
                                        Verifikasi
                                    </a>
                                    <a href="{{ route('ak01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 {{ pdfState($level, 30) }}">Lihat
                                        PDF</a>
                                </div>
                            </div>
                            @if($ak01Done)
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

                    {{-- 1. FR.IA.01 (BARU: Static) --}}
                    @if($show['ia01'] == 1)
                        <div class="relative pl-20 pb-8">
                            <div
                                class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa01) }}">
                                @if($stIa01 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg> @else <span class="font-bold text-xs">IA.01</span> @endif
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.IA.01 - Ceklis Observasi Aktivitas</h3>
                                    <div class="flex gap-2 ml-4">
                                        @php $btn = getActionBtn($stIa01, $isFinalized); @endphp
                                        <a href="{{ route('ia01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
                                        <a href="{{ route('ia01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>
                                @if($stIa01 == 'DONE')
                                    <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                                @elseif($stIa01 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                    </p>
                                @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                            </div>
                        </div>
                    @endif

                    {{-- 2. FR.IA.02 (LAMA: Active) --}}
                    @if($show['ia02'] == 1)
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
                                        @php 
                                            $btn = getActionBtn($stIa02, $isFinalized); 
                                            if ($stIa02 == 'DONE') {
                                                $btn['label'] = 'Lihat Instruksi';
                                            }
                                        @endphp
                                        {{-- Link ke Show karena itu formnya --}}
                                        <a href="{{ route('ia02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
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
                    @endif

                    {{-- 3. FR.IA.03 (BARU: Static) --}}
                    @if($show['ia03'] == 1)
                        <div class="relative pl-20 pb-8">
                            <div
                                class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa03) }}">
                                @if($stIa03 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg> @else <span class="font-bold text-xs">IA.03</span> @endif
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.IA.03 - Pertanyaan Mendukung Observasi
                                    </h3>
                                    <div class="flex gap-2 ml-4">
                                        @php $btn = getActionBtn($stIa03, $isFinalized); @endphp
                                        <a href="{{ route('ia03.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
                                        <a href="{{ route('ia03.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>
                                @if($stIa03 == 'DONE')
                                    <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                                @elseif($stIa03 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                    </p>
                                @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                            </div>
                        </div>
                    @endif

                    {{-- 4. FR.IA.04 (BARU: Static) --}}
                    @if($show['ia04'] == 1)
                        <div class="relative pl-20 pb-8">
                            <div
                                class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa04) }}">
                                @if($stIa04 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg> @else <span class="font-bold text-xs">IA.04</span> @endif
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.IA.04 - Penjelasan Singkat Proyek</h3>
                                    <div class="flex gap-2 ml-4">
                                        @php $btn = getActionBtn($stIa04, $isFinalized); @endphp
                                        <a href="{{ route('fria04a.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
                                        <a href="{{ route('ia04.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>
                                @if($stIa04 == 'DONE')
                                    <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                                @elseif($stIa04 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                    </p>
                                @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                            </div>
                        </div>
                    @endif

                    {{-- 5. FR.IA.05 (LAMA: Active) --}}
                    @if($show['ia05'] == 1)
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
                                        @php $btn = getActionBtn($stIa05, $isFinalized); @endphp
                                        <a href="{{ route('ia05.asesor', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
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
                    @endif

                    {{-- 6. FR.IA.06 (LAMA: Active) --}}
                    @if($show['ia06'] == 1)
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
                                        @php $btn = getActionBtn($stIa06, $isFinalized); @endphp
                                        <a href="{{ route('asesor.ia06.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
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
                    @endif

                    {{-- 7. FR.IA.07 (LAMA: Active) --}}
                    @if($show['ia07'] == 1)
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
                                        @php $btn = getActionBtn($stIa07, $isFinalized); @endphp
                                        <a href="{{ route('ia07.asesor', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
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
                    @endif

                    {{-- 8. FR.IA.08 (BARU: Static) --}}
                    @if($show['ia08'] == 1)
                        <div class="relative pl-20 pb-8">
                            <div
                                class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa08) }}">
                                @if($stIa08 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg> @else <span class="font-bold text-xs">IA.08</span> @endif
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.IA.08 - Ceklis Verifikasi Portofolio</h3>
                                    <div class="flex gap-2 ml-4">
                                        @php $btn = getActionBtn($stIa08, $isFinalized); @endphp
                                        <a href="{{ route('ia08.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
                                        <a href="{{ route('ia08.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>
                                @if($stIa08 == 'DONE')
                                    <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                                @elseif($stIa08 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                    </p>
                                @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                            </div>
                        </div>
                    @endif

                    {{-- 9. FR.IA.09 (BARU: Static) --}}
                    @if($show['ia09'] == 1)
                        <div class="relative pl-20 pb-8">
                            <div
                                class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa09) }}">
                                @if($stIa09 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg> @else <span class="font-bold text-xs">IA.09</span> @endif
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.IA.09 - Pertanyaan Wawancara</h3>
                                    <div class="flex gap-2 ml-4">
                                        @php $btn = getActionBtn($stIa09, $isFinalized); @endphp
                                        <a href="{{ route('ia09.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
                                        <a href="{{ route('ia09.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>
                                @if($stIa09 == 'DONE')
                                    <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                                @elseif($stIa09 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                    </p>
                                @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                            </div>
                        </div>
                    @endif

                    {{-- 10. FR.IA.10 (LAMA: Active) --}}
                    @if($show['ia10'] == 1)
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
                                        @php $btn = getActionBtn($stIa10, $isFinalized); @endphp
                                        <a href="{{ route('fr-ia-10.create', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
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
                    @endif

                    {{-- 11. FR.IA.11 (BARU: Static) --}}
                    @if($show['ia11'] == 1)
                        <div class="relative pl-20 pb-8">
                            <div
                                class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa11) }}">
                                @if($stIa11 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg> @else <span class="font-bold text-xs">IA.11</span> @endif
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.IA.11 - Ceklis Meninjau Instrumen</h3>
                                    <div class="flex gap-2 ml-4">
                                        @php $btn = getActionBtn($stIa11, $isFinalized); @endphp
                                        <a href="{{ route('ia11.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btn['class'] }} text-xs font-bold py-1 px-3 rounded-md">{{ $btn['label'] }}</a>
                                        <a href="{{ route('ia11.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($level, 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>
                                @if($stIa11 == 'DONE')
                                    <p class="text-xs text-green-500 mt-1 font-semibold">Sudah Dinilai</p>
                                @elseif($stIa11 == 'ACTIVE') <p class="text-xs text-yellow-600 mt-1 font-semibold">Belum Dinilai
                                    </p>
                                @else <p class="text-xs text-gray-400 mt-1 italic">Belum Terbuka</p> @endif
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">

                        {{-- ITEM TERAKHIR: AK.02 (KEPUTUSAN) --}}
                        <div class="relative pl-20 pt-4 border-t mt-4 group">
                            <div
                                class="absolute left-0 top-6 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white 
                                {{ $isFinalized ? 'bg-green-600 text-white' : ($allIADone ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                                <span class="font-bold text-xs">AK.02</span>
                            </div>
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold text-gray-800 mt-2">Keputusan Asesmen (AK.02)</h3>
                                    <div class="flex gap-2 ml-4 mt-2">
                                        {{-- Tombol Verifikasi --}}
                                        <a href="{{ route('ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ btnState($allIADone ? 100 : 0, 100, $isFinalized) }} text-xs font-bold py-1 px-3 rounded-md">Verifikasi</a>

                                        {{-- Tombol Lihat PDF --}}
                                        <a href="{{ route('ak02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}"
                                            target="_blank"
                                            class="{{ pdfState($isFinalized ? 100 : 0, 100) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat
                                            PDF</a>
                                    </div>
                                </div>

                                @if($dataSertifikasi->status_validasi == 'valid')
                                    <div class="mt-4 p-4 bg-green-100 border border-green-400 rounded-lg shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-green-500 text-white rounded-full p-1"><svg class="w-6 h-6"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg></div>
                                            <div>
                                                <p class="text-green-800 font-bold text-lg">SELESAI & TERVALIDASI</p>
                                                <p class="text-sm text-green-700">Pekerjaan Anda telah diperiksa dan disetujui
                                                    oleh Validator.</p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($isFinalized)
                                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-blue-800 font-bold">✅ Keputusan telah dikirim ke Validator.</p>
                                        <p class="text-xs text-blue-600">Menunggu validasi</p>
                                    </div>
                                @elseif($allIADone)
                                    <p class="text-sm text-gray-500 mb-2 font-semibold text-yellow-600">Silakan isi keputusan
                                        asesmen.</p>
                                @else
                                    <p class="text-xs text-red-400 mt-2 italic">Selesaikan penilaian pada semua form yang wajib
                                        di atas terlebih dahulu.</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
        </section>
    </div>
@endsection