@extends('layouts.detail-tuk-master')

@section('title', 'Alur Proses Sertifikasi')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold text-center mb-16 text-gray-800">Alur Proses Sertifikasi</h1>

    <div class="relative max-w-4xl mx-auto">
        
        {{-- Garis Vertikal: Absolute di posisi kiri (28px dari tepi) --}}
        {{-- Garis inilah yang menciptakan kolom kiri kosong --}}
        <div class="absolute inset-0">
            <div class="w-1 bg-gray-300 h-full absolute left-[28px] transform -translate-x-1/2"></div>
        </div>

        {{-- Item Alur 1: Pendaftaran & Verifikasi --}}
        {{-- Gunakan pl-16 pada item untuk memastikan ruang kosong di kiri --}}
        <div class="mb-10 w-full relative pl-16"> 
            
            {{-- Lingkaran Poin Alur: Posisi absolut tepat di atas garis --}}
            <div class="absolute left-[28px] top-0 mt-2 z-10 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow-md transform -translate-x-1/2"></div>

            {{-- Kartu Detail --}}
            {{-- Konten mengambil sisa lebar (ml-0 karena sudah ada pl-16) --}}
            <div class="p-6 w-full bg-yellow-50 rounded-xl shadow-md border-l-4 border-yellow-300 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-900">Pendaftaran & Verifikasi Dokumen</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

        {{-- Item Alur 2: Pembayaran --}}
        <div class="mb-10 w-full relative pl-16">
            <div class="absolute left-[28px] top-0 mt-2 z-10 w-6 h-6 rounded-full bg-gray-400 border-4 border-white shadow-md transform -translate-x-1/2"></div>
            <div class="p-6 w-full bg-gray-200 rounded-xl shadow-md border-l-4 border-gray-300 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Pembayaran</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

        {{-- Item Alur 3: Pelaksanaan Asesmen --}}
        <div class="mb-10 w-full relative pl-16">
            <div class="absolute left-[28px] top-0 mt-2 z-10 w-6 h-6 rounded-full bg-gray-400 border-4 border-white shadow-md transform -translate-x-1/2"></div>
            <div class="p-6 w-full bg-gray-200 rounded-xl shadow-md border-l-4 border-gray-300 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Pelaksanaan Asesmen Kompetensi</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

        {{-- Item Alur 4: Penerbitan Sertifikat --}}
        <div class="mb-10 w-full relative pl-16">
            <div class="absolute left-[28px] top-0 mt-2 z-10 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow-md transform -translate-x-1/2"></div>
            <div class="p-6 w-full bg-white rounded-xl shadow-lg border-2 border-blue-600 transition duration-300 transform hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-900">Penerbitan Sertifikat</h3>
                <p class="text-gray-600">lorem ipsum dolor sit amet</p>
            </div>
        </div>

    </div>
</div>
@endsection