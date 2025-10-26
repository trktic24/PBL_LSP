@extends('layouts.app-info-tuk') {{-- Ganti dengan layout yang sesuai (app-info-tuk.blade.php) --}}

@section('title', 'Alur Proses Sertifikasi')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold text-center mb-16 text-gray-800">Alur Proses Sertifikasi</h1>

    <div class="relative max-w-4xl mx-auto">
        
        {{-- Garis Vertikal (Absolute Positioned) --}}
        <div class="absolute inset-0 flex justify-center">
            {{-- Garis hanya terlihat di desktop ke atas (md:block) dan berada di kiri (ml-0) --}}
            <div class="w-1 bg-gray-300 h-full absolute left-[44px] md:left-1/4 transform -translate-x-1/2"></div>
        </div>

        {{-- Item Alur 1: Pendaftaran & Verifikasi (Varian Kuning/Krem Aktif) --}}
        <div class="mb-10 flex items-start w-full relative">
            {{-- Lingkaran Poin Alur --}}
            <div class="absolute left-0 top-0 mt-2 z-10 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow-md"></div>

            {{-- Kartu Detail --}}
            <div class="ml-16 md:ml-1/4 p-6 w-full md:w-3/4 bg-yellow-50 rounded-xl shadow-md border-l-4 border-yellow-300 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-900">Pendaftaran & Verifikasi Dokumen</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

        {{-- Item Alur 2: Pembayaran (Varian Gray) --}}
        <div class="mb-10 flex items-start w-full relative">
            {{-- Lingkaran Poin Alur --}}
            <div class="absolute left-0 top-0 mt-2 z-10 w-6 h-6 rounded-full bg-gray-400 border-4 border-white shadow-md"></div>

            {{-- Kartu Detail --}}
            <div class="ml-16 md:ml-1/4 p-6 w-full md:w-3/4 bg-gray-200 rounded-xl shadow-md border-l-4 border-gray-300 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Pembayaran</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

        {{-- Item Alur 3: Pelaksanaan Asesmen (Varian Gray) --}}
        <div class="mb-10 flex items-start w-full relative">
            {{-- Lingkaran Poin Alur --}}
            <div class="absolute left-0 top-0 mt-2 z-10 w-6 h-6 rounded-full bg-gray-400 border-4 border-white shadow-md"></div>

            {{-- Kartu Detail --}}
            <div class="ml-16 md:ml-1/4 p-6 w-full md:w-3/4 bg-gray-200 rounded-xl shadow-md border-l-4 border-gray-300 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Pelaksanaan Asesmen Kompetensi</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

        {{-- Item Alur 4: Penerbitan Sertifikat (Varian Blue Border/Aktif) --}}
        <div class="mb-10 flex items-start w-full relative">
            {{-- Lingkaran Poin Alur --}}
            <div class="absolute left-0 top-0 mt-2 z-10 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow-md"></div>

            {{-- Kartu Detail --}}
            <div class="ml-16 md:ml-1/4 p-6 w-full md:w-3/4 bg-white rounded-xl shadow-lg border-2 border-blue-600 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-900">Penerbitan Sertifikat</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

    </div>
</div>
@endsection