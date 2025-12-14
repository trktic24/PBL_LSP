@extends('layouts.app-sidebar')

@section('content')
    {{-- Style Internal --}}
    <style>
        .custom-radio:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        textarea:focus, input:focus {
            outline: none;
            --tw-ring-color: #3b82f6;
        }
    </style>

    <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">

        {{-- HEADER --}}
        <x-header_form.header_form title="FR.AK.03. UMPAN BALIK DAN CATATAN ASESMEN" />
        <br>

        {{-- FORM WRAPPER --}}
        <form action="#" method="POST">
            @csrf

            {{-- 1. IDENTITAS SKEMA (Manual HTML sesuai Source 2) --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Identitas Skema & Jadwal</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        {{-- Header --}}
                        <tr>
                            <td colspan="3" class="font-bold text-gray-900 pb-2">
                                Skema Sertifikasi (KKNI/Okupasi/Klaster)
                            </td>
                        </tr>
                        {{-- Judul --}}
                        <tr>
                            <td class="w-40 font-bold text-gray-700 py-2 align-top">Judul</td>
                            <td class="w-4 py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium"></td>
                        </tr>
                        {{-- Nomor --}}
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Nomor</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium"></td>
                        </tr>
                        {{-- TUK --}}
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">TUK</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2">
                                <div class="flex flex-wrap gap-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="tuk" value="sewaktu" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-700">Sewaktu</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="tuk" value="tempat_kerja" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-700">Tempat Kerja</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="tuk" value="mandiri" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-700">Mandiri</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        {{-- Asesor & Asesi --}}
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Nama Asesor</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">{{ Auth::user()->name ?? 'Nama Asesor' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Nama Asesi</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">Peserta Uji</td>
                        </tr>
                        {{-- Tanggal Spesifik (Mulai & Selesai) sesuai Source 2 --}}
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Tanggal Asesmen</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex items-center">
                                        <span class="w-16 font-semibold text-gray-600">Mulai:</span>
                                        <input type="date" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-16 font-semibold text-gray-600">Selesai:</span>
                                        <input type="date" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- 2. TABEL KOMPONEN UMPAN BALIK --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Umpan Balik Asesi</h3>
                    <p class="text-sm text-gray-500 mt-2 italic">
                        (Diisi oleh Asesi setelah pengambilan keputusan)
                    </p>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th rowspan="2" class="px-4 py-4 text-left text-xs font-bold uppercase w-[50%] border-r border-gray-700">
                                    Komponen
                                </th>
                                <th colspan="2" class="px-2 py-2 text-center text-xs font-bold uppercase border-b border-gray-700 border-r border-gray-700 w-24">
                                    Hasil
                                </th>
                                <th rowspan="2" class="px-4 py-4 text-left text-xs font-bold uppercase w-[30%]">
                                    Catatan / Komentar Asesi
                                </th>
                            </tr>
                            <tr>
                                <th class="px-2 py-2 text-center text-xs font-bold uppercase bg-gray-800 border-r border-gray-700">Ya</th>
                                <th class="px-2 py-2 text-center text-xs font-bold uppercase bg-gray-800 border-r border-gray-700">Tidak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php
                                $questions = [
                                    "Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi",
                                    "Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya",
                                    "Asesor memberikan kesempatan untuk mendiskusikan/ menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen",
                                    "Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki",
                                    "Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen",
                                    "Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen",
                                    "Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya",
                                    "Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya",
                                    "Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen",
                                    "Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen"
                                ];
                            @endphp

                            @foreach ($questions as $index => $question)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-800 align-middle border-r border-gray-200 leading-relaxed">
                                        {{ $question }}
                                    </td>
                                    {{-- YA --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="umpan_balik[{{ $index }}]" value="ya" 
                                            class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                    </td>
                                    {{-- TIDAK --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="umpan_balik[{{ $index }}]" value="tidak" 
                                            class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                    </td>
                                    {{-- CATATAN --}}
                                    <td class="px-4 py-3 align-middle">
                                        <input type="text" name="catatan[{{ $index }}]" 
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                                            placeholder="Tulis catatan...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Catatan Lainnya [cite: 4] --}}
                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan/komentar lainnya (apabila ada) :</label>
                    <textarea name="komentar_lain" rows="3" 
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-3 resize-none"
                        placeholder="Tambahkan komentar tambahan di sini..."></textarea>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mt-8 border-t-2 border-gray-200 pt-6 mb-8">
                <a href="#" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 transition text-center shadow-sm">
                    Kembali
                </a>
                <button type="button" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    Simpan Form FR.AK.03
                </button>
            </div>

        </form>
    </div>
@endsection