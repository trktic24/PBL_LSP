{{--
  File: resources/views/pages/ia-01/template-aktivitas.blade.php
  (VERSI FIXED)
--}}

@extends('layouts.wizard', ['currentStep' => 1])

@section('title', 'IA.01 - Step 1: Aktivitas')

@section('wizard-content')

    @php
        if (!isset($unitKompetensi)) {
            $unitKompetensi = (object) [
                'skema_id' => 1,
                'urutan' => 1,
                'kode_unit' => 'J.620100.004.02',
                'judul_unit' => 'Menggunakan Struktur Data',
                'skema' => (object) ['nama_skema' => 'Junior Web Developer'],
            ];
            $kuks = [
                (object) ['id' => 1, 'nomor_kuk' => '1.1', 'pernyataan' => 'Mengidentifikasi konsep data dan struktur data sesuai dengan konteks permasalahan...', 'tipe' => 'aktivitas'],
                (object) ['id' => 2, 'nomor_kuk' => '1.2', 'pernyataan' => 'Membandingkan struktur data dan kelebihan dan kekurangannya untuk konteks permasalahan yang diselesaikan', 'tipe' => 'aktivitas'],
                (object) ['id' => 3, 'nomor_kuk' => '2.1', 'pernyataan' => 'Implementasi struktur data sesuai dengan bahasa pemrograman yang akan digunakan', 'tipe' => 'demonstrasi'],
                (object) ['id' => 4, 'nomor_kuk' => '2.2', 'pernyataan' => 'Menerapkan akses terhadap data dengan metode yang efisien sesuai bahasa pemrograman yang akan dipakai', 'tipe' => 'demonstrasi'],
            ];
            $data_sesi = [];
        }
    @endphp

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Oops! Ada yang salah:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="#" method="POST">
        @csrf

        {{-- Header Form --}}
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    FR.IA.01 - CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI
                </h1>
            </div>
            <img src="{{ asset('images/bnsp_logo.png') }}" alt="BNSP" class="h-16">
        </div>

        {{-- Info Detail Asesmen --}}
        <div class="grid grid-cols-2 gap-x-8 gap-y-4 mb-6">
            {{-- Kiri --}}
            <div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Skema Sertifikasi</label>
                    <input type="text"
                        value="{{ $unitKompetensi->skema->nama_skema ?? '' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm bg-gray-100"
                        readonly>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">TUK</label>
                    <div class="flex space-x-4 mt-2">
                        @php $tuk_value = old('tuk', $data_sesi['tuk'] ?? ''); @endphp
                        <label class="flex items-center">
                            <input type="checkbox" name="tuk" value="sewaktu"
                                class="rounded border-gray-400" {{ $tuk_value == 'sewaktu' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Sewaktu</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="tuk" value="tempat_kerja"
                                class="rounded border-gray-400" {{ $tuk_value == 'tempat_kerja' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Tempat Kerja</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="tuk" value="mandiri"
                                class="rounded border-gray-400" {{ $tuk_value == 'mandiri' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Mandiri</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Kanan --}}
            <div>
                <div class="mb-3">
                    <label for="asesor" class="block text-sm font-medium text-gray-700">Nama Asesor</label>
                    <input type="text" id="asesor" name="nama_asesor"
                        value="{{ old('nama_asesor', $data_sesi['nama_asesor'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Asesi</label>
                    <input type="text" value="Tatang Sidartang"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm bg-gray-100"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal_asesmen"
                        value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen'] ?? now()->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
            </div>
        </div>

        {{-- Detail Unit Kompetensi --}}
        <div class="bg-gray-100 p-4 rounded-lg mb-8">
            <h3 class="font-semibold text-gray-800">{{ $unitKompetensi->judul_unit }}</h3>
            <p class="text-sm text-gray-600">{{ $unitKompetensi->kode_unit }}</p>
            <p class="text-sm text-gray-600">(Element 1 dari 9)</p>
        </div>

        {{-- Table 1: Aktivitas --}}
        <h3 class="text-md font-semibold text-gray-800 mb-4">Mengidentifikasi konsep data dan struktur data</h3>
        <x-table>
            <x-slot name="thead">
                <tr>
                    <th class="p-3 text-left text-sm font-semibold tracking-wide w-3/5">Kriteria Unjuk Kerja</th>
                    <th class="p-3 text-left text-sm font-semibold tracking-wide w-1/5">Standar Industri atau Standar Kerja</th>
                    <th class="p-3 text-center text-sm font-semibold tracking-wide">Ya</th>
                    <th class="p-3 text-center text-sm font-semibold tracking-wide">Tidak</th>
                    <th class="p-3 text-left text-sm font-semibold tracking-wide">Penilaian Lanjut</th>
                </tr>
            </x-slot>

            @foreach ($kuks as $kuk)
                @if ($kuk->tipe == 'aktivitas')
                    @php $kuk_value = old('kuk.' . $kuk->id, $data_sesi['kuk'][$kuk->id] ?? ''); @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm text-gray-700">{{ $kuk->nomor_kuk }} {{ $kuk->pernyataan }}</td>
                        <td class="p-3 text-sm text-gray-700 align-top">[Standar diisi di sini]</td>
                        <td class="p-3 text-center align-top">
                            <input type="checkbox" name="kuk[{{ $kuk->id }}]" value="K"
                                class="rounded border-gray-400" {{ $kuk_value == 'K' ? 'checked' : '' }}>
                        </td>
                        <td class="p-3 text-center align-top">
                            <input type="checkbox" name="kuk[{{ $kuk->id }}]" value="BK"
                                class="rounded border-gray-400" {{ $kuk_value == 'BK' ? 'checked' : '' }}>
                        </td>
                        <td class="p-3 text-sm text-gray-700 align-top"></td>
                    </tr>
                @endif
            @endforeach
        </x-table>

        {{-- Table 2: Demonstrasi --}}
        <h3 class="text-md font-semibold text-gray-800 mb-4 mt-10">Menerapkan struktur data dan akses terhadap struktur data tersebut</h3>
        <x-table>
            <x-slot name="thead">
                <tr>
                    <th class="p-3 text-left text-sm font-semibold tracking-wide w-3/5">Kriteria Unjuk Kerja</th>
                    <th class="p-3 text-left text-sm font-semibold tracking-wide w-1/5">Benchmark (SOP / Spesifikasi Produk Industri)</th>
                    <th class="p-3 text-center text-sm font-semibold tracking-wide">K</th>
                    <th class="p-3 text-center text-sm font-semibold tracking-wide">BK</th>
                    <th class="p-3 text-left text-sm font-semibold tracking-wide">Penilaian Lanjut</th>
                </tr>
            </x-slot>

            @foreach ($kuks as $kuk)
                @if ($kuk->tipe == 'demonstrasi')
                    @php $kuk_value = old('kuk.' . $kuk->id, $data_sesi['kuk'][$kuk->id] ?? ''); @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm text-gray-700">{{ $kuk->nomor_kuk }} {{ $kuk->pernyataan }}</td>
                        <td class="p-3 text-sm text-gray-700 align-top">[Benchmark diisi di sini]</td>
                        <td class="p-3 text-center align-top">
                            <input type="checkbox" name="kuk[{{ $kuk->id }}]" value="K"
                                class="rounded border-gray-400" {{ $kuk_value == 'K' ? 'checked' : '' }}>
                        </td>
                        <td class="p-3 text-center align-top">
                            <input type="checkbox" name="kuk[{{ $kuk->id }}]" value="BK"
                                class="rounded border-gray-400" {{ $kuk_value == 'BK' ? 'checked' : '' }}>
                        </td>
                        <td class="p-3 text-sm text-gray-700 align-top"></td>
                    </tr>
                @endif
            @endforeach
        </x-table>

        {{-- Tombol Navigasi --}}
        <div class="flex justify-end mt-10">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Selanjutnya
            </button>
        </div>
    </form>
@endsection
