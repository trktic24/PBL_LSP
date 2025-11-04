@extends('layouts.app-sidebar')

@section('content')
    <main class="main-content">

        <form class="form-body" method="POST" action="">
            @csrf 
            <x-header_form.header_form title="FR.IA.05.C. LEMBAR JAWABAN PILIHAN GANDA" />
             
            <x-identitas_skema_form.identitas_skema_form
                skema="Junior Web Developer"
                nomorSkema="SKK.XXXXX.XXXX"
                tuk="Tempat Kerja" 
                namaAsesor="Ajeng Febria Hidayati"
                namaAsesi="Tatang Sidartang"
                tanggal="3 November 2025" 
            />


            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            {{-- TABEL UNIT KOMPETENSI (Sama seperti IA.05.B) --}}
            <div class="form-section my-8">
                <div class="border border-gray-900 shadow-md">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="border border-gray-900 p-2 font-semibold w-[25%]">Kelompok Pekerjaan ...</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[30%]">Kode Unit</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[35%]">Judul Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="3" class="border border-gray-900 p-2 align-top text-sm">..............................</td>
                                <td class="border border-gray-900 p-2 text-sm text-center">1.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kode_unit_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="judul_unit_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">2.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kode_unit_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="judul_unit_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">3.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kode_unit_3" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="judul_unit_3" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center">Dst.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kode_unit_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="judul_unit_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL LEMBAR JAWABAN (8 Kolem) --}}
            <div class="form-section my-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Lembar Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
                
                <div class="border border-gray-900 shadow-md">
                    <table class="w-full">
                        <thead class="bg-black text-white">
                            {{-- Header Baris 1 --}}
                            <tr>
                                <th class="border border-gray-900 p-2 font-semibold w-[5%]" rowspan="2">No.</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[20%]" rowspan="2">Jawaban</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[20%]" colspan="2">Pencapaian</th>
                                
                                <th class="border border-gray-900 p-2 font-semibold w-[5%]" rowspan="2">No.</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[20%]" rowspan="2">Jawaban</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[20%]" colspan="2">Pencapaian</th>
                            </tr>
                            {{-- Header Baris 2 --}}
                            <tr class="bg-black text-white">
                                <th class="border border-gray-900 p-2 font-semibold">Ya</th>
                                <th class="border border-gray-900 p-2 font-semibold">Tidak</th>
                                <th class="border border-gray-900 p-2 font-semibold">Ya</th>
                                <th class="border border-gray-900 p-2 font-semibold">Tidak</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Baris 1 & 6 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">1.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_1" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_1_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_1_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">6.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_6" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_6_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_6_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            </tr>
                            {{-- Baris 2 & 7 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">2.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_2" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_2_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_2_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">7.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_7" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_7_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_7_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            </tr>
                            {{-- Baris 3 & 8 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">3.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_3" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_3_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_3_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">8.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_8" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_8_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_8_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            </tr>
                            {{-- Baris 4 & 9 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">4.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_4" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_4_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_4_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">9.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_9" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_9_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_9_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            </tr>
                            {{-- Baris 5 & Dst --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">5.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_5" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_5_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_5_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">Dst.</td>
                                <td class="border border-gray-900 p-2 text-sm"><input type="text" name="jawaban_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_dst_y" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="checkbox" name="capaian_dst_t" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- TABEL UMPAN BALIK & TTD (Bertingkat) --}}
            <div class="form-section my-8">
                {{-- 1. BLOK UMPAN BALIK (YANG SUDAH DIPISAH) --}}
                <div class="form-section my-8">
                    <div class="border border-gray-900 shadow-md w-full">
                        <table class="w-full border-collapse">
                            <tbody>
                                <tr>
                                    <td class="border border-gray-900 p-2 font-semibold w-40 bg-black text-white align-top">Umpan balik untuk asesi</td>
                                    <td class="border border-gray-900 p-2">
                                        <p class="text-sm font-medium text-gray-800">Aspek pengetahuan seluruh unit kompetensi yang diujikan (tercapai / belum tercapai)*</p>
                                        
                                        {{-- Ini adalah textarea yang sesuai dengan gambar Anda --}}
                                        <textarea name="umpan_balik" 
                                                class="form-textarea w-full border-gray-300 rounded-md shadow-sm mt-2" 
                                                rows="3" 
                                                placeholder="Tuliskan unit/elemen/KUK jika belum tercapai..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

{{-- 2. PANGGILAN KOMPONEN TANDA TANGAN (SESUAI PERMINTAAN ANDA) --}}
@include('components.kolom_ttd.asesiasesor')
            </div>
            
            {{-- FOOTER BUTTONS --}}
            <div class="form-footer flex justify-between mt-10">
                <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim</button>
            </div>
            
            {{-- Catatan footer tidak ada di gambar 05.C, jadi saya hapus --}}

        </form>

    </main>
@endsection