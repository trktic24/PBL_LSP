@extends('layouts.app-profil')

@section('title', 'Visi & Misi')

@section('content')
    <section id="visi-misi" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            
            {{-- Header Section --}}
            <div class="text-center mb-16">
                <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="LSP POLINES" class="mb-6 mx-auto h-40 object-contain">
                <h1 class="font-extrabold text-3xl md:text-4xl text-gray-800 font-poppins">Lembaga Sertifikasi Profesi</h1>
                <p class="text-xl text-gray-600 mt-2 font-medium">Politeknik Negeri Semarang</p>
            </div>

            {{-- Grid Container (Layout Utama) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
        
                {{-- Kartu Visi --}}
                <div class="bg-white shadow-lg rounded-2xl p-8 md:p-10 text-center h-full w-full border-t-8 border-yellow-400 hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                    <h3 class="text-3xl font-bold mb-6 text-gray-800 font-poppins">Visi</h3>
                    <div class="flex-grow flex items-center justify-center">
                        <p class="text-gray-700 text-lg leading-relaxed italic font-medium">
                            "Menjadi lembaga sertifikasi profesi yang profesional dalam memastikan dan memelihara kompetensi sumberdaya manusia."
                        </p>
                    </div>
                </div>
        
                {{-- Kartu Misi --}}
                <div class="bg-white shadow-lg rounded-2xl p-8 md:p-10 h-full w-full border-t-8 border-yellow-400 hover:shadow-2xl transition-shadow duration-300">
                    <h3 class="text-3xl font-bold mb-6 text-gray-800 text-center font-poppins">Misi</h3>
                    {{-- List Misi (Sudah dirapikan dengan <ol>) --}}
                    <ol class="list-decimal list-outside ml-5 space-y-3 text-gray-700 text-left text-lg">
                        <li class="pl-2">Mendorong harmonisasi pendidikan di Politeknik Negeri Semarang dengan dunia industri/dunia kerja.</li>
                        <li class="pl-2">Menginisiasi pengembangan standar kompetensi.</li>
                        <li class="pl-2">Mengembangkan dan memelihara skema sertifikasi.</li>
                        <li class="pl-2">Melaksanakan proses sertifikasi kompetensi.</li>
                        <li class="pl-2">Menjunjung tinggi kejujuran dan kerahasiaan di dalam proses sertifikasi untuk menghasilkan tenaga kerja yang kompeten.</li>
                    </ol>
                </div>
        
                {{-- Kartu Tujuan (Membentang 2 kolom / Full Width di bawah) --}}
                <div class="bg-white shadow-lg rounded-2xl p-8 md:p-12 lg:col-span-2 w-full border-t-8 border-blue-800 hover:shadow-2xl transition-shadow duration-300">
                    <h3 class="text-3xl font-bold mb-6 text-center text-gray-800 font-poppins">Tujuan</h3>
                    <div class="text-left mx-auto max-w-4xl">
                        <ol class="list-decimal list-outside ml-5 space-y-3 text-gray-700 text-lg">
                            <li class="pl-2">Memastikan kompetensi atau capaian pembelajaran mahasiswa dan/atau lulusan Politeknik Negeri Semarang.</li>
                            <li class="pl-2">Memelihara kompetensi mahasiswa dan/atau lulusan Politeknik Negeri Semarang.</li>
                            <li class="pl-2">Membantu mengontrol mutu lulusan pendidikan di Politeknik Negeri Semarang.</li>
                            <li class="pl-2">Memenuhi persyaratan pendamping ijazah bagi lulusan Politeknik Negeri Semarang sesuai dengan peraturan perundangan.</li>
                        </ol>
                    </div>
                </div>
        
            </div>
            
        </div>
    </section>
@endsection