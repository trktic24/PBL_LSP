@extends('layouts.app-sidebar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded shadow-sm">
            <strong class="font-bold">Berhasil!</strong>
            <span class="ml-2">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-6 rounded shadow-sm">
            <strong class="font-bold">Error!</strong>
            <span class="ml-2">{{ session('error') }}</span>
        </div>
    @endif

    @if($sertifikasi)
    <form action="{{ route('ia02.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf

        {{-- HEADER --}}
        <x-header_form.header_form title="FR.IA.02 - TUGAS PRAKTIK DEMONSTRASI" />

        {{-- IDENTITAS SKEMA --}}
        <div class="mb-8">
            <x-identitas_skema_form.identitas_skema_form :sertifikasi="$sertifikasi" />
        </div>

        {{-- PETUNJUK --}}
        <div class="bg-blue-50 border border-blue-300 p-6 mb-10 rounded shadow-sm">
            <h2 class="text-lg font-bold text-blue-800 mb-3">Petunjuk</h2>
            <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
                <li>Baca instruksi kerja sebelum mulai.</li>
                <li>Klarifikasi pada asesor jika ada yang kurang jelas.</li>
                <li>Ikuti prosedur sesuai urutan.</li>
                <li>Kerjakan sesuai SOP/WI yang dipersyaratkan (jika ada).</li>
            </ul>
        </div>

        {{-- SKENARIO + TABEL UNIT --}}
        <div class="mb-10">

            <h2 class="text-xl font-semibold text-gray-800 mb-4">Skenario Tugas Praktik Demonstrasi</h2>

            {{-- TABEL UNIT KOMPETENSI STYLE IA-01 --}}
            <div class="border-2 border-gray-800 rounded-sm overflow-x-auto mb-8 shadow-sm">

                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead class="bg-gray-100 border-b-2 border-gray-800">
                        <tr>
                            <th class="p-3 text-sm font-bold text-center border-r border-gray-800 w-1/12">No.</th>
                            <th class="p-3 text-sm font-bold text-center border-r border-gray-800 w-1/4">Kode Unit</th>
                            <th class="p-3 text-sm font-bold text-center">Judul Unit</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white text-gray-800">
                        @forelse($sertifikasi->skema->unitKompetensis ?? [] as $unit)
                            <tr class="border-b border-gray-300 hover:bg-gray-50 transition">
                                <td class="p-3 text-center border-r border-gray-200 font-semibold">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="p-3 font-mono border-r border-gray-200 text-center">
                                    {{ $unit->kode_unit }}
                                </td>
                                <td class="p-3">
                                    {{ $unit->judul_unit }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500 italic">Tidak ada unit kompetensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            {{-- INPUT SKENARIO --}}
            <div class="mb-6">
                <label class="font-bold text-sm text-gray-700">Instruksi / Skenario *</label>

                @if($isAdmin)
                    <textarea name="skenario" rows="6"
                        class="mt-2 w-full border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        {{ old('skenario', $ia02->skenario ?? '') }}
                    </textarea>
                @else
                    <div class="mt-2 bg-gray-50 border border-gray-300 rounded p-4 whitespace-pre-line min-h-[100px]">
                        {!! nl2br(e($ia02->skenario ?? '-')) !!}
                    </div>
                @endif
            </div>

            {{-- PERALATAN --}}
            <div class="mb-6">
                <label class="font-bold text-sm text-gray-700">Perlengkapan & Peralatan *</label>

                @if($isAdmin)
                    <textarea name="peralatan" rows="3"
                        class="mt-2 w-full border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        {{ old('peralatan', $ia02->peralatan ?? '') }}
                    </textarea>
                @else
                    <div class="mt-2 bg-gray-50 border border-gray-300 rounded p-4 whitespace-pre-line">
                        {!! nl2br(e($ia02->peralatan ?? '-')) !!}
                    </div>
                @endif
            </div>

            {{-- WAKTU --}}
            <div class="mb-6">
                <label class="font-bold text-sm text-gray-700">Waktu Pengerjaan *</label>

                @if($isAdmin)
                    <input type="time" name="waktu"
                        value="{{ old('waktu', isset($ia02->waktu) ? \Carbon\Carbon::parse($ia02->waktu)->format('H:i') : '') }}"
                        class="mt-2 border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 w-1/3">
                @else
                    <div class="mt-2 bg-gray-50 border border-gray-300 rounded p-3 w-1/3">
                        {{ $ia02->waktu ?? '-' }}
                    </div>
                @endif
            </div>

        </div>

        {{-- TANDA TANGAN --}}
        <div class="mb-10">
            <x-kolom_ttd.asesiasesor 
                :sertifikasi="$sertifikasi" 
                :tanggal="\Carbon\Carbon::now()" 
            />
        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-4 pb-10">

            <a href="{{ route('daftar_asesi') }}"
                class="px-6 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-full shadow-sm transition">
                Kembali
            </a>

            @if($isAdmin)
            <button type="submit"
                class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                Simpan Instruksi ✓
            </button>
            @endif

        </div>

    </form>

    @else
        <div class="bg-red-100 border border-red-400 text-red-700 p-6 text-center rounded-lg shadow-sm">
            <h2 class="text-xl font-bold mb-2">Data Tidak Ditemukan</h2>
            <a href="{{ route('daftar_asesi') }}" class="text-blue-600 font-bold">Kembali</a>
        </div>
    @endif

</div>
@endsection
