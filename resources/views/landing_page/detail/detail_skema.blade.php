@extends('layouts.app-profil')

@section('content')

    {{-- CATATAN PENTING: Untuk memastikan konten halaman ini sejajar sempurna
       dengan Navbar, pastikan bagian konten di layouts/app-profil.blade.php
       menggunakan wrapper yang SAMA, yaitu: max-w-screen-xl mx-auto px-8. --}}

    {{-- HERO SECTION: Menampilkan detail utama Skema --}}
    <section class="max-w-screen-xl mx-auto px-8 mt-20">
        <div class="relative h-[500px] rounded-[2rem] overflow-hidden shadow-xl">
            @php
                // SETTING PLACEHOLDER BARU
                $detailImgSrc = 'images/default_pic.jpg';

                if (!empty($skema->gambar)) {
                    // Cek path 1: Path manual (misal: images/namafile.jpg)
                    if (str_starts_with($skema->gambar, 'images/')) {
                        if (file_exists(public_path($skema->gambar))) {
                            $detailImgSrc = $skema->gambar;
                        }
                    } 
                    // Cek path 2: Folder foto_skema
                    elseif (file_exists(public_path('images/skema/foto_skema/' . $skema->gambar))) {
                        $detailImgSrc = 'images/skema/foto_skema/' . $skema->gambar;
                    } 
                    // Cek path 3: Folder skema root
                    elseif (file_exists(public_path('images/skema/' . $skema->gambar))) {
                        $detailImgSrc = 'images/skema/' . $skema->gambar;
                    }
                }
            @endphp
            
            <img src="{{ asset($detailImgSrc) }}"
                alt="{{ $skema->nama_skema ?? 'Skema Sertifikasi' }}"
                class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/90 via-blue-400/40 to-transparent"></div>

            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white">
                <h1 class="text-6xl font-bold mb-4 font-poppins">{{ strtoupper($skema->nama_skema ?? $skema->nama ?? 'Skema Sertifikasi') }}</h1>
                <p class="text-lg max-w-md">{{ $skema->deskripsi ?? 'Deskripsi skema belum tersedia.' }}</p>
                
                {{-- Detail jadwal tunggal dihapus karena ini adalah halaman daftar skema --}}
            </div>
        </div>
    </section>

    <div class="py-10"></div> {{-- Spacer --}}

    {{-- Jadwal Pelaksanaan (Menggunakan Grid/Card Horizontal) --}}
    <section class="max-w-screen-xl mx-auto px-8 py-10">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 font-poppins">Jadwal Pelaksanaan</h2>

        @if(isset($skema->jadwal) && $skema->jadwal->isNotEmpty())
            {{-- MENGGUNAKAN LAYOUT CARD HORIZONTAL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($skema->jadwal->sortBy('tanggal_pelaksanaan') as $jadwal)
                    @php
                        // Gunakan accessor model, tidak perlu Carbon::parse eksplisit
                        $tanggal_pelaksanaan = $jadwal->tanggal_pelaksanaan; 
                        
                        // Default true jika tanggal null (supaya tidak bisa didaftar)
                        $is_past = $tanggal_pelaksanaan ? $tanggal_pelaksanaan->isPast() : true; 
                        
                        // Menentukan warna border dan teks tombol
                        $border_class = $is_past ? 'border-gray-300' : 'border-blue-500';
                        $button_class = $is_past 
                            ? 'bg-gray-300 text-gray-700 cursor-not-allowed' 
                            : 'bg-yellow-400 text-black font-bold hover:bg-yellow-500';
                        
                        $button_link = $is_past 
                            ? '#' 
                            : route('detail_jadwal', $jadwal->id_jadwal);
                                
                        $button_text = $is_past 
                            ? 'Selesai' 
                            : 'Detail';
                            
                        // REVISI 1: Mengambil Nama Bulan Pelaksanaan untuk Header Kartu, ABAIKAN SESI
                        $header_text = $tanggal_pelaksanaan ? $tanggal_pelaksanaan->isoFormat('MMMM') : 'N/A';
                    @endphp
                    
                    {{-- Kartu Jadwal Ringkas --}}
                    {{-- Hapus text-center dari kartu agar konten di dalamnya bisa diratakan ke kiri --}}
                    <div class="bg-white rounded-2xl shadow-xl p-6 border-2 {{ $border_class }} flex flex-col justify-between transition duration-300">
                        
                        {{-- Header: Bulan Pelaksanaan (Teks Rata Tengah) --}}
                        <h3 class="font-extrabold text-xl mb-4 text-gray-800 uppercase text-center border-b pb-2 font-poppins">
                            {{ $header_text ?? 'N/A' }}
                        </h3>

                        {{-- REVISI 2: Container untuk detail tanggal/lokasi diatur agar rata kiri (text-left) --}}
                        <div class="space-y-3 mb-6 text-gray-700 w-full text-left">
                            
                            {{-- Tanggal Pelaksanaan (Ikon Hitam, Rata Kiri) --}}
                            <div class="flex items-center gap-3 text-base">
                                {{-- Icon Kalender --}}
                                <svg class="w-5 h-5 text-gray-800 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{-- FIX: Menggunakan isoFormat untuk bahasa Indonesia dan nullsafe operator --}}
                                <span>{{ $tanggal_pelaksanaan?->isoFormat('D MMMM YYYY') ?? 'N/A' }}</span>
                            </div>

                            {{-- TUK / Lokasi (Ikon Hitam, Rata Kiri) --}}
                            <div class="flex items-center gap-3 text-base">
                                {{-- Icon Lokasi --}}
                                <svg class="w-5 h-5 text-gray-800 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $jadwal->masterTuk->nama_lokasi ?? 'Lokasi TUK' }}</span>
                            </div>
                            
                        </div>

                        {{-- REVISI 3: Tombol Dinamis - Hilangkan w-full dan ganti dengan flex justify-center untuk merampingkan tombol --}}
                        <div class="w-full flex justify-center mt-4">
                            <a href="{{ $button_link }}"
                                class="px-6 py-2 rounded-lg transition shadow-md {{ $button_class }}"
                                @if($is_past) onclick="return false;" @endif>
                                {{ $button_text }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4 border-dashed border-2 border-gray-300 rounded-lg">Belum ada jadwal pelaksanaan yang tersedia untuk skema ini.</p>
        @endif
    </section>

    <div class="py-5"></div> {{-- Spacer --}}

    {{-- Unit Kompetensi --}}
    <section class="max-w-screen-xl mx-auto px-8 py-10">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 font-poppins">Unit Kompetensi</h2>

        <div class="space-y-4">
            @if(isset($skema->unitKompetensi) && $skema->unitKompetensi->isNotEmpty())
                @foreach($skema->unitKompetensi as $unit)
                    <div class="bg-white rounded-2xl p-6 border-2 border-blue-500 shadow-xl hover:shadow-2xl transition duration-300">
                        {{-- Menampilkan Kode Unit --}}
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-400">
                                {{ $unit->kode_unit ?? 'N/A' }}
                            </span>
                        </div>
                        
                        {{-- Menampilkan Judul Unit (String, bukan Array) --}}
                        <h3 class="text-lg font-bold text-gray-800 font-poppins">
                            {{ $unit->judul_unit ?? 'Judul Unit Tidak Tersedia' }}
                        </h3>
                    </div>
                @endforeach
            @else                       
                <p class="text-gray-500 text-center py-4 border-dashed border-2 border-gray-300 rounded-lg">
                    Data Unit Kompetensi tidak tersedia.
                </p>
            @endif
        </div>
    </section>

    <div class="py-5"></div> {{-- Spacer --}}

    {{-- SKKNI (Standar Kompetensi Kerja Nasional Indonesia) --}}
    <section class="max-w-screen-xl mx-auto px-8 py-10">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 font-poppins">SKKNI</h2>

        <div class="space-y-4">
            @if(!empty($skema->SKKNI))
                <div class="bg-blue-50 rounded-2xl shadow-md p-4 flex items-center justify-between transition duration-300 hover:bg-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            {{-- Icon Dokumen --}}
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-800">Dokumen SKKNI</span>
                    </div>
                    
                    {{-- Asumsi SKKNI adalah link atau path file --}}
                    <a href="{{ $skema->SKKNI }}" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center gap-1">
                        Unduh / Lihat
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-4 border-dashed border-2 border-gray-300 rounded-lg">
                    Dokumen SKKNI tidak tersedia.
                </p>
            @endif
        </div>
    </section>

    <div class="py-5"></div> {{-- Spacer --}}

    {{-- Ambil Skema Button --}}
    <section class="max-w-screen-xl mx-auto px-8 py-10 text-center">
        <a href="{{ route('login') }}"
            class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-12 rounded-lg text-lg transition shadow-lg hover:shadow-xl transform hover:scale-[1.02] duration-300">
            Ambil Skema
        </a>
    </section>

@endsection
