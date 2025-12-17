{{-- 
    Nama File: resources/views/asesmen/fr-ia-04b.blade.php 
    Deskripsi: Form FR.IA.04B menggunakan layout Wizard
--}}

@extends('layouts.wizard')

@section('wizard-content')

    {{-- Style khusus untuk tabel border hitam --}}
    <style>
        .form-table, .form-table td, .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>

    {{-- Header Mobile (Tombol Sidebar) --}}
    <div class="lg:hidden p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 mb-6 rounded-lg">
        <span class="font-bold">Form Assessment</span>
        <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none hover:bg-blue-700 rounded-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- FORM START --}}
    {{-- Pastikan route 'fr_ia_04b.store' sudah dibuat di web.php --}}
    <form action="{{ route('fr_ia_04b.store') }}" method="POST">
        @csrf
        
        <div class="bg-white">
            
            {{-- KOP SURAT --}}
            <div class="mb-10 text-center">
                <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div> 
                <h1 class="text-xl lg:text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-2 inline-block">
                    FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA
                </h1>
            </div>

            {{-- INFO SKEMA & KEGIATAN --}}
            <div class="grid grid-cols-1 lg:grid-cols-[250px_auto] gap-y-3 text-sm mb-10 text-gray-700">
                
                <div class="font-bold text-black">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</div>
                <div>
                    <div class="flex flex-col sm:flex-row gap-2"><span class="font-semibold w-20">Judul</span> : Junior Web Programmer</div>
                    <div class="flex flex-col sm:flex-row gap-2"><span class="font-semibold w-20">Nomor</span> : -</div>
                </div>

                <div class="font-bold text-black">TUK</div>
                <div>: Sewaktu / Tempat Kerja / <span class="font-bold">Mandiri</span>*</div>

                <div class="font-bold text-black">Judul Kegiatan Terstruktur</div>
                <div>: 
                    <input type="text" 
                        name="judul_kegiatan"
                        value="{{ old('judul_kegiatan', $data->judul_kegiatan ?? '') }}"
                        class="border-b border-gray-300 focus:outline-none w-full sm:w-1/2"
                        placeholder="Isi judul kegiatan...">
                </div>

                <div class="font-bold text-black">Nama Asesor</div>
                <div>: -</div>

                <div class="font-bold text-black">Nama Asesi</div>
                <div>: Tatang Sidartang</div>

                <div class="font-bold text-black">Tanggal</div>
                <div>: {{ date('d F Y') }}</div>
            </div>

            {{-- PANDUAN ASESOR --}}
            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-8 text-sm text-gray-700 rounded-r-lg">
                <h3 class="font-bold text-orange-900 mb-2">PANDUAN BAGI ASESOR:</h3>
                <ul class="list-disc pl-5 space-y-1 text-xs leading-relaxed">
                    <li>Lakukan penilaian pencapaian hasil proyek singkat atau kegiatan terstruktur lainnya melalui presentasi.</li>
                    <li>Penilaian dilakukan sesuai dengan <b>FR.IA.04A. DIT</b>.</li>
                    <li>Pertanyaan disampaikan oleh asesor setelah asesi melakukan presentasi.</li>
                    <li>Isilah kolom lingkup penyajian proyek atau kegiatan terstruktur lainnya.</li>
                    <li>Berikan keputusan pencapaian berdasarkan kesimpulan jawaban asesi.</li>
                </ul>
            </div>

            {{-- TABEL PENILAIAN --}}
            <div class="mb-8 overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                <table class="w-full text-sm form-table min-w-[900px]">
                    <thead class="bg-gray-100 text-center font-bold text-gray-700">
                        <tr>
                            <th rowspan="2" class="p-2 w-10">No</th>
                            <th colspan="3" class="p-2">Aspek Penilaian</th>
                            <th colspan="2" class="p-2 w-24">Pencapaian</th>
                        </tr>
                        <tr>
                            <th class="p-2 w-1/4">Lingkup Penyajian Proyek / Kegiatan Terstruktur</th>
                            <th class="p-2 w-1/3">Daftar Pertanyaan</th>
                            <th class="p-2 w-1/4">Kesesuaian dengan Standar Kompetensi</th>
                            <th class="p-2 w-10">Ya</th>
                            <th class="p-2 w-10">Tdk</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        {{-- ROW 1 --}}
                        <tr>
                            <td class="p-2 text-center align-top font-bold">1.</td>
                            <td class="p-2 align-top">
                                <textarea name="lingkup_penyajian_1" class="w-full h-32 border border-gray-300 p-2 rounded resize-none focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Lingkup penyajian...">{{ old('lingkup_penyajian_1', $data->lingkup_penyajian_1 ?? '') }}</textarea>
                            </td>
                            <td class="p-2 align-top">
                                <div class="mb-2">
                                    <span class="font-bold block text-xs mb-1 text-gray-500">Pertanyaan:</span>
                                    <textarea name="pertanyaan_1" class="w-full h-20 border border-gray-300 p-2 rounded resize-none focus:ring-1 focus:ring-blue-500 outline-none">{{ old('pertanyaan_1', $data->pertanyaan_1 ?? '') }}</textarea>
                                </div>
                                <div>
                                    <span class="font-bold block text-xs mb-1 text-gray-500">Tanggapan:</span>
                                    <textarea name="tanggapan_1" class="w-full h-20 border border-gray-300 p-2 rounded resize-none bg-gray-50 outline-none">{{ old('tanggapan_1', $data->tanggapan_1 ?? '') }}</textarea>
                                </div>
                            </td>
                            <td class="p-2 align-top">
                                <textarea name="kuk_elemen_1" class="w-full h-full min-h-[150px] border border-gray-300 p-2 rounded resize-none outline-none" placeholder="KUK / Elemen...">{{ old('kuk_elemen_1', $data->kuk_elemen_1 ?? '') }}</textarea>
                            </td>
                            <td class="p-2 text-center align-top pt-4">
                                <input type="checkbox" name="is_kompeten_1" value="1" class="w-5 h-5 text-blue-600" {{ ($data->is_kompeten_1 ?? 0) == 1 ? 'checked' : '' }}>
                            </td>
                            <td class="p-2 text-center align-top pt-4">
                                <input type="checkbox" name="is_kompeten_1" value="0" class="w-5 h-5 text-red-600" {{ ($data->is_kompeten_1 ?? 0) == 0 ? 'checked' : '' }}>
                            </td>
                        </tr>
                        {{-- ROW 2 --}}
                        <tr>
                            <td class="p-2 text-center align-top font-bold">2.</td>
                            <td class="p-2 align-top">
                                <textarea name="lingkup_penyajian_2" class="w-full h-32 border border-gray-300 p-2 rounded resize-none focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Lingkup penyajian...">{{ old('lingkup_penyajian_2', $data->lingkup_penyajian_2 ?? '') }}</textarea>
                            </td>
                            <td class="p-2 align-top">
                                <div class="mb-2">
                                    <span class="font-bold block text-xs mb-1 text-gray-500">Pertanyaan:</span>
                                    <textarea name="pertanyaan_2" class="w-full h-20 border border-gray-300 p-2 rounded resize-none focus:ring-1 focus:ring-blue-500 outline-none">{{ old('pertanyaan_2', $data->pertanyaan_2 ?? '') }}</textarea>
                                </div>
                                <div>
                                    <span class="font-bold block text-xs mb-1 text-gray-500">Tanggapan:</span>
                                    <textarea name="tanggapan_2" class="w-full h-20 border border-gray-300 p-2 rounded resize-none bg-gray-50 outline-none">{{ old('tanggapan_2', $data->tanggapan_2 ?? '') }}</textarea>
                                </div>
                            </td>
                            <td class="p-2 align-top">
                                <textarea name="kuk_elemen_2" class="w-full h-full min-h-[150px] border border-gray-300 p-2 rounded resize-none outline-none" placeholder="KUK / Elemen...">{{ old('kuk_elemen_2', $data->kuk_elemen_2 ?? '') }}</textarea>
                            </td>
                            <td class="p-2 text-center align-top pt-4">
                                <input type="checkbox" name="is_kompeten_2" value="1" class="w-5 h-5 text-blue-600" {{ ($data->is_kompeten_2 ?? 0) == 1 ? 'checked' : '' }}>
                            </td>
                            <td class="p-2 text-center align-top pt-4">
                                <input type="checkbox" name="is_kompeten_2" value="0" class="w-5 h-5 text-red-600" {{ ($data->is_kompeten_2 ?? 0) == 0 ? 'checked' : '' }}>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- REKOMENDASI ASESOR --}}
            <div class="border border-black mb-8 p-0 bg-white shadow-sm">
                <div class="flex flex-col sm:flex-row border-b border-black bg-gray-50">
                    <div class="w-full sm:w-1/4 p-3 font-bold border-r-0 sm:border-r border-b sm:border-b-0 border-black">Rekomendasi Asesor:</div>
                    <div class="w-full sm:w-3/4 p-3">
                        <p class="mb-2 text-sm">Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan:</p>
                        <div class="flex gap-8 font-bold">
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                <input type="checkbox" name="rekomendasi" value="kompeten" class="w-5 h-5 text-blue-600" {{ ($data->rekomendasi ?? '') == 'kompeten' ? 'checked' : '' }}> Kompeten
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                <input type="checkbox" name="rekomendasi" value="belum_kompeten" class="w-5 h-5 text-red-600" {{ ($data->rekomendasi ?? '') == 'belum_kompeten' ? 'checked' : '' }}> Belum Kompeten
                            </label>
                        </div>
                    </div>
                </div>

                {{-- TANDA TANGAN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-black">
                    {{-- Asesi --}}
                    <div class="p-4">
                        <div class="font-bold mb-4 underline">Asesi:</div>
                        <div class="flex mb-2 items-center">
                            <span class="w-32 text-sm font-semibold">Nama</span>
                            <span>: Tatang Sidartang</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-semibold">Tanda Tangan/ <br>Tanggal</span>
                            <div class="flex-1">
                                <span class="mr-2">:</span>
                                <div class="h-20 border-b border-gray-400 border-dashed inline-block w-3/4"></div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Asesor --}}
                    <div class="p-4">
                        <div class="font-bold mb-4 underline">Asesor:</div>
                        <div class="flex mb-2 items-center">
                            <span class="w-32 text-sm font-semibold">Nama</span>
                            <span class="flex-1 flex gap-1 items-center">: 
                                <input type="text" name="nama_asesor" value="{{ old('nama_asesor', $data->nama_asesor ?? '') }}" class="border-b border-gray-400 outline-none w-full" placeholder="...">
                            </span>
                        </div>
                        <div class="flex mb-2 items-center">
                            <span class="w-32 text-sm font-semibold">No. Reg</span>
                            <span class="flex-1 flex gap-1 items-center">: 
                                <input type="text" name="no_reg_asesor" value="{{ old('no_reg_asesor', $data->no_reg_asesor ?? '') }}" class="border-b border-gray-400 outline-none w-full" placeholder="...">
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-semibold">Tanda Tangan/ <br>Tanggal</span>
                            <div class="flex-1">
                                <span class="mr-2">:</span>
                                <div class="h-20 border-b border-gray-400 border-dashed inline-block w-3/4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- TOMBOL SUBMIT --}}
            <div class="mt-12 flex justify-end gap-4 sticky bottom-0 bg-white p-4 border-t shadow-lg sm:static sm:bg-transparent sm:border-0 sm:shadow-none z-10">
                <button type="submit" name="status" value="draft" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg shadow hover:bg-gray-300 transition w-full sm:w-auto">Simpan Draft</button>
                <button type="submit" name="status" value="permanen" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition w-full sm:w-auto">Simpan Permanen</button>
            </div>

        </div>
    </form>
@endsection