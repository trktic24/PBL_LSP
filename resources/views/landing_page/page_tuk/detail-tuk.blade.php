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
                
                {{-- Detail Lokasi --}}
                <div class="flex items-start space-x-3">
                    <svg class="h-6 w-6 text-yellow-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657A8 8 0 1117.657 16.657z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Lokasi</p>
                        {{-- DATA DINAMIS: ALAMAT --}}
                        <p class="text-sm text-gray-600">{{ $data_tuk->alamat_tuk }}</p>
                    </div>
                </div>

                {{-- Detail Kontak --}}
                <div class="flex items-start space-x-3">
                    <svg class="h-6 w-6 text-yellow-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.141a4 4 0 003.86 3.86l1.141-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Kontak</p>
                        {{-- DATA DINAMIS: KONTAK --}}
                        <p class="text-sm text-gray-600">{{ $data_tuk->kontak_tuk }}</p>
                    </div>
                </div>

                {{-- Tombol Google Maps --}}
                {{-- DATA DINAMIS: LINK GMAP --}}
                <a href="{{ $data_tuk->link_gmap }}" target="_blank"
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 mt-4">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657A8 8 0 1117.657 16.657z" />
                    </svg>
                    Buka di Google Maps
                </a>
            </div>

            {{-- KOLOM KANAN: FOTO TUK --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-100 rounded-lg overflow-hidden border border-gray-300">
                    {{-- DATA DINAMIS: FOTO TUK (Menggunakan kolom foto_tuk) --}}
                    {{-- Pastikan URL foto yang dihasilkan Faker valid atau diganti dengan URL foto asli --}}
                    <img src="{{ $data_tuk->foto_tuk }}" alt="Foto Tempat Uji Kompetensi" 
                         class="w-full h-80 object-cover" 
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder_tuk.png') }}';">
                </div>
            </div>
            
        </div>
        
    </div>
</div>



@endsection