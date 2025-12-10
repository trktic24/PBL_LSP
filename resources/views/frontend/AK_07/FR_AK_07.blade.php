@extends('layouts.app-sidebar')

@section('content')
<div class="p-4 sm:p-6 md:p-8 font-[Poppins]">

    {{-- 1. HEADER FORM --}}
    <x-header_form.header_form title="FR.AK.07. CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN" />

    <div class="mt-6"></div>

    {{-- 2. IDENTITAS SKEMA (Dinamis dari Relasi Model) --}}
    <div class="bg-white p-6 rounded-md shadow-sm border border-gray-200 mb-6">
        {{-- Mengambil data dari variabel $sertifikasi yang dikirim Controller --}}
        <x-identitas_skema_form
            {{-- SEKARANG BISA PANGGIL LANGSUNG ->skema --}}
            skema="{{ $sertifikasi->skema->nama_skema ?? '-' }}"
            nomorSkema="{{ $sertifikasi->skema->nomor_skema ?? '-' }}"

            {{-- TUK --}}
            tuk="{{ $sertifikasi->jadwal->tuk->nama_tuk ?? '-' }}"

            {{-- ASESOR & ASESI --}}
            namaAsesor="{{ $sertifikasi->asesor->nama_lengkap ?? '-' }}"
            namaAsesi="{{ $sertifikasi->asesi->nama_lengkap ?? '-' }}"

            tanggal="{{ \Carbon\Carbon::parse($sertifikasi->tanggal_daftar)->format('d-m-Y') }}" />
    </div>

    {{-- 3. PANDUAN --}}
    {{-- 3. PANDUAN --}}
    @if($isReadOnly)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-r-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-eye text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-yellow-800 uppercase">Mode Lihat Saja</h3>
                <p class="mt-1 text-sm text-yellow-700">
                    Anda sedang melihat formulir ini sebagai <strong>{{ Auth::user()->role->nama_role }}</strong>. Anda tidak dapat mengubah data.
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-blue-800 uppercase">Panduan Bagi Asesor</h3>
                <p class="mt-1 text-sm text-blue-700">
                    Formulir ini digunakan jika ada asesi yang mempunyai keterbatasan karakteristik sehingga diperlukan penyesuaian wajar.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- 4. FORM UTAMA --}}
    <form action="{{ route('asesor.fr-ak-07.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf

        {{-- BAGIAN A: POTENSI ASESI (Looping Database) --}}
        <div class="bg-white rounded-md shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">A. POTENSI ASESI</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3">
                @forelse($masterPotensi as $potensi)
                <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded transition">
                    {{-- Value adalah ID dari tabel master --}}
                    <input type="checkbox"
                        name="potensi_asesi[]"
                        value="{{ $potensi->id_poin_potensi_AK07 }}"
                        {{ $isReadOnly ? 'disabled' : '' }}
                        class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">{{ $potensi->deskripsi_potensi }}</span>
                </label>
                @empty
                <p class="text-red-500 italic text-sm">Data Master Potensi belum di-seed. Silakan jalankan Seeder.</p>
                @endforelse
            </div>
        </div>

        {{-- BAGIAN B: CEKLIS PENYESUAIAN (Looping Database Q1-Q7) --}}
        <div class="bg-white rounded-md shadow-sm border border-gray-200 mb-8">
            <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">B. IDENTIFIKASI PERSYARATAN MODIFIKASI</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-bold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3 w-1/3">Karakteristik Asesi</th>
                            <th class="px-4 py-3 w-24 text-center">Penyesuaian?</th>
                            <th class="px-4 py-3">Keterangan / Bentuk Penyesuaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @forelse($masterPersyaratan as $soal)
                        {{-- Gunakan Alpine.js untuk interaksi Ya/Tidak --}}
                        <tr class="bg-white hover:bg-gray-50 transition" x-data="{ showOptions: false }">

                            <td class="px-4 py-4 text-center font-semibold align-top">{{ $loop->iteration }}</td>

                            {{-- Pertanyaan dari DB --}}
                            <td class="px-4 py-4 align-top">
                                <p class="font-medium text-gray-900">{{ $soal->pertanyaan_karakteristik }}</p>
                            </td>

                            {{-- Radio Button (Ya/Tidak) --}}
                            <td class="px-4 py-4 text-center align-top">
                                <div class="flex flex-col items-center gap-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio"
                                            name="penyesuaian[{{ $soal->id_persyaratan_modifikasi_AK07 }}][status]"
                                            value="Ya"
                                            @click="showOptions = true"
                                            {{ $isReadOnly ? 'disabled' : '' }}
                                            class="text-blue-600 form-radio focus:ring-blue-500">
                                        <span class="ml-1">Ya</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio"
                                            name="penyesuaian[{{ $soal->id_persyaratan_modifikasi_AK07 }}][status]"
                                            value="Tidak"
                                            @click="showOptions = false"
                                            checked
                                            {{ $isReadOnly ? 'disabled' : '' }}
                                            class="text-gray-600 form-radio focus:ring-gray-500">
                                        <span class="ml-1">Tidak</span>
                                    </label>
                                </div>
                            </td>

                            {{-- Checkbox Opsi (Muncul jika Ya) --}}
                            <td class="px-4 py-4 align-top">
                                {{-- Area Opsi --}}
                                <div x-show="showOptions"
                                    x-transition.opacity.duration.300ms
                                    class="space-y-2">

                                    {{-- Looping Opsi Keterangan (Nested Loop) --}}
                                    @if($soal->catatanKeterangan->isNotEmpty())
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">
                                        @foreach($soal->catatanKeterangan as $opsi)
                                        <label class="flex items-start space-x-2 cursor-pointer">
                                            <input type="checkbox"
                                                name="penyesuaian[{{ $soal->id_persyaratan_modifikasi_AK07 }}][keterangan][]"
                                                value="{{ $opsi->id_catatan_keterangan_AK07 }}"
                                                {{ $isReadOnly ? 'disabled' : '' }}
                                                class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="leading-snug">{{ $opsi->isi_opsi }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @endif

                                    {{-- Input Manual Tambahan (Opsional) --}}
                                    <input type="text"
                                        name="penyesuaian[{{ $soal->id_persyaratan_modifikasi_AK07 }}][catatan_manual]"
                                        placeholder="Catatan tambahan (opsional)..."
                                        {{ $isReadOnly ? 'disabled' : '' }}
                                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                {{-- Pesan jika Tidak --}}
                                <div x-show="!showOptions" class="text-gray-400 text-xs italic mt-2 text-center">
                                    - Tidak ada penyesuaian -
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-red-500">Data Master Soal belum di-seed.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- BAGIAN C: HASIL KESEPAKATAN (Input Text) --}}
        <div class="bg-white p-6 rounded-md shadow-sm border border-gray-200 mb-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Hasil Penyesuaian yang Wajar dan Beralasan</h3>
            <div class="grid grid-cols-1 gap-6">

                <x-kolom_form.kolom_form
                    label="1) Acuan Pembanding Asesmen"
                    id="acuan_pembanding"
                    name="acuan_pembanding"
                    :disabled="$isReadOnly"
                    type="text" />

                <x-kolom_form.kolom_form
                    label="2) Metode Asesmen"
                    id="metode_asesmen"
                    name="metode_asesmen"
                    :disabled="$isReadOnly"
                    type="text" />

                <x-kolom_form.kolom_form
                    label="3) Instrumen Asesmen"
                    id="instrumen_asesmen"
                    name="instrumen_asesmen"
                    :disabled="$isReadOnly"
                    type="text" />

            </div>
        </div>

        {{-- TANDA TANGAN --}}
        <x-kolom_ttd.asesiasesor
            :sertifikasi="$sertifikasi"
            :showAsesi="true"
            :showAsesor="true" />

        {{-- NAVIGASI BUTTONS --}}
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-200 pt-6">
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-center flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if(!$isReadOnly)
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                Simpan & Lanjutkan <i class="fas fa-arrow-right"></i>
            </button>
            @endif
        </div>

    </form>
</div>
@endsection