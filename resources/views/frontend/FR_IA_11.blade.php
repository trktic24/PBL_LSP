@extends($layout ?? 'layouts.app-sidebar')

@section('content')

    {{-- 1. ROLE LOGIC & DATA MAPPING (KRUSIAL) --}}
    @php
        // Asumsi variabel $user dan $ia11 tersedia dari Controller
        $role = $user->role ?? 'guest';
        $isAdmin = $role === 'admin';
        $isAsesor = $role === 'asesor';
        $isAsesi = $role === 'asesi';
        
        // Atribut Kontrol Edit
        $readOnlyAttr = 'disabled'; // Default: Admin/View Only
        $asesiEditableAttr = ($isAsesi || $isAsesor) ? '' : 'disabled'; // Asesi & Asesor bisa edit data produk
        $asesorEditableAttr = $isAsesor ? '' : 'disabled'; // Hanya Asesor yang bisa edit data penilaian
        
        // Data Assessment (dari JSON Workaround)
        $asesorData = $asesor_data ?? [];
        $penilaian = $asesorData['penilaian'] ?? [];
        
        // Ambil nilai dari JSON atau default
        $tukType = $asesorData['tuk_type'] ?? $ia11->tuk_type ?? '';
        $tanggalAsesmen = $asesorData['tanggal_asesmen'] ?? $tanggal_sekarang; // Tanggal asesmen disimpan di JSON
        $rekomKelompok = $asesorData['rekomendasi_kelompok'] ?? '';
        $rekomUnit = $asesorData['rekomendasi_unit'] ?? '';
        $catatanAsesor = $asesorData['catatan_asesor'] ?? '';
        $ttdAsesi = $asesorData['ttd_asesi'] ?? '';
        $ttdAsesor = $asesorData['ttd_asesor'] ?? '';
    @endphp

    <main class="main-content p-6 sm:p-10">

        <header class="form-header flex justify-between items-center border border-gray-900 shadow-md">
            {{-- ... Header tetap sama ... --}}
            <div class="p-4 w-full text-center">
                 <h1 class="font-bold text-xl">FR.IA.11. CEKLIS MENINJAU INSTRUMEN ASESMEN</h1>
                 @if(isset($isMasterView))
                    <div class="text-sm text-blue-600 mt-1">[TEMPLATE MASTER]</div>
                 @endif
            </div>
        </header>

        {{-- FORM AKSI: Mengirim data ke fungsi update di Controller --}}
        <form class="form-body mt-6" method="POST" action="{{ isset($isMasterView) ? '#' : route('ia11.update', $ia11->id_ia11) }}">
            @csrf
            @method('PUT') 

            <div class="border border-gray-900 shadow-md mb-6">
                <table class="w-full border-collapse text-sm">
                    <tbody>
                        {{-- BLOK SKEMA SERTIFIKASI --}}
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top" rowspan="2">
                                Skema Sertifikasi (KKNI/Okupasi/Klaster)
                            </td>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Judul
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                {{-- Judul Skema (Readonly) --}}
                                : <input type="text" name="judul_skema" value="{{ $judul_skema }}" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Nomor
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                {{-- Nomor Skema (Readonly) --}}
                                : <input type="text" name="nomor_skema" value="{{ $nomor_skema }}" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100">
                                {{-- ... Panduan Asesor ... --}}
                            </td>
                            <td colspan="2" class="border border-gray-900 p-2 w-2/3 space-y-2">
                                {{-- TUK: Hanya Asesor yang bisa edit --}}
                                <div class="flex items-center">
                                    <label class="font-medium w-36">TUK</label>
                                    <div class="radio-group flex items-center space-x-4">
                                        <span>:</span>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_sewaktu" name="tuk_type" value="Sewaktu" class="form-radio h-4 w-4 text-blue-600" {{ $asesorEditableAttr }} {{ $tukType === 'Sewaktu' ? 'checked' : '' }}>
                                            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_tempatkerja" name="tuk_type" value="Tempat Kerja" class="form-radio h-4 w-4 text-blue-600" {{ $asesorEditableAttr }} {{ $tukType === 'Tempat Kerja' ? 'checked' : '' }}>
                                            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_mandiri" name="tuk_type" value="Mandiri" class="form-radio h-4 w-4 text-blue-600" {{ $asesorEditableAttr }} {{ $tukType === 'Mandiri' ? 'checked' : '' }}>
                                            <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Nama Asesor (Readonly) --}}
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesor</label>
                                    <span>:</span>
                                    <input type="text" value="{{ $nama_asesor }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                                </div>
                                {{-- Nama Asesi (Readonly) --}}
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesi</label>
                                    <span>:</span>
                                    <input type="text" value="{{ $nama_asesi }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" disabled>
                                </div>
                                {{-- Tanggal: Hanya Asesor yang bisa edit --}}
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Tanggal</label>
                                    <span>:</span>
                                    <input type="date" name="tanggal_asesmen" value="{{ $tanggalAsesmen }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- ... Tabel Unit Kompetensi (Tetap sama, diasumsikan hanya menampilkan data) ... --}}

            <div class="border border-gray-900 shadow-md mt-6">
                <div class="bg-black text-white font-bold p-2 text-sm">RANCANGAN PRODUK ATAU DATA TEKNIS PRODUK</div>
                <table class="w-full border-collapse text-sm">
                    <tr>
                        <td class="border border-gray-900 p-2 w-1/2 align-top">
                            {{-- Nama Produk (DB Column: nama_produk) --}}
                            <div class="font-bold mb-1">Nama produk yang direviu :</div>
                            <input type="text" name="nama_produk" value="{{ $ia11->nama_produk }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesiEditableAttr }}>
                            
                            {{-- Standar Industri (DB Column: standar_industri) --}}
                            <div class="font-bold mb-1">Standar industri atau tempat kerja :</div>
                            <input type="text" name="standar_industri" value="{{ $ia11->standar_industri }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesiEditableAttr }}>
                            
                            {{-- Spesifikasi Umum (JSON Field) --}}
                            <div class="font-bold mb-1">Spesifikasi produk, secara umum :</div>
                            <textarea rows="3" name="spesifikasi_umum" class="form-textarea w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 text-xs" {{ $asesiEditableAttr }}>{{ $asesorData['spesifikasi_umum'] ?? '' }}</textarea>
                        </td>
                        <td class="border border-gray-900 p-2 w-1/2 align-top">
                            {{-- Dimensi produk (JSON Field) --}}
                            <div class="font-bold mb-1">Dimensi produk :</div>
                            <input type="text" name="dimensi_produk" value="{{ $asesorData['dimensi_produk'] ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesiEditableAttr }}>
                            
                            {{-- Bahan produk / Berat produk (JSON Field) --}}
                            <div class="font-bold mb-1">Bahan produk / Berat produk :</div>
                            <input type="text" name="bahan_produk" value="{{ $asesorData['bahan_produk'] ?? '' }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesiEditableAttr }}>
                            
                            {{-- Spesifikasi Teknis (JSON Field) --}}
                            <div class="font-bold mb-1">Spesifikasi produk secara teknis :</div>
                            <textarea rows="3" name="spesifikasi_teknis" class="form-textarea w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 text-xs" {{ $asesiEditableAttr }}>{{ $asesorData['spesifikasi_teknis'] ?? '' }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-gray-900 p-2 align-top">
                            {{-- Tanggal penggunaan (DB Column: tanggal_pengoperasian) --}}
                            <div class="font-bold mb-1">Tanggal penggunaan/penggunaan :</div>
                            <input type="text" name="tanggal_pengoperasian" value="{{ $ia11->tanggal_pengoperasian }}" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesiEditableAttr }}>
                            
                            {{-- Gambar produk (DB Column: gambar_produk) --}}
                            <div class="font-bold mb-1">Gambar produk (jika ada) :</div>
                            <input type="text" name="gambar_produk" value="{{ $ia11->gambar_produk }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesiEditableAttr }}>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- BLOK CHECKLIST REVIU PRODUK (HANYA ASESOR EDIT) --}}
            <div class="border border-gray-900 shadow-md mt-6">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        {{-- ... Header Tabel Penilaian ... --}}
                    </thead>
                    <tbody>
                        {{-- Helper untuk mendapatkan status checkbox dari JSON --}}
                        @php
                            $getChecked = function($name) use ($penilaian) {
                                return ($penilaian[$name] ?? false) ? 'checked' : '';
                            };
                        @endphp
                        
                        {{-- 1. Spesifikasi produk --}}
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="3">1.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Spesifikasi produk</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Ukuran produk sesuai rencana atau gambar kerja</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h1a_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h1a_tidak') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p1a_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p1a_tidak') }}></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">b. Estetika/penampilan produk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h1b_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h1b_tidak') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p1b_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p1b_tidak') }}></td>
                        </tr>

                        {{-- 2. Performa produk --}}
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="2">2.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Performa produk atau Karakteristik produk</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Kebersihan dan kerapian produk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h2a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h2a_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h2a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h2a_tidak') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p2a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p2a_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p2a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p2a_tidak') }}></td>
                        </tr>

                        {{-- 3. Keselamatan dan keamanan --}}
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="4">3.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Keselamatan dan keamanan</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Kesesuaian dengan gambar kerja atau bentuk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h3a_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h3a_tidak') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p3a_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p3a_tidak') }}></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">b. Kerapian dan kerapatan sambungan</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h3b_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h3b_tidak') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p3b_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p3b_tidak') }}></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">c. Pemasangan perlengkapan bahan pendukung</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3c_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h3c_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3c_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('h3c_tidak') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3c_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p3c_ya') }}></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3c_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ $asesorEditableAttr }} {{ $getChecked('p3c_tidak') }}></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-gray-900 p-2 text-xs italic">
                                *Diisi sesuai dengan jenis produk yang di review
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- BLOK REKOMENDASI ASESOR (HANYA ASESOR EDIT) --}}
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
                                Kelompok Pekerjaan: <input type="text" name="rekomendasi_kelompok" value="{{ $rekomKelompok }}" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32" {{ $asesorEditableAttr }}>
                                Unit Kompetensi: <input type="text" name="rekomendasi_unit" value="{{ $rekomUnit }}" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32" {{ $asesorEditableAttr }}>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- BLOK TANDA TANGAN --}}
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
                        {{-- HANYA ASESI yang bisa mengisi TTD --}}
                        <input type="text" name="ttd_asesi" value="{{ $ttdAsesi }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $isAsesi ? '' : 'disabled' }}>
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
                        {{-- HANYA ASESOR yang bisa mengisi TTD --}}
                        <input type="text" name="ttd_asesor" value="{{ $ttdAsesor }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                    </div>
                </div>
            </div>

            {{-- BLOK CATATAN (HANYA ASESOR EDIT) --}}
            <div class="mt-6">
                <div class="font-bold mb-1">Catatan:</div>
                <div class="text-sm italic">Tuliskan temuan pencapaian hasil reviu produk, jika belum/tidak terpenuhi.</div>
                {{-- Catatan Asesor (disimpan di JSON) --}}
                <textarea rows="4" name="catatan_asesor" class="form-textarea w-full border-gray-900 rounded-md shadow-md focus:border-blue-500 focus:ring-blue-500 text-sm" {{ $asesorEditableAttr }}>{{ $catatanAsesor }}</textarea>
            </div>

            {{-- BLOK PENYUSUN DAN VALIDATOR (HANYA ASESOR EDIT) --}}
            <h3 class="font-bold mt-6 mb-2">PENYUSUN DAN VALIDATOR</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        {{-- ... Header Penyusun/Validator ... --}}
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top @if($i==1) font-bold bg-gray-100 @else bg-white @endif" @if($i==1) rowspan="2" @endif>
                                @if($i==1) PENYUSUN @endif
                            </td>
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="penyusun_nama_{{ $i }}" value="{{ $asesorData['penyusun_nama_'.$i] ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="penyusun_nomor_met_{{ $i }}" value="{{ $asesorData['penyusun_nomor_met_'.$i] ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2 text-center">
                                <input type="text" name="penyusun_ttd_{{ $i }}" value="{{ $asesorData['penyusun_ttd_'.$i] ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
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
                                <input type="text" name="validator_nama_{{ $i }}" value="{{ $asesorData['validator_nama_'.$i] ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2">
                                <input type="text" name="validator_nomor_met_{{ $i }}" value="{{ $asesorData['validator_nomor_met_'.$i] ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                            </td>
                            <td class="border border-gray-900 p-2 text-center">
                                <input type="text" name="validator_ttd_{{ $i }}" value="{{ $asesorData['validator_ttd_'.$i] ?? '' }}" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" {{ $asesorEditableAttr }}>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL SIMPAN: Hanya ditampilkan jika peran bukan Admin --}}
            @if (!$isAdmin)
            <div class="mt-8 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
                    Simpan dan Perbarui FR.IA.11
                </button>
            </div>
            @endif

        </form>

    </main>
@endsection