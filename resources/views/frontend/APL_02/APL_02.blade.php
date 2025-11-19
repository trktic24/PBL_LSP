@extends('layouts.app-sidebar')

@section('content')
    <style>
        .accordion-content {
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }

        .accordion-content.active {
            max-height: 5000px;
            opacity: 1;
        }

        .accordion-icon {
            transition: transform 0.3s ease;
        }

        .accordion-btn[aria-expanded="true"] .accordion-icon {
            transform: rotate(180deg);
        }
    </style>


    <x-header_form.header_form title="FR.APL.02 ASESMEN MANDIRI" /><br>

    {{-- Penyesuaian Margin Global: Mengganti p-3 sm:p-6 md:p-8 menjadi p-3 sm:p-4 md:p-6 dan menghapus max-w-7xl mx-auto dari container utama --}}
    <div class="p-3 sm:p-4 md:p-6">

        {{-- Detail Pelaksanaan, tanpa margin otomatis horizontal untuk menyesuaikan dengan container baru --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">

            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Skema Sertifikasi</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">[Memuat...]</dd>
                {{-- Nama Asesor --}}
                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Nomor Skema</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">[Memuat...]</dd>

                {{-- Nama Asesi --}}
                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Nama Asesi</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">[Memuat...]</dd>

                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Tanggal Pengisian</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">[Memuat...]</dd>
            </dl>
        </div>

        {{-- Panduan --}}
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 sm:p-5 mb-6 sm:mb-8 rounded-r-lg shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 mr-3 flex-shrink-0 mt-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-xs sm:text-sm text-amber-900 leading-relaxed">
                        <strong class="font-bold">Panduan:</strong> Baca setiap pertanyaan, pilih <span
                            class="font-bold text-green-700">K (Kompeten)</span> jika yakin dapat melakukannya, atau <span
                            class="font-bold text-red-700">BK (Belum Kompeten)</span> jika belum. Upload bukti pendukung
                        yang relevan.
                    </p>
                </div>
            </div>
        </div>

        {{-- DAFTAR UNIT (ACCORDION) --}}
        <div class="space-y-4 sm:space-y-5 mb-8 sm:mb-10">

            @php
                $units = [
                    ['code' => 'J.620100.004.02', 'title' => 'Menggunakan Struktur Data'],
                    ['code' => 'J.620100.005.02', 'title' => 'Mengimplementasikan User Interface'],
                    ['code' => 'J.620100.009.01', 'title' => 'Melakukan Instalasi Software Tools Pemrograman'],
                    ['code' => 'J.620100.016.01', 'title' => 'Menulis Kode dengan Prinsip Sesuai Guidelines'],
                    ['code' => 'J.620100.017.02', 'title' => 'Mengimplementasikan Pemrograman Terstruktur'],
                    ['code' => 'J.620100.019.02', 'title' => 'Menggunakan Library atau Komponen Pre-Existing'],
                    ['code' => 'J.620100.023.02', 'title' => 'Membuat Dokumen Kode Program'],
                    ['code' => 'J.620100.025.02', 'title' => 'Melakukan Debugging'],
                ];
            @endphp

            @foreach ($units as $index => $unit)
                <div
                    class="border-2 border-gray-200 rounded-xl overflow-hidden shadow-md bg-white hover:shadow-xl transition-shadow">

                    {{-- Header Accordion --}}
                    <button type="button"
                        class="accordion-btn w-full bg-gradient-to-r from-gray-50 to-gray-100 p-4 sm:p-5 flex justify-between items-center text-left hover:from-gray-100 hover:to-gray-200 transition-all"
                        aria-expanded="false">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">Unit
                                    {{ $index + 1 }}</span>
                                <span
                                    class="text-xs sm:text-sm text-gray-600 font-medium break-all">{{ $unit['code'] }}</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 break-words">{{ $unit['title'] }}</h3>
                        </div>
                        <svg class="accordion-icon w-6 h-6 sm:w-7 sm:h-7 text-gray-500 ml-3 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    {{-- Body Accordion --}}
                    <div class="accordion-content">
                        <div class="p-0">

                            {{-- Desktop Table --}}
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-900 text-white">
                                        <tr>
                                            {{-- No: w-12 --}}
                                            <th class="px-4 py-4 text-left text-xs font-bold uppercase w-12">No</th>
                                            {{-- Pertanyaan Asesmen Mandiri: Dibuat proporsional lebih kecil (misal: w-3/5 atau 60%) --}}
                                            <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[55%]">Pertanyaan
                                                Asesmen
                                                Mandiri</th>
                                            {{-- K: w-24 --}}
                                            <th class="px-4 py-4 text-center text-xs font-bold uppercase w-16">K</th>
                                            {{-- BK: w-24 --}}
                                            <th class="px-4 py-4 text-center text-xs font-bold uppercase w-16">BK</th>
                                            {{-- Bukti Pendukung: Dibuat proporsional lebih besar (misal: w-1/5 atau 20%) --}}
                                            <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[25%]">Bukti
                                                Pendukung</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @for ($elemen = 1; $elemen <= 3; $elemen++)
                                            <tr class="hover:bg-blue-50 transition-colors">
                                                <td class="px-4 py-5 text-sm font-bold text-gray-900 align-top">
                                                    {{ $elemen }}</td>
                                                <td class="px-4 py-5 text-sm text-gray-700 align-top">
                                                    <p class="font-bold text-gray-900 mb-2 text-base">Elemen Kompetensi
                                                        {{ $elemen }}</p>
                                                    <p class="leading-relaxed">Apakah Anda dapat melakukan identifikasi dan
                                                        implementasi {{ strtolower($unit['title']) }} sesuai dengan standar
                                                        dan spesifikasi yang berlaku?</p>
                                                </td>
                                                <td class="px-4 py-5 align-top text-center">
                                                    <label
                                                        class="inline-flex items-center justify-center cursor-pointer group">
                                                        <input type="radio"
                                                            name="unit_{{ $index }}_elm_{{ $elemen }}"
                                                            value="K"
                                                            class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                                    </label>
                                                </td>
                                                <td class="px-4 py-5 align-top text-center">
                                                    <label
                                                        class="inline-flex items-center justify-center cursor-pointer group">
                                                        <input type="radio"
                                                            name="unit_{{ $index }}_elm_{{ $elemen }}"
                                                            value="BK"
                                                            class="w-5 h-5 text-red-600 border-2 border-gray-300 focus:ring-2 focus:ring-red-500">
                                                    </label>
                                                </td>
                                                <td class="px-4 py-5 align-top">
                                                    <input type="file"
                                                        class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="lg:hidden p-3 sm:p-4 space-y-4">
                                @for ($elemen = 1; $elemen <= 3; $elemen++)
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-4 shadow-sm">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span
                                                class="bg-gray-900 text-white text-xs font-bold px-2 py-1 rounded">{{ $elemen }}</span>
                                            <span class="text-sm font-bold text-gray-900">Elemen Kompetensi
                                                {{ $elemen }}</span>
                                        </div>

                                        <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                                            Apakah Anda dapat melakukan identifikasi dan implementasi
                                            {{ strtolower($unit['title']) }} sesuai dengan standar dan spesifikasi yang
                                            berlaku?
                                        </p>

                                        <div class="mb-4">
                                            <label class="block text-xs font-bold text-gray-700 mb-2">Penilaian
                                                Diri:</label>
                                            <div class="grid grid-cols-2 gap-3">
                                                <label
                                                    class="flex items-center justify-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all has-[:checked]:border-green-600 has-[:checked]:bg-green-100">
                                                    <input type="radio"
                                                        name="unit_{{ $index }}_elm_{{ $elemen }}_mobile"
                                                        value="K" class="w-4 h-4 text-green-600 mr-2">
                                                    <span class="text-sm font-bold text-green-700">Kompeten</span>
                                                </label>
                                                <label
                                                    class="flex items-center justify-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 hover:bg-red-50 transition-all has-[:checked]:border-red-600 has-[:checked]:bg-red-100">
                                                    <input type="radio"
                                                        name="unit_{{ $index }}_elm_{{ $elemen }}_mobile"
                                                        value="BK" class="w-4 h-4 text-red-600 mr-2">
                                                    <span class="text-sm font-bold text-red-700">Belum Kompeten</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-2">Bukti
                                                Pendukung:</label>
                                            <input type="file"
                                                class="block w-full text-xs text-gray-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                @endfor
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Tanda Tangan --}}
        <div
            class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4 sm:p-6 shadow-lg mb-8">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Rekomendasi & Tanda Tangan</h3>

            <div class="mb-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded-r-lg">
                <p class="text-xs sm:text-sm text-yellow-900 leading-relaxed">
                    <strong>Catatan:</strong> Asesmen mandiri ini akan diverifikasi oleh Asesor. Pastikan bukti yang Anda
                    lampirkan valid dan asli.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                {{-- Asesi --}}
                <div class="bg-white rounded-xl p-4 sm:p-5 shadow-md border border-gray-200">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesi</label>
                    <div
                        class="w-full h-40 sm:h-48 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 group-hover:text-blue-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            <p class="text-xs sm:text-sm text-gray-500 mt-2 font-medium group-hover:text-blue-600">Klik
                                untuk Tanda Tangan</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-bold text-gray-900">Peserta Uji</p>
                        <p class="text-xs text-gray-500 mt-1">Tanggal: 20 November 2025</p>
                    </div>
                </div>

                {{-- Asesor --}}
                <div class="bg-white rounded-xl p-4 sm:p-5 shadow-md border border-gray-200 opacity-60">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Rekomendasi Asesor</label>
                    <div
                        class="w-full h-40 sm:h-48 bg-gray-100 border-2 border-gray-300 rounded-xl flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            <p class="text-xs sm:text-sm text-gray-400 italic mt-2">Menunggu Verifikasi Asesor</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-bold text-gray-900">Nama Asesor</p>
                        <p class="text-xs text-gray-500 mt-1">Ditinjau: -</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Navigasi --}}
        <div
            class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 mt-8 sm:mt-12 border-t-2 border-gray-200 pt-6">
            @csrf

            <a href="#"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 hover:border-gray-400 transition shadow-sm text-center flex items-center justify-center">
                Sebelumnya
            </a>
            <button type="button"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center flex items-center justify-center">
                Simpan & Lanjutkan
            </button>
        </div>

    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                acc.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.classList.toggle('active');

                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                });
            });
        });
    </script>
@endsection
