@extends('layouts.app-sidebar')
@section('content')

<div class="p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">FR.IA.06A. DPT - Daftar Pertanyaan Tertulis Esai</h1>

    <!-- Box Info Atas (Sesuai Layout Gambar 06A) -->
    <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">

            <!-- Kolom Kiri -->
            <div class="space-y-3">
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
                    <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Waktu</span><span class="font-medium">:</span>
                    <input type="time" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-3">
                <div class="grid grid-cols-[100px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Judul</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[100px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nomor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
                </div>
                <div class="grid grid-cols-[100px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700"></span><span class="font-medium"></span>
                    <span class="text-gray-600 text-xs pt-1">Sewaktu/Tempat Kerja/Mandiri*</span>
                </div>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">*Coret yang tidak perlu</p>
    </div>

    <!-- Box Daftar Pertanyaan -->
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Jawablah semua pertanyaan di bawah ini:</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm bg-white divide-y divide-gray-200">
            <!-- Loop untuk 5 pertanyaan -->
            @for ($i = 1; $i <= 5; $i++)
            <div class="p-3">
                <div class="flex flex-row gap-3 align-top">
                    <span class="font-medium pt-2">{{ $i }}.</span>
                    <textarea
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-y"
                        rows="2"
                        placeholder="Tulis PERTANYAAN esai ke-{{ $i }} di sini..."
                    ></textarea>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Box Penyusun dan Validator (Sama seperti 06B) -->
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg">Penyusun dan Validator</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left font-medium">Status</th>
                        <th class="p-3 text-left font-medium w-[5%]">No</th>
                        <th class="p-3 text-left font-medium">Nama</th>
                        <th class="p-3 text-left font-medium">Nomor MET</th>
                        <th class="p-3 text-left font-medium">Tanda Tangan dan Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <!-- Baris Penyusun -->
                    <tr>
                        <td class="p-3 font-medium align-top" rowspan="2">PENYUSUN</td>
                        <td class="p-3 align-top pt-5">1</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                    </tr>
                    <tr>
                        <td class="p-3 align-top pt-5">2</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                    </tr>

                    <!-- Baris Validator -->
                    <tr>
                        <td class="p-3 font-medium align-top" rowspan="2">VALIDATOR</td>
                        <td class="p-3 align-top pt-5">1</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                    </tr>
                    <tr>
                        <td class="p-3 align-top pt-5">2</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
