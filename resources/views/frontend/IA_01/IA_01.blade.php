{{-- File: resources/views/pages/ia-01/form_unit.blade.php --}}

@extends('layouts.wizard', ['currentStep' => $unitKompetensi->urutan])

@section('title', 'IA.01 - Unit: ' . $unitKompetensi->kode_unit)

@section('wizard-content')

    {{-- Tampilkan Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
            <p class="font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Ada isian yang terlewat:
            </p>
            <ul class="list-disc list-inside mt-2 ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ia01.storeStep', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan, 'urutan' => $unitKompetensi->urutan]) }}" method="POST">
        @csrf

        {{-- HEADER UNIT KOMPETENSI --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Ceklis Observasi Aktivitas (Lanjutan)
                </h1>
                <div class="mt-2 text-gray-700 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-bold bg-gray-200 px-2 py-1 rounded text-gray-800">Unit ke-{{ $unitKompetensi->urutan }}</span>
                        <span class="font-semibold">{{ $unitKompetensi->kode_unit }}</span>
                    </div>
                    <div class="mt-1 text-lg font-medium text-blue-600">
                        {{ $unitKompetensi->judul_unit }}
                    </div>
                </div>
            </div>
            {{-- Logo BNSP opsional di sini, biar gak penuh --}}
        </div>

        {{-- TABEL CEKLIS OBSERVASI --}}
        <div class="border-2 border-gray-800 rounded-sm overflow-hidden mb-8">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-900 border-b-2 border-gray-800">
                    <tr>
                        <th class="p-3 text-sm font-bold border-r border-gray-800 w-1/4 text-center">Elemen</th>
                        <th class="p-3 text-sm font-bold border-r border-gray-800 w-1/3 text-center">Kriteria Unjuk Kerja</th>
                        <th class="p-3 text-sm font-bold border-r border-gray-800 w-1/6 text-center">
                            Standar Industri
                        </th>
                        {{-- Header Pencapaian digabung --}}
                        <th class="p-0 border-r border-gray-800 w-1/6">
                            <div class="border-b border-gray-800 p-1 text-center text-sm font-bold bg-gray-200">Pencapaian</div>
                            <div class="flex">
                                <div class="w-1/2 text-center p-1 border-r border-gray-800 text-xs font-bold bg-green-50">Ya</div>
                                <div class="w-1/2 text-center p-1 text-xs font-bold bg-red-50">Tidak</div>
                            </div>
                        </th>
                        <th class="p-3 text-sm font-bold w-1/6 text-center">Penilaian Lanjut</th>
                    </tr>
                </thead>

                <tbody class="text-gray-800 bg-white">
                    {{--
                        LOGIC LOOPING:
                        1. Loop Elemen
                        2. Di dalam Elemen, Loop KUK
                        3. Pakai rowspan di iterasi KUK pertama
                    --}}

                    @forelse ($unitKompetensi->elemens as $elemen)
                        @php
                            // Hitung jumlah KUK per elemen buat Rowspan
                            $totalKuk = $elemen->kriteriaUnjukKerja->count();
                        @endphp

                        @foreach ($elemen->kriteriaUnjukKerja as $index => $kuk)
                            <tr class="border-b border-gray-300 last:border-b-0 hover:bg-gray-50 transition-colors">

                                {{-- KOLOM 1: ELEMEN (Pakai Rowspan) --}}
                                @if ($index === 0)
                                    <td rowspan="{{ $totalKuk }}" class="p-3 border-r border-gray-800 align-top bg-white">
                                        <div class="text-sm font-semibold">
                                            {{-- Nomor Elemen (misal: 1.) --}}
                                            <span class="font-bold mr-1">{{ $loop->parent->iteration }}.</span>
                                            {{ $elemen->elemen }}
                                        </div>
                                    </td>
                                @endif

                                {{-- KOLOM 2: KUK --}}
                                <td class="p-3 border-r border-gray-800 align-top text-sm leading-relaxed">
                                    {{-- Nomor KUK (misal: 1.1) --}}
                                    <span class="font-bold mr-1">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                                    {{ $kuk->kriteria }}
                                </td>

                                {{-- KOLOM 3: STANDAR INDUSTRI --}}
                                <td class="p-2 border-r border-gray-800 align-top">
                                    {{-- Bisa input manual atau text statis --}}
                                    <textarea name="standar_industri[{{ $kuk->id }}]" rows="3"
                                        class="w-full text-xs border-gray-300 rounded focus:border-blue-500 focus:ring-0 bg-gray-50 resize-none"
                                        placeholder="Isi jika ada...">{{ old("standar_industri.{$kuk->id}") }}</textarea>
                                </td>

                                {{-- KOLOM 4 & 5: CHECKBOX YA/TIDAK --}}
                                <td class="p-0 border-r border-gray-800 align-top">
                                    <div class="flex h-full">
                                        {{-- Opsi YA --}}
                                        <label class="w-1/2 border-r border-gray-300 cursor-pointer hover:bg-green-100 flex items-center justify-center py-4 transition">
                                            <input type="radio" name="hasil[{{ $kuk->id }}]" value="kompeten"
                                                class="w-5 h-5 text-green-600 focus:ring-green-500 border-gray-400"
                                                {{ old("hasil.{$kuk->id}") == 'kompeten' ? 'checked' : '' }} required>
                                        </label>

                                        {{-- Opsi TIDAK --}}
                                        <label class="w-1/2 cursor-pointer hover:bg-red-100 flex items-center justify-center py-4 transition">
                                            <input type="radio" name="hasil[{{ $kuk->id }}]" value="belum_kompeten"
                                                class="w-5 h-5 text-red-600 focus:ring-red-500 border-gray-400"
                                                {{ old("hasil.{$kuk->id}") == 'belum_kompeten' ? 'checked' : '' }}>
                                        </label>
                                    </div>
                                </td>

                                {{-- KOLOM 6: PENILAIAN LANJUT --}}
                                <td class="p-2 align-top">
                                    <textarea name="penilaian_lanjut[{{ $kuk->id }}]" rows="3"
                                        class="w-full text-xs rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400"
                                        placeholder="Catatan...">{{ old("penilaian_lanjut.{$kuk->id}") }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data KUK untuk unit ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER NAVIGASI --}}
        <div class="flex justify-between items-center mt-10 pb-10">
            {{-- Tombol Back --}}
            @if ($unitKompetensi->urutan == 1)
                <a href="{{ route('ia01.cover', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan]) }}"
                   class="flex items-center px-6 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-full shadow-sm transition">
                    ← Data Diri
                </a>
            @else
                <a href="{{ route('ia01.showStep', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan, 'urutan' => $unitKompetensi->urutan - 1]) }}"
                   class="flex items-center px-6 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-full shadow-sm transition">
                    ← Unit Sebelumnya
                </a>
            @endif

            {{-- Tombol Next/Finish --}}
            @if ($unitKompetensi->urutan == $totalSteps)
                <button type="submit" class="flex items-center px-8 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                    Simpan & Selesai ✓
                </button>
            @else
                <button type="submit" class="flex items-center px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                    Unit Selanjutnya →
                </button>
            @endif
        </div>
    </form>
@endsection
