@extends('layouts.app-sidebar')

@section('content')
<main class="main-content">

    @php $is_admin = $user->role_id == 1; @endphp

    <x-header_form.header_form title="FR.IA.05.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA" />
    <br>
    
    {{-- BAGIAN IDENTITAS SKEMA (SUDAH DIISI) --}}
    <div class="form-row grid grid-cols-1 md:grid-cols-[250px_1fr] md:gap-x-6 gap-y-1.5 items-start md:items-center">
        
        <label class="text-sm font-bold text-black">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
        <div class="flex items-center">
            <span>:</span>
            {{-- Ambil Judul Skema dari Controller --}}
            <p class="ml-2 font-medium text-gray-600">{{ $skema_info->judul_skema ?? 'Junior Web Developer' }}</p>
        </div>

        <label class="text-sm font-bold text-black">Nomor</label>
        <div class="flex items-center">
            <span>:</span>
            {{-- Ambil Kode Skema dari Controller --}}
            <p class="ml-2 font-medium text-gray-600">{{ $skema_info->kode_skema ?? 'SKK.XXXXX.XXXX' }}</p>
        </div>

        <label class="text-sm font-bold text-black">TUK</label>
        <div class="radio-group flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-4">
            <span>:</span>
            <div class="flex items-center space-x-2 ml-0 md:ml-2">
                <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" disabled>
                <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="radio" id="tuk_tempatkerja" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" checked disabled>
                <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="radio" id="tuk_mandiri" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" disabled>
                <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form class="form-body mt-10" method="POST" action="{{ route('ia-05.store.kunci') }}">
        @csrf 
        
        {{-- TABEL UNIT KOMPETENSI (Statis / Disabled) --}}
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
                                <input type="text" name="kode_unit_1" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_1" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 text-sm text-center">2.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_2" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_2" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 text-sm text-center">3.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_3" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_3" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2"></td>
                            <td class="border border-gray-900 p-2 text-sm text-center">Dst.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-section my-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full">
                    <thead>
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban (Teks)</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban (Teks)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($semua_soal) && $semua_soal->count() > 0)
                            {{-- Kita loop chunk-nya, tapi kita hitung nomor manual --}}
                            @foreach ($semua_soal->chunk(2) as $chunkIndex => $chunk)
                            <tr>
                                {{-- KOLOM KIRI --}}
                                @if (isset($chunk[0]))
                                    @php 
                                        $soal_kiri = $chunk[0]; 
                                        // HITUNG NOMOR MANUAL: (Index Chunk * 2) + 1
                                        $nomor_kiri = ($loop->index * 2) + 1; 
                                    @endphp
                                    
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        {{ $nomor_kiri }}.
                                    </td>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        <input type="text" 
                                            name="kunci[{{ $soal_kiri->id_soal_ia05 }}]" 
                                            class="form-input w-full border-gray-300 rounded-md shadow-sm"
                                            value="{{ $kunci_jawaban->get($soal_kiri->id_soal_ia05) ?? '' }}"
                                            placeholder="Contoh: A. {{ $soal_kiri->opsi_jawaban_a }}"
                                            @if(!$is_admin) disabled @endif>
                                    </td>
                                @else
                                    <td class="border border-gray-900 p-2"></td>
                                    <td class="border border-gray-900 p-2"></td>
                                @endif

                                {{-- KOLOM KANAN --}}
                                @if (isset($chunk[1]))
                                    @php 
                                        $soal_kanan = $chunk[1]; 
                                        // HITUNG NOMOR MANUAL: (Index Chunk * 2) + 2
                                        $nomor_kanan = ($loop->index * 2) + 2; 
                                    @endphp
                                    
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        {{ $nomor_kanan }}.
                                    </td>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        <input type="text" 
                                            name="kunci[{{ $soal_kanan->id_soal_ia05 }}]" 
                                            class="form-input w-full border-gray-300 rounded-md shadow-sm"
                                            value="{{ $kunci_jawaban->get($soal_kanan->id_soal_ia05) ?? '' }}"
                                            placeholder="Contoh: B. {{ $soal_kanan->opsi_jawaban_b }}"
                                            @if(!$is_admin) disabled @endif>
                                    </td>
                                @else
                                    <td class="border border-gray-900 p-2"></td>
                                    <td class="border border-gray-900 p-2"></td>
                                @endif
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    Belum ada soal yang dibuat oleh Admin.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="form-section my-8">
            @include('components.kolom_ttd.penyusunvalidator')
        </div>
        
        <div class="form-footer flex justify-between mt-10">
            <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
            @if($is_admin)
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Kunci Jawaban</button>
            @endif
        </div>
        
        <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
            <p>*Coret yang tidak perlu</p>
        </div>
    </form>
</main>
@endsection