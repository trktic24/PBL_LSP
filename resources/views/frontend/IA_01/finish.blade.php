@extends('layouts.wizard', ['currentStep' => 99])

@section('title', 'IA.01 - Rekomendasi & Tanda Tangan')

@section('wizard-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
            </div>
        </div>

        {{-- Nama Asesor --}}
        <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
            <label class="font-bold">Nama Asesor</label>
            <div class="hidden md:block font-bold">:</div>
            <div>{{ $sertifikasi->asesor->name }}</div>
        </div>

        {{-- Nama Asesi --}}
        <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
            <label class="font-bold">Nama Asesi</label>
            <div class="hidden md:block font-bold">:</div>
            <div>{{ $sertifikasi->asesi->name }}</div>
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
    {{-- 2. REKOMENDASI (VERSI BARU - PROFESSIONAL) --}}
    {{-- ================================================================= --}}
    <div class="mb-10 p-6 rounded-lg border transition-colors duration-300
        {{ $rekomendasiSistem == 'belum_kompeten' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' }}">

        <h2 class="font-bold text-xl mb-4 text-gray-800">Rekomendasi Asesor:</h2>

        {{-- ALERT KALAU OTOMATIS BK --}}
        @if($rekomendasiSistem == 'belum_kompeten')
            <div class="flex items-start gap-3 text-red-800 bg-red-100 p-4 rounded-md mb-6 text-sm border border-red-200 shadow-sm">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="leading-relaxed">
                    <strong>Perhatian:</strong> Berdasarkan hasil checklist observasi, terdapat poin <strong>Belum Kompeten (BK)</strong>. <br>
                    Sesuai prosedur, rekomendasi akhir dikunci pada status <strong>Belum Kompeten</strong>.
                </div>
            </div>
        @endif

        {{-- PILIHAN KOMPETEN --}}
        <label class="flex items-center gap-3 mb-3 p-2 rounded hover:bg-white/50 transition
            {{ $rekomendasiSistem == 'belum_kompeten' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">

            <input type="checkbox" name="rekomendasi" value="kompeten"
                class="reco-check w-5 h-5 border-2 border-gray-400 rounded text-green-600 focus:ring-green-500"
                onclick="toggleRekomendasi(this, 'kompeten')"
                {{ $rekomendasiSistem == 'kompeten' ? 'checked' : '' }}
                {{ $rekomendasiSistem == 'belum_kompeten' ? 'disabled' : '' }}>

            <span class="font-semibold text-gray-800">
                Asesi <span class="text-green-700 font-bold">KOMPETEN</span> (Memenuhi seluruh kriteria unjuk kerja)
            </span>
        </label>

        {{-- PILIHAN BELUM KOMPETEN --}}
        <label class="flex items-center gap-3 p-2 rounded hover:bg-white/50 transition cursor-pointer">
            <input type="checkbox" name="rekomendasi" value="belum_kompeten"
                class="reco-check w-5 h-5 border-2 border-gray-400 rounded text-red-600 focus:ring-red-500"
                onclick="toggleRekomendasi(this, 'belum_kompeten')"
                {{ $rekomendasiSistem == 'belum_kompeten' ? 'checked' : '' }}>

            <span class="font-semibold text-gray-800">
                Asesi <span class="text-red-600 font-bold">BELUM KOMPETEN</span> (Belum memenuhi seluruh kriteria)
            </span>
        </label>

        {{-- Detail BK (FORM INPUT) --}}
        <div id="bk-details" class="ml-8 mt-4 space-y-4 {{ $rekomendasiSistem == 'belum_kompeten' ? '' : 'hidden' }}">

            {{-- Note Kecil --}}
            <div class="text-sm text-gray-600 italic border-l-4 border-gray-400 pl-3 py-1">
                Mohon lengkapi rincian aspek yang belum terpenuhi di bawah ini sebagai bahan evaluasi:
            </div>

            {{-- Kelompok Pekerjaan --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm text-gray-700">Kelompok Pekerjaan</label>
                <input type="text" name="bk_kelompok"
                       class="w-full border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100 text-gray-600 cursor-not-allowed shadow-sm"
                       value="{{ $kelompok->nama_kelompok_pekerjaan }}" readonly>
            </div>

            {{-- Unit Kompetensi --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm text-gray-700">Unit Kompetensi</label>
                <input type="text" name="bk_unit"
                       class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:border-red-500 focus:ring-red-200 shadow-sm"
                       placeholder="Contoh: Menyiapkan Peralatan (Kode Unit)" value="{{ old('bk_unit') }}">
            </div>

            {{-- Elemen --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm text-gray-700">Elemen</label>
                <input type="text" name="bk_elemen"
                       class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:border-red-500 focus:ring-red-200 shadow-sm"
                       placeholder="Sebutkan elemen kompetensi yang belum terpenuhi..." value="{{ old('bk_unit') }}">
            </div>

            {{-- KUK --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                <label class="font-bold text-sm text-gray-700">No. KUK</label>
                <input type="text" name="bk_kuk"
                       class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:border-red-500 focus:ring-red-200 shadow-sm"
                       placeholder="Contoh: 1.2, 2.1 (Pisahkan dengan koma)" value="{{ old('bk_unit') }}">
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- TANDA TANGAN --}}
    {{-- ================================================================= --}}
    <div class="mb-10">
        <h2 class="font-bold text-xl mb-6">Tanda Tangan:</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            {{-- ASESI --}}
            <div>
                <p class="font-bold">Asesi</p>
                <p class="font-bold mb-4 text-gray-800">{{ now()->format('d-m-Y') }} (tanggal)</p>

                <div class="h-32 flex items-end mb-2">
                    @if($sertifikasi->asesi->ttd_path)
                        <img src="{{ asset($sertifikasi->asesi->ttd_path) }}" class="h-28 object-contain">
                    @else
                        <svg width="150" height="80" viewBox="0 0 200 100">
                            <path d="M10,80 Q50,10 90,80 T180,80" stroke="black" fill="transparent" stroke-width="2"/>
                        </svg>
                    @endif
                </div>

                <p class="font-medium">{{ $sertifikasi->asesi->name }}</p>
            </div>

            {{-- ASESOR --}}
            <div>
                <p class="font-bold">Asesor</p>
                <div class="mb-4">
                    <input type="text" value="{{ now()->format('d-m-Y') }}"
                           class="border border-gray-400 px-2 py-1 text-sm rounded w-40 text-center" readonly>
                </div>

                <div class="border border-gray-800 rounded-lg p-4 h-40 flex items-center justify-center">
                    @if($sertifikasi->asesor->ttd_path)
                        <img src="{{ asset($sertifikasi->asesor->ttd_path) }}" class="h-full object-contain">
                    @else
                        <svg width="150" height="80" viewBox="0 0 200 100">
                            <path d="M20,50 C50,20 80,80 110,50 S170,20 190,80" stroke="black" fill="transparent" stroke-width="2"/>
                        </svg>
                    @endif
                </div>

                <p class="font-medium mt-2 text-center">{{ $sertifikasi->asesor->name }}</p>
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
</div>

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
