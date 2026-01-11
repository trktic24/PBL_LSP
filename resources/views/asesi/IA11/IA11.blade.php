<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.11. Ceklist Reviu Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        @layer base {
            body {
                font-family: 'Poppins', sans-serif;
            }
        }

        /* Style untuk checkbox Ya/Tidak yang lebih besar */
        .custom-radio {
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
</head>

<body class="">
    {{-- START: WRAPPER UTAMA UNTUK FIXED SIDEBAR DAN SCROLLABLE CONTENT --}}
    <div class="flex h-screen bg-gray-100">

        {{-- Sidebar Component (Fixed di kiri) --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- Main Content Area (Scrollable) --}}
        <main class="flex-1 p-8 md:p-12 overflow-y-auto">
            <div class="max-w-6xl mx-auto">

                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 text-center mb-4 tracking-wide">
                        Ceklist Reviu Produk (CRP)
                    </h1>
                    <div class="w-full border-b-2 border-gray-300 mb-6"></div>
                </div>

                {{-- Asumsi data seperti $asesi, $asesor, $skema, $ia11, $trackerUrl dilewatkan dari IA11Controller::show --}}
                @php
                    // Contoh dummy data jika Anda menguji tanpa controller penuh:
                    $asesi = $asesi ?? (object) ['nama_lengkap' => 'Nama Asesi'];
                    $asesor = $asesor ?? (object) ['nama_lengkap' => 'Nama Asesor', 'nomor_regis' => '-'];
                    $skema = $skema ?? (object) ['nama_skema' => 'Skema Sertifikasi', 'nomor_skema' => 'XXX/YYY'];
                    $tanggal = $ia11->dataSertifikasiAsesi->tanggal_pelaksanaan ?? '-';
                    $spesifikasiUmum =
                        $ia11->spesifikasiProduk ?? (object) ['dimensi_produk' => '-', 'berat_produk' => '-'];
                    $trackerUrl = $trackerUrl ?? '/dashboard';

                    // Jenis TUK
                    $jenisTuk = $ia11->dataSertifikasiAsesi->jenis_tuk ?? '-';

                    // Variabel untuk data spesifikasi dan performa
                    $pencapaianSpesifikasi = $ia11->pencapaianSpesifikasi ?? collect();
                    $pencapaianPerforma = $ia11->pencapaianPerforma ?? collect();

                    // Unit kompetensi dari skema (asumsi relasi sudah ada)
                    $unitKompetensi = $skema->unitKompetensi ?? collect();

                    // === AMBIL TANDA TANGAN ASESI ===
                    $ttdAsesi = null;
                    if ($asesi->tanda_tangan ?? false) {
                        $pathAsesi = storage_path('app/private_uploads/' . $asesi->tanda_tangan);
                        if (file_exists($pathAsesi)) {
                            $ttdAsesi = 'data:image/png;base64,' . base64_encode(file_get_contents($pathAsesi));
                        }
                    }

                    // === AMBIL TANDA TANGAN ASESOR ===
                    $ttdAsesor = null;
                    if ($asesor->tanda_tangan ?? false) {
                        $pathAsesor = storage_path('app/private_uploads/' . $asesor->tanda_tangan);
                        if (file_exists($pathAsesor)) {
                            $ttdAsesor = 'data:image/png;base64,' . base64_encode(file_get_contents($pathAsesor));
                        }
                    }
                @endphp

                {{-- INFORMASI ASESMENT - VERSI CANTIK (SAMA KAYAK IA03) --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Informasi Asesmen
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kiri -->
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
                                <div class="flex flex-wrap gap-6 mt-2">
                                    <label class="flex items-center text-gray-900 font-medium text-sm">
                                        <input type="radio" name="tuk" value="Sewaktu" disabled
                                            {{ ($jenisTuk?->jenis_tuk ?? ($jenisTuk ?? '')) === 'Sewaktu' ? 'checked' : '' }}
                                            class="w-4 h-4 rounded-full border-gray-300 mr-2 focus:ring-0 cursor-default">
                                        Sewaktu
                                    </label>
                                    <label class="flex items-center text-gray-900 font-medium text-sm">
                                        <input type="radio" name="tuk" value="Tempat Kerja" disabled
                                            {{ ($jenisTuk?->jenis_tuk ?? ($jenisTuk ?? '')) === 'Tempat Kerja' ? 'checked' : '' }}
                                            class="w-4 h-4 rounded-full border-gray-300 mr-2 focus:ring-0 cursor-default">
                                        Tempat Kerja
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kanan -->
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
                                <p class="text-gray-900 font-semibold mt-1">
                                    {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KELOMPOK PEKERJAAN & UNIT KOMPETENSI --}}
                <div
                    class="bg-gradient-to-br from-indigo-50 to-blue-50 p-6 rounded-xl shadow-lg border-2 border-indigo-200 mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-600 text-white p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-indigo-900">
                            Kelompok Pekerjaan & Unit Kompetensi
                        </h3>
                    </div>

                    <div class="bg-white rounded-lg p-5 shadow-md">
                        <div class="mb-4 pb-3 border-b border-gray-200">
                            <label class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Kelompok
                                Pekerjaan</label>
                        </div>

                        @if ($unitKompetensi->isNotEmpty())
                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Unit
                                    Kompetensi</label>
                                <div class="grid gap-3">
                                    @foreach ($unitKompetensi as $index => $unit)
                                        <div
                                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border-l-4 border-indigo-500 hover:shadow-md transition-shadow">
                                            <div class="flex items-start">
                                                <span
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-sm mr-3 flex-shrink-0">
                                                    {{ $index + 1 }}
                                                </span>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span
                                                            class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Kode
                                                            Unit</span>
                                                        <span
                                                            class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-mono rounded">{{ $unit->kode_unit ?? '-' }}</span>
                                                    </div>
                                                    <p class="text-sm font-semibold text-gray-800 leading-relaxed">
                                                        {{ $unit->judul_unit ?? 'Unit Kompetensi ' . ($index + 1) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 italic">Tidak ada unit kompetensi yang tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
                    <div class="p-6 bg-blue-50 border-b border-blue-200">
                        <h2 class="text-2xl font-bold text-blue-800">
                            1. Rancangan Produk atau Data Teknis Produk
                        </h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                        {{-- Baris 1 --}}
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama produk yang dihasilkan</label>
                            <p class="text-gray-900 font-semibold mt-1">{{ $ia11->nama_produk ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Standar Industri atau tempat
                                kerja</label>
                            <p class="text-gray-900 font-semibold mt-1">{{ $ia11->standar_industri ?? '-' }}</p>
                        </div>

                        {{-- Baris 2 --}}
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Rancangan Produk atau Data Teknis
                                Produk</label>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 min-h-[50px]">
                                <p class="text-gray-800">
                                    {{ $ia11->rancangan_produk ?? 'Tidak ada deskripsi rancangan produk.' }}</p>
                            </div>
                        </div>

                        {{-- Baris 3 --}}
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal
                                pengoperasian/penggunaan</label>
                            <p class="text-gray-900 font-semibold mt-1">
                                {{ $ia11->tanggal_pengoperasian ? \Carbon\Carbon::parse($ia11->tanggal_pengoperasian)->format('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Gambar produk (jika ada)</label>
                            {{-- Logika sederhana untuk menampilkan Gambar Produk --}}
                            @if ($ia11->gambar_produk ?? false)
                                <img src="{{ asset($ia11->gambar_produk) }}" alt="Gambar Produk" class="w-40 h-auto">
                            @else
                                <p class="text-gray-500 italic mt-1">- Tidak ada gambar produk -</p>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Spesifikasi Umum & Teknis --}}
                    <div class="p-6 pt-0 grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Spesifikasi Umum --}}
                        <div class="md:col-span-1 bg-gray-50 rounded-xl border border-gray-200 p-4">
                            <h4 class="text-base font-bold text-gray-800 mb-3 border-b border-gray-300 pb-2">
                                Spesifikasi
                                Produk Umum</h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-xs font-medium text-gray-500">Dimensi produk:</label>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $spesifikasiUmum->dimensi_produk ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500">Berat produk:</label>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $spesifikasiUmum->berat_produk ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Bahan Produk --}}
                        <div class="md:col-span-1 bg-gray-50 rounded-xl border border-gray-200 p-4">
                            <h4 class="text-base font-bold text-gray-800 mb-3 border-b border-gray-300 pb-2">Bahan
                                Produk
                            </h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                                @forelse ($ia11->bahanProduk ?? [] as $bahan)
                                    <li>{{ $bahan->nama_bahan ?? '-' }}</li>
                                @empty
                                    <li class="text-gray-500 italic">Tidak ada data bahan.</li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Spesifikasi Teknis --}}
                        <div class="md:col-span-1 bg-gray-50 rounded-xl border border-gray-200 p-4">
                            <h4 class="text-base font-bold text-gray-800 mb-3 border-b border-gray-300 pb-2">Data
                                Teknis
                            </h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                                @forelse ($ia11->spesifikasiTeknis ?? [] as $teknis)
                                    <li>{{ $teknis->data_teknis ?? '-' }}</li>
                                @empty
                                    <li class="text-gray-500 italic">Tidak ada data teknis.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-indigo-800">
                    {{-- 2. Pencapaian Spesifikasi dan Performa Produk --}}
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-indigo-200">
                            <h2 class="text-2xl font-bold text-indigo-900">
                                2. Pencapaian Spesifikasi dan Performa Produk
                            </h2>
                        </div>

                        <div class="p-6">
                            <div class="overflow-x-auto rounded-lg border border-gray-300">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-12">
                                                No</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Kelompok</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Item Reviu Produk</th>
                                            <th colspan="2"
                                                class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Hasil Review</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Catatan Temuan</th>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <th colspan="3"></th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-600">Ya</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-600">Tidak
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 text-sm">

                                        {{-- SPESIFIKASI PRODUK --}}
                                        @forelse($ia11->pencapaianSpesifikasi as $index => $item)
                                            <tr class="{{ $loop->first ? '' : 'border-t-2 border-gray-300' }}">
                                                <td class="px-4 py-3 text-center text-gray-700 font-medium">
                                                    {{ $loop->iteration }}
                                                </td>
                                                @if ($loop->first)
                                                    <td rowspan="{{ $ia11->pencapaianSpesifikasi->count() }}"
                                                        class="px-4 py-3 text-indigo-700 font-semibold bg-indigo-50 align-middle text-sm">
                                                        Spesifikasi Produk
                                                    </td>
                                                @endif
                                                <td class="px-4 py-3 text-gray-900">
                                                    {{ $item->spesifikasiItem?->deskripsi_spesifikasi ??
                                                        ($item->spesifikasi?->deskripsi_spesifikasi ?? 'Item tidak ditemukan') }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="radio" disabled
                                                        {{ $item->hasil_reviu == 1 ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 border-gray-300 focus:ring-0 cursor-default">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="radio" disabled
                                                        {{ $item->hasil_reviu == 0 ? 'checked' : '' }}
                                                        class="w-4 h-4 text-red-600 border-gray-300 focus:ring-0 cursor-default">
                                                </td>
                                                <td class="px-4 py-3 text-gray-600">
                                                    {{ $item->catatan_temuan ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                                                    Tidak ada data pencapaian spesifikasi produk
                                                </td>
                                            </tr>
                                        @endforelse

                                        {{-- PERFORMA PRODUK --}}
                                        @forelse($ia11->pencapaianPerforma as $index => $item)
                                            <tr
                                                class="{{ $loop->first ? 'border-t-4 border-gray-400' : 'border-t-2 border-gray-300' }}">
                                                <td class="px-4 py-3 text-center text-gray-700 font-medium">
                                                    {{ $loop->iteration + $ia11->pencapaianSpesifikasi->count() }}
                                                </td>
                                                @if ($loop->first)
                                                    <td rowspan="{{ $ia11->pencapaianPerforma->count() }}"
                                                        class="px-4 py-3 text-green-700 font-semibold bg-green-50 align-middle text-sm">
                                                        Performa Produk
                                                    </td>
                                                @endif
                                                <td class="px-4 py-3 text-gray-800">
                                                    {{ $item->performaItem?->deskripsi_performa ?? ($item->performa?->deskripsi_performa ?? 'Item tidak ditemukan') }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="radio" disabled
                                                        {{ $item->hasil_reviu == 1 ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 border-gray-300 focus:ring-0 cursor-default">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="radio" disabled
                                                        {{ $item->hasil_reviu == 0 ? 'checked' : '' }}
                                                        class="w-4 h-4 text-red-600 border-gray-300 focus:ring-0 cursor-default">
                                                </td>
                                                <td class="px-4 py-3 text-gray-600">
                                                    {{ $item->catatan_temuan ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                                                    Tidak ada data pencapaian performa produk
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
                        <div class="p-6 bg-yellow-50 border-b border-yellow-200">
                            <h2 class="text-2xl font-bold text-yellow-800">
                                3. Rekomendasi Asesor
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-6 mb-6">
                                <label class="flex items-center text-lg font-bold text-gray-900">
                                    <input type="radio" name="rekomendasi" value="kompeten" disabled
                                        {{ ($ia11->rekomendasi ?? 'kompeten') === 'kompeten' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 mr-3 opacity-100 cursor-default">
                                    <span>Direkomendasikan KOMPETEN</span>
                                </label>
                                <label class="flex items-center text-lg font-bold text-gray-900">
                                    <input type="radio" name="rekomendasi" value="observasi" disabled
                                        {{ ($ia11->rekomendasi ?? 'kompeten') === 'observasi' ? 'checked' : '' }}
                                        class="w-5 h-5 text-red-600 mr-3 opacity-100 cursor-default">
                                    <span>Direkomendasikan OBSERVASI LANGSUNG</span>
                                </label>
                            </div>

                            {{-- Tanda Tangan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-gray-200">
                                <div>
                                    <h4 class="text-base font-bold text-gray-700 mb-3">Asesi:
                                        {{ $asesi->nama_lengkap ?? '-' }}</h4>
                                    <div
                                        class="bg-gray-100 rounded-lg p-3 min-h-[120px] border-2 border-dashed border-gray-300 text-center flex items-center justify-center">
                                        @if ($ttdAsesi)
                                            <img src="{{ $ttdAsesi }}"
                                                alt="Tanda Tangan Asesi" class="max-h-28">
                                        @else
                                            <p class="text-sm text-gray-500 italic">Tanda tangan belum diunggah</p>
                                        @endif
                                    </div>
                                    <p class="text-center text-xs text-gray-500 mt-2">Tanda Tangan Asesi</p>
                                </div>

                                <div>
                                    <h4 class="text-base font-bold text-gray-700 mb-3">Asesor:
                                        {{ $asesor->nama_lengkap ?? '-' }}</h4>
                                    <div
                                        class="bg-gray-100 rounded-lg p-3 min-h-[120px] border-2 border-dashed border-gray-300 text-center flex items-center justify-center">
                                        @if ($ttdAsesor)
                                            <img src="{{ $ttdAsesor }}"
                                                alt="Tanda Tangan Asesor" class="max-h-28">
                                        @else
                                            <p class="text-sm text-gray-500 italic">Tanda tangan belum diunggah</p>
                                        @endif
                                    </div>
                                    <p class="text-center text-xs text-gray-500 mt-2">Tanda Tangan Asesor</p>
                                </div>
                            </div>


                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end pb-8">
                                <a href="{{ $trackerUrl }}"
                                    class="w-48 py-3 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none transform hover:-translate-y-0.5 text-center inline-block">
                                    Kembali
                                </a>
                            </div>
        </main>

    </div> {{-- End flex h-screen wrapper --}}
</body>

</html>
