<x-app-layout>
    {{-- 1. IMPORT FONT POPPINS --}}
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            .font-poppins {
                font-family: 'Poppins', sans-serif;
            }
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

                {{-- BAGIAN 1: HEADER INFORMASI (Wajib Ada) --}}
                <div
                    class="grid grid-cols-12 gap-y-3 gap-x-4 text-sm mb-8 border border-gray-300 p-5 rounded-lg bg-gray-50">

                    {{-- Skema --}}
                    <div class="col-span-2 font-bold text-gray-700">Skema Sertifikasi</div>
                    <div class="col-span-10 text-gray-900 font-semibold">
                        : {{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}
                        <span
                            class="font-normal text-gray-500">({{ $sertifikasi->jadwal->skema->nomor_skema ?? '-' }})</span>
                    </div>

                    {{-- TUK --}}
                    <div class="col-span-2 font-bold text-gray-700">TUK</div>
                    <div class="col-span-10 text-gray-900">
                        : {{ $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-' }}
                    </div>

                    {{-- Asesor --}}
                    <div class="col-span-2 font-bold text-gray-700">Nama Asesor</div>
                    <div class="col-span-10 text-gray-900">: {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}
                    </div>

                    {{-- Asesi --}}
                    <div class="col-span-2 font-bold text-gray-700">Nama Asesi</div>
                    <div class="col-span-10 text-gray-900">:
                        {{ $sertifikasi->asesi->nama_lengkap ?? Auth::user()->name }}</div>

                    {{-- Tanggal --}}
                    <div class="col-span-2 font-bold text-gray-700">Tanggal</div>
                    <div class="col-span-10 text-gray-900">: {{ $sertifikasi->tanggal_pelaksanaan ?? date('d-m-Y') }}
                    </div>
                </div>

                {{-- === BAGIAN BARU: DAFTAR UNIT KOMPETENSI (SESUAI GAMBAR) === --}}
                <div class="mb-8 space-y-8">

                    {{-- Kita loop per Kelompok Pekerjaan yang ada di Skema --}}
                    @foreach ($sertifikasi->jadwal->skema->kelompokPekerjaan as $indexKelompok => $kelompok)
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">

                            {{-- HEADER BIRU MUDA --}}
                            <div class="bg-blue-50 p-6 border-b border-blue-100">
                                {{-- Mengambil kolom 'judul_unit' di tabel kelompok_pekerjaan sesuai request lu --}}
                                <h2 class="text-xl font-bold text-blue-900 mb-1">
                                    {{ $kelompok->judul_unit ?? 'Kelompok Pekerjaan ' . ($indexKelompok + 1) }}
                                </h2>
                                <p class="text-blue-600 text-sm font-medium">
                                    Kelompok Pekerjaan {{ $loop->iteration }} dari
                                    {{ $sertifikasi->jadwal->skema->kelompokPekerjaan->count() }}
                                </p>
                            </div>

                            {{-- BODY TABEL UNIT --}}
                            <div class="p-6 bg-white">
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
                                            {{-- Loop Unit Kompetensi di dalam Kelompok ini --}}
                                            @forelse($kelompok->unitKompetensi as $indexUnit => $unit)
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 text-center font-medium">{{ $indexUnit + 1 }}
                                                    </td>
                                                    <td class="px-6 py-4 font-mono text-gray-900 font-semibold">
                                                        {{ $unit->kode_unit }}
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-800">
                                                        {{ $unit->judul_unit }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3"
                                                        class="px-6 py-4 text-center text-gray-400 italic">
                                                        Tidak ada unit kompetensi di kelompok ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- BAGIAN 2: TABEL REKAPAN JAWABAN (Langsung Loop Data ia07) --}}
                <div class="overflow-hidden border border-black rounded-sm">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-200 text-gray-800">
                            <tr>
                                <th class="border border-black p-3 w-12 text-center" rowspan="2">No</th>
                                <th class="border border-black p-3 text-left" rowspan="2">Pertanyaan & Jawaban</th>
                                <th class="border border-black p-2 text-center w-32" colspan="2">Rekomendasi
                                    Pencapaian</th>
                            </tr>
                            <tr>
                                <th class="border border-black p-1 text-center w-16 bg-green-100">Ya (K)</th>
                                <th class="border border-black p-1 text-center w-16 bg-red-100">Tidak (BK)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            {{-- Loop Data Langsung dari Relasi --}}
                            @forelse($sertifikasi->ia07 as $index => $item)
                                <tr>
                                    {{-- NOMOR --}}
                                    <td class="border border-black p-3 text-center align-top font-bold">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- KONTEN: PERTANYAAN & JAWABAN --}}
                                    <td class="border border-black p-4 align-top">
                                        {{-- Pertanyaan --}}
                                        <div class="mb-3">
                                            <span
                                                class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pertanyaan:</span>
                                            <p class="text-gray-900 font-medium mt-1">{{ $item->pertanyaan }}</p>
                                        </div>

                                        <hr class="border-dashed border-gray-300 my-2">

                                        {{-- Jawaban Asesi --}}
                                        <div class="mt-2 bg-blue-50 p-3 rounded border border-blue-100">
                                            <span
                                                class="text-xs font-bold text-blue-600 uppercase tracking-wide">Jawaban
                                                Asesi:</span>
                                            <p class="text-gray-800 mt-1 whitespace-pre-line">
                                                {{ $item->jawaban_asesi ?? '-' }}</p>
                                        </div>
                                    </td>

                                    {{-- CHECKBOX PENCAPAIAN (YA) --}}
                                    <td class="border border-black p-2 text-center align-middle bg-gray-50">
                                        <div class="flex justify-center">
                                            {{-- Cek database: kalau enum 'ya', centang --}}
                                            <input type="checkbox" disabled
                                                {{ $item->pencapaian === 'ya' ? 'checked' : '' }}
                                                class="w-6 h-6 text-green-600 border-gray-400 rounded focus:ring-green-500 disabled:opacity-100 cursor-not-allowed">
                                        </div>
                                    </td>

                                    {{-- CHECKBOX PENCAPAIAN (TIDAK) --}}
                                    <td class="border border-black p-2 text-center align-middle bg-gray-50">
                                        <div class="flex justify-center">
                                            {{-- Cek database: kalau enum 'tidak', centang --}}
                                            <input type="checkbox" disabled
                                                {{ $item->pencapaian === 'tidak' ? 'checked' : '' }}
                                                class="w-6 h-6 text-red-600 border-gray-400 rounded focus:ring-red-500 disabled:opacity-100 cursor-not-allowed">
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500 italic border border-black">
                                        Data rekaman IA.07 tidak ditemukan untuk sertifikasi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER TOMBOL --}}
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ $backUrl ?? ($sertifikasi ? '/tracker/' . $sertifikasi->id_jadwal : '/dashboard') }}"
                        class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                        &larr; Kembali ke Tracker
                    </a>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>
