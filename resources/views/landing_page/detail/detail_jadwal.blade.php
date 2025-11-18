@extends('layouts.app-profil') 
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mx-auto px-4 py-8 pt-24 max-w-6xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        {{-- Menggunakan relasi 'skema' dari Model Jadwal --}}
        <h1 class="text-3xl font-bold mb-4">{{ $jadwal->skema?->nama_skema ?? 'Nama Skema Tidak Ditemukan' }}</h1>
        
        <div class="mb-6">
            <div class="flex flex-wrap gap-6 text-gray-900 text-sm">
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar"></i>
                    <span>{{ $jadwal->tanggal?->format('d F Y') ?? 'Tanggal Tidak Tersedia' }}</span>
                    {{-- Menggunakan kolom tanggal_pelaksanaan dari Model Jadwal --}}
                    <span>{{ $jadwal->tanggal_pelaksanaan?->format('d F Y') ?? 'TBA' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    {{-- Menggunakan kolom waktu_mulai dari Model Jadwal --}}
                    <span>{{ $jadwal->waktu_mulai ?? '00:00' }} - Selesai</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    {{-- Menggunakan relasi 'masterTuk' dan kolom 'nama_lokasi' (dari jadwal.blade.php) --}}
                    <span>{{ $jadwal->masterTuk?->nama_lokasi ?? 'TUK Tidak Ditemukan' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-gray-900 mb-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                {{-- Asumsi Deskripsi dan Persyaratan ada di Model Skema --}}
                <div class="mb-12">
                    <h2 class="text-xl font-bold mb-3">Deskripsi Skema</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $jadwal->skema?->deskripsi ?? 'Deskripsi belum tersedia.' }}</p>
                </div>
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-3">Persyaratan Peserta</h2>
                    {{-- Ganti 'persyaratan' dengan nama kolom yang benar di tabel skema Anda --}}
                    <p class="text-gray-600 leading-relaxed">{{ $jadwal->skema?->persyaratan ?? 'Persyaratan belum tersedia.' }}</p>
                </div>
            </div>

            <div class="lg:col-span-1">
                {{-- Asumsi Harga ada di Model Skema --}}
                <div class="bg-white border border-gray-300 rounded-lg p-12" style="box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.12);">
                    <div class="flex justify-between items-center mb-5">
                        <span class="font-semibold text-gray-700">Harga :</span>
                        <span class="font-bold text-lg">RP. {{ number_format($jadwal->skema?->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-10">
                        <span class="font-semibold text-gray-700">Jumlah Pendaftar :</span>
                        {{-- Menggunakan data $jumlahPeserta dari Controller --}}
                        <span class="font-bold">{{ $jumlahPeserta ?? 0 }} Orang</span>
                    </div>
                    
                    {{-- Tombol ini seharusnya mengarah ke form pendaftaran Asesi --}}
                    <a href="#" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-center text-black font-semibold py-3 rounded-lg transition duration-200 mb-3">
                        Daftar Sekarang
                    </a>
                    
                    <p class="text-center text-gray-500 text-xs">
                        Pendaftaran ditutup tanggal {{ $jadwal->tanggal_selesai->format('d F Y') }}
                        {{-- Menggunakan kolom tanggal_selesai (penutupan) dari Model Jadwal --}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection