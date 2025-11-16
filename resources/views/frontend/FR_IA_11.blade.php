@extends('layouts.app-sidebar')

@section('content')

    <main class="main-content p-6 sm:p-10">

        <header class="form-header flex justify-between items-center border border-gray-900 shadow-md">
            <div class="p-4 bg-gray-100 w-full">
                <h1 class="text-lg md:text-2xl font-bold text-gray-900 text-center uppercase">
                    FR.IA.11. CEKLIST REVIU PRODUK (CRP)
                </h1>
            </div>
            <div class="border-l border-gray-900 p-4 text-center font-bold text-3xl hidden sm:block">
                FR.IA.11
            </div>
        </header>

        <form class="form-body mt-6">

            <div class="border border-gray-900 shadow-md mb-6">
                <table class="w-full border-collapse text-sm">
                    <tbody>
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top" rowspan="2">
                                Skema Sertifikasi (KKNI/Okupasi/Klaster)
                            </td>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Judul
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                : <input type="text" value="[Judul Skema Sertifikasi]" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100 align-top">
                                Nomor
                            </td>
                            <td class="border border-gray-900 p-2 w-1/3">
                                : <input type="text" value="[Nomor Skema Sertifikasi]" class="form-input w-2/3 ml-2 border-none p-0 text-sm focus:ring-0">
                            </td>
                        </tr>

                        <tr>
                            <td class="border border-gray-900 p-2 font-bold w-1/3 bg-gray-100">
                                <div class="font-bold">PANDUAN BAGI ASESOR</div>
                                <div class="text-xs font-normal mt-1">Instruksi:</div>
                                <ol class="list-decimal list-inside pl-3 space-y-1 text-xs font-normal">
                                    <li>Diisi oleh Asesi sebelum/saat proses asesmen.</li>
                                    <li>Digunakan untuk menilai produk yang telah dibuat/dipersiapkan/digunakan minimal satu tahun, kecuali (garis/format yang ditetapkan).</li>
                                    <li>Pembahasan yang tidak perlu dalam format ini dapat dipertimbangkan/dilengkapi dengan lebih spesifik sesuai kebutuhan kelompok/lembaga.</li>
                                </ol>
                            </td>
                            <td colspan="2" class="border border-gray-900 p-2 w-2/3 space-y-2">
                                <div class="flex items-center">
                                    <label class="font-medium w-36">TUK</label>
                                    <div class="radio-group flex items-center space-x-4">
                                        <span>:</span>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-blue-600">
                                            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_tempatkerja" name="tuk_type" class="form-radio h-4 w-4 text-blue-600">
                                            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="tuk_mandiri" name="tuk_type" checked class="form-radio h-4 w-4 text-blue-600">
                                            <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesor</label>
                                    <span>:</span>
                                    <input type="text" value="[Nama Asesor]" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Nama Asesi</label>
                                    <span>:</span>
                                    <input type="text" value="[Nama Asesi]" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
                                </div>
                                <div class="flex items-center">
                                    <label class="font-medium w-36">Tanggal</label>
                                    <span>:</span>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="border border-gray-900 shadow-md mt-8">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-black text-white text-center">
                            <th class="border border-gray-900 p-2 w-1/5 font-semibold">Kelompok Pekerjaan</th>
                            <th class="border border-gray-900 p-2 w-1/12 font-semibold">No.</th>
                            <th class="border border-gray-900 p-2 w-1/5 font-semibold">Kode Unit</th>
                            <th class="border border-gray-900 p-2 w-auto font-semibold">Judul Unit</th>
                            <th class="border border-gray-900 p-2 w-1/12 font-semibold">Waktu</th>
                            <th class="border border-gray-900 p-2 w-1/12 font-semibold">Tempat Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top">{{-- Contoh Kelompok --}}</td>
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}.</td>
                            <td class="border border-gray-900 p-2">{{-- Contoh Kode Unit --}}</td>
                            <td class="border border-gray-900 p-2">{{-- Contoh Judul Unit --}}</td>
                            <td class="border border-gray-900 p-2 text-center">{{-- Waktu --}}</td>
                            <td class="border border-gray-900 p-2 text-center">{{-- Tempat --}}</td>
                        </tr>
                        @endfor
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top" colspan="6">Dst.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="border border-gray-900 shadow-md mt-6">
                <div class="bg-black text-white font-bold p-2 text-sm">RANCANGAN PRODUK ATAU DATA TEKNIS PRODUK</div>
                <table class="w-full border-collapse text-sm">
                    <tr>
                        <td class="border border-gray-900 p-2 w-1/2 align-top">
                            <div class="font-bold mb-1">Nama produk yang direviu :</div>
                            <input type="text" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                            <div class="font-bold mb-1">Standar industri atau tempat kerja :</div>
                            <input type="text" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                            <div class="font-bold mb-1">Spesifikasi produk, secara umum :</div>
                            <textarea rows="3" class="form-textarea w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 text-xs"></textarea>
                        </td>
                        <td class="border border-gray-900 p-2 w-1/2 align-top">
                            <div class="font-bold mb-1">Dimensi produk :</div>
                            <input type="text" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                            <div class="font-bold mb-1">Bahan produk / Berat produk :</div>
                            <input type="text" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                            <div class="font-bold mb-1">Spesifikasi produk secara teknis :</div>
                            <textarea rows="3" class="form-textarea w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 text-xs"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-gray-900 p-2 align-top">
                            <div class="font-bold mb-1">Tanggal penggunaan/penggunaan :</div>
                            <input type="text" class="form-input w-full mb-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                            <div class="font-bold mb-1">Gambar produk (jika ada) :</div>
                            <input type="text" class="form-input w-full border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="border border-gray-900 shadow-md mt-6">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-black text-white text-center">
                            <th class="border border-gray-900 p-2 w-1/12 font-semibold" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2 w-1/3 font-semibold" rowspan="2">Spesifikasi dan Performan Produk</th>
                            <th class="border border-gray-900 p-2 w-1/4 font-semibold" colspan="2">Hasil Review Produk*</th>
                            <th class="border border-gray-900 p-2 w-1/4 font-semibold" colspan="2">Pencapaian</th>
                        </tr>
                        <tr class="bg-black text-white text-center text-xs">
                            <th class="border border-gray-900 p-1 w-1/8 font-normal">Ya</th>
                            <th class="border border-gray-900 p-1 w-1/8 font-normal">Tidak</th>
                            <th class="border border-gray-900 p-1 w-1/8 font-normal">Ya</th>
                            <th class="border border-gray-900 p-1 w-1/8 font-normal">Tidak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="3">1.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Spesifikasi produk</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Ukuran produk sesuai rencana atau gambar kerja</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">b. Estetika/penampilan produk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h1b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p1b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                        </tr>

                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="2">2.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Performa produk atau Karakteristik produk</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Kebersihan dan kerapian produk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h2a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h2a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p2a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p2a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                        </tr>

                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top font-bold" rowspan="4">3.</td>
                            <td class="border border-gray-900 px-3 py-1 font-bold">Keselamatan dan keamanan</td>
                            <td class="border border-gray-900 text-center" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">a. Kesesuaian dengan gambar kerja atau bentuk</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3a_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3a_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">b. Kerapian dan kerapatan sambungan</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3b_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3b_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 px-3 py-1 text-xs">c. Pemasangan perlengkapan bahan pendukung</td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3c_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="h3c_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3c_ya" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                            <td class="border border-gray-900 text-center"><input type="checkbox" name="p3c_tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-gray-900 p-2 text-xs italic">
                                *Diisi sesuai dengan jenis produk yang di review
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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
                                Kelompok Pekerjaan: <input type="text" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32">
                                Unit Kompetensi: <input type="text" class="form-input border-b border-dashed border-gray-400 p-0 text-xs focus:ring-0 w-32">
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
                        <input type="text" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
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
                        <input type="text" class="form-input w-full ml-2 border-b border-dashed border-gray-400 p-0 text-sm focus:ring-0" readonly>
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
                <div class="text-sm italic">Tuliskan temuan pencapaian hasil reviu produk, jika belum/tidak terpenuhi.</div>
                <textarea rows="4" class="form-textarea w-full border-gray-900 rounded-md shadow-md focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
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
                        @for ($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top @if($i==1) font-bold bg-gray-100 @else bg-white @endif" @if($i==1) rowspan="2" @endif>
                                @if($i==1) PENYUSUN @endif
                            </td>
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                            <td class="border border-gray-900 p-2">{{-- Input Nama --}}</td>
                            <td class="border border-gray-900 p-2">{{-- Input No. MET --}}</td>
                            <td class="border border-gray-900 p-2 text-center">{{-- Input TTD & Tanggal --}}</td>
                        </tr>
                        @endfor
                        @for ($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="border border-gray-900 p-2 text-center align-top @if($i==1) font-bold bg-gray-100 @else bg-white @endif" @if($i==1) rowspan="2" @endif>
                                @if($i==1) VALIDATOR @endif
                            </td>
                            <td class="border border-gray-900 p-2 text-center">{{ $i }}</td>
                            <td class="border border-gray-900 p-2">{{-- Input Nama --}}</td>
                            <td class="border border-gray-900 p-2">{{-- Input No. MET --}}</td>
                            <td class="border border-gray-900 p-2 text-center">{{-- Input TTD & Tanggal --}}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

        </form>

    </main>
@endsection