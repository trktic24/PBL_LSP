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

<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- TOMBOL SYNC --}}
    @if(in_array(Auth::user()->role_id, [1, 3, 4]) && !isset($isMasterView))
        <div class="mb-4 flex justify-end">
            <a href="{{ route('ia06.reset', $sertifikasi->id_data_sertifikasi_asesi) }}" 
               onclick="return confirm('Apakah Anda yakin ingin menyinkronkan ulang pertanyaan dari templat? Semua jawaban dan penilaian yang ada akan dihapus.')"
               class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold py-2 px-4 rounded shadow-sm transition flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Sinkronisasi dari Templat
            </a>
        </div>
    @endif

    {{-- FORM WRAPPER --}}
    @if($role != 1)
    <form action="{{ $formAction }}" method="POST">
        @csrf @method('PUT')
    @endif

    <div class="space-y-6">
        {{-- LOOP SOAL (Gunakan @forelse untuk handle data kosong) --}}
        @forelse($daftar_soal as $index => $item)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

            {{-- BAGIAN 1: SOAL --}}
            <div class="bg-gray-50 px-6 py-4 border-b">
                <span class="text-xs font-bold text-gray-500 uppercase">Soal No. {{ $index + 1 }}</span>
                <p class="mt-1 text-gray-900 text-lg font-medium">{{ $item->soal->soal_ia06 }}</p>
            </div>

            <div class="p-6 grid grid-cols-1 {{ $role != 2 ? 'md:grid-cols-2' : '' }} gap-8">

                {{-- BAGIAN 2: JAWABAN ASESI --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jawaban Asesi:</label>

                    @if($role == 2)
                        {{-- ASESI: Input Textarea --}}
                        <textarea name="jawaban[{{ $item->id_jawaban_ia06 }}]" rows="6"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"
                            placeholder="Ketik jawaban Anda di sini...">{{ $item->jawaban_asesi }}</textarea>
                    @else
                        {{-- ASESOR & ADMIN: Read Only --}}
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-gray-800 min-h-[100px] whitespace-pre-line">
                            {{ $item->jawaban_asesi ?? '(Belum dijawab)' }}
                        </div>
                    @endif
                </div>

                {{-- BAGIAN 3: AREA PENILAIAN (Khusus Asesor & Admin) --}}
                @if($role != 2)
                <div class="border-t md:border-t-0 md:border-l border-gray-200 pt-6 md:pt-0 md:pl-8">

                    {{-- Kunci Jawaban --}}
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

        @empty
            {{-- ========== TAMPILAN JIKA SOAL KOSONG (MODIFIKASI DI SINI) ========== --}}
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="bg-gray-100 p-3 rounded-full mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Soal Belum tersedia</h3>
                <p class="text-gray-500 text-sm mt-1 max-w-sm">
                    Saat ini belum ada pertanyaan esai yang tersedia untuk skema sertifikasi ini.
                </p>
            </div>
        @endforelse
    </div>

    {{-- BAGIAN 4: UMPAN BALIK (Hanya muncul jika ada soal) --}}
    @if(($role != 2 || ($role == 2 && $umpanBalik)) && $daftar_soal->isNotEmpty())
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
        {{-- Hanya tampilkan tombol jika ada soal --}}
        @if($daftar_soal->isNotEmpty())

            @php
                // Cek apakah Asesi sudah mengisi minimal satu jawaban
                $isButtonEnabled = ($role == 2) || $daftar_soal->contains(fn($i) => !empty($i->jawaban_asesi));
            @endphp

            <div class="mt-12 flex justify-end pb-10 border-t border-gray-200 pt-6">

                <div class="mr-auto text-sm text-gray-500 italic hidden md:block">
                    * Pastikan semua jawaban sudah terisi sebelum menyimpan.
                </div>

                @if($isButtonEnabled)
                    <button type="submit" class="px-8 py-3 rounded-lg font-bold text-white shadow-lg transition transform hover:scale-105 active:scale-95
                        {{ $role == 2 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-purple-700' }}">
                        {{ $role == 2 ? 'Simpan Jawaban Saya' : 'Simpan Penilaian' }}
                    </button>
                @else
                    {{-- TOMBOL DISABLE (Jika Asesi Belum Jawab) --}}
                    <button type="button" disabled class="px-8 py-3 rounded-lg font-bold text-white shadow bg-gray-400 cursor-not-allowed" title="Menunggu Asesi mengisi jawaban">
                        Asesi Belum Menjawab
                    </button>
                @endif
            </div>

        @endif
    </form>
    @endif

</div>
@endsection