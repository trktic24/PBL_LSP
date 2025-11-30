@extends('layouts.app-sidebar')

@section('content')

    <main class="main-content p-6 sm:p-10">

        <header class="form-header flex justify-between items-center border border-gray-900 shadow-md">
            <div class="p-4 bg-gray-100 w-full">
                <h1 class="text-lg md:text-2xl font-bold text-gray-900 text-center uppercase">
                    FR.IA.09. PW-PERTANYAAN WAWANCARA
                </h1>
            </div>
            <div class="border-l border-gray-900 p-4 text-center font-bold text-3xl hidden sm:block">
                FR.IA.09
            </div>
        </header>

        <form class="form-body mt-6">

            <div class="border border-gray-900 shadow-md mb-6">
                <table class="w-full border-collapse text-sm">
                    <tbody>
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top" rowspan="2">
                                Skema Sertifikasi (KKNI/Okupasi/Klaster)*
                            </td>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Judul
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                : <input type="text" value="{{ $dataIA09['skema']['judul'] }}" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Nomor
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                : <input type="text" value="{{ $dataIA09['skema']['nomor'] }}" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0">
                            </td>
                        </tr>

                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100">
                                <div class="font-bold">PANDUAN BAGI ASESOR</div>
                                <div class="text-xs font-normal mt-1">Instruksi:</div>
                                <ol class="list-disc list-inside pl-3 space-y-1 text-xs font-normal">
                                    @foreach ($dataIA09['panduan_asesor'] as $panduan)
                                        <li>{{ $panduan }}</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td colspan="2" class="border border-gray-900 p-2 w-2/3 space-y-2">
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
                                    <input type="date" value="{{ $dataIA09['info_umum']['tanggal'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-2 text-xs italic">*Coret yang tidak perlu</div>
            </div>

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


            <div class="border border-gray-900 shadow-md mt-6">
                <div class="bg-black text-white font-bold p-2 text-sm text-center">DAFTAR PERTANYAAN WAWANCARA</div>
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-black text-white text-center">
                            <th class="border border-gray-900 p-2 w-1/12 font-semibold" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">Daftar Pertanyaan Wawancara</th>
                            <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">Kesimpulan Jawaban Asesi</th>
                            <th class="border border-gray-900 p-2 w-1/6 font-semibold" colspan="2">Pencapaian</th>
                        </tr>
                        <tr class="bg-black text-white text-center text-xs">
                            <th class="border border-gray-900 p-1 w-1/12 font-normal">Ya</th>
                            <th class="border border-gray-900 p-1 w-1/12 font-normal">Tidak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataIA09['pertanyaan'] as $item)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold">{{ $item['no'] }}.</td>
                            <td class="border border-gray-900 px-3 py-1 text-xs">{{ $item['pertanyaan'] }}</td>
                            <td class="border border-gray-900 px-3 py-1 text-xs">{{ $item['jawaban'] }}</td>
                            <td class="border border-gray-900 text-center">
                                <input type="checkbox" name="p{{ $item['no'] }}_ya" {{ $item['pencapaian'] == 'Ya' ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600 rounded">
                            </td>
                            <td class="border border-gray-900 text-center">
                                <input type="checkbox" name="p{{ $item['no'] }}_tidak" {{ $item['pencapaian'] == 'Tidak' ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600 rounded">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border border-gray-900 shadow-md mt-6">
                <table class="w-full border-collapse text-sm">
                    <tr>
                        <td class="border border-gray-900 p-2 font-semibold w-1/4 bg-black text-white">REKOMENDASI ASESOR</td>
                        <td class="border border-gray-900 p-2 w-3/4">
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                <li>Asesi telah memenuhi pencapaian seluruh kriteria untuk kerja, direkomendasikan **KOMPETEN** @if (str_contains($dataIA09['rekomendasi'], 'KOMPETEN')) <span class="font-bold text-lg">✓</span> @endif</li>
                                <li>Asesi belum memenuhi pencapaian seluruh kriteria untuk kerja, direkomendasikan **OBSERVASI LANGSUNG** @if (str_contains($dataIA09['rekomendasi'], 'OBSERVASI LANGSUNG')) <span class="font-bold text-lg">✓</span> @endif</li>
                            </ul>
                            <div class="mt-2 text-xs">
                                Kelompok Pekerjaan: <input type="text" value="{{ implode(', ', array_column($dataIA09['unit_kompetensi'], 'kelompok')) }}" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32">
                                Unit Kompetensi: <input type="text" value="{{ implode(', ', array_column($dataIA09['unit_kompetensi'], 'kode')) }}" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                    <div class="font-bold mb-3">ASESI</div>
                    <div class="flex items-center mb-2">
                        <label class="w-24">Nama</label>
                        <span>:</span>
                        <input type="text" value="{{ $dataIA09['info_umum']['nama_asesi'] }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24">Tanda tangan dan Tanggal</label>
                        <span>:</span>
                        <input type="text" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
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
                        <input type="text" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24">Tanda tangan dan Tanggal</label>
                        <span>:</span>
                        <input type="text" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="font-bold mb-1">Catatan:</div>
                <div class="text-sm italic">Tuliskan temuan pencapaian hasil wawancara, jika belum/tidak terpenuhi.</div>
                <textarea rows="4" class="form-textarea w-full border-gray-900 rounded-md shadow-md focus:border-blue-500 focus:ring-blue-500 text-sm">{{ $dataIA09['catatan'] }}</textarea>
            </div>

            <h3 class="font-bold mt-6 mb-2">PENYUSUN DAN VALIDATOR</h3>
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
                                <td class="border border-gray-900 p-2 text-center align-top @if($i==1) font-bold bg-gray-100 @else bg-white @endif" @if($i==1) rowspan="2" @endif>
                                    @if($i==1) {{ $status }} @endif
                                </td>
                                <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                                <td class="border border-gray-900 p-2"><input type="text" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0"></td>
                                <td class="border border-gray-900 p-2"><input type="text" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0"></td>
                                <td class="border border-gray-900 p-2 text-center"><input type="text" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0"></td>
                            </tr>
                            @endfor
                        @endforeach
                    </tbody>
                </table>
            </div>

        </form>

    </main>
@endsection