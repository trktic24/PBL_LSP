@extends($layout ?? 'layouts.app-sidebar')

@section('content')

    @php
        // Simplified role check
        $isEditable = in_array($user->role->nama_role ?? 'guest', ['admin', 'asesor']);
        $editableAttr = $isEditable ? '' : 'disabled';
    @endphp

    <main class="main-content p-6 sm:p-10">

        <header class="form-header flex justify-between items-center border border-gray-900 shadow-md">
            <div class="p-4 w-full text-center">
                 <h1 class="font-bold text-xl">FR.IA.11. CEKLIS MENINJAU INSTRUMEN ASESMEN</h1>
                 @if(isset($isMasterView))
                    <div class="text-sm text-blue-600 mt-1">[TEMPLATE MASTER]</div>
                 @endif
            </div>
        </header>

        {{-- The form now points to a single upsert logic handled by the controller --}}
        <form class="form-body mt-6" method="POST" action="{{ $ia11->exists ? route('ia11.update', $ia11->id_ia11) : route('ia11.store') }}">
            @csrf
            @if ($ia11->exists)
                @method('PUT')
            @endif
            <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $ia11->id_data_sertifikasi_asesi }}">

            <div class="border border-gray-900 shadow-md mb-6">
                <table class="w-full border-collapse text-sm">
                    <tbody>
                        {{-- Skema Sertifikasi Block --}}
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top" rowspan="2">
                                Skema Sertifikasi (KKNI/Okupasi/Klaster)
                            </td>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Judul
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                : <input type="text" value="{{ $judul_skema }}" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Nomor
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                : <input type="text" value="{{ $nomor_skema }}" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0" disabled>
                            </td>
                        </tr>

                        {{-- Detail Asesmen Block --}}
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100">
                                Panduan Bagi Asesor
                            </td>
                            <td colspan="2" class="border border-gray-900 p-2 w-2/3 space-y-2">
                                <div class="flex items-center">
                                    <label class="font-medium w-36">TUK</label>
                                    <div class="radio-group flex items-center space-x-4">
                                        <span>:</span>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_sewaktu" name="tuk_type" value="Sewaktu" class="form-radio h-4 w-4 text-blue-600" {{ $editableAttr }} {{ ($ia11->tuk_type ?? '') === 'Sewaktu' ? 'checked' : '' }}>
                                            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_tempatkerja" name="tuk_type" value="Tempat Kerja" class="form-radio h-4 w-4 text-blue-600" {{ $editableAttr }} {{ ($ia11->tuk_type ?? '') === 'Tempat Kerja' ? 'checked' : '' }}>
                                            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_mandiri" name="tuk_type" value="Mandiri" class="form-radio h-4 w-4 text-blue-600" {{ $editableAttr }} {{ ($ia11->tuk_type ?? '') === 'Mandiri' ? 'checked' : '' }}>
                                            <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesor</label>
                                    <span>:</span>
                                    <input type="text" value="{{ $nama_asesor }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesi</label>
                                    <span>:</span>
                                    <input type="text" value="{{ $nama_asesi }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Tanggal</label>
                                    <span>:</span>
                                    <input type="date" name="tanggal_asesmen" value="{{ $ia11->tanggal_asesmen?->format('Y-m-d') ?? $tanggal_sekarang }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Data Teknis Produk Block --}}
            <div class="border border-gray-900 shadow-md mt-6">
                <div class="bg-black text-white font-bold p-2 text-sm">RANCANGAN PRODUK ATAU DATA TEKNIS PRODUK</div>
                <table class="w-full border-collapse text-sm">
                    <tr>
                        <td class="border border-gray-900 p-2 w-1/2 align-top">
                            <div class="font-bold mb-1">Nama produk yang direviu :</div>
                            <input type="text" name="nama_produk" value="{{ $ia11->nama_produk ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            
                            <div class="font-bold mb-1">Standar industri atau tempat kerja :</div>
                            <input type="text" name="standar_industri" value="{{ $ia11->standar_industri ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            
                            <div class="font-bold mb-1">Spesifikasi produk, secara umum :</div>
                            <textarea rows="3" name="spesifikasi_umum" class="form-textarea w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 text-xs" {{ $editableAttr }}>{{ $ia11->spesifikasi_umum ?? '' }}</textarea>
                        </td>
                        <td class="border border-gray-900 p-2 w-1/2 align-top">
                            <div class="font-bold mb-1">Dimensi produk :</div>
                            <input type="text" name="dimensi_produk" value="{{ $ia11->dimensi_produk ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            
                            <div class="font-bold mb-1">Bahan produk / Berat produk :</div>
                            <input type="text" name="bahan_produk" value="{{ $ia11->bahan_produk ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            
                            <div class="font-bold mb-1">Spesifikasi produk secara teknis :</div>
                            <textarea rows="3" name="spesifikasi_teknis" class="form-textarea w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 text-xs" {{ $editableAttr }}>{{ $ia11->spesifikasi_teknis ?? '' }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-gray-900 p-2 align-top">
                            <div class="font-bold mb-1">Tanggal penggunaan/penggunaan :</div>
                            <input type="date" name="tanggal_pengoperasian" value="{{ $ia11->tanggal_pengoperasian?->format('Y-m-d') ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            
                            <div class="font-bold mb-1">Gambar produk (jika ada) :</div>
                            <input type="text" name="gambar_produk" value="{{ $ia11->gambar_produk ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Checklist Reviu Block --}}
            <div class="border border-gray-900 shadow-md mt-6">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-900 p-2" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2" rowspan="2">DAFTAR PERTANYAAN</th>
                            <th class="border border-gray-900 p-2 text-center" colspan="2">HASIL</th>
                            <th class="border border-gray-900 p-2 text-center" colspan="2">PENCAPAIAN</th>
                        </tr>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-900 p-2 text-center text-xs">Ya</th>
                            <th class="border border-gray-900 p-2 text-center text-xs">Tidak</th>
                            <th class="border border-gray-900 p-2 text-center text-xs">Ya</th>
                            <th class="border border-gray-900 p-2 text-center text-xs">Tidak</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 1. Spesifikasi produk --}}
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="3">1.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Spesifikasi produk</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Ukuran produk sesuai rencana atau gambar kerja</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h1a_hasil ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h1a_hasil ?? null) === false ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p1a_pencapaian ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p1a_pencapaian ?? null) === false ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">b. Estetika/penampilan produk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h1b_hasil ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h1b_hasil ?? null) === false ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p1b_pencapaian ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p1b_pencapaian ?? null) === false ? 'checked' : '' }}></td>
                        </tr>

                        {{-- 2. Performa produk --}}
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="2">2.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Performa produk atau Karakteristik produk</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Kebersihan dan kerapian produk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h2a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h2a_hasil ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h2a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h2a_hasil ?? null) === false ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p2a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p2a_pencapaian ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p2a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p2a_pencapaian ?? null) === false ? 'checked' : '' }}></td>
                        </tr>

                        {{-- 3. Keselamatan dan keamanan --}}
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="4">3.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Keselamatan dan keamanan</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Kesesuaian dengan gambar kerja atau bentuk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h3a_hasil ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h3a_hasil ?? null) === false ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p3a_pencapaian ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p3a_pencapaian ?? null) === false ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">b. Kerapian dan kerapatan sambungan</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h3b_hasil ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h3b_hasil ?? null) === false ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p3b_pencapaian ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p3b_pencapaian ?? null) === false ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">c. Pemasangan perlengkapan bahan pendukung</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3c_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h3c_hasil ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3c_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->h3c_hasil ?? null) === false ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3c_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p3c_pencapaian ?? null) === true ? 'checked' : '' }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3c_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $editableAttr }} {{ ($ia11->p3c_pencapaian ?? null) === false ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-gray-900 p-2 text-xs italic">
                                *Diisi sesuai dengan jenis produk yang di review
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Rekomendasi Asesor Block --}}
            <div class="border border-gray-900 shadow-md mt-6">
                <table class="w-full border-collapse text-sm">
                    <tr>
                        <td class="border border-gray-900 p-2 font-semibold w-1/4 bg-black text-white">REKOMENDASI ASESOR</td>
                        <td class="border border-gray-900 p-2 w-3/4">
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                <li>Asesi telah memenuhi pencapaian seluruh kriteria untuk kerja, direkomendasikan **KOMPETEN**</li>
                                <li>Asesi belum memenuhi pencapaian seluruh kriteria untuk kerja, direkomendasikan **OBSERVASI LANGSUNG**</li>
                            </ul>
                            <div class="mt-2 text-xs">
                                Kelompok Pekerjaan: <input type="text" name="rekomendasi_kelompok" value="{{ $ia11->rekomendasi_kelompok ?? '' }}" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32" {{ $editableAttr }}>
                                Unit Kompetensi: <input type="text" name="rekomendasi_unit" value="{{ $ia11->rekomendasi_unit ?? '' }}" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32" {{ $editableAttr }}>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Tanda Tangan Block --}}
            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                    <div class="font-bold mb-3">ASESI</div>
                    <div class="flex items-center mb-2">
                        <label class="w-24">Nama</label>
                        <span>:</span>
                        <input type="text" value="{{ $nama_asesi }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24">Tanda tangan dan Tanggal</label>
                        <span>:</span>
                        <input type="text" name="ttd_asesi" value="{{ $ia11->ttd_asesi ?? '' }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                    </div>
                </div>
                <div class="border border-gray-900 p-4 shadow-md bg-gray-50">
                    <div class="font-bold mb-3">ASESOR</div>
                    <div class="flex items-center mb-2">
                        <label class="w-24">Nama</label>
                        <span>:</span>
                        <input type="text" value="{{ $nama_asesor }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                    </div>
                    <div class="flex items-center mb-2">
                        <label class="w-24">No. Reg. MET</label>
                        <span>:</span>
                        <input type="text" value="[No. Reg. MET Asesor]" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24">Tanda tangan dan Tanggal</label>
                        <span>:</span>
                        <input type="text" name="ttd_asesor" value="{{ $ia11->ttd_asesor ?? '' }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                    </div>
                </div>
            </div>

            {{-- Catatan Asesor Block --}}
            <div class="mt-6">
                <div class="font-bold mb-1">Catatan:</div>
                <div class="text-sm italic">Tuliskan temuan pencapaian hasil reviu produk, jika belum/tidak terpenuhi.</div>
                <textarea rows="4" name="catatan_asesor" class="form-textarea w-full border-gray-900 rounded-md shadow-md focus:border-blue-500 focus:ring-blue-500 text-sm" {{ $editableAttr }}>{{ $ia11->catatan_asesor ?? '' }}</textarea>
            </div>

            {{-- Penyusun dan Validator Block --}}
            <h3 class="font-bold mt-6 mb-2">PENYUSUN DAN VALIDATOR</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-900 p-2" colspan="2"></th>
                            <th class="border border-gray-900 p-2">Nama</th>
                            <th class="border border-gray-900 p-2">No. MET</th>
                            <th class="border border-gray-900 p-2">Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top @if($i==1) font-bold bg-gray-100 @else bg-white @endif" @if($i==1) rowspan="2" @endif>
                                @if($i==1) PENYUSUN @endif
                            </td>
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="penyusun_nama_{{ $i }}" value="{{ $ia11->{'penyusun_nama_'.$i} ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="penyusun_nomor_met_{{ $i }}" value="{{ $ia11->{'penyusun_nomor_met_'.$i} ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2 text-center">
                                <input type="text" name="penyusun_ttd_{{ $i }}" value="{{ $ia11->{'penyusun_ttd_'.$i} ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            </td>
                        </tr>
                        @endfor
                        @for ($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top @if($i==1) font-bold bg-gray-100 @else bg-white @endif" @if($i==1) rowspan="2" @endif>
                                @if($i==1) VALIDATOR @endif
                            </td>
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="validator_nama_{{ $i }}" value="{{ $ia11->{'validator_nama_'.$i} ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="validator_nomor_met_{{ $i }}" value="{{ $ia11->{'validator_nomor_met_'.$i} ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2 text-center">
                                <input type="text" name="validator_ttd_{{ $i }}" value="{{ $ia11->{'validator_ttd_'.$i} ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $editableAttr }}>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            @if ($isEditable)
            <div class="mt-8 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
                    Simpan dan Perbarui FR.IA.11
                </button>
            </div>
            @endif

        </form>

    </main>
@endsection