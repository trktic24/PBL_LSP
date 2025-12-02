<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan Mendukung Observasi</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        @layer base {
            body {
                font-family: 'Poppins', sans-serif;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    @php
        $currentPage = request()->get('page', 1);
        $totalKelompok = $kelompokPekerjaan->count();

        // Halaman terakhir adalah untuk catatan umpan balik
        $totalPages = $totalKelompok + 1;
        $isUmpanBalikPage = $currentPage > $totalKelompok;

        $currentKelompok = !$isUmpanBalikPage ? $kelompokPekerjaan[$currentPage - 1] ?? null : null;

        // Filtering pertanyaan
        $pertanyaanFiltered = $currentKelompok
            ? $pertanyaanIA03->where('kelompok_pekerjaan_id', $currentKelompok->id)->values()
            : collect();
    @endphp

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 md:p-12 bg-white overflow-y-auto">
            <div class="max-w-6xl mx-auto">

                <!-- JUDUL -->
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 text-center mb-4 tracking-wide">
                        Pertanyaan Mendukung Observasi
                    </h1>
                    <div class="w-full border-b-2 border-gray-300 mb-6"></div>
                </div>

                <!-- Informasi Asesmen -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Informasi Asesmen
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Skema Sertifikasi</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $skema->nama_skema ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">No. Skema Sertifikasi</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $skema->nomor_skema ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">TUK</label>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    <label class="flex items-center text-gray-900 font-medium text-sm">
                                        <input type="radio" name="tuk" value="Sewaktu"
                                            {{ ($jenisTuk->jenis_tuk ?? '') == 'Sewaktu' ? 'checked' : '' }} disabled
                                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                                        Sewaktu
                                    </label>
                                    <label class="flex items-center text-gray-900 font-medium text-sm">
                                        <input type="radio" name="tuk" value="Tempat Kerja"
                                            {{ ($jenisTuk->jenis_tuk ?? '') == 'Tempat Kerja' ? 'checked' : '' }}
                                            disabled
                                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                                        Tempat Kerja
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Asesor</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $asesor->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Asesi</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $asesi->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Pelaksanaan</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $tanggal ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!$isUmpanBalikPage && $currentKelompok)
                    {{-- KONTEN KELOMPOK PEKERJAAN --}}
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">

                        <!-- Header Kelompok -->
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                            <h2 class="text-2xl font-bold text-blue-800 mb-1">
                                Kelompok Pekerjaan {{ $currentPage }}
                            </h2>
                            <p class="text-blue-600 text-sm font-medium">
                                Unit Kompetensi {{ $currentPage }} dari {{ $totalKelompok }}
                            </p>
                        </div>

                        <!-- Daftar Unit Kompetensi -->
                        <div class="p-6 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                            <div class="overflow-hidden rounded-xl border border-gray-300">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr
                                            class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            <th class="p-3 border-b border-gray-300 w-16 text-center">No</th>
                                            <th class="p-3 border-b border-gray-300">Kode Unit</th>
                                            <th class="p-3 border-b border-gray-300">Judul Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($currentKelompok->unitKompetensi ?? [] as $i => $unit)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="p-3 text-center font-medium text-gray-700">
                                                    {{ $i + 1 }}</td>
                                                <td class="p-3 font-medium text-gray-700">{{ $unit->kode_unit ?? '-' }}
                                                </td>
                                                <td class="p-3 text-gray-700">{{ $unit->judul_unit ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="p-6 text-center text-gray-500">Tidak ada unit
                                                    kompetensi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Daftar Pertanyaan -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Pertanyaan</h3>

                            <!-- DESKTOP VIEW -->
                            <div class="hidden md:block overflow-hidden rounded-xl border border-gray-300">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr
                                            class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            <th rowspan="2"
                                                class="p-3 border-b border-r border-gray-300 w-16 text-center">No</th>
                                            <th rowspan="2"
                                                class="p-3 border-b border-r border-gray-300 text-center">Pertanyaan
                                            </th>
                                            <th colspan="2" class="p-3 border-b border-gray-300 text-center">
                                                Pencapaian</th>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="p-2 border-b border-r border-gray-300 w-20 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                K</th>
                                            <th
                                                class="p-2 border-b border-gray-300 w-20 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                BK</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($pertanyaanFiltered as $index => $pertanyaan)
                                            @php $p = $pertanyaan->pencapaian; @endphp
                                            <tr class="hover:bg-blue-50/30 transition-colors">
                                                <td
                                                    class="p-3 text-center font-semibold text-gray-700 border-r border-gray-200 align-top">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td
                                                    class="p-3 text-sm text-gray-700 border-r border-gray-200 align-top">
                                                    {{ $pertanyaan->pertanyaan ?? '-' }}
                                                </td>
                                                <td class="p-3 text-center border-r border-gray-200 align-top">
                                                    <input type="radio" name="pencapaian_{{ $pertanyaan->id_IA03 }}"
                                                        value="1" {{ in_array($p, [1, '1']) ? 'checked' : '' }}
                                                        disabled
                                                        class="w-5 h-5 text-green-600 opacity-100 cursor-default">
                                                </td>
                                                <td class="p-3 text-center align-top">
                                                    <input type="radio" name="pencapaian_{{ $pertanyaan->id_IA03 }}"
                                                        value="0" {{ in_array($p, [0, '0']) ? 'checked' : '' }}
                                                        disabled
                                                        class="w-5 h-5 text-red-600 opacity-100 cursor-default">
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-50/80">
                                                <td
                                                    class="p-3 text-center font-semibold text-gray-700 border-r border-gray-200">
                                                    Tanggapan</td>
                                                <td colspan="3" class="p-3 text-sm text-gray-700">
                                                    {{ $pertanyaan->tanggapan ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-6 text-center text-gray-500">
                                                    Tidak ada pertanyaan untuk kelompok pekerjaan ini
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- MOBILE VIEW -->
                            <div class="md:hidden space-y-4">
                                @forelse ($pertanyaanFiltered as $index => $pertanyaan)
                                    @php $p = $pertanyaan->pencapaian; @endphp
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm">
                                        <div class="flex items-start gap-3 mb-3">
                                            <span
                                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <p class="text-sm text-gray-700 font-medium leading-relaxed flex-1">
                                                {{ $pertanyaan->pertanyaan ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="flex gap-3 mb-3">
                                            <label
                                                class="flex-1 flex items-center justify-center bg-white border-2 rounded-lg px-3 py-2 cursor-default
                                                {{ in_array($p, [1, '1']) ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                                <input type="radio" name="pencapaian_mob_{{ $pertanyaan->id }}"
                                                    value="1" {{ in_array($p, [1, '1']) ? 'checked' : '' }}
                                                    disabled
                                                    class="w-4 h-4 text-green-600 mr-2 opacity-100 cursor-default">
                                                <span class="text-sm font-semibold text-gray-700">K</span>
                                            </label>
                                            <label
                                                class="flex-1 flex items-center justify-center bg-white border-2 rounded-lg px-3 py-2 cursor-default
                                                {{ in_array($p, [0, '0']) ? 'border-red-600 bg-red-50' : 'border-gray-300' }}">
                                                <input type="radio" name="pencapaian_mob_{{ $pertanyaan->id }}"
                                                    value="0" {{ in_array($p, [0, '0']) ? 'checked' : '' }}
                                                    disabled
                                                    class="w-4 h-4 text-red-600 mr-2 opacity-100 cursor-default">
                                                <span class="text-sm font-semibold text-gray-700">BK</span>
                                            </label>
                                        </div>

                                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                                            <p class="text-xs font-semibold text-gray-600 mb-1">Tanggapan:</p>
                                            <p class="text-sm text-gray-700">{{ $pertanyaan->tanggapan ?? '-' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                                        <p class="text-gray-500">Tidak ada pertanyaan untuk kelompok pekerjaan ini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @elseif ($isUmpanBalikPage)
                    {{-- HALAMAN CATATAN UMPAN BALIK --}}
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">

                        <!-- Header Catatan -->
                        <div class="p-6 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                            <h2 class="text-2xl font-bold text-purple-800 mb-1">
                                Catatan Umpan Balik
                            </h2>
                            <p class="text-purple-600 text-sm font-medium">
                                Catatan dari asesor untuk seluruh proses asesmen
                            </p>
                        </div>

                        <!-- Konten Catatan -->
                        <div class="p-6 md:p-8">
                            <div class="bg-gray-50 rounded-xl border border-gray-300 p-6 md:p-8">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    Catatan Umpan Balik dari Asesor:
                                </label>
                                <div class="bg-white rounded-lg border border-gray-300 p-4 md:p-6 min-h-[200px]">
                                    @if ($catatanUmpanBalik && $catatanUmpanBalik->isNotEmpty())
                                        {{-- Menggunakan Unordered List (UL) untuk poin-poin --}}
                                        <ul class="list-disc list-inside space-y-2 pl-4">
                                            @foreach ($catatanUmpanBalik as $catatan)
                                                <li class="text-gray-800 leading-relaxed text-left">
                                                    {{ $catatan }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500 italic text-left">Tidak ada catatan umpan balik.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Info box --}}
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-sm text-gray-700">
                                        Catatan ini berisi feedback dan masukan dari asesor mengenai kinerja Anda selama
                                        proses asesmen.
                                        Gunakan catatan ini sebagai bahan evaluasi dan pengembangan diri.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- NAVIGATION BUTTONS -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between pb-8">

                    <div>
                        @if ($currentPage > 1)
                            <a href="?page={{ $currentPage - 1 }}"
                                class="w-48 py-3 bg-gray-300 text-gray-700 font-bold rounded-full shadow-sm hover:bg-gray-400 transition text-center inline-block">
                                Sebelumnya
                            </a>
                        @else
                            {{-- PERBAIKAN 1: Tombol "Kembali" di halaman pertama, langsung ke tracker --}}
                            <a href="{{ $trackerUrl }}"
                                class="w-48 py-3 bg-gray-300 text-gray-700 font-bold rounded-full shadow-sm hover:bg-gray-400 transition duration-200 focus:outline-none transform hover:-translate-y-0.5 text-center inline-block">
                                Kembali
                            </a>
                        @endif
                    </div>

                    <div>
                        @if ($currentPage < $totalPages)
                            <a href="?page={{ $currentPage + 1 }}"
                                class="w-48 py-3 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition text-center inline-block">
                                Selanjutnya
                            </a>
                        @else
                            {{-- PERBAIKAN 2: Tombol "Selesai" di halaman terakhir diubah menjadi "Kembali" ke tracker --}}
                            <a href="{{ $trackerUrl }}"
                                class="w-48 py-3 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none transform hover:-translate-y-0.5 text-center inline-block">
                                Kembali
                            </a>
                        @endif
                    </div>

                </div>

            </div>
        </main>
    </div>
</body>

</html>
