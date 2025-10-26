@extends('layouts.app-layout')

@section('content')
<section class="bg-gray-50 min-h-screen">
  <div class="max-w-4xl mx-auto px-6 py-12">
    <h2 class="text-2xl md:text-3xl font-semibold text-center text-gray-900 mb-12">
      Alur Proses Sertifikasi
    </h2>

    <!-- Timeline Container -->
    <div class="relative border-l-2 border-gray-200 ml-5">
      <!-- Step 1 -->
      <div class="mb-10 ml-8 relative">
        <div class="absolute -left-5 top-1 w-5 h-5 rounded-full bg-blue-500 border-4 border-white shadow-md"></div>
        <div class="bg-yellow-50 shadow-md rounded-xl p-5">
          <h3 class="font-semibold text-gray-900">Pendaftaran & Verifikasi Dokumen</h3>
          <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="mb-10 ml-8 relative">
        <div class="absolute -left-5 top-1 w-5 h-5 rounded-full bg-gray-300 border-4 border-white shadow-md"></div>
        <div class="bg-gray-200 shadow-md rounded-xl p-5">
          <h3 class="font-semibold text-gray-900">Pembayaran</h3>
          <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="mb-10 ml-8 relative">
        <div class="absolute -left-5 top-1 w-5 h-5 rounded-full bg-gray-300 border-4 border-white shadow-md"></div>
        <div class="bg-gray-200 shadow-md rounded-xl p-5">
          <h3 class="font-semibold text-gray-900">Pelaksanaan Asesmen Kompetensi</h3>
          <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="ml-8 relative">
        <div class="absolute -left-5 top-1 w-5 h-5 rounded-full bg-gray-300 border-4 border-white shadow-md"></div>
        <div class="bg-gray-200 shadow-md rounded-xl p-5">
          <h3 class="font-semibold text-gray-900">Penerbitan Sertifikat</h3>
          <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Blue Section -->
  <div class="bg-gradient-to-b from-blue-500 to-blue-700 text-white py-16 text-center mt-20">
    <p class="uppercase tracking-widest text-sm mb-2">Sertifikasi Profesi untuk Karier Anda</p>
    <h2 class="text-2xl md:text-3xl font-semibold mb-4">
      Tingkatkan Kompetensi Profesional Anda
    </h2>
    <p class="max-w-2xl mx-auto text-sm md:text-base mb-8">
      LSP Polines berkomitmen menghasilkan tenaga kerja kompeten yang siap bersaing dan diakui secara nasional maupun internasional.
    </p>
    <a href="#" class="inline-block bg-white text-blue-600 font-medium px-6 py-3 rounded-full shadow-md hover:bg-blue-100 transition">
      Hubungi Kami
    </a>

    <div class="mt-10 text-xs opacity-80">
      <p>Jl. Prof. Soedarto, SH, Tembalang, Semarang, Jawa Tengah.</p>
      <p class="mt-1">(024) 7473417 ext.256 | lsp@polines.ac.id</p>
    </div>

    <div class="mt-10 text-sm opacity-70">
      © 2025 LSP POLINES. All rights reserved.
    </div>
  </div>
</section>
@endsection
