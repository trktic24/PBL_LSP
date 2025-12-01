{{-- 
  File: resources/views/components/kolom_ttd/penyusunvalidator.blade.php
  (Ganti seluruh isi file dengan kode ini)
--}}

{{-- 
  Container utama ini menggantikan <table> lama.
  Saya menggunakan `space-y-4` untuk memberi jarak antar bagian.
  Layout ini meniru style dari gambar yang Anda berikan.
--}}
<div class="border border-gray-200 shadow-md p-4 rounded-lg bg-white">

    {{-- Judul utama, diambil dari style gambar --}}
    <h3 class="text-lg font-semibold">
        Pencatatan dan Validasi
    </h3>

    {{-- 
      BAGIAN PENYUSUN
      Ini menggantikan 2 baris pertama (rowspan="2") dari <tbody> tabel lama.
      Saya beri jarak 'mb-3' (margin-bottom) antara Penyusun 1 dan 2.
    --}}
    <div>
        <h4 class="font-medium mb-2 text-base">Penyusun</h4>
        
        {{-- Data Penyusun 1 --}}
        <div class="grid grid-cols-[auto_auto_1fr] gap-x-2 gap-y-1 text-sm mb-3">
            <div>Nama</div>
            <div>:</div>
            {{-- 'min-h-[1.5em]' untuk tinggi minimal & border-b untuk garis --}}
            <div class="min-h-[1.5em]">
                {{-- Isi data nama penyusun 1 di sini --}}
            </div>

            <div>No. Reg. MET.</div>
            <div>:</div>
            <div class="min-h-[1.5em]">
                {{-- Isi data no reg 1 di sini --}}
            </div>

            <div>Tanggal</div>
            <div>:</div>
            <div class="min-h-[1.5em]">
                {{-- Isi data tanggal 1 di sini --}}
            </div>

            <div>Tanda Tangan</div>
            <div>:</div>
            <div class="h-16">
                {{-- Area Tanda Tangan (seperti h-16 di kode lama) --}}
            </div>
        </div>

    {{-- Garis pemisah antar bagian --}}
    <hr class="my-4">

    {{-- 
      BAGIAN VALIDATOR
      Ini menggantikan 2 baris terakhir (rowspan="2") dari <tbody> tabel lama.
    --}}
    <div>
        <h4 class="font-medium mb-2 text-base">Validator</h4>
        
        {{-- Data Validator 1 --}}
        <div class="grid grid-cols-[auto_auto_1fr] gap-x-2 gap-y-1 text-sm mb-3">
            <div>Nama</div>
            <div>:</div>
            <div class="min-h-[1.5em]"></div>

            <div>No. Reg. MET.</div>
            <div>:</div>
            <div class="min-h-[1.5em]"></div>

            <div>Tanggal</div>
            <div>:</div>
            <div class="min-h-[1.5em]"></div>

            <div>Tanda Tangan</div>
            <div>:</div>
            <div class="h-16"></div>
        </div>

    </div>

</div>