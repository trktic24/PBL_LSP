@extends('layouts.app-profil')

@section('content')

{{-- HAPUS BLOK PHP DECODE JSON DI SINI. Data questions dan units 
     otomatis di-cast menjadi Array oleh Laravel (karena setting $casts di Model IA09). --}}

<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6">
            FR.IA.09. PERTANYAAN WAWANCARA (INPUT ASESOR)
        </h1>

        {{-- FORM UTAMA UNTUK SUBMIT DATA --}}
        <form action="{{ route('ia09.asesor.update', $data->id) }}" method="POST">
            @csrf
            {{-- Menggunakan method PUT karena ini adalah operasi UPDATE --}}
            @method('PUT') 

            {{-- SKEMA DAN DATA UMUM --}}
            <div class="border border-gray-300 p-4 rounded-lg mb-6 bg-gray-50">
                <h2 class="text-xl font-semibold mb-4 text-blue-700">Skema & Data Umum</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="space-y-2">
                        {{-- Menggunakan relasi BelongsTo --}}
                        <p><strong>Skema Sertifikasi</strong>: <span class="font-normal text-gray-700">{{ $data->skema->judul ?? '...' }}</span></p>
                        <p><strong>Nomor Skema</strong>: <span class="font-normal text-gray-700">{{ $data->skema->nomor ?? '...' }}</span></p>
                        {{-- Tanggal Asesmen: Menggunakan Carbon instance hasil cast untuk format default input date --}}
                        <p><strong>Tanggal Asesmen</strong>: 
                            <input type="date" name="tanggal_asesmen" 
                                value="{{ $data->tanggal_asesmen ? $data->tanggal_asesmen->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}" 
                                class="p-1 border rounded text-gray-700">
                        </p>
                    </div>
                    <div class="space-y-2">
                        {{-- Menggunakan relasi BelongsTo --}}
                        <p><strong>Nama Asesor</strong>: <span class="font-normal text-gray-700">{{ $data->asesor->nama_lengkap ?? '...' }}</span></p>
                        <p><strong>Nama Asesi</strong>: <span class="font-normal text-gray-700">{{ $data->asesi->nama_lengkap ?? '...' }}</span></p>
                        {{-- TUK: Disimpan langsung di kolom IA09 --}}
                        <p><strong>TUK</strong>: 
                            <input type="text" name="tuk" value="{{ $data->tuk ?? 'Sewaktu' }}" class="p-1 border rounded text-gray-700">
                        </p>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4 text-red-700">INPUT Hasil Wawancara (Diisi Asesor)</h2>
            
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-5/12">Daftar Pertanyaan Wawancara</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-4/12">Kesimpulan Jawaban Asesi (INPUT)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Pencapaian (YA/TIDAK)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Menggunakan $data->questions langsung, karena sudah di-cast ke array --}}
                        @forelse($data->questions as $index => $q)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}.</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{-- Kolom Pertanyaan diubah menjadi input field agar Asesor bisa mengedit/menambahkan --}}
                                    <strong class="text-xs text-blue-600">Bukti No. 
                                        <input type="text" name="questions[{{ $index }}][no_bukti]" value="{{ $q['no_bukti'] ?? '' }}" class="p-1 border rounded w-12">
                                    </strong><br>
                                    <textarea name="questions[{{ $index }}][pertanyaan]" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2">{{ $q['pertanyaan'] ?? '' }}</textarea>
                                    
                                    {{-- Hidden input untuk ID question agar bisa di-update (optional jika tidak menggunakan Model Relasi terpisah) --}}
                                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $q['id'] ?? '' }}">
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{-- Kolom Kesimpulan Jawaban diubah menjadi textarea --}}
                                    <textarea name="questions[{{ $index }}][kesimpulan_jawaban]" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2">{{ $q['kesimpulan_jawaban'] ?? '' }}</textarea>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                    {{-- Kolom Pencapaian diubah menjadi radio button --}}
                                    <div class="flex flex-col space-y-2 items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="questions[{{ $index }}][pencapaian]" value="Ya" class="form-radio text-green-600" {{ ($q['pencapaian'] ?? '') === 'Ya' ? 'checked' : '' }}>
                                            <span>Ya</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="questions[{{ $index }}][pencapaian]" value="Tidak" class="form-radio text-red-600" {{ ($q['pencapaian'] ?? '') === 'Tidak' ? 'checked' : '' }}>
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    <p>Tidak ada pertanyaan yang terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <hr class="my-8">

            {{-- RINGKASAN UNIT KOMPETENSI (Diubah menjadi input rekomendasi) --}}
            <h2 class="text-xl font-semibold mb-4 text-red-700">INPUT Rekomendasi Unit Kompetensi</h2>
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Kode Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-7/12">Judul Unit Kompetensi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Rekomendasi Asesor (K/BK)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Menggunakan $data->units langsung, karena sudah di-cast ke array --}}
                        @forelse($data->units as $index => $u)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}.</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    <input type="text" name="units[{{ $index }}][kode]" value="{{ $u['kode'] ?? '...' }}" class="p-1 border rounded w-full">
                                    <input type="hidden" name="units[{{ $index }}][id]" value="{{ $u['id'] ?? '' }}">
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <input type="text" name="units[{{ $index }}][unit]" value="{{ $u['unit'] ?? '...' }}" class="p-1 border rounded w-full">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                    <div class="flex flex-col space-y-2 items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="units[{{ $index }}][rekomendasi]" value="K" class="form-radio text-green-600" {{ ($u['rekomendasi'] ?? '') === 'K' ? 'checked' : '' }}>
                                            <span>K</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="units[{{ $index }}][rekomendasi]" value="BK" class="form-radio text-red-600" {{ ($u['rekomendasi'] ?? '') === 'BK' ? 'checked' : '' }}>
                                            <span>BK</span>
                                        </label>
                                    </div>
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

            <hr class="my-8">

            {{-- Area Keputusan Asesor (INPUT) --}}
            <div class="border border-gray-300 p-4 rounded-lg mb-6 bg-yellow-100">
                <h2 class="text-xl font-semibold mb-4 text-red-700">Keputusan & Catatan Akhir Asesor (INPUT)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="mb-2"><strong>Rekomendasi Umum Asesor:</strong></p>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2 text-lg font-bold text-green-700">
                                <input type="radio" name="rekomendasi_asesor" value="K" class="form-radio w-5 h-5 text-green-700" {{ ($data->rekomendasi_asesor ?? '') === 'K' ? 'checked' : '' }}>
                                <span>KOMPETEN (K)</span>
                            </label>
                            <label class="flex items-center space-x-2 text-lg font-bold text-red-700">
                                <input type="radio" name="rekomendasi_asesor" value="BK" class="form-radio w-5 h-5 text-red-700" {{ ($data->rekomendasi_asesor ?? '') === 'BK' ? 'checked' : '' }}>
                                <span>BELUM KOMPETEN (BK)</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <p class="mb-2"><strong>Catatan Tambahan Asesor:</strong></p>
                        <textarea name="catatan_asesor" rows="4" class="p-3 border border-gray-400 bg-white rounded w-full italic text-gray-700 shadow-sm">{{ $data->catatan_asesor ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Area Tanda Tangan Asesor (Hanya Nama dan Tanggal untuk form input) --}}
            <h2 class="text-xl font-semibold mt-10 mb-4 text-blue-700">Konfirmasi Asesor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Asesor</h3>
                    <div class="border border-gray-300 p-4 rounded-lg bg-gray-50">
                        <p class="mb-1"><strong>Nama</strong>: {{ $data->asesor->nama_lengkap ?? '...' }}</p>
                        <p class="mb-1"><strong>No. Reg. MET.</strong>: {{ $data->asesor->no_reg_met ?? '...' }}</p>
                        <p class="mb-4"><strong>Tanggal TTD</strong>: 
                            <input type="text" name="tgl_ttd_asesor" value="{{ $data->tgl_ttd_asesor ?? \Carbon\Carbon::now()->format('d F Y') }}" class="p-1 border rounded text-gray-700">
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
               <button type="submit" class="bg-blue-600 text-white font-bold px-8 py-3 rounded-lg shadow-md hover:bg-blue-700 transition">
                   <i class="fas fa-save mr-2"></i> SIMPAN HASIL WAWANCARA
               </button>
            </div>

        </form>
    </div>
</div>

@endsection