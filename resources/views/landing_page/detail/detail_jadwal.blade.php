@extends('layouts.app-profil') 
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mx-auto px-4 py-8 pt-24 max-w-6xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        {{-- Judul Skema --}}
        <h1 class="text-3xl font-bold mb-4">{{ $jadwal->skema?->nama_skema ?? 'Nama Skema Tidak Ditemukan' }}</h1>
        
        <div class="mb-6">
            <div class="flex flex-wrap gap-6 text-gray-900 text-sm">
                
                {{-- 🟦 PERBAIKAN 1: TANGGAL --}}
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar"></i>
                    {{-- Menggunakan 'tanggal_pelaksanaan' yang benar, bukan 'tanggal' --}}
                    <span>
                        {{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d F Y') : 'Tanggal Belum Diatur' }}
                    </span>
                </div>

                {{-- Waktu --}}
                <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - Selesai</span>
                </div>

                {{-- Lokasi TUK --}}
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $jadwal->masterTuk?->nama_lokasi ?? 'TUK Tidak Ditemukan' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-gray-900 mb-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                
                {{-- 🟦 PERBAIKAN 2: DESKRIPSI --}}
                <div class="mb-12">
                    <h2 class="text-xl font-bold mb-3">Deskripsi Skema</h2>
                    {{-- Menggunakan 'deskripsi_skema' sesuai Model Skema Anda --}}
                    <p class="text-gray-600 leading-relaxed">
                        {{ $jadwal->skema?->deskripsi_skema ?? 'Deskripsi belum tersedia.' }}
                    </p>
                </div>

                {{-- PERSYARATAN (Catatan: Kolom ini tidak ada di Model Skema Anda) --}}
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-3">Persyaratan Peserta</h2>
                    {{-- Jika Anda nanti menambahkan kolom 'persyaratan' di tabel skema, kode ini akan jalan --}}
                    <p class="text-gray-600 leading-relaxed">
                        {{ $jadwal->skema?->persyaratan ?? 'Persyaratan belum tersedia.' }}
                    </p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-300 rounded-lg p-12" style="box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.12);">
                    <div class="flex justify-between items-center mb-5">
                        <span class="font-semibold text-gray-700">Harga :</span>
                        <span class="font-bold text-lg">RP. {{ number_format($jadwal->skema?->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-10">
                        <span class="font-semibold text-gray-700">Jumlah Pendaftar :</span>
                        <span class="font-bold">{{ $jumlahPeserta ?? 0 }} Orang</span>
                    </div>
                    
                    {{-- Tombol Daftar --}}
                    <a href="#" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-center text-black font-semibold py-3 rounded-lg transition duration-200 mb-3">
                        Daftar Sekarang
                    </a>
                    
                    <p class="text-center text-gray-500 text-xs">
                        Pendaftaran ditutup tanggal 
                        {{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection