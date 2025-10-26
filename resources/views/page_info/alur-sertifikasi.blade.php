@extends('layouts.app-profil')

@section('title', 'Alur Proses Sertifikasi')

@section('content')
<!-- CSS lokal hanya untuk halaman ini -->
<style>
    /* Sembunyikan navbar hanya di halaman ini */
    nav, .navbar, .fixed, .top-0, .z-50 {
        display: none !important;
    }
</style>

<section class="bg-white min-h-screen border border-gray-300 rounded-lg mx-6 md:mx-12 my-10 p-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-gray-900 mb-12">
            Alur Proses Sertifikasi
        </h2>

        <!-- Timeline Container -->
        <div class="relative ml-8">
            <!-- Garis utama -->
            <div class="absolute left-3 top-0 h-full w-[3px] bg-gray-400"></div>

            <!-- Step 1 -->
            <div class="mb-10 relative pl-12">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-blue-500 border-[4px] border-white shadow"></div>
                <div class="bg-yellow-50 shadow-md rounded-xl p-5 ml-2 transition duration-300 hover:bg-yellow-100 border border-gray-200">
                    <h3 class="font-semibold text-gray-900">Pendaftaran & Verifikasi Dokumen</h3>
                    <p class="text-gray-600 text-sm">Peserta melakukan pendaftaran dan mengunggah dokumen pendukung untuk verifikasi awal.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="mb-10 relative pl-12">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow"></div>
                <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 ml-2 transition duration-300 hover:bg-yellow-100">
                    <h3 class="font-semibold text-gray-900">Pembayaran</h3>
                    <p class="text-gray-600 text-sm">Peserta melakukan pembayaran biaya sertifikasi setelah dokumen dinyatakan valid.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="mb-10 relative pl-12">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow"></div>
                <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 ml-2 transition duration-300 hover:bg-yellow-100">
                    <h3 class="font-semibold text-gray-900">Pelaksanaan Asesmen Kompetensi</h3>
                    <p class="text-gray-600 text-sm">Asesmen dilakukan oleh asesor sesuai dengan skema sertifikasi yang dipilih.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="relative pl-12">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow"></div>
                <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 ml-2 transition duration-300 hover:bg-yellow-100">
                    <h3 class="font-semibold text-gray-900">Penerbitan Sertifikat</h3>
                    <p class="text-gray-600 text-sm">Sertifikat kompetensi diterbitkan bagi peserta yang dinyatakan kompeten.</p>
                </div>
            </div>
        </div>
    </div>
    
</section>
@endsection
