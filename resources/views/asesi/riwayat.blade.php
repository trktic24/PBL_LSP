{{--
  File: resources/views/riwayat-sertifikasi.blade.php

  PENTING:
  Template ini mengasumsikan sebuah variabel bernama $riwayat_list
  dikirim dari Controller.

  Contoh di Controller:
  $riwayat_list = auth()->user()->riwayatSertifikasi;
  return view('riwayat-sertifikasi', ['riwayat_list' => $riwayat_list]);
--}}

@extends('layouts.app-profil') {{-- Ganti ini dengan nama layout utama Anda --}}

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-12 md:py-16">

        {{--
          Logika utama:
          Cek apakah koleksi $riwayat_list kosong atau tidak.
        --}}

        @if($riwayat_list->isEmpty())

            {{-- =============================================== --}}
            {{-- BAGIAN 1: TAMPILAN JIKA RIWAYAT MASIH KOSONG    --}}
            {{-- =============================================== --}}

            <div class="flex flex-col items-center justify-center text-center py-20">
                <svg class="mx-auto h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>

                <h3 class="mt-4 text-sm font-semibold text-gray-900 uppercase tracking-wide">Riwayat Sertifikasi Anda</h3>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Anda belum mengambil sertifikasi
                </h1>
                <p class="mt-4 text-base text-gray-600 max-w-lg">
                    Semua skema sertifikasi yang telah Anda daftarkan atau selesaikan akan muncul di halaman ini.
                </p>
                <div class="mt-8">
                    <a href="{{ url('/skema') }}" {{-- Arahkan ke halaman daftar skema --}}
                       class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">
                        Lihat Daftar Skema
                    </a>
                </div>
            </div>

        @else

            {{-- =============================================== --}}
            {{-- BAGIAN 2: TAMPILAN JIKA RIWAYAT SUDAH ADA       --}}
            {{-- =============================================== --}}

            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-8">
                    Riwayat Sertifikasi Anda
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    {{--
                      Mulai Looping di sini.
                      Gunakan @foreach($riwayat_list as $riwayat)
                    --}}

                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl">
                        <img class="h-48 w-full object-cover"
                             src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80"
                             alt="Gambar Skema Web Developer">

                        <div class="p-5">
                            <span class_comment="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Kompeten
                            </span>

                            <h3 class="mt-3 text-lg font-semibold text-gray-900">
                                Junior Web Developer
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Tanggal Uji: 20 Oktober 2025
                            </p>

                            <div class="mt-5">
                                <a href="#" {{-- Ganti href dengan link ke sertifikat --}}
                                   class="w-full text-center rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                                    Lihat Sertifikat
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl">
                        <img class="h-48 w-full object-cover"
                             src="{{-- Ganti dengan gambar dari $riwayat->skema->gambar --}}"
                             alt="Gambar Skema IoT Specialist">

                        <div class="p-5">
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Belum Kompeten
                            </span>

                            <h3 class="mt-3 text-lg font-semibold text-gray-900">
                                IoT Specialist
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Tanggal Uji: 15 September 2025
                            </p>

                            <div class="mt-5">
                                <a href="#" {{-- Ganti href dengan link ke detail riwayat --}}
                                   class="w-full text-center rounded-md bg-gray-100 px-3.5 py-2.5 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-200 transition-colors">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl">
                        <img class="h-48 w-full object-cover"
                             src="{{-- Ganti dengan gambar dari $riwayat->skema->gambar --}}"
                             alt="Gambar Skema Data Analyst">

                        <div class="p-5">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Menunggu Hasil
                            </span>

                            <h3 class="mt-3 text-lg font-semibold text-gray-900">
                                Junior Data Analyst
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Tanggal Uji: 05 November 2025
                            </p>

                            <div class="mt-5">
                                <a href="#" {{-- Ganti href dengan link ke detail pendaftaran --}}
                                   class="w-full text-center rounded-md bg-gray-100 px-3.5 py-2.5 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-200 transition-colors">
                                    Lihat Detail Pendaftaran
                                </a>
                            </div>
                        </div>
                    </div>
                    {{--
                      Akhir Looping di sini.
                      Gunakan @endforeach
                    --}}

                </div> {{-- Tambahkan Pagination Links di sini jika perlu --}}
                {{-- <div class="mt-10">
                    $riwayat_list->links()
                </div> --}}

            </div>

        @endif

    </div>
</div>
@endsection
