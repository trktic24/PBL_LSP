{{-- File: resources/views/pages/ia-01/tampilan_awal.blade.php --}}

@extends('layouts.wizard', ['currentStep' => 1])

@section('title', 'IA.01 - Step 1: Aktivitas')

@section('wizard-content')

    {{-- Error Validasi --}}
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

    <form action="{{ route('ia01.storeCover', ['skema_id' => $skema->id_skema]) }}" method="POST">
        @csrf

        {{-- HEADER --}}
        <div class="relative mb-10">
            <img src="{{ asset('images/bnsp_logo.png') }}" class="h-12 object-contain mb-4" alt="BNSP">
            <h1 class="text-2xl md:text-3xl font-bold leading-tight text-gray-900">
                FR.IA.01 - Ceklis Observasi Aktivitas di Tempat Kerja atau Tempat Kerja Simulasi
            </h1>
        </div>

        {{-- DATA UTAMA --}}
        <div class="mb-8 space-y-6">

            {{-- BOX SKEMA --}}
            <div class="border-2 border-gray-800 rounded-sm overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="bg-white p-4 md:w-1/3 font-bold text-gray-900 flex items-center border-b md:border-b-0 md:border-r-2 border-gray-800">
                        Skema Sertifikasi
                    </div>

                    <div class="flex-1 bg-white">
                        <div class="flex border-b-2 border-gray-800">
                            <div class="w-28 p-2 pl-4 text-sm font-semibold text-gray-600">Judul</div>
                            <div class="p-2 font-medium text-gray-800">
                                : {{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-28 p-2 pl-4 text-sm font-semibold text-gray-600">Tanggal</div>
                            <div class="p-2 font-medium text-gray-800">
                                : {{ now()->format('d-m-Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM INPUT --}}
            <div class="space-y-4 text-gray-800 pl-1">

                {{-- TUK --}}
                <div class="grid grid-cols-1 md:grid-cols-3 md:[grid-template-columns:200px_20px_1fr] gap-y-2">
                    <label class="font-bold">TUK</label>
                    <div class="hidden md:block font-bold">:</div>

                    <div class="flex flex-wrap gap-6">
                        @php $tuk_value = old('tuk', $data_sesi['tuk'] ?? ''); @endphp

                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tuk" value="sewaktu"
                                   onclick="selectOnlyThis(this)"
                                   class="tuk-checkbox h-5 w-5 border-gray-400 rounded text-gray-800 focus:ring-gray-500"
                                   {{ $tuk_value == 'sewaktu' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm group-hover:text-blue-600">Sewaktu</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tuk" value="tempat_kerja"
                                   onclick="selectOnlyThis(this)"
                                   class="tuk-checkbox h-5 w-5 border-gray-400 rounded text-gray-800 focus:ring-gray-500"
                                   {{ $tuk_value == 'tempat_kerja' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm group-hover:text-blue-600">Tempat Kerja</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tuk" value="mandiri"
                                   onclick="selectOnlyThis(this)"
                                   class="tuk-checkbox h-5 w-5 border-gray-400 rounded text-gray-800 focus:ring-gray-500"
                                   {{ $tuk_value == 'mandiri' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm group-hover:text-blue-600">Mandiri</span>
                        </label>
                    </div>
                </div>

                {{-- Nama Asesor --}}
                <div class="grid grid-cols-1 md:grid-cols-3 md:[grid-template-columns:200px_20px_1fr] items-center">
                    <label for="asesor" class="font-bold">Nama Asesor</label>
                    <div class="hidden md:block font-bold">:</div>
                    <input type="text" id="asesor" name="nama_asesor"
                           class="w-full max-w-md border-0 border-b border-gray-400 bg-transparent py-1 px-0 text-gray-800 focus:ring-0 focus:border-blue-500"
                           placeholder="Masukkan nama asesor"
                           value="{{ old('nama_asesor', $data_sesi['nama_asesor'] ?? '') }}">
                </div>

                {{-- Nama Asesi --}}
                <div class="grid grid-cols-1 md:grid-cols-3 md:[grid-template-columns:200px_20px_1fr] items-center">
                    <label class="font-bold">Nama Asesi</label>
                    <div class="hidden md:block font-bold">:</div>
                    <div class="py-1 font-medium text-gray-800">
                        {{ auth()->user()->name ?? 'User' }}
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="grid grid-cols-1 md:grid-cols-3 md:[grid-template-columns:200px_20px_1fr] items-center">
                    <label for="tanggal" class="font-bold">Tanggal</label>
                    <div class="hidden md:block font-bold">:</div>
                    <input type="date" id="tanggal" name="tanggal_asesmen"
                           class="w-full max-w-xs border-0 border-b border-gray-400 bg-gray-100 py-1 px-0 text-gray-800 cursor-not-allowed focus:ring-0"
                           value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen'] ?? now()->format('Y-m-d')) }}"
                           readonly>
                </div>

            </div>
        </div>

        {{-- PANDUAN --}}
        <div class="border-2 border-gray-800 mb-6 text-gray-900 text-sm">
            <div class="p-2 bg-gray-50 border-b-2 border-gray-800 font-bold">
                PANDUAN BAGI ASESOR
            </div>
            <div class="p-3">
                <ul class="list-disc list-inside space-y-1 leading-relaxed">
                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                    <li>Isilah standar industri atau tempat kerja.</li>
                    <li>Beri tanda centang (✓) pada kolom YA jika sesuai, atau Tidak bila sebaliknya.</li>
                    <li>Penilaian Lanjut diisi jika hasil belum dapat disimpulkan.</li>
                    <li>Isilah kolom KUK sesuai SKKNI.</li>
                </ul>
            </div>
        </div>

        {{-- TABEL UNIT --}}
        <div class="overflow-x-auto mb-10">
            <table class="w-full border-2 border-gray-800 text-gray-900 text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-800 font-bold">
                        <th class="p-2 text-center border-r-2 border-gray-800 w-1/4">Kelompok Pekerjaan</th>
                        <th class="p-2 text-center border-r border-gray-800 w-12">No.</th>
                        <th class="p-2 text-center border-r border-gray-800 w-32">Kode Unit</th>
                        <th class="p-2 text-center">Judul Unit</th>
                    </tr>
                </thead>

                <tbody class="align-top">
                    @php
                        $units = $kelompok->unitKompetensis ?? [];
                        $totalUnits = max(count($units), 1);
                        $skemaTitle = $kelompok->nama_kelompok_pekerjaan ?? '-';
                    @endphp

                    @forelse ($units as $index => $unit)
                        <tr class="border-b border-gray-800">
                            @if ($index === 0)
                                <td rowspan="{{ $totalUnits }}" class="p-4 bg-white font-bold border-r-2 border-gray-800 align-middle">
                                    {{ $skemaTitle }}
                                </td>
                            @endif

                            <td class="p-2 text-center border-r border-gray-800">{{ $index + 1 }}</td>
                            <td class="p-2 border-r border-gray-800">{{ $unit->kode_unit }}</td>
                            <td class="p-2">{{ $unit->judul_unit }}</td>
                        </tr>
                    @empty
                        <tr class="border-b border-gray-800">
                            <td class="p-4 bg-white font-bold border-r-2 border-gray-800">{{ $skemaTitle }}</td>
                            <td class="p-2 text-center border-r border-gray-800">-</td>
                            <td class="p-2 border-r border-gray-800">-</td>
                            <td class="p-2">Belum ada unit kompetensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end mt-12">
            <button type="submit"
                class="bg-[#3b82f6] hover:bg-blue-600 text-white font-semibold py-2 px-10 rounded-full shadow-lg transition">
                Mulai Asesmen
            </button>
        </div>
    </form>

    <script>
        function selectOnlyThis(checkbox) {
            document.querySelectorAll('.tuk-checkbox').forEach(cb => {
                if (cb !== checkbox) cb.checked = false;
            });
        }
    </script>

@endsection
