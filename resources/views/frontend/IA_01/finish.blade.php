@extends('layouts.wizard', ['currentStep' => 99])

@section('title', 'IA.01 - Rekomendasi & Tanda Tangan')

@section('wizard-content')

<form action="{{ route('ia01.storeFinish', ['skema_id' => $skema->id_skema]) }}" method="POST">
    @csrf

    {{-- ================================================================= --}}
    {{-- HEADER LOGO & JUDUL --}}
    {{-- ================================================================= --}}
    <div class="relative mb-8">
        <div class="mb-4">
            <img src="{{ asset('images/bnsp_logo.png') }}"
                 alt="BNSP"
                 class="h-12 object-contain max-w-full">
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
            FR.IA.01 - Ceklis Observasi Aktivitas di Tempat Kerja atau Tempat Kerja Simulasi
        </h1>
    </div>

    {{-- ================================================================= --}}
    {{-- INFO DATA DIRI (TUK, ASESOR, ASESI) --}}
    {{-- ================================================================= --}}
    <div class="mb-10 space-y-3 text-gray-800 border-b pb-8">

        {{-- TUK --}}
        <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
            <label class="font-bold">TUK</label>
            <div class="hidden md:block font-bold">:</div>
            <div class="flex flex-wrap gap-6">
                <div class="flex items-center">
                    <div class="w-4 h-4 border border-gray-400 mr-2 flex items-center justify-center">
                        @if(($data_sesi['tuk'] ?? '') == 'sewaktu') <div class="w-2 h-2 bg-black"></div> @endif
                    </div> Sewaktu
                </div>

                <div class="flex items-center">
                    <div class="w-4 h-4 border border-gray-400 mr-2 flex items-center justify-center">
                        @if(($data_sesi['tuk'] ?? '') == 'tempat_kerja') <div class="w-2 h-2 bg-black"></div> @endif
                    </div> Tempat Kerja
                </div>

                <div class="flex items-center">
                    <div class="w-4 h-4 border border-gray-400 mr-2 flex items-center justify-center">
                        @if(($data_sesi['tuk'] ?? '') == 'mandiri') <div class="w-2 h-2 bg-black"></div> @endif
                    </div> Mandiri
                </div>
            </div>
        </div>

        {{-- Nama Asesor --}}
        <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
            <label class="font-bold">Nama Asesor</label>
            <div class="hidden md:block font-bold">:</div>
            <div>{{ $data_sesi['nama_asesor'] ?? $asesor?->name }}</div>
        </div>

        {{-- Nama Asesi --}}
        <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
            <label class="font-bold">Nama Asesi</label>
            <div class="hidden md:block font-bold">:</div>
            <div>{{ $asesi?->name }}</div>
        </div>

        {{-- Tanggal --}}
        <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
            <label class="font-bold">Tanggal</label>
            <div class="hidden md:block font-bold">:</div>
            <div>{{ \Carbon\Carbon::parse($data_sesi['tanggal_asesmen'] ?? now())->format('d-m-Y') }}</div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 1. UMPAN BALIK --}}
    {{-- ================================================================= --}}
    <div class="mb-8">
        <h2 class="font-bold text-xl mb-3">Umpan Balik Untuk Asesi:</h2>
        <textarea name="umpan_balik" rows="4"
            class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:ring-0 resize-none bg-gray-50"
            placeholder="Tuliskan catatan atau feedback..."></textarea>
    </div>

    {{-- ================================================================= --}}
    {{-- 2. REKOMENDASI --}}
    {{-- ================================================================= --}}
    <div class="mb-10">
        <h2 class="font-bold text-xl mb-4">Rekomendasi:</h2>

        <label class="flex items-start gap-3 mb-3 cursor-pointer">
            <input type="checkbox" name="rekomendasi" value="kompeten"
                class="reco-check w-6 h-6 border-2 border-gray-400 rounded text-green-600 cursor-pointer"
                onclick="toggleRekomendasi(this, 'kompeten')"
                {{ $rekomendasiSistem == 'kompeten' ? 'checked' : '' }}>
            <span class="font-bold text-gray-800">Asesi telah memenuhi seluruh kriteria unjuk kerja (KOMPETEN)</span>
        </label>

        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="rekomendasi" value="belum_kompeten"
                class="reco-check w-6 h-6 border-2 border-gray-400 rounded text-red-600 cursor-pointer"
                onclick="toggleRekomendasi(this, 'belum_kompeten')"
                {{ $rekomendasiSistem == 'belum_kompeten' ? 'checked' : '' }}>
            <span class="font-bold text-gray-800">Asesi belum memenuhi seluruh kriteria (BELUM KOMPETEN)</span>
        </label>

        {{-- Detail BK --}}
        <div id="bk-details" class="ml-9 mt-4 space-y-3 {{ $rekomendasiSistem == 'belum_kompeten' ? '' : 'hidden' }}">

            {{-- Responsivitas diperbaiki --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm">Kelompok Pekerjaan</label>
                <input type="text" class="w-full border-gray-300 rounded px-2 py-1 text-sm"
                       value="{{ $kelompok->nama_kelompok_pekerjaan }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm">Unit Kompetensi</label>
                <input type="text" class="w-full border-gray-300 rounded px-2 py-1 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm">Elemen</label>
                <input type="text" class="w-full border-gray-300 rounded px-2 py-1 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm">KUK</label>
                <input type="text" class="w-full border-gray-300 rounded px-2 py-1 text-sm">
            </div>
        </div>
    </div>

    <div class="mb-10">
    <h2 class="font-bold text-xl mb-6">Tanda Tangan:</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

        {{-- ASESI --}}
        <div>
            <p class="font-bold">Asesi</p>
            <p class="font-bold mb-4 text-gray-800">{{ now()->format('d-m-Y') }} (tanggal)</p>

            <div class="h-32 flex items-end mb-2">
                @if($asesi->ttd_path ?? false)
                    <img src="{{ asset($asesi->ttd_path) }}" class="h-28 object-contain">
                @else
                    <svg width="150" height="80" viewBox="0 0 200 100">
                        <path d="M10,80 Q50,10 90,80 T180,80"
                              stroke="black" fill="transparent" stroke-width="2"/>
                    </svg>
                @endif
            </div>

            <p class="font-medium">{{ $asesi?->name }}</p>
        </div>

        {{-- ASESOR --}}
        <div>
            <p class="font-bold">Asesor</p>
            <div class="mb-4">
                <input type="text" value="{{ now()->format('d-m-Y') }}"
                       class="border border-gray-400 px-2 py-1 text-sm rounded w-40 text-center" readonly>
            </div>

            <div class="border border-gray-800 rounded-lg p-4 h-40 flex items-center justify-center">
                @if($asesor->ttd_path ?? false)
                    <img src="{{ asset($asesor->ttd_path) }}" class="h-full object-contain">
                @else
                    <svg width="150" height="80" viewBox="0 0 200 100">
                        <path d="M20,50 C50,20 80,80 110,50 S170,20 190,80"
                              stroke="black" fill="transparent" stroke-width="2"/>
                    </svg>
                @endif
            </div>

            <p class="font-medium mt-2 text-center">{{ $data_sesi['nama_asesor'] ?? $asesor?->name }}</p>
        </div>

    </div>

    <p class="text-red-500 text-sm mt-4">* Tanda Tangan di sini (Simulasi)</p>
</div>


    {{-- ================================================================= --}}
    {{-- NAVIGASI --}}
    {{-- ================================================================= --}}
    <div class="flex justify-between items-center pt-6 border-t mt-8">
        <button type="button" onclick="history.back()"
            class="px-8 py-2 border border-blue-400 text-blue-500 font-bold rounded-full hover:bg-blue-50 transition">
            Sebelumnya
        </button>

        <button type="submit"
            class="px-12 py-2 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition transform hover:-translate-y-0.5">
            Kirim
        </button>
    </div>
</form>

<script>
    function toggleRekomendasi(checkbox, val) {
        document.querySelectorAll('.reco-check').forEach(el => {
            if(el !== checkbox) el.checked = false;
        });
        if(!checkbox.checked) checkbox.checked = true;

        const details = document.getElementById('bk-details');
        val === 'belum_kompeten'
            ? details.classList.remove('hidden')
            : details.classList.add('hidden');
    }
</script>

@endsection
