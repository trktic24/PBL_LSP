@extends('layouts.app-profil')

@section('title', 'Detail Tempat Uji Kompetensi')



@section('content')

<div class="min-h-screen bg-[#fffdf5] py-16 px-6 lg:px-8">
    
    {{-- Judul Halaman --}}
    <div class="max-w-4xl mx-auto text-center mb-10">
        <h1 class="text-3xl font-semibold text-gray-800">Detail Tempat Uji Kompetensi</h1>
        <div class="mt-2 w-24 h-1 bg-yellow-400 mx-auto rounded-full"></div>
    </div>

    {{-- Kartu Detail TUK --}}
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 p-8">
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $data_tuk->nama_lokasi }}</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: LOKASI & KONTAK --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- DETAIL LOKASI & ALAMAT --}}
                <div class="flex items-start space-x-3">
                    {{-- Menggunakan ikon placeholder yang mirip dengan gambar Anda --}}
                    <span class="text-3xl font-bold text-gray-700 leading-none">📍</span>
                    <div>
                        <p class="font-medium text-gray-900">Alamat</p>
                        {{-- DATA DINAMIS: ALAMAT --}}
                        <p class="text-sm text-gray-600">{{ $data_tuk->alamat_tuk }}</p>
                    </div>
                </div>

                {{-- DETAIL KONTAK --}}
                <div class="flex items-start space-x-3">
                    {{-- Menggunakan ikon placeholder yang mirip dengan gambar Anda --}}
                    <span class="text-3xl font-bold text-gray-700 leading-none">📞</span>
                    <div>
                        <p class="font-medium text-gray-900">Kontak</p>
                        {{-- DATA DINAMIS: KONTAK --}}
                        <p class="text-sm text-gray-600">{{ $data_tuk->kontak_tuk }}</p>
                    </div>
                </div>

                {{-- Tombol Google Maps --}}
                {{-- Menggunakan DATA DINAMIS: LINK GMAP --}}
                <a href="{{ $data_tuk->link_gmap }}" target="_blank"
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 mt-4">
                    Buka di Google Maps
                </a>
            </div>

            {{-- KOLOM KANAN: FOTO TUK --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-100 rounded-lg overflow-hidden border border-gray-300">
                    {{-- DATA DINAMIS: FOTO TUK --}}
                    <img src="{{ $data_tuk->foto_tuk }}" alt="Foto Tempat Uji Kompetensi" 
                         class="w-full h-80 object-cover" 
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder_tuk.png') }}';">
                </div>
            </div>
            
        </div>
        
    </div>
</div>



@endsection