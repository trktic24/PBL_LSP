@extends('layouts.app-sidebar-asesi')

@section('content')
<main class="main-content">    

    @php 
        // Cek apakah user adalah Admin
        $is_admin = ($user->role_id == 1); 
    @endphp

    <x-header_form.header_form title="FR.IA.05.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA" />
    <br>
    
{{-- === DROPDOWN NAVIGASI (Form B) === --}}
    @if($user->role_id != 2)
    
    {{-- Ambil ID Asesi dari URL (?ref=1) --}}
    @php $ref_id = request('ref'); @endphp

    <div class="flex justify-end mt-6 mb-2 relative">
        <button type="button" onclick="toggleNavDropdown()" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 flex items-center gap-2 text-sm font-bold transition duration-150 ease-in-out">
            <span>Navigasi</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="nav-dropdown" class="hidden absolute right-0 top-full mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-xl z-50 overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                Pindah Halaman
            </div>
            
            @if($ref_id)
                {{-- Jika ada ID Referensi, Tampilkan Link Navigasi --}}
                <a href="{{ route('FR_IA_05_A', $ref_id) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 border-b border-gray-100 transition">
                    Soal
                </a>
                <a href="{{ route('FR_IA_05_C', $ref_id) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                    Penilaian
                </a>
            @else
                {{-- Jika Form B dibuka langsung tanpa link dari A/C --}}
                <span class="block px-4 py-3 text-sm text-gray-400 italic border-b border-gray-100">
                    (Buka dari Form A/C untuk navigasi)
                </span>
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                    🏠 Ke Dashboard
                </a>
            @endif
        </div>
    </div>
    {{-- Script Toggle --}}
    <script>
        function toggleNavDropdown() { document.getElementById('nav-dropdown').classList.toggle('hidden'); }
        window.onclick = function(event) {
            if (!event.target.closest('button')) {
                const dropdown = document.getElementById('nav-dropdown');
                if (!dropdown.classList.contains('hidden')) dropdown.classList.add('hidden');
            }
        }
    </script>
    @endif
    {{-- === AKHIR DROPDOWN === --}}

    {{-- BAGIAN IDENTITAS SKEMA --}}
    <x-identitas_skema_form.identitas_skema_form
        :sertifikasi="$asesi"
    />

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form hanya submit ke route store jika Admin, jika tidak biarkan # --}}
    <form class="form-body mt-10" method="POST" action="{{ $is_admin ? route('ia-05.store.kunci') : '#' }}">
        @csrf 
        
        {{-- === BAGIAN BARU: SLIDER KELOMPOK PEKERJAAN (ALPINE JS) === --}}
        <div class="mb-8 mt-8" x-data="{ 
            activeSlide: 0, 
            totalSlides: {{ $asesi->jadwal->skema->kelompokPekerjaan->count() }} 
        }">

            @foreach ($asesi->jadwal->skema->kelompokPekerjaan as $indexKelompok => $kelompok)
                
                {{-- Container Slide --}}
                <div x-show="activeSlide === {{ $indexKelompok }}" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="border border-gray-200 rounded-lg overflow-hidden shadow-md bg-white">

                    {{-- HEADER SLIDER --}}
                    <div class="bg-blue-50 p-4 border-b border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-blue-900 mb-1">
                                {{ $kelompok->judul_unit ?? 'Kelompok Pekerjaan ' . ($indexKelompok + 1) }}
                            </h2>
                            <p class="text-blue-600 text-sm font-medium">
                                Kelompok Pekerjaan {{ $loop->iteration }} dari {{ $loop->count }}
                            </p>
                        </div>

                        {{-- Navigasi Tombol --}}
                        <div class="flex items-center space-x-3 bg-white px-3 py-1.5 rounded-full shadow-sm border border-blue-100">
                            <button @click="activeSlide = activeSlide > 0 ? activeSlide - 1 : 0" 
                                    :class="{ 'opacity-50 cursor-not-allowed': activeSlide === 0, 'hover:bg-blue-100 text-blue-600': activeSlide > 0 }"
                                    class="p-2 rounded-full transition focus:outline-none" 
                                    :disabled="activeSlide === 0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>

                            <span class="text-sm font-bold text-gray-600 min-w-[3rem] text-center">
                                <span x-text="activeSlide + 1"></span> / <span x-text="totalSlides"></span>
                            </span>

                            <button @click="activeSlide = activeSlide < totalSlides - 1 ? activeSlide + 1 : totalSlides - 1" 
                                    :class="{ 'opacity-50 cursor-not-allowed': activeSlide === totalSlides - 1, 'hover:bg-blue-100 text-blue-600': activeSlide < totalSlides - 1 }"
                                    class="p-2 rounded-full transition focus:outline-none"
                                    :disabled="activeSlide === totalSlides - 1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- TABEL UNIT --}}
                    <div class="p-6 bg-white min-h-[300px]">
                        <h3 class="font-bold text-gray-800 text-lg mb-4">Daftar Unit Kompetensi</h3>
                        <div class="overflow-hidden border border-gray-200 rounded-lg">
                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 text-gray-700 uppercase font-bold text-xs">
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 w-16 text-center">No</th>
                                        <th class="px-6 py-3 border-b border-gray-200 w-48">Kode Unit</th>
                                        <th class="px-6 py-3 border-b border-gray-200">Judul Unit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($kelompok->unitKompetensi as $indexUnit => $unit)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-center font-medium">{{ $indexUnit + 1 }}</td>
                                            <td class="px-6 py-4 font-mono text-gray-900 font-semibold">{{ $unit->kode_unit }}</td>
                                            <td class="px-6 py-4 text-gray-800">{{ $unit->judul_unit }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-gray-400 italic">Tidak ada unit kompetensi di kelompok ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-section my-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full">
                    <thead>
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban (Teks)</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban (Teks)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOGIKA LOOPING DINAMIS --}}
                        {{-- Menggunakan chunk(2) untuk membagi soal menjadi 2 kolom per baris --}}
                        @forelse ($semua_soal->chunk(2) as $chunk)
                            <tr>
                                {{-- === KOLOM KIRI (Soal Ganjil) === --}}
                                @php 
                                    $soalKiri = $chunk->first(); 
                                    $nomorKiri = ($loop->index * 2) + 1;
                                @endphp
                                <td class="border border-gray-900 p-2 text-sm text-center">{{ $nomorKiri }}.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" 
                                           name="kunci[{{ $soalKiri->id_soal_ia05 }}]" 
                                           class="form-input w-full border-gray-300 rounded-md shadow-sm"
                                           {{-- Tampilkan jawaban jika ada di database --}}
                                           value="{{ $kunci_jawaban[$soalKiri->id_soal_ia05] ?? '' }}" 
                                           placeholder="Contoh: a"
                                           {{-- Disabled jika bukan Admin --}}
                                           {{ $is_admin ? '' : 'disabled' }}>
                                </td>

                                {{-- === KOLOM KANAN (Soal Genap) === --}}
                                @if($chunk->count() > 1)
                                    @php 
                                        $soalKanan = $chunk->last(); 
                                        $nomorKanan = ($loop->index * 2) + 2;
                                    @endphp
                                    <td class="border border-gray-900 p-2 text-sm text-center">{{ $nomorKanan }}.</td>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        <input type="text" 
                                               name="kunci[{{ $soalKanan->id_soal_ia05 }}]" 
                                               class="form-input w-full border-gray-300 rounded-md shadow-sm"
                                               value="{{ $kunci_jawaban[$soalKanan->id_soal_ia05] ?? '' }}" 
                                               placeholder="Contoh: c"
                                               {{ $is_admin ? '' : 'disabled' }}>
                                    </td>
                                @else
                                    {{-- Jika soal ganjil (tidak ada pasangan di kanan), tampilkan kolom kosong --}}
                                    <td class="border border-gray-900 p-2 bg-gray-100"></td>
                                    <td class="border border-gray-900 p-2 bg-gray-100"></td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4 text-gray-500">
                                    Belum ada soal yang dibuat. Silakan input soal di Form IA.05.A terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="form-section my-8">
            @include('components.kolom_ttd.penyusunvalidator', ['sertifikasi' => $asesi])
        </div>
        
        <div class="form-footer flex justify-end mt-10">
            @if($is_admin)
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Kunci Jawaban</button>
            @endif
        </div>
        
        <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
            <p>*Coret yang tidak perlu</p>
        </div>
    </form>
</main>
@endsection