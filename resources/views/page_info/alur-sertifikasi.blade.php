@extends('layouts.app-alur-sertifikasi')

@section('title', 'Alur Proses Sertifikasi')

@section('content')
<section class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-12">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-gray-900 mb-12">
            Alur Proses Sertifikasi
        </h2>

<!-- Timeline Container -->
<div class="relative ml-8">
    <!-- Garis utama -->
    <div class="absolute left-2 top-0 h-full w-[3px] bg-blue-500"></div>

    <!-- Step 1 -->
    <div class="mb-10 relative pl-8">
        <div class="absolute left-0 top-2 w-4 h-4 rounded-full bg-blue-500 border-[3px] border-white shadow"></div>
        <div class="bg-yellow-50 shadow-md rounded-xl p-5 ml-2">
            <h3 class="font-semibold text-gray-900">Pendaftaran & Verifikasi Dokumen</h3>
            <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
    </div>

    <!-- Step 2 -->
    <div class="mb-10 relative pl-8">
        <div class="absolute left-0 top-2 w-4 h-4 rounded-full bg-gray-300 border-[3px] border-white shadow"></div>
        <div class="bg-gray-200 shadow-md rounded-xl p-5 ml-2">
            <h3 class="font-semibold text-gray-900">Pembayaran</h3>
            <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
    </div>

    <!-- Step 3 -->
    <div class="mb-10 relative pl-8">
        <div class="absolute left-0 top-2 w-4 h-4 rounded-full bg-gray-300 border-[3px] border-white shadow"></div>
        <div class="bg-gray-200 shadow-md rounded-xl p-5 ml-2">
            <h3 class="font-semibold text-gray-900">Pelaksanaan Asesmen Kompetensi</h3>
            <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
    </div>

    <!-- Step 4 -->
    <div class="relative pl-8">
        <div class="absolute left-0 top-2 w-4 h-4 rounded-full bg-gray-300 border-[3px] border-white shadow"></div>
        <div class="bg-gray-200 shadow-md rounded-xl p-5 ml-2">
            <h3 class="font-semibold text-gray-900">Penerbitan Sertifikat</h3>
            <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
    </div>
</div>



</section>
@endsection
