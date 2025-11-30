@extends('layouts.app-sidebar-asesi')

@section('content')
    <div class="p-3 sm:p-6 md:p-8">

        {{-- 1. Menggunakan Komponen Header Form --}}
        <x-header_form.header_form title="FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI" /><br>

        {{-- Kontainer Utama Form --}}
        <div class="bg-white p-4 sm:p-6 rounded-md shadow-sm mb-4 sm:mb-6 border border-gray-200">

            {{-- 2. Progress Bar - Responsif --}}
            <div class="flex items-center justify-center mb-8 sm:mb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-base sm:text-xl shadow-md">
                        1
                    </div>
                </div>
                <div class="w-20 sm:w-32 h-1 bg-yellow-400 mx-2 sm:mx-3"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-base sm:text-xl shadow-md">
                        2
                    </div>
                </div>
                <div class="w-20 sm:w-32 h-1 bg-gray-300 mx-2 sm:mx-3"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 font-bold text-base sm:text-xl">
                        3
                    </div>
                </div>
            </div>

            {{-- 3. Header Data Sertifikasi --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">
                Data Sertifikasi
            </h1>
            <p class="text-gray-600 mb-8 sm:mb-10 text-sm sm:text-base leading-relaxed">
                Unggah bukti kelengkapan persyaratan dasar sesuai dengan skema sertifikasi.
            </p>

            {{-- 4. Info Box Skema --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 sm:p-6 mb-6 sm:mb-8 shadow-sm">
                <h3 class="text-sm sm:text-base font-semibold text-gray-800 mb-3 sm:mb-4">Skema Sertifikasi / Klaster Asesmen</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-y-2 text-xs sm:text-sm">
                    <div class="flex flex-wrap">
                        <dt class="min-w-[80px] sm:min-w-[100px] text-gray-600">Judul</dt>
                        <dd class="text-gray-900 font-medium break-words flex-1" id="judul-skema">
                            : Junior Web Developer</dd>
                    </div>
                    <div class="flex flex-wrap">
                        <dt class="min-w-[80px] sm:min-w-[100px] text-gray-600">Nomor</dt>
                        <dd class="text-gray-900 font-medium break-words flex-1" id="nomor-skema">
                            : SKM.JWD.2024.01</dd>
                    </div>
                </dl>
            </div>

            {{-- 5. Pilih Tujuan Asesmen --}}
            <div class="mb-6 sm:mb-8">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Pilih Tujuan Asesmen</h3>
                
                {{-- Grid radio button --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">

                    {{-- 1. Sertifikasi --}}
                    <label class="flex items-start p-3 sm:p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer shadow-sm transition-colors">
                        <input type="radio" name="tujuan_asesmen" value="Sertifikasi"
                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio mt-0.5 flex-shrink-0">
                        <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700">Sertifikasi</span>
                    </label>

                    {{-- 2. Sertifikasi Ulang --}}
                    <label class="flex items-start p-3 sm:p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer shadow-sm transition-colors">
                        <input type="radio" name="tujuan_asesmen" value="Sertifikasi Ulang"
                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio mt-0.5 flex-shrink-0">
                        <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700">Sertifikasi Ulang</span>
                    </label>

                    {{-- 3. PKT --}}
                    <label class="flex items-start p-3 sm:p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer shadow-sm transition-colors">
                        <input type="radio" name="tujuan_asesmen" value="PKT"
                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio mt-0.5 flex-shrink-0">
                        <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700">Pengakuan Kompetensi Terkini (PKT)</span>
                    </label>

                    {{-- 4. RPL --}}
                    <label class="flex items-start p-3 sm:p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer shadow-sm transition-colors">
                        <input type="radio" name="tujuan_asesmen" value="RPL"
                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio mt-0.5 flex-shrink-0">
                        <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700">Rekognisi Pembelajaran Lampau</span>
                    </label>

                    {{-- 5. Lainnya --}}
                    <label class="flex items-start p-3 sm:p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer shadow-sm transition-colors">
                        <input type="radio" name="tujuan_asesmen" value="Lainnya"
                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio mt-0.5 flex-shrink-0"
                            id="radio-lainnya">
                        <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700">Lainnya</span>
                    </label>
                </div>
            </div>

            {{-- 6. Tabel Unit Kompetensi - DESKTOP VERSION --}}
            <div class="mb-8 sm:mb-10">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 border-b border-gray-200 pb-2">
                    Daftar Unit Kompetensi
                </h3>
                
                {{-- Desktop Table (hidden di mobile) --}}
                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider w-16">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">
                                    Kode Unit
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">
                                    Judul Unit
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">
                                    Jenis Standard
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @for ($i = 1; $i <= 7; $i++)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $i }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        J.XXXXXXXX.XXX.XX
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        Lorem ipsum Dolor Sit Amet
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        SKKNI No xxx tahun 20xx
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card Version (hidden di desktop) --}}
                <div class="md:hidden space-y-3">
                    @for ($i = 1; $i <= 7; $i++)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 shadow-sm">
                            <div class="flex items-start mb-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-900 text-white text-xs font-bold rounded-full mr-2 flex-shrink-0">
                                    {{ $i }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 mb-1 break-words">
                                        Lorem ipsum Dolor Sit Amet
                                    </p>
                                </div>
                            </div>
                            <dl class="space-y-1 text-xs">
                                <div class="flex">
                                    <dt class="text-gray-500 w-24 flex-shrink-0">Kode Unit:</dt>
                                    <dd class="text-gray-700 font-medium">J.XXXXXXXX.XXX.XX</dd>
                                </div>
                                <div class="flex">
                                    <dt class="text-gray-500 w-24 flex-shrink-0">Standard:</dt>
                                    <dd class="text-gray-700 break-words">SKKNI No xxx tahun 20xx</dd>
                                </div>
                            </dl>
                        </div>
                    @endfor
                </div>
            </div>

        </div> {{-- End div.bg-white p-6 --}}

        {{-- Tombol Footer --}}
        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 border-t border-gray-200 pt-4">
            @csrf

            <a href="{{ route('tracker', $sertifikasi->id_data_sertifikasi_asesi) }}"
                class="bg-gray-200 text-gray-700 font-medium py-2.5 sm:py-3 px-4 sm:px-6 md:px-8 rounded-lg hover:bg-gray-300 transition flex items-center justify-center text-sm">
                <i class="fas fa-arrow-left mr-2 text-xs sm:text-sm"></i> Kembali
            </a>
        <form action="{{ route('apl01_1.store') }}" method="POST" class="flex-1 w-full">
            @csrf
            <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
            <button type="submit"
                class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 text-sm flex items-center justify-center">
                Selanjutnya <i class="fas fa-arrow-right ml-2 text-xs sm:text-sm"></i>
            </button>
        </form>
        </div>

    </div> {{-- End max-w-5xl mx-auto --}}

    {{-- Script JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Logika JavaScript jika ada
        });
    </script>
@endsection