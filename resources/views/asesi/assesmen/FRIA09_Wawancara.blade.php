@php
    $user = Auth::user();
    
    // 1. Jika User adalah ASESI (Login sebagai peserta)
    if ($user->asesi) {
         $backUrl = url("/asesi/tracker/" . ($sertifikasi->id_jadwal ?? ''));
    } 
    
    // 2. Jika User adalah ADMIN (Login sebagai admin/asesor)
    else {
         // Rakit URL Manual agar tidak 404
         $baseUrl = "/admin/asesi/" . $sertifikasi->id_asesi . "/tracker";
         // Tambahkan query string sertifikasi_id
         $backUrl = url($baseUrl) . "?sertifikasi_id=" . $sertifikasi->id_data_sertifikasi_asesi;
    }
@endphp

<x-app-layout>
    {{-- Container Utama --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden bg-gray-100"> 
        {{-- 1. SIDEBAR (Desktop Only) --}}
            <div class="hidden md:block md:w-80 flex-shrink-0">
                <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" :backUrl="url('/asesi/tracker/' . $sertifikasi->id_jadwal)" />
            </div>

            {{-- 2. HEADER MOBILE (Data Dinamis) --}}
            @php
                $gambarSkema =
                    $sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar
                        ? asset('storage/' . $sertifikasi->jadwal->skema->gambar)
                        : asset('images/default_pic.jpeg');
            @endphp

            <x-mobile_header 
                :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'" 
                :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')" 
                :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'" 
                :image="$gambarSkema"
                :backUrl="url('/asesi/tracker/' . $sertifikasi->id_jadwal)" 
            />

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 bg-gray-100 overflow-y-auto">
            <div class="max-w-7xl mx-auto p-8 lg:p-12 content-font-base bg-white shadow-lg rounded-xl my-8">

                {{-- HEADER: Tetap Center tapi font lebih pro --}}
                <div class="mb-10 text-center border-b-2 border-gray-200 pb-6">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2 uppercase tracking-wide">FR.IA.09. Pertanyaan Wawancara</h1>
                    <p class="text-gray-500 font-medium">Rekaman pertanyaan wawancara dan kesimpulan penilaian.</p>
                </div>

                {{-- INFORMASI UMUM: Dikasih Border Box biar Rapi --}}
                <div class="mb-10 bg-gray-50 rounded-lg border border-gray-300 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-[200px_auto] gap-y-3 text-sm">
                        
                        <div class="font-bold text-gray-700">Skema Sertifikasi</div>
                        <div class="text-gray-900 font-medium">: {{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}</div>
                        
                        <div class="font-bold text-gray-700">TUK</div>
                        <div class="text-gray-900 font-medium">: {{ $jenis_tuk_db }}</div>
                        
                        <div class="font-bold text-gray-700">Nama Asesor</div>
                        <div class="text-gray-900 font-medium">: {{ $asesor->nama_lengkap ?? '-' }}</div>
                        
                        <div class="font-bold text-gray-700">Nama Asesi</div>
                        <div class="text-gray-900 font-medium">: {{ $asesi->nama_lengkap ?? '-' }}</div>
                        
                        <div class="font-bold text-gray-700">Tanggal</div>
                        <div class="text-gray-900 font-medium">: {{ $tanggal_pelaksanaan }}</div>
                    </div>
                </div>

                {{-- PANDUAN (Opsional, dibiarkan tapi style dirapikan) --}}
                <div class="mb-8 text-sm text-gray-600 italic border-l-4 border-blue-500 pl-4 py-2 bg-blue-50">
                    <span class="font-bold not-italic text-blue-800">Note:</span> Dokumen ini bersifat *Read Only* (Hanya Lihat). Berisi kesimpulan yang telah diinput oleh Asesor.
                </div>

                {{-- TABEL 1: UNIT KOMPETENSI (Fix Nested Loop) --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-l-4 border-gray-800 pl-3">Unit Kompetensi</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse border border-gray-400">
                            <thead class="bg-gray-800 text-white text-center">
                                <tr>
                                    <th rowspan="2" class="p-3 border border-gray-500 font-semibold w-1/5">Kelompok Pekerjaan</th>
                                    <th colspan="3" class="p-3 border border-gray-500 font-semibold">Unit Kompetensi</th>
                                </tr>
                                <tr>
                                    <th class="p-3 border border-gray-500 font-semibold w-12">No.</th>
                                    <th class="p-3 border border-gray-500 font-semibold w-1/4">Kode Unit</th>
                                    <th class="p-3 border border-gray-500 font-semibold">Judul Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $nomor_urut = 1; @endphp
                                @forelse ($data_unit_kompetensi as $group)
                                    @foreach ($group['units'] as $index => $unit)
                                    <tr class="bg-white hover:bg-gray-50 transition">
                                        {{-- Rowspan Logic --}}
                                        @if ($index === 0)
                                        <td rowspan="{{ count($group['units']) }}" class="p-3 border border-gray-400 text-center align-middle font-semibold text-gray-700 bg-gray-50">
                                            {{ $group['nama_kelompok'] }}
                                        </td>
                                        @endif
                                        <td class="p-3 border border-gray-400 text-center">{{ $nomor_urut++ }}.</td>
                                        <td class="p-3 border border-gray-400 text-center font-mono font-medium">{{ $unit['code'] }}</td>
                                        <td class="p-3 border border-gray-400">{{ $unit['title'] }}</td>
                                    </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center border border-gray-400 text-gray-500">Data unit tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 2: BUKTI PORTOFOLIO --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-l-4 border-gray-800 pl-3">Bukti Portofolio</h3>
                    <table class="w-full text-sm border-collapse border border-gray-400">
                        <thead class="bg-gray-800 text-white text-center">
                            <tr>
                                <th class="p-3 border border-gray-500 w-12 font-semibold">No.</th>
                                <th class="p-3 border border-gray-500 font-semibold">Bukti Portofolio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($merged_data as $index => $item)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="p-3 border border-gray-400 text-center align-top">{{ $index + 1 }}.</td>
                                <td class="p-3 border border-gray-400">
                                    <div class="font-medium text-gray-700">{{ $item->bukti_dasar }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 3: WAWANCARA --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-l-4 border-gray-800 pl-3">Daftar Pertanyaan Wawancara</h3>
                    <table class="w-full text-sm border-collapse border border-gray-400">
                        <thead class="bg-gray-800 text-white text-center">
                            <tr>
                                <th class="p-3 border border-gray-500 w-12 font-semibold">No.</th>
                                <th class="p-3 border border-gray-500 w-1/2 font-semibold">Pertanyaan</th>
                                <th class="p-3 border border-gray-500 font-semibold">Kesimpulan Jawaban Asesi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($merged_data as $index => $item)
                            <tr class="bg-white">
                                <td class="p-3 border border-gray-400 text-center align-top font-medium">{{ $index + 1 }}.</td>
                                <td class="p-3 border border-gray-400 align-top">
                                    <div class="text-gray-800 leading-relaxed">{{ $item->pertanyaan_teks }}</div>
                                </td>
                                <td class="p-3 border border-gray-400 align-top bg-gray-50">
                                    {{-- Readonly Textarea yang rapi --}}
                                    <textarea 
                                        readonly
                                        rows="4" 
                                        class="w-full bg-transparent border-gray-300 rounded focus:ring-0 focus:border-gray-300 text-gray-600 resize-none cursor-default text-sm"
                                    >{{ $item->kesimpulan_jawaban_asesi }}</textarea>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>

<style>
    /* Styling khusus biar tabel tegas tapi gak kaku */
    textarea { border: 1px solid #d1d5db; }
    textarea:focus { outline: none; box-shadow: none; }
    body, * { font-family: 'Poppins', sans-serif !important; }
</style>