@extends('layouts.app-sidebar')

@section('content')

<div class="p-8">

    {{-- ================= HEADER JUDUL ================= --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6">FR.IA.06.B. Lembar Kunci Jawaban Pertanyaan Tertulis Esai</h1>

    {{-- ================= BOX INFO HEADER (STATIS) ================= --}}
    <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <!-- Info Baris 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">Skema Sertifikasi</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" placeholder="KKNI/Okupasi/Klaster">
            </div>
            <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                <span class="font-medium text-gray-700">Judul</span><span class="font-medium">:</span>
                <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
            </div>
        </div>

        <hr class="my-4 border-gray-300">

        <!-- Info Baris 2 -->
        <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
            <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">

            <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="John Doe (ID: 1)">

            <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
            <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="{{ date('Y-m-d') }}">
        </div>
        <p class="text-xs text-gray-500 mt-4 italic">*Coret yang tidak perlu</p>
    </div>

    {{-- ================= TABEL PENILAIAN ================= --}}
    <div class="mb-8">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg border-b pb-2">Penilaian Jawaban Peserta</h3>

        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200 text-gray-700">
                    <tr>
                        <th class="p-4 text-left font-bold w-3/4 pl-5">Detail Pertanyaan & Jawaban</th>
                        <th class="p-4 text-center font-bold w-1/4 border-l border-gray-200">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">

                    @forelse ($soalItems as $index => $soal)
                        @php
                            // Ambil jawaban asesi dari relasi (asumsi hasMany -> first())
                            $jawabanAsesi = $soal->jawabanAsesi->first();
                        @endphp

                        <tr>
                            {{-- KOLOM KIRI: Soal, Jawaban Asesi, Kunci Master --}}
                            <td class="p-5 text-left hover:bg-gray-50 align-top transition duration-150">
                                <div class="flex flex-row gap-4">
                                    <span class="font-bold text-gray-700 pt-1 text-lg bg-gray-100 h-8 w-8 flex items-center justify-center rounded">{{ $index + 1 }}</span>

                                    <div class="w-full space-y-5">

                                        {{-- 1. Pertanyaan --}}
                                        <div class="text-gray-900 font-semibold text-base border-b border-gray-200 pb-2">
                                            {{ $soal->soal_ia06 }}
                                        </div>

                                        {{-- 2. Jawaban Asesi (YANG DINILAI) --}}
                                        <div class="relative group">
                                            <span class="absolute -top-3 left-0 text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wide z-10">
                                                Jawaban Asesi
                                            </span>
                                            <div class="w-full p-4 mt-2 border border-blue-200 border-l-4 border-l-blue-500 bg-blue-50/30 text-gray-800 rounded-r-md text-sm shadow-sm">
                                                @if($jawabanAsesi)
                                                    {{ $jawabanAsesi->teks_jawaban_ia06 }}
                                                @else
                                                    <span class="text-red-500 italic flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Asesi belum menjawab soal ini.
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- 3. Kunci Jawaban Master (REFERENSI) --}}
                                        <div class="relative opacity-90 hover:opacity-100 transition-opacity">
                                            <span class="absolute -top-3 left-0 text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded border border-green-200 uppercase tracking-wide z-10">
                                                Kunci Jawaban Master (Referensi)
                                            </span>
                                            <div class="w-full p-4 mt-2 border border-green-200 border-l-4 border-l-green-500 bg-green-50/30 text-gray-600 rounded-r-md text-sm italic">
                                                {{ $soal->kunci_jawaban_ia06 ?? 'Belum ada kunci jawaban master.' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>

                            {{-- KOLOM KANAN: Radio Button K/BK --}}
                            <td class="p-4 align-middle text-center border-l border-gray-200 bg-gray-50/20">
                                <div class="flex flex-col items-center justify-center gap-4 h-full">

                                    {{-- K (Kompeten) --}}
                                    <label class="inline-flex items-center cursor-pointer hover:bg-green-100 p-3 rounded-lg transition border border-transparent hover:border-green-300 w-full justify-center group shadow-sm bg-white">
                                        <input type="radio"
                                               name="nilai[{{ $soal->id_soal_ia06 }}]"
                                               value="K"
                                               class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                        <span class="ml-2 font-bold text-gray-700 group-hover:text-green-800">K</span>
                                        <span class="ml-1 text-[10px] text-green-600 font-semibold uppercase tracking-wider">(Kompeten)</span>
                                    </label>

                                    {{-- BK (Belum Kompeten) --}}
                                    <label class="inline-flex items-center cursor-pointer hover:bg-red-100 p-3 rounded-lg transition border border-transparent hover:border-red-300 w-full justify-center group shadow-sm bg-white">
                                        <input type="radio"
                                               name="nilai[{{ $soal->id_soal_ia06 }}]"
                                               value="BK"
                                               class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                        <span class="ml-2 font-bold text-gray-700 group-hover:text-red-800">BK</span>
                                        <span class="ml-1 text-[10px] text-red-600 font-semibold uppercase tracking-wider">(Belum)</span>
                                    </label>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-10 text-center text-gray-500 bg-gray-50">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p>Belum ada data soal yang tersedia.</p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= TABEL PENYUSUN & VALIDATOR ================= --}}
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg">Penyusun dan Validator</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200 text-gray-700">
                    <tr>
                        <th class="p-3 text-left font-bold w-1/6">Status</th>
                        <th class="p-3 text-left font-bold w-[5%]">No</th>
                        <th class="p-3 text-left font-bold w-1/4">Nama</th>
                        <th class="p-3 text-left font-bold w-1/4">Nomor MET</th>
                        <th class="p-3 text-left font-bold">Tanda Tangan dan Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">

                    {{-- BARIS PENYUSUN (STATIS) --}}
                    <tr>
                        <td class="p-3 font-bold align-top border-r bg-gray-50 text-gray-600" rowspan="2">PENYUSUN</td>
                        <td class="p-3 align-top pt-4 text-center">1</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                    </tr>
                    <tr>
                        <td class="p-3 align-top pt-4 text-center">2</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                    </tr>

                    {{-- BARIS VALIDATOR (DINAMIS DARI DATABASE) --}}
                    @forelse($validators as $index => $validator)
                    <tr>
                        {{-- Rowspan 'VALIDATOR' hanya di baris pertama --}}
                        @if($index === 0)
                            <td class="p-3 font-bold align-top border-r bg-gray-50 text-gray-600" rowspan="{{ $validators->count() }}">
                                VALIDATOR
                            </td>
                        @endif

                        <td class="p-3 align-top pt-4 text-center">{{ $index + 1 }}</td>

                        <td class="p-3">
                            <input type="text"
                                   class="w-full p-2 border border-gray-200 bg-gray-100 text-gray-600 rounded cursor-not-allowed"
                                   value="{{ $validator->nama_validator }}"
                                   readonly>
                        </td>

                        <td class="p-3">
                            <input type="text"
                                   class="w-full p-2 border border-gray-200 bg-gray-100 text-gray-600 rounded cursor-not-allowed"
                                   value="{{ $validator->no_MET_validator }}"
                                   readonly>
                        </td>

                        <td class="p-3">
                            <input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none" placeholder="Tanda Tangan...">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="p-3 font-bold align-top border-r bg-gray-50 text-gray-600">VALIDATOR</td>
                        <td class="p-3 text-center">1</td>
                        <td colspan="3" class="p-3 text-red-500 italic text-center bg-red-50">
                            Data Validator kosong. Silakan jalankan seeder.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= TOMBOL SIMPAN HASIL PENILAIAN ================= --}}
    <div class="mt-8 flex justify-end">
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Simpan Hasil Penilaian
        </button>
    </div>

</div>
@endsection