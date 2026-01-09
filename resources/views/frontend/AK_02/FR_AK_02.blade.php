@extends('layouts.app-sidebar')

@section('content')
    {{-- Style Internal untuk checkbox custom --}}
    <style>
        .custom-checkbox:checked {
            background-color: #2563eb; /* blue-600 */
            border-color: #2563eb;
        }
    </style>

    <x-header_form.header_form title="FR.AK.02. REKAMAN ASESMEN KOMPETENSI" /><br>

    {{-- Form mengarah ke Route Update di Ak02Controller --}}
    {{-- Pastikan route 'ak02.update' sudah didefinisikan di routes/auth.php atau web.php --}}
    <form action="{{ route('asesor.ak02.update', $asesi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Container Utama --}}
        <div class="p-3 sm:p-4 md:p-6">

            {{-- 1. IDENTITAS SKEMA (Menggunakan Component) --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Identitas Skema & Peserta</h3>
                {{-- Kita bisa passing data asesi ke component jika component mendukung --}}
                <x-identitas_skema_form.identitas_skema_form :asesi="$asesi" />
            </div>

            {{-- 2. TABEL KELOMPOK PEKERJAAN & UNIT KOMPETENSI --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Kelompok Pekerjaan</h3>

                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 border-collapse">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[25%] border-r border-gray-700">Kelompok Pekerjaan</th>
                                <th class="px-4 py-4 text-center text-xs font-bold uppercase w-12 border-r border-gray-700">No.</th>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase w-[20%] border-r border-gray-700">Kode Unit</th>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase">Judul Unit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php $no = 1; @endphp

                            {{-- Loop Kelompok Pekerjaan --}}
                            @foreach ($asesi->skema->kelompokPekerjaan as $kelompok)
                                @php
                                    $jumlahUnit = $kelompok->unitKompetensi->count();
                                @endphp

                                {{-- Loop Unit di dalam Kelompok --}}
                                @foreach ($kelompok->unitKompetensi as $index => $unit)
                                    <tr class="hover:bg-blue-50 transition-colors">

                                        {{-- LOGIC ROWSPAN: Tampilkan Nama Kelompok hanya di baris pertama --}}
                                        @if ($index === 0)
                                            <td rowspan="{{ $jumlahUnit }}" class="p-4 align-top border-r border-gray-200 bg-gray-50">
                                                <span class="font-semibold text-gray-700 text-sm">
                                                    {{ $kelompok->nama_kelompok_pekerjaan }}
                                                </span>
                                            </td>
                                        @endif

                                        {{-- Nomor Urut --}}
                                        <td class="px-4 py-4 text-center font-bold text-gray-700 border-r border-gray-200 align-top">
                                            {{ $no++ }}.
                                        </td>

                                        {{-- Kode Unit (Read Only) --}}
                                        <td class="px-4 py-4 border-r border-gray-200 align-top font-mono text-sm text-gray-600">
                                            {{ $unit->kode_unit }}
                                        </td>

                                        {{-- Judul Unit (Read Only) --}}
                                        <td class="px-4 py-4 align-top text-sm text-gray-800 font-medium">
                                            {{ $unit->judul_unit }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. TABEL BUKTI KOMPETENSI (MATRIX CHECKBOX) --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Bukti-Bukti Kompetensi</h3>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase min-w-[200px]">Unit Kompetensi</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Observasi Demonstrasi</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Portofolio</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-24">Pernyataan Pihak Ketiga</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Pertanyaan Lisan</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Pertanyaan Tertulis</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Proyek Kerja</th>
                                <th class="px-2 py-4 text-center text-xs font-bold uppercase w-20">Lainnya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            {{-- Kita Loop ulang untuk Matrix Bukti --}}
                            @foreach ($asesi->skema->kelompokPekerjaan as $kelompok)
                                @foreach ($kelompok->unitKompetensi as $unit)

                                    {{-- Ambil Data Penilaian dari Controller (Collection terpisah) --}}
                                    @php
                                        $nilai = $penilaianList->get($unit->id_unit_kompetensi);
                                        $buktiTerpilih = $nilai ? $nilai->jenis_bukti : []; // Array JSON
                                    @endphp

                                    <tr class="hover:bg-blue-50 transition-colors">
                                        {{-- Judul Unit --}}
                                        <td class="px-4 py-3 font-medium text-gray-800">
                                            {{ $unit->judul_unit }}
                                            {{-- Hidden ID Unit wajib ada untuk array key --}}
                                            <input type="hidden" name="penilaian[{{ $unit->id_unit_kompetensi }}][id_unit]" value="{{ $unit->id_unit_kompetensi }}">
                                        </td>

                                        {{-- Loop Checkbox --}}
                                        @php
                                            $opsiBukti = ['observasi', 'portofolio', 'pihak_ketiga', 'lisan', 'tertulis', 'proyek', 'lainnya'];
                                        @endphp

                                        @foreach ($opsiBukti as $type)
                                            <td class="px-2 py-3 text-center align-middle">
                                                <input type="checkbox"
                                                       {{-- Name Array: penilaian[ID_UNIT][jenis_bukti][] --}}
                                                       name="penilaian[{{ $unit->id_unit_kompetensi }}][jenis_bukti][]"
                                                       value="{{ $type }}"
                                                       {{-- Cek apakah sudah dicentang sebelumnya --}}
                                                       {{ is_array($buktiTerpilih) && in_array($type, $buktiTerpilih) ? 'checked' : '' }}
                                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer transition custom-checkbox">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. REKOMENDASI & TINDAK LANJUT --}}
            {{-- Catatan: Di Controller, data ini akan disimpan ke SEMUA baris unit (karena tabelnya per unit) --}}
            @php
                // Ambil data global dari salah satu penilaian pertama (jika ada)
                $firstNilai = $penilaianList->first();
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- Rekomendasi Hasil --}}
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Rekomendasi Hasil Asesmen</h3>
                    <div class="flex-grow flex flex-col justify-center gap-4">
                        {{-- Kompeten --}}
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-500 transition-all has-[:checked]:bg-green-100 has-[:checked]:border-green-600">
                            <input type="radio" name="global_kompeten" value="Kompeten"
                                   {{ optional($firstNilai)->kompeten == 'Kompeten' ? 'checked' : '' }}
                                   class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500">
                            <span class="ml-3 font-bold text-gray-700 text-lg">Kompeten</span>
                        </label>

                        {{-- Belum Kompeten --}}
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-red-50 hover:border-red-500 transition-all has-[:checked]:bg-red-100 has-[:checked]:border-red-600">
                            <input type="radio" name="global_kompeten" value="Belum Kompeten"
                                   {{ optional($firstNilai)->kompeten == 'Belum Kompeten' ? 'checked' : '' }}
                                   class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-3 font-bold text-gray-700 text-lg">Belum Kompeten</span>
                        </label>
                    </div>
                </div>

                {{-- Tindak Lanjut --}}
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Tindak Lanjut yang Dibutuhkan</h3>
                    <textarea name="global_tindak_lanjut" rows="5"
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm resize-none placeholder-gray-400 p-3"
                        placeholder="Tuliskan tindak lanjut jika ada...">{{ optional($firstNilai)->tindak_lanjut }}</textarea>
                </div>
            </div>

            {{-- 5. KOMENTAR ASESOR --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Komentar / Observasi Asesor</h3>
                <textarea name="global_komentar" rows="3"
                    class="block w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400 p-3"
                    placeholder="Catatan tambahan dari asesor...">{{ optional($firstNilai)->komentar }}</textarea>
            </div>

            {{-- 6. TANDA TANGAN (Hanya Tampilan) --}}
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4 sm:p-6 shadow-lg mb-8">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-6">Tanda Tangan Persetujuan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    {{-- TTD Asesor --}}
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesor</label>
                        <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-50 hover:bg-blue-50 transition-all group">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mt-2 font-medium group-hover:text-blue-600">Klik untuk TTD Asesor</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 bg-transparent" value="{{ Auth::user()->name ?? 'Nama Asesor' }}" readonly>
                        </div>
                    </div>

                    {{-- TTD Asesi --}}
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesi</label>
                        <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mt-2 font-medium">TTD Asesi</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <input type="text" class="w-full text-sm font-bold text-gray-900 border-0 border-b-2 border-gray-300 bg-transparent" value="{{ $asesi->user->name ?? 'Nama Asesi' }}" readonly>
                            <p class="text-xs text-gray-500 mt-1">Tanggal: {{ date('d-m-Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. FOOTER BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mt-8 border-t-2 border-gray-200 pt-6">
                <a href="{{ url()->previous() }}" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 transition text-center shadow-sm">
                    Kembali
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    Simpan Form FR.AK.02
                </button>
            </div>

        </div>
    </form>
@endsection