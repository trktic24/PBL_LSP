@extends('layouts.app-sidebar')

@section('content')
<div class="p-5">
    
    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- FORM UTAMA --}}
    @if($sertifikasi)
    <form action="{{ route('ia02.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf

        {{-- KOMPONEN 1: Header Form --}}
        {{-- Menggunakan component x-header_form --}}
        <x-header_form.header_form title="FR.IA.02. TPD - TUGAS PRAKTIK DEMONSTRASI" />

        {{-- KOMPONEN 2: Identitas Skema --}}
        {{-- Menggunakan component x-identitas_skema_form --}}
        {{-- Asumsi component ini menerima prop :sertifikasi --}}
        <div class="mb-6">
            <x-identitas_skema_form.identitas_skema_form 
                :sertifikasi="$sertifikasi" 
            />
        </div>

        {{-- Petunjuk (Manual karena biasanya statis per form) --}}
        <div class="bg-blue-50 p-6 rounded-md shadow-sm mb-6 border border-blue-200">
            <h3 class="text-lg font-bold text-blue-800 mb-3">Petunjuk</h3>
            <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                <li>Baca dan pelajari setiap instruksi kerja di bawah ini dengan cermat sebelum melaksanakan praktek.</li>
                <li>Klarifikasi kepada asesor kompetensi apabila ada hal-hal yang belum jelas.</li>
                <li>Laksanakan pekerjaan sesuai dengan urutan proses yang sudah ditetapkan.</li>
                <li>Seluruh proses kerja mengacu kepada SOP/WI yang dipersyaratkan (Jika Ada).</li>
            </ul>
        </div>

        {{-- KONTEN UTAMA: SKENARIO (Belum ada component khusus, kita buat style yang senada) --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Skenario Tugas Praktik Demonstrasi</h3>

            <!-- Tabel Unit Kompetensi -->
            <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left font-medium w-[10%] border-b">No.</th>
                            <th class="p-3 text-left font-medium w-[25%] border-b">Kode Unit</th>
                            <th class="p-3 text-left font-medium w-[65%] border-b">Judul Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($sertifikasi->skema->unitKompetensis ?? [] as $index => $unit)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 text-center">{{ $loop->iteration }}.</td>
                                <td class="p-3 font-mono">{{ $unit->kode_unit }}</td>
                                <td class="p-3">{{ $unit->judul_unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-3 text-center text-gray-500 italic">Unit Kompetensi tidak ditemukan pada skema ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Input Area dengan Logic Role -->
            <div class="space-y-6">
                
                {{-- 1. SKENARIO --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Skenario Tugas Praktik Demonstrasi <span class="text-red-500">*</span>
                    </label>
                    @if($isAdmin)
                        <textarea name="skenario" class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm transition" rows="6" placeholder="Masukkan instruksi skenario lengkap untuk Asesi di sini...">{{ old('skenario', $ia02->skenario ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Dapat diedit oleh Admin.</p>
                    @else
                        <div class="w-full p-4 bg-gray-50 border border-gray-200 rounded-md text-gray-800 whitespace-pre-line min-h-[100px]">
                            {!! nl2br(e($ia02->skenario ?? 'Belum ada instruksi skenario dari Admin/Asesor.')) !!}
                        </div>
                    @endif
                </div>

                {{-- 2. PERALATAN --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Perlengkapan dan Peralatan <span class="text-red-500">*</span>
                    </label>
                    @if($isAdmin)
                        <textarea name="peralatan" class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm transition" rows="3" placeholder="Contoh: Laptop, Teks Editor VS Code, Web Browser Chrome...">{{ old('peralatan', $ia02->peralatan ?? '') }}</textarea>
                    @else
                        <div class="w-full p-4 bg-gray-50 border border-gray-200 rounded-md text-gray-800 whitespace-pre-line">
                            {!! nl2br(e($ia02->peralatan ?? '-')) !!}
                        </div>
                    @endif
                </div>

                {{-- 3. WAKTU --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Waktu Pengerjaan <span class="text-red-500">*</span>
                    </label>
                    @if($isAdmin)
                        <input 
                            type="time" 
                            name="waktu"
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm w-1/3"
                            value="{{ old('waktu', isset($ia02->waktu) ? \Carbon\Carbon::parse($ia02->waktu)->format('H:i') : '') }}"
                        >
                    @else
                        <div class="w-full p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-800 font-medium inline-block w-1/3">
                            {{ $ia02->waktu ?? '-' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOMPONEN 3: Tanda Tangan --}}
        {{-- Menggunakan component x-kolom_ttd.asesiasesor --}}
        <div class="mb-6">
            <x-kolom_ttd.asesiasesor 
                :sertifikasi="$sertifikasi" 
                :tanggal="\Carbon\Carbon::now()"
            />
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-8 flex justify-end gap-4 sticky bottom-4 bg-white/80 p-4 backdrop-blur-sm rounded-lg shadow-md border border-gray-100">
            <a href="{{ route('daftar_asesi') }}" class="bg-white text-gray-700 border border-gray-300 font-medium py-2 px-6 rounded-md hover:bg-gray-50 transition shadow-sm">
                Kembali
            </a>
            
            @if($isAdmin)
                <button type="submit" class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700 shadow-lg transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan Instruksi
                </button>
            @endif
        </div>

    </form>
    @else
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center" role="alert">
            <strong class="font-bold text-lg">Data Tidak Ditemukan</strong>
            <p class="mt-2">Silakan kembali ke halaman daftar asesi.</p>
            <a href="{{ route('daftar_asesi') }}" class="inline-block mt-4 bg-red-200 hover:bg-red-300 text-red-800 px-4 py-2 rounded transition">Kembali</a>
        </div>
    @endif
</div>
@endsection