@extends($layout ?? 'layouts.app-sidebar')

@section('content')
    <div class="p-4 sm:p-6 md:p-8">

        {{-- 1. Menggunakan Komponen Header Form --}}
        <x-header_form.header_form title="FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN" />
        @if(isset($isMasterView))
            <div class="text-center font-bold text-blue-600 my-2">[TEMPLATE MASTER]</div>
        @endif
        <br>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- DETAIL PELAKSANAAN --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">

            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                {{-- TUK --}}
                <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                    @php
                        // Ambil jenis TUK dari database (jika ada), default null
                        $jenisTuk = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? '';
                    @endphp
                    <label class="flex items-center text-gray-900 font-medium">
                        <input type="checkbox" disabled {{ $jenisTuk == 'Sewaktu' ? 'checked' : '' }} 
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Sewaktu
                    </label>
                    <label class="flex items-center text-gray-900 font-medium">
                        <input type="checkbox" disabled {{ $jenisTuk == 'Tempat Kerja' ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Tempat Kerja
                    </label>
                    <label class="flex items-center text-gray-900 font-medium">
                        <input type="checkbox" disabled {{ $jenisTuk == 'Mandiri' ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Mandiri
                    </label>
                </dd>

                {{-- Nama Asesor --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: 
                    <span id="nama_asesor">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}</span>
                </dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: 
                    <span id="nama_asesi">{{ $asesi->nama_lengkap ?? (isset($isMasterView) ? '(Template Master)' : Auth::user()->name) }}</span>
                </dd>

                {{-- Skema Sertifikasi (Judul) --}}
                <dt class="col-span-1 font-medium text-gray-500">Skema Sertifikasi (Judul)</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: 
                    <span>{{ $sertifikasi->jadwal->skema->judul_skema ?? 'N/A' }}</span>
                </dd>

                {{-- Skema Sertifikasi (Nomor) --}}
                <dt class="col-span-1 font-medium text-gray-500">Skema Sertifikasi (Nomor)</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: 
                    <span>{{ $sertifikasi->jadwal->skema->nomor_skema ?? 'N/A' }}</span>
                </dd>

                {{-- Bukti yang dikumpulkan (Display Only - Statis Sesuai FR) --}}
                <dt class="col-span-1 font-medium text-gray-500">Bukti yang dikumpulkan</dt>
                <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" checked disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Verifikasi Portofolio
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" checked disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Hasil Observasi / Tes Praktek
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" checked disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Hasil Tes Tulis (Jika ada)
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" checked disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Hasil Wawancara
                    </label>
                </dd>
            </dl>
        </div>

        {{-- PERNYATAAN PERSETUJUAN --}}
        <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg mb-6 shadow-sm">
            <h4 class="font-semibold text-blue-800 mb-2">Persetujuan dan Kerahasiaan</h4>
            <p class="text-gray-800 text-sm leading-relaxed mb-2">
                {{ $template['pernyataan_1'] ?? 'Bahwa saya sudah mendapatkan penjelasan Hak dan Prosedur Banding oleh Asesor.' }}
            </p>
            <p class="text-gray-700 text-sm leading-relaxed">
                {{ $template['pernyataan_2'] ?? 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.' }}
            </p>
        </div>

        {{-- AREA TANDA TANGAN (Otomatis dari Profil) --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Peserta</label>
            <div class="w-full h-56 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors"
                id="ttd_container">
                
                @php
                    $ttdAsesiBase64 = getTtdBase64($asesi->tanda_tangan ?? null, null, 'asesi');
                @endphp
                @if($ttdAsesiBase64)
                    {{-- Jika ada TTD di database, tampilkan --}}
                    <img src="{{ $ttdAsesiBase64 }}" 
                         alt="Tanda Tangan Asesi" 
                         class="h-40 object-contain">
                @else
                    {{-- Jika belum ada --}}
                    <div class="text-center">
                         <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm mt-2">Tanda tangan belum tersedia di profil.</p>
                        <p class="text-xs text-gray-400">Silakan lengkapi profil Anda terlebih dahulu.</p>
                    </div>
                @endif
            </div>
             <p class="text-xs text-gray-500 mt-2 text-center">Tanggal: {{ now()->isoFormat('D MMMM Y') }}</p>
        </div>

        {{-- TOMBOL NAVIGASI --}}
        <div class="mt-6 sm:mt-8 md:mt-12 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 border-t border-gray-200 pt-4 sm:pt-6">
            
            {{-- Tombol Kembali --}}
            <a href="{{ isset($isMasterView) ? url()->previous() : route('tracker') }}"
                class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg hover:bg-gray-300 transition shadow-sm text-center flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2 text-xs sm:text-sm"></i>
                <span>Kembali</span>
            </a>

            {{-- Form Submit --}}
            @if(!isset($isMasterView))
            <form action="{{ route('ak01.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
                @csrf
                <button type="submit" id="btn-submit-ak01"
                    class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center w-full sm:w-auto">
                    <span>Setuju dan Lanjutkan</span>
                    <i class="fas fa-arrow-right ml-2 text-xs sm:text-sm"></i>
                </button>
            </form>
            @endif
        </div>

    </div>
@endsection