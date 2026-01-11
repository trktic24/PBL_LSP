@extends('layouts.app-profil')

@section('title', 'Detail Tempat Uji Kompetensi')



@section('content')

<div class="min-h-screen bg-[#fffdf5] py-16 px-6 lg:px-8">
    
    {{-- Judul Halaman --}}
    <div class="max-w-4xl mx-auto text-center mb-10">
        <h1 class="text-3xl font-semibold text-gray-800 font-poppins">Detail Tempat Uji Kompetensi</h1>
        <div class="mt-2 w-24 h-1 bg-yellow-400 mx-auto rounded-full"></div>
    </div>

    {{-- Kartu Detail TUK --}}
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 p-8">
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6 font-poppins">{{ $data_tuk->nama_lokasi }}</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: LOKASI & KONTAK --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- DETAIL LOKASI & ALAMAT --}}
                <div class="flex items-start space-x-3">

                    <span class="text-3xl font-bold text-gray-700 leading-none">📍</span>
                    <div>
                        <p class="font-medium text-gray-900">Alamat</p>

                        <p class="text-sm text-gray-600">{{ $data_tuk->alamat_tuk }}</p>
                    </div>
                </div>

                {{-- DETAIL KONTAK --}}
                <div class="flex items-start space-x-3">

                    <span class="text-3xl font-bold text-gray-700 leading-none">📞</span>
                    <div>
                        <p class="font-medium text-gray-900">Kontak</p>
                        
                        <p class="text-sm text-gray-600">{{ $data_tuk->kontak_tuk }}</p>
                    </div>
                </div>

                {{-- Tombol Google Maps (MODAL TRIGGER) --}}
                <button onclick="openMapModal()"
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 mt-4">
                    Buka di Google Maps
                </button>
            </div>

            {{-- KOLOM KANAN: FOTO TUK --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-100 rounded-lg overflow-hidden border border-gray-300">
                    
                    <img src="{{ asset('storage/' . $data_tuk->foto_tuk) }}" alt="Foto Tempat Uji Kompetensi" 
                         class="w-full h-80 object-cover" 
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholders/landscape.svg') }}';">
                </div>
            </div>
            
        </div>
        
    </div>
</div>

{{-- MODAL MAPS --}}
<div id="mapModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 overflow-hidden relative">
        {{-- Header Modal --}}
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Lokasi: {{ $data_tuk->nama_lokasi }}</h3>
            <button onclick="closeMapModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Body Modal --}}
        <div class="p-0 h-[400px]">
            @if($data_tuk->link_gmap)
                <iframe src="{{ $data_tuk->link_gmap }}" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            @else
                <div class="flex items-center justify-center h-full text-gray-500">
                    <p>Link Google Maps tidak tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openMapModal() {
        document.getElementById('mapModal').classList.remove('hidden');
    }

    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('mapModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMapModal();
        }
    });
</script>

@endsection