{{-- 
    FILE: resources/views/asesor/fr_ia_07.blade.php
    (Atau sesuaikan dengan struktur folder project Anda)
--}}

<x-app-layout>
    
    <div class="flex min-h-screen">
        
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan</h1>
                <p class="text-gray-600 mb-10">
                    Daftar pertanyaan lisan yang diajukan asesor untuk menilai kompetensi asesi.
                </p>

                <form action="{{ route('ia07.store') }}" method="POST">
                    @csrf 

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
                    
                    @php
                        $units = [
                            ['code' => 'J.620100.004.02', 'title' => 'Menggunakan Struktur Data'],
                            ['code' => 'J.620100.005.02', 'title' => 'Mengimplementasikan User Interface'],
                            ['code' => 'J.620100.009.01', 'title' => 'Melakukan Instalasi Software Tools Pemrograman'],
                            ['code' => 'J.620100.016.01', 'title' => 'Menulis Kode dengan Prinsip Sesuai Guidelines'],
                            ['code' => 'J.620100.017.02', 'title' => 'Mengimplementasikan Pemrograman Terstruktur'],
                            ['code' => 'J.620100.019.02', 'title' => 'Menggunakan Library atau Komponen Pre-Existing'],
                            ['code' => 'J.620100.023.02', 'title' => 'Membuat Dokumen Kode Program'],
                            ['code' => 'J.620100.025.02', 'title' => 'Melakukan Debugging'],
                        ];
                    @endphp

                    <div class="space-y-6 mb-12">
                        @foreach($units as $index => $unit)
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            
                            <button type="button" class="accordion-btn w-full bg-white p-5 flex justify-between items-center text-left hover:bg-gray-50 transition-colors border-b border-gray-100" aria-expanded="false">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $unit['code'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $unit['title'] }}</p>
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
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-16">No</th>
                                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Pertanyaan Lisan</th>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-32">Rekomendasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @for($q = 1; $q <= 3; $q++)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-6 text-center align-top">
                                                    <span class="font-semibold text-gray-800">{{ $q }}</span>
                                                </td>
                                                
                                                <td class="px-6 py-6 text-sm text-gray-700 align-top leading-relaxed">
                                                    <p class="mb-2 font-medium text-gray-900">
                                                        Jelaskan langkah-langkah dalam melakukan {{ strtolower(substr($unit['title'], 0, 15)) }}...?
                                                    </p>
                                                    <p class="text-xs text-gray-500 italic mb-3 pl-3 border-l-2 border-gray-300">
                                                        Kunci Jawaban: Peserta mampu menjelaskan secara rinci mengenai prosedur...
                                                    </p>
                                                    
                                                    <label for="ringkasan_{{$index}}_{{$q}}" class="block text-xs font-medium text-gray-600 mb-1">Ringkasan Jawaban Asesi:</label>
                                                    <textarea id="ringkasan_{{$index}}_{{$q}}"
                                                              name="jawaban[{{$index}}][{{$q}}][ringkasan]" 
                                                              rows="3" 
                                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3 border bg-gray-50" 
                                                              placeholder="Tuliskan poin penting jawaban asesi..."></textarea>
                                                </td>
                                                
                                                <td class="px-6 py-6 align-top">
                                                    <div class="flex justify-center space-x-6 pt-2">
                                                        <label class="flex flex-col items-center cursor-pointer group">
                                                            <input type="radio" 
                                                                   name="jawaban[{{$index}}][{{$q}}][hasil]" 
                                                                   value="K" 
                                                                   class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                                   required>
                                                            <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 mt-1">K</span>
                                                        </label>
                                                        <label class="flex flex-col items-center cursor-pointer group">
                                                            <input type="radio" 
                                                                   name="jawaban[{{$index}}][{{$q}}][hasil]" 
                                                                   value="BK" 
                                                                   class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500"
                                                                   required>
                                                            <span class="text-sm font-bold text-gray-700 group-hover:text-red-600 mt-1">BK</span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm mb-12">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Tanda Tangan & Keputusan</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Tanda Tangan Asesor</label>
                                <div class="w-full h-48 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center relative overflow-hidden group">
                                    <p class="text-gray-400 text-sm group-hover:opacity-0 transition-opacity">Area Tanda Tangan</p>
                                    <img src="https://via.placeholder.com/300x150/FFFFFF/000000?text=Ttd+Asesor" alt="Ttd Asesor" class="absolute inset-0 object-cover opacity-50">
                                </div>
                                <p class="mt-3 text-sm font-bold text-gray-900">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Nama Asesor' }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $sertifikasi->jadwal->asesor->no_reg ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Tanda Tangan Asesi</label>
                                <div class="w-full h-48 bg-white border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 hover:border-blue-500 transition-all group">
                                    <svg class="h-10 w-10 text-blue-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <p class="text-sm font-medium text-blue-600">Klik untuk Tanda Tangan</p>
                                    <p class="text-xs text-gray-400 mt-1">Konfirmasi hasil penilaian lisan</p>
                                </div>
                                <p class="mt-3 text-sm font-bold text-gray-900">{{ $asesi->nama_lengkap ?? 'Nama Peserta Uji' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="button" onclick="history.back()" class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Sebelumnya
                        </button>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan & Selesai
                        </button>
                    </div>
                
                </form>
                </div>
        </main>
    </div>

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
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>

</x-app-layout>