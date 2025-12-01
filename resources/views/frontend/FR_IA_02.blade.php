{{-- Menggunakan layout sidebar yang sama --}}
@extends('layouts.app-sidebar')

@section('content')
<main class="main-content">
    {{-- 
        Ann-Note: 
        Variabel yang dikirim dari Controller:
        - $sertifikasi (DataSertifikasiAsesi)
        - $jadwal (Jadwal)
        - $asesi (Asesi)
        - $skema (Skema)
        - $skenario (SkenarioIa02)
        - $unitKompetensis (Kumpulan MasterUnitKompetensi)
    --}}
    
    <x-header_form.header_form title="FR.IA.02. TPD - TUGAS PRAKTIK DEMONSTRASI" />

    {{-- Pesan Sukses Setelah Menyimpan --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form di-wrap di sini, menunjuk ke route 'store' --}}
    <form class="form-body" method="POST" action="{{ route('fr-ia-02.store', $sertifikasi->id_data_sertifikasi_asesi) }}">
        @csrf 

        <!-- Box Info Atas (Dinamis) -->
        {{-- Menggunakan komponen identitas_skema_form dengan data dinamis --}}
        <x-identitas_skema_form.identitas_skema_form
            :skema="$skema->nama_skema ?? ''"
            :nomorSkema="$skema->kode_unit ?? ''" 
            :tuk="$jadwal->tuk->nama_lokasi ?? 'Tempat Kerja'" {{-- Asumsi relasi TUK di Jadwal --}}
            :namaAsesor="$jadwal->asesor->nama_lengkap ?? ''"
            :namaAsesi="$asesi->nama_lengkap ?? ''"
            :tanggal="optional($jadwal->tanggal_pelaksanaan)->format('d F Y') ?? date('d F Y')" 
        />

        <!-- Box Petunjuk (Tetap Statis) -->
        <div class="bg-blue-50 p-6 rounded-md shadow-sm mb-6 border border-blue-200">
            <h3 class="text-lg font-bold text-blue-800 mb-3">Petunjuk</h3>
            <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                <li>Baca dan pelajari setiap instruksi kerja di bawah ini dengan cermat sebelum melaksanakan praktek.</li>
                <li>Klarifikasi kepada asesor kompetensi apabila ada hal-hal yang belum jelas.</li>
                <li>Laksanakan pekerjaan sesuai dengan urutan proses yang sudah ditetapkan.</li>
                <li>Seluruh proses kerja mengacu kepada SOP/WI yang dipersyaratkan (Jika Ada).</li>
            </ul>
        </div>

        <!-- Box Skenario Tugas (Dinamis) -->
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h3 class="text-black font-bold text-gray-800 mb-4">Skenario Tugas Praktik Demonstrasi</h3>

            <!-- Tabel Kelompok Pekerjaan (Dinamis) -->
            <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            {{-- TODO: Buat 'Kelompok Pekerjaan' dinamis jika perlu --}}
                            <th class="p-3 text-left font-medium w-[25%]">Kelompok Pekerjaan ...</th> 
                            <th class="p-3 text-left font-medium w-[10%]">No.</th>
                            <th class="p-3 text-left font-medium w-[25%]">Kode Unit</th>
                            <th class="p-3 text-left font-medium w-[40%]">Judul Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        
                        {{-- Loop data Unit Kompetensi --}}
                        @forelse ($unitKompetensis as $index => $uk)
                            <tr>
                                @if ($loop->first)
                                    {{-- Asumsi semua unit dari 1 kelompok, atau Anda bisa buat logika grouping --}}
                                    <td class="p-3" rowspan="{{ $unitKompetensis->count() }}">
                                        {{ $uk->kelompokPekerjaan->nama_kelompok ?? 'Kelompok Pekerjaan 1' }}
                                    </td>
                                @endif
                                <td class="p-3 text-center">{{ $index + 1 }}.</td>
                                <td class="p-3">
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded-md bg-gray-50" 
                                           value="{{ $uk->kode_unit }}" readonly>
                                </td>
                                <td class="p-3">
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded-md bg-gray-50" 
                                           value="{{ $uk->judul_unit }}" readonly>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">
                                    Data Unit Kompetensi tidak ditemukan untuk Skema ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Skenario, Perlengkapan, Waktu (Dinamis) -->
            <div class="space-y-4">
                <div>
                    <label for="skenario" class="text-black font-medium mb-3">Skenario Tugas Praktik Demonstrasi:</label>
                    <textarea id="skenario" name="skenario" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                              rows="4" placeholder="Jelaskan skenario tugas di sini...">{{ old('skenario', $skenario->skenario) }}</textarea>
                    @error('skenario') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="peralatan" class="text-black font-medium mb-3">Perlengkapan dan Peralatan:</label>
                    <textarea id="peralatan" name="peralatan" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                              rows="2" placeholder="Contoh: Laptop, Teks Editor, Web Browser...">{{ old('peralatan', $skenario->peralatan) }}</textarea>
                    @error('peralatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="waktu" class="text-black font-medium mb-3">Waktu:</label>
                    <input id="waktu" name="waktu" type="text" class="w-full p-2 border border-gray-300 rounded-md" 
                           placeholder="Contoh: 120 Menit" value="{{ old('waktu', $skenario->waktu) }}">
                    @error('waktu') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Komponen Tanda Tangan (Masih Statis sesuai file Anda) --}}
        {{-- TODO: Modifikasi komponen ini agar menerima data dinamis --}}
        @include('components.kolom_ttd.asesiasesor')
        
        {{-- Komponen Penyusun & Validator (Masih Statis sesuai file Anda) --}}
        {{-- TODO: Modifikasi komponen ini agar menerima data dinamis --}}
        @include('components.kolom_ttd.penyusunvalidator')

        <!-- Tombol Footer (Dinamis) -->
        <div class="mt-8 flex justify-between">
            {{-- Tombol Kembali --}}
            <a href="{{ url()->previous() }}"> {{-- Kembali ke halaman sebelumnya --}}
                <x-secondary-button type="button">
                    {{ __('Kembali') }}
                </x-secondary-button>
            </a>
            
            {{-- Tombol Simpan --}}
            <x-primary-button type="submit">
                {{ __('Simpan Form') }}
            </x-primary-button>
        </div>

    </form> {{-- Akhir dari Form --}}
</main>
@endsection
