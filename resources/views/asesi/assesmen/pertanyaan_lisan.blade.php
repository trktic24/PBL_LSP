<x-app-layout>
    <div class="flex min-h-screen">
        
        {{-- Sidebar Menu --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan</h1>
                <p class="text-gray-600 mb-10">
                    Daftar pertanyaan lisan yang diajukan asesor untuk menilai kompetensi asesi.
                </p>

                <form action="{{ route('ia07.store') }}" method="POST">
                    @csrf 
                    {{-- Hidden Input ID Sertifikasi --}}
                    <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">

                    {{-- HEADER DATA --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-12 border-b border-gray-200 pb-8">
                        <div class="col-span-1 font-medium text-gray-800">Skema Sertifikasi</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}
                        </div>
                        
                        <div class="col-span-1 font-medium text-gray-800">TUK</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? $sertifikasi->jadwal->jenisTuk->nama_tuk ?? '-' }}
                        </div>
                        
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                        </div>

                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $asesi->nama_lengkap ?? '-' }}
                        </div>

                        <div class="col-span-1 font-medium text-gray-800">Tanggal</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                        </div>
                    </div>
                    
                    {{-- 
                        [PERBAIKAN LOGIC] 
                        Looping Unit Kompetensi dari Database (Relasi Skema)
                        Pastikan Model Skema punya relasi -> unitKompetensi()
                    --}}
                    <div class="space-y-6 mb-12">
                        @forelse($sertifikasi->jadwal->skema->unitKompetensi as $index => $unit)
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            
                            {{-- Accordion Header --}}
                            <button type="button" class="accordion-btn w-full bg-white p-5 flex justify-between items-center text-left hover:bg-gray-50 transition-colors border-b border-gray-100" aria-expanded="false">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $unit->kode_unit }}</h3>
                                    <p class="text-sm text-gray-600">{{ $unit->judul_unit }}</p>
                                </div>
                                <div class="ml-4 bg-blue-100 p-2 rounded-full text-blue-600 accordion-icon-wrapper">
                                    <svg class="accordion-icon w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>

                            <div class="accordion-content hidden transition-all duration-300 ease-in-out bg-white">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-900 text-white">
                                            <tr>
                                                <th class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-16">No</th>
                                                <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Pertanyaan Lisan</th>
                                                <th class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-32">Rekomendasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            
                                            {{-- 
                                                [PERBAIKAN LOGIC]
                                                Looping Pertanyaan dari Database
                                                Pastikan Model UnitKompetensi punya relasi -> pertanyaanLisan()
                                            --}}
                                            @forelse($unit->pertanyaanLisan as $tanya)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-6 text-center align-top">
                                                    <span class="font-semibold text-gray-800">{{ $loop->iteration }}</span>
                                                </td>
                                                
                                                <td class="px-6 py-6 text-sm text-gray-700 align-top leading-relaxed">
                                                    {{-- Soal Dinamis --}}
                                                    <p class="mb-2 font-medium text-gray-900">
                                                        {{ $tanya->pertanyaan }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 italic mb-3 pl-3 border-l-2 border-gray-300">
                                                        Kunci Jawaban: {{ $tanya->kunci_jawaban }}
                                                    </p>
                                                    
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Ringkasan Jawaban Asesi:</label>
                                                    
                                                    {{-- 
                                                        [PENTING] Name pakai ID Pertanyaan 
                                                        Format: jawaban[ID_PERTANYAAN][FIELD]
                                                    --}}
                                                    <textarea 
                                                        name="jawaban[{{ $tanya->id_pertanyaan_lisan }}][ringkasan]" 
                                                        rows="3" 
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3 border bg-gray-50" 
                                                        placeholder="Tuliskan ringkasan jawaban asesi..."></textarea>
                                                </td>
                                                
                                                <td class="px-6 py-6 align-top">
                                                    <div class="flex justify-center space-x-6 pt-2">
                                                        <label class="flex flex-col items-center cursor-pointer group">
                                                            <input type="radio" 
                                                                   name="jawaban[{{ $tanya->id_pertanyaan_lisan }}][hasil]" 
                                                                   value="K" 
                                                                   class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                                   required>
                                                            <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 mt-1">K</span>
                                                        </label>
                                                        <label class="flex flex-col items-center cursor-pointer group">
                                                            <input type="radio" 
                                                                   name="jawaban[{{ $tanya->id_pertanyaan_lisan }}][hasil]" 
                                                                   value="BK" 
                                                                   class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500"
                                                                   required>
                                                            <span class="text-sm font-bold text-gray-700 group-hover:text-red-600 mt-1">BK</span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center p-4 text-gray-400 italic">
                                                    Belum ada pertanyaan lisan untuk unit ini.
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center p-8 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-gray-500">
                            Tidak ada Unit Kompetensi yang terhubung dengan Skema ini.
                        </div>
                        @endforelse
                    </div>

                    {{-- AREA TTD --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm mb-12">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Tanda Tangan & Keputusan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Tanda Tangan Asesor</label>
                                <div class="w-full h-48 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center relative overflow-hidden group">
                                    <p class="text-gray-400 text-sm">Area Tanda Tangan</p>
                                </div>
                                <p class="mt-3 text-sm font-bold text-gray-900">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Nama Asesor' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Tanda Tangan Asesi</label>
                                <div class="w-full h-48 bg-white border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 transition-all">
                                    <p class="text-sm font-medium text-blue-600">Klik untuk Tanda Tangan</p>
                                </div>
                                <p class="mt-3 text-sm font-bold text-gray-900">{{ $asesi->nama_lengkap ?? 'Nama Peserta Uji' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="button" onclick="history.back()" class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200 transition-colors">
                            Sebelumnya
                        </button>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-colors">
                            Simpan & Selesai
                        </button>
                    </div>
                
                </form>
            </div>
        </main>
    </div>

    {{-- Script Accordion --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordionBtns = document.querySelectorAll('.accordion-btn');
            accordionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false');
                    this.querySelector('.accordion-icon-wrapper').classList.toggle('rotate-180');
                    this.nextElementSibling.classList.toggle('hidden');
                });
            });
        });
    </script>
    
    <style>
        .rotate-180 { transform: rotate(180deg); }
    </style>

</x-app-layout>