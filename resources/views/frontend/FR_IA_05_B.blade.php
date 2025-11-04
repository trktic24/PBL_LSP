@extends('layouts.app-sidebar')

@section('content')
    <main class="main-content">
        
       <x-header_form.header_form title="FR.IA.05.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA" />
        <br>

        <div class="form-row grid grid-cols-1 md:grid-cols-[250px_1fr] md:gap-x-6 gap-y-1.5 items-start md:items-center">
            
            <label class="text-sm font-bold text-black">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
            <div class="flex items-center">
                <span>:</span>
                {{-- Ganti $skema dengan variabel Anda, contoh: $data->skema->judul --}}
                <p class="ml-2 font-medium text-gray-600">{{ $skema ?? 'Data Skema' }}</p>
            </div>

            <label class="text-sm font-bold text-black">Nomor</label>
            <div class="flex items-center">
                <span>:</span>
                {{-- Ganti $nomorSkema dengan variabel Anda, contoh: $data->skema->nomor --}}
                <p class="ml-2 font-medium text-gray-600">{{ $nomorSkema ?? 'Data Nomor Skema' }}</p>
            </div>

            <label class="text-sm font-bold text-black">TUK</label>
            <div class="radio-group flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-4">
                <span>:</span>
                <div class="flex items-center space-x-2 ml-0 md:ml-2">
                    {{-- Ganti $tuk dengan variabel Anda, contoh: $data->tuk --}}
                    <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" 
                        @checked(isset($tuk) && $tuk == 'Sewaktu')>
                    <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="radio" id="tuk_tempatkerja" name="tuk_type" class="form-radio h-4 w-4 text-gray-400"
                        @checked(isset($tuk) && $tuk == 'Tempat Kerja')>
                    <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="radio" id="tuk_mandiri" name="tuk_type" class="form-radio h-4 w-4 text-gray-400"
                        @checked(isset($tuk) && $tuk == 'Mandiri')>
                    <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                </div>
            </div>
        </div>


        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form class="form-body mt-10" method="POST" action="">
            @csrf 
            

            {{-- TABEL UNIT KOMPETENSI --}}
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

            {{-- TABEL KUNCI JAWABAN (GRID 2 KOLOM) --}}
            {{-- TABEL KUNCI JAWABAN (Struktur 4 Kolom Tunggal) --}}
            <div class="form-section my-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
                
                <div class="border border-gray-900 shadow-md">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-black text-white">
                                {{-- Header untuk kolom 1 & 2 --}}
                                <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban</th>
                                
                                {{-- Header untuk kolom 3 & 4 --}}
                                <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                                <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Baris untuk No. 1 dan 6 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">1.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">6.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_6" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            {{-- Baris untuk No. 2 dan 7 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">2.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">7.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_7" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            {{-- Baris untuk No. 3 dan 8 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">3.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_3" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">8.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_8" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            {{-- Baris untuk No. 4 dan 9 --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">4.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_4" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">9.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_9" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                            {{-- Baria untuk No. 5 dan Dst. --}}
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm text-center">5.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_5" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">Dst.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="kunci_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                    
            
            {{-- TABEL PENYUSUN/VALIDATOR: Menggunakan @include --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">PENYUSUN DAN VALIDATOR</h2>
                @include('components.kolom_ttd.penyusunvalidator')
            </div>
            
            {{-- FOOTER BUTTONS --}}
            <div class="form-footer flex justify-between mt-10">
                <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim</button>
            </div>
            
            {{-- FOOTER NOTES --}}
            <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
                <p>*Coret yang tidak perlu</p>
            </div>

        </form>

    </main>
@endsection