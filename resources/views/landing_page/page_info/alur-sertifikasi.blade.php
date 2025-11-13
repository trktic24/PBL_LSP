@extends('layouts.app-profil')

@section('title', 'Alur Proses Sertifikasi')

@section('content')
<!-- CSS lokal hanya untuk halaman ini -->
<!-- <style>
    /* Sembunyikan navbar hanya di halaman ini */
    nav, .navbar, .fixed, .top-0, .z-50 {
        display: none !important;
    }
</style> -->

<section class="bg-white border border-white rounded-lg mx-6 md:mx-12 my-10 p-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-gray-900 mb-12">
            Alur Proses Sertifikasi
        </h2>

        <!-- Timeline Container -->
        <div class="relative ml-8">
            <!-- Garis utama (vertikal penuh, sejajar tengah lingkaran) -->
            <div style="
                position: absolute;
                left: 1rem; /* posisikan tepat di tengah lingkaran */
                top: 0;
                bottom: 0;
                width: 3px;
                background-color: #9ca3af; /* bg-gray-400 */
                z-index: 0;
            "></div>

            <!-- Step 1 -->
            <div class="mb-10 relative pl-12 group">
                <!-- Lingkaran -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 
                            w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow 
                            z-10 transition duration-300 group-hover:bg-blue-500"></div>
                <!-- Card -->
                <div class="bg-white border border-gray-200 shadow-md rounded-xl 
                            p-5 ml-2 transition duration-300 hover:bg-yellow-100 relative z-20">
                    <h3 class="font-semibold text-gray-900">Pendaftaran & Verifikasi Dokumen</h3>
                    <p class="text-gray-600 text-sm">
                        Peserta melakukan pendaftaran dan mengunggah dokumen pendukung untuk verifikasi awal.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="mb-10 relative pl-12 group">
                <!-- Lingkaran -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 
                            w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow 
                            z-10 transition duration-300 group-hover:bg-blue-500"></div>
                <!-- Card -->
                <div class="bg-white border border-gray-200 shadow-md rounded-xl 
                            p-5 ml-2 transition duration-300 hover:bg-yellow-100 relative z-20">
                    <h3 class="font-semibold text-gray-900">Pembayaran</h3>
                    <p class="text-gray-600 text-sm">
                        Peserta melakukan pembayaran biaya sertifikasi setelah dokumen dinyatakan valid.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="mb-10 relative pl-12 group">
                <!-- Lingkaran -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 
                            w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow 
                            z-10 transition duration-300 group-hover:bg-blue-500"></div>
                <!-- Card -->
                <div class="bg-white border border-gray-200 shadow-md rounded-xl 
                            p-5 ml-2 transition duration-300 hover:bg-yellow-100 relative z-20">
                    <h3 class="font-semibold text-gray-900">Pelaksanaan Asesmen Kompetensi</h3>
                    <p class="text-gray-600 text-sm">
                        Asesmen dilakukan oleh asesor sesuai dengan skema sertifikasi yang dipilih.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="relative pl-12 group">
                <!-- Lingkaran -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 
                            w-8 h-8 rounded-full bg-gray-400 border-[4px] border-white shadow 
                            z-10 transition duration-300 group-hover:bg-blue-500"></div>
                <!-- Card -->
                <div class="bg-white border border-gray-200 shadow-md rounded-xl 
                            p-5 ml-2 transition duration-300 hover:bg-yellow-100 relative z-20">
                    <h3 class="font-semibold text-gray-900">Penerbitan Sertifikat</h3>
                    <p class="text-gray-600 text-sm">
                        Sertifikat kompetensi diterbitkan bagi peserta yang dinyatakan kompeten.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
</section>
@endsection
