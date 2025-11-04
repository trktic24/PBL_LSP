@extends('layouts.app-sidebar')
@section('content')

<div class="p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">FR.IA.06.B. Lembar Kunci Jawaban Pertanyaan Tertulis Esai</h1>

    <!-- Box Info Atas -->
    <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        
        <!-- Baris 1: Skema & Judul -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
            <!-- Kolom Kiri -->
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
            </div>
            <!-- Kolom Kanan -->
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">Judul</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
            </div>
        </div>

        <!-- Baris 2: TUK & Nomor -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 mt-3">
            <!-- Kolom Kiri -->
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
            </div>
            <!-- Kolom Kanan -->
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
                <span class="font-medium text-gray-700">Nomor</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
            </div>
        </div>
        
        <!-- Baris 3: TUK Pilihan (sesuai gambar) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 mt-3">
             <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
                <span classs="font-medium text-gray-700"></span><span class="font-medium"></span>
                <span class="text-gray-600 text-xs">Sewaktu/Tempat Kerja/Mandiri*</span>
             </div>
             <div></div> <!-- Kolom kanan kosong untuk perataan -->
        </div>

        <hr class="my-4 border-gray-300">
        
        <!-- Baris Sisa Info -->
        <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
            <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">

            <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">

            <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
            <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="">
        </div>

        <p class="text-xs text-gray-500 mt-4">*Coret yang tidak perlu</p>
    </div>

    <!-- Box Kunci Jawaban (Gaya Tabel) -->
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg">Jawaban</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left font-medium w-full pl-5">Kunci Jawaban</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    
                    @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td class="p-3 text-left">
                                <!-- Flexbox untuk perataan nomor dan textarea -->
                                <div class="flex flex-row gap-3 align-top">
                                    <span class="font-medium pt-2">{{ $i }}.</span>
                                    <textarea 
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-y" 
                                        rows="3" 
                                        placeholder="Tulis kunci jawaban esai di sini..."
                                    ></textarea>
                                </div>
                            </td>
                        </tr>
                    @endfor

                </tbody>
            </table>
        </div>
    </div>

    <!-- Box Penyusun dan Validator -->
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
