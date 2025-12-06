<x-app-layout>

    {{-- LAYOUT UTAMA: Flexbox untuk Sidebar & Konten --}}
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR: Menggunakan komponen yang sama dengan FR.AK.03 --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                {{-- HEADER --}}
                <h1 class="text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan</h1>
                <p class="text-gray-500 mb-10">Silakan isi jawaban atas pertanyaan kompetensi yang diberikan.</p>

                <form action="{{ route('ia07.store') }}" method="POST">
                    @csrf
                    
                    {{-- PERBAIKAN 1: Hidden Input ID Data Sertifikasi Asesi (Sesuai Model) --}}
                    <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">

                    {{-- BAGIAN 1: DATA INFORMASI --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8 border-b border-gray-200 pb-8">

                        {{-- Skema --}}
                        <div class="col-span-1 font-medium text-gray-800 pt-2">Skema Sertifikasi</div>
                        <div class="col-span-3 text-gray-800 font-semibold pt-2">
                            : {{ $skema->nama_skema ?? '-' }} <span class="text-gray-500 font-normal">({{ $skema->nomor_skema ?? '-' }})</span>
                        </div>

                        {{-- TUK --}}
                        <div class="col-span-1 font-medium text-gray-800 pt-2">TUK</div>
                        <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                             @php
                                $tukRaw = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? $sertifikasi->jadwal->jenisTuk->nama_tuk ?? '';
                                $tukDb = strtolower($tukRaw);
                            @endphp
                            
                            {{-- Sewaktu --}}
                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio" disabled {{ $tukDb == 'sewaktu' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 mr-2 bg-gray-100">
                                Sewaktu
                            </label>

                            {{-- Tempat Kerja --}}
                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio" disabled {{ str_contains($tukDb, 'tempat') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 mr-2 bg-gray-100">
                                Tempat Kerja
                            </label>

                            {{-- Mandiri --}}
                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio" disabled {{ str_contains($tukDb, 'mandiri') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 mr-2 bg-gray-100">
                                Mandiri
                            </label>
                        </div>

                        {{-- Asesor --}}
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                        </div>

                        {{-- Asesi --}}
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $asesi->nama_lengkap ?? Auth::user()->name }}
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-span-1 font-medium text-gray-800 pt-2">Tanggal</div>
                        <div class="col-span-3">
                            <input type="date" name="tanggal_pelaksanaan" value="{{ date('Y-m-d') }}" class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    {{-- BAGIAN 2: DAFTAR UNIT & PERTANYAAN (ACCORDION DINAMIS) --}}
                    <div class="space-y-4 mb-10">
                        @foreach($units as $index => $unit)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            
                            {{-- Header Accordion --}}
                            <button type="button" class="accordion-btn w-full bg-gray-50 px-6 py-4 flex justify-between items-center text-left hover:bg-gray-100 transition-colors" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                <div>
                                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">Unit Kompetensi {{ $loop->iteration }}</div>
                                    {{-- Menggunakan Object Syntax ($unit->kode_unit) sesuai Eloquent --}}
                                    <h3 class="text-base font-bold text-gray-900">{{ $unit->kode_unit ?? $unit->kode_unit_kompetensi ?? '-' }}</h3>
                                    <p class="text-sm text-gray-600 truncate max-w-lg">{{ $unit->judul_unit ?? $unit->judul_unit_kompetensi ?? '-' }}</p>
                                </div>
                                {{-- Icon Chevron --}}
                                <svg class="accordion-icon w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Body Accordion --}}
                            <div class="accordion-content bg-white {{ $index === 0 ? 'active' : '' }}">
                                <div class="p-6 border-t border-gray-200 space-y-8">
                                    
                                    {{-- PERBAIKAN 2: Loop Pertanyaan Lisan dari Database (Relasi) --}}
                                    @forelse($unit->pertanyaanLisan as $tanya)
                                    <div class="bg-white">
                                        <label class="block text-sm font-medium text-gray-900 mb-2 leading-relaxed">
                                            <span class="font-bold text-gray-700 mr-1">{{ $loop->iteration }}.</span> 
                                            {{ $tanya->pertanyaan }}
                                        </label>
                                        
                                        {{-- PERBAIKAN 3: Name Array ID Pertanyaan & Value Lama --}}
                                        <textarea 
                                            name="jawaban[{{ $tanya->id_pertanyaan_lisan }}]" 
                                            rows="3" 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3"
                                            placeholder="Tulis jawaban Anda disini..."
                                        >{{ $jawabanUser[$tanya->id_pertanyaan_lisan] ?? '' }}</textarea>
                                        {{-- Value diambil dari $jawabanUser agar data tidak hilang saat reload --}}
                                    </div>
                                    @empty
                                        <div class="text-center py-4 text-gray-500 text-sm italic bg-gray-50 rounded">
                                            Tidak ada pertanyaan lisan untuk unit ini.
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- REVISI TOMBOL: Hanya tombol Selanjutnya --}}
                    <div class="flex justify-end items-center mt-12">
                        {{-- Hapus tombol Kembali --}}
                        
                        <button type="submit" class="px-10 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                            Selanjutnya &rarr;
                        </button>
                    </div>

                </form>

            </div>
        </main>
    </div>

                   

    {{-- SCRIPT ACCORDION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                acc.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.accordion-icon');
                    
                    // Toggle Active Class
                    content.classList.toggle('active');
                    
                    // Rotate Icon
                    if (content.classList.contains('active')) {
                        icon.style.transform = 'rotate(180deg)';
                        this.setAttribute('aria-expanded', 'true');
                    } else {
                        icon.style.transform = 'rotate(0deg)';
                        this.setAttribute('aria-expanded', 'false');
                    }
                });
            });
        });
    </script>

    {{-- STYLE TAMBAHAN --}}
    <style>
        .accordion-content {
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        .accordion-content.active {
            max-height: 2000px;
            opacity: 1;
        }
    </style>

</x-app-layout>