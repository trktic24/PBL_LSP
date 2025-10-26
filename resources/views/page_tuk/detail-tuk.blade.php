@extends('layouts.app-profil')

@section('title', 'Detail Tempat Uji Kompetensi')

{{-- SECTION STYLES untuk menyembunyikan footer (tetap dipertahankan) --}}
@section('styles')
<style>
    /* Menyembunyikan Global Footer */
    footer, .global-footer {
        display: none !important;
    }
</style>
@endsection

@section('content')

{{-- Container Utama --}}
<div class="container mx-auto px-4 py-12">
    
    {{-- Judul Halaman --}}
    <h1 class="text-3xl font-semibold text-center mb-10 text-gray-800">Tempat Uji Kompetensi</h1>

    {{-- KARTU UTAMA: DETAIL TUK CARD --}}
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border-t-8 border-blue-600 flex flex-col md:flex-row">
        
        {{-- BAGIAN KIRI: CARD INFO --}}
        <div class="p-8 lg:p-12 md:w-1/2 space-y-4">
            {{-- Nama Tempat --}}
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Politeknik Negeri Semarang</h2>
            
            {{-- Detail Alamat --}}
            <div class="flex items-center space-x-3 text-lg text-gray-700">
                <i class="fas fa-map-marker-alt w-5 h-5 text-blue-600"></i> {{-- ICON MAPS --}}
                <p>Jalan prof Soedarto</p>
            </div>
            
            {{-- Detail Telepon --}}
            <div class="flex items-center space-x-3 text-lg text-gray-700">
                <i class="fas fa-phone-alt w-5 h-5 text-blue-600"></i> {{-- ICON TELEPON --}}
                <p>082185859493</p>
            </div>
            
            {{-- Tombol Google Maps --}}
            <a href="#" class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 flex items-center space-x-2 transition duration-150 w-max">
                <i class="fas fa-map-marked-alt"></i> {{-- ICON MAPS (Di Tombol) --}}
                <span>Buka di Google Maps</span>
            </a>
        </div>
        
        {{-- BAGIAN KANAN: IMAGE PLACEHOLDER --}}
        <div class="p-8 md:p-12 md:w-1/2 flex items-center justify-center">
            <div class="w-full h-64 bg-gray-300 flex items-center justify-center rounded-lg overflow-hidden">
                <div class="text-center p-6 text-gray-600">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <span class="text-base font-medium">fe:picture</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BAGIAN CALL-TO-ACTION (Section Biru Besar) --}}
<div class="bg-blue-800 text-white py-20 mt-12">
    <div class="container mx-auto px-4 text-center">
        {{-- Slogan Kecil --}}
        <p class="text-sm font-light uppercase tracking-widest mb-4 opacity-75">
            Sertifikasi Profesi Untuk Karier Anda
        </p>

        {{-- Judul Utama --}}
        <h2 class="text-4xl md:text-5xl font-serif font-bold mb-6 tracking-wide">
            Tingkatkan Kompetensi Profesional Anda
        </h2>

        {{-- Deskripsi --}}
        <p class="max-w-3xl mx-auto text-lg mb-10 opacity-90">
            LSP Polines berkomitmen menghasilkan tenaga kompeten yang siap bersaing dan diakui secara nasional maupun internasional.
        </p>

        {{-- Tombol Hubungi Kami --}}
        <a href="#" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition duration-300 border-2 border-white">
            Hubungi Kami
        </a>

        {{-- Informasi Kontak Bawah --}}
        <div class="mt-20 flex flex-wrap justify-center text-gray-300 text-sm">
            <p class="mx-4 my-2">Jl. Prof. Soedarto, SH. Tembalang, Semarang, Jawa Tengah.</p>
            <p class="mx-4 my-2">(024) 7473417 ext.256</p>
            <p class="mx-4 my-2">lsp@polines.ac.id</p>
        </div>

    </div>
</div>

@endsection