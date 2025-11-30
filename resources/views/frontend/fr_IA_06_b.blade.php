@extends('layouts.app-sidebar')
@section('content')

<div class="p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">FR.IA.06.B. Lembar Kunci Jawaban Pertanyaan Tertulis Esai</h1>

    <!-- Box Info Atas -->
    <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">

        <!-- Baris 1: Skema & Judul -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">Skema Sertifikasi</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" placeholder="KKNI/Okupasi/Klaster">
            </div>
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">Judul</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
            </div>
        </div>

        <!-- Baris 2: TUK & Nomor -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 mt-3">
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Sewaktu/Tempat Kerja/Mandiri*">
            </div>
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
                <span class="font-medium text-gray-700">Nomor</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
            </div>
        </div>

        <hr class="my-4 border-gray-300">

        <!-- Baris Sisa Info -->
        <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
            <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">

            <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">

            <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
            <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
        </div>

        <p class="text-xs text-gray-500 mt-4">*Coret yang tidak perlu</p>
    </div>

    <!-- Box Kunci Jawaban & Penilaian -->
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg">Jawaban & Penilaian</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        {{-- Kolom 1: Lebar 75% --}}
                        <th class="p-3 text-left font-medium w-3/4 pl-5">Pertanyaan & Kunci Jawaban</th>
                        {{-- Kolom 2: Lebar 25% untuk Penilaian K/BK --}}
                        <th class="p-3 text-center font-medium w-1/4 border-l border-gray-200">Rekomendasi (K/BK)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">

                    @forelse ($soalItems as $index => $soal)
                        <tr>
                            {{-- Konten Pertanyaan & Kunci --}}
                            <td class="p-4 text-left hover:bg-gray-50 align-top">
                                <div class="flex flex-row gap-3">
                                    <span class="font-bold text-gray-700 pt-1">{{ $index + 1 }}.</span>

                                    <div class="w-full space-y-2">
                                        {{-- Pertanyaan --}}
                                        <div class="mb-1 text-gray-600 italic border-l-2 border-gray-300 pl-2">
                                            {{ $soal->soal_ia06 }}
                                        </div>

                                        {{-- Kunci Jawaban (Master) --}}
                                        <div class="relative">
                                            <span class="absolute top-2 left-2 text-xs font-bold text-green-600 bg-green-100 px-1 rounded">KUNCI:</span>
                                            <textarea
                                                class="w-full p-2 pt-8 border border-green-200 bg-green-50 text-gray-800 rounded-md focus:ring-green-500 focus:border-green-500 resize-y font-medium text-sm"
                                                rows="3"
                                                readonly
                                            >{{ $soal->kunci_jawaban_ia06 ?? 'Belum ada kunci jawaban yang diset.' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Penilaian K / BK --}}
                            <td class="p-4 align-middle text-center border-l border-gray-200 bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center gap-4 h-full">

                                    {{-- Opsi K (Kompeten) --}}
                                    <label class="inline-flex items-center cursor-pointer hover:bg-green-50 p-2 rounded transition">
                                        <input type="checkbox" name="nilai[{{ $soal->id_soal_ia06 }}]" value="K" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <span class="ml-2 font-bold text-gray-700">K</span>
                                        <span class="ml-1 text-xs text-gray-500">(Kompeten)</span>
                                    </label>

                                    {{-- Opsi BK (Belum Kompeten) --}}
                                    <label class="inline-flex items-center cursor-pointer hover:bg-red-50 p-2 rounded transition">
                                        <input type="checkbox" name="nilai[{{ $soal->id_soal_ia06 }}]" value="BK" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        <span class="ml-2 font-bold text-gray-700">BK</span>
                                        <span class="ml-1 text-xs text-gray-500">(Belum)</span>
                                    </label>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-8 text-center text-gray-500">
                                Belum ada data soal. Silakan input soal di halaman FR.IA.06.A
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- Box Penyusun dan Validator (Statis) -->
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg">Penyusun dan Validator</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left font-medium border-b">Status</th>
                        <th class="p-3 text-left font-medium w-[5%] border-b">No</th>
                        <th class="p-3 text-left font-medium border-b">Nama</th>
                        <th class="p-3 text-left font-medium border-b">Nomor MET</th>
                        <th class="p-3 text-left font-medium border-b">Tanda Tangan dan Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <!-- Baris Penyusun -->
                    <tr>
                        <td class="p-3 font-medium align-top border-r" rowspan="2">PENYUSUN</td>
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
                        <td class="p-3 font-medium align-top border-r" rowspan="2">VALIDATOR</td>
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