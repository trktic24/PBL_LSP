{{-- File: resources/views/pages/ia-01/tampilan_awal.blade.php --}}

@extends('layouts.wizard', ['currentStep' => 1])

@section('title', 'IA.01 - Step 1: Aktivitas')

@section('wizard-content')

    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded shadow-sm" role="alert">
            <p class="font-bold">Oops! Ada yang belum diisi:</p>
            <ul class="list-disc list-inside mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ia01.storeCover', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan, 'urutan' => $unitKompetensi->urutan]) }}" method="POST">
        @csrf

        {{-- 1. HEADER: Logo & Judul --}}
        <div class="relative mb-8">
            {{-- Logo BNSP (Posisi Absolute di Kiri Atas atau Flex, sesuai selera. Di Figma dia di atas judul) --}}
            <div class="mb-4">
                <img src="{{ asset('images/bnsp_logo.png') }}" alt="BNSP" class="h-12 object-contain">
            </div>

            {{-- Judul Form --}}
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                FR.IA.01 - Ceklis Observasi Aktivitas di Tempat Kerja atau Tempat Kerja Simulasi
            </h1>
        </div>

        {{-- 2. TABEL INFO SKEMA (Sesuai Figma) --}}
        <div class="mb-8 border-2 border-gray-800 rounded-sm overflow-hidden">
            <div class="flex flex-col md:flex-row">
                {{-- Label Kiri --}}
                <div class="bg-white p-4 md:w-1/3 font-bold text-gray-900 flex items-center border-b md:border-b-0 md:border-r-2 border-gray-800">
                    Skema Sertifikasi
                </div>

                {{-- Isi Kanan --}}
                <div class="flex-1 bg-white">
                    {{-- Baris Judul --}}
                    <div class="flex border-b-2 border-gray-800">
                        <div class="w-24 p-2 pl-4 text-sm font-semibold text-gray-600">Judul</div>
                        <div class="p-2 text-gray-800 font-medium">: {{ $unitKompetensi->kelompokPekerjaan->nama_kelompok_pekerjaan ?? 'Junior Web Developer' }}</div>
                    </div>
                    {{-- Baris Tanggal --}}
                    <div class="flex">
                        <div class="w-24 p-2 pl-4 text-sm font-semibold text-gray-600">Tanggal</div>
                        {{-- Tanggal ini statis atau dari props, sesuaikan --}}
                        <div class="p-2 text-gray-800 font-medium">: {{ now()->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. FORM INPUT DATA (Grid Layout) --}}
        <div class="space-y-4 text-gray-800">

            {{-- Baris TUK --}}
            <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                <label class="font-bold">TUK</label>
                <div class="hidden md:block font-bold">:</div>
                <div class="flex flex-wrap gap-6">
                    @php $tuk_value = old('tuk', $data_sesi['tuk'] ?? ''); @endphp

                    <label class="inline-flex items-center cursor-pointer">
                        <span class="mr-2 text-sm">Sewaktu</span>
                        <input type="radio" name="tuk" value="sewaktu" class="form-radio h-5 w-5 text-gray-800 border-gray-400 focus:ring-gray-500" {{ $tuk_value == 'sewaktu' ? 'checked' : '' }}>
                    </label>

                    <label class="inline-flex items-center cursor-pointer">
                        {{-- Di Figma pake checkbox style kotak, tapi logic radio. Kita pake radio biar aman --}}
                        <input type="radio" name="tuk" value="tempat_kerja" class="form-radio h-5 w-5 text-gray-800 border-gray-400 focus:ring-gray-500" {{ $tuk_value == 'tempat_kerja' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">Tempat Kerja</span>
                    </label>

                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="tuk" value="mandiri" class="form-radio h-5 w-5 text-gray-800 border-gray-400 focus:ring-gray-500" {{ $tuk_value == 'mandiri' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">Mandiri</span>
                    </label>
                </div>
            </div>

            {{-- Baris Nama Asesor --}}
            <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                <label class="font-bold" for="asesor">Nama Asesor</label>
                <div class="hidden md:block font-bold">:</div>
                <input type="text" id="asesor" name="nama_asesor"
                       value="{{ old('nama_asesor', $data_sesi['nama_asesor'] ?? '') }}"
                       class="w-full max-w-md border-0 border-b border-gray-400 focus:border-blue-500 focus:ring-0 px-0 py-1 text-gray-800 placeholder-gray-400"
                       placeholder="Masukkan nama asesor">
            </div>

            {{-- Baris Nama Asesi --}}
            <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                <label class="font-bold">Nama Asesi</label>
                <div class="hidden md:block font-bold">:</div>
                <div class="text-gray-800 font-medium py-1">
                    {{ auth()->user()->name ?? 'Sucipto Kripsi' }}
                </div>
            </div>

            {{-- Baris Tanggal --}}
            <div class="grid grid-cols-1 md:grid-cols-[200px_20px_1fr] items-center">
                <label class="font-bold" for="tanggal">Tanggal</label>
                <div class="hidden md:block font-bold">:</div>
                <input type="date" id="tanggal" name="tanggal_asesmen"
           value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen'] ?? now()->format('Y-m-d')) }}"
           class="w-full max-w-xs border-0 border-b border-gray-400 focus:border-blue-500 focus:ring-0 px-0 py-1 text-gray-800 bg-gray-100 cursor-not-allowed"
           readonly>
            </div>

        </div>

        {{-- 4. TOMBOL SELANJUTNYA --}}
        <div class="flex justify-end mt-12">
            <button type="submit"
                    class="bg-[#3b82f6] hover:bg-blue-600 text-white font-semibold py-2 px-10 rounded-full shadow-lg transition duration-200">
                Mulai Asesmen
            </button>
        </div>
    </form>
@endsection
