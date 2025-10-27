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

{{-- Tombol Kembali ke Daftar TUK (Tambahan Wajib) --}}
<div class="container mx-auto px-4 pt-4">
    <a href="{{ route('info.tuk') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition duration-150">
        <i class="fas fa-arrow-left mr-2"></i> {{-- Asumsi Anda menggunakan FontAwesome --}}
        Kembali ke Daftar TUK
    </a>
</div>

{{-- Container Utama --}}
<div class="container mx-auto px-4 py-8">
    
    {{-- Judul Halaman --}}
    <h1 class="text-3xl font-semibold text-center mb-10 text-gray-800">Detail Tempat Uji Kompetensi</h1>

    {{-- KARTU UTAMA: DETAIL TUK CARD --}}
    <div class="bg-white rounded-xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        
        {{-- BAGIAN KIRI: CARD INFO (Semua teks kini dinamis) --}}
        <div class="p-8 lg:p-12 md:w-1/2 space-y-4">
            {{-- Nama Tempat DINAMIS (DIUBAH) --}}
            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $tuk->nama_tempat ?? 'N/A' }}</h2>
            
            {{-- Detail Alamat DINAMIS --}}
            <div class="flex items-center space-x-3 text-lg text-gray-700">
                <i class="fas fa-map-marker-alt w-5 h-5 text-gray-900"></i>
                <p>{{ $tuk->alamat_lengkap ?? 'Alamat Tidak Tersedia' }}</p>
            </div>
            
            {{-- Detail Telepon DINAMIS --}}
            <div class="flex items-center space-x-3 text-lg text-gray-700">
                <i class="fas fa-phone-alt w-5 h-5 text-gray-900"></i>
                <p>{{ $tuk->kontak ?? 'Kontak Tidak Tersedia' }}</p>
            </div>
            
            {{-- Tombol Google Maps DINAMIS --}}
            <a href="{{ $tuk->gmaps_link ?? '#' }}" target="_blank"
               class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 flex items-center space-x-2 transition duration-150 w-max">
                <i class="fas fa-map-marked-alt"></i>
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



@endsection