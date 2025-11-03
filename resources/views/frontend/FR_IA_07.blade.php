@extends('layouts.app-sidebar')
@section('content')

    <main class="main-content">
        
        <header class="form-header">
            <img src="{{ asset('images/logo_bnsp.png') }}" alt="Logo BNSP" class="h-12 w-auto">
            <div class="text-center mt-8">
                <h1 class="text-4xl md:text-4xl font-bold text-gray-900">FR.IA.07. DPL - DAFTAR PERTANYAAN LISAN</h1>
            </div>
        </header>

        <form class="form-body mt-10">
            
            <div class="form-row grid grid-cols-[250px_1fr] gap-x-6 gap-y-4 items-center mb-8">
                
                <label class="text-sm font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="Junior Web Developer (Contoh)" 
                        class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">Nomor</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="SSK.XX.XXXX (Contoh)" 
                        class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">TUK</label>
                <div class="radio-group flex items-center space-x-4">
                    <span>:</span>
                    <div class="flex items-center space-x-2 ml-2">
                        <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="tuk_tempatkerja" name="tuk_type" checked class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="tuk_mandiri" name="tuk_type" class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                    </div>
                </div>

                <label class="text-sm font-medium text-gray-700">Nama Asesor</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="Ajeng Febria Hidayati (Contoh)" 
                        class="form-input w-full ml-2" readonly>
                </div>
                
                <label class="text-sm font-medium text-gray-700">Nama Asesi</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="Tatang Sidartang (Contoh)" 
                        class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">Tanggal</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="date" value="<?php echo date('Y-m-d'); ?>" 
                        class="form-input w-full ml-2">
                </div>
            </div>

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

                <div class="border border-gray-900 shadow-md">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="border border-gray-900 p-2 w-36 font-semibold"></th>                            
                                <th class="border border-gray-900 p-2 w-12 font-semibold">No.</th>
                                <th class="border border-gray-900 p-2 w-28 font-semibold">Kode Unit</th>
                                <th class="border border-gray-900 p-2 font-semibold">Judul Unit</th>
                            </tr>
                        </thead>
                        <tbody>
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
                        </tbody>
                    </table>
                </div>           
                
                <div class="h-6"></div> <div class="border border-gray-900 shadow-md">
                    <table class="w-full border-collapse">
                        <thead>
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
                        </thead>
                        <tbody>
                            
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

                        </tbody>
                    </table>
                </div>              
            </div>

                <div class="border border-gray-900 shadow-md w-full max-w-4xl mx-auto">
                <table class="w-full border-collapse">
                    <tbody>
                    <!-- Umpan balik untuk asesi -->
                    <tr>
                        <td class="border border-gray-900 p-2 font-semibold w-40 bg-black text-white">Umpan balik untuk asesi</td>
                        <td colspan="2" class="border border-gray-900 p-2">
                        Aspek pengetahuan seluruh unit kompetensi yang diuji <br>
                        <strong>(tercapai / belum tercapai)*</strong><br>
                        Tuliskan Unit Kompetensi / Elemen / KUK jika belum tercapai: ....
                        </td>                        
                    </tr>

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
                    </tbody>
                </table>
                </div>


                <h3 class="font-bold mt-6">PENYUSUN DAN VALIDATOR</h3>
                <x-table>
                    <!-- ttd asesi asesor -->
                    <x-slot name="thead">
                        <tr>
                            <td class="border border-gray-900 p-2 font-bold text-center w-[80px] bg-black text-white">STATUS</td>
                            <td class="border border-gray-900 p-2 font-bold text-center w-[30px] bg-black text-white">NO</td>
                            <td class="border border-gray-900 p-2 font-bold text-center w-[200px] bg-black text-white">NAMA</td>                        
                            <td class="border border-gray-900 p-2 font-bold text-center w-[100px] bg-black text-white">NOMOR MET</td>
                            <td class="border border-gray-900 p-2 font-bold text-center w-[80px] bg-black text-white">TANDA TANGAN DAN TANGGAL</td>                                                
                        </tr>
                    </x-slot>

                    <!-- penyusun -->
                    <tr class="bg-gray-100 font-semibold">
                        <td class="border border-gray-900 p-2 bg-black text-white">PENYUSUN</td>
                        <td class="border border-gray-900 p-2 text-center">1</td>
                        <td class="border border-gray-900 p-2"></td>
                        <td class="border border-gray-900 p-2"></td>
                        <td class="border border-gray-900 p-2 text-center"></td>
                    </tr>

                    <!-- validator -->
                    <tr class="bg-gray-100 font-semibold">
                        <td rowspan="2" class="border border-gray-900 p-2 bg-black text-white">VALIDATOR</td>
                        <td class="border border-gray-900 p-2 text-center">2</td>
                        <td class="border border-gray-900 p-2"></td>
                        <td class="border border-gray-900 p-2"></td>
                        <td class="border border-gray-900 p-2 "></td>                        
                    </tr>
                </x-table>             



        
        </form>

    </main>
@endsection