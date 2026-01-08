@extends('layouts.app-sidebar')
@section('content')


<x-header_form.header_form title="FR.IA.06. PERTANYAAN ESAI" />

<div class="max-w-5xl mx-auto px-4">
    <div class="flex justify-between items-center text-sm text-gray-700 mt-2">

        <div class="opacity-90">
            {{ $sertifikasi->asesi->nama_lengkap }} — {{ $sertifikasi->jadwal->skema->nama_skema }}
        </div>

        <div class="px-3 py-1 bg-white/20 rounded font-semibold">
            Mode: {{ $role == 2 ? 'ASESI (Ujian)' : ($role == 3 ? 'ASESOR (Penilaian)' : 'ADMIN (Monitor)') }}
        </div>

    </div>
</div>
<!--
    <div class="max-w-5xl mx-auto flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold">FR.IA.06 Pertanyaan Esai</h1>
            <p class="text-sm opacity-90">{{ $sertifikasi->asesi->nama_lengkap }} - {{ $sertifikasi->jadwal->skema->nama_skema }}</p>
        </div>
        <div class="px-3 py-1 bg-white/20 rounded text-sm font-semibold">
            Mode: {{ $role == 2 ? 'ASESI (Ujian)' : ($role == 3 ? 'ASESOR (Penilaian)' : 'ADMIN (Monitor)') }}
        </div>
    </div>
-->

<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- FORM WRAPPER (Admin tidak butuh form karena cuma lihat) --}}
    @if($role != 1)
    <form action="{{ $formAction }}" method="POST">
        @csrf @method('PUT')
    @endif

    <div class="space-y-6">
        @foreach($daftar_soal as $index => $item)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

            {{-- BAGIAN 1: SOAL (Semua Role Lihat) --}}
            <div class="bg-gray-50 px-6 py-4 border-b">
                <span class="text-xs font-bold text-gray-500 uppercase">Soal No. {{ $index + 1 }}</span>
                <p class="mt-1 text-gray-900 text-lg font-medium">{{ $item->soal->soal_ia06 }}</p>
            </div>

            <div class="p-6 grid grid-cols-1 {{ $role != 2 ? 'md:grid-cols-2' : '' }} gap-8">

                {{-- BAGIAN 2: JAWABAN ASESI --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jawaban Asesi:</label>

                    @if($role == 2)
                        {{-- ASESI: Input Textarea (Bisa Ngetik) --}}
                        <textarea name="jawaban[{{ $item->id_jawaban_ia06 }}]" rows="6"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"
                            placeholder="Ketik jawaban Anda di sini...">{{ $item->jawaban_asesi }}</textarea>
                    @else
                        {{-- ASESOR & ADMIN: Read Only (Teks Biasa) --}}
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-gray-800 min-h-[100px] whitespace-pre-line">
                            {{ $item->jawaban_asesi ?? '(Belum dijawab)' }}
                        </div>
                    @endif
                </div>

                {{-- BAGIAN 3: AREA PENILAIAN (Khusus Asesor & Admin) --}}
                @if($role != 2)
                <div class="border-t md:border-t-0 md:border-l border-gray-200 pt-6 md:pt-0 md:pl-8">

                    {{-- Kunci Jawaban (Toggle) --}}
                    <details class="mb-6 group">
                        <summary class="text-sm font-semibold text-indigo-600 cursor-pointer list-none flex items-center">
                            <span>Lihat Kunci Jawaban</span>
                            <span class="ml-2 transition group-open:rotate-180">▼</span>
                        </summary>
                        <div class="mt-2 text-sm text-gray-600 bg-gray-100 p-3 rounded">
                            {{ $item->soal->kunci_jawaban_ia06 }}
                        </div>
                    </details>

                    {{-- Input Nilai --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keputusan:</label>

                        @if($role == 3)
                            {{-- ASESOR: Radio Button (Bisa Klik) --}}
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="penilaian[{{ $item->id_jawaban_ia06 }}]" value="1"
                                        class="peer sr-only" {{ $item->pencapaian === 1 ? 'checked' : '' }} required>
                                    <div class="p-2 text-center rounded border border-gray-300 peer-checked:bg-green-600 peer-checked:text-white transition">
                                        Kompeten (K)
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="penilaian[{{ $item->id_jawaban_ia06 }}]" value="0"
                                        class="peer sr-only" {{ $item->pencapaian === 0 ? 'checked' : '' }}>
                                    <div class="p-2 text-center rounded border border-gray-300 peer-checked:bg-red-600 peer-checked:text-white transition">
                                        Belum (BK)
                                    </div>
                                </label>
                            </div>
                        @else
                            {{-- ADMIN: Read Only Label --}}
                            @if($item->pencapaian === 1)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded font-bold">Kompeten</span>
                            @elseif($item->pencapaian === 0)
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded font-bold">Belum Kompeten</span>
                            @else
                                <span class="text-gray-400 italic">Belum dinilai</span>
                            @endif
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>

    {{-- BAGIAN 4: UMPAN BALIK --}}
    @if($role != 2 || ($role == 2 && $umpanBalik))
    <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border-t-4 border-purple-500">
        <h3 class="font-bold text-gray-900 mb-2">Umpan Balik Asesor</h3>

        @if($role == 3)
            <textarea name="umpan_balik" rows="3" class="w-full border-gray-300 rounded p-2" required placeholder="Catatan untuk asesi...">{{ $umpanBalik->umpan_balik ?? '' }}</textarea>
        @else
            <p class="text-gray-700 bg-purple-50 p-4 rounded">{{ $umpanBalik->umpan_balik ?? 'Belum ada umpan balik.' }}</p>
        @endif
    </div>
    @endif

    {{-- TOMBOL SUBMIT STICKY BOTTOM --}}
    @if($role != 1)
            <div class="mt-12 flex justify-end pb-10 border-t border-gray-200 pt-6">

                {{-- Info Text Opsional --}}
                <div class="mr-auto text-sm text-gray-500 italic hidden md:block">
                    * Pastikan semua jawaban sudah terisi sebelum menyimpan.
                </div>

                <button type="submit" class="px-8 py-3 rounded-lg font-bold text-white shadow-lg transition transform hover:scale-105 active:scale-95
                    {{ $role == 2 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-purple-700' }}">
                    {{ $role == 2 ? 'Simpan Jawaban Saya' : 'Simpan Penilaian' }}
                </button>
            </div>
    </form>
    @endif

</div>
@endsection
