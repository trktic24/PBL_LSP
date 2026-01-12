@extends('layouts.app-sidebar-asesi')
@section('content')
<main class="main-content">
<x-header_form.header_form title="FR.IA.05A. DPT - PERTANYAAN TERTULIS PILIHAN GANDA" />

{{-- === [BARU] DROPDOWN NAVIGASI (Hanya Admin & Asesor) === --}}
    @if($user->role_id != 2)
    <div class="flex justify-end mt-6 mb-2 relative">
        <button type="button" onclick="toggleNavDropdown()" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 flex items-center gap-2 text-sm font-bold transition duration-150 ease-in-out">
            <span>Navigasi</span>
            {{-- Icon Panah Bawah --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Isi Dropdown --}}
        <div id="nav-dropdown" class="hidden absolute right-0 top-full mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-xl z-50 overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                Pindah Halaman
            </div>
            
            {{-- Link ke Form B --}}
            <a href="{{ route('FR_IA_05_B') }}?ref={{ $asesi->id_data_sertifikasi_asesi }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 border-b border-gray-100 transition">
                Kunci Jawaban
            </a>
            
            {{-- Link ke Form C --}}
            {{-- Kita oper ID Asesi saat ini agar Form C membuka orang yang sama --}}
            <a href="{{ route('FR_IA_05_C', $asesi->id_data_sertifikasi_asesi) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                Lembar Penilaian
            </a>
        </div>
    </div>

    {{-- Script Sederhana untuk Buka/Tutup --}}
    <script>
        function toggleNavDropdown() {
            const dropdown = document.getElementById('nav-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Tutup dropdown jika klik di luar tombol
        window.onclick = function(event) {
            if (!event.target.closest('button')) {
                const dropdown = document.getElementById('nav-dropdown');
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        }
    </script>
    @endif
    {{-- === [AKHIR] DROPDOWN === --}}

    @php
        $formAction = '#'; 
        if ($user->role_id == 1) { // Admin
            $formAction = route('ia-05.store.soal');
        } elseif ($user->role_id == 2) { // Asesi
            $formAction = route('ia-05.store.jawaban', ['id_asesi' => $asesi->id_data_sertifikasi_asesi]);
        }
    @endphp
    
    <form class="form-body mt-6" action="{{ $formAction }}" method="POST"> 
        @csrf
        <x-identitas_skema_form.identitas_skema_form
            skema="{{ $asesi->jadwal->skema->nama_skema ?? 'Judul Skema Tidak Ditemukan' }}"
            nomorSkema="{{ $asesi->jadwal->skema->nomor_skema ?? 'Kode Tidak Ditemukan' }}"
            tuk="{{ $asesi->jadwal->jenisTuk->jenis_tuk ?? 'Tempat Kerja' }}" 
            namaAsesor="{{ $asesi->asesor->nama_lengkap ?? 'Nama Asesor Tidak Ditemukan' }}"
            namaAsesi="{{ $asesi->asesi->nama_lengkap ?? 'Nama Asesi Tidak Ditemukan' }}"
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

        <div class="form-section my-8">
            <h3 class="mb-4 font-semibold text-lg text-gray-800">
                @if($user->role_id == 1) Input Pertanyaan (Mode Admin):
                @elseif($user->role_id == 2) Lembar Jawaban Pilihan Ganda:
                @else Daftar Pertanyaan: {{-- TULISAN READ ONLY SUDAH DIHAPUS --}}
                @endif
            </h3>

            {{-- === LOOPING SOAL === --}}
            @forelse ($semua_soal as $loop => $soal)
            <div class="form-group bg-white p-4 rounded-lg border border-gray-200 @if(!$loop->first) mt-4 @endif">
                
                {{-- TAMPILAN ADMIN --}}
                @if ($user->role == 'admin')
                    <div class="flex items-start space-x-3">
                        <label for="q{{ $soal->id_soal_ia05 }}" class="text-base font-semibold text-gray-800 pt-2">{{ $loop->iteration }}.</label>
                        <textarea id="q{{ $soal->id_soal_ia05 }}" name="soal[{{ $soal->id_soal_ia05 }}][pertanyaan]" rows="2" placeholder="Tulis pertanyaan..." 
                                  class="form-textarea w-full border-gray-300 rounded-md shadow-sm"
                        >{{ $soal->soal_ia05 }}</textarea>
                    </div>
                    <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 items-center mt-3 ml-8">
                        <label for="q{{ $soal->id_soal_ia05 }}a" class="text-sm">a.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}a" name="soal[{{ $soal->id_soal_ia05 }}][opsi_a]" value="{{ $soal->opsi_jawaban_a }}" placeholder="Opsi a..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q{{ $soal->id_soal_ia05 }}b" class="text-sm">b.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}b" name="soal[{{ $soal->id_soal_ia05 }}][opsi_b]" value="{{ $soal->opsi_jawaban_b }}" placeholder="Opsi b..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q{{ $soal->id_soal_ia05 }}c" class="text-sm">c.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}c" name="soal[{{ $soal->id_soal_ia05 }}][opsi_c]" value="{{ $soal->opsi_jawaban_c }}" placeholder="Opsi c..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q{{ $soal->id_soal_ia05 }}d" class="text-sm">d.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}d" name="soal[{{ $soal->id_soal_ia05 }}][opsi_d]" value="{{ $soal->opsi_jawaban_d }}" placeholder="Opsi d..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                
                {{-- TAMPILAN ASESI (Bisa Pilih Jawaban) --}}
                @elseif ($user->role == 'asesi')
                    <div class="flex items-start space-x-3">
                        <label class="text-base font-semibold text-gray-800 pt-2">{{ $loop->iteration }}.</label>
                        <div class="w-full bg-gray-50 p-3 rounded text-gray-700 border border-gray-200">{{ $soal->soal_ia05 }}</div>
                    </div>
                    <div class="space-y-2 mt-3 ml-8">
                        @foreach(['A','B','C','D'] as $opsi)
                            @php 
                                $teks_opsi = 'opsi_jawaban_'.strtolower($opsi); 
                                // Cek apakah opsi tersebut ada isinya (misal opsi D kosong, jangan ditampilkan)
                            @endphp
                            @if(!empty($soal->$teks_opsi))
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="jawaban[{{ $soal->id_soal_ia05 }}]" value="{{ $opsi }}" class="form-radio h-4 w-4 text-blue-600"
                                        {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == $opsi) ? 'checked' : '' }} required>
                                    <span class="text-sm"><strong>{{ strtolower($opsi) }}.</strong> {{ $soal->$teks_opsi }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>

                {{-- TAMPILAN ASESOR (Lihat Soal & Opsi - Read Only) --}}
                @else
                    <div class="flex items-start space-x-3">
                        <label class="text-base font-semibold text-gray-800 pt-2">{{ $loop->iteration }}.</label>
                        <div class="w-full bg-gray-50 p-3 rounded text-gray-700 border border-gray-200">{{ $soal->soal_ia05 }}</div>
                    </div>
                    <div class="space-y-2 mt-3 ml-8">
                        {{-- REVISI: MENAMPILKAN OPSI A, B, C, D TAPI DISABLED --}}
                        @foreach(['A','B','C','D'] as $opsi)
                            @php $teks_opsi = 'opsi_jawaban_'.strtolower($opsi); @endphp
                            
                            @if(!empty($soal->$teks_opsi))
                                <label class="flex items-center space-x-3 cursor-default">
                                    <input type="radio" disabled class="form-radio h-4 w-4 text-gray-300 bg-gray-100 border-gray-300">
                                    <span class="text-sm text-gray-600"><strong>{{ strtolower($opsi) }}.</strong> {{ $soal->$teks_opsi }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endif 
            </div>
            @empty
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                Data soal belum diinput oleh Admin.
            </div>
            @endforelse 

            {{-- BAGIAN DINAMIS (ADMIN ONLY) --}}
            <div id="dynamic-soal-container"></div>

            @if($user->role == 'admin')
            <div class="mt-4 text-center">
                <button type="button" onclick="tambahSoal()" class="btn py-2 px-4 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">
                    + Tambah Soal Baru
                </button>
            </div>
            @endif

        </div>
        
        {{-- Bagian TTD dipindah manual di bawah agar tidak konflik dengan komponen --}}

        {{-- Tanda Tangan --}}
        <div class="bg-white border border-gray-300 rounded-xl p-6 shadow-md mb-8">

            <div class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">
                    {{-- BAGIAN ASESOR --}}
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-700 mb-3">Semarang, {{ \Carbon\Carbon::parse($asesi->jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</h3>
                            <h4 class="font-medium text-gray-800">Penyusun</h4>
                            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-start">
                                <!-- Baris Nama -->
                                <span class="font-medium text-gray-700">Nama</span>
                                <span class="font-medium">:</span>
                                <span class="font-medium text-gray-700">{{ $asesi->asesor->nama_lengkap }}</span>
                                <span class="font-medium text-gray-700">Tanda Tangan</span>
                                <span class="font-medium">:</span>
                                @php
                                    $ttdAsesorBase64 = getTtdBase64($asesi->asesor->tanda_tangan ?? null, $asesi->asesor->id_user ?? $asesi->asesor->user_id ?? null, 'asesor');
                                @endphp
                                @if($ttdAsesorBase64)
                                <img src="{{ $ttdAsesorBase64 }}" 
                                     alt="Tanda Tangan Asesor" 
                                     class="h-20 w-auto object-contain p-1 hover:scale-110 transition cursor-pointer">
                                @else
                                <span class="text-gray-400 text-xs">Belum ada TTD</span>
                                @endif
                            </div>
                        </div>

                    {{-- BAGIAN PJ KEGIATAN --}}
                        <div class="space-y-3 md:mt-10">
                            <h4 class="font-medium text-gray-800">Validator</h4>
                            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-start">
                                <!-- Baris Nama -->
                                <span class="font-medium text-gray-700">Nama</span>
                                <span class="font-medium">:</span>
                                <span class="font-medium text-gray-700">Ajeng Febria H.</span>
                                <span class="font-medium text-gray-700">Tanda Tangan</span>
                                <span class="font-medium">:</span>
                                @if($ttdAsesorBase64)
                                <img src="{{ $ttdAsesorBase64 }}" 
                                    class="w-20 h-auto object-contain p-1 hover:scale-110 transition cursor-pointer">
                                @else
                                <span class="text-gray-400 text-xs">Belum ada TTD</span>
                                @endif
                            </div>
                        </div>
                    
                </div>
            </div>      
        
        <div class="form-footer flex justify-end mt-10">
            @if($user->role == 'admin')
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Soal</button>
            @elseif($user->role == 'asesi')
                 <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim Jawaban</button>
            @endif
        </div>
        
        <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
            <p>*Coret yang tidak perlu</p>
        </div>
    </form>
</main>

@if($user->role == 'admin')
<script>
    let newSoalIndex = {{ count($semua_soal) }}; 

    function tambahSoal() {
        newSoalIndex++;
        const container = document.getElementById('dynamic-soal-container');
        
        const template = `
        <div class="form-group bg-white p-4 rounded-lg border border-gray-200 mt-4 relative">
            <div class="absolute top-2 right-2">
                 <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700 text-xs font-bold">[Hapus]</button>
            </div>
            <div class="flex items-start space-x-3">
                <label class="text-base font-semibold text-gray-800 pt-2">${newSoalIndex}.</label>
                <textarea name="new_soal[${newSoalIndex}][pertanyaan]" rows="2" placeholder="Tulis pertanyaan baru..." class="form-textarea w-full border-gray-300 rounded-md shadow-sm" required></textarea>
            </div>
            <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 items-center mt-3 ml-8">
                <label class="text-sm">a.</label> <input type="text" name="new_soal[${newSoalIndex}][opsi_a]" placeholder="Opsi a..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                <label class="text-sm">b.</label> <input type="text" name="new_soal[${newSoalIndex}][opsi_b]" placeholder="Opsi b..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                <label class="text-sm">c.</label> <input type="text" name="new_soal[${newSoalIndex}][opsi_c]" placeholder="Opsi c..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                <label class="text-sm">d.</label> <input type="text" name="new_soal[${newSoalIndex}][opsi_d]" placeholder="Opsi d..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm" required>
            </div>
        </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
    }
</script>
@endif

@endsection