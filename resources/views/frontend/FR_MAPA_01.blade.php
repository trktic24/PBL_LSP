{{-- 
    Nama File: resources/views/asesmen/fr-mapa-01.blade.php 
    Deskripsi: Form FR.MAPA.01 menggunakan layout Wizard
--}}

@extends('layouts.app-sidebar-asesi')

@section('content')

    {{-- Style khusus untuk tabel border hitam --}}
    <style>
        .form-table, .form-table td, .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>

    {{-- Header Mobile (Tombol Sidebar) --}}
    <div class="lg:hidden p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 mb-6 rounded-lg">
        <span class="font-bold">Form Assessment</span>
        <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- FORM START --}}
    <form action="{{ route('mapa01.store') }}" method="POST">
        @csrf
        
        <div class="bg-white">
            
            {{-- KOP SURAT --}}
            <div class="mb-10 text-center">
                <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div> 
                
                <h1 class="text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-2 inline-block">
                    FR.MAPA.01. MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN
                </h1>
            </div>

            {{-- INFO SKEMA --}}
            <div class="grid grid-cols-[250px_auto] gap-y-3 text-sm mb-10 text-gray-700">
                <div class="font-bold text-black">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</div>
                <div>
                    <div class="flex gap-2"><span class="font-semibold w-20">Judul</span> : Junior Web Programmer</div>
                    <div class="flex gap-2"><span class="font-semibold w-20">Nomor</span> : -</div>
                </div>
            </div>

            {{-- BAGIAN 1: PENDEKATAN ASESMEN --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-4">1. Menentukan Pendekatan Asesmen</h3>
                <table class="w-full text-sm form-table mb-6">
                    <tbody>
                        {{-- 1.1 Asesi --}}
                        <tr>
                            <td rowspan="5" class="p-2 font-bold align-top w-10 text-center">1.1</td>
                            <td rowspan="5" class="p-2 font-bold align-top w-32">Asesi</td>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]" value="Hasil pelatihan dan atau pendidikan, Kurikulum & fasilitas telusur"> Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]" value="Hasil pelatihan - belum berbasis kompetensi"> Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]" value="Pekerja berpengalaman - telusur"> Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]" value="Pekerja berpengalaman - belum berbasis kompetensi"> Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]" value="Pelatihan / belajar mandiri"> Pelatihan / belajar mandiri atau otodidak.
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-2"></td>
                            <td class="p-2 font-bold">Tujuan Sertifikasi</td>
                            <td class="p-2">
                                <div class="flex gap-6">
                                    <label><input type="radio" name="tujuan_sertifikasi" value="Sertifikasi" checked> Sertifikasi</label>
                                    <label><input type="radio" name="tujuan_sertifikasi" value="PKT"> PKT</label>
                                    <label><input type="radio" name="tujuan_sertifikasi" value="RPL"> RPL</label>
                                    <label><input type="radio" name="tujuan_sertifikasi" value="Lainnya"> Lainnya</label>
                                </div>
                            </td>
                        </tr>

                        {{-- Konteks Asesmen --}}
                        <tr>
                            <td rowspan="3" class="p-2"></td>
                            <td rowspan="3" class="p-2 font-bold align-top">Konteks Asesmen</td>
                            <td class="p-2">
                                <span class="font-bold">Lingkungan:</span> 
                                <label class="ml-2"><input type="checkbox" name="konteks_lingkungan[]" value="Tempat kerja nyata"> Tempat kerja nyata</label>
                                <label class="ml-4"><input type="checkbox" name="konteks_lingkungan[]" value="Tempat kerja simulasi"> Tempat kerja simulasi</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <span class="font-bold">Peluang mengumpulkan bukti:</span> 
                                <label class="ml-2"><input type="checkbox" name="peluang_bukti[]" value="Tersedia"> Tersedia</label>
                                <label class="ml-4"><input type="checkbox" name="peluang_bukti[]" value="Terbatas"> Terbatas</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                               <span class="font-bold">Siapa yang melakukan asesmen:</span>
                               <div class="mt-1">
                                   <label class="block"><input type="checkbox" name="pelaksana_asesmen[]" value="Lembaga Sertifikasi"> Lembaga Sertifikasi</label>
                                   <label class="block"><input type="checkbox" name="pelaksana_asesmen[]" value="Organisasi Pelatihan"> Organisasi Pelatihan</label>
                                   <label class="block"><input type="checkbox" name="pelaksana_asesmen[]" value="Asesor Perusahaan"> Asesor Perusahaan</label>
                               </div>
                            </td>
                        </tr>
                        
                        {{-- Konfirmasi Orang Relevan --}}
                        <tr>
                            <td rowspan="4" class="p-2"></td>
                            <td rowspan="4" class="p-2 font-bold align-top">Konfirmasi dengan orang yang relevan</td>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded" name="konfirmasi_relevan[]" value="Manajer sertifikasi LSP">
                                    <span>Manajer sertifikasi LSP</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded" name="konfirmasi_relevan[]" value="Master Asesor / Master Trainer / Lead Asesor Kompetensi">
                                    <span>Master Asesor / Master Trainer / Lead Asesor Kompetensi</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded" name="konfirmasi_relevan[]" value="Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar">
                                    <span>Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded" name="konfirmasi_relevan[]" value="Manajer atau Supervisor ditempat kerja">
                                    <span>Manajer atau Supervisor ditempat kerja</span>
                                </label>
                            </td>
                        </tr>

                        {{-- 1.2 Standar Industri --}}
                        <tr>
                            <td class="p-2 font-bold text-center">1.2</td>
                            <td class="p-2 font-bold align-top">Standar Industri / Tempat Kerja</td>
                            <td class="p-2">
                                <div class="mb-2"><span class="font-bold">Standar Kompetensi:</span> <input type="text" name="standar_kompetensi" class="inline-block ml-2 border-b border-gray-300 focus:outline-none" value="SKKNI Junior Web Programmer"></div>
                                <div class="mb-2"><span class="font-bold">Spesifikasi Produk:</span> <input type="text" name="spesifikasi_produk" class="inline-block ml-2 border-b border-gray-300 focus:outline-none" value="Aplikasi Web Sederhana"></div>
                                <div><span class="font-bold">Pedoman Khusus:</span> <input type="text" name="pedoman_khusus" class="inline-block ml-2 border-b border-gray-300 focus:outline-none" value="SOP Pengembangan Perangkat Lunak"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- BAGIAN 2: PERENCANAAN ASESMEN --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-4">2. Perencanaan Asesmen</h3>
                
                <div class="mb-4">
                    <p class="font-bold text-sm mb-2">Kelompok Pekerjaan 1</p>
                    <table class="w-full text-sm form-table bg-gray-50">
                        <thead>
                            <tr>
                                <th class="p-2 w-12">No.</th>
                                <th class="p-2 w-40">Kode Unit</th>
                                <th class="p-2">Judul Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-2 text-center">1.</td>
                                <td class="p-2 text-center"><input type="text" name="kelompok1[0][kode_unit]" class="w-full text-center border-none bg-transparent" value="J.620100.004.02"></td>
                                <td class="p-2"><input type="text" name="kelompok1[0][judul_unit]" class="w-full border-none bg-transparent" value="Mengimplementasikan User Interface"></td>
                            </tr>
                            <tr>
                                <td class="p-2 text-center">2.</td>
                                <td class="p-2 text-center"><input type="text" name="kelompok1[1][kode_unit]" class="w-full text-center border-none bg-transparent" value="J.620100.011.01"></td>
                                <td class="p-2"><input type="text" name="kelompok1[1][judul_unit]" class="w-full border-none bg-transparent" value="Melakukan Debugging"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Tabel Checklist Besar --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm form-table min-w-[1200px]">
                        <thead class="bg-white text-center font-bold">
                            <tr>
                                <th rowspan="2" class="p-2 border border-black w-40 align-middle">Unit Kompetensi</th>
                                <th rowspan="2" class="p-2 border border-black w-64 align-middle">
                                    Bukti-Bukti<br>
                                    <span class="font-normal text-xs italic mt-1 block">
                                        (Kinerja, Produk, Portofolio, dan / atau Pengetahuan)
                                    </span>
                                </th>
                                <th colspan="3" class="p-2 border border-black w-24 align-middle">Jenis Bukti</th>
                                <th colspan="6" class="p-2 border border-black align-middle">
                                    Metode dan Perangkat Asesmen
                                </th>
                            </tr>
                            
                            <tr class="text-xs">
                                <th class="p-2 border border-black w-10 align-middle font-bold text-sm">L</th>
                                <th class="p-2 border border-black w-10 align-middle font-bold text-sm">TL</th>
                                <th class="p-2 border border-black w-10 align-middle font-bold text-sm">T</th>
                                
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span class="font-bold block text-center mb-1 text-sm">Observasi langsung</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span class="font-bold block text-center mb-1 text-sm">Kegiatan Terstruktur</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span class="font-bold block text-center mb-1 text-sm">Tanya Jawab</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span class="font-bold block text-center mb-1 text-sm">Verifikasi Portofolio</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span class="font-bold block text-center mb-1 text-sm">Reviu Produk</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span class="font-bold block text-center mb-1 text-sm">Verifikasi Pihak Ketiga</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Unit 1 --}}
                            <tr>
                                <td class="p-2 border border-black align-top font-semibold">1. Mengimplementasikan User Interface</td>
                                <td class="p-2 border border-black align-top">
                                    <textarea name="unit_kompetensi[0][bukti]" class="w-full h-24 border-none resize-none text-sm focus:outline-none placeholder-gray-400" placeholder="Tulis bukti di sini..."></textarea>
                                </td>
                                
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][L]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][TL]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][T]" class="w-5 h-5 cursor-pointer" value="1"></td>

                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][observasi]" class="w-5 h-5 cursor-pointer" checked value="1"></td> 
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][kegiatan_terstruktur]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][tanya_jawab]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][verifikasi_portofolio]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][reviu_produk]" class="w-5 h-5 cursor-pointer" checked value="1"></td> 
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[0][verifikasi_pihak_ketiga]" class="w-5 h-5 cursor-pointer" value="1"></td>
                            </tr>

                            {{-- Unit 2 --}}
                            <tr>
                                <td class="p-2 border border-black align-top font-semibold">2. Melakukan Debugging</td>
                                <td class="p-2 border border-black align-top">
                                    <textarea name="unit_kompetensi[1][bukti]" class="w-full h-24 border-none resize-none text-sm focus:outline-none placeholder-gray-400" placeholder="Tulis bukti di sini..."></textarea>
                                </td>
                                
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][L]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][TL]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][T]" class="w-5 h-5 cursor-pointer" value="1"></td>

                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][observasi]" class="w-5 h-5 cursor-pointer" checked value="1"></td> 
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][kegiatan_terstruktur]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][tanya_jawab]" class="w-5 h-5 cursor-pointer" checked value="1"></td> 
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][verifikasi_portofolio]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][reviu_produk]" class="w-5 h-5 cursor-pointer" value="1"></td>
                                <td class="p-1 border border-black text-center align-middle"><input type="checkbox" name="unit_kompetensi[1][verifikasi_pihak_ketiga]" class="w-5 h-5 cursor-pointer" value="1"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- BAGIAN 3: MODIFIKASI DAN KONTEKSTUALISASI --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-4">3. Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi</h3>
                <table class="w-full text-sm form-table">
                    <tbody>
                        {{-- 3.1 a --}}
                        <tr>
                            <td rowspan="2" class="p-2 font-bold w-10 text-center align-top">3.1</td>
                            <td class="p-2 w-1/3 align-top">a. Karakteristik Kandidat</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer" name="karakteristik_ada_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <span class="text-gray-500 italic text-xs block mb-1">*Ada / Tidak ada karakteristik khusus:</span>
                                        <textarea name="karakteristik_kandidat" class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none" :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'" :disabled="!active" placeholder="Tuliskan jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- 3.1 b --}}
                        <tr>
                            <td class="p-2 w-1/3 align-top">b. Kebutuhan kontekstualisasi</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer" name="kebutuhan_kontekstualisasi_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <span class="text-gray-500 italic text-xs block mb-1">*Ada / Tidak ada kebutuhan kontekstualisasi:</span>
                                        <textarea name="kebutuhan_kontekstualisasi" class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none" :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'" :disabled="!active" placeholder="Tuliskan jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- 3.2 --}}
                        <tr>
                            <td class="p-2 font-bold text-center align-top">3.2</td>
                            <td class="p-2 align-top">Saran dari paket pelatihan / pengembang</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer" name="saran_paket_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <textarea name="saran_paket_pelatihan" class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none" :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'" :disabled="!active" placeholder="Tulis saran jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- 3.3 --}}
                        <tr>
                            <td class="p-2 font-bold text-center align-top">3.3</td>
                            <td class="p-2 align-top">Penyesuaian perangkat asesmen</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer" name="penyesuaian_perangkat_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <textarea name="penyesuaian_perangkat_asesmen" class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none" :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'" :disabled="!active" placeholder="Tulis penyesuaian jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- KONFIRMASI DAN TANDA TANGAN --}}
            <div class="mb-8">
                <h4 class="font-bold text-md mb-2">Konfirmasi dengan orang yang relevan</h4>
                <table class="w-full text-sm form-table">
                    <thead>
                        <tr>
                            <th colspan="2" class="p-2 border border-black text-center font-bold bg-white">Orang yang relevan</th>
                            <th class="p-2 border border-black text-center font-bold w-1/4 bg-white">Nama</th>
                            <th class="p-2 border border-black text-center font-bold w-1/4 bg-white">Tandatangan dan Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Baris Manajer Sertifikasi --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]" value="Manajer sertifikasi LSP">
                            </td>
                            <td class="p-2 border border-black align-middle">Manajer sertifikasi LSP</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent" placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                        {{-- Baris Master Asesor --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]" value="Master Asesor">
                            </td>
                            <td class="p-2 border border-black align-middle">Master Asesor / Master Trainer / Lead Asesor Kompetensi</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent" placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                        {{-- Baris Manajer Pelatihan --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]" value="Manajer Pelatihan">
                            </td>
                            <td class="p-2 border border-black align-middle">Manajer pelatihan Lembaga Training terakreditasi</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent" placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                        {{-- Baris Supervisor --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]" value="Manajer atau supervisor">
                            </td>
                            <td class="p-2 border border-black align-middle">Manajer atau supervisor ditempat kerja</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent" placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PENYUSUN & VALIDATOR --}}
            <div class="mt-8">
                <h3 class="font-bold text-sm mb-2 uppercase">Penyusun dan Validator</h3>
                <table class="w-full text-sm form-table">
                    <thead class="bg-gray-100 text-center font-bold">
                        <tr>
                            <th class="p-2 w-1/6">STATUS</th>
                            <th class="p-2 w-12">NO</th>
                            <th class="p-2 w-1/3">NAMA</th>
                            <th class="p-2 w-1/6">NOMOR MET</th>
                            <th class="p-2">TANDA TANGAN DAN TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" class="p-2 text-center font-bold align-middle bg-gray-50">PENYUSUN</td>
                            <td class="p-2 text-center">1</td>
                            <td class="p-2"><input type="text" name="penyusun[0][nama]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[0][nomor_met]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[0][ttd_tanggal]" class="w-full outline-none bg-transparent"></td>
                        </tr>
                        <tr>
                            <td class="p-2 text-center">2</td>
                            <td class="p-2"><input type="text" name="penyusun[1][nama]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[1][nomor_met]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[1][ttd_tanggal]" class="w-full outline-none bg-transparent"></td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="p-2 text-center font-bold align-middle bg-gray-50">VALIDATOR</td>
                            <td class="p-2 text-center">1</td>
                            <td class="p-2"><input type="text" name="validator[0][nama]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[0][nomor_met]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[0][ttd_tanggal]" class="w-full outline-none bg-transparent"></td>
                        </tr>
                        <tr>
                            <td class="p-2 text-center">2</td>
                            <td class="p-2"><input type="text" name="validator[1][nama]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[1][nomor_met]" class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[1][ttd_tanggal]" class="w-full outline-none bg-transparent"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="mt-12 flex justify-end gap-4 pb-8">
                <button type="button" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg shadow hover:bg-gray-300 transition">Simpan Draft</button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition">Simpan Permanen</button>
            </div>

        </div>
    </form>
@endsection