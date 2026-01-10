{{--
Nama File: resources/views/asesmen/fr-mapa-01.blade.php
Deskripsi: Form FR.MAPA.01 menggunakan layout Wizard
--}}

@extends($layout ?? 'layouts.app-sidebar')
@php
    $jadwal = $sertifikasi->jadwal ?? null;
    $asesi = $sertifikasi->asesi ?? null;
    $backUrl = isset($backUrl) ? $backUrl : (isset($isMasterView) ? '#' : ($sertifikasi ? route('asesor.tracker', $sertifikasi->id_data_sertifikasi_asesi) : '#'));
@endphp

@section('content')

    {{-- Style khusus untuk tabel border hitam --}}
    <style>
        .form-table,
        .form-table td,
        .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>

    <x-header_form.header_form title="FR.MAPA.01. MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN" /><br>
    {{-- Header Mobile (Tombol Sidebar) --}}
    <div
        class="lg:hidden p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 mb-6 rounded-lg">
        <span class="font-bold">Form Assessment</span>
        <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>



    {{-- FORM START --}}
    <form action="{{ route('mapa01.store') }}" method="POST">
        @csrf
        @if($sertifikasi)
        <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
        @endif

        <div class="bg-white">

            {{-- KOP SURAT --}}

            {{-- INFO SKEMA --}}
            <div class="grid grid-cols-[250px_auto] gap-y-3 text-sm mb-10 text-gray-700">
                <div class="font-bold text-black">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</div>
                <div>
                    <div class="flex gap-2"><span class="font-semibold w-20">Judul</span> : {{ $skema->nama_skema ?? '-' }}</div>
                    <div class="flex gap-2"><span class="font-semibold w-20">Nomor</span> : {{ $skema->nomor_skema ?? '-' }}</div>
                </div>
            </div>

            {{-- BAGIAN 1: PENDEKATAN ASESMEN --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-4">1. Menentukan Pendekatan Asesmen</h3>
                <table class="w-full text-sm form-table mb-6">
                    <tbody>
                        {{-- 1. Asesi --}}
                        <tr>
                            <td rowspan="5" class="p-2 font-bold align-top w-10 text-center">1.1</td>
                            <td rowspan="5" class="p-2 font-bold align-top w-32">Asesi</td>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]"
                                        value="Hasil pelatihan dan atau pendidikan, Kurikulum & fasilitas telusur"
                                        {{ (in_array('Hasil pelatihan dan atau pendidikan, Kurikulum & fasilitas telusur', old('pendekatan_asesmen', $mapa01->pendekatan_asesmen ?? $template['pendekatan_asesmen'] ?? []))) ? 'checked' : '' }}> Hasil
                                    pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur
                                    terhadap standar kompetensi
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]"
                                        value="Hasil pelatihan - belum berbasis kompetensi"
                                        {{ (in_array('Hasil pelatihan - belum berbasis kompetensi', old('pendekatan_asesmen', $mapa01->pendekatan_asesmen ?? $template['pendekatan_asesmen'] ?? []))) ? 'checked' : '' }}> Hasil pelatihan dan / atau
                                    pendidikan, dimana kurikulum belum berbasis kompetensi.
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]"
                                        value="Pekerja berpengalaman - telusur"
                                        {{ (in_array('Pekerja berpengalaman - telusur', old('pendekatan_asesmen', $mapa01->pendekatan_asesmen ?? $template['pendekatan_asesmen'] ?? []))) ? 'checked' : '' }}> Pekerja berpengalaman, dimana berasal dari
                                    industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]"
                                        value="Pekerja berpengalaman - belum berbasis kompetensi"
                                        {{ (in_array('Pekerja berpengalaman - belum berbasis kompetensi', old('pendekatan_asesmen', $mapa01->pendekatan_asesmen ?? $template['pendekatan_asesmen'] ?? []))) ? 'checked' : '' }}> Pekerja berpengalaman,
                                    dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis
                                    kompetensi.
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-1" name="pendekatan_asesmen[]"
                                        value="Pelatihan / belajar mandiri"
                                        {{ (in_array('Pelatihan / belajar mandiri', old('pendekatan_asesmen', $mapa01->pendekatan_asesmen ?? $template['pendekatan_asesmen'] ?? []))) ? 'checked' : '' }}> Pelatihan / belajar mandiri atau otodidak.
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-2"></td>
                            <td class="p-2 font-bold">Tujuan Sertifikasi</td>
                            <td class="p-2">
                                @php
                                    $tujuan = old('tujuan_sertifikasi', $mapa01->tujuan_sertifikasi ?? $template['tujuan_sertifikasi'] ?? 'Sertifikasi');
                                @endphp
                                <div class="flex gap-6">
                                    <label><input type="radio" name="tujuan_sertifikasi" value="Sertifikasi" {{ $tujuan == 'Sertifikasi' ? 'checked' : '' }}>
                                        Sertifikasi</label>
                                    <label><input type="radio" name="tujuan_sertifikasi" value="PKT" {{ $tujuan == 'PKT' ? 'checked' : '' }}> PKT</label>
                                    <label><input type="radio" name="tujuan_sertifikasi" value="RPL" {{ $tujuan == 'RPL' ? 'checked' : '' }}> RPL</label>
                                    <label><input type="radio" name="tujuan_sertifikasi" value="Lainnya" {{ $tujuan == 'Lainnya' ? 'checked' : '' }}> Lainnya</label>
                                </div>
                            </td>
                        </tr>

                        {{-- Konteks Asesmen --}}
                        <tr>
                            <td rowspan="3" class="p-2"></td>
                            <td rowspan="3" class="p-2 font-bold align-top">Konteks Asesmen</td>
                            <td class="p-2">
                                <span class="font-bold">Lingkungan:</span>
                                @php
                                    $lingkungan = old('konteks_lingkungan', $mapa01->konteks_lingkungan ?? $template['konteks_lingkungan'] ?? []);
                                @endphp
                                <label class="ml-2"><input type="checkbox" name="konteks_lingkungan[]"
                                        value="Tempat kerja nyata" {{ in_array('Tempat kerja nyata', $lingkungan) ? 'checked' : '' }}> Tempat kerja nyata</label>
                                <label class="ml-4"><input type="checkbox" name="konteks_lingkungan[]"
                                        value="Tempat kerja simulasi" {{ in_array('Tempat kerja simulasi', $lingkungan) ? 'checked' : '' }}> Tempat kerja simulasi</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                @php
                                    $peluang = old('peluang_bukti', $mapa01->peluang_bukti ?? $template['peluang_bukti'] ?? []);
                                @endphp
                                <span class="font-bold">Peluang mengumpulkan bukti:</span>
                                <label class="ml-2"><input type="checkbox" name="peluang_bukti[]" value="Tersedia" {{ in_array('Tersedia', $peluang) ? 'checked' : '' }}>
                                    Tersedia</label>
                                <label class="ml-4"><input type="checkbox" name="peluang_bukti[]" value="Terbatas" {{ in_array('Terbatas', $peluang) ? 'checked' : '' }}>
                                    Terbatas</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                @php
                                    $pelaksana = old('pelaksana_asesmen', $mapa01->pelaksana_asesmen ?? $template['pelaksana_asesmen'] ?? []);
                                @endphp
                                <span class="font-bold">Siapa yang melakukan asesmen:</span>
                                <div class="mt-1">
                                    <label class="block"><input type="checkbox" name="pelaksana_asesmen[]"
                                            value="Lembaga Sertifikasi" {{ in_array('Lembaga Sertifikasi', $pelaksana) ? 'checked' : '' }}> Lembaga Sertifikasi</label>
                                    <label class="block"><input type="checkbox" name="pelaksana_asesmen[]"
                                            value="Organisasi Pelatihan" {{ in_array('Organisasi Pelatihan', $pelaksana) ? 'checked' : '' }}> Organisasi Pelatihan</label>
                                    <label class="block"><input type="checkbox" name="pelaksana_asesmen[]"
                                            value="Asesor Perusahaan" {{ in_array('Asesor Perusahaan', $pelaksana) ? 'checked' : '' }}> Asesor Perusahaan</label>
                                </div>
                            </td>
                        </tr>

                        {{-- Konfirmasi Orang Relevan --}}
                        @php
                            $konfirmasi = old('konfirmasi_relevan', $mapa01->konfirmasi_relevan ?? $template['konfirmasi_relevan'] ?? []);
                        @endphp
                        <tr>
                            <td rowspan="4" class="p-2"></td>
                            <td rowspan="4" class="p-2 font-bold align-top">Konfirmasi dengan orang yang relevan</td>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded"
                                        name="konfirmasi_relevan[]" value="Manajer sertifikasi LSP" {{ in_array('Manajer sertifikasi LSP', $konfirmasi) ? 'checked' : '' }}>
                                    <span>Manajer sertifikasi LSP</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded"
                                        name="konfirmasi_relevan[]"
                                        value="Master Asesor / Master Trainer / Lead Asesor Kompetensi" {{ in_array('Master Asesor / Master Trainer / Lead Asesor Kompetensi', $konfirmasi) ? 'checked' : '' }}>
                                    <span>Master Asesor / Master Trainer / Lead Asesor Kompetensi</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded"
                                        name="konfirmasi_relevan[]"
                                        value="Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar" {{ in_array('Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar', $konfirmasi) ? 'checked' : '' }}>
                                    <span>Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training
                                        terdaftar</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="w-4 h-4 border-gray-300 rounded"
                                        name="konfirmasi_relevan[]" value="Manajer atau Supervisor ditempat kerja" {{ in_array('Manajer atau Supervisor ditempat kerja', $konfirmasi) ? 'checked' : '' }}>
                                    <span>Manajer atau Supervisor ditempat kerja</span>
                                </label>
                            </td>
                        </tr>

                        {{-- 1.2 Standar Industri --}}
                        <tr>
                            <td class="p-2 font-bold text-center">1.2</td>
                            <td class="p-2 font-bold align-top">Standar Industri / Tempat Kerja</td>
                            <td class="p-2">
                                <div class="mb-2"><span class="font-bold">Standar Kompetensi:</span> <input type="text"
                                        name="standar_kompetensi"
                                        class="inline-block ml-2 border-b border-gray-300 focus:outline-none w-1/2"
                                        value="{{ old('standar_kompetensi', $mapa01->standar_kompetensi ?? $template['standar_kompetensi'] ?? ($skema->nama_skema ?? '')) }}"></div>
                                <div class="mb-2"><span class="font-bold">Spesifikasi Produk:</span> <input type="text"
                                        name="spesifikasi_produk"
                                        class="inline-block ml-2 border-b border-gray-300 focus:outline-none w-1/2"
                                        value="{{ old('spesifikasi_produk', $mapa01->spesifikasi_produk ?? $template['spesifikasi_produk'] ?? '') }}"></div>
                                <div><span class="font-bold">Pedoman Khusus:</span> <input type="text" name="pedoman_khusus"
                                        class="inline-block ml-2 border-b border-gray-300 focus:outline-none w-1/2"
                                        value="{{ old('pedoman_khusus', $mapa01->pedoman_khusus ?? $template['pedoman_khusus'] ?? '') }}"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- BAGIAN 2: PERENCANAAN ASESMEN --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-4">2. Perencanaan Asesmen</h3>
                <div class="mb-4">
                    <p class="font-bold text-sm mb-2">Kelompok Pekerjaan</p>
                    <table class="w-full text-sm form-table bg-gray-50 text-center">
                        <thead>
                            <tr>
                                <th class="p-2 w-12">No.</th>
                                <th class="p-2 w-40">Kode Unit</th>
                                <th class="p-2">Judul Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skema->kelompokPekerjaan as $kp)
                                @foreach ($kp->unitKompetensi as $unit)
                                <tr>
                                    <td class="p-2 border-black">{{ $loop->iteration }}</td>
                                    <td class="p-2 border-black font-mono">{{ $unit->kode_unit }}</td>
                                    <td class="p-2 border-black text-left">{{ $unit->judul_unit }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Tabel Checklist Besar --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm form-table min-w-[1200px]">
                        <thead class="bg-white text-center font-bold">
                            <tr>
                                <th rowspan="2" class="p-2 border border-black w-40 align-middle">Unit Kompetensi</th>
                                <th rowspan="2" class="p-2 border border-black w-64 align-middle">
                                    Bukti-Bukti<br>
                                    <span class="font-normal text-xs italic mt-1 block">
                                        (Kinerja, Produk, Portofolio, dan / atau Pengetahuan)
                                    </span>
                                </th>
                                <th colspan="3" class="p-2 border border-black w-24 align-middle">Jenis Bukti</th>
                                <th colspan="6" class="p-2 border border-black align-middle">
                                    Metode dan Perangkat Asesmen
                                </th>
                            </tr>
                            <tr class="text-xs">
                                <th class="p-2 border border-black w-10 align-middle font-bold text-sm text-center">L</th>
                                <th class="p-2 border border-black w-10 align-middle font-bold text-sm text-center">TL</th>
                                <th class="p-2 border border-black w-10 align-middle font-bold text-sm text-center">T</th>

                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span
                                        class="font-bold block text-center mb-1 text-sm">Observasi langsung</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span
                                        class="font-bold block text-center mb-1 text-sm">Kegiatan Terstruktur</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span
                                        class="font-bold block text-center mb-1 text-sm">Tanya Jawab</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span
                                        class="font-bold block text-center mb-1 text-sm">Verifikasi Portofolio</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span
                                        class="font-bold block text-center mb-1 text-sm">Reviu Produk</span></th>
                                <th class="p-2 border border-black w-32 align-top font-normal text-left"><span
                                        class="font-bold block text-center mb-1 text-sm">Verifikasi Pihak Ketiga</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $unitIdx = 0;
                            @endphp
                            @foreach ($skema->kelompokPekerjaan as $kp)
                                @foreach ($kp->unitKompetensi as $unit)
                                    @php
                                        $savedUnit = isset($mapa01->unit_kompetensi[$unitIdx]) ? $mapa01->unit_kompetensi[$unitIdx] : (isset($template['unit_kompetensi'][$unitIdx]) ? $template['unit_kompetensi'][$unitIdx] : []);
                                    @endphp
                                    <tr>
                                        <td class="p-2 border border-black align-top font-semibold">{{ $loop->iteration }}. {{ $unit->judul_unit }}</td>
                                        <td class="p-2 border border-black align-top">
                                            <textarea name="unit_kompetensi[{{ $unitIdx }}][bukti]"
                                                class="w-full h-24 border-none resize-none text-sm focus:outline-none placeholder-gray-400"
                                                placeholder="Tulis bukti di sini...">{{ old("unit_kompetensi.$unitIdx.bukti", $savedUnit['bukti'] ?? '') }}</textarea>
                                        </td>

                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][L]" class="w-5 h-5 cursor-pointer" value="1" {{ ($savedUnit['L'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][TL]" class="w-5 h-5 cursor-pointer" value="1" {{ ($savedUnit['TL'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][T]" class="w-5 h-5 cursor-pointer" value="1" {{ ($savedUnit['T'] ?? '') == '1' ? 'checked' : '' }}></td>

                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][observasi]" class="w-5 h-5 cursor-pointer" 
                                                value="1" {{ ($savedUnit['observasi'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][kegiatan_terstruktur]" class="w-5 h-5 cursor-pointer"
                                                value="1" {{ ($savedUnit['kegiatan_terstruktur'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][tanya_jawab]" class="w-5 h-5 cursor-pointer"
                                                value="1" {{ ($savedUnit['tanya_jawab'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][verifikasi_portofolio]" class="w-5 h-5 cursor-pointer"
                                                value="1" {{ ($savedUnit['verifikasi_portofolio'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][reviu_produk]" class="w-5 h-5 cursor-pointer"
                                                value="1" {{ ($savedUnit['reviu_produk'] ?? '') == '1' ? 'checked' : '' }}></td>
                                        <td class="p-1 border border-black text-center align-middle"><input type="checkbox"
                                                name="unit_kompetensi[{{ $unitIdx }}][verifikasi_pihak_ketiga]" class="w-5 h-5 cursor-pointer"
                                                value="1" {{ ($savedUnit['verifikasi_pihak_ketiga'] ?? '') == '1' ? 'checked' : '' }}></td>
                                    </tr>
                                    @php $unitIdx++; @endphp
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- BAGIAN 3: MODIFIKASI DAN KONTEKSTUALISASI --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-4">3. Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi</h3>
                <table class="w-full text-sm form-table">
                    <tbody>
                        {{-- 3.1 a --}}
                        <tr>
                            <td rowspan="2" class="p-2 font-bold w-10 text-center align-top">3.1</td>
                            <td class="p-2 w-1/3 align-top">a. Karakteristik Kandidat</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer"
                                            name="karakteristik_ada_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <span class="text-gray-500 italic text-xs block mb-1">*Ada / Tidak ada karakteristik
                                            khusus:</span>
                                        <textarea name="karakteristik_kandidat"
                                            class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none"
                                            :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'"
                                            :disabled="!active" placeholder="Tuliskan jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- 3.1 b --}}
                        <tr>
                            <td class="p-2 w-1/3 align-top">b. Kebutuhan kontekstualisasi</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer"
                                            name="kebutuhan_kontekstualisasi_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <span class="text-gray-500 italic text-xs block mb-1">*Ada / Tidak ada kebutuhan
                                            kontekstualisasi:</span>
                                        <textarea name="kebutuhan_kontekstualisasi"
                                            class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none"
                                            :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'"
                                            :disabled="!active" placeholder="Tuliskan jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- 3.2 --}}
                        <tr>
                            <td class="p-2 font-bold text-center align-top">3.2</td>
                            <td class="p-2 align-top">Saran dari paket pelatihan / pengembang</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer"
                                            name="saran_paket_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <textarea name="saran_paket_pelatihan"
                                            class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none"
                                            :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'"
                                            :disabled="!active" placeholder="Tulis saran jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- 3.3 --}}
                        <tr>
                            <td class="p-2 font-bold text-center align-top">3.3</td>
                            <td class="p-2 align-top">Penyesuaian perangkat asesmen</td>
                            <td class="p-2 align-top">
                                <div class="flex gap-3" x-data="{ active: false }">
                                    <div class="pt-2"><input type="checkbox" x-model="active" class="w-4 h-4 cursor-pointer"
                                            name="penyesuaian_perangkat_checkbox" value="1"></div>
                                    <div class="w-full">
                                        <textarea name="penyesuaian_perangkat_asesmen"
                                            class="w-full h-16 border p-2 rounded text-sm transition-colors resize-none focus:outline-none"
                                            :class="active ? 'bg-white border-gray-400 focus:border-blue-500' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'"
                                            :disabled="!active" placeholder="Tulis penyesuaian jika ada..."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- KONFIRMASI DAN TANDA TANGAN --}}
            <div class="mb-8">
                <h4 class="font-bold text-md mb-2">Konfirmasi dengan orang yang relevan</h4>
                <table class="w-full text-sm form-table">
                    <thead>
                        <tr>
                            <th colspan="2" class="p-2 border border-black text-center font-bold bg-white">Orang yang
                                relevan</th>
                            <th class="p-2 border border-black text-center font-bold w-1/4 bg-white">Nama</th>
                            <th class="p-2 border border-black text-center font-bold w-1/4 bg-white">Tandatangan dan Tanggal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Baris Manajer Sertifikasi --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]"
                                    value="Manajer sertifikasi LSP">
                            </td>
                            <td class="p-2 border border-black align-middle">Manajer sertifikasi LSP</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text"
                                    name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent"
                                    placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                        {{-- Baris Master Asesor --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]"
                                    value="Master Asesor">
                            </td>
                            <td class="p-2 border border-black align-middle">Master Asesor / Master Trainer / Lead Asesor
                                Kompetensi</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text"
                                    name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent"
                                    placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                        {{-- Baris Manajer Pelatihan --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]"
                                    value="Manajer Pelatihan">
                            </td>
                            <td class="p-2 border border-black align-middle">Manajer pelatihan Lembaga Training
                                terakreditasi</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text"
                                    name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent"
                                    placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                        {{-- Baris Supervisor --}}
                        <tr>
                            <td class="p-2 border border-black text-center w-10 align-middle">
                                <input type="checkbox" class="w-5 h-5 cursor-pointer mt-1" name="konfirmasi_relevan[]"
                                    value="Manajer atau supervisor">
                            </td>
                            <td class="p-2 border border-black align-middle">Manajer atau supervisor ditempat kerja</td>
                            <td class="p-2 border border-black align-middle"><input type="text" name="konfirmasi_nama[]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2 border border-black align-middle"><input type="text"
                                    name="konfirmasi_ttd_tanggal[]" class="w-full outline-none bg-transparent"
                                    placeholder="Tanda tangan & Tanggal"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PENYUSUN & VALIDATOR --}}
            <div class="mt-8">
                <h3 class="font-bold text-sm mb-2 uppercase">Penyusun dan Validator</h3>
                <table class="w-full text-sm form-table">
                    <thead class="bg-gray-100 text-center font-bold">
                        <tr>
                            <th class="p-2 w-1/6">STATUS</th>
                            <th class="p-2 w-12">NO</th>
                            <th class="p-2 w-1/3">NAMA</th>
                            <th class="p-2 w-1/6">NOMOR MET</th>
                            <th class="p-2">TANDA TANGAN DAN TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" class="p-2 text-center font-bold align-middle bg-gray-50">PENYUSUN</td>
                            <td class="p-2 text-center">1</td>
                            <td class="p-2"><input type="text" name="penyusun[0][nama]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[0][nomor_met]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[0][ttd_tanggal]"
                                    class="w-full outline-none bg-transparent"></td>
                        </tr>
                        <tr>
                            <td class="p-2 text-center">2</td>
                            <td class="p-2"><input type="text" name="penyusun[1][nama]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[1][nomor_met]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="penyusun[1][ttd_tanggal]"
                                    class="w-full outline-none bg-transparent"></td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="p-2 text-center font-bold align-middle bg-gray-50">VALIDATOR</td>
                            <td class="p-2 text-center">1</td>
                            <td class="p-2"><input type="text" name="validator[0][nama]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[0][nomor_met]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[0][ttd_tanggal]"
                                    class="w-full outline-none bg-transparent"></td>
                        </tr>
                        <tr>
                            <td class="p-2 text-center">2</td>
                            <td class="p-2"><input type="text" name="validator[1][nama]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[1][nomor_met]"
                                    class="w-full outline-none bg-transparent"></td>
                            <td class="p-2"><input type="text" name="validator[1][ttd_tanggal]"
                                    class="w-full outline-none bg-transparent"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL AKSI --}}
            @if(isset($isMasterView) && $isMasterView)
                <div class="mt-12 flex justify-end gap-4 pb-8">
                     <p class="text-gray-500 italic">Mode Pratinjau (Read Only)</p>
                     <a href="{{ $backUrl }}" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg shadow hover:bg-gray-700 transition">Kembali</a>
                </div>
            @else
            <div class="mt-12 flex justify-end gap-4 pb-8">
                <button type="button"
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg shadow hover:bg-gray-300 transition">Simpan
                    Draft</button>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition">Simpan
                    Permanen</button>
            </div>
            @endif

        </div>
    </form>
@endsection