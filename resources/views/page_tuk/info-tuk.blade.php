@extends('layouts.app-profil') {{-- Menggunakan layout yang Anda sebutkan --}}

@section('title', 'Tempat Uji Kompetensi')

@section('content')
<div class="container mx-auto px-4 py-12">

    {{-- Judul Halaman --}}
    {{-- Margin bawah yang lebih besar untuk memisahkan dari tabel --}}
    <h1 class="text-3xl font-semibold text-center mb-10 text-gray-800">Tempat Uji Kompetensi</h1>

    {{-- Pembungkus Tabel (Card) --}}
    {{-- Shadow besar, rounded, border top tebal kuning (mirip gambar), background putih --}}
    <div class="bg-white rounded-lg shadow-xl overflow-hidden mb-12 border-t-4 border-yellow-500">
        <div class="overflow-x-auto">

            <table id="tuk-table" class="min-w-full divide-y divide-gray-200">

                {{-- Table Header --}}
                {{-- Header abu-abu muda, teks lebih gelap --}}
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        {{-- Kolom Tempat --}}
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Tempat
                        </th>
                        {{-- Kolom Alamat --}}
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Alamat
                        </th>
                        {{-- Kolom Kontak --}}
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Kontak
                        </th>
                        {{-- Kolom Detail --}}
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Detail
                        </th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody class="divide-y divide-gray-100">

                    {{-- Baris 1: Politeknik Negeri Semarang Gedung Kuliah Terpadu --}}
                    <tr class="hover:bg-gray-50 transition duration-100">
                        {{-- Data Tempat --}}
                        <td class="px-6 py-4 text-base text-gray-800">
                            Politeknik Negeri Semarang <br> Gedung Kuliah Terpadu
                        </td>
                        {{-- Data Alamat --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.
                        </td>
                        {{-- Data Kontak --}}
                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                            (024) 7473417 ext.256
                        </td>
                        {{-- Tombol Detail --}}
                        <td class="px-6 py-4 text-center">
                            {{-- Asumsi komponen x-detail-tuk-button sudah memiliki styling tombol kuning --}}
                            <x-detail-tuk-button url="{{ url('/info-tuk/detail/polines-gkt') }}" />
                        </td>
                    </tr>

                    {{-- Baris 2: Politeknik Negeri Semarang MST LT3 --}}
                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 text-base text-gray-800">
                            Politeknik Negeri Semarang <br> MST LT3
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                            25 Oktober 2025
                        </td>
                        <td class="px-6 py-4 text-center">
                            <x-detail-tuk-button url="{{ url('/info-tuk/detail/polines-mst-lt3') }}" />
                        </td>
                    </tr>

                    {{-- Baris 3: Politeknik Negeri Semarang Gedung Sekolah Satu --}}
                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 text-base text-gray-800">
                            Politeknik Negeri Semarang <br> Gedung Sekolah Satu
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                            (024) 7473417 ext.256
                        </td>
                        <td class="px-6 py-4 text-center">
                            <x-detail-tuk-button url="{{ url('/info-tuk/detail/polines-gedung-satu') }}" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ****************************************************** --}}
{{-- BAGIAN CALL-TO-ACTION (Footer-like Section) --}}
{{-- Bagian ini harus dimasukkan di dalam content karena terletak di tengah halaman --}}
{{-- ****************************************************** --}}
<div class="bg-blue-800 text-white py-20 mt-12">
    <div class="container mx-auto px-4 text-center">
        {{-- Slogan Kecil --}}
        <p class="text-sm font-light uppercase tracking-widest mb-4 opacity-75">
            Sertifikasi Profesi Untuk Karier Anda
        </p>

        {{-- Judul Utama --}}
        {{-- Menggunakan font-serif (atau font custom lain yang sesuai) untuk kesan elegan --}}
        <h2 class="text-4xl md:text-5xl font-serif font-bold mb-6 tracking-wide">
            Tingkatkan Kompetensi Profesional Anda
        </h2>

        {{-- Deskripsi --}}
        <p class="max-w-3xl mx-auto text-lg mb-10 opacity-90">
            LSP Polines berkomitmen menghasilkan tenaga kompeten yang siap bersaing dan diakui secara nasional maupun internasional.
        </p>

        {{-- Tombol Hubungi Kami --}}
        {{-- Tombol dengan background biru terang --}}
        <a href="#" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition duration-300">
            Hubungi Kami
        </a>

        {{-- Informasi Kontak di Bawah Tombol (Diperlukan jika master layout tidak menanganinya) --}}
        <div class="mt-20 flex flex-wrap justify-center text-gray-300 text-sm">
            <p class="mx-4 my-2">Jl. Prof. Soedarto, SH. Tembalang, Semarang, Jawa Tengah.</p>
            <p class="mx-4 my-2">(024) 7473417 ext.256</p>
            <p class="mx-4 my-2">lsp@polines.ac.id</p>
        </div>

    </div>
</div>


@endsection
