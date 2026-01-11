{{-- File: resources/views/frontend/IA_01/admin_show.blade.php --}}

@extends('layouts.app-sidebar') 

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">FR.IA.01 - Ceklis Observasi Aktivitas</h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if(isset($isMasterView))
                        Mode Pratinjau Template (Master Data)
                    @else
                        Mode Tinjauan Admin (Read Only)
                    @endif
                </p>
            </div>
            <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                &larr; Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <div class="grid grid-cols-[120px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Skema</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $skema->nama_skema }}</span>
                </div>
                <div class="grid grid-cols-[120px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Kelompok</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-[120px_10px_1fr]">
                    <span class="font-semibold text-gray-600">TUK</span>
                    <span>:</span>
                    <span class="text-gray-900 capitalize">
                        {{-- TUK biasanya disimpan di session atau tabel lain, disini kita mock atau ambil dari data --}}
                        {{ $responses->first()->tuk ?? 'Tempat Kerja' }}
                    </span>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-[120px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Asesor</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '(Template)' }}</span>
                </div>
                <div class="grid grid-cols-[120px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Asesi</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $sertifikasi->asesi->nama_lengkap ?? '(Template)' }}</span>
                </div>
                <div class="grid grid-cols-[120px_10px_1fr]">
                    <span class="font-semibold text-gray-600">Tanggal</span>
                    <span>:</span>
                    <span class="text-gray-900">{{ now()->format('d-m-Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- LOOPING UNIT KOMPETENSI --}}
    @foreach ($units as $unit)
    <div class="mb-10 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        {{-- Header Unit --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded">Unit {{ $unit->urutan }}</span>
                <h2 class="text-lg font-bold text-gray-800">{{ $unit->kode_unit }} - {{ $unit->judul_unit }}</h2>
            </div>
        </div>

        {{-- Tabel KUK --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="p-4 border-b w-1/4">Elemen</th>
                        <th class="p-4 border-b w-1/3">Kriteria Unjuk Kerja</th>
                        <th class="p-4 border-b w-1/6">Standar Industri</th>
                        <th class="p-4 border-b w-24 text-center">Hasil</th>
                        <th class="p-4 border-b w-1/5">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach ($unit->elemen as $elemen)
                        @foreach ($elemen->kriteria as $index => $kuk)
                            @php
                                $response = $responses[$kuk->id_kriteria] ?? null;
                                $isKompeten = $response ? $response->pencapaian_ia01 == 1 : false;
                                $hasResponse = $response !== null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                {{-- Elemen (Rowspan logic handled simply by repeating for now, or use logic if strict layout needed) --}}
                                @if ($index === 0)
                                    <td rowspan="{{ $elemen->kriteria->count() }}" class="p-4 align-top font-medium bg-white border-r border-gray-100">
                                        {{ $elemen->elemen }}
                                    </td>
                                @endif

                                <td class="p-4 align-top border-r border-gray-100">
                                    <div class="flex gap-2">
                                        <span class="font-bold text-gray-500">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                                        <span>{{ $kuk->kriteria }}</span>
                                    </div>
                                </td>

                                <td class="p-4 align-top border-r border-gray-100">
                                    @php
                                        $standarDisplay = $response->standar_industri_ia01 ?? ($templateContent[$kuk->id_kriteria] ?? $kuk->standar_industri_kerja);
                                    @endphp
                                    @if($standarDisplay)
                                        <div class="p-2 bg-gray-50 rounded border border-gray-200 text-xs">
                                            {{ $standarDisplay }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>

                                <td class="p-4 align-top text-center border-r border-gray-100">
                                    @if ($hasResponse)
                                        @if ($isKompeten)
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded">K</span>
                                        @else
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded">BK</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="p-4 align-top">
                                    @if($response && $response->penilaian_lanjut_ia01)
                                        <div class="p-2 bg-yellow-50 rounded border border-yellow-200 text-xs text-yellow-800">
                                            {{ $response->penilaian_lanjut_ia01 }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    {{-- REKOMENDASI & TTD --}}
    {{-- REKOMENDASI & TTD --}}
    @if(!isset($isMasterView))
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Rekomendasi & Tanda Tangan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- Rekomendasi --}}
            <div>
                <p class="font-semibold text-gray-700 mb-2">Rekomendasi Asesor:</p>
                @php
                    $rekomendasi = $sertifikasi->rekomendasi_ia01 ?? '-';
                    $color = $rekomendasi === 'kompeten' ? 'text-green-600' : ($rekomendasi === 'belum_kompeten' ? 'text-red-600' : 'text-gray-600');
                    $rekomendasiText = $rekomendasi === 'kompeten' ? 'Kompeten' : ($rekomendasi === 'belum_kompeten' ? 'Belum Kompeten' : '-');
                @endphp
                <div class="text-2xl font-bold {{ $color }} mb-4">
                    {{ $rekomendasiText }}
                </div>

                <p class="font-semibold text-gray-700 mb-2">Umpan Balik:</p>
                <div class="p-4 bg-gray-50 rounded border border-gray-200 italic text-gray-600 min-h-[80px]">
                    {{ $sertifikasi->feedback_ia01 ?? '-' }}
                </div>

                {{-- Detail Belum Kompeten --}}
                @if($sertifikasi->rekomendasi_ia01 === 'belum_kompeten' && ($sertifikasi->bk_unit_ia01 || $sertifikasi->bk_elemen_ia01 || $sertifikasi->bk_kuk_ia01))
                <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                    <p class="font-bold text-red-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Detail Aspek Belum Kompeten
                    </p>
                    <div class="space-y-2 text-sm">
                        @if($sertifikasi->bk_unit_ia01)
                        <div class="grid grid-cols-[140px_10px_1fr]">
                            <span class="font-semibold text-red-700">Unit Kompetensi</span>
                            <span>:</span>
                            <span class="text-red-900">{{ $sertifikasi->bk_unit_ia01 }}</span>
                        </div>
                        @endif
                        @if($sertifikasi->bk_elemen_ia01)
                        <div class="grid grid-cols-[140px_10px_1fr]">
                            <span class="font-semibold text-red-700">Elemen</span>
                            <span>:</span>
                            <span class="text-red-900">{{ $sertifikasi->bk_elemen_ia01 }}</span>
                        </div>
                        @endif
                        @if($sertifikasi->bk_kuk_ia01)
                        <div class="grid grid-cols-[140px_10px_1fr]">
                            <span class="font-semibold text-red-700">No. KUK</span>
                            <span>:</span>
                            <span class="text-red-900">{{ $sertifikasi->bk_kuk_ia01 }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Tanda Tangan --}}
            <div class="flex justify-around items-end">
                <div class="text-center">
                    <div class="h-24 w-32 bg-gray-100 rounded mb-2 flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">
                        (TTD Asesi)
                    </div>
                    <p class="font-bold text-sm">{{ $sertifikasi->asesi->nama_lengkap ?? 'Asesi' }}</p>
                    <p class="text-xs text-gray-500">Asesi</p>
                </div>
                <div class="text-center">
                    <div class="h-24 w-32 bg-gray-100 rounded mb-2 flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">
                        (TTD Asesor)
                    </div>
                    <p class="font-bold text-sm">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Asesor' }}</p>
                    <p class="text-xs text-gray-500">Asesor</p>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
