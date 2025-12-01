@extends('layouts.app-profil')

@section('title', 'Alur Proses Sertifikasi')
@section('description', 'Panduan lengkap alur proses sertifikasi kompetensi di LSP Polines.')

@section('content')
    {{-- HEADER SECTION --}}
    <div class="bg-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-poppins">Alur Proses Sertifikasi</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg font-inter">
                Ikuti 4 langkah mudah berikut untuk mendapatkan sertifikasi kompetensi profesi Anda di LSP Polines.
            </p>
        </div>
    </div>

    {{-- STEPS SECTION --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-7xl">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                
                {{-- Connecting Line (Desktop Only) --}}
                <div class="hidden lg:block absolute top-12 left-0 w-full h-1 bg-gray-200 -z-10 transform translate-y-1/2"></div>

                {{-- STEP 1 --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
                    <div class="w-24 h-24 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-blue-100 transition-colors relative z-10 border-4 border-white">
                        <i class="fas fa-file-alt text-4xl text-blue-600"></i>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center font-bold font-poppins text-sm border-2 border-white">1</div>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3 text-gray-900 font-poppins">Pendaftaran & Verifikasi</h3>
                    <p class="text-gray-600 text-center text-sm leading-relaxed font-inter">
                        Peserta melakukan pendaftaran online dan mengunggah dokumen persyaratan untuk diverifikasi oleh admin LSP.
                    </p>
                </div>

                {{-- STEP 2 --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
                    <div class="w-24 h-24 mx-auto bg-green-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-green-100 transition-colors relative z-10 border-4 border-white">
                        <i class="fas fa-money-bill-wave text-4xl text-green-600"></i>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center font-bold font-poppins text-sm border-2 border-white">2</div>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3 text-gray-900 font-poppins">Pembayaran</h3>
                    <p class="text-gray-600 text-center text-sm leading-relaxed font-inter">
                        Setelah dokumen valid, peserta melakukan pembayaran biaya asesmen sesuai skema yang dipilih.
                    </p>
                </div>

                {{-- STEP 3 --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
                    <div class="w-24 h-24 mx-auto bg-purple-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-purple-100 transition-colors relative z-10 border-4 border-white">
                        <i class="fas fa-chalkboard-teacher text-4xl text-purple-600"></i>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center font-bold font-poppins text-sm border-2 border-white">3</div>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3 text-gray-900 font-poppins">Asesmen Kompetensi</h3>
                    <p class="text-gray-600 text-center text-sm leading-relaxed font-inter">
                        Pelaksanaan uji kompetensi (tertulis/praktek/wawancara) oleh Asesor di Tempat Uji Kompetensi (TUK).
                    </p>
                </div>

                {{-- STEP 4 --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
                    <div class="w-24 h-24 mx-auto bg-yellow-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-yellow-100 transition-colors relative z-10 border-4 border-white">
                        <i class="fas fa-certificate text-4xl text-yellow-600"></i>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold font-poppins text-sm border-2 border-white">4</div>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3 text-gray-900 font-poppins">Penerbitan Sertifikat</h3>
                    <p class="text-gray-600 text-center text-sm leading-relaxed font-inter">
                        Peserta yang dinyatakan "Kompeten" akan menerima sertifikat resmi dari BNSP.
                    </p>
                </div>

            </div>

            {{-- CTA Section --}}
            <div class="mt-20 text-center">
                <div class="bg-blue-600 rounded-2xl p-8 md:p-12 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-4 font-poppins relative z-10">Siap untuk Tersertifikasi?</h2>
                    <p class="text-blue-100 mb-8 max-w-2xl mx-auto relative z-10 font-inter">
                        Jangan tunda lagi, tingkatkan daya saing profesional Anda dengan sertifikasi kompetensi yang diakui secara nasional.
                    </p>
                    <a href="{{ route('login') }}" class="inline-block bg-yellow-400 text-blue-900 font-bold py-3 px-8 rounded-full hover:bg-yellow-300 transition-colors transform hover:scale-105 shadow-lg relative z-10 font-poppins">
                        Daftar Sekarang
                    </a>
                </div>
            </div>

        </div>
    </section>
@endsection
