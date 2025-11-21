@extends('layouts.app-sidebar')
@section('content')

    <main class="main-content">
        
        <header class="form-header">
            <img src="{{ asset('images/logo_bnsp.png') }}" alt="Logo BNSP" class="h-12 w-auto">
            <div class="text-center mt-8">
                <h1 class="text-4xl md:text-4xl font-bold text-gray-900">FR.IA.07. DPL - DAFTAR PERTANYAAN LISAN</h1>
            </div>
        </header>

        {{-- Menggunakan route ia07.store dan method POST --}}
        <form action="{{ route('ia07.store') }}" method="POST" class="form-body mt-10">
            @csrf
            
            <div class="form-row grid grid-cols-[250px_1fr] gap-x-6 gap-y-4 items-center mb-8">
                
                <label class="text-sm font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div class="flex items-center">
                    <span>:</span>
                    {{-- DATA DARI $skema --}}
                    <input type="text" name="skema_sertifikasi" value="{{ $skema->nama_skema ?? 'N/A' }}" 
                        class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">Nomor</label>
                <div class="flex items-center">
                    <span>:</span>
                    {{-- DATA DARI $skema --}}
                    <input type="text" name="nomor_skema" value="{{ $skema->nomor_skema ?? 'N/A' }}" 
                        class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">TUK</label>
                <div class="radio-group flex items-center space-x-4">
                    <span>:</span>
                    {{-- LOOPING DATA JENIS TUK DARI DATABASE --}}
                    @forelse($jenisTukOptions as $tuk)
                    <div class="flex items-center space-x-2 ml-2">
                        {{-- Menggunakan id_jenis_tuk sebagai value yang akan dikirim --}}
                        <input type="radio" id="tuk_{{ $tuk->id_jenis_tuk }}" name="id_jenis_tuk" value="{{ $tuk->id_jenis_tuk }}" 
                            class="form-radio h-4 w-4 text-blue-600"
                            {{ $loop->first ? 'checked' : '' }}> {{-- Auto-check yang pertama --}}
                        <label for="tuk_{{ $tuk->id_jenis_tuk }}" class="text-sm text-gray-700">{{ $tuk->jenis_tuk }}</label>
                    </div>
                    @empty
                        <p class="text-red-500 text-xs ml-2">Data Jenis TUK tidak ditemukan!</p>
                    @endforelse
                </div>

                <label class="text-sm font-medium text-gray-700">Nama Asesor</label>
                <div class="flex items-center">
                    <span>:</span>
                    {{-- DATA DARI $asesor --}}
                    <input type="text" name="nama_asesor" value="{{ $asesor->nama_lengkap ?? 'N/A' }}" 
                        class="form-input w-full ml-2" readonly>
                    <input type="hidden" name="nomor_regis_asesor" value="{{ $asesor->nomor_regis ?? 'N/A' }}">
                </div>
                
                <label class="text-sm font-medium text-gray-700">Nama Asesi</label>
                <div class="flex items-center">
                    <span>:</span>
                    {{-- DATA DARI $asesi --}}
                    <input type="text" name="nama_asesi" value="{{ $asesi->nama_lengkap ?? 'N/A' }}" 
                        class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">Tanggal</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="date" name="tanggal_pelaksanaan" value="<?php echo date('Y-m-d'); ?>" 
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
                
                <div class="h-6"></div> 
                
                <div class="border border-gray-900 shadow-md">
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
                            {{-- PERTANYAAN 1 --}}
                            <tr>
                                <td rowspan="3" class="border border-gray-900 p-2 text-center text-sm font-bold align-top">1.</td>
                                <td class="border border-gray-900 px-3 py-1 text-sm font-medium">Pertanyaan:</td>
                                <td class="border border-gray-900 text-center">
                                    <input type="checkbox" name="p1_status" value="ya" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </td>
                                <td class="border border-gray-900 text-center">
                                    <input type="checkbox" name="p1_status" value="tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 border-r-0 px-3 py-1 text-xs bg-gray-50">Kunci Jawaban:</td>
                                <td colspan="2" class="border border-gray-900 text-center bg-gray-50"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 px-3 py-1 text-sm">Jawaban Asesi:
                                    <textarea name="p1_jawaban" rows="2" class="w-full border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </td>
                                <td colspan="2" class="border border-gray-900 text-center"></td>
                            </tr>

                            {{-- PERTANYAAN 2 --}}
                            <tr>
                                <td rowspan="3" class="border border-gray-900 p-2 text-center text-sm font-bold align-top">2.</td>
                                <td class="border border-gray-900 px-3 py-1 text-sm font-medium">Pertanyaan:</td>
                                <td class="border border-gray-900 text-center">
                                    <input type="checkbox" name="p2_status" value="ya" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </td>
                                <td class="border border-gray-900 text-center">
                                    <input type="checkbox" name="p2_status" value="tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 px-3 py-1 text-xs bg-gray-50">Kunci Jawaban:</td>
                                <td colspan="2" class="border border-gray-900 text-center bg-gray-50"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 px-3 py-1 text-sm">Jawaban Asesi:
                                    <textarea name="p2_jawaban" rows="2" class="w-full border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </td>
                                <td colspan="2" class="border border-gray-900 text-center"></td>
                            </tr>

                            {{-- PERTANYAAN 3 --}}
                            <tr>
                                <td rowspan="3" class="border border-gray-900 p-2 text-center text-sm font-bold align-top">3.</td>
                                <td class="border border-gray-900 px-3 py-1 text-sm font-medium">Pertanyaan:</td>
                                <td class="border border-gray-900 text-center">
                                    <input type="checkbox" name="p3_status" value="ya" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </td>
                                <td class="border border-gray-900 text-center">
                                    <input type="checkbox" name="p3_status" value="tidak" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 px-3 py-1 text-xs bg-gray-50">Kunci Jawaban:</td>
                                <td colspan="2" class="border border-gray-900 text-center bg-gray-50"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 px-3 py-1 text-sm">Jawaban Asesi:
                                    <textarea name="p3_jawaban" rows="2" class="w-full border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </td>
                                <td colspan="2" class="border border-gray-900 text-center"></td>
                            </tr>                                    
                        </tbody>
                    </table>
                </div>              
            </div>

            <div class="border border-gray-900 shadow-md w-full max-w-4xl mx-auto mb-8">
                <table class="w-full border-collapse">
                    <tbody>
                        <tr>
                            <td class="border border-gray-900 p-2 font-semibold w-40 bg-black text-white">Umpan balik untuk asesi</td>
                            <td class="border border-gray-900 p-2">
                                Aspek pengetahuan seluruh unit kompetensi yang diuji <br>
                                <strong>(tercapai / belum tercapai)*</strong><br>
                                <textarea name="umpan_balik_asesi" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2" rows="2" placeholder="Tuliskan Unit Kompetensi / Elemen / KUK jika belum tercapai: ...."></textarea>
                            </td>                        
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex w-full gap-8 max-w-4xl mx-auto mb-10 font-sans text-black">
                
                <div class="w-1/2">
                    <div class="font-bold mb-1">ASESI:</div>
                    <div class="mb-1">
                        Nama: {{ $asesi->nama_lengkap ?? '[Nama Asesi]' }}
                    </div>
                    <div class="mb-2">Tanda tangan dan Tanggal:</div>
                    <div class="border border-gray-900 h-32 w-full bg-white">
                        {{-- Placeholder Tanda Tangan Asesi --}}
                    </div>
                </div>

                <div class="w-1/2">
                    <div class="font-bold mb-1">ASESOR:</div>
                    <div class="mb-1">
                        Nama: {{ $asesor->nama_lengkap ?? '[Nama Asesor]' }}
                    </div>
                    <div class="mb-1">
                        No. Reg.MET.: {{ $asesor->nomor_regis ?? '[Nomor Registrasi]' }}
                    </div>
                    <div class="mb-2">Tanda tangan dan Tanggal:</div>
                    <div class="border border-gray-900 h-32 w-full bg-white">
                        {{-- Placeholder Tanda Tangan Asesor --}}
                    </div>
                </div>
            </div>

            <div class="flex justify-end max-w-4xl mx-auto pb-10">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                    Simpan Jawaban
                </button>
            </div>
        
        </form>

    </main>
@endsection