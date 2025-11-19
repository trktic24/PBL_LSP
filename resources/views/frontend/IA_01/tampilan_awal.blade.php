{{-- File: resources/views/pages/ia-01/tampilan_awal.blade.php --}}

@extends('layouts.wizard', ['currentStep' => 1])

@section('title', 'IA.01 - Step 1: Aktivitas')

@section('wizard-content')

    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded shadow-sm" role="alert">
            <p class="font-bold">Ada yang belum diisi:</p>
            <ul class="list-disc list-inside mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM ACTION: Pake ID Skema --}}
    <form action="{{ route('ia01.storeCover', ['skema_id' => $skema->id_skema]) }}" method="POST">
        @csrf

        {{-- 1. HEADER: Logo & Judul --}}
        <div class="relative mb-8">
            <div class="mb-4">
                <img src="{{ asset('images/bnsp_logo.png') }}" alt="BNSP" class="h-12 object-contain">
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                FR.IA.01 - Ceklis Observasi Aktivitas di Tempat Kerja atau Tempat Kerja Simulasi
            </h1>
        </div>

        {{-- 2. DATA UTAMA --}}
        <div class="mb-8 space-y-6">

            {{-- A. Info Skema --}}
            <div class="border-2 border-gray-800 rounded-sm overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="bg-white p-4 md:w-1/3 font-bold text-gray-900 flex items-center border-b md:border-b-0 md:border-r-2 border-gray-800">
                        Skema Sertifikasi
                    </div>
                    <div class="flex-1 bg-white">
                        <div class="flex border-b-2 border-gray-800">
                            <div class="w-24 p-2 pl-4 text-sm font-semibold text-gray-600">Judul</div>
                            {{-- FIX: Ambil Nama Kelompok langsung dari variable $kelompok --}}
                            <div class="p-2 text-gray-800 font-medium">: {{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-24 p-2 pl-4 text-sm font-semibold text-gray-600">Tanggal</div>
                            <div class="p-2 text-gray-800 font-medium">: {{ now()->format('d-m-Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- B. Input Form --}}
            <div class="space-y-4 text-gray-800 pl-1">

                {{-- Baris TUK (CHECKBOX STYLE) --}}
                <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-start md:items-center">
                    <label class="font-bold">TUK</label>
                    <div class="hidden md:block font-bold">:</div>
                    <div class="flex flex-wrap gap-6">
                        @php $tuk_value = old('tuk', $data_sesi['tuk'] ?? ''); @endphp

                        {{-- Checkbox Sewaktu --}}
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tuk" value="sewaktu"
                                   class="tuk-checkbox form-checkbox h-5 w-5 text-gray-800 border-gray-400 rounded focus:ring-gray-500 transition"
                                   onclick="selectOnlyThis(this)"
                                   {{ $tuk_value == 'sewaktu' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm group-hover:text-blue-600 transition">Sewaktu</span>
                        </label>

                        {{-- Checkbox Tempat Kerja --}}
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tuk" value="tempat_kerja"
                                   class="tuk-checkbox form-checkbox h-5 w-5 text-gray-800 border-gray-400 rounded focus:ring-gray-500 transition"
                                   onclick="selectOnlyThis(this)"
                                   {{ $tuk_value == 'tempat_kerja' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm group-hover:text-blue-600 transition">Tempat Kerja</span>
                        </label>

                        {{-- Checkbox Mandiri --}}
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tuk" value="mandiri"
                                   class="tuk-checkbox form-checkbox h-5 w-5 text-gray-800 border-gray-400 rounded focus:ring-gray-500 transition"
                                   onclick="selectOnlyThis(this)"
                                   {{ $tuk_value == 'mandiri' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm group-hover:text-blue-600 transition">Mandiri</span>
                        </label>
                    </div>
                </div>

                {{-- Baris Nama Asesor --}}
                <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                    <label class="font-bold" for="asesor">Nama Asesor</label>
                    <div class="hidden md:block font-bold">:</div>
                    <input type="text" id="asesor" name="nama_asesor"
                           value="{{ old('nama_asesor', $data_sesi['nama_asesor'] ?? '') }}"
                           class="w-full max-w-md border-0 border-b border-gray-400 focus:border-blue-500 focus:ring-0 px-0 py-1 text-gray-800 placeholder-gray-400 bg-transparent"
                           placeholder="Masukkan nama asesor">
                </div>

                {{-- Baris Nama Asesi --}}
                <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                    <label class="font-bold">Nama Asesi</label>
                    <div class="hidden md:block font-bold">:</div>
                    <div class="text-gray-800 font-medium py-1">
                        {{ auth()->user()->name ?? 'Sucipto Kripsi' }}
                    </div>
                </div>

                {{-- Baris Tanggal --}}
                <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                    <label class="font-bold" for="tanggal">Tanggal</label>
                    <div class="hidden md:block font-bold">:</div>
                    <input type="date" id="tanggal" name="tanggal_asesmen"
                           value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen'] ?? now()->format('Y-m-d')) }}"
                           class="w-full max-w-xs border-0 border-b border-gray-400 focus:border-blue-500 focus:ring-0 px-0 py-1 text-gray-800 bg-gray-100 cursor-not-allowed"
                           readonly>
                </div>
            </div>
        </div>

        {{-- 3. PANDUAN --}}
        <div class="mb-6 border-2 border-gray-800 text-gray-900 text-sm">
            <div class="p-2 font-bold border-b-2 border-gray-800 bg-gray-50">
                PANDUAN BAGI ASESOR
            </div>
            <div class="p-3">
                <ul class="list-disc list-inside space-y-1 leading-relaxed">
                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                    <li>Isilah standar industri atau tempat kerja.</li>
                    <li>Beri tanda centang (<span class="font-sans">✓</span>) pada kolom "YA" jika Anda yakin asesi dapat melakukan/mendemonstrasikan tugas sesuai KUK, atau centang pada kolom "Tidak" bila sebaliknya.</li>
                    <li>Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.</li>
                    <li>Isilah kolom KUK sesuai dengan Unit Kompetensi/SKKNI.</li>
                </ul>
            </div>
        </div>

        {{-- 4. TABEL DAFTAR UNIT --}}
        <div class="mb-8 overflow-x-auto">
            <table class="w-full border-2 border-gray-800 text-left text-gray-900 text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-800 font-bold">
                        <th class="p-2 border-r-2 border-gray-800 w-1/4 text-center">Kelompok Pekerjaan</th>
                        <th class="p-2 border-r border-gray-800 w-12 text-center">No.</th>
                        <th class="p-2 border-r border-gray-800 w-32 text-center">Kode Unit</th>
                        <th class="p-2 text-center">Judul Unit</th>
                    </tr>
                </thead>
                <tbody class="align-top">
                    @php
                        // FIX: Ambil units langsung dari $kelompok (bukan dari $unitKompetensi)
                        // Pake nama relasi 'unitKompetensis' sesuai model
                        $units = $kelompok->unitKompetensis ?? [];
                        $totalUnits = count($units) > 0 ? count($units) : 1;
                        $skemaTitle = $kelompok->nama_kelompok_pekerjaan ?? '-';
                    @endphp

                    @forelse ($units as $index => $unit)
                        <tr class="border-b border-gray-800 last:border-b-0">
                            @if ($index === 0)
                                <td rowspan="{{ $totalUnits }}" class="p-4 border-r-2 border-gray-800 font-bold align-middle bg-white">
                                    {{ $skemaTitle }}
                                </td>
                            @endif
                            <td class="p-2 border-r border-gray-800 text-center">{{ $index + 1 }}</td>
                            <td class="p-2 border-r border-gray-800">{{ $unit->kode_unit }}</td>
                            <td class="p-2">{{ $unit->judul_unit }}</td>
                        </tr>
                    @empty
                        <tr class="border-b border-gray-800">
                            <td class="p-4 border-r-2 border-gray-800 font-bold align-middle bg-white">{{ $skemaTitle }}</td>
                            <td class="p-2 border-r border-gray-800 text-center">-</td>
                            <td class="p-2 border-r border-gray-800">-</td>
                            <td class="p-2">Belum ada unit kompetensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 5. TOMBOL MULAI --}}
        <div class="flex justify-end mt-12">
            <button type="submit"
                    class="bg-[#3b82f6] hover:bg-blue-600 text-white font-semibold py-2 px-10 rounded-full shadow-lg transition duration-200">
                Mulai Asesmen
            </button>
        </div>
    </form>

    <script>
        function selectOnlyThis(checkbox) {
            var checkboxes = document.getElementsByClassName('tuk-checkbox');
            Array.prototype.forEach.call(checkboxes, function(item) {
                if (item !== checkbox) item.checked = false;
            });
        }
    </script>

@endsection
