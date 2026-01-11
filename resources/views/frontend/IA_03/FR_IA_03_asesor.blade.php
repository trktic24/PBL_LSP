@extends('layouts.app-sidebar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- STATUS READONLY NOTIFICATION --}}
    @if(isset($isCompleted) && $isCompleted)
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-blue-800">Form ini sudah diselesaikan dan dalam mode tampilan saja (read-only)</p>
        </div>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">FR.IA.03 - Pertanyaan Mendukung Observasi</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Mode Asesor - {{ ($isCompleted ?? false) ? 'Tampilan Read-Only' : 'Isi Tanggapan & Pencapaian' }}
                </p>
            </div>
        </div>
    
        {{-- Informasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Skema Sertifikasi</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $skema->nama_skema ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">No. Skema</span>
                    <span>:</span>
                    <span class="text-gray-900">{{ $skema->nomor_skema ?? '-' }}</span>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Nama Asesor</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $asesor->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Nama Asesi</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $asesi->nama_lengkap ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- PANDUAN ASESOR --}}
    @if(!($isCompleted ?? false))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-blue-900 mb-3">Panduan Bagi Asesor</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex"><span class="font-bold mr-2">•</span><span>Formulir ini di isi oleh asesor kompetensi sebelum, pada saat atau setelah melakukan asesmen dengan metode observasi demonstrasi.</span></li>
                    <li class="flex"><span class="font-bold mr-2">•</span><span>Pertanyaan dibuat untuk menggali dimensi kompetensi, batasan variabel dan aspek kritis yang relevan dengan skenario tugas/praktik demonstrasi.</span></li>
                    <li class="flex"><span class="font-bold mr-2">•</span><span>Jika pertanyaan disampaikan sebelum praktik demonstrasi, fokus pada K3L, SOP, penggunaan peralatan/perlengkapan.</span></li>
                    <li class="flex"><span class="font-bold mr-2">•</span><span>Jika setelah praktik demonstrasi item pertanyaan pendukung observasi sudah terpenuhi, cukup beri catatan di kolom tanggapan.</span></li>
                    <li class="flex"><span class="font-bold mr-2">•</span><span>Jika ada hal yang perlu dikonfirmasi dan pertanyaan tidak ada di instrumen, asesor dapat menambah pertanyaan yang relevan dengan praktik demonstrasi.</span></li>
                    <li class="flex"><span class="font-bold mr-2">•</span><span>Tanggapan asesi ditulis pada kolom tanggapan.</span></li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- FORM START --}}
    <form method="POST" action="{{ route('asesor.ia03.update', $sertifikasi->id_data_sertifikasi_asesi) }}">
        @csrf
        @method('PUT')

        {{-- LOOP KELOMPOK PEKERJAAN --}}
        @foreach($kelompokPekerjaan as $kelompokIndex => $kelompok)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
                {{-- Header Kelompok --}}
                <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                    <h2 class="text-2xl font-bold text-blue-800 mb-1">Kelompok Pekerjaan {{ $kelompokIndex + 1 }}</h2>
                    <p class="text-blue-600 text-sm font-medium">{{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}</p>
                </div>

                {{-- Daftar Unit Kompetensi --}}
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                    <div class="overflow-hidden rounded-lg border border-gray-300">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    <th class="p-3 border-b border-gray-300 w-16 text-center">No</th>
                                    <th class="p-3 border-b border-gray-300">Kode Unit</th>
                                    <th class="p-3 border-b border-gray-300">Judul Unit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($kelompok->unitKompetensi ?? [] as $i => $unit)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-3 text-center font-medium text-gray-700">{{ $i + 1 }}</td>
                                        <td class="p-3 font-medium text-gray-700">{{ $unit->kode_unit ?? '-' }}</td>
                                        <td class="p-3 text-gray-700">{{ $unit->judul_unit ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-6 text-center text-gray-500">Tidak ada unit kompetensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pertanyaan & Jawaban --}}
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pertanyaan dan Tanggapan</h3>
                    
                    @php
                        $pertanyaanKelompok = $pertanyaanPerKelompok[$kelompok->id_kelompok_pekerjaan] ?? collect();
                    @endphp

                    @forelse($pertanyaanKelompok as $pIndex => $pertanyaan)
                        @php
                            $tanggapan = old('tanggapan.' . $pertanyaan->id_IA03, $pertanyaan->tanggapan);
                            $pencapaian = old('pencapaian.' . $pertanyaan->id_IA03, $pertanyaan->pencapaian);

                            if (empty(trim($tanggapan))) {
                                $pencapaian = null;
                            }
                        @endphp
                        <div class="mb-6 p-4 {{ ($isCompleted ?? false) ? 'bg-gray-100' : 'bg-gray-50' }} border border-gray-200 rounded-lg">
                            {{-- Pertanyaan (readonly) --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan {{ $pIndex + 1 }}:</label>
                                <textarea rows="2" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">{{ $pertanyaan->pertanyaan }}</textarea>
                            </div>

                            {{-- Hidden ID --}}
                            <input type="hidden" name="id_ia03[]" value="{{ $pertanyaan->id_IA03 }}">

                            {{-- Tanggapan --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggapan:</label>
                                <textarea name="tanggapan[{{ $pertanyaan->id_IA03 }}]" rows="3"
                                    {{ ($isCompleted ?? false) ? 'readonly' : '' }}
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg {{ ($isCompleted ?? false) ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}"
                                    placeholder="{{ ($isCompleted ?? false) ? '' : 'Tuliskan tanggapan...' }}">{{ $tanggapan }}</textarea>
                            </div>

                            {{-- Pencapaian --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pencapaian:</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center {{ ($isCompleted ?? false) ? 'cursor-not-allowed' : '' }}">
                                        <input type="radio" name="pencapaian[{{ $pertanyaan->id_IA03 }}]" value="1"
                                            {{ $pencapaian === '1' || $pencapaian === 1 ? 'checked' : '' }}
                                            {{ ($isCompleted ?? false) ? 'disabled' : '' }}
                                            class="form-radio text-green-600 {{ ($isCompleted ?? false) ? 'cursor-not-allowed' : '' }}">
                                        <span class="ml-2 text-sm {{ ($isCompleted ?? false) ? 'text-gray-500' : '' }}">Ya</span>
                                    </label>
                                    <label class="inline-flex items-center {{ ($isCompleted ?? false) ? 'cursor-not-allowed' : '' }}">
                                        <input type="radio" name="pencapaian[{{ $pertanyaan->id_IA03 }}]" value="0"
                                            {{ $pencapaian === '0' || $pencapaian === 0 ? 'checked' : '' }}
                                            {{ ($isCompleted ?? false) ? 'disabled' : '' }}
                                            class="form-radio text-red-600 {{ ($isCompleted ?? false) ? 'cursor-not-allowed' : '' }}">
                                        <span class="ml-2 text-sm {{ ($isCompleted ?? false) ? 'text-gray-500' : '' }}">Tidak</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Belum ada pertanyaan untuk kelompok ini.</p>
                    @endforelse
                </div>
            </div>
        @endforeach

        {{-- Umpan Balik Umum --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Umpan Balik Umum</h3>
            <textarea name="umpan_balik_umum" rows="4"
                {{ ($isCompleted ?? false) ? 'readonly' : '' }}
                class="w-full px-4 py-2 border border-gray-300 rounded-lg {{ ($isCompleted ?? false) ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}"
                placeholder="{{ ($isCompleted ?? false) ? '' : 'Catatan atau umpan balik umum untuk asesi...' }}">{{ old('umpan_balik_umum', $umpanBalikList->first() ?? '') }}</textarea>
        </div>

        {{-- Tombol Simpan --}}
        @if(!($isCompleted ?? false))
        <div class="flex justify-end pb-8">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Jawaban
            </button>
        </div>
        @else
        <div class="flex justify-center pb-8">
            <div class="px-8 py-3 bg-gray-400 text-white font-bold rounded-lg shadow-lg flex items-center gap-2 cursor-not-allowed">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Form Sudah Diselesaikan
            </div>
        </div>
        @endif
    </form>

</div>
@endsection
