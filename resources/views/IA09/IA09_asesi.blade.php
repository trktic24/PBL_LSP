@extends('layouts.app-profil')

@section('content')

<?php
// PERBAIKAN UTAMA: Blok PHP ini dipertahankan untuk memastikan kompatibilitas 
// dengan data yang mungkin masih berupa string JSON, meskipun sudah ada JSON Casting di Model.
// Jika data sudah di-cast oleh Eloquent, is_string() akan false, dan akan menggunakan $data->questions (Array/Object).
$questions = isset($data->questions) 
    && is_string($data->questions) 
    && !empty($data->questions) 
    ? json_decode($data->questions, true) 
    : ($data->questions ?? []);

$units = isset($data->units) 
    && is_string($data->units) 
    && !empty($data->units) 
    ? json_decode($data->units, true) 
    : ($data->units ?? []);

// Pastikan hasilnya adalah array agar loop @forelse tidak error
if (!is_array($questions)) $questions = [];
if (!is_array($units)) $units = [];
?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6">
            FR.IA.09. PERTANYAAN WAWANCARA 
        </h1>

        {{-- SKEMA DAN DATA UMUM (Data Relasi: Asesi, Asesor, Skema) --}}
        <div class="border border-gray-300 p-4 rounded-lg mb-6 bg-gray-50">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Skema & Data Umum</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="space-y-2">
                    <p><strong>Skema Sertifikasi</strong>: <span class="font-normal text-gray-700">{{ $data->skema->judul ?? '...' }}</span></p>
                    <p><strong>Nomor Skema</strong>: <span class="font-normal text-gray-700">{{ $data->skema->nomor ?? '...' }}</span></p>
                    {{-- Formatting tanggal menggunakan Carbon --}}
                    <p><strong>Tanggal Asesmen</strong>: <span class="font-normal text-gray-700">{{ $data->tanggal_asesmen ? \Carbon\Carbon::parse($data->tanggal_asesmen)->format('d F Y') : '...' }}</span></p>
                </div>
                <div class="space-y-2">
                    {{-- Akses data menggunakan relasi yang telah diperbaiki di Model --}}
                    <p><strong>Nama Asesor</strong>: <span class="font-normal text-gray-700">{{ $data->asesor->nama_lengkap ?? '...' }}</span></p>
                    <p><strong>Nama Asesi</strong>: <span class="font-normal text-gray-700">{{ $data->asesi->nama_lengkap ?? '...' }}</span></p>
                    <p><strong>TUK</strong>: <span class="font-normal text-gray-700">{{ $data->tuk ?? '...' }}</span></p>
                </div>
            </div>
        </div>
        <hr class="my-6">
        
        <h2 class="text-xl font-semibold mb-4 text-blue-700">Daftar Pertanyaan Wawancara</h2>
        <p class="text-sm text-gray-600 italic mb-4">Anda dapat melihat daftar pertanyaan yang diajukan dan hasil wawancara yang dicatat oleh Asesor.</p>
        
        {{-- TABEL PERTANYAAN WAWANCARA (Menggunakan $questions) --}}
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-5/12">Daftar Pertanyaan Wawancara</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-4/12">Kesimpulan Jawaban Asesi (Tercatat oleh Asesor)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Pencapaian Asesor</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($questions as $index => $q)
                        <tr class="{{ ($q['pencapaian'] ?? '') === 'Ya' ? 'bg-green-50' : (($q['pencapaian'] ?? '') === 'Tidak' ? 'bg-red-50' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}.</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <strong>Bukti No: {{ $q['no_bukti'] ?? '-' }}</strong><br>
                                {{ $q['pertanyaan'] ?? '...' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 italic">
                                {{ $q['kesimpulan_jawaban'] ?? 'Belum ada catatan jawaban.' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                <span class="inline-block px-3 py-1 rounded-full text-white 
                                    @if(($q['pencapaian'] ?? '') === 'Ya')
                                        bg-green-600
                                    @elseif(($q['pencapaian'] ?? '') === 'Tidak')
                                        bg-red-600
                                    @else
                                        bg-gray-400
                                    @endif
                                ">
                                    {{ $q['pencapaian'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pertanyaan wawancara yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <h2 class="text-xl font-semibold mt-10 mb-4 text-blue-700">Ringkasan Unit Kompetensi</h2>
        {{-- TABEL UNIT KOMPETENSI (Menggunakan $units) --}}
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Kode Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-7/12">Judul Unit Kompetensi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Rekomendasi Asesor</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $index => $u)
                        <tr class="{{ ($u['rekomendasi'] ?? '') === 'K' ? 'bg-green-50' : (($u['rekomendasi'] ?? '') === 'BK' ? 'bg-red-50' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}.</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $u['kode'] ?? '...' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $u['unit'] ?? '...' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                <span class="inline-block px-3 py-1 rounded-full text-white 
                                    @if(($u['rekomendasi'] ?? '') === 'K')
                                        bg-green-700
                                    @elseif(($u['rekomendasi'] ?? '') === 'BK')
                                        bg-red-700
                                    @else
                                        bg-gray-500
                                    @endif
                                ">
                                    {{ $u['rekomendasi'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data unit kompetensi yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        {{-- Area Keputusan Asesor --}}
        <div class="border border-gray-300 p-4 rounded-lg mb-6 bg-yellow-50">
            <h2 class="text-xl font-semibold mb-4 text-red-700">Keputusan & Catatan Asesor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="mb-2"><strong>Rekomendasi Umum Asesor:</strong></p>
                    <span class="inline-block px-4 py-2 rounded-full text-lg font-bold text-white shadow-lg 
                        @if($data->rekomendasi_asesor === 'K')
                            bg-green-700
                        @elseif($data->rekomendasi_asesor === 'BK')
                            bg-red-700
                        @else
                            bg-gray-500
                        @endif
                    ">
                        {{ $data->rekomendasi_asesor === 'K' ? 'KOMPETEN' : ($data->rekomendasi_asesor === 'BK' ? 'BELUM KOMPETEN' : 'BELUM DIPUTUSKAN') }}
                    </span>
                </div>
                <div>
                    <p class="mb-2"><strong>Catatan Tambahan Asesor:</strong></p>
                    <div class="p-3 border border-gray-400 bg-white rounded h-24 overflow-y-auto italic text-gray-700">
                        {{ $data->catatan_asesor ?? 'Tidak ada catatan khusus dari Asesor.' }}
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-6">

        {{-- Area Tanda Tangan --}}
        <h2 class="text-xl font-semibold mt-10 mb-4 text-blue-700">Konfirmasi Asesi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-2">Asesi</h3>
                <div class="border border-gray-300 p-4 rounded-lg bg-gray-50">
                    <p class="mb-1"><strong>Nama</strong>: {{ $data->asesi->nama_lengkap ?? '...' }}</p>
                    <p class="mb-4"><strong>Tanggal TTD</strong>: {{ $data->tgl_ttd_asesi ?? '...' }}</p>
                    <div class="h-24 border border-dashed border-gray-400 flex items-center justify-center text-gray-500">
                        <p class="text-sm">TTD Terlampir / Digital</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
             {{-- Anda mungkin perlu menambahkan logic untuk URL cetak PDF --}}
             <button class="bg-green-600 text-white font-bold px-8 py-3 rounded-lg shadow-md hover:bg-green-700 transition">
                 <i class="fas fa-file-pdf mr-2"></i> Cetak Dokumen
             </button>
        </div>

    </div>
</div>

@endsection