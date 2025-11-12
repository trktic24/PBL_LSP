{{--
  File: resources/views/pages/ia-01/template-demonstrasi.blade.php

  CATATAN:
  File ini nanti akan dapat variabel $unitKompetensi, $kuks, $data_sesi,
  dan $totalSteps dari controller.
--}}

{{-- $currentStep-nya dinamis, diambil dari $unitKompetensi->urutan --}}
@extends('layouts.wizard', ['currentStep' => $unitKompetensi->urutan ?? 2])

@section('title', 'IA.01 - Step ' . ($unitKompetensi->urutan ?? 2))

@section('wizard-content')

    {{-- Nanti controller akan ngirim $unitKompetensi.skema_id dan $unitKompetensi->urutan --}}
    @php
        // ---- INI DATA DUMMY (HAPUS NANTI KALO UDAH ADA CONTROLLER) ----
        if (!isset($unitKompetensi)) {
            $unitKompetensi = (object) [
                'skema_id' => 1,
                'urutan' => 2,
                'kode_unit' => 'J.620100.005.02',
                'judul_unit' => 'Mengimplementasikan User Interface',
                'skema' => (object) ['nama_skema' => 'Junior Web Developer'],
            ];
            $kuks = [
                (object) ['id' => 11, 'nomor_kuk' => '1.1', 'pernyataan' => 'Mengidentifikasi kebutuhan user interface untuk aplikasi'],
                (object) ['id' => 12, 'nomor_kuk' => '1.2', 'pernyataan' => 'Mengidentifikasi komponen user interface-dialog sesuai konteks dan alur proses'],
                (object) ['id' => 13, 'nomor_kuk' => '1.3', 'pernyataan' => 'Menjelaskan ukuran dan bentuk komponen user interface dialog'],
                (object) ['id' => 14, 'nomor_kuk' => '1.4', 'pernyataan' => 'Mempersiapkan Simulasi (mockup) UI/dialog yang akan dibuat'],
                (object) ['id' => 21, 'nomor_kuk' => '2.1', 'pernyataan' => 'Menerapkan Menu (pull-down) sesuai dengan rancangan program'],
            ];
            $data_sesi = [];
            $totalSteps = 5; // Asumsi total ada 5 step
        }
        // ---- BATAS DATA DUMMY ----
    @endphp

    {{-- Tampilkan error validasi (Penting untuk backend nanti) --}}
    @if ($errors->any())
        <div class_red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Oops! Ada yang salah:</p>
            <ul class_disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="#" method="POST">
        @csrf

        {{-- Header Form (Dinamis) --}}
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Cek Observasi Demonstrasi / Praktik</h1>
                {{-- Judul dan Kode Unit diambil dari DB --}}
                <p class="text-gray-600">{{ $unitKompetensi->judul_unit }} ({{ $unitKompetensi->kode_unit }})</p>
            </div>
            <img src="{{ asset('images/bnsp_logo.png') }}" alt="BNSP" class="h-16">
        </div>

        {{--
          Di sini kita bisa tambahkan logika untuk mengelompokkan KUK
          Misal KUK 1.1, 1.2, 1.3 jadi 1 tabel. KUK 2.1, 2.2 jadi tabel lain.
          Untuk sementara, kita gabung semua jadi 1 tabel.
        --}}

        <h3 class="text-md font-semibold text-gray-800 mb-4">Unit Kompetensi: {{ $unitKompetensi->judul_unit }}</h3>
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

            {{-- KUK-nya di-looping dari DB --}}
            @foreach ($kuks as $kuk)
                @php $kuk_value = old('kuk.'.$kuk->id, $data_sesi['kuk'][$kuk->id] ?? ''); @endphp
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 text-sm text-gray-700">
                        {{ $kuk->nomor_kuk }} {{ $kuk->pernyataan }}
                    </td>
                    <td class="p-3 text-sm text-gray-700 align-top">[Benchmark diisi di sini]</td>
                    <td class="p-3 text-center align-top">
                        {{-- 'name' input-nya dinamis --}}
                        <input type="radio" name="kuk[{{ $kuk->id }}]" value="K" class="rounded border-gray-400" {{ $kuk_value == 'K' ? 'checked' : '' }}>
                    </td>
                    <td class="p-3 text-center align-top">
                        <input type="radio" name="kuk[{{ $kuk->id }}]" value="BK" class_rounded border-gray-400" {{ $kuk_value == 'BK' ? 'checked' : '' }}>
                    </td>
                    <td class="p-3 text-sm text-gray-700 align-top"></td>
                </tr>
            @endforeach
        </x-table>

        {{-- Tombol Navigasi (Dinamis) --}}
        <div class="flex justify-between mt-10">
            {{-- Tombol 'Sebelumnya' cuma muncul kalo bukan step 1 --}}
            @if ($unitKompetensi->urutan > 1)
                <a href="#" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                    Sebelumnya
                </a>
            @else
                {{-- Placeholder biar tombol 'Selanjutnya' tetap di kanan --}}
                <span></span>
            @endif

            {{-- Cek apa ini step terakhir --}}
            @if ($unitKompetensi->urutan == $totalSteps)
                {{-- Ini step terakhir, ganti jadi tombol 'Simpan' --}}
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Selesai & Simpan
                </button>
            @else
                {{-- Ini belum step terakhir --}}
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Selanjutnya
                </button>
            @endif
        </div>
    </form>
@endsection
