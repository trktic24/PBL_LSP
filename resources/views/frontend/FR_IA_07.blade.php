@extends('layouts.app-sidebar')
@section('content')

    <main class="main-content">
        <div class="p-8">
        
        <x-header_form.header_form title="FR.IA.07. DPL - DAFTAR PERTANYAAN LISAN" />

        <form class="form-body mt-6">               
            <x-identitas_skema_form.identitas_skema_form
                skema="Junior Web Developer"
                nomorSkema="SKK.XXXXX.XXXX"
                tuk="Tempat Kerja" 
                namaAsesor="Ajeng Febria Hidayati"
                namaAsesi="Tatang Sidartang"
                tanggal="3 November 2025"
                :showWaktu="false" 
            />


            <div class="guide-box bg-gray-100 border-gray-100 p-6 rounded-md shadow-sm my-8">
                <h3 class="text-lg font-bold text-black mb-3">PANDUAN BAGI ASESOR</h3>
                <ul class="list-disc list-inside space-y-2 text-black">
                    <li>Tentukan pihak ketiga yang akan dimintai verifikasi.</li>
                    <li>Ajukan pertanyaan kepada pihak ketiga.</li>
                    <li>Berikan penilaian kepada asesi berdasarkan verifikasi pihak ketiga.</li>
                    <li>Pertanyaan/pernyataan dapat dikembangkan sesuai dengan konteks pekerjaan dan relasi.</li>
                </ul>
            </div>

            <div class="form-section my-8">

                <div class="border border-gray-900 shadow-md mb-6">
                    <div class="grid grid-cols-[100px_1fr] divide-x divide-gray-900">
                        <div class="font-bold p-2 bg-black text-white border-r border-gray-900">Instruksi:</div>
                        <ol class="list-decimal list-inside p-2 space-y-1 text-sm">
                            <li>Ajukan pertanyaan kepada Asesi dari daftar pertanyaan di bawah ini untuk mengonfirmasi pengetahuan, sebagaimana diperlukan.</li>
                            <li>Tempatkan centang di kotak pencapaian "Ya" atau "Tidak".</li>
                            <li>Tulis jawaban Asesi secara singkat di tempat yang disediakan dan konfirmasi ulang untuk setiap jawaban.</li>
                        </ol>
                    </div>
                </div>

                <x-table>
                    <x-slot name="thead">
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 w-36 font-semibold"></th>                            
                            <th class="border border-gray-900 p-2 w-12 font-semibold">No.</th>
                            <th class="border border-gray-900 p-2 w-28 font-semibold">Kode Unit</th>
                            <th class="border border-gray-900 p-2 font-semibold">Judul Unit</th>
                        </tr>
                    </x-slot>
                    <tr>
                        <td class="bg-black text-white border border-gray-900 p-2 h-10 text-sm align-top text-center font-bold" rowspan="3">
                                            Kelompok Pekerjaan ...</td>                                
                        <td class="border border-gray-900 p-2 text-sm text-center">1.</td>
                        <td class="border border-gray-900 p-2 text-sm"></td>
                        <td class="border border-gray-900 p-2 text-sm"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 text-sm text-center">2.</td>
                        <td class="border border-gray-900 p-2 text-sm"></td>
                        <td class="border border-gray-900 p-2 text-sm"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 text-sm text-center">Dst.</td>
                        <td class="border border-gray-900 p-2 text-sm"></td>
                        <td class="border border-gray-900 p-2 text-sm"></td>
                    </tr>                        
                </x-table>
            </div>

            <div class="form-section my-8">
                
                <x-table>
                    <x-slot name="thead">
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 w-12 text-sm font-semibold">No.</th>
                            <th class="border border-gray-900 p-2 text-sm font-semibold">Pertanyaan</th>
                            <th colspan="2" class="border border-gray-900 p-2 w-32 text-sm font-semibold">Pencapaian</th>
                        </tr>
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 text-xs font-normal"></th>
                            <th class="border border-gray-900 text-xs font-normal"></th>
                            <th class="border border-gray-900 p-1 w-16 text-xs font-normal">Ya</th>
                            <th class="border border-gray-900 p-1 w-16 text-xs font-normal">Tidak</th>
                        </tr>                        
                    </x-slot>
                    <tr>
                        <td rowspan="3" class="border border-gray-900 p-2 text-center text-sm font-bold align-top">
                            1.
                        </td>
                        <td class="border border-gray-900 px-3 py-1 text-sm font-medium">Pertanyaan:</td>
                        <td class="border border-gray-900 text-center">
                            <input type="checkbox" name="p1_ya" value="ya" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                        <td class="border border-gray-900 text-center">
                            <input type="checkbox" name="p1_tidak" value="tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 border-r-0 px-3 py-1 text-xs bg-gray-50">Kunci Jawaban:</td>
                        <td colspan="2" class="border border-gray-900 text-center bg-gray-50"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 px-3 py-1 text-sm">Jawaban Asesi:</td>
                        <td colspan="2" class="border border-gray-900 text-center"></td>
                    </tr>

                    <tr>
                        <td rowspan="3" class="border border-gray-900 p-2 text-center text-sm font-bold align-top">
                            2.
                        </td>
                        <td class="border border-gray-900 px-3 py-1 text-sm font-medium">Pertanyaan:</td>
                        <td class="border border-gray-900 text-center">
                            <input type="checkbox" name="p2_ya" value="ya" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                        <td class="border border-gray-900 text-center">
                            <input type="checkbox" name="p2_tidak" value="tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 px-3 py-1 text-xs bg-gray-50">Kunci Jawaban:</td>
                        <td colspan="2" class="border border-gray-900 text-center bg-gray-50"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 px-3 py-1 text-sm">Jawaban Asesi:</td>
                        <td colspan="2" class="border border-gray-900 text-center"></td>
                    </tr>

                    <tr>
                        <td rowspan="3" class="border border-gray-900 p-2 text-center text-sm font-bold align-top">
                            3.
                        </td>
                        <td class="border border-gray-900 px-3 py-1 text-sm font-medium">Pertanyaan:</td>
                        <td class="border border-gray-900 text-center">
                            <input type="checkbox" name="p3_ya" value="ya" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                        <td class="border border-gray-900 text-center">
                            <input type="checkbox" name="p_tidak" value="tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 px-3 py-1 text-xs bg-gray-50">Kunci Jawaban:</td>
                        <td colspan="2" class="border border-gray-900 text-center bg-gray-50"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 px-3 py-1 text-sm">Jawaban Asesi:</td>
                        <td colspan="2" class="border border-gray-900 text-center"></td>
                    </tr>
                </x-table>                            
            </div>

            <div class="form-section my-8">
                <x-table>
                    <x-slot name="thead">
                        <!-- Umpan balik untuk asesi -->
                        <tr>
                            <td class="border border-gray-900 p-2 font-semibold w-40 bg-black text-white">Umpan balik untuk asesi</td>
                            <td colspan="2" class="border border-gray-900 p-2">
                            Aspek pengetahuan seluruh unit kompetensi yang diuji <br>
                            <strong>(tercapai / belum tercapai)*</strong><br>
                            Tuliskan Unit Kompetensi / Elemen / KUK jika belum tercapai: ....
                            </td>                        
                        </tr>
                    </x-slot>
                    <!-- ASESI -->
                    <tr class="bg-gray-100 font-semibold">
                        <td class="border border-gray-900 p-2 bg-black text-white" colspan="3">ASESI</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 w-32">Nama</td>
                        <td class="border border-gray-900 p-2 w-8 text-center">:</td>
                        <td class="border border-gray-900 p-2"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 w-32">Tanda tangan dan Tanggal</td>
                        <td class="border border-gray-900 p-2 w-8 text-center">:</td>
                        <td class="border border-gray-900 p-2"></td>
                    </tr>

                    <!-- ASESOR -->
                    <tr class="bg-gray-100 font-semibold">
                        <td class="border border-gray-900 p-2 bg-black text-white" colspan="3">ASESOR</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 w-32">Nama</td>
                        <td class="border border-gray-900 p-2 w-8 text-center">:</td>
                        <td class="border border-gray-900 p-2"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 w-32">No. Reg. MET</td>
                        <td class="border border-gray-900 p-2 w-8 text-center">:</td>
                        <td class="border border-gray-900 p-2"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-900 p-2 w-32">Tanda tangan dan Tanggal</td>
                        <td class="border border-gray-900 p-2 w-8 text-center">:</td>
                        <td class="border border-gray-900 p-2"></td>
                    </tr>
                </x-table>
            </div>


            <div class="form-section overflow-x-auto my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Penyusun dan Validator</h2>
                <x-kolom_ttd.penyusunvalidator/>
            
            </div>
        </form>
        </div>

    </main>
@endsection