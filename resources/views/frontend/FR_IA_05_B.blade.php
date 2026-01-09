@extends('layouts.app-sidebar')

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

    {{-- BAGIAN IDENTITAS SKEMA (SUDAH DIISI) --}}
    <div class="form-row grid grid-cols-1 md:grid-cols-[250px_1fr] md:gap-x-6 gap-y-1.5 items-start md:items-center">
        
        <label class="text-sm font-bold text-black">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
        <div class="flex items-center">
            <span>:</span>
            {{-- Ambil Judul Skema dari Controller --}}
            <p class="ml-2 font-medium text-gray-600">{{ $skema_info->nama_skema ?? 'Junior Web Developer' }}</p>
        </div>

        <label class="text-sm font-bold text-black">Nomor</label>
        <div class="flex items-center">
            <span>:</span>
            {{-- Ambil Kode Skema dari Controller --}}
            <p class="ml-2 font-medium text-gray-600">{{ $skema_info->nomor_skema ?? 'SKK.XXXXX.XXXX' }}</p>
        </div>

        <label class="text-sm font-bold text-black">TUK</label>
        <div class="radio-group flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-4">
            <span>:</span>
            <div class="flex items-center space-x-2 ml-0 md:ml-2">
                <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" disabled>
                <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="radio" id="tuk_tempatkerja" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" checked disabled>
                <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="radio" id="tuk_mandiri" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" disabled>
                <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
            </div>
        </div>
    </div>

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
        
        {{-- TABEL UNIT KOMPETENSI (Statis / Disabled sesuai request) --}}
        <div class="form-section my-8">
            <div class="border border-gray-900 shadow-md">
                <table class="w-full">
                    <thead>
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 font-semibold w-[25%]">Kelompok Pekerjaan ...</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[30%]">Kode Unit</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[35%]">Judul Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3" class="border border-gray-900 p-2 align-top text-sm">..............................</td>
                            <td class="border border-gray-900 p-2 text-sm text-center">1.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_1" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_1" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 text-sm text-center">2.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_2" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_2" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2 text-sm text-center">3.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_3" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_3" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-900 p-2"></td>
                            <td class="border border-gray-900 p-2 text-sm text-center">Dst.</td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="kode_unit_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                            <td class="border border-gray-900 p-2 text-sm">
                                <input type="text" name="judul_unit_dst" class="form-input w-full border-gray-300 rounded-md shadow-sm" disabled>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
            @include('components.kolom_ttd.penyusunvalidator')
        </div>
        
        <div class="form-footer flex justify-between mt-10">
            <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
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