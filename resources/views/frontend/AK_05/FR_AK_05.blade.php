@extends('layouts.app-sidebar')

@section('content')
    {{-- Style Internal untuk konsistensi UI --}}
    <style>
        .custom-checkbox:checked {
            background-color: #2563eb; /* blue-600 */
            border-color: #2563eb;
        }
        textarea:focus, input:focus {
            outline: none;
            --tw-ring-color: #3b82f6; /* blue-500 */
        }
    </style>

    <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">

        {{-- HEADER FORM --}}
        <x-header_form.header_form title="FR.AK.05. LAPORAN ASESMEN" /><br>

        {{-- FORM WRAPPER (Statis: action="#") --}}
        <form action="#" method="POST">
            @csrf

            {{-- 1. IDENTITAS SKEMA (Menggunakan Component yang sudah ada) --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Identitas Skema</h3>
                {{-- Component ini mencakup Judul, Nomor, TUK, Nama Asesor, Tanggal sesuai --}}
                <x-identitas_skema_form.identitas_skema_form />
            </div>

            {{-- 2. TABEL PESERTA / ASESI --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Data Asesi & Rekomendasi</h3>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th rowspan="2" class="px-4 py-4 text-center text-xs font-bold uppercase w-12 border-r border-gray-700">No.</th>
                                <th rowspan="2" class="px-4 py-4 text-left text-xs font-bold uppercase min-w-[200px] border-r border-gray-700">Nama Asesi</th>
                                <th colspan="2" class="px-4 py-2 text-center text-xs font-bold uppercase border-b border-gray-700 border-r">Rekomendasi</th>
                                <th rowspan="2" class="px-4 py-4 text-left text-xs font-bold uppercase w-[35%]">Keterangan**</th>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-center text-xs font-bold uppercase w-16 bg-gray-800 border-r border-gray-700">K</th>
                                <th class="px-4 py-2 text-center text-xs font-bold uppercase w-16 bg-gray-800 border-r border-gray-700">BK</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            {{-- Loop 4 baris sesuai dokumen --}}
                            @for ($i = 1; $i <= 4; $i++)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-3 text-center font-bold text-gray-700 border-r border-gray-200">{{ $i }}.</td>
                                    
                                    {{-- Nama Asesi --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <input type="text" name="asesi[{{ $i }}][nama]"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                            placeholder="Nama Asesi...">
                                    </td>

                                    {{-- Checkbox K --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="asesi[{{ $i }}][rekomendasi]" value="K"
                                            class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer transition">
                                    </td>

                                    {{-- Checkbox BK --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="asesi[{{ $i }}][rekomendasi]" value="BK"
                                            class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer transition">
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="px-4 py-3">
                                        <input type="text" name="asesi[{{ $i }}][keterangan]"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                            placeholder="Keterangan...">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                
                {{-- Catatan Kaki sesuai --}}
                <div class="mt-3 text-xs text-gray-500 italic">
                    ** Tuliskan Kode dan Judul Unit Kompetensi yang dinyatakan BK bila mengases satu skema
                </div>
            </div>

            {{-- 3. ASPEK & CATATAN LAIN (Sesuai ) --}}
            <div class="grid grid-cols-1 gap-6 mb-6">
                
                {{-- Aspek Negatif dan Positif --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Aspek Negatif dan Positif dalam Asesmen</h3>
                    <textarea name="aspek_asesmen" rows="4"
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm resize-none placeholder-gray-400 p-3"
                        placeholder="Tuliskan aspek negatif dan positif yang ditemukan..."></textarea>
                </div>

                {{-- Pencatatan Penolakan --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Pencatatan Penolakan Hasil Asesmen</h3>
                    <textarea name="catatan_penolakan" rows="3"
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm resize-none placeholder-gray-400 p-3"
                        placeholder="Tuliskan jika ada penolakan hasil asesmen..."></textarea>
                </div>

                {{-- Saran Perbaikan --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Saran Perbaikan (Asesor/Personil Terkait)</h3>
                    <textarea name="saran_perbaikan" rows="3"
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm resize-none placeholder-gray-400 p-3"
                        placeholder="Saran perbaikan untuk proses asesmen selanjutnya..."></textarea>
                </div>
            </div>

            {{-- 4. TANDA TANGAN (Sesuai ) --}}
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-6 shadow-lg mb-8">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-6">Pengesahan Laporan</h3>
                
                {{-- Catatan Umum --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan:</label>
                    <textarea name="catatan_akhir" rows="2" class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Kotak Tanda Tangan Asesor --}}
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesor</label>
                        
                        {{-- Area TTD --}}
                        <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                <p class="text-xs text-gray-500 mt-2 font-medium group-hover:text-blue-600">Klik untuk Tanda Tangan</p>
                            </div>
                        </div>

                        {{-- Detail Asesor --}}
                        <div class="mt-4 space-y-3">
                            <div>
                                <label class="text-xs text-gray-500 font-semibold uppercase">Nama</label>
                                <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-blue-500 px-0 py-1 bg-transparent" 
                                    value="{{ Auth::user()->name ?? '' }}" placeholder="Nama Lengkap">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold uppercase">No. Reg. MET</label>
                                <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-blue-500 px-0 py-1 bg-transparent" 
                                    placeholder="Nomor Registrasi">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold uppercase">Tanggal</label>
                                <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-blue-500 px-0 py-1 bg-transparent" 
                                    value="{{ date('d-m-Y') }}">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Space kosong untuk keseimbangan grid (opsional, karena di dokumen hanya ada Asesor) --}}
                    <div class="hidden md:block"></div>
                </div>
            </div>

            {{-- FOOTER BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mt-8 border-t-2 border-gray-200 pt-6 mb-8">
                <a href="#" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 transition text-center shadow-sm">
                    Kembali
                </a>
                <button type="button" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    Simpan Laporan FR.AK.05
                </button>
            </div>

        </form>
    </div>
@endsection