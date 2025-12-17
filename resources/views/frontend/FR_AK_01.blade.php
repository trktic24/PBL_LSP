@extends('layouts.app-sidebar')

@section('content')
    <div class="p-4 sm:p-6 md:p-8">

        {{-- 1. Menggunakan Komponen Header Form --}}
        <x-header_form.header_form title="FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN" /><br>

        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">

            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                {{-- TUK --}}
                <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                    <label class="flex items-center text-gray-900 font-medium">
                        <input type="checkbox" value="Sewaktu" id="tuk_Sewaktu" disabled
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Sewaktu
                    </label>
                    <label class="flex items-center text-gray-900 font-medium">
                        <input type="checkbox" value="Tempat Kerja" id="tuk_Tempat Kerja" disabled
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Tempat Kerja
                    </label>
                    <label class="flex items-center text-gray-900 font-medium">
                        <input type="checkbox" value="Mandiri" id="tuk_Mandiri" disabled
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                        Mandiri
                    </label>
                </dd>

                {{-- Nama Asesor --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: <span
                        id="nama_asesor">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</span></dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: <span
                        id="nama_asesi">{{ $asesi->nama_lengkap ?? '-' }}</span></dd>

                {{-- Bukti yang dikumpulkan --}}
                <dt class="col-span-1 font-medium text-gray-500">Bukti yang dikumpulkan</dt>
                <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Verifikasi Portofolio" id="bukti_Verifikasi Portofolio"
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default"> Verifikasi Portofolio
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Hasil Test Tulis" id="bukti_Hasil Test Tulis"
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default"> Hasil Test Tulis
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Hasil Tes Lisan" id="bukti_Hasil Tes Lisan"
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default"> Hasil Tes Lisan
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Hasil Wawancara" id="bukti_Hasil Wawancara"
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default"> Hasil Wawancara
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Observasi Langsung" id="bukti_Observasi Langsung"
                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default"> Observasi Langsung
                    </label>
                </dd>
            </dl>
        </div>

        {{-- Pernyataan Persetujuan --}}
        <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg mb-6 shadow-sm">
            <h4 class="font-semibold text-blue-800 mb-2">Persetujuan dan Kerahasiaan</h4>
            <p class="text-gray-800 text-sm leading-relaxed mb-2">
                Bahwa saya sudah mendapatkan penjelasan Hak dan Prosedur Banding oleh Asesor.
            </p>
            <p class="text-gray-700 text-sm leading-relaxed">
                Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk
                pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
            </p>
        </div>

        {{-- 3. Area Tanda Tangan dan Tombol Hapus --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Peserta</label>
            <div class="w-full h-56 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors"
                id="ttd_container">
                <p id="ttd_placeholder" class="text-gray-400 text-sm">Area Tanda Tangan akan muncul di sini</p>
            </div>
        </div>

        {{-- 4. Komponen Kolom TTD (Jika diperlukan, atau hanya footer button) --}}
        {{-- Jika FR.AK.01 memiliki kolom TTD khusus, gunakan komponen yang sesuai.
        Jika tidak, kita hanya perlu tombol navigasi. --}}

        {{-- Tombol Navigasi --}}
        <div
            class="mt-6 sm:mt-8 md:mt-12 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 border-t border-gray-200 pt-4 sm:pt-6">
            <a href="{{ url()->previous() }}"
                class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg hover:bg-gray-300 transition shadow-sm text-center flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2 text-xs sm:text-sm"></i>
                <span>Kembali</span>
            </a>
            <form action="{{ route('ak01.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
                @csrf
                <button type="submit" id="btn-submit-ak01"
                    class="px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center">
                    <span>Setuju dan Lanjutkan</span>
                    <i class="fas fa-arrow-right ml-2 text-xs sm:text-sm"></i>
                </button>
            </form>
        </div>

    </div>
@endsection
