{{-- File: resources/views/pages/ia-01/tampilan_awal.blade.php --}}

@extends('layouts.wizard', ['currentStep' => 1])

@section('title', 'IA.01 - Step 1: Aktivitas')

@section('wizard-content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded shadow-sm">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ia01.storeCover', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" method="POST">
        @csrf

        {{-- HEADER --}}
        <div class="relative mb-8">
            <img src="{{ asset('images/bnsp_logo.png') }}" class="h-12 object-contain mb-4" alt="BNSP">
            <h1 class="text-2xl md:text-3xl font-bold leading-tight text-gray-900">
                FR.IA.01 - Ceklis Observasi Aktivitas di Tempat Kerja atau Tempat Kerja Simulasi
            </h1>
        </div>

        {{-- DATA UTAMA (REVISI LAYOUT: MENYATU DALAM 1 TABEL BESAR) --}}
        <div class="mb-8 border-2 border-gray-800 rounded-sm text-sm text-gray-900">

            {{-- BARIS 1: SKEMA SERTIFIKASI --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50 flex items-center">
                    Skema Sertifikasi
                </div>
                <div>
                    <div class="grid grid-cols-[100px_10px_1fr] border-b border-gray-800 p-2 items-center">
                        <div class="font-semibold text-gray-600">Judul</div>
                        <div>:</div>
                        <div class="font-bold uppercase">{{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-[100px_10px_1fr] p-2 items-center">
                        <div class="font-semibold text-gray-600">Tanggal</div>
                        <div>:</div>
                        <div>{{ now()->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- BARIS 2: TUK --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800 items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    TUK
                </div>
                <div class="p-2 pl-4 flex items-center gap-6">
                    @php $tuk_value = old('tuk', $data_sesi['tuk'] ?? ''); @endphp
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tuk" value="sewaktu" onclick="selectOnlyThis(this)"
                               class="tuk-checkbox rounded text-blue-600 focus:ring-blue-500"
                               {{ $tuk_value == 'sewaktu' ? 'checked' : '' }}>
                        <span class="ml-2">Sewaktu</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tuk" value="tempat_kerja" onclick="selectOnlyThis(this)"
                               class="tuk-checkbox rounded text-blue-600 focus:ring-blue-500"
                               {{ $tuk_value == 'tempat_kerja' ? 'checked' : '' }}>
                        <span class="ml-2">Tempat Kerja</span>
                    </label>
                </div>
            </div>

            {{-- BARIS 3: NAMA ASESOR --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800 items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    Nama Asesor
                </div>
                <div class="p-2 pl-4 font-medium">
                    {{ $sertifikasi->jadwal->asesor->name ?? 'Asesor' }}
                    <input type="hidden" name="nama_asesor" value="{{ $sertifikasi->jadwal->asesor->name ?? 'Asesor' }}">
                </div>
            </div>

            {{-- BARIS 4: NAMA ASESI --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800 items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    Nama Asesi
                </div>
                <div class="p-2 pl-4 font-medium">
                    {{ $sertifikasi->asesi->name ?? 'Asesi' }}
                </div>
            </div>

            {{-- BARIS 5: TANGGAL PELAKSANAAN --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    Tanggal
                </div>
                <div class="p-2 pl-4">
                    <input type="date" name="tanggal_asesmen"
                           class="border-0 border-b border-gray-400 focus:ring-0 p-0 text-gray-900 bg-transparent"
                           value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen'] ?? now()->format('Y-m-d')) }}">
                </div>
            </div>

        </div>
        {{-- END DATA UTAMA --}}

        {{-- PANDUAN --}}
        <div class="border-2 border-gray-800 mb-6 text-gray-900 text-sm rounded-sm">
            <div class="p-2 bg-gray-50 border-b-2 border-gray-800 font-bold">
                PANDUAN BAGI ASESOR
            </div>
            <div class="p-3">
                <ul class="list-disc list-inside space-y-1">
                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                    <li>Isilah standar industri atau tempat kerja.</li>
                    <li>Beri tanda centang (✓) pada kolom YA jika sesuai, atau Tidak bila sebaliknya.</li>
                    <li>Penilaian Lanjut diisi jika hasil belum dapat disimpulkan.</li>
                    <li>Isilah kolom KUK sesuai SKKNI.</li>
                </ul>
            </div>
        </div>

        {{-- TABEL UNIT KOMPETENSI --}}
        <div class="overflow-x-auto mb-10 border-2 border-gray-800 rounded-sm">
            <table class="w-full text-gray-900 text-sm">
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
                    @endphp

                    @forelse ($units as $index => $unit)
                        <tr class="border-b border-gray-800 last:border-b-0">
                            @if ($index === 0)
                                <td rowspan="{{ $totalUnits }}" class="p-4 bg-white font-bold border-r-2 border-gray-800 align-middle">
                                    {{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}
                                </td>
                            @endif
                            <td class="p-2 text-center border-r border-gray-800">{{ $index + 1 }}</td>
                            <td class="p-2 border-r border-gray-800">{{ $unit->kode_unit }}</td>
                            <td class="p-2">{{ $unit->judul_unit }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-4 bg-white font-bold border-r-2 border-gray-800 align-middle">-</td>
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
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-10 rounded-full shadow-lg transition">
                Mulai Asesmen
            </button>
        </div>
    </form>

    </div>

    <script>
        function selectOnlyThis(checkbox) {
            document.querySelectorAll('.tuk-checkbox').forEach(cb => {
                if (cb !== checkbox) cb.checked = false;
            });
        }
    </script>
@endsection
