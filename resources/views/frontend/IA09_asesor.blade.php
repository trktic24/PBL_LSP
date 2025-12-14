@extends('layouts.app-sidebar')

@section('content')

<main class="main-content p-6 sm:p-10">

    <!-- Header Dokumen -->
    <header class="form-header flex justify-between items-center border border-gray-900 shadow-md">
        <div class="p-4 bg-gray-100 w-full">
            <h1 class="text-lg md:text-2xl font-bold text-gray-900 text-center uppercase">
                FR.IA.09. PW-PERTANYAAN WAWANCARA (INPUT ASESOR)
            </h1>
        </div>
        <div class="border-l border-gray-900 p-4 text-center font-bold text-3xl hidden sm:block">
            FR.IA.09
        </div>
    </header>

    <form class="form-body mt-6" action="{{ route('ia09.store') }}" method="POST">
        @csrf

        <!-- BLOK 1: INFORMASI SKEMA DAN UMUM (Grup Utama) -->
        <div class="border border-gray-900 shadow-md mb-2">
            <table class="w-full border-collapse text-sm">
                <tbody>
                    <tr>
                        <!-- Kolom Kiri: Skema (Rowspan 2) -->
                        <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top" rowspan="2">
                            Skema Sertifikasi (KKNI/Okupasi/Klaster)*
                        </td>
                        <!-- Kolom Kanan 1: Judul -->
                        <td class="border border-gray-900 p-2 font-bold w-1/6 bg-gray-50 align-top">
                            Judul
                        </td>
                        <!-- Kolom Kanan 2: Judul Input -->
                        <td class="border border-gray-900 p-2 w-1/2">
                            : <input type="text" name="skema_judul" value="{{ $dataIA09['skema']['judul'] }}" class="form-input w-11/12 ml-2 border-none p-0 text-sm focus:ring-0">
                        </td>
                    </tr>
                    <tr>
                        <!-- Kolom Kanan 1: Nomor -->
                        <td class="border border-gray-900 p-2 font-bold w-1/6 bg-gray-50 align-top">
                            Nomor
                        </td>
                        <!-- Kolom Kanan 2: Nomor Input -->
                        <td class="border border-gray-900 p-2 w-1/2">
                            : <input type="text" name="skema_nomor" value="{{ $dataIA09['skema']['nomor'] }}" class="form-input w-11/12 ml-2 border-none p-0 text-sm focus:ring-0">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-gray-900 p-3 space-y-2 bg-white">
                            <h4 class="font-bold text-base mb-2 border-b border-gray-300 pb-1 text-gray-800">INFORMASI UMUM ASESMEN</h4>
                            <!-- Info Umum (TUK, Nama, Tanggal) -->
                            <div class="space-y-2 pl-2">
                                <div class="flex items-center">
                                    <label class="font-medium w-36">TUK</label>
                                    <div class="radio-group flex items-center space-x-4">
                                        <span>:</span>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_sewaktu" name="tuk_type" value="Sewaktu" {{ $dataIA09['info_umum']['tuk_type'] == 'Sewaktu' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600">
                                            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_tempatkerja" name="tuk_type" value="Tempat Kerja" {{ $dataIA09['info_umum']['tuk_type'] == 'Tempat Kerja' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600">
                                            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_mandiri" name="tuk_type" value="Mandiri" {{ $dataIA09['info_umum']['tuk_type'] == 'Mandiri' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600">
                                            <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesor</label>
                                    <span>:</span>
                                    <input type="text" value="{{ $dataIA09['info_umum']['nama_asesor'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesi</label>
                                    <span>:</span>
                                    <input type="text" value="{{ $dataIA09['info_umum']['nama_asesi'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Tanggal</label>
                                    <span>:</span>
                                    <input type="date" name="tanggal" value="{{ $dataIA09['info_umum']['tanggal'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </div>

        <div class="border border-gray-900 shadow-md mb-6">
            <div class="bg-gray-100 p-3 border-b border-gray-900">
                <div class="font-bold text-lg text-gray-900">PANDUAN BAGI ASESOR</div>
            </div>
            <div class="p-4">
                {{-- PERUBAHAN DI SINI: list-outside, pl-6, dan text-justify di <li> --}}
                <ol class="list-decimal list-outside pl-6 space-y-2 text-sm font-normal">
                    @if (isset($dataIA09['panduan_asesor']) && is_array($dataIA09['panduan_asesor']))
                        @foreach ($dataIA09['panduan_asesor'] as $panduan)
                            {{-- Tambahkan text-justify pada li --}}
                            <li class="text-justify">{{ $panduan }}</li>
                        @endforeach
                    @else
                        <li>*Data panduan tidak tersedia.*</li>
                    @endif
                </ol>
            </div>
            <div class="p-2 text-xs italic bg-gray-50 border-t border-gray-300">*Coret yang tidak perlu</div>
        </div>

        <!-- DAFTAR UNIT KOMPETENSI -->
        <div class="border border-gray-900 shadow-md mt-8">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-black text-white text-center">
                        <th class="border border-gray-900 p-2 w-1/5 font-semibold">Kelompok Pekerjaan</th>
                        <th class="border border-gray-900 p-2 w-1/12 font-semibold">No.</th>
                        <th class="border border-gray-900 p-2 w-1/5 font-semibold">Kode Unit</th>
                        <th class="border border-gray-900 p-2 w-auto font-semibold" colspan="2">Judul Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataIA09['unit_kompetensi'] as $index => $unit)
                    <tr>
                        <td class="border border-gray-900 p-2 text-center align-top">{{ $unit['kelompok'] }}</td>
                        <td class="border border-gray-900 p-2 text-center">{{ $index + 1 }}.</td>
                        <td class="border border-gray-900 p-2">{{ $unit['kode'] }}</td>
                        <td class="border border-gray-900 p-2" colspan="2">{{ $unit['judul'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="border border-gray-900 p-2 text-center align-top" colspan="5">Dst.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BUKTI PORTOFOLIO -->
        <div class="border border-gray-900 shadow-md mt-6">
            <div class="bg-black text-white font-bold p-2 text-sm">BUKTI PORTOFOLIO</div>
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr>
                        <th class="border border-gray-900 p-2 w-1/12 font-semibold bg-gray-100">No.</th>
                        <th class="border border-gray-900 p-2 font-semibold bg-gray-100">Bukti Portofolio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataIA09['bukti_portofolio'] as $bukti)
                    <tr>
                        @php
                            $parts = explode('.', $bukti, 2);
                            $no = trim($parts[0]);
                            $desc = trim($parts[1] ?? '');
                        @endphp
                        <td class="border border-gray-900 p-2 text-center">{{ $no }}</td>
                        <td class="border border-gray-900 p-2">{{ $desc }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- DAFTAR PERTANYAAN WAWANCARA (Bagian Input Asesor) -->
        <div class="border border-gray-900 shadow-md mt-6">
            <div class="bg-black text-white font-bold p-2 text-sm text-center">DAFTAR PERTANYAAN WAWANCARA</div>
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-800 text-white text-center">
                        <th class="border border-gray-900 p-2 w-1/12 font-semibold" rowspan="2">No.</th>
                        <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">Daftar Pertanyaan Wawancara</th>
                        <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">Kesimpulan Jawaban Asesi</th>
                        <th class="border border-gray-900 p-2 w-1/6 font-semibold" colspan="2">Pencapaian</th>
                    </tr>
                    <tr class="bg-gray-800 text-white text-center text-xs">
                        <th class="border border-gray-900 p-1 w-1/12 font-normal">Ya</th>
                        <th class="border border-gray-900 p-1 w-1/12 font-normal">Tidak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataIA09['pertanyaan'] as $item)
                    <tr>
                        <td class="border border-gray-900 p-2 text-center align-top font-bold">{{ $item['no'] }}.</td>
                        <td class="border border-gray-900 px-3 py-1 text-xs align-top">{{ $item['pertanyaan'] }}</td>
                        
                        <!-- Kolom Input/Edit Asesor -->
                        <td class="border border-gray-900 px-3 py-1 text-xs">
                            <textarea name="jawaban_{{ $item['no'] }}" rows="3" class="form-textarea w-full border-gray-300 rounded-md p-1 text-xs focus:border-blue-500 focus:ring-blue-500">{{ $item['jawaban'] }}</textarea>
                        </td>
                        
                        <!-- Pilihan Pencapaian (Radio Group) -->
                        <td class="border border-gray-900 text-center">
                            <input type="radio" name="pencapaian_{{ $item['no'] }}" value="Ya" {{ $item['pencapaian'] == 'Ya' ? 'checked' : '' }} class="form-radio h-4 w-4 text-green-600 rounded">
                        </td>
                        <td class="border border-gray-900 text-center">
                            <input type="radio" name="pencapaian_{{ $item['no'] }}" value="Tidak" {{ $item['pencapaian'] == 'Tidak' ? 'checked' : '' }} class="form-radio h-4 w-4 text-red-600 rounded">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan ASESI dan ASESOR -->
        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                <div class="font-bold mb-3">ASESI</div>
                <div class="flex items-center mb-2">
                    <label class="w-24">Nama</label>
                    <span>:</span>
                    <input type="text" value="{{ $dataIA09['info_umum']['nama_asesi'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                </div>
                <div class="flex items-center mb-2">
                    <label class="w-24">Tanggal</label>
                    <span>:</span>
                    <input type="text" value="{{ \Carbon\Carbon::parse($dataIA09['info_umum']['tanggal'])->format('d/m/Y') }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                </div>
                <!-- Kotak Tanda Tangan Asesi -->
                <div class="mt-4">
                    <label class="font-medium block mb-1">Tanda Tangan Asesi:</label>
                    <div class="signature-box h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md">
                        Area Tanda Tangan
                    </div>
                    <!-- Hidden input to capture/store signature data if it were a real drawing pad -->
                    <input type="hidden" name="ttd_asesi">
                </div>
            </div>

            <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                <div class="font-bold mb-3">ASESOR</div>
                <div class="flex items-center mb-2">
                    <label class="w-24">Nama</label>
                    <span>:</span>
                    <input type="text" value="{{ $dataIA09['info_umum']['nama_asesor'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                </div>
                <div class="flex items-center mb-2">
                    <label class="w-24">No. Reg. MET</label>
                    <span>:</span>
                    <input type="text" value="{{ $dataIA09['info_umum']['no_reg_met'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                </div>
                <div class="flex items-center mb-2">
                    <label class="w-24">Tanggal</label>
                    <span>:</span>
                    <input type="text" value="{{ \Carbon\Carbon::parse($dataIA09['info_umum']['tanggal'])->format('d/m/Y') }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                </div>
                <!-- Kotak Tanda Tangan Asesor -->
                <div class="mt-4">
                    <label class="font-medium block mb-1">Tanda Tangan Asesor:</label>
                    <div class="signature-box h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md">
                        Area Tanda Tangan
                    </div>
                    <!-- Hidden input to capture/store signature data if it were a real drawing pad -->
                    <input type="hidden" name="ttd_asesor">
                </div>
            </div>
        </div>

        <!-- Penyusun & Validator (Read-Only di Asesor) -->
        <h3 class="font-bold mt-6 mb-2 text-lg uppercase border-b pb-1">PENYUSUN DAN VALIDATOR</h3>
<div class="border border-gray-900 shadow-md">
    <table class="w-full border-collapse text-sm">
        <thead>
            <tr class="bg-black text-white text-center">
                <th class="border border-gray-900 p-2 w-[15%] font-bold">STATUS</th>
                <th class="border border-gray-900 p-2 w-[5%] font-bold">NO</th>
                <th class="border border-gray-900 p-2 w-[30%] font-bold">NAMA</th>
                <th class="border border-gray-900 p-2 w-[20%] font-bold">NOMOR MET</th>
                <th class="border border-gray-900 p-2 w-[30%] font-bold">TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach (['PENYUSUN', 'VALIDATOR'] as $status)
                @for ($i = 1; $i <= 2; $i++)
                <tr>
                    {{-- Perubahan di sini: STATUS hanya muncul jika i == 1 --}}
                    @if ($i == 1)
                        <td class="border border-gray-900 p-2 text-center align-middle font-bold bg-gray-100" rowspan="2">
                            {{ $status }}
                        </td>
                    @endif
                    <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                    <td class="border border-gray-900 p-2"><input type="text" value="[Nama {{ $status }} {{ $i }}]" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly></td>
                    <td class="border border-gray-900 p-2"><input type="text" value="[Nomor MET {{ $i }}]" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly></td>
                    <td class="border border-gray-900 p-2 text-center">
                        <!-- Kotak Tanda Tangan Penyusun/Validator -->
                        <div class="h-16 border border-dashed border-gray-300 bg-white flex items-center justify-center text-xs text-gray-400 rounded-sm">
                            TTD & Tanggal
                        </div>
                    </td>
                </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>
        <!-- Tombol Simpan -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition duration-150 ease-in-out">
                Simpan
            </button>
        </div>

    </form>

</main>
@endsection