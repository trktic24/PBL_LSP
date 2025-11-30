@extends('layouts.app-sidebar')
@section('content')

{{--
    LOGIKA ALPINE JS + LARAVEL SESSION:
    Kita inisialisasi showNotif berdasarkan session 'success' dari controller.
    Jika ada session success, showNotif = true (Modal muncul).
--}}
<div class="p-8" x-data="{ showNotif: {{ session('success') ? 'true' : 'false' }} }">

    <!-- ==================================== -->
    <!-- BAGIAN FORM (TAMPIL DEFAULT)       -->
    <!-- ==================================== -->
    <div x-show="!showNotif">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">FR.IA.06 Pertanyaan Tertulis Esai</h1>

        <!-- FORM PEMBUNGKUS UTAMA -->
        <form action="{{ route('fr_IA_06_c.store') }}" method="POST">
            @csrf

            <!-- Box Info Atas -->
            <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                    <span class="font-medium text-gray-700">Skema Sertifikasi</span><span class="font-medium">:</span>
                    <input type="text" name="skema" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Junior Web Developer">

                    <span class="font-medium text-gray-700">Judul / Nomor</span><span class="font-medium">:</span>
                    <input type="text" name="judul" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Sertifikasi KKNI II / 0341XXXXXXX">
                </div>

                <hr class="my-4 border-gray-300">

                <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
                    <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="sewaktu" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Sewaktu
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="tempat_kerja" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Tempat Kerja
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="mandiri" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Mandiri
                        </label>
                    </div>

                    <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
                    <input type="text" name="nama_asesor" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">

                    <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
                    <input type="text" name="nama_asesi" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="John Doe">

                    <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
                    <input type="date" name="tanggal" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="{{ date('Y-m-d') }}">
                </div>
                <p class="text-xs text-gray-500 mt-4">*Coret yang tidak perlu</p>
            </div>

            <!-- ===================================================== -->
            <!-- BOX TABEL PERTANYAAN (DINAMIS)                        -->
            <!-- ===================================================== -->
            <div class="mb-6">
                <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left font-medium w-full pl-5">Pertanyaan & Jawaban</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">

                            {{-- LOOPING SOAL DARI DATABASE --}}
                            @forelse ($soalItems as $index => $soal)
                                <tr>
                                    <td class="p-4 text-left">
                                        <div class="flex flex-row gap-3 align-top">
                                            <span class="font-bold text-gray-800 pt-1">{{ $index + 1 }}.</span>

                                            <div class="w-full space-y-3">
                                                {{-- 1. Tampilkan Pertanyaan (Agar Asesi bisa baca) --}}
                                                <div class="bg-blue-50 p-3 rounded border border-blue-100 text-gray-800 font-medium">
                                                    {{ $soal->soal_ia06 }}
                                                </div>

                                                {{-- 2. Input Jawaban Asesi --}}
                                                {{-- Name: jawaban[ID_SOAL] agar mudah ditangkap controller --}}
                                                <textarea
                                                    name="jawaban[{{ $soal->id_soal_ia06 }}]"
                                                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-y shadow-sm"
                                                    rows="4"
                                                    placeholder="Tulis jawaban Anda di sini secara lengkap..."
                                                    required
                                                ></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-8 text-center text-gray-500">
                                        Soal belum tersedia. Hubungi Admin.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Box Umpan Balik Asesi -->
            <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Umpan Balik dari Asesi:</h3>
                <div class="grid grid-cols-[1fr] gap-y-3 text-sm">
                    <label class="font-medium text-gray-700">Aspek pengetahuan seluruh unit kompetensi yang diujikan (tercapai/belum tercapai)*</label>
                    <textarea name="umpan_balik" class="p-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-y" rows="3" placeholder="Tulis catatan umpan balik (opsional)..."></textarea>
                </div>
            </div>

            <!-- Bagian Tanda Tangan -->
            <div class="mt-8">
                <h3 class="font-semibold text-gray-700 mb-3">Pencatatan dan Validasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">
                    <!-- Kolom Asesi -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-800">Asesi</h4>
                        <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                            <span class="font-medium text-gray-700">Nama</span><span class="font-medium">:</span>
                            <input type="text" class="p-1 w-full border-b border-gray-300 bg-transparent" value="John Doe" readonly>

                            <span class="font-medium text-gray-700">Tanda Tangan</span><span class="font-medium">:</span>
                            <div class="text-xs text-gray-500 italic">[Ditandatangani Digital saat Submit]</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit (Type Submit) -->
            <div class="mt-8 text-right">
                <button type="submit"
                        class="bg-blue-600 text-white font-medium py-3 px-8 rounded-md hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                    Simpan & Selesaikan Ujian
                </button>
            </div>

        </form>
    </div>

    <!-- ==================================== -->
    <!-- BAGIAN NOTIFIKASI SUKSES (MODAL)    -->
    <!-- ==================================== -->
    <div x-show="showNotif" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white p-12 rounded-lg shadow-2xl text-center max-w-md mx-auto border border-gray-200 transform transition-all scale-100">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">FR.IA.06 - Terkirim!</h1>

            <svg class="w-24 h-24 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <p class="text-xl font-medium text-gray-700 mb-2">Jawaban Anda berhasil disimpan.</p>
            <p class="text-sm text-gray-500">Terima kasih telah menyelesaikan asesmen ini.</p>

            <div class="mt-8 flex justify-center gap-4">
                <!-- Tombol Lihat Jawaban -->
                <button type="button" @click="showNotif = false" class="bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-md hover:bg-gray-300 transition">
                    Lihat Jawaban
                </button>

                <!-- Tombol Ke Dashboard (Ganti route('home') jika perlu) -->
                <a href="{{ url('/') }}" class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700 transition">
                    Ke Dashboard
                </a>
            </div>
        </div>
    </div>

</div>
@endsection