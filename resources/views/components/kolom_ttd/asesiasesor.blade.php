{{-- 
    File: ttdasesiasesor.blade.php
    Ini adalah komponen untuk menampilkan bagian tanda tangan Asesi dan Asesor.
    
    Pastikan Anda memanggil file ini dari dalam file lain yang sudah 
    menggunakan layout @extends('layouts.app-sidebar') 
    agar style Tailwind CSS-nya berfungsi.

    Cara memanggilnya: @include('nama-folder.ttdasesiasesor')
--}}

<div class="mt-8">
    <h3 class="font-semibold text-gray-700 mb-3">Pencatatan dan Validasi</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">

        <div class="space-y-3">
            <h4 class="font-medium text-gray-800">Asesi</h4>
            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                <span class="font-medium text-gray-700">Nama</span>
                <span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="John Doe">

                <span class="font-medium text-gray-700">Tandatangan/Tanggal</span>
                <span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="[Tanda Tangan] / 28-09-2025">
            </div>
        </div>

        <div class="space-y-3">
            <h4 class="font-medium text-gray-800">Asesor</h4>
            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                <span class="font-medium text-gray-700">Nama</span>
                <span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Ajeng Febria H.">

                <span class="font-medium text-gray-700">No. Reg. MET.</span>
                <span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="MET-12345">

                <span class="font-medium text-gray-700">Tandatangan/Tanggal</span>
                <span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="[Tanda Tangan] / 28-09-2025">
            </div>
        </div>
    </div>
</div>