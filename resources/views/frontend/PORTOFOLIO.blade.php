@extends('layouts.app-sidebar')

@section('custom_styles')
<style>
    .upload-card { transition: all 0.3s ease; }
    .upload-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')
@php
    // Helper untuk mencari file dan memotong path menjadi nama file saja
    $getFile = function($keywords) use ($dokumen_db) {
        // Jika keywords adalah string tunggal, ubah jadi array
        $searchArray = is_array($keywords) ? $keywords : [$keywords];
        $file = null;

        // Iterasi keywords (misal: cari Pengalaman_Kerja dulu, kalau tidak ada baru cari CV)
        foreach ($searchArray as $keyword) {
            $file = $dokumen_db->filter(function($item) use ($keyword) {
                return str_contains(strtolower($item->bukti_dasar), strtolower($keyword));
            })->first();
            
            if ($file) break; // Berhenti jika salah satu ditemukan
        }

        if ($file) {
            return [
                'fileName' => basename($file->bukti_dasar),
                'fullPath' => $file->bukti_dasar
            ];
        }
        return null;
    };
@endphp

<div class="bg-white min-h-screen overflow-y-auto p-6 lg:p-12">
    <div class="max-w-5xl mx-auto pb-20">

        {{-- Header --}}
        <div class="mb-8 border-b border-gray-200 pb-6">
            <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">Portofolio Asesi</h1>
            <p class="text-gray-600">Berikut adalah dokumen persyaratan yang telah Anda unggah.</p>
        </div>

        {{-- Info Box --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Nama Asesi</p>
                <p class="text-lg font-normal text-gray-900">{{ $nama_lengkap }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Skema Sertifikasi</p>
                <p class="text-lg font-normal text-gray-900">{{ $nama_skema }}</p>
            </div>
        </div>

        <div class="space-y-8">
            {{-- 1. PERSYARATAN DASAR --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                    <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">1</div>
                    <h3 class="text-lg font-bold text-gray-800">PERSYARATAN DASAR</h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Foto Background Merah --}}
                    @php $foto = $getFile('Foto_Background_Merah'); @endphp
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Foto Background Merah</p>
                        </div>
                        @if($foto)
                            @php $fotoBase64 = getDocBase64($foto['fullPath']); @endphp
                            <p class="text-xs text-blue-600 mb-2">{{ $foto['fileName'] }}</p>
                            @if($fotoBase64)
                                <img src="data:{{ getDocMimeType($foto['fullPath']) }};base64,{{ $fotoBase64 }}" class="h-40 w-auto rounded border shadow-sm" alt="Foto Asesi">
                            @else
                                <p class="text-xs text-red-400 italic">Gambar tidak dapat dimuat</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Dokumen belum tersedia</p>
                        @endif
                    </div>

                    {{-- KTP --}}
                    @php $ktp = $getFile('KTP'); @endphp
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Kartu Tanda Penduduk (KTP)</p>
                        </div>
                        @if($ktp)
                            @php $ktpBase64 = getDocBase64($ktp['fullPath']); @endphp
                            <p class="text-xs text-blue-600 mb-2">{{ $ktp['fileName'] }}</p>
                            @if($ktpBase64)
                                <img src="data:{{ getDocMimeType($ktp['fullPath']) }};base64,{{ $ktpBase64 }}" class="h-40 w-auto rounded border shadow-sm" alt="KTP">
                            @else
                                <p class="text-xs text-red-400 italic">Gambar tidak dapat dimuat</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Dokumen belum tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. PERSYARATAN ADMINISTRATIF --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                    <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">2</div>
                    <h3 class="text-lg font-bold text-gray-800">PERSYARATAN ADMINISTRATIF</h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Sertifikasi Pelatihan --}}
                    @php $sertif = $getFile('Sertifikasi_Pelatihan'); @endphp
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Sertifikasi Pelatihan</p>
                        </div>
                        @if($sertif)
                            @php $sertifBase64 = getDocBase64($sertif['fullPath']); @endphp
                            <p class="text-xs text-blue-600 mb-2">{{ $sertif['fileName'] }}</p>
                            @if($sertifBase64)
                                <img src="data:{{ getDocMimeType($sertif['fullPath']) }};base64,{{ $sertifBase64 }}" class="h-60 w-auto rounded border shadow-sm" alt="Sertifikasi Pelatihan">
                            @else
                                <p class="text-xs text-red-400 italic">Gambar tidak dapat dimuat</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Dokumen belum tersedia</p>
                        @endif
                    </div>

                    {{-- Ijazah --}}
                    @php $ijazah = $getFile('Ijazah'); @endphp
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Ijazah Terakhir</p>
                        </div>
                        @if($ijazah)
                            @php $ijazahBase64 = getDocBase64($ijazah['fullPath']); @endphp
                            <p class="text-xs text-blue-600 mb-2">{{ $ijazah['fileName'] }}</p>
                            @if($ijazahBase64)
                                <img src="data:{{ getDocMimeType($ijazah['fullPath']) }};base64,{{ $ijazahBase64 }}" class="h-60 w-auto rounded border shadow-sm" alt="Ijazah">
                            @else
                                <p class="text-xs text-red-400 italic">Gambar tidak dapat dimuat</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Dokumen belum tersedia</p>
                        @endif
                    </div>

                    {{-- Surat Keterangan Kerja --}}
                    @php $skk = $getFile('Surat_Keterangan_Kerja'); @endphp
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Surat Keterangan Kerja</p>
                        </div>
                        @if($skk)
                            @php $skkBase64 = getDocBase64($skk['fullPath']); @endphp
                            <p class="text-xs text-blue-600 mb-2">{{ $skk['fileName'] }}</p>
                            @if($skkBase64)
                                <img src="data:{{ getDocMimeType($skk['fullPath']) }};base64,{{ $skkBase64 }}" class="h-60 w-auto rounded border shadow-sm" alt="Surat Keterangan Kerja">
                            @else
                                <p class="text-xs text-red-400 italic">Gambar tidak dapat dimuat</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Dokumen belum tersedia</p>
                        @endif
                    </div>

                    {{-- Pengalaman Kerja atau CV --}}
                    {{-- Sistem akan mencari Pengalaman_Kerja dulu, jika tidak ada baru cari CV --}}
                    @php $kerja_cv = $getFile(['Pengalaman_Kerja', 'CV']); @endphp
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Pengalaman Kerja / CV</p>
                        </div>
                        @if($kerja_cv)
                            @php $kerjaCvBase64 = getDocBase64($kerja_cv['fullPath']); @endphp
                            <p class="text-xs text-blue-600 mb-2">{{ $kerja_cv['fileName'] }}</p>
                            @if($kerjaCvBase64)
                                <img src="data:{{ getDocMimeType($kerja_cv['fullPath']) }};base64,{{ $kerjaCvBase64 }}" class="h-60 w-auto rounded border shadow-sm" alt="Pengalaman Kerja / CV">
                            @else
                                <p class="text-xs text-red-400 italic">Gambar tidak dapat dimuat</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Dokumen belum tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
            <a href="{{ route('home') }}" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm"> Kembali </a>
            <button type="button" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5"> Lanjutkan </button>
        </div>
    </div>
</div>
@endsection