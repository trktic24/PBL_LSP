@extends('layouts.app-profil')
@section('content')

    {{-- Hero Section dengan Gambar Cybersecurity --}}
    <section class="container mx-auto px-8 mt-20">
        <div class="relative h-[500px] rounded-[2rem] overflow-hidden shadow-xl">
            <img src="{{ asset('images/' . $skema['gambar']) }}" 
            alt="{{ $skema['nama'] }}"
            class="w-full h-full object-cover">

            <!-- Overlay gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/90 via-blue-400/40 to-transparent"></div>

            <!-- Text Content -->
            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white">
                <h1 class="text-6xl font-bold mb-4">{{ strtoupper($skema['nama']) }}</h1>
                <p class="text-lg max-w-md">{{ $skema['deskripsi'] }}</p>
            </div>
        </div>
    </section>

    {{-- Jadwal Pelaksanaan --}}
        <section class="container mx-auto px-8 py-10">
            <h2 class="text-3xl font-bold mb-6">Jadwal Pelaksanaan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto">
                {{-- Card 1 --}}
                <div class="bg-white rounded-2xl shadow-[6px_6px_20px_rgba(0,0,0,0.25)] p-8 border-2 border-blue-500">
                    <h3 class="font-bold text-xl text-center mb-6">Gelombang 1</h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>15 Oktober 2025</span>
                        </div>
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Politeknik Negeri Semarang</span>
                        </div>
                    </div>
                    {{-- [PERBAIKAN 1]: Tambahkan div flex justify-center di sekitar tombol Detail --}}
                    <div class="flex justify-center">
                        <button class="w-auto px-8 bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 rounded-lg transition">
                            Detail
                        </button>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="bg-white rounded-2xl shadow-[6px_6px_20px_rgba(0,0,0,0.25)] p-8 border-2 border-blue-500">
                    <h3 class="font-bold text-xl text-center mb-6">Gelombang 2</h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>15 Oktober 2025</span>
                        </div>
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Politeknik Negeri Semarang</span>
                        </div>
                    </div>
                    {{-- [PERBAIKAN 2]: Tambahkan div flex justify-center di sekitar tombol Detail --}}
                    <div class="flex justify-center">
                        <button class="w-auto px-8 bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 rounded-lg transition">
                            Detail
                        </button>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="bg-white rounded-2xl shadow-[6px_6px_20px_rgba(0,0,0,0.25)] p-8 border-2 border-blue-500">
                    <h3 class="font-bold text-xl text-center mb-6">September</h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>15 September 2025</span>
                        </div>
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>  
                            <span>Politeknik Negeri Semarang</span>
                        </div>
                    </div>
                    {{-- [PERBAIKAN 3]: Tambahkan div flex justify-center di sekitar tombol Selesai --}}
                    <div class="flex justify-center">
                        <button class="w-auto px-8 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 rounded-lg transition">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </section>

    {{-- Unit Kompetensi --}}
    <section class="container mx-auto px-8 py-10">
        <h2 class="text-3xl font-bold mb-6">Unit Kompetensi</h2>

        <div class="space-y-4">
            {{-- Unit 1 --}}
            <div class="bg-white rounded-2xl p-6
                border-2 border-blue-500
                shadow-xl">
                {{-- Perubahan: border-l-4 diubah menjadi border-2 di semua sisi --}}
                {{-- Perubahan: shadow-md diubah menjadi shadow-xl agar lebih terlihat --}}

                <h3 class="text-lg font-bold text-blue-600 mb-3">Kode Unit : 123456789</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    <li>Mengidentifikasi Konsep Keamanan Jaringan</li>
                    <li>Mengidentifikasi Konsep Keamanan Jaringan</li>
                </ul>
            </div>

            {{-- Unit 2 --}}
            <div class="bg-white rounded-2xl p-6
                border-2 border-blue-500
                shadow-xl">
                {{-- Perubahan yang sama diterapkan di sini --}}

                <h3 class="text-lg font-bold text-blue-600 mb-3">Kode Unit : 123456789</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    <li>Mengidentifikasi Konsep Keamanan Jaringan</li>
                    <li>Mengidentifikasi Konsep Keamanan Jaringan</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- SKKNI --}}
    <section class="container mx-auto px-8 py-10">
        <h2 class="text-2xl font-bold mb-6">SKKNI (Standar Kompetensi Kerja Nasional Indonesia)</h2>

        <div class="space-y-4">
            {{-- SKKNI Item 1 --}}
            <div class="bg-blue-50 rounded-2xl shadow-md p-4 flex items-center justify-between">
                {{-- Perubahan 1: bg-white diubah menjadi bg-blue-50 --}}
                {{-- Perubahan 2: p-6 diubah menjadi p-4 (mengurangi padding) --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-800">SKKNI Keamanan Siber 1</span>
                </div>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat PDF</a>
            </div>

            {{-- SKKNI Item 2 --}}
            <div class="bg-blue-50 rounded-2xl shadow-md p-4 flex items-center justify-between">
                {{-- Perubahan yang sama diterapkan pada item 2 --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-800">SKKNI Keamanan Siber 2</span>
                </div>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat PDF</a>
            </div>
        </div>
    </section>

    {{-- Ambil Skema Button --}}
    <section class="container mx-auto px-8 py-10 text-center">
        <a href="{{ route('login') }}" 
            class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-12 rounded-lg text-lg transition shadow-lg hover:shadow-xl">
            Ambil Skema
        </a>
    </section>
@endsection
