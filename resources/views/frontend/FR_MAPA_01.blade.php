{{-- 
Nama File: resources/views/frontend/FR_MAPA_01.blade.php 
--}}
@extends('layouts.app-sidebar')

@section('content')
    {{-- LOGIC PHP UNTUK HANDLING DATA NULL --}}
    @php
        // Ambil data MAPA jika ada, jika tidak kosongkan array agar tidak error
        $data = $mapa01 ?? null; 
        
        // Helper untuk Checkbox (Ubah JSON jadi Array)
        $pendekatan = $data->pendekatan_asesmen ?? [];
        $tujuan = $data->tujuan_sertifikasi ?? '';
        $konteks = $data->konteks_lingkungan ?? [];
        
        // Helper untuk Unit Kompetensi
        // Kita ambil data tersimpan berdasarkan index array
        $savedUnits = $data->unit_kompetensi ?? [];
        
        // Helper untuk Bagian 3 (Kontekstualisasi)
        $karakteristik = $data->karakteristik_kandidat ?? '';
        $karakteristik_check = $data->karakteristik_ada_checkbox ?? false;
        
        // Helper Tanda Tangan
        $penyusun = $data->penyusun[0] ?? [];
        $validator = $data->validator[0] ?? [];
    @endphp

    <style>
        .form-table, .form-table td, .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 11px; /* Sesuai standar dokumen biasanya kecil */
        }
        .bg-header { background-color: #e2e8f0; font-weight: bold; text-align: center; }
        .input-plain { width: 100%; outline: none; background: transparent; }
        .checkbox-label { display: flex; align-items: center; gap: 4px; margin-bottom: 2px; }
    </style>

    <div class="container mx-auto p-4 bg-white shadow-lg rounded-lg">
        
        {{-- HEADER JUDUL --}}
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold">FR.MAPA.01. MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN</h2>
        </div>

        <form action="{{ route('mapa01.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- ID DATA SERTIFIKASI (HIDDEN) --}}
            <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">

            {{-- BAGIAN 1: PENDEKATAN ASESMEN --}}
            <table class="w-full form-table mb-6">
                <tr>
                    <td colspan="2" class="bg-header p-2 text-left">1. Pendekatan Asesmen</td>
                </tr>
                <tr>
                    <td class="w-1/2 p-2 align-top">
                        <strong>Kandidat</strong><br>
                        <label class="checkbox-label">
                            <input type="checkbox" name="pendekatan_asesmen[]" value="Hasil pelatihan dan / atau pendidikan" 
                                {{ in_array('Hasil pelatihan dan / atau pendidikan', $pendekatan) ? 'checked' : '' }}>
                            Hasil pelatihan dan / atau pendidikan:
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="pendekatan_asesmen[]" value="Pekerja berpengalaman"
                                {{ in_array('Pekerja berpengalaman', $pendekatan) ? 'checked' : '' }}>
                            Pekerja berpengalaman
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="pendekatan_asesmen[]" value="Pelatihan / belajar mandiri"
                                {{ in_array('Pelatihan / belajar mandiri', $pendekatan) ? 'checked' : '' }}>
                            Pelatihan / belajar mandiri
                        </label>
                    </td>
                    <td class="w-1/2 p-2 align-top">
                        <strong>Tujuan Asesmen</strong><br>
                        <label class="checkbox-label">
                            <input type="radio" name="tujuan_sertifikasi" value="Sertifikasi" 
                                {{ $tujuan == 'Sertifikasi' ? 'checked' : '' }}> Sertifikasi
                        </label>
                        <label class="checkbox-label">
                            <input type="radio" name="tujuan_sertifikasi" value="RCC" 
                                {{ $tujuan == 'RCC' ? 'checked' : '' }}> Sertifikasi Ulang (RCC)
                        </label>
                        <label class="checkbox-label">
                            <input type="radio" name="tujuan_sertifikasi" value="RPL" 
                                {{ $tujuan == 'RPL' ? 'checked' : '' }}> Pengakuan Kompetensi Terkini (PKT)
                        </label>
                        <label class="checkbox-label">
                            <input type="radio" name="tujuan_sertifikasi" value="Lainnya" 
                                {{ $tujuan == 'Lainnya' ? 'checked' : '' }}> Lainnya
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-2">
                        <strong>Konteks Asesmen:</strong><br>
                        <div class="grid grid-cols-2">
                            <label class="checkbox-label">
                                <input type="checkbox" name="konteks_lingkungan[]" value="Tempat kerja"
                                    {{ in_array('Tempat kerja', $konteks) ? 'checked' : '' }}> Tempat kerja
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="konteks_lingkungan[]" value="Tempat kerja simulasi"
                                    {{ in_array('Tempat kerja simulasi', $konteks) ? 'checked' : '' }}> Tempat kerja simulasi
                            </label>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- BAGIAN 2: RENCANA ASESMEN (CORE LOOPING UNIT) --}}
            <div class="mb-2 font-bold">2. Rencana Asesmen</div>
            <p class="text-xs mb-2">Kode dan Judul Unit Kompetensi (Sesuai Skema)</p>
            
            <div class="overflow-x-auto">
                <table class="w-full form-table mb-6">
                    <thead>
                        <tr class="bg-header">
                            <th rowspan="2" class="w-1/4">Kode dan Judul Unit Kompetensi</th>
                            <th rowspan="2" class="w-1/4">Bukti-bukti (Portofolio / Observasi)</th>
                            <th colspan="3">Jenis Bukti</th>
                            <th colspan="5">Metode Asesmen</th>
                        </tr>
                        <tr class="bg-header" style="font-size: 9px;">
                            <th class="w-8">L</th>
                            <th class="w-8">TL</th>
                            <th class="w-8">T</th>
                            
                            {{-- Metode Checkbox Headers --}}
                            <th>Observasi Langsung</th>
                            <th>Kegiatan Terstruktur</th>
                            <th>Tanya Jawab</th>
                            <th>Verifikasi Portofolio</th>
                            <th>Review Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skema->unitKompetensi as $index => $unit)
                            @php
                                // Ambil data unit yang tersimpan (jika ada) berdasarkan index
                                $uData = $savedUnits[$index] ?? [];
                            @endphp
                            
                            {{-- HIDDEN INPUTS UNTUK DATA UNIT --}}
                            <input type="hidden" name="unit_kompetensi[{{ $index }}][kode_unit]" value="{{ $unit->kode_unit }}">
                            <input type="hidden" name="unit_kompetensi[{{ $index }}][judul_unit]" value="{{ $unit->judul_unit }}">

                            <tr>
                                <td class="p-2 font-semibold bg-gray-50">
                                    {{ $unit->kode_unit }}<br>
                                    {{ $unit->judul_unit }}
                                </td>
                                <td class="p-2">
                                    <textarea name="unit_kompetensi[{{ $index }}][bukti]" rows="3" 
                                        class="w-full border-gray-300 rounded text-xs p-1" 
                                        placeholder="Isi bukti...">{{ $uData['bukti'] ?? '' }}</textarea>
                                </td>
                                
                                {{-- JENIS BUKTI (L/TL/T) --}}
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][L]" value="1" 
                                        {{ isset($uData['L']) ? 'checked' : '' }}>
                                </td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][TL]" value="1" 
                                        {{ isset($uData['TL']) ? 'checked' : '' }}>
                                </td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][T]" value="1" 
                                        {{ isset($uData['T']) ? 'checked' : '' }}>
                                </td>

                                {{-- METODE ASESMEN --}}
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][metode_observasi]" value="1" 
                                        {{ isset($uData['metode_observasi']) ? 'checked' : '' }}>
                                </td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][metode_terstruktur]" value="1" 
                                        {{ isset($uData['metode_terstruktur']) ? 'checked' : '' }}>
                                </td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][metode_tanya_jawab]" value="1" 
                                        {{ isset($uData['metode_tanya_jawab']) ? 'checked' : '' }}>
                                </td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][metode_verifikasi]" value="1" 
                                        {{ isset($uData['metode_verifikasi']) ? 'checked' : '' }}>
                                </td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="unit_kompetensi[{{ $index }}][metode_review]" value="1" 
                                        {{ isset($uData['metode_review']) ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- BAGIAN 3: MODIFIKASI DAN KONTEKSTUALISASI --}}
            <table class="w-full form-table mb-6">
                <tr class="bg-header">
                    <td colspan="3" class="text-left p-2">3. Modifikasi dan Kontekstualisasi</td>
                </tr>
                <tr>
                    <td class="w-10 text-center p-2">3.1</td>
                    <td class="w-1/3 p-2">Karakteristik Kandidat</td>
                    <td class="p-2">
                        <label class="flex items-center gap-2 mb-2">
                            <input type="hidden" name="karakteristik_ada_checkbox" value="0">
                            <input type="checkbox" name="karakteristik_ada_checkbox" value="1" 
                                {{ $karakteristik_check ? 'checked' : '' }}>
                            <span>Ada</span>
                        </label>
                        <textarea name="karakteristik_kandidat" class="w-full border p-1" rows="2">{{ $karakteristik }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="w-10 text-center p-2">3.2</td>
                    <td class="p-2">Kebutuhan Kontekstualisasi</td>
                    <td class="p-2">
                        @php $kk_check = $data->kebutuhan_kontekstualisasi_checkbox ?? false; @endphp
                        <label class="flex items-center gap-2 mb-2">
                            <input type="hidden" name="kebutuhan_kontekstualisasi_checkbox" value="0">
                            <input type="checkbox" name="kebutuhan_kontekstualisasi_checkbox" value="1" 
                                {{ $kk_check ? 'checked' : '' }}>
                            <span>Ada</span>
                        </label>
                        <textarea name="kebutuhan_kontekstualisasi" class="w-full border p-1" rows="2">{{ $data->kebutuhan_kontekstualisasi ?? '' }}</textarea>
                    </td>
                </tr>
                {{-- Lanjutkan pola yang sama untuk Saran Paket & Penyesuaian Perangkat --}}
            </table>

            {{-- BAGIAN TANDA TANGAN (Storage Rule Compliance) --}}
            <div class="mt-8">
                <div class="font-bold mb-2">Penyusun dan Validator</div>
                <table class="w-full form-table">
                    <thead>
                        <tr class="bg-header">
                            <th>Peran</th>
                            <th>Nama</th>
                            <th>No. Registrasi / MET</th>
                            <th>Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PENYUSUN --}}
                        <tr>
                            <td class="text-center font-bold p-2">Penyusun (Asesor)</td>
                            <td class="p-2">
                                <input type="text" name="penyusun[0][nama]" 
                                    value="{{ $penyusun['nama'] ?? Auth::user()->name ?? '' }}" 
                                    class="input-plain border-b border-gray-300">
                            </td>
                            <td class="p-2">
                                <input type="text" name="penyusun[0][no_reg]" 
                                    value="{{ $penyusun['no_reg'] ?? '' }}" 
                                    class="input-plain border-b border-gray-300">
                            </td>
                            <td class="p-2 text-center">
                                @if(isset($penyusun['ttd_path']))
                                    {{-- TAMPILKAN GAMBAR JIKA ADA (Sesuai Rule: asset('storage/..')) --}}
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $penyusun['ttd_path']) }}" alt="TTD Penyusun" class="h-16 mx-auto">
                                    </div>
                                    <p class="text-xs text-green-600">Tanda tangan tersimpan</p>
                                @endif
                                
                                {{-- Input File untuk Upload/Ganti --}}
                                <input type="file" name="ttd_penyusun_file" class="text-xs mt-1">
                            </td>
                        </tr>

                        {{-- VALIDATOR --}}
                        <tr>
                            <td class="text-center font-bold p-2">Validator</td>
                            <td class="p-2">
                                <input type="text" name="validator[0][nama]" 
                                    value="{{ $validator['nama'] ?? '' }}" 
                                    class="input-plain border-b border-gray-300">
                            </td>
                            <td class="p-2">
                                <input type="text" name="validator[0][no_reg]" 
                                    value="{{ $validator['no_reg'] ?? '' }}" 
                                    class="input-plain border-b border-gray-300">
                            </td>
                            <td class="p-2 text-center">
                                @if(isset($validator['ttd_path']))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $validator['ttd_path']) }}" alt="TTD Validator" class="h-16 mx-auto">
                                    </div>
                                @endif
                                <input type="file" name="ttd_validator_file" class="text-xs mt-1">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ url()->previous() }}" class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold">
                    Simpan FR.MAPA.01
                </button>
            </div>

        </form>
    </div>
@endsection