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
        $currentPage     = request()->get('page', 1);
        $totalKelompok   = $kelompokPekerjaan->count();
        $currentKelompok = $kelompokPekerjaan[$currentPage - 1] ?? null;

        // PERBAIKAN UTAMA: Filtering dipindah ke atas & hanya sekali dijalankan
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
                                            {{ ($jenisTuk->jenis_tuk ?? '') == 'Tempat Kerja' ? 'checked' : '' }} disabled
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

                @if ($currentKelompok)
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
                                        <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            <th class="p-3 border-b border-gray-300 w-16 text-center">No</th>
                                            <th class="p-3 border-b border-gray-300">Kode Unit</th>
                                            <th class="p-3 border-b border-gray-300">Judul Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($currentKelompok->unitKompetensi ?? [] as $i => $unit)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="p-3 text-center font-medium text-gray-700">{{ $i + 1 }}</td>
                                                <td class="p-3 font-medium text-gray-700">{{ $unit->kode_unit ?? '-' }}</td>
                                                <td class="p-3 text-gray-700">{{ $unit->judul_unit ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="p-6 text-center text-gray-500">Tidak ada unit kompetensi</td>
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
                                        <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            <th rowspan="2" class="p-3 border-b border-r border-gray-300 w-16 text-center">No</th>
                                            <th rowspan="2" class="p-3 border-b border-r border-gray-300 text-center">Pertanyaan</th>
                                            <th colspan="2" class="p-3 border-b border-gray-300 text-center">Pencapaian</th>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 border-b border-r border-gray-300 w-20 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">K</th>
                                            <th class="p-2 border-b border-gray-300 w-20 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">BK</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($pertanyaanFiltered as $index => $pertanyaan)
                                            @php $p = $pertanyaan->pencapaian; @endphp
                                            <tr class="hover:bg-blue-50/30 transition-colors">
                                                <td class="p-3 text-center font-semibold text-gray-700 border-r border-gray-200 align-top">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="p-3 text-sm text-gray-700 border-r border-gray-200 align-top">
                                                    {{ $pertanyaan->pertanyaan ?? '-' }}
                                                </td>
                                                <td class="p-3 text-center border-r border-gray-200 align-top">
                                                    <input type="radio" name="pencapaian_{{ $pertanyaan->id_IA03 }}" value="1"
                                                        {{ in_array($p, [1, '1']) ? 'checked' : '' }} disabled
                                                        class="w-5 h-5 text-green-600 opacity-100 cursor-default">
                                                </td>
                                                <td class="p-3 text-center align-top">
                                                    <input type="radio" name="pencapaian_{{ $pertanyaan->id_IA03 }}" value="0"
                                                        {{ in_array($p, [0, '0']) ? 'checked' : '' }} disabled
                                                        class="w-5 h-5 text-red-600 opacity-100 cursor-default">
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-50/80">
                                                <td class="p-3 text-center font-semibold text-gray-700 border-r border-gray-200">Tanggapan</td>
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
                                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <p class="text-sm text-gray-700 font-medium leading-relaxed flex-1">
                                                {{ $pertanyaan->pertanyaan ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="flex gap-3 mb-3">
                                            <label class="flex-1 flex items-center justify-center bg-white border-2 rounded-lg px-3 py-2 cursor-default
                                                {{ in_array($p, [1, '1']) ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                                <input type="radio" name="pencapaian_mob_{{ $pertanyaan->id }}" value="1"
                                                    {{ in_array($p, [1, '1']) ? 'checked' : '' }} disabled
                                                    class="w-4 h-4 text-green-600 mr-2 opacity-100 cursor-default">
                                                <span class="text-sm font-semibold text-gray-700">K</span>
                                            </label>
                                            <label class="flex-1 flex items-center justify-center bg-white border-2 rounded-lg px-3 py-2 cursor-default
                                                {{ in_array($p, [0, '0']) ? 'border-red-600 bg-red-50' : 'border-gray-300' }}">
                                                <input type="radio" name="pencapaian_mob_{{ $pertanyaan->id }}" value="0"
                                                    {{ in_array($p, [0, '0']) ? 'checked' : '' }} disabled
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
                            <a href="{{ $backUrl ?? $trackerUrl }}"
                                class="w-48 py-3 bg-red-500 text-white font-bold rounded-full shadow-sm hover:bg-red-600 transition text-center inline-block items-center justify-center">
                                Kembali
                            </a>
                        @endif
                    </div>

                    <div>
                        @if ($currentPage < $totalKelompok)
                            <a href="?page={{ $currentPage + 1 }}"
                                class="w-48 py-3 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition text-center inline-block">
                                Selanjutnya
                            </a>
                        @else
                            <a href="{{ $backUrl ?? $trackerUrl }}"
                                class="w-48 py-3 bg-green-500 text-white font-bold rounded-full shadow-lg hover:bg-green-600 transition text-center inline-block">
                                Selesai
                            </a>
                        @endif
                    </div>

                </div>

            </div>
        </main>
    </div>
</body>

</html>