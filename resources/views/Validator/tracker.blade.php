@extends('layouts.app-sidebar-asesi') {{-- Pastikan layout ini benar --}}

@section('content')

@php
    // =========================================================================
    // 1. SETUP DATA & HELPER
    // =========================================================================

    $level = $dataSertifikasi->level_status;

    // Helper Warna Icon (Sama seperti Asesor)
    function iconColor($status) {
        if ($status == 'DONE') return 'bg-green-500 text-white';
        if ($status == 'ACTIVE') return 'bg-yellow-400 text-white';
        return 'bg-gray-200 text-gray-400';
    }

    // Helper PDF (Validator BOLEH lihat PDF kapan saja jika sudah ada)
    // Kita anggap jika statusnya DONE atau ACTIVE (sudah tahapnya), PDF bisa dilihat.
    function pdfBtn($isActive) {
        return $isActive 
            ? 'bg-red-600 text-white hover:bg-red-700 cursor-pointer' 
            : 'bg-gray-300 text-gray-500 pointer-events-none cursor-not-allowed';
    }

    // --- CEK STATUS ITEM ASESMEN ---
    $stIa05 = $dataSertifikasi->lembarJawabIa05()->exists() ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
    $stIa10 = $dataSertifikasi->ia10()->exists() ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
    $stIa02 = $dataSertifikasi->ia02()->exists() ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
    $stIa06 = $dataSertifikasi->ia06Answers()->count() > 0 ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
    $stIa07 = $dataSertifikasi->ia07()->exists() ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
@endphp

<div class="max-w-6xl mx-auto">
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800">Validasi Asesmen</h1>
        <p class="text-sm text-gray-500 mt-2">Silahkan periksa dokumen hasil kerja Asesor di bawah ini.</p>
    </div>

    {{-- SECTION 1: PRA-ASESMEN --}}
    <section id="pra-asesmen">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="relative">
                <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                {{-- ITEM 1: FR.APL.01 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $dataSertifikasi->rekomendasi_apl01 == 'diterima' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                        @if($dataSertifikasi->rekomendasi_apl01 == 'diterima') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">01</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.APL.01 - Permohonan Sertifikasi</h3>
                            @if($dataSertifikasi->rekomendasi_apl01 == 'diterima') <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                        <a href="{{ route('apl01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="bg-red-600 text-white text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 hover:bg-red-700">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM 2: FR.MAPA.01 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $level >= 20 ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                        @if($level >= 20) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">M1</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.MAPA.01 - Merencanakan Aktivitas</h3>
                            @if($level >= 20) <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                        <a href="{{ route('mapa01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 10) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM 3: FR.MAPA.02 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $level >= 20 ? 'bg-green-500 text-white' : ($level >= 10 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($level >= 20) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">M2</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold {{ $level < 10 ? 'text-gray-400' : 'text-gray-800' }}">FR.MAPA.02 - Peta Instrumen</h3>
                            @if($level >= 20) <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                        <a href="{{ route('mapa02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 20) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM 4: FR.APL.02 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $level >= 30 ? 'bg-green-500 text-white' : ($level >= 20 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($level >= 30) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">02</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold {{ $level < 20 ? 'text-gray-400' : 'text-gray-800' }}">FR.APL.02 - Asesmen Mandiri</h3>
                            @if($dataSertifikasi->rekomendasi_apl02 == 'diterima') <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                        <a href="{{ route('apl02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 20) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM 5: FR.AK.01 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                        {{ $level >= 40 ? 'bg-green-500 text-white' : ($level >= 30 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                        @if($level >= 40) <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">AK1</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold {{ $level < 30 ? 'text-gray-400' : 'text-gray-800' }}">FR.AK.01 - Persetujuan & Kerahasiaan</h3>
                            @if($level >= 40) <p class="text-xs text-green-500 mt-1 font-semibold">Diterima</p>
                            @else <p class="text-xs text-yellow-600 mt-1 font-semibold">Menunggu Verifikasi</p> @endif
                        </div>
                        <a href="{{ route('ak01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 30) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 2: ASESMEN --}}
    <section id="asesmen" class="mt-8">
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">Asesmen</h1>
        <div class="bg-white rounded-2xl shadow-lg p-8 relative">
            <div class="relative">
                <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                {{-- ITEM: FR.IA.05 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa05) }}">
                        @if($stIa05 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">IA.05</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.IA.05 - Pertanyaan Tertulis</h3>
                            <p class="text-xs {{ $stIa05 == 'DONE' ? 'text-green-500' : 'text-yellow-600' }} mt-1 font-semibold">
                                {{ $stIa05 == 'DONE' ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </p>
                        </div>
                        <a href="{{ route('ia05.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM: FR.IA.10 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa10) }}">
                        @if($stIa10 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">IA.10</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.IA.10 - Verifikasi Pihak Ketiga</h3>
                            <p class="text-xs {{ $stIa10 == 'DONE' ? 'text-green-500' : 'text-yellow-600' }} mt-1 font-semibold">
                                {{ $stIa10 == 'DONE' ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </p>
                        </div>
                        <a href="{{ route('ia10.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM: FR.IA.02 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa02) }}">
                        @if($stIa02 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">IA.02</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.IA.02 - Tugas Praktik Demonstrasi</h3>
                            <p class="text-xs {{ $stIa02 == 'DONE' ? 'text-green-500' : 'text-yellow-600' }} mt-1 font-semibold">
                                {{ $stIa02 == 'DONE' ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </p>
                        </div>
                        <a href="{{ route('ia02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM: FR.IA.06 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa06) }}">
                        @if($stIa06 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">IA.06</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.IA.06 - Pertanyaan Lisan</h3>
                            <p class="text-xs {{ $stIa06 == 'DONE' ? 'text-green-500' : 'text-yellow-600' }} mt-1 font-semibold">
                                {{ $stIa06 == 'DONE' ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </p>
                        </div>
                        <a href="{{ route('ia06.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>

                {{-- ITEM: FR.IA.07 --}}
                <div class="relative pl-20 pb-8">
                    <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white {{ iconColor($stIa07) }}">
                        @if($stIa07 == 'DONE') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else <span class="font-bold text-xs">IA.07</span> @endif
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">FR.IA.07 - Daftar Pertanyaan Lisan</h3>
                            <p class="text-xs {{ $stIa07 == 'DONE' ? 'text-green-500' : 'text-yellow-600' }} mt-1 font-semibold">
                                {{ $stIa07 == 'DONE' ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </p>
                        </div>
                        <a href="{{ route('ia07.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ pdfBtn($level >= 40) }} text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1">Lihat PDF</a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

   {{-- SECTION 3: VALIDASI FINAL --}}
    <section class="mt-12 mb-20">
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-8 text-center shadow-inner">
            <h2 class="text-2xl font-bold text-indigo-900">Validasi Pekerjaan Asesor</h2>
            <p class="text-gray-600 mt-2 mb-6">Dengan menekan tombol di bawah, Anda menyatakan bahwa seluruh dokumen asesmen di atas telah diperiksa dan dinyatakan VALID.</p>

            {{-- LOGIC TAMPILAN --}}
            @if($dataSertifikasi->status_validasi == 'valid')
                
                {{-- SKENARIO 1: SUDAH DIVALIDASI (HIJAU) --}}
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg inline-block shadow-sm">
                    <div class="flex items-center gap-3">
                         <div class="bg-green-500 text-white rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="text-left">
                            <strong class="font-bold text-lg">VALIDASI BERHASIL</strong>
                            <p class="text-sm">Data telah tersimpan dan status diperbarui.</p>
                        </div>
                    </div>
                </div>

            @else
                
                {{-- SKENARIO 2: BELUM DIVALIDASI (TOMBOL BIRU) --}}
                @if($level >= 100)
                    <form action="{{ route('validator.tracker.validasi', $dataSertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin data ini sudah benar?')" 
                            class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-indigo-700 hover:shadow-xl transition transform hover:-translate-y-1">
                            ✔ VALIDASI PEKERJAAN ASESOR
                        </button>
                    </form>
                @else
                    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded inline-block">
                        <strong>⏳ Belum Siap Validasi</strong>
                        <p class="text-sm">Asesor belum mengirimkan keputusan akhir (AK.02).</p>
                    </div>
                @endif

            @endif
        </div>
    </section>

</div>
@endsection