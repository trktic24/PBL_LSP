{{-- Menggunakan layout sidebar yang sama --}}
@extends('layouts.app-sidebar')

@section('content')
<main class="main-content">
    <x-header_form.header_form title="FR.IA.02. TPD - TUGAS PRAKTIK DEMONSTRASI" />

    <!-- Box Info Atas (Mengadopsi gaya dari fr_IA_05_a.blade.php) -->
    <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

            <!-- Kolom Kiri -->
            <div class="space-y-3">
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Junior Web Developer" placeholder="Judul Skema...">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="sewaktu" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Sewaktu
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="tempat_kerja" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked> Tempat Kerja
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="mandiri" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Mandiri
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="A-001 - John Doe" placeholder="Nama Asesor...">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-3">
                <div class="grid grid-cols-[100px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nomor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="SKM.JWD.001" placeholder="Nomor Skema...">
                </div>
                <div class="grid grid-cols-[100px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Budi Santoso" placeholder="Nama Asesi...">
                </div>
                <div class="grid grid-cols-[100px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
                    <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Box Petunjuk (Gaya baru) -->
    <div class="bg-blue-50 p-6 rounded-md shadow-sm mb-6 border border-blue-200">
        <h3 class="text-lg font-bold text-blue-800 mb-3">Petunjuk</h3>
        <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
            <li>Baca dan pelajari setiap instruksi kerja di bawah ini dengan cermat sebelum melaksanakan praktek.</li>
            <li>Klarifikasi kepada asesor kompetensi apabila ada hal-hal yang belum jelas.</li>
            <li>Laksanakan pekerjaan sesuai dengan urutan proses yang sudah ditetapkan.</li>
            <li>Seluruh proses kerja mengacu kepada SOP/WI yang dipersyaratkan (Jika Ada).</li>
        </ul>
    </div>

    <!-- Box Skenario Tugas -->
    <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <h3 class="text-black font-bold text-gray-800 mb-4">Skenario Tugas Praktik Demonstrasi</h3>

        <!-- Tabel Kelompok Pekerjaan (Mengadopsi gaya dari FR_IA_05_B.blade.php) -->
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left font-medium w-[25%]">Kelompok Pekerjaan ...</th>
                        <th class="p-3 text-left font-medium w-[10%]">No.</th>
                        <th class="p-3 text-left font-medium w-[25%]">Kode Unit</th>
                        <th class="p-3 text-left font-medium w-[40%]">Judul Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr>
                        <td class="p-3" rowspan="3">Kelompok Pekerjaan 1: Implementasi Desain</td>
                        <td class="p-3 text-center">1.</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md" value="J.620100.004.02"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md" value="Menggunakan Struktur Data"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">2.</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md" value="J.620100.005.01"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md" value="Mengimplementasikan User Interface"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">Dst.</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Kode Unit..."></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Judul Unit..."></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Skenario, Perlengkapan, Waktu -->
        <div class="space-y-4">
            <div>
                <label class="text-black font-medium mb-3">Skenario Tugas Praktik Demonstrasi:</label>
                <textarea class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="4" placeholder="Jelaskan skenario tugas di sini..."></textarea>
            </div>
            <div>
                <label class="text-black font-medium mb-3">Perlengkapan dan Peralatan:</label>
                <textarea class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="2" placeholder="Contoh: Laptop, Teks Editor, Web Browser..."></textarea>
            </div>
            <div>
                <label class="text-black font-medium mb-3">Waktu:</label>
                <input type="text" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Contoh: 120 Menit">
            </div>
        </div>
    </div>

    <!-- Box Tanda Tangan Asesi & Asesor (Mengadopsi gaya dari fr_IA_06_c.blade.php) -->
    {{-- 
        KODE LAMA DIHAPUS (baris 148-199):
        <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            ... (kode statis untuk tanda tangan) ...
        </div>
    --}}
    
    {{-- KODE BARU: Memanggil komponen yang sudah ada --}}
    @include('components.kolom_ttd.asesiasesor')
    
    <!-- Box Penyusun dan Validator (Mengadopsi gaya dari fr_IA_06_b.blade.php) -->
    {{-- 
        KODE LAMA DIHAPUS (baris 201-246):
        <div class="mb-6">
            <h3 class="font-semibold text-gray-700 mb-3 text-lg">Penyusun dan Validator</h3>
            ... (kode statis untuk tabel penyusun) ...
        </div>
    --}}

    {{-- KODE BARU: Memanggil komponen yang sudah ada --}}
    @include('components.kolom_ttd.penyusunvalidator')

    <!-- Tombol Footer (gaya Tailwind) -->
    <div class="mt-8 flex justify-between">
        <a href="#" {{-- URL placeholder --}}
           class="bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-md hover:bg-gray-300">
            Kembali
        </a>
        
        <button type="submit" class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700">
            Simpan Form
        </button>
    </div>
</div>
@endsection