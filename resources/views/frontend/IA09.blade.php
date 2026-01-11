@extends('layouts.app-sidebar') 
@section('content') 
<main class="main-content p-6 sm:p-10"> 
    
    {{-- Header dengan Badge Mode --}}
    <header class="form-header flex justify-between items-center border border-gray-900 shadow-md rounded-lg overflow-hidden"> 
        <div class="p-4 bg-gray-100 w-full"> 
            <div class="flex items-center justify-center gap-3">
                <h1 class="text-lg md:text-2xl font-extrabold text-gray-900 text-center uppercase tracking-wider"> 
                    FR.IA.09. PW-PERTANYAAN WAWANCARA
                </h1>
                @if($mode === 'edit')
                    <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full">MODE INPUT</span>
                @else
                    <span class="px-3 py-1 bg-gray-500 text-white text-xs font-bold rounded-full">READ-ONLY</span>
                @endif
            </div>
        </div> 
        <div class="border-l border-gray-900 p-4 text-center font-extrabold text-2xl sm:text-3xl bg-gray-200 hidden sm:block"> 
            FR.IA.09 
            @if(isset($isMasterView))
                <div class="text-xs text-blue-600 mt-1">[TEMPLATE MASTER]</div>
            @endif
        </div> 
    </header>
    
    {{-- Form hanya muncul jika mode edit --}}
    @if($mode === 'edit')
    <form class="form-body mt-6" 
        action="{{ route('asesor.ia09.store', ['id_data_sertifikasi_asesi' => $dataIA09['id_data_sertifikasi_asesi']]) }}" 
        method="POST" 
        id="formIA09">
        @csrf
    
        {{-- Ini adalah hidden input tambahan untuk memudahkan Controller --}}
        <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $dataIA09['id_data_sertifikasi_asesi'] }}">
    @else
    <div class="form-body mt-6">
    @endif

        {{-- Hidden input hanya untuk mode edit --}}
        @if($mode === 'edit')
        <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $dataIA09['id_data_sertifikasi_asesi'] }}">
        @endif

        {{-- Section 1: Skema dan Informasi Umum --}} 
        <div class="border border-gray-900 shadow-lg mb-6 rounded-md overflow-hidden"> 
            <table class="w-full border-collapse text-sm"> 
                <tbody> 
                    <tr class="bg-white"> 
                        <td class="border border-gray-900 p-3 font-bold w-1/3 bg-gray-100 align-top" rowspan="2"> 
                            Skema Sertifikasi (KKNI/Okupasi/Klaster)* 
                        </td> 
                        <td class="border border-gray-900 p-3 font-bold w-1/6 bg-gray-50 align-top">Judul</td> 
                        <td class="border border-gray-900 p-3 w-1/2"> 
                            : <input type="text" value="{{ $dataIA09['skema']['judul'] }}" class="form-input w-11/12 ml-2 border-none p-0 text-sm focus:ring-0 focus:border-transparent" readonly> 
                        </td> 
                    </tr> 
                    <tr class="bg-white"> 
                        <td class="border border-gray-900 p-3 font-bold w-1/6 bg-gray-50 align-top">Nomor</td> 
                        <td class="border border-gray-900 p-3 w-1/2"> 
                            : <input type="text" value="{{ $dataIA09['skema']['nomor'] }}" class="form-input w-11/12 ml-2 border-none p-0 text-sm focus:ring-0 focus:border-transparent" readonly> 
                        </td> 
                    </tr> 
                    <tr class="bg-white"> 
                        <td colspan="3" class="border border-gray-900 p-4 space-y-3"> 
                            <h4 class="font-bold text-base mb-2 border-b-2 border-blue-600/50 pb-1 text-gray-800 uppercase">INFORMASI UMUM ASESMEN</h4> 
                            <div class="space-y-2 pl-2 text-sm"> 
                                <div class="flex items-center"> 
                                    <label class="font-medium w-36">TUK</label> 
                                    <span class="mr-2">:</span> 
                                    <div class="radio-group flex items-center space-x-4"> 
                                        <div class="flex items-center space-x-1"> 
                                            <input type="radio" id="tuk_sewaktu" value="Sewaktu" {{ $dataIA09['info_umum']['tuk_type'] == 'Sewaktu' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600 border-gray-300" disabled> 
                                            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label> 
                                        </div> 
                                        <div class="flex items-center space-x-1"> 
                                            <input type="radio" id="tuk_tempatkerja" value="Tempat Kerja" {{ $dataIA09['info_umum']['tuk_type'] == 'Tempat Kerja' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600 border-gray-300" disabled> 
                                            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label> 
                                        </div> 
                                        <div class="flex items-center space-x-1"> 
                                            <input type="radio" id="tuk_mandiri" value="Mandiri" {{ $dataIA09['info_umum']['tuk_type'] == 'Mandiri' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600 border-gray-300" disabled> 
                                            <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label> 
                                        </div> 
                                    </div> 
                                </div> 
                                <div class="flex items-center"> 
                                    <label class="font-medium w-36">Nama Asesor</label> 
                                    <span class="mr-2">:</span> 
                                    <input type="text" value="{{ $dataIA09['info_umum']['nama_asesor'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                                </div> 
                                <div class="flex items-center"> 
                                    <label class="font-medium w-36">Nama Asesi</label> 
                                    <span class="mr-2">:</span> 
                                    <input type="text" value="{{ $dataIA09['info_umum']['nama_asesi'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                                </div> 
                                <div class="flex items-center"> 
                                    <label class="font-medium w-36">Tanggal</label> 
                                    <span class="mr-2">:</span> 
                                    <input type="text" value="{{ \Carbon\Carbon::parse($dataIA09['info_umum']['tanggal'])->format('d/m/Y') }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                                </div> 
                            </div> 
                        </td> 
                    </tr> 
                    <tr> 
                        <td colspan="3" class="p-2 text-xs italic bg-gray-50 border-t border-gray-900">
                            *Coret yang tidak perlu
                        </td> 
                    </tr> 
                </tbody> 
            </table> 
        </div> 

        {{-- Section 2: Panduan Bagi Asesor --}} 
        <div class="border border-gray-900 shadow-lg mb-6 rounded-md overflow-hidden"> 
            <div class="bg-gray-800 text-white p-3 border-b border-gray-900"> 
                <div class="font-bold text-lg">PANDUAN BAGI ASESOR</div> 
            </div> 
            <div class="p-4 bg-white"> 
                <ol class="list-decimal list-outside pl-6 space-y-2 text-sm font-normal"> 
                    @if (isset($dataIA09['panduan_asesor']) && is_array($dataIA09['panduan_asesor'])) 
                        @foreach ($dataIA09['panduan_asesor'] as $panduan) 
                        <li class="text-justify">{{ $panduan }}</li> 
                        @endforeach 
                    @else 
                    <li class="text-red-500 italic">*Data panduan tidak tersedia. Pastikan data IA.09 lengkap.</li> 
                    @endif 
                </ol> 
            </div> 
        </div> 

        {{-- Section 3: Daftar Unit Kompetensi --}} 
        <div class="border border-gray-900 shadow-lg mt-8 rounded-md overflow-hidden"> 
            <div class="bg-black text-white font-bold p-2 text-sm text-center">DAFTAR UNIT KOMPETENSI</div> 
            <table class="w-full border-collapse text-sm"> 
                <thead> 
                    <tr class="bg-gray-100 text-gray-800 text-center"> 
                        <th class="border border-gray-900 p-2 w-1/5 font-semibold">Kelompok Pekerjaan</th> 
                        <th class="border border-gray-900 p-2 w-1/12 font-semibold">No.</th> 
                        <th class="border border-gray-900 p-2 w-1/5 font-semibold">Kode Unit</th> 
                        <th class="border border-gray-900 p-2 w-auto font-semibold" colspan="2">Judul Unit</th> 
                    </tr> 
                </thead> 
                <tbody class="bg-white"> 
                    @foreach ($dataIA09['unit_kompetensi'] as $index => $unit) 
                    <tr> 
                        <td class="border border-gray-900 p-2 text-center align-top">{{ $unit['kelompok'] }}</td> 
                        <td class="border border-gray-900 p-2 text-center">{{ $index + 1 }}.</td> 
                        <td class="border border-gray-900 p-2">{{ $unit['kode'] }}</td> 
                        <td class="border border-gray-900 p-2" colspan="2">{{ $unit['judul'] }}</td> 
                    </tr> 
                    @endforeach 
                </tbody> 
            </table> 
        </div> 

        {{-- Section 4: Bukti Portofolio --}} 
        <div class="border border-gray-900 shadow-lg mt-6 rounded-md overflow-hidden"> 
            <div class="bg-black text-white font-bold p-2 text-sm text-center">BUKTI PORTOFOLIO</div> 
            <table class="w-full border-collapse text-sm"> 
                <thead> 
                    <tr> 
                        <th class="border border-gray-900 p-2 w-1/12 font-semibold bg-gray-100">No.</th>
                        <th class="border border-gray-900 p-2 font-semibold bg-gray-100">Bukti Portofolio</th> 
                    </tr> 
                </thead> 
                <tbody class="bg-white"> 
                    @foreach ($dataIA09['bukti_portofolio'] as $index => $bukti) 
                        <tr> 
                            <td class="border border-gray-900 p-2 text-center align-top">{{ $index + 1 }}</td> 
                            <td class="border border-gray-900 p-2">{{ preg_replace('/^\d+\.\s*/', '', $bukti) }}</td>
                        </tr> 
                    @endforeach 
                </tbody>
            </table> 
        </div> 

        {{-- Section 5: Daftar Pertanyaan Wawancara --}} 
        <div class="border border-gray-900 shadow-lg mt-6 rounded-md overflow-hidden" id="pertanyaanWawancaraTable"> 
            <div class="bg-black text-white font-bold p-2 text-sm text-center">DAFTAR PERTANYAAN WAWANCARA</div> 
            <table class="w-full border-collapse text-sm"> 
                <thead> 
                    <tr class="bg-gray-800 text-white text-center"> 
                        <th class="border border-gray-900 p-2 w-1/12 font-semibold" rowspan="2">No.</th> 
                        <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">Daftar Pertanyaan Wawancara</th> 
                        <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">
                            Kesimpulan Jawaban Asesi 
                            @if($mode === 'edit')<span class="text-red-400">*</span>@endif
                        </th> 
                        <th class="border border-gray-900 p-2 w-1/6 font-semibold" colspan="2">
                            Pencapaian 
                            @if($mode === 'edit')<span class="text-red-400">*</span>@endif
                        </th> 
                    </tr> 
                    <tr class="bg-gray-800 text-white text-center text-xs"> 
                        <th class="border border-gray-900 p-1 w-1/12 font-normal">Ya</th> 
                        <th class="border border-gray-900 p-1 w-1/12 font-normal">Tidak</th> 
                    </tr> 
                </thead> 
                <tbody class="bg-white"> 
                    {{-- PASTIKAN PERULANGAN MENGGUNAKAN $index --}}
                    @foreach ($dataIA09['pertanyaan'] as $index => $item) 
                    <tr> 
                        {{-- Nomor pertanyaan tetap menggunakan $item['no'] karena ini hanya untuk tampilan --}}
                        <td class="border border-gray-900 p-2 text-center align-top font-bold">{{ $item['no'] }}.</td> 
                        <td class="border border-gray-900 px-3 py-1 text-xs align-top">
                            {{ $item['pertanyaan'] }}
                            @if($mode === 'edit')
                            {{-- PERBAIKAN: Mengganti key array dari $item['no'] ke $index untuk INPUT FIELD --}}
                            <input type="hidden" name="pertanyaan[{{ $index }}][pertanyaan]" value="{{ $item['pertanyaan'] }}">
                            <input type="hidden" name="pertanyaan[{{ $index }}][no]" value="{{ $item['no'] }}">
                            @if($item['id_jawaban'])
                            <input type="hidden" name="pertanyaan[{{ $index }}][id_jawaban]" value="{{ $item['id_jawaban'] }}">
                            @endif
                            @endif
                        </td> 

                        {{-- Kesimpulan Jawaban: Editable atau Read-only --}} 
                        <td class="border border-gray-900 px-3 py-1 text-xs"> 
                            @if($mode === 'edit')
                                <textarea 
                                    {{-- PERBAIKAN: Mengganti key name ke $index --}}
                                    name="pertanyaan[{{ $index }}][jawaban]" 
                                    rows="4" 
                                    required
                                    placeholder="Tulis kesimpulan jawaban asesi (minimal 10 karakter)..."
                                    {{-- PERBAIKAN: Mengganti key error ke $index --}}
                                    class="form-textarea w-full border-gray-300 rounded-md p-2 text-xs focus:border-blue-500 focus:ring-blue-500 @error('pertanyaan.'.$index.'.jawaban') border-red-500 @enderror">{{ old('pertanyaan.'.$index.'.jawaban', $item['jawaban']) }}</textarea>
                                {{-- PERBAIKAN: Mengganti key error ke $index --}}
                                @error('pertanyaan.'.$index.'.jawaban')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            @else
                                <div class="p-2 bg-gray-50 rounded text-xs min-h-[80px] whitespace-pre-wrap">
                                    {{ $item['jawaban'] ?: 'Belum ada jawaban' }}
                                </div>
                            @endif
                        </td> 
        
                        {{-- Pencapaian Ya --}} 
                        <td class="border border-gray-900 text-center"> 
                            <input  
                                type="radio" 
                                {{-- PERBAIKAN: Mengganti key name ke $index --}}
                                name="pertanyaan[{{ $index }}][pencapaian]" 
                                value="Ya" 
                                {{-- PERBAIKAN: Mengganti ID radio ke $index --}}
                                id="pencapaian_ya_{{ $index }}" 
                                {{-- PERBAIKAN: Mengganti key old helper ke $index --}}
                                {{ old('pertanyaan.'.$index.'.pencapaian', $item['pencapaian']) == 'Ya' ? 'checked' : '' }} 
                                @if($mode === 'edit') required @else disabled @endif
                                class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500"
                                title="Pencapaian Ya"> 
                            <label for="pencapaian_ya_{{ $index }}" class="sr-only">Ya</label> 
                        </td> 
        
                        {{-- Pencapaian Tidak --}} 
                        <td class="border border-gray-900 text-center"> 
                            <input 
                                type="radio" 
                                {{-- PERBAIKAN: Mengganti key name ke $index --}}
                                name="pertanyaan[{{ $index }}][pencapaian]" 
                                value="Tidak" 
                                {{-- PERBAIKAN: Mengganti ID radio ke $index --}}
                                id="pencapaian_tidak_{{ $index }}" 
                                {{-- PERBAIKAN: Mengganti key old helper ke $index --}}
                                {{ old('pertanyaan.'.$index.'.pencapaian', $item['pencapaian']) == 'Tidak' ? 'checked' : '' }} 
                                @if($mode === 'edit') required @else disabled @endif
                                class="form-radio h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500"
                                title="Pencapaian Tidak"> 
                            <label for="pencapaian_tidak_{{ $index }}" class="sr-only">Tidak</label> 
                        </td> 
                    </tr> 
                    @endforeach 
                </tbody>
            </table> 
        </div>

        {{-- Section 6: Tanda Tangan --}} 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6"> 
            {{-- ASESI --}} 
            <div class="border border-gray-900 p-4 shadow-lg bg-gray-50 flex flex-col justify-between rounded-md"> 
                <div class="font-extrabold mb-3 border-b pb-1 text-lg text-gray-800 uppercase">ASESI</div> 
                <div class="space-y-3"> 
                    <div class="space-y-2 text-sm"> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">Nama</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['info_umum']['nama_asesi'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="flex items-center h-5"> 
                            <label class="w-32 text-sm text-gray-400 italic">No. Reg. -</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="-" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">Tanggal</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ \Carbon\Carbon::parse($dataIA09['info_umum']['tanggal'])->format('d/m/Y') }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                    </div> 
                    <div class="mt-4 pt-2 border-t border-gray-300"> 
                        <label class="font-medium block mb-1 text-sm">Tanda Tangan Asesi:</label> 
                        <div class="signature-box h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md overflow-hidden"> 
                            @php
                                $ttdAsesiBase64 = getTtdBase64($dataIA09['ttd']['asesi'] ?? null, null, 'asesi');
                            @endphp
                            @if ($ttdAsesiBase64) 
                            <img src="{{ $ttdAsesiBase64 }}" alt="Tanda Tangan Asesi" class="max-h-full max-w-full object-contain p-1"> 
                            @else 
                            Area Tanda Tangan Asesi (Belum Ditandatangani) 
                            @endif 
                        </div> 
                    </div> 
                </div> 
            </div> 
            
            {{-- ASESOR --}} 
            <div class="border border-gray-900 p-4 shadow-lg bg-gray-50 flex flex-col justify-between rounded-md"> 
                <div class="font-extrabold mb-3 border-b pb-1 text-lg text-gray-800 uppercase">ASESOR</div> 
                <div class="space-y-3"> 
                    <div class="space-y-2 text-sm"> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">Nama</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['info_umum']['nama_asesor'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">No. Reg. MET</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['info_umum']['no_reg_met'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">Tanggal</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ \Carbon\Carbon::parse($dataIA09['info_umum']['tanggal'])->format('d/m/Y') }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                    </div> 
                    <div class="mt-4 pt-2 border-t border-gray-300"> 
                        <label class="font-medium block mb-1 text-sm">Tanda Tangan Asesor:</label> 
                        <div class="signature-box h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md overflow-hidden"> 
                            @php
                                $ttdAsesorBase64 = getTtdBase64($dataIA09['ttd']['asesor'] ?? null, null, 'asesor');
                            @endphp
                            @if ($ttdAsesorBase64) 
                            <img src="{{ $ttdAsesorBase64 }}" alt="Tanda Tangan Asesor" class="max-h-full max-w-full object-contain p-1"> 
                            @else 
                            Area Tanda Tangan Asesor (Belum Ditandatangani) 
                            @endif 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 

        {{-- Section 7: Penyusun dan Validator --}} 
        <h3 class="font-bold mt-8 mb-2 text-lg uppercase border-b-2 border-gray-400 pb-1 text-gray-800">PENYUSUN DAN VALIDATOR</h3> 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
            {{-- PENYUSUN --}} 
            <div class="border border-gray-900 shadow-lg rounded-md overflow-hidden"> 
                <div class="bg-black text-white font-bold p-2 text-center text-sm">PENYUSUN</div> 
                <div class="p-4 space-y-4 bg-white"> 
                    @if (isset($dataIA09['penyusun']) && !empty($dataIA09['penyusun']['nama'])) 
                    <div class="border border-gray-300 p-3 bg-gray-50 rounded-md space-y-2 text-sm"> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">Nama</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['penyusun']['nama'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">No. Reg. MET</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['penyusun']['no_reg_met'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="mt-3"> 
                            <label class="font-medium block mb-1 text-sm">Tanda Tangan & Tanggal:</label> 
                            <div class="h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md overflow-hidden"> 
                                @php
                                    $ttdPenyusunBase64 = getTtdBase64($dataIA09['penyusun']['ttd'] ?? null, null, 'other');
                                @endphp
                                @if ($ttdPenyusunBase64) 
                                    <img src="{{ $ttdPenyusunBase64 }}" alt="TTD Penyusun" class="max-h-full max-w-full object-contain p-1"> 
                                @else 
                                    <span class="text-gray-500">
                                        @if(isset($dataIA09['penyusun']['tanggal']))
                                            TTD & Tgl: {{ \Carbon\Carbon::parse($dataIA09['penyusun']['tanggal'])->format('d/m/Y') }}
                                        @else
                                            (Belum Ditandatangani)
                                        @endif
                                    </span>
                                @endif 
                            </div> 
                        </div> 
                    </div> 
                    @else 
                    <p class="text-sm text-red-500 italic p-3">Data Penyusun tidak ditemukan.</p> 
                    @endif 
                </div> 
            </div> 
    
            {{-- VALIDATOR --}} 
            <div class="border border-gray-900 shadow-lg rounded-md overflow-hidden"> 
                <div class="bg-black text-white font-bold p-2 text-center text-sm">VALIDATOR</div> 
                <div class="p-4 space-y-4 bg-white"> 
                    @if (isset($dataIA09['validator']) && !empty($dataIA09['validator']['nama'])) 
                    <div class="border border-gray-300 p-3 bg-gray-50 rounded-md space-y-2 text-sm"> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">Nama</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['validator']['nama'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="flex items-center"> 
                            <label class="w-32 font-medium">No. Reg. MET</label> 
                            <span class="mr-2">:</span> 
                            <input type="text" value="{{ $dataIA09['validator']['no_reg_met'] }}" class="form-input w-full border-none p-0 text-sm focus:ring-0 bg-transparent" readonly> 
                        </div> 
                        <div class="mt-3"> 
                            <label class="font-medium block mb-1 text-sm">Tanda Tangan & Tanggal:</label> 
                            <div class="h-24 border border-dashed border-gray-500 bg-white flex items-center justify-center text-xs text-gray-400 rounded-md overflow-hidden"> 
                                @php
                                    $ttdValidatorBase64 = getTtdBase64($dataIA09['validator']['ttd'] ?? null, null, 'other');
                                @endphp
                                @if ($ttdValidatorBase64) 
                                    <img src="{{ $ttdValidatorBase64 }}" alt="TTD Validator" class="max-h-full max-w-full object-contain p-1"> 
                                @else 
                                    <span class="text-gray-500">
                                        @if(isset($dataIA09['validator']['tanggal_validasi']))
                                            TTD & Tgl: {{ $dataIA09['validator']['tanggal_validasi'] }}
                                        @elseif(isset($dataIA09['validator']['tanggal']))
                                            TTD & Tgl: {{ \Carbon\Carbon::parse($dataIA09['validator']['tanggal'])->format('d/m/Y') }}
                                        @else
                                            (Belum Ditandatangani)
                                        @endif
                                    </span>
                                @endif 
                            </div> 
                        </div> 
                    </div> 
                    @else 
                    <p class="text-sm text-red-500 italic p-3">Data Validator tidak ditemukan.</p> 
                    @endif 
                </div> 
            </div> 
        </div>
        
        {{-- Tombol Submit (Hanya Mode Edit) --}}
        @if($mode === 'edit')
        <div class="flex justify-end mt-8 pb-10"> 
            <button 
                type="submit" 
                id="btnSubmit"
                class="px-8 py-3 bg-green-600 text-white font-extrabold rounded-full shadow-xl hover:bg-green-700 transition duration-150 ease-in-out text-lg transform hover:scale-[1.02]"> 
                <i class="fas fa-save mr-2"></i> 
                Simpan Hasil Asesmen 
            </button> 
        </div>
        @endif

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50" id="successAlert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50" id="errorAlert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md" id="validationAlert">
            <div class="font-bold mb-2">Terdapat kesalahan:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>  
        @endif

        {{-- JavaScript hanya untuk mode edit --}}
        @if($mode === 'edit')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-hide alerts
                setTimeout(function() {
                    const alerts = document.querySelectorAll('#successAlert, #errorAlert, #validationAlert');
                    alerts.forEach(alert => {
                        if(alert) {
                            alert.style.transition = 'opacity 0.5s';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500);
                        }
                    });
                }, 5000);

                // Form validation
                const form = document.getElementById('formIA09');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        let isValid = true;
                        const pertanyaanTable = document.getElementById('pertanyaanWawancaraTable');
                        
                        if (!pertanyaanTable) {
                            console.error('Tabel pertanyaan tidak ditemukan!');
                            isValid = false;
                        }

                        if (isValid) {
                            const pertanyaanRows = pertanyaanTable.querySelectorAll('tbody tr');
                            
                            pertanyaanRows.forEach((row) => {
                                const textarea = row.querySelector('textarea[name*="[jawaban]"]');
                                const radioYa = row.querySelector('input[type="radio"][value="Ya"]');
                                const radioTidak = row.querySelector('input[type="radio"][value="Tidak"]');
                                const noInput = row.querySelector('input[name*="[no]"]');
                                const noPertanyaan = noInput ? noInput.value : 'N/A';
                                
                                if (textarea) {
                                    textarea.classList.remove('border-red-500', 'ring-2', 'ring-red-500');
                                }

                                if(textarea && textarea.value.trim().length < 10) {
                                    isValid = false;
                                    textarea.classList.add('border-red-500', 'ring-2', 'ring-red-500');
                                    alert(`Gagal: Pertanyaan nomor ${noPertanyaan}: Kesimpulan jawaban minimal 10 karakter!`);
                                    e.preventDefault(); 
                                    return;
                                }
                                
                                if(radioYa && radioTidak && !radioYa.checked && !radioTidak.checked) {
                                    isValid = false;
                                    alert(`Gagal: Pertanyaan nomor ${noPertanyaan}: Pencapaian harus dipilih!`);
                                    e.preventDefault(); 
                                    return;
                                }
                            });
                        }
                        
                        if(!isValid) {
                            e.preventDefault();
                            return false;
                        }
                        
                        const btnSubmit = document.getElementById('btnSubmit');
                        btnSubmit.disabled = true;
                        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    });
                }
            });
        </script>
        @endif

    @if($mode === 'edit')
    </form> 
    @else
    </div>
    @endif
</main> 
@endsection