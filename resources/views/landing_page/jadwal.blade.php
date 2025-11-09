{{-- 1. Mewarisi layout utama (yang sudah punya header, footer, & CSS) --}}
@extends('layouts.app-profil')

{{-- 2. Mengatur judul halaman (menggantikan tag <title>) --}}
@section('title', 'Jadwal Asesmen')

{{-- 3. Memasukkan semua konten Anda ke 'slot' konten di layout --}}
@section('content')

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Asesmen</h1>
            <p class="text-gray-600">Lorem ipsum dolor<br>sit amet</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Div pembungkus tabel dengan latar belakang kuning --}}
        <div class="bg-yellow-50 rounded-lg shadow-md overflow-x-auto border border-gray-200">
            
            {{-- 
              Saya sesuaikan border-b agar cocok dengan gambar (lebih tipis) 
              Saya kembalikan border-gray-900 dari kode asli Anda 
            --}}
            <div class="grid grid-cols-6 bg-yellow-50 border-b-2 border-gray-900">
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Skema Sertifikasi</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Pendaftaran</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Tanggal Asesmen</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">TUK</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Kuota</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Status</div>
            </div>

            @foreach($jadwalList as $jadwal)
                {{-- Baris tabel dengan latar kuning dan hover --}}
                <div class="grid grid-cols-6 border-b border-gray-200 bg-yellow-50 hover:bg-yellow-100 transition items-center">
                    
                    <div class="px-6 py-4 text-sm text-gray-900 text-left font-medium">{{ $jadwal['skema'] }}</div>
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{ $jadwal['pendaftaran_mulai']->format('d') }} - {{ $jadwal['pendaftaran_selesai']->format('d M Y') }}
                    </div>
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{ $jadwal['tanggal_asesmen']->format('d M Y') }}
                    </div>
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">{{ $jadwal['tuk'] }}</div>
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{ $jadwal['terisi'] }}/{{ $jadwal['kuota'] }}
                    </div>
                    <div class="px-6 py-4 text-center">
                        
                        {{-- Logic tombol Anda tetap utuh --}}
                        @if($jadwal['dapat_daftar'])
                            <a href="{{ route('detail.jadwal', ['id' => $jadwal['id']]) }}" 
                               class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $jadwal['statusColor'] }} {{ $jadwal['statusBg'] }} hover:opacity-80 transition cursor-pointer">
                                {{ $jadwal['status'] }} - Daftar
                            </a>
                        @else
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $jadwal['statusColor'] }} {{ $jadwal['statusBg'] }} cursor-not-allowed">
                                {{ $jadwal['status'] }}
                            </span>
                        @endif
                        
                    </div>
                </div>
            @endforeach
        </div> </div> @endsection