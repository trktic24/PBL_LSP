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
    {{-- MENGHILANGKAN: border-t-8 border-blue-600 --}}
    <div class="bg-white rounded-xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        
        {{-- BAGIAN KIRI: CARD INFO --}}
        <div class="p-8 lg:p-12 md:w-1/2 space-y-4">
            {{-- Nama Tempat --}}
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Politeknik Negeri Semarang</h2>
            
            {{-- Detail Alamat --}}
            <div class="flex items-center space-x-3 text-lg text-gray-700">
                <i class="fas fa-map-marker-alt w-5 h-5 text-gray-900"></i> {{-- UBAH: text-blue-600 -> text-gray-900 --}}
                <p>Jalan prof Soedarto</p>
            </div>
            
            {{-- Detail Telepon --}}
            <div class="flex items-center space-x-3 text-lg text-gray-700">
                <i class="fas fa-phone-alt w-5 h-5 text-gray-900"></i> {{-- UBAH: text-blue-600 -> text-gray-900 --}}
                <p>082185859493</p>
            </div>
            
            {{-- Tombol Google Maps --}}
            {{-- Ikon di tombol tetap putih karena tombolnya biru, jadi tidak diubah --}}
            <a href="#" class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 flex items-center space-x-2 transition duration-150 w-max">
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