@extends('layouts.app-sidebar')

@section('content')
    {{-- Style Internal untuk kustomisasi spesifik --}}
    <style>
        .custom-checkbox:checked {
            background-color: #2563eb; /* blue-600 */
            border-color: #2563eb;
        }
    </style>

    <x-header_form.header_form title="FR.AK.02. REKAMAN ASESMEN KOMPETENSI" /><br>

    {{-- FORM WRAPPER (Statis) --}}
    <form action="#" method="POST">
        @csrf

        {{-- Container Utama --}}
        <div class="p-3 sm:p-4 md:p-6">

            {{-- 1. IDENTITAS SKEMA --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Identitas Skema & Peserta</h3>
                <x-identitas_skema_form.identitas_skema_form />
            </div>

            {{-- 2. TABEL KELOMPOK PEKERJAAN (MODIFIKASI SESUAI GAMBAR) --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Kelompok Pekerjaan</h3>

                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 border-collapse">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                {{-- Header Kolom Kiri --}}
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[25%] border-r border-gray-700">
                                    Kelompok Pekerjaan
                                </th>
                                {{-- Header Kolom Unit --}}
                                <th class="px-4 py-4 text-center text-xs font-bold uppercase w-12 border-r border-gray-700">
                                    No.
                                </th>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[25%] border-r border-gray-700">
                                    Kode Unit
                                </th>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase">
                                    Judul Unit
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php 
                                $totalBaris = 3; // Jumlah unit kompetensi
                            @endphp

                            @for ($i = 1; $i <= $totalBaris; $i++)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    
                                    {{-- KOLOM 1: MERGED CELL (Hanya dirender pada baris pertama) --}}
                                    @if ($i === 1)
                                        <td rowspan="{{ $totalBaris }}" class="p-4 align-top border-r border-gray-200 bg-gray-50">
                                            <textarea 
                                                name="kelompok_pekerjaan" 
                                                rows="6" 
                                                class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400 resize-none"
                                                placeholder="Tuliskan Kelompok Pekerjaan..."></textarea>
                                        </td>
                                    @endif

                                    {{-- KOLOM 2: NOMOR --}}
                                    <td class="px-4 py-4 text-center font-bold text-gray-700 border-r border-gray-200 align-top">
                                        {{ $i }}.
                                    </td>

                                    {{-- KOLOM 3: KODE UNIT --}}
                                    <td class="px-4 py-4 border-r border-gray-200 align-top">
                                        <input type="text" name="unit[{{$i}}][kode]"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                                            placeholder="Kode Unit...">
                                    </td>

                                    {{-- KOLOM 4: JUDUL UNIT --}}
                                    <td class="px-4 py-4 align-top">
                                        <input type="text" name="unit[{{$i}}][judul]"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                                            placeholder="Judul Unit Kompetensi...">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. TABEL BUKTI KOMPETENSI (MATRIX) --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Bukti-Bukti Kompetensi</h3>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase min-w-[200px]">Unit Kompetensi</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Observasi Demonstrasi</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Portofolio</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-24">Pernyataan Pihak Ketiga Pertanyaan Wawancara</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Pertanyaan Lisan</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Pertanyaan Tertulis</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Proyek Kerja</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Lainnya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @for ($i = 1; $i <= 4; $i++)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <input type="text" name="bukti[{{ $i }}][unit]"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                            placeholder="Unit Kompetensi {{ $i }}">
                                    </td>
                                    {{-- Loop Checkbox --}}
                                    @foreach (['observasi', 'portofolio', 'pihak_ketiga', 'lisan', 'tertulis', 'proyek', 'lainnya'] as $type)
                                        <td class="px-2 py-3 text-center align-middle">
                                            <input type="checkbox" name="bukti[{{ $i }}][{{ $type }}]"
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer transition custom-checkbox">
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. REKOMENDASI & TINDAK LANJUT --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                {{-- Rekomendasi --}}
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Rekomendasi Hasil Asesmen</h3>
                    <div class="flex-grow flex flex-col justify-center gap-4">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-500 transition-all has-[:checked]:bg-green-100 has-[:checked]:border-green-600">
                            <input type="radio" name="hasil_asesmen" value="kompeten" class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500">
                            <span class="ml-3 font-bold text-gray-700 text-lg">Kompeten</span>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-red-50 hover:border-red-500 transition-all has-[:checked]:bg-red-100 has-[:checked]:border-red-600">
                            <input type="radio" name="hasil_asesmen" value="belum_kompeten" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-3 font-bold text-gray-700 text-lg">Belum Kompeten</span>
                        </label>
                    </div>
                </div>

                {{-- Tindak Lanjut --}}
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Tindak Lanjut yang Dibutuhkan</h3>
                    <textarea name="tindak_lanjut" rows="5"
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm resize-none placeholder-gray-400 p-3"
                        placeholder="Tuliskan tindak lanjut jika ada..."></textarea>
                </div>
            </div>

            {{-- 5. KOMENTAR ASESOR --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Komentar / Observasi Asesor</h3>
                <textarea name="komentar_asesor" rows="3"
                    class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400 p-3"
                    placeholder="Catatan tambahan dari asesor..."></textarea>
            </div>

            {{-- 6. TANDA TANGAN --}}
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4 sm:p-6 shadow-lg mb-8">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-6">Tanda Tangan Persetujuan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    {{-- TTD Asesor --}}
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesor</label>
                        <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                <p class="text-xs text-gray-500 mt-2 font-medium group-hover:text-blue-600">Klik untuk TTD Asesor</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-blue-500 px-0 py-1 bg-transparent" placeholder="Nama Asesor" value="{{ Auth::user()->name ?? '' }}">
                            <p class="text-xs text-gray-500 mt-1">No. Reg: -</p>
                        </div>
                    </div>

                    {{-- TTD Asesi --}}
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesi</label>
                        <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                <p class="text-xs text-gray-500 mt-2 font-medium group-hover:text-blue-600">Klik untuk TTD Asesi</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-blue-500 px-0 py-1 bg-transparent" placeholder="Nama Asesi">
                            <p class="text-xs text-gray-500 mt-1">Tanggal: {{ date('d-m-Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. FOOTER BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mt-8 border-t-2 border-gray-200 pt-6">
                <a href="#" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 transition text-center shadow-sm">
                    Kembali
                </a>
                {{-- Tombol type="button" untuk sementara (statis) --}}
                <button type="button" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    Simpan Form FR.AK.02
                </button>
            </div>

        </div>
    </form>
@endsection