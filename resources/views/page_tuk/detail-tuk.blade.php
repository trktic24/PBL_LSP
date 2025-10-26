@extends('layouts.detail-tuk-master')

@section('title', 'Detail Tempat Uji Kompetensi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold text-center mb-10 text-gray-800">Tempat Uji Kompetensi</h1>

    {{-- KARTU UTAMA: DETAIL TUK CARD --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-8 border-blue-600 flex flex-col md:flex-row">
        
        {{-- BAGIAN KIRI: CARD INFO --}}
        <div class="p-8 lg:p-12 md:w-1/2">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Politeknik Negeri Semarang</h2>
            
            {{-- Detail Alamat (Ganti gambar dengan ikon Font Awesome untuk konsistensi Tailwind) --}}
            <div class="flex items-center space-x-3 mb-3 text-gray-700">
                <i class="fas fa-map-marker-alt w-5 h-5 text-blue-600"></i>
                <p>Jalan prof Soedarto</p>
            </div>
            
            {{-- Detail Telepon --}}
            <div class="flex items-center space-x-3 mb-6 text-gray-700">
                <i class="fas fa-phone-alt w-5 h-5 text-blue-600"></i>
                <p>082185859493</p>
            </div>
            
            {{-- Tombol Google Maps --}}
            <a href="#" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 flex items-center space-x-2 transition duration-150 w-max">
                <i class="fas fa-map-marked-alt"></i>
                <span>Buka di Google Maps</span>
            </a>
        </div>
        
        {{-- BAGIAN KANAN: IMAGE PLACEHOLDER --}}
        <div class="p-4 md:p-8 md:w-1/2 flex items-center justify-center">
            <div class="w-full h-full bg-gray-200 flex items-center justify-center h-64 md:h-auto rounded-lg">
                <div class="text-center p-6">
                    <i class="fas fa-image text-4xl text-gray-500 mb-2"></i>
                    <span class="text-gray-500 text-base font-medium">fe:picture</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection