<x-app-layout>
    {{-- Container Utama background putih penuh --}}
    <div class="flex h-screen overflow-hidden bg-white"> 
        {{-- SIDEBAR KIRI - Tetap milik Anda (Tidak diubah) --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 bg-white overflow-y-auto">
            {{-- Wrapper Konten: Menggunakan max-w-7xl agar tabel lebar --}}
            <div class="max-w-7xl mx-auto p-8 lg:p-12 content-font-base bg-white shadow-none">

                <div class="mb-12 text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">FR.IA.09. – Pertanyaan Wawancara</h1>
                    <p class="text-gray-600">Daftar pertanyaan wawancara yang diajukan asesor untuk menilai kompetensi asesi berdasarkan portofolio.</p>
                </div>

                <form action="{{ route('asesmen.ia09.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_sertifikasi" value="{{ $sertifikasi->id_data_sertifikasi_asesi ?? 0 }}">

                    {{-- INFORMASI UMUM --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-12 border-b border-gray-200 pb-8 px-4 bg-white font-normal">
                        <div class="col-span-1 font-medium text-gray-800">Skema Sertifikasi</div>
                        <div class="col-span-3 text-gray-800">: {{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}</div>
                        <div class="col-span-1 font-medium text-gray-800">TUK</div>
                        <div class="col-span-3 text-gray-800">: {{ $jenis_tuk_db }}</div>
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800">: {{ $asesor->nama_lengkap ?? '-' }}</div>
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800">: {{ $asesi->nama_lengkap ?? '-' }}</div>
                        <div class="col-span-1 font-medium text-gray-800">Tanggal</div>
                        <div class="col-span-3 text-gray-800">: {{ $tanggal_pelaksanaan }}</div>
                    </div>

                    {{-- PANDUAN ASESOR --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-10 shadow-sm mx-4 font-normal">
                        <h3 class="font-bold text-blue-900 mb-3 uppercase">Panduan Bagi Asesor:</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 text-sm">
                            <li>Pertanyaan wawancara dapat dilakukan untuk keseluruhan unit kompetensi atau kelompok pekerjaan.</li>
                            <li>Isilah bukti portofolio sesuai dengan bukti yang diminta pada skema sertifikasi sebagaimana yang telah dibuat pada FR.IA.08</li>
                            <li>Ajukan pertanyaan verifikasi portofolio untuk semua unit/elemen kompetensi yang di *checklist* pada FR.IA.08</li>
                            <li>Tuliskan pencapaian atas setiap kesimpulan pertanyaan wawancara dengan cara mencentang (√) “Ya” atau “Tidak”.</li>
                        </ul>
                    </div>

                    {{-- UNIT KOMPETENSI --}}
                    <div class="mb-10 mx-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Unit Kompetensi (Lihat)</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full table-formal text-sm border-collapse">
                                <thead class="bg-blue-800 text-white text-center font-bold">
                                    <tr>
                                        <th rowspan="2" class="p-3 border border-black w-40 font-normal">Kelompok Pekerjaan</th>
                                        <th colspan="3" class="p-3 border border-black font-normal">Unit Kompetensi</th>
                                    </tr>
                                    <tr>
                                        <th class="p-3 border border-black w-10 font-normal">No.</th>
                                        <th class="p-3 border border-black w-1/4 font-normal">Kode Unit</th>
                                        <th class="p-3 border border-black font-normal">Judul Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unitsToDisplay as $index => $unit)
                                    <tr class="bg-white font-normal">
                                        @if ($index === 0)
                                        <td rowspan="{{ count($unitsToDisplay) }}" class="p-3 border border-black text-center align-middle bg-white">{{ $kelompok_pekerjaan }}</td>
                                        @endif
                                        <td class="p-2 border border-black text-center bg-white">{{ $index + 1 }}.</td>
                                        <td class="p-2 border border-black text-center bg-white uppercase">{{ $unit['code'] }}</td>
                                        <td class="p-3 border border-black bg-white">{{ $unit['title'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- BUKTI PORTOFOLIO --}}
                    <div class="mb-10 mx-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Bukti Portofolio</h3>
                        <table class="w-full table-formal text-sm border border-black border-collapse">
                            <thead class="bg-blue-800 text-white text-center font-bold">
                                <tr>
                                    <th class="p-3 border border-black w-12 font-normal">No.</th>
                                    <th class="p-3 border border-black font-normal">Bukti Portofolio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($merged_data as $index => $item)
                                <tr class="bg-white font-normal">
                                    <td class="p-2 border border-black text-center bg-white">{{ $index + 1 }}.</td>
                                    <td class="p-2 border border-black bg-white">
                                        <textarea rows="1" class="w-full bg-white border-none focus:ring-0 text-sm no-scrollbar resize-none font-normal" readonly>{{ $item->bukti_dasar }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- DAFTAR PERTANYAAN WAWANCARA --}}
                    <div class="mb-12 mx-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Daftar Pertanyaan Wawancara</h3>
                        <table class="w-full table-formal text-sm border border-black border-collapse">
                            <thead class="bg-blue-800 text-white text-center font-normal">
                                <tr>
                                    <th class="p-3 border border-black w-12 font-normal">No.</th>
                                    <th class="p-3 border border-black w-1/2 font-normal tracking-wider">Daftar Pertanyaan Wawancara</th>
                                    <th class="p-3 border border-black font-normal tracking-wider">Kesimpulan Jawaban Asesi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($merged_data as $index => $item)
                                <tr class="bg-white font-normal">
                                    <td class="p-2 border border-black text-center align-top bg-white leading-relaxed">{{ $index + 1 }}.</td>
                                    <td class="p-2 border border-black bg-white align-top font-normal">
                                        <div class="p-2 leading-relaxed text-sm">{{ $item->pertanyaan_teks }}</div>
                                    </td>
                                    <td class="p-2 border border-black align-top bg-white">
                                        <textarea name="kesimpulan[{{ $item->id_input }}]" rows="4" class="w-full border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-blue-500 bg-white font-normal" placeholder="Kesimpulan Asesor atas jawaban Asesi">{{ $item->kesimpulan_jawaban_asesi }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- KONFIRMASI & TANDA TANGAN --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm mb-12 mx-4 font-normal">
                        {{-- PERUBAHAN DI SINI: text-center diubah menjadi text-left --}}
                        <h3 class="text-xl font-bold text-gray-900 mb-10 pb-3 border-b border-gray-100 text-left tracking-widest">Konfirmasi & Tanda Tangan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            {{-- ASESOR --}}
                            <div class="flex flex-col items-center">
                                <label class="block text-sm font-medium text-gray-700 mb-3 font-semibold">Tanda Tangan Asesor</label>
                                <div class="w-full max-w-[400px] h-48 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center relative overflow-hidden group">
                                    @if($tanda_tangan_asesor_path)
                                        <img src="{{ asset('storage/' . $tanda_tangan_asesor_path) }}" alt="Ttd Asesor" class="max-h-full object-contain p-2">
                                    @else
                                        <p class="text-gray-400 text-sm font-medium opacity-50 font-semibold">Area Tanda Tangan</p>
                                    @endif
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-base text-gray-900 underline decoration-1 font-medium">{{ $asesor->nama_lengkap ?? '-' }}</p>
                                    {{-- MENGHUBUNGKAN KE KOLOM nomor_regis DI DATABASE ASESOR --}}
                                    <p class="text-xs text-gray-500 font-mono mt-1 italic">No. Reg. MET. {{ $asesor->nomor_regis ?? 'N/A' }}</p>
                                    {{-- MENAMPILKAN TANGGAL DI BAWAH ASESOR --}}
                                    <p class="text-xs text-gray-500 font-mono mt-2 italic">Tanggal: {{ $tanggal_pelaksanaan }}</p>
                                </div>
                            </div>

                            {{-- ASESI --}}
                            <div class="flex flex-col items-center">
                                <label class="block text-sm font-medium text-gray-700 mb-3 font-semibold">Tanda Tangan Asesi</label>
                                <div class="w-full max-w-[400px] h-48 bg-white border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center relative group">
                                    @if($tanda_tangan_asesi_path)
                                        <img src="{{ asset('storage/' . $tanda_tangan_asesi_path) }}" alt="Ttd Asesi" class="max-h-full object-contain p-2">
                                    @else
                                        <svg class="h-10 w-10 text-blue-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732"></path></svg>
                                        <p class="text-sm font-bold text-blue-600 font-semibold">Klik untuk Tanda Tangan</p>
                                        <p class="text-xs text-gray-400 mt-1 italic tracking-tighter">Konfirmasi Asesi</p>
                                    @endif
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-base text-gray-900 underline decoration-1 font-medium">{{ $asesi->nama_lengkap ?? '-' }}</p>
                                    {{-- MENAMPILKAN TANGGAL DI BAWAH ASESI --}}
                                    <p class="text-xs text-gray-500 font-mono mt-2 italic">Tanggal: {{ $tanggal_pelaksanaan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-white px-4 pb-8">
                        <button type="button" onclick="history.back()" class="px-10 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200 transition-all shadow-sm">Sebelumnya</button>
                        <button type="submit" class="px-10 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-colors focus:ring-2 focus:ring-blue-500">Simpan Penilaian Wawancara</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>

<style>
    .table-formal th, .table-formal td { border: 1px solid black !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    body, * { font-family: 'Poppins', sans-serif !important; }
</style>