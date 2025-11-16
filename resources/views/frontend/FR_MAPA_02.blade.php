{{-- Menggunakan layout sidebar yang sama --}}
@extends('layouts.app-sidebar')

@section('content')
<div class="p-8">
    {{-- 1. Menggunakan Komponen Header Form --}}
    <x-header_form.header_form title="FR.MAPA.02. PETA INSTRUMEN ASESSMEN" />
    
    {{-- 2. Menggunakan Komponen Identitas Skema --}}
    {{-- Komponen ini akan menampilkan data statis (placeholder) skema, asesor, asesi, TUK, dll --}}
    {{-- (Sama seperti yang Anda modifikasi di FR.IA.02) --}}
    <x-identitas_skema_form.identitas_skema_form />

    <!-- Box Tabel Kelompok Pekerjaan (Mengadopsi gaya dari FR.IA.02) -->
    <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm mb-6">
            <table class="w-full text-sm">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="p-3 text-left font-medium w-[25%]">Kelompok Pekerjaan ...</th>
                        <th class="p-3 text-left font-medium w-[10%]">No.</th>
                        <th class="p-3 text-left font-medium w-[25%]">Kode Unit</th>
                        <th class="p-3 text-left font-medium w-[40%]">Judul Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr>
                        <td class="p-3" rowspan="3">Implementasi Desain</td>
                        <td class="p-3 text-center">1.</td>
                        <td class="p-3">J.620100.004.02</td>
                        <td class="p-3">Menggunakan Struktur Data</td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">2.</td>
                        <td class="p-3">J.620100.005.01</td>
                        <td class="p-3">Mengimplementasikan User Interface</td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">Dst.</td>
                        <td class="p-3">...</td>
                        <td class="p-3">...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Box Tabel Potensi Asesi (Bagian Inti FR.MAPA.02) -->
    <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="p-3 text-left font-medium w-[5%]">No.</th>
                        <th class="p-3 text-left font-medium w-[50%]">INSTRUMEN ASESMEN</th>
                        <th class="p-3 text-center font-medium" colspan="5">Potensi Asesi **</th>
                    </tr>
                    <tr class="bg-black text-white">
                        <th class="p-3 text-left font-medium"></th>
                        <th class="p-3 text-left font-medium"></th>
                        <th class="p-3 text-center font-medium w-[5%]">1</th>
                        <th class="p-3 text-center font-medium w-[5%]">2</th>
                        <th class="p-3 text-center font-medium w-[5%]">3</th>
                        <th class="p-3 text-center font-medium w-[5%]">4</th>
                        <th class="p-3 text-center font-medium w-[5%]">5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    {{-- Daftar statis 11 instrumen --}}
                    <tr>
                        <td class="p-3 text-center">1.</td>
                        <td class="p-3">FR.IA.01. CL - Ceklis Observasi</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia01" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia01" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia01" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia01" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia01" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">2.</td>
                        <td class="p-3">FR.IA.02. TPD - Tugas Praktik Demonstrasi</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia02" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia02" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia02" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia02" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia02" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">3.</td>
                        <td class="p-3">FR.IA.03. PMO - Pertanyaan Untuk Mendukung Observasi</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia03" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia03" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia03" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia03" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia03" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">4.a</td>
                        <td class="p-3">FR.IA.04A. DIT - Daftar Instruksi Terstruktur (Proyek)</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04a" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04a" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04a" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04a" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04a" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">4.b</td>
                        <td class="p-3">FR.IA.04B. DIT - Daftar Instruksi Terstruktur (Lainnya)</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04b" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04b" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04b" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04b" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia04b" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">5.</td>
                        <td class="p-3">FR.IA.05. DPT - Daftar Pertanyaan Tertulis Pilihan Ganda</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia05" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia05" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia05" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia05" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia05" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">6.</td>
                        <td class="p-3">FR.IA.06. DPT - Daftar Pertanyaan Tertulis Pilihan Esai</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia06" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia06" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia06" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia06" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia06" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">7.</td>
                        <td class="p-3">FR.IA.07. DPL - Daftar Pertanyaan Lisan</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia07" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia07" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia07" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia07" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia07" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">8.</td>
                        <td class="p-3">FR.IA.08. CVP - Ceklis Verifikasi Portofolio</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia08" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia08" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia08" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia08" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia08" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">9.</td>
                        <td class="p-3">FR.IA.09. PW - Pertanyaan Wawancara</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia09" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia09" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia09" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia09" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia09" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">10.</td>
                        <td class="p-3">FR.IA.10. VPK - Verifikasi Pihak Ketiga</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia10" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia10" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia10" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia10" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia10" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-center">11.</td>
                        <td class="p-3">FR.IA.11. CRP - Ceklis Reviu Produk</td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia11" value="1" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia11" value="2" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia11" value="3" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia11" value="4" class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="potensi_ia11" value="5" class="h-4 w-4 text-blue-600"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-gray-500 mt-2">*) diisi berdasarkan hasil penentuan pendekatan asesmen dan perencanaan asesmen</p>
        <p class="text-xs text-gray-500 mt-1">**) Potensi Asesi: 1:Sangat Baik, 2:Baik, 3:Cukup, 4:Kurang, 5:Sangat Kurang</p>

    </div>
    
    {{-- 3. Menggunakan Komponen Penyusun dan Validator --}}
    {{-- (Sesuai dokumen PDF, MAPA-02 hanya butuh Penyusun/Validator) --}}
    <x-kolom_ttd.penyusunvalidator />

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