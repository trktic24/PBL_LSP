@extends('layouts.app-sidebar')

@section('content')
    <style>
        .accordion-content {
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }

        .accordion-content.active {
            max-height: 5000px;
            opacity: 1;
        }

        .accordion-icon {
            transition: transform 0.3s ease;
        }

        .accordion-btn[aria-expanded="true"] .accordion-icon {
            transform: rotate(180deg);
        }
    </style>

    <x-header_form.header_form title="FR.APL.02 ASESMEN MANDIRI" /><br>

    {{-- Penyesuaian Margin Global --}}
    <div class="p-3 sm:p-4 md:p-6">

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <p class="font-bold">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <p class="font-bold">Terdapat kesalahan input:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM WRAPPER (MENGIRIM DATA KE CONTROLLER) --}}
        <form action="{{ route('apl02.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Detail Pelaksanaan --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Skema Sertifikasi</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">
                    {{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}
                </dd>
                
                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Nomor Skema</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">
                    {{ $sertifikasi->jadwal->skema->nomor_skema ?? '-' }}
                </dd>

                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Nama Asesi</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">
                    {{ $sertifikasi->asesi->nama_lengkap ?? Auth::user()->name }}
                </dd>

                <dt class="font-medium text-gray-500 text-xs sm:text-sm mb-1">Tanggal Pengisian</dt>
                <dd class="text-gray-900 font-bold text-sm sm:text-base">
                    {{ now()->isoFormat('D MMMM Y') }}
                </dd>
            </dl>
        </div>

        {{-- Panduan --}}
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 sm:p-5 mb-6 sm:mb-8 rounded-r-lg shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 mr-3 flex-shrink-0 mt-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-xs sm:text-sm text-amber-900 leading-relaxed">
                        <strong class="font-bold">Panduan:</strong> Baca setiap pertanyaan, pilih <span
                            class="font-bold text-green-700">K (Kompeten)</span> jika yakin dapat melakukannya, atau <span
                            class="font-bold text-red-700">BK (Belum Kompeten)</span> jika belum. Upload bukti pendukung
                        yang relevan.
                    </p>
                </div>
            </div>
        </div>

        {{-- DAFTAR UNIT (ACCORDION DINAMIS) --}}
        <div class="space-y-4 sm:space-y-5 mb-8 sm:mb-10">

            {{-- Looping Unit Kompetensi dari Database --}}
            @foreach ($sertifikasi->jadwal->skema->unitKompetensi as $indexUnit => $unit)
                <div class="border-2 border-gray-200 rounded-xl overflow-hidden shadow-md bg-white hover:shadow-xl transition-shadow">

                    {{-- Header Accordion --}}
                    <button type="button"
                        class="accordion-btn w-full bg-gradient-to-r from-gray-50 to-gray-100 p-4 sm:p-5 flex justify-between items-center text-left hover:from-gray-100 hover:to-gray-200 transition-all"
                        aria-expanded="{{ $indexUnit === 0 ? 'true' : 'false' }}">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                                    Unit {{ $indexUnit + 1 }}
                                </span>
                                <span class="text-xs sm:text-sm text-gray-600 font-medium break-all">
                                    {{ $unit->kode_unit }}
                                </span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 break-words">
                                {{ $unit->judul_unit }}
                            </h3>
                        </div>
                        <svg class="accordion-icon w-6 h-6 sm:w-7 sm:h-7 text-gray-500 ml-3 flex-shrink-0 {{ $indexUnit === 0 ? 'rotate-180' : '' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    {{-- Body Accordion --}}
                    <div class="accordion-content {{ $indexUnit === 0 ? 'active' : '' }}">
                        <div class="p-0">

                            {{-- Desktop Table --}}
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-900 text-white">
                                        <tr>
                                            <th class="px-4 py-4 text-left text-xs font-bold uppercase w-12">No</th>
                                            <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[55%]">Pertanyaan Asesmen Mandiri</th>
                                            <th class="px-4 py-4 text-center text-xs font-bold uppercase w-16">K</th>
                                            <th class="px-4 py-4 text-center text-xs font-bold uppercase w-16">BK</th>
                                            <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[25%]">Bukti Pendukung</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        {{-- Looping Elemen --}}
                                        @foreach ($unit->elemen as $indexElemen => $elemen)
                                            <tr class="bg-gray-50">
                                                <td colspan="5" class="px-4 py-3 text-sm font-bold text-gray-800">
                                                    Elemen {{ $indexElemen + 1 }}: {{ $elemen->nama_elemen }}
                                                </td>
                                            </tr>

                                            {{-- Looping Kriteria (Pertanyaan) --}}
                                            @foreach ($elemen->kriteria as $indexKuk => $kuk)
                                                @php
                                                    // Ambil ID Kriteria
                                                    // Pastikan nama kolom primary key di database Anda: id_kriteria_unjuk_kerja
                                                    $idKuk = $kuk->id_kriteria_unjuk_kerja; 
                                                    
                                                    // Cek apakah sudah dijawab sebelumnya
                                                    $dataJawaban = $jawaban[$idKuk] ?? null;
                                                    
                                                    // Ambil status (1 = K, 0 = BK) konversi ke string 'K'/'BK'
                                                    $status = '';
                                                    if($dataJawaban) {
                                                        $status = $dataJawaban->respon_asesi_apl02 == 1 ? 'K' : 'BK';
                                                    }
                                                @endphp

                                                <tr class="hover:bg-blue-50 transition-colors">
                                                    <td class="px-4 py-5 text-sm font-bold text-gray-900 align-top">
                                                        {{ $indexElemen + 1 }}.{{ $indexKuk + 1 }}
                                                    </td>
                                                    <td class="px-4 py-5 text-sm text-gray-700 align-top">
                                                        <p class="leading-relaxed font-medium">
                                                            {{ $kuk->kriteria_unjuk_kerja }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1 italic">
                                                            Apakah Anda dapat melakukan kriteria ini?
                                                        </p>
                                                    </td>
                                                    {{-- Radio K --}}
                                                    <td class="px-4 py-5 align-top text-center">
                                                        <label class="inline-flex items-center justify-center cursor-pointer group">
                                                            <input type="radio"
                                                                name="respon[{{ $idKuk }}][status]"
                                                                value="K"
                                                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer"
                                                                {{ $status == 'K' ? 'checked' : '' }}
                                                                required>
                                                        </label>
                                                    </td>
                                                    {{-- Radio BK --}}
                                                    <td class="px-4 py-5 align-top text-center">
                                                        <label class="inline-flex items-center justify-center cursor-pointer group">
                                                            <input type="radio"
                                                                name="respon[{{ $idKuk }}][status]"
                                                                value="BK"
                                                                class="w-5 h-5 text-red-600 border-2 border-gray-300 focus:ring-2 focus:ring-red-500"
                                                                {{ $status == 'BK' ? 'checked' : '' }}>
                                                        </label>
                                                    </td>
                                                    {{-- File Upload --}}
                                                    <td class="px-4 py-5 align-top">
                                                        <input type="file"
                                                            name="respon[{{ $idKuk }}][bukti]"
                                                            class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        
                                                        @if($dataJawaban && $dataJawaban->bukti_asesi_apl02)
                                                            <div class="mt-2">
                                                                <a href="{{ asset('storage/'.$dataJawaban->bukti_asesi_apl02) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 underline flex items-center font-semibold">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                                    Lihat Bukti Terupload
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="lg:hidden p-3 sm:p-4 space-y-4">
                                @foreach ($unit->elemen as $indexElemen => $elemen)
                                    @foreach ($elemen->kriteria as $indexKuk => $kuk)
                                        @php
                                            $idKuk = $kuk->id_kriteria_unjuk_kerja; 
                                            $dataJawaban = $jawaban[$idKuk] ?? null;
                                            $status = $dataJawaban ? ($dataJawaban->respon_asesi_apl02 == 1 ? 'K' : 'BK') : '';
                                        @endphp

                                        <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-4 shadow-sm">
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="bg-gray-900 text-white text-xs font-bold px-2 py-1 rounded">
                                                    KUK {{ $indexElemen + 1 }}.{{ $indexKuk + 1 }}
                                                </span>
                                            </div>

                                            <p class="text-sm text-gray-700 mb-4 leading-relaxed font-semibold">
                                                {{ $kuk->kriteria_unjuk_kerja }}
                                            </p>

                                            <div class="mb-4">
                                                <label class="block text-xs font-bold text-gray-700 mb-2">Penilaian Diri:</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <label class="flex items-center justify-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all has-[:checked]:border-green-600 has-[:checked]:bg-green-100">
                                                        <input type="radio"
                                                            name="respon[{{ $idKuk }}][status]"
                                                            value="K" class="w-4 h-4 text-green-600 mr-2"
                                                            {{ $status == 'K' ? 'checked' : '' }} required>
                                                        <span class="text-sm font-bold text-green-700">Kompeten</span>
                                                    </label>
                                                    <label
                                                        class="flex items-center justify-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 hover:bg-red-50 transition-all has-[:checked]:border-red-600 has-[:checked]:bg-red-100">
                                                        <input type="radio"
                                                            name="respon[{{ $idKuk }}][status]"
                                                            value="BK" class="w-4 h-4 text-red-600 mr-2"
                                                            {{ $status == 'BK' ? 'checked' : '' }}>
                                                        <span class="text-sm font-bold text-red-700">Belum Kompeten</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-2">Bukti Pendukung:</label>
                                                <input type="file"
                                                    name="respon[{{ $idKuk }}][bukti]"
                                                    class="block w-full text-xs text-gray-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                
                                                @if($dataJawaban && $dataJawaban->bukti_asesi_apl02)
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/'.$dataJawaban->bukti_asesi_apl02) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 underline font-bold">
                                                            Lihat Bukti Terupload
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Tanda Tangan (Read Only) --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4 sm:p-6 shadow-lg mb-8">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Rekomendasi & Tanda Tangan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                {{-- Asesi --}}
                <div class="bg-white rounded-xl p-4 sm:p-5 shadow-md border border-gray-200">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesi</label>
                    <div class="w-full h-40 sm:h-48 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center overflow-hidden">
                        <div class="text-center">
                            @php
                                $ttdAsesiBase64 = getTtdBase64($sertifikasi->asesi->tanda_tangan ?? null, null, 'asesi');
                            @endphp
                            @if($ttdAsesiBase64)
                                <img src="{{ $ttdAsesiBase64 }}" 
                                     alt="Tanda Tangan Asesi" 
                                     class="h-32 object-contain mx-auto">
                            @else
                                <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <p class="text-xs sm:text-sm text-gray-500 mt-2 font-medium">Tanda tangan belum tersedia di profil.</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-bold text-gray-900">{{ $sertifikasi->asesi->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500 mt-1">Tanggal: {{ now()->format('d-m-Y') }}</p>
                    </div>
                </div>

                {{-- Asesor --}}
                <div class="bg-white rounded-xl p-4 sm:p-5 shadow-md border border-gray-200 opacity-60">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Rekomendasi Asesor</label>
                    <div class="w-full h-40 sm:h-48 bg-gray-100 border-2 border-gray-300 rounded-xl flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            <p class="text-xs sm:text-sm text-gray-400 italic mt-2">Menunggu Verifikasi Asesor</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-bold text-gray-900">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Navigasi --}}
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 mt-8 sm:mt-12 border-t-2 border-gray-200 pt-6">
            
            <a href="{{ route('tracker') }}" class="px-6 sm:px-8 py-2.5 sm:py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 hover:border-gray-400 transition shadow-sm text-center flex items-center justify-center">
                Kembali
            </a>

            <button type="submit"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center flex items-center justify-center">
                Simpan & Lanjutkan
            </button>
        </div>

        </form>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                acc.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.classList.toggle('active');

                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    
                    // Rotate icon logic
                    const icon = this.querySelector('.accordion-icon');
                    if(isExpanded) {
                        icon.classList.remove('rotate-180');
                    } else {
                        icon.classList.add('rotate-180');
                    }
                });
            });
        });
    </script>
@endsection