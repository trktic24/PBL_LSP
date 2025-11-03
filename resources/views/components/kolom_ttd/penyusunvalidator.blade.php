{{-- 
    File: ttdpenyusunvalidator.blade.php
    Ini adalah komponen yang diambil dari FR.IA.07. 
    Pastikan Anda memanggil file ini dari dalam file lain yang sudah 
    menggunakan layout @extends('layouts.app-sidebar') 
    agar style Tailwind CSS-nya berfungsi.

    Cara memanggilnya: @include('nama-folder.ttdpenyusunvalidator')
--}}

<h3 class="font-bold mt-6">PENYUSUN DAN VALIDATOR</h3>
<div class="border border-gray-900 shadow-md w-full max-w-4xl mx-auto">
<table class="w-full border-collapse">
    <tbody>
        <tr class="h-20">
            <td class="border border-gray-900 p-2 font-bold text-center w-[80px] bg-black text-white">STATUS</td>
            <td class="border border-gray-900 p-2 font-bold text-center w-[30px] bg-black text-white">NO</td>
            <td class="border border-gray-900 p-2 font-bold text-center w-[200px] bg-black text-white">NAMA</td>
            <td class="border border-gray-900 p-2 font-bold text-center w-[100px] bg-black text-white">NOMOR MET</td>
            <td class="border border-gray-900 p-2 font-bold text-center w-[80px] bg-black text-white">TANDA TANGAN DAN TANGGAL</td>
        </tr>

        <tr class="bg-gray-100 font-semibold">
            <td class="border border-gray-900 p-2 bg-black text-white">PENYUSUN</td>
            <td class="border border-gray-900 p-2 text-center">1</td>
            <td class="border border-gray-900 p-2">
                {{-- Tambahkan input jika perlu --}}
                {{-- <input type="text" class="form-input w-full"> --}}
            </td>
            <td class="border border-gray-900 p-2">
                {{-- <input type="text" class="form-input w-full"> --}}
            </td>
            <td class="border border-gray-900 p-2 text-center h-16">
                {{-- Sel untuk Tanda Tangan --}}
            </td>
        </tr>

        {{-- 
            Catatan: Kode asli Anda menggunakan rowspan="2" tapi hanya ada 1 baris.
            Ini adalah kode yang persis dari file Anda.
        --}}
        <tr class="bg-gray-100 font-semibold">
            <td rowspan="2" class="border border-gray-900 p-2 bg-black text-white align-top text-center">VALIDATOR</td>
            <td class="border border-gray-900 p-2 text-center">2</td>
            <td class="border border-gray-900 p-2">
                {{-- <input type="text" class="form-input w-full"> --}}
            </td>
            <td class="border border-gray-900 p-2">
                {{-- <input type="text" class="form-input w-full"> --}}
            </td>
            <td class="border border-gray-900 p-2 h-16">
                {{-- Sel untuk Tanda Tangan --}}
            </td>
        </tr>
        
        {{-- 
            Baris ini tidak ada di kode asli Anda, 
            tapi diperlukan jika rowspan="2" ingin berfungsi.
            Hapus saja jika Anda ingin sama persis.
        --}}
         <tr class="bg-gray-100 font-semibold">
            <td class="border border-gray-900 p-2 text-center"></td>
            <td class="border border-gray-900 p-2"></td>
            <td class="border border-gray-900 p-2"></td>
            <td class="border border-gray-900 p-2 h-16"></td>
        </tr>

    </tbody>
</table>
</div>