@extends('layouts.app-sidebar')

@section('content')

<main class="main-content p-6 sm:p-10">

    <header class="form-header flex justify-between items-center border border-gray-900 shadow-md">
        <div class="p-4 bg-gray-100 w-full">
            <h1 class="text-lg md:text-2xl font-bold text-gray-900 text-center uppercase">
                FR.IA.09. PW-PERTANYAAN WAWANCARA (VIEW ADMIN)
            </h1>
        </div>
        <div class="border-l border-gray-900 p-4 text-center font-bold text-3xl hidden sm:block">
            FR.IA.09
        </div>
    </header>

    <div class="form-body mt-6">

        <div class="border border-gray-900 shadow-md mb-2">
            <table class="w-full border-collapse text-sm">
                <tbody>
                    <tr>
                        <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top" rowspan="2">
                            Skema Sertifikasi (KKNI/Okupasi/Klaster)*
                        </td>
                        <td class="border border-gray-900 p-2 font-bold w-1/6 bg-gray-50 align-top">
                            Judul
                        </td>
                        <td class="border border-gray-900 p-2 w-1/2">
                            : <span class="ml-2 font-medium">{{ data_get($dataIA09, 'skema.judul', '-') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 font-bold w-1/6 bg-gray-50 align-top">
                            Nomor
                        </td>
                        <td class="border border-gray-900 p-2 w-1/2">
                            : <span class="ml-2 font-medium">{{ data_get($dataIA09, 'skema.nomor', '-') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-gray-900 p-3 space-y-2 bg-white">
                            <h4 class="font-bold text-base mb-2 border-b border-gray-300 pb-1 text-gray-800">INFORMASI UMUM ASESMEN</h4>
                            <div class="space-y-2 pl-2">
                                <div class="flex items-center">
                                    <label class="font-medium w-36">TUK</label>
                                    <div class="flex items-center space-x-4">
                                        <span>:</span>
                                        @php $tuk = data_get($dataIA09, 'info_umum.tuk_type', '-'); @endphp
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block h-4 w-4 border border-gray-500 rounded-full flex items-center justify-center">
                                                @if ($tuk == 'Sewaktu') <span class="block h-2 w-2 bg-blue-600 rounded-full"></span> @endif
                                            </span>
                                            <span class="text-sm text-gray-700">Sewaktu</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block h-4 w-4 border border-gray-500 rounded-full flex items-center justify-center">
                                                @if ($tuk == 'Tempat Kerja') <span class="block h-2 w-2 bg-blue-600 rounded-full"></span> @endif
                                            </span>
                                            <span class="text-sm text-gray-700">Tempat Kerja</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block h-4 w-4 border border-gray-500 rounded-full flex items-center justify-center">
                                                @if ($tuk == 'Mandiri') <span class="block h-2 w-2 bg-blue-600 rounded-full"></span> @endif
                                            </span>
                                            <span class="text-sm text-gray-700">Mandiri</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesor</label>
                                    <span>:</span>
                                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ data_get($dataIA09, 'info_umum.nama_asesor', '-') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesi</label>
                                    <span>:</span>
                                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ data_get($dataIA09, 'info_umum.nama_asesi', '-') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Tanggal</label>
                                    <span>:</span>
                                    @php
                                        $tanggal = data_get($dataIA09, 'info_umum.tanggal');
                                        $tanggal_format = $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d-m-Y') : '-';
                                    @endphp
                                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ $tanggal_format }}</span>
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
                    @if (isset($dataIA09['unit_kompetensi']) && is_array($dataIA09['unit_kompetensi']))
                        @foreach ($dataIA09['unit_kompetensi'] as $index => $unit)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top">{{ data_get($unit, 'kelompok', '-') }}</td>
                            <td class="border border-gray-900 p-2 text-center">{{ $index + 1 }}.</td>
                            <td class="border border-gray-900 p-2">{{ data_get($unit, 'kode', '-') }}</td>
                            <td class="border border-gray-900 p-2" colspan="2">{{ data_get($unit, 'judul', 'Unit tidak terdefinisi') }}</td>
                        </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td class="border border-gray-900 p-2 text-center align-top font-bold" colspan="5">Dst.</td>
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
                    @if (isset($dataIA09['bukti_portofolio']) && is_array($dataIA09['bukti_portofolio']))
                        @foreach ($dataIA09['bukti_portofolio'] as $bukti)
                        <tr>
                            @php
                                $parts = explode('.', $bukti, 2);
                                $no = trim($parts[0]);
                                $desc = trim($parts[1] ?? '');
                            @endphp
                            <td class="border border-gray-900 p-2 text-center">{{ $no }}</td>
                            {{-- Tambahkan text-justify --}}
                            <td class="border border-gray-900 p-2 text-justify">{{ $desc }}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>


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
                    @if (isset($dataIA09['pertanyaan']) && is_array($dataIA09['pertanyaan']))
                        @foreach ($dataIA09['pertanyaan'] as $item)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold">{{ data_get($item, 'no', '') }}.</td>
                            {{-- Tambahkan text-justify --}}
                            <td class="border border-gray-900 px-3 py-1 text-xs align-top text-justify">{{ data_get($item, 'pertanyaan', 'Pertanyaan Hilang') }}</td>
                            
                            {{-- Tambahkan text-justify --}}
                            <td class="border border-gray-900 px-3 py-1 text-xs whitespace-pre-wrap align-top text-justify">
                                {{ data_get($item, 'jawaban', 'Belum dijawab') }}
                            </td>
                            
                            <td class="border border-gray-900 text-center align-middle">
                                @if (data_get($item, 'pencapaian') == 'Ya')
                                    <span class="text-green-600 font-bold text-lg">✓</span>
                                @endif
                            </td>
                            <td class="border border-gray-900 text-center align-middle">
                                @if (data_get($item, 'pencapaian') == 'Tidak')
                                    <span class="text-red-600 font-bold text-lg">X</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                <div class="font-bold mb-3">ASESI</div>
                <div class="flex items-center mb-2">
                    <label class="w-24">Nama</label>
                    <span>:</span>
                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ data_get($dataIA09, 'info_umum.nama_asesi', '-') }}</span>
                </div>
                @php
                    $tanggal_asesi = data_get($dataIA09, 'info_umum.tanggal');
                    $tanggal_asesi_format = $tanggal_asesi ? \Carbon\Carbon::parse($tanggal_asesi)->format('d/m/Y') : '-';
                @endphp
                <div class="flex items-center mb-2">
                    <label class="w-24">Tanggal</label>
                    <span>:</span>
                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ $tanggal_asesi_format }}</span>
                </div>
                <div class="mt-4">
                    <label class="font-medium block mb-1">Tanda Tangan Asesi:</label>
                    <div class="signature-box h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md">
                        @if (data_get($dataIA09, 'info_umum.ttd_asesi_url'))
                            <img src="{{ data_get($dataIA09, 'info_umum.ttd_asesi_url') }}" alt="Tanda Tangan Asesi" class="max-h-full max-w-full object-contain">
                        @else
                            Area Tanda Tangan (Belum Ada)
                        @endif
                    </div>
                </div>
            </div>

            <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                <div class="font-bold mb-3">ASESOR</div>
                <div class="flex items-center mb-2">
                    <label class="w-24">Nama</label>
                    <span>:</span>
                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ data_get($dataIA09, 'info_umum.nama_asesor', '-') }}</span>
                </div>
                <div class="flex items-center mb-2">
                    <label class="w-24">No. Reg. MET</label>
                    <span>:</span>
                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ data_get($dataIA09, 'info_umum.no_reg_met', '-') }}</span>
                </div>
                @php
                    $tanggal_asesor = data_get($dataIA09, 'info_umum.tanggal');
                    $tanggal_asesor_format = $tanggal_asesor ? \Carbon\Carbon::parse($tanggal_asesor)->format('d/m/Y') : '-';
                @endphp
                <div class="flex items-center mb-2">
                    <label class="w-24">Tanggal</label>
                    <span>:</span>
                    <span class="ml-2 font-medium border-b border-dashed border-gray-400 p-0 text-sm">{{ $tanggal_asesor_format }}</span>
                </div>
                <div class="mt-4">
                    <label class="font-medium block mb-1">Tanda Tangan Asesor:</label>
                    <div class="signature-box h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md">
                        @if (data_get($dataIA09, 'info_umum.ttd_asesor_url'))
                            <img src="{{ data_get($dataIA09, 'info_umum.ttd_asesor_url') }}" alt="Tanda Tangan Asesor" class="max-h-full max-w-full object-contain">
                        @else
                            Area Tanda Tangan (Belum Ada)
                        @endif
                    </div>
                </div>
            </div>
        </div>

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
                        @php
                            // Asumsi data penyusun/validator ada di $dataIA09['penyusun_validator']
                            // dan diakses dengan kunci 'PENYUSUN_1', 'PENYUSUN_2', 'VALIDATOR_1', 'VALIDATOR_2'
                            $key = $status . '_' . $i;
                            $name = data_get($dataIA09, 'penyusun_validator.' . $key . '.nama', '[Nama ' . $status . ' ' . $i . ']');
                            $nomor_met = data_get($dataIA09, 'penyusun_validator.' . $key . '.nomor_met', '[Nomor MET ' . $i . ']');
                            $ttd_url = data_get($dataIA09, 'penyusun_validator.' . $key . '.ttd_url');
                            $tgl = data_get($dataIA09, 'penyusun_validator.' . $key . '.tanggal');
                            $ttd_text = $tgl ? \Carbon\Carbon::parse($tgl)->format('d/m/Y') : 'Belum TTD';
                        @endphp
                        <tr>
                            @if ($i == 1)
                                <td class="border border-gray-900 p-2 text-center align-middle font-bold bg-gray-100" rowspan="2">
                                    {{ $status }}
                                </td>
                            @endif
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                            <td class="border border-gray-900 p-2">
                                <span class="font-medium">{{ $name }}</span>
                            </td>
                            <td class="border border-gray-900 p-2">
                                <span class="font-medium">{{ $nomor_met }}</span>
                            </td>
                            <td class="border border-gray-900 p-2 text-center">
                                <div class="relative h-16 border border-dashed border-gray-300 bg-white flex items-center justify-center text-xs text-gray-400 rounded-sm">
                                    @if ($ttd_url)
                                        <img src="{{ $ttd_url }}" alt="TTD {{ $status }} {{ $i }}" class="max-h-full max-w-full object-contain">
                                        <span class="absolute bottom-1 right-2 text-xs font-semibold text-gray-800">{{ $ttd_text }}</span>
                                    @else
                                        TTD & Tanggal
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endfor
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</main>
@endsection