<x-app-layout>
    <head>
        {{-- 1. IMPORT FONT POPPINS --}}
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        {{-- 2. IMPORT ALPINE JS --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            .font-poppins { font-family: 'Poppins', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>

    <div class="flex h-screen overflow-hidden font-poppins">

        {{-- SIDEBAR --}}
        <x-sidebar2 :idAsesi="$sertifikasi->asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-8 bg-gray-100 overflow-y-auto">
            <div class="max-w-6xl mx-auto bg-white shadow-xl rounded-lg p-8">

                {{-- JUDUL HALAMAN --}}
                <div class="mb-8 border-b-2 border-gray-200 pb-6 text-center">
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-wide">FR.IA.07. PERTANYAAN LISAN</h1>
                    <p class="text-gray-500 mt-2 text-sm">Rekaman hasil asesmen lisan asesi.</p>
                </div>

                {{-- BAGIAN 1: HEADER INFORMASI --}}
                <div class="grid grid-cols-12 gap-y-3 gap-x-4 text-sm mb-8 border border-gray-300 p-5 rounded-lg bg-gray-50">
                    {{-- Skema --}}
                    <div class="col-span-2 font-bold text-gray-700">Skema Sertifikasi</div>
                    <div class="col-span-10 text-gray-900 font-semibold">
                        : {{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}
                        <span class="font-normal text-gray-500">({{ $sertifikasi->jadwal->skema->nomor_skema ?? '-' }})</span>
                    </div>
                    {{-- TUK --}}
                    <div class="col-span-2 font-bold text-gray-700">TUK</div>
                    <div class="col-span-10 text-gray-900">: {{ $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-' }}</div>
                    {{-- Asesor --}}
                    <div class="col-span-2 font-bold text-gray-700">Nama Asesor</div>
                    <div class="col-span-10 text-gray-900">: {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</div>
                    {{-- Asesi --}}
                    <div class="col-span-2 font-bold text-gray-700">Nama Asesi</div>
                    <div class="col-span-10 text-gray-900">: {{ $sertifikasi->asesi->nama_lengkap ?? Auth::user()->name }}</div>
                    {{-- Tanggal --}}
                    <div class="col-span-2 font-bold text-gray-700">Tanggal</div>
                    <div class="col-span-10 text-gray-900">: {{ $sertifikasi->tanggal_pelaksanaan ?? date('d-m-Y') }}</div>
                </div>

                {{-- === BAGIAN BARU: SLIDER KELOMPOK PEKERJAAN (ALPINE JS) === --}}
                <div class="mb-8" x-data="{ 
                    activeSlide: 0, 
                    totalSlides: {{ $sertifikasi->jadwal->skema->kelompokPekerjaan->count() }} 
                }">

                    @foreach ($sertifikasi->jadwal->skema->kelompokPekerjaan as $indexKelompok => $kelompok)
                        
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

                {{-- BAGIAN 2: TABEL REKAPAN JAWABAN --}}
                <div class="overflow-hidden border border-black rounded-sm mb-8">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-200 text-gray-800">
                            <tr>
                                <th class="border border-black p-3 w-12 text-center" rowspan="2">No</th>
                                <th class="border border-black p-3 text-left" rowspan="2">Pertanyaan & Jawaban</th>
                                <th class="border border-black p-2 text-center w-32" colspan="2">Rekomendasi Pencapaian</th>
                            </tr>
                            <tr>
                                <th class="border border-black p-1 text-center w-16 bg-green-100">Ya (K)</th>
                                <th class="border border-black p-1 text-center w-16 bg-red-100">Tidak (BK)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($sertifikasi->ia07 as $index => $item)
                                <tr>
                                    <td class="border border-black p-3 text-center align-top font-bold">{{ $loop->iteration }}</td>
                                    <td class="border border-black p-4 align-top">
                                        <div class="mb-3">
                                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pertanyaan:</span>
                                            <p class="text-gray-900 font-medium mt-1">{{ $item->pertanyaan }}</p>
                                        </div>
                                        <hr class="border-dashed border-gray-300 my-2">
                                        <div class="mt-2 bg-blue-50 p-3 rounded border border-blue-100">
                                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Jawaban Asesi:</span>
                                            <p class="text-gray-800 mt-1 whitespace-pre-line">{{ $item->jawaban_asesi ?? '-' }}</p>
                                        </div>
                                    </td>
                                    {{-- Centang YA (Logic Boolean Aman) --}}
                                    <td class="border border-black p-2 text-center align-middle bg-gray-50">
                                        <div class="flex justify-center">
                                            <input type="checkbox" disabled {{ $item->pencapaian == 1 ? 'checked' : '' }} class="w-6 h-6 text-green-600 border-gray-400 rounded cursor-not-allowed">
                                        </div>
                                    </td>
                                    {{-- Centang TIDAK (Logic Boolean Aman) --}}
                                    <td class="border border-black p-2 text-center align-middle bg-gray-50">
                                        <div class="flex justify-center">
                                            <input type="checkbox" disabled {{ (isset($item->pencapaian) && $item->pencapaian == 0) ? 'checked' : '' }} class="w-6 h-6 text-red-600 border-gray-400 rounded cursor-not-allowed">
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500 italic border border-black">
                                        Data rekaman IA.07 tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TOMBOL KEMBALI HILANG SUDAH --}}

            </div>
        </main>
    </div>
</x-app-layout>