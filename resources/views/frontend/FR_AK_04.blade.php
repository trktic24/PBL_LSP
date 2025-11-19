@extends('layouts.app-sidebar')

@section('content')
    <div class="p-3 sm:p-6 md:p-8 max-w-5xl mx-auto">

        {{-- 1. Menggunakan Komponen Header Form --}}
        <x-header_form.header_form title="FR.AK.04. FORMULIR BANDING ASESMEN" /><br>

        <div class="bg-white p-4 sm:p-6 rounded-md shadow-sm mb-4 sm:mb-6 border border-gray-200">

            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4 border-b border-gray-200 pb-2">Informasi Banding</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-4 sm:gap-y-6 text-sm">
                {{-- TUK --}}
                <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                <dd class="col-span-3 flex flex-wrap gap-x-4 sm:gap-x-6 gap-y-2 items-center">
                    <label class="flex items-center text-gray-900 font-medium text-sm">
                        <input type="checkbox" value="Sewaktu" id="tuk_Sewaktu" disabled
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Sewaktu
                    </label>
                    <label class="flex items-center text-gray-900 font-medium text-sm">
                        <input type="checkbox" value="Tempat Kerja" id="tuk_Tempat Kerja" disabled
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Tempat Kerja
                    </label>
                    <label class="flex items-center text-gray-900 font-medium text-sm">
                        <input type="checkbox" value="Mandiri" id="tuk_Mandiri" disabled
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Mandiri
                    </label>
                </dd>

                {{-- Nama Asesor --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block break-words">: <span id="nama_asesor">[Memuat...]</span></dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block break-words">: <span id="nama_asesi">[Memuat...]</span></dd>
            </dl>
        </div>

        {{-- Card Utama Form Banding --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 sm:mb-8">

            {{-- Petunjuk Jawaban Ya/Tidak --}}
            <div class="p-3 sm:p-4 bg-blue-50 border-b border-blue-100">
                <p class="text-xs sm:text-sm text-gray-800 font-medium">
                    Jawablah dengan <span class="font-bold">Ya</span> atau <span class="font-bold">Tidak</span>
                    pertanyaan-pertanyaan berikut ini:
                </p>
            </div>

            {{-- TABEL UNTUK DESKTOP (hidden di mobile) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left font-bold uppercase tracking-wider">
                                Komponen</th>
                            <th scope="col" class="px-6 py-3 text-center font-bold uppercase tracking-wider w-24">Ya</th>
                            <th scope="col" class="px-6 py-3 text-center font-bold uppercase tracking-wider w-24">Tidak
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                        @for ($i = 1; $i <= 3; $i++)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    {{ ['Apakah Proses Banding telah dijelaskan kepada Anda?', 'Apakah Anda telah mendiskusikan Banding dengan Asesor?', 'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?'][$i - 1] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="banding_{{ $i }}" value="ya"
                                        class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="banding_{{ $i }}" value="tidak"
                                        class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- CARD UNTUK MOBILE (hidden di desktop) --}}
            <div class="md:hidden p-3 sm:p-4 space-y-3 sm:space-y-4">
                @php
                    $pertanyaan = [
                        'Apakah Proses Banding telah dijelaskan kepada Anda?',
                        'Apakah Anda telah mendiskusikan Banding dengan Asesor?',
                        'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?'
                    ];
                @endphp

                @for ($i = 1; $i <= 3; $i++)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-xs sm:text-sm text-gray-700 font-medium mb-2 sm:mb-3 leading-relaxed break-words">
                            {{ $i }}. {{ $pertanyaan[$i - 1] }}
                        </p>
                        <div class="flex gap-2 sm:gap-4">
                            <label class="flex-1 flex items-center justify-center bg-white border-2 border-gray-300 rounded-lg px-2 py-2 sm:px-4 sm:py-3 cursor-pointer hover:border-blue-500 transition-colors has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="banding_{{ $i }}" value="ya"
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 focus:ring-blue-500 mr-1 sm:mr-2 flex-shrink-0">
                                <span class="text-xs sm:text-sm font-semibold text-gray-700">Ya</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center bg-white border-2 border-gray-300 rounded-lg px-2 py-2 sm:px-4 sm:py-3 cursor-pointer hover:border-red-500 transition-colors has-[:checked]:border-red-600 has-[:checked]:bg-red-50">
                                <input type="radio" name="banding_{{ $i }}" value="tidak"
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 border-gray-300 focus:ring-red-500 mr-1 sm:mr-2 flex-shrink-0">
                                <span class="text-xs sm:text-sm font-semibold text-gray-700">Tidak</span>
                            </label>
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Bagian Informasi Skema & Alasan Banding --}}
            <div class="p-3 sm:p-4 md:p-6">

                <p class="text-xs sm:text-sm text-gray-700 mb-4 sm:mb-6 leading-relaxed">
                    Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional
                    berikut:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Skema Sertifikasi</label>
                        <input type="text" value="Junior Web Developer" disabled
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-gray-700 text-xs sm:text-sm focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">No. Skema Sertifikasi</label>
                        <input type="text" value="SKM/001/JWD/2024" disabled
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-gray-700 text-xs sm:text-sm focus:outline-none">
                    </div>
                </div>

                <label for="alasan_banding" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2">
                    Banding ini diajukan atas alasan sebagai berikut:
                </label>
                <textarea id="alasan_banding" rows="5"
                    class="w-full border-gray-300 rounded-lg sm:rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs sm:text-sm p-3 sm:p-4 resize-none"
                    placeholder="Berikan keterangan atau alasan pengajuan banding disini..."></textarea>

                {{-- Catatan Penting --}}
                <div class="mt-4 sm:mt-6 bg-red-50 border-l-4 border-red-500 p-3 sm:p-4">
                    <p class="text-xs sm:text-sm text-red-700 leading-relaxed">
                        <strong>Catatan:</strong> Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen
                        tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                    </p>
                </div>

                {{-- Tanda Tangan Pemohon Banding --}}
                <div class="mt-4 sm:mt-6 bg-white p-3 sm:p-4 md:p-6 rounded-md shadow-sm border border-gray-200">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Tanda Tangan Peserta</label>
                    <div class="w-full h-48 sm:h-56 bg-white border-2 border-dashed border-gray-300 rounded-lg sm:rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors"
                        id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-400 text-xs sm:text-sm px-4 text-center">Area Tanda Tangan akan muncul di sini</p>
                    </div>
                </div>

                {{-- Tombol Navigasi --}}
                <div class="mt-6 sm:mt-8 md:mt-12 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 border-t border-gray-200 pt-4 sm:pt-6">
                    <a href="#"
                        class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg hover:bg-gray-300 transition shadow-sm text-center flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2 text-xs sm:text-sm"></i> 
                        <span>Sebelumnya</span>
                    </a>
                    <button type="button" id="tombol-kirim-ak04"
                        class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center">
                        <span>Kirim Pengajuan</span>
                        <i class="fas fa-arrow-right ml-2 text-xs sm:text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection