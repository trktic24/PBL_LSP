@extends('layouts.app-sidebar')
@section('content')
<main class="main-content">

    @php 
        // Tentukan Role untuk Logic Disabled
        $is_asesor = ($user->role_id == 3 || $user->role_id == 1); 
    @endphp

    <form class="form-body" method="POST" action="{{ route('ia-05.store.penilaian', ['id_asesi' => $asesi->id_data_sertifikasi_asesi]) }}">
        @csrf 
        <x-header_form.header_form title="FR.IA.05.C. LEMBAR JAWABAN PILIHAN GANDA" />
        
{{-- === DROPDOWN NAVIGASI (Form C) === --}}
    @if($user->role_id != 2)
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
            
            {{-- Link ke Form A (Soal) --}}
            <a href="{{ route('FR_IA_05_A', $asesi->id_data_sertifikasi_asesi) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 border-b border-gray-100 transition">
                Soal
            </a>
            
            {{-- Link ke Form B (Kunci Jawaban) --}}
            {{-- PENTING: Kita titip ID Asesi via '?ref=' supaya Form B tau mau balik kemana --}}
            <a href="{{ route('FR_IA_05_B') }}?ref={{ $asesi->id_data_sertifikasi_asesi }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                Kunci Jawaban
            </a>
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

        {{-- HEADER IDENTITAS --}}
        <x-identitas_skema_form.identitas_skema_form
            skema="{{ $asesi->jadwal->skema->nama_skema ?? 'Junior Web Developer' }}"
            nomorSkema="{{ $asesi->jadwal->skema->nomor_skema ?? 'SKK.TIK.001' }}"
            tuk="{{ $asesi->jadwal->jenisTuk->jenis_tuk ?? 'SKK.TIK.001' }}" 
            namaAsesor="{{ $asesi->jadwal->asesor->nama_lengkap ?? 'Budi Santoso (Asesor)' }}"
            namaAsesi="{{ $asesi->asesi->nama_lengkap ?? 'Siti Aminah (Asesi)' }}"
            tanggal="{{ now()->format('d F Y') }}"
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
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Lembar Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="border border-gray-900 p-2 font-semibold w-[5%]" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" rowspan="2">Jawaban Asesi</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" colspan="2">Pencapaian</th>
                            
                            {{-- PEMBATAS TENGAH --}}
                            <th class="border border-gray-900 p-2 bg-gray-800 w-[2%]" rowspan="2"></th>

                            <th class="border border-gray-900 p-2 font-semibold w-[5%]" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" rowspan="2">Jawaban Asesi</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" colspan="2">Pencapaian</th>
                        </tr>
                        <tr>
                            <th class="border border-gray-900 p-2 font-semibold">K</th>
                            <th class="border border-gray-900 p-2 font-semibold">BK</th>
                            <th class="border border-gray-900 p-2 font-semibold">K</th>
                            <th class="border border-gray-900 p-2 font-semibold">BK</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @forelse ($semua_soal->chunk(2) as $chunk)
                        {{-- === INI FIXNYA: RESET KEYS AGAR SELALU MULAI DARI 0 === --}}
                        @php $chunk = $chunk->values(); @endphp 
                        {{-- ===================================================== --}}

                        <tr>
                            {{-- KOLOM KIRI --}}
                            @if (isset($chunk[0]))
                                @php 
                                    $soal_kiri = $chunk[0];
                                    $jawaban_kiri = $lembar_jawab->get($soal_kiri->id_soal_ia05);
                                    $nomor_kiri = ($loop->index * 2) + 1;
                                @endphp
                                
                                <td class="border border-gray-900 p-2 text-sm text-center font-bold">{{ $nomor_kiri }}.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" 
                                           class="form-input w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-center font-bold text-blue-800"
                                           value="{{ $jawaban_kiri->jawaban_asesi_ia05 ?? 'A' }}"
                                           readonly>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kiri->id_soal_ia05 }}]" value="ya"
                                        class="form-radio h-5 w-5 text-green-600"
                                        {{-- CEK APAKAH STRINGNYA 'ya' --}}
                                        @checked($jawaban_kiri && $jawaban_kiri->pencapaian_ia05 == 'ya')
                                        @disabled(!$is_asesor) required>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kiri->id_soal_ia05 }}]" value="tidak"
                                        class="form-radio h-5 w-5 text-red-600"
                                        {{-- CEK APAKAH STRINGNYA 'tidak' --}}
                                        @checked($jawaban_kiri && $jawaban_kiri->pencapaian_ia05 == 'tidak')
                                        @disabled(!$is_asesor)>
                                </td>
                            @endif

                            {{-- PEMBATAS TENGAH --}}
                            <td class="border border-gray-900 bg-gray-300"></td>

                            {{-- KOLOM KANAN --}}
                            @if (isset($chunk[1]))
                                @php 
                                    $soal_kanan = $chunk[1]; 
                                    $jawaban_kanan = $lembar_jawab->get($soal_kanan->id_soal_ia05);
                                    $nomor_kanan = ($loop->index * 2) + 2;
                                @endphp
                                
                                <td class="border border-gray-900 p-2 text-sm text-center font-bold">{{ $nomor_kanan }}.</td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" 
                                           class="form-input w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-center font-bold text-blue-800"
                                           value="{{ $jawaban_kanan->jawaban_asesi_ia05 ?? 'B' }}"
                                           readonly>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                   <input type="radio" name="penilaian[{{ $soal_kanan->id_soal_ia05 }}]" value="ya"
                                        class="form-radio h-5 w-5 text-green-600"
                                        {{-- CEK APAKAH STRINGNYA 'ya' --}}
                                        @checked($jawaban_kanan && $jawaban_kanan->pencapaian_ia05 == 'ya')
                                        @disabled(!$is_asesor) required>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kanan->id_soal_ia05 }}]" value="tidak"
                                        class="form-radio h-5 w-5 text-red-600"
                                        {{-- CEK APAKAH STRINGNYA 'tidak' --}}
                                        @checked($jawaban_kanan && $jawaban_kanan->pencapaian_ia05 == 'tidak')
                                        @disabled(!$is_asesor)>
                                </td>
                            @else
                                {{-- Jika Ganjil, Kolom Kanan Kosong --}}
                                <td class="border border-gray-900 bg-gray-50" colspan="4"></td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="9" class="p-4 text-center text-gray-500">Belum ada soal.</td></tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
            
        <div class="form-section my-8">
            {{-- UMPAN BALIK --}}
            <div class="border border-gray-900 shadow-md w-full mb-8">
                <table class="w-full border-collapse">
                    <tbody>
                        <tr>
                            <td class="border border-gray-900 p-2 font-semibold w-40 bg-black text-white align-top">Umpan balik untuk asesi</td>
                            <td class="p-2">
                                <label class="font-bold">Aspek pengetahuan seluruh unit kompetensi yang diujikan (tercapai / belum tercapai)*</label>
                                
                                {{-- PASTIKAN NAME-NYA 'global_komentar' --}}
                                <textarea 
                                    name="umpan_balik"      {{-- UBAH JADI INI --}}
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" 
                                    rows="3"
                                    placeholder="Tulis umpan balik disini..."
                                    {{-- Karena di Controller IA05 kita kirim variabel $umpan_balik, maka pakainya itu --}}
                                >{{ old('umpan_balik', $umpan_balik ?? '') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @include('components.kolom_ttd.asesiasesor', ['sertifikasi' => $asesi])
        </div>
            
        <div class="form-footer flex justify-between mt-10">
            <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
            @if($is_asesor)
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Penilaian</button>
            @endif
        </div>
    </form>
</main>
@endsection