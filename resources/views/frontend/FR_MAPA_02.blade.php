@extends($layout ?? 'layouts.app-sidebar-asesi')
@php
    $jadwal = $sertifikasi->jadwal ?? null;
    $asesi = $sertifikasi->asesi ?? null;
    $backUrl = isset($backUrl) ? $backUrl : ($sertifikasi ? route('asesor.tracker', $sertifikasi->jadwal->id_jadwal) : '#');
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded shadow-sm">
            <strong class="font-bold">Berhasil!</strong>
            <span class="ml-2">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-6 rounded shadow-sm">
            <strong class="font-bold">Error!</strong>
            <span class="ml-2">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ isset($isMasterView) ? '#' : route('mapa02.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf
        
        {{-- HEADER --}}
        <x-header_form.header_form title="FR.MAPA.02 - PETA INSTRUMEN ASESSMEN" />
        
        {{-- IDENTITAS SKEMA --}}
        <div class="mb-8">
            @if(isset($isMasterView))
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="font-bold">Skema: {{ $skema->nama_skema }}</p>
                    <p>Nomor: {{ $skema->nomor_skema }}</p>
                </div>
            @else
                <x-identitas_skema_form.identitas_skema_form :sertifikasi="$sertifikasi" />
            @endif
        </div>

        @php
            $currentSkema = isset($isMasterView) ? $skema : $sertifikasi->jadwal->skema;
        @endphp
        @foreach($currentSkema->kelompokPekerjaan as $kp)
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Kelompok Pekerjaan: {{ $kp->nama_kelompok_pekerjaan }}</h2>
                
                {{-- TABEL UNIT KOMPETENSI --}}
                <div class="border-2 border-gray-800 rounded-sm overflow-x-auto mb-6 shadow-sm">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead class="bg-gray-100 border-b-2 border-gray-800">
                            <tr>
                                <th class="p-3 text-sm font-bold text-center border-r border-gray-800 w-1/4">Kelompok Pekerjaan</th>
                                <th class="p-3 text-sm font-bold text-center border-r border-gray-800 w-1/12">No.</th>
                                <th class="p-3 text-sm font-bold text-center border-r border-gray-800 w-1/4">Kode Unit</th>
                                <th class="p-3 text-sm font-bold text-center">Judul Unit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white text-gray-800">
                            @foreach($kp->unitKompetensi as $index => $unit)
                                <tr class="border-b border-gray-300 hover:bg-gray-50 transition">
                                    @if($loop->first)
                                        <td class="p-3 text-center border-r border-gray-200 font-semibold" rowspan="{{ count($kp->unitKompetensi) }}">
                                            {{ $kp->nama_kelompok_pekerjaan }}
                                        </td>
                                    @endif
                                    <td class="p-3 text-center border-r border-gray-200">
                                        {{ $loop->iteration }}.
                                    </td>
                                    <td class="p-3 font-mono text-center border-r border-gray-200">
                                        {{ $unit->kode_unit }}
                                    </td>
                                    <td class="p-3">
                                        {{ $unit->judul_unit }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL POTENSI ASESI --}}
                <div class="border-2 border-gray-800 rounded-sm overflow-x-auto mb-2 shadow-sm">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead class="bg-gray-100 border-b-2 border-gray-800">
                            <tr>
                                <th class="p-3 text-sm font-bold text-center border-r border-gray-800 w-1/12">No.</th>
                                <th class="p-3 text-sm font-bold text-left border-r border-gray-800 w-1/2">INSTRUMEN ASESMEN</th>
                                <th class="p-3 text-sm font-bold text-center" colspan="5">Potensi Asesi **</th>
                            </tr>
                            <tr class="bg-gray-100 border-b-2 border-gray-800">
                                <th class="border-r border-gray-800"></th>
                                <th class="border-r border-gray-800"></th>
                                <th class="p-2 text-xs font-bold text-center border-r border-gray-800 w-[5%]">1</th>
                                <th class="p-2 text-xs font-bold text-center border-r border-gray-800 w-[5%]">2</th>
                                <th class="p-2 text-xs font-bold text-center border-r border-gray-800 w-[5%]">3</th>
                                <th class="p-2 text-xs font-bold text-center border-r border-gray-800 w-[5%]">4</th>
                                <th class="p-2 text-xs font-bold text-center w-[5%]">5</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white text-gray-800">
                            @php
                                $instruments = [
                                    'FR.IA.01. CL - Ceklis Observasi',
                                    'FR.IA.02. TPD - Tugas Praktik Demonstrasi',
                                    'FR.IA.03. PMO - Pertanyaan Untuk Mendukung Observasi',
                                    'FR.IA.04A. DIT - Daftar Instruksi Terstruktur (Proyek)',
                                    'FR.IA.04B. DIT - Daftar Instruksi Terstruktur (Lainnya)',
                                    'FR.IA.05. DPT - Daftar Pertanyaan Tertulis Pilihan Ganda',
                                    'FR.IA.06. DPT - Daftar Pertanyaan Tertulis Pilihan Esai',
                                    'FR.IA.07. DPL - Daftar Pertanyaan Lisan',
                                    'FR.IA.08. CVP - Ceklis Verifikasi Portofolio',
                                    'FR.IA.09. PW - Pertanyaan Wawancara',
                                    'FR.IA.10. VPK - Verifikasi Pihak Ketiga',
                                    'FR.IA.11. CRP - Ceklis Reviu Produk',
                                ];
                            @endphp

                            @foreach($instruments as $index => $instrument)
                                <tr class="border-b border-gray-300 hover:bg-gray-50 transition">
                                    <td class="p-3 text-center border-r border-gray-200">
                                        {{ $index + 1 }}.
                                    </td>
                                    <td class="p-3 border-r border-gray-200">
                                        {{ $instrument }}
                                    </td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <td class="p-3 text-center border-r border-gray-200 last:border-r-0">
                                            @php
                                                $checked = false;
                                                if (isset($mapa02Map[$kp->id_kelompok_pekerjaan][$instrument])) {
                                                    $checked = $mapa02Map[$kp->id_kelompok_pekerjaan][$instrument] == $i;
                                                } elseif (isset($template[$kp->id_kelompok_pekerjaan][$instrument])) {
                                                    $checked = $template[$kp->id_kelompok_pekerjaan][$instrument] == $i;
                                                }
                                            @endphp
                                            <input type="radio" 
                                                name="potensi[{{ $kp->id_kelompok_pekerjaan }}][{{ $instrument }}]" 
                                                value="{{ $i }}" 
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                {{ $checked ? 'checked' : '' }}
                                                {{ !$canEdit ? 'disabled' : '' }}>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-500 mt-2">*) diisi berdasarkan hasil penentuan pendekatan asesmen dan perencanaan asesmen</p>
                <p class="text-xs text-gray-500 mt-1">**) Potensi Asesi: 1:Sangat Baik, 2:Baik, 3:Cukup, 4:Kurang, 5:Sangat Kurang</p>
            </div>
        @endforeach
        
        {{-- TANDA TANGAN --}}
        <div class="mb-10">
            <x-kolom_ttd.penyusunvalidator />
        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-4 pb-10 mt-8">
            <a href="{{ $backUrl }}" 
                class="px-6 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-full shadow-sm transition">
                Kembali
            </a>
            
            @if($canEdit && !isset($isMasterView))
                <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    Simpan Form ✓
                </button>
            @endif
        </div>
    </form>
</div>
@endsection