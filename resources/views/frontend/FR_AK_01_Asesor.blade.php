@extends($layout ?? 'layouts.app-sidebar')

@section('content')
    {{-- Helper Lokal Tanda Tangan --}}
    @php
        if (!function_exists('getTtdBase64')) {
            function getTtdBase64($path) {
                if (empty($path)) return null;
                $paths = [public_path($path), public_path('storage/'.$path), storage_path('app/public/'.$path)];
                foreach ($paths as $p) { if (file_exists($p)) return base64_encode(file_get_contents($p)); }
                return null;
            }
        }
    @endphp

    <div class="p-4 sm:p-6 md:p-8">

        {{-- HEADER FORM (Sama seperti Asesi) --}}
        <x-header_form.header_form title="FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN" />
        
        {{-- FORM START --}}
        <form action="{{ route('asesor.ak01.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf

        <br>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- DETAIL PELAKSANAAN --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>
            
            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                
                {{-- TUK (Tetap Radio Button karena Lokasi hanya satu) --}}
                <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                    @php $tuk = $sertifikasi->jadwal->jenis_tuk ?? ''; @endphp
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="jenis_tuk" value="Sewaktu" {{ $tuk=='Sewaktu'?'checked':'' }} class="text-blue-600 focus:ring-blue-500 border-gray-300"> 
                        <span class="ml-2">Sewaktu</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="jenis_tuk" value="Tempat Kerja" {{ $tuk=='Tempat Kerja'?'checked':'' }} class="text-blue-600 focus:ring-blue-500 border-gray-300"> 
                        <span class="ml-2">Tempat Kerja</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="jenis_tuk" value="Mandiri" {{ $tuk=='Mandiri'?'checked':'' }} class="text-blue-600 focus:ring-blue-500 border-gray-300"> 
                        <span class="ml-2">Mandiri</span>
                    </label>
                </dd>

                {{-- Nama Asesor --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: 
                    {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                </dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: 
                    {{ $asesi->nama_lengkap ?? '-' }}
                </dd>

                {{-- BUKTI KELENGKAPAN (CHECKBOX - BISA PILIH BANYAK) --}}
                <dt class="col-span-1 font-medium text-gray-500 pt-2">Bukti yang akan dikumpulkan:</dt>
                <dd class="col-span-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4">
                        @php
                            // Ambil data string dari DB, pecah jadi array untuk pengecekan checkbox
                            $savedString = $sertifikasi->respon_bukti_ak01 ?? '';
                            $savedArray = explode(', ', $savedString);

                            // Daftar opsi standar untuk mendeteksi "Lainnya"
                            $standardOptions = [
                                'Verifikasi Portofolio', 
                                'Observasi Langsung', 
                                'Hasil Tes Tulis', 
                                'Hasil Tes Lisan', 
                                'Hasil Wawancara'
                            ];

                            // Cari apakah ada teks lain di luar opsi standar (itu adalah isian "Lainnya")
                            $customValues = array_diff($savedArray, $standardOptions);
                            $lainnyaText = !empty($customValues) ? reset($customValues) : ''; // Ambil nilai custom pertama
                        @endphp

                        {{-- CHECKBOX 1 --}}
                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="bukti_kelengkapan[]" value="Verifikasi Portofolio" 
                            {{ in_array('Verifikasi Portofolio', $savedArray) ? 'checked' : '' }} 
                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                            <span class="ml-2">Verifikasi Portofolio</span>
                        </label>
                        
                        {{-- CHECKBOX 2 --}}
                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="bukti_kelengkapan[]" value="Observasi Langsung" 
                            {{ in_array('Observasi Langsung', $savedArray) ? 'checked' : '' }} 
                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                            <span class="ml-2">Observasi Langsung</span>
                        </label>

                        {{-- CHECKBOX 3 --}}
                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="bukti_kelengkapan[]" value="Hasil Tes Tulis" 
                            {{ in_array('Hasil Tes Tulis', $savedArray) ? 'checked' : '' }} 
                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                            <span class="ml-2">Hasil Tes Tulis</span>
                        </label>
                        
                        {{-- CHECKBOX 4 --}}
                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="bukti_kelengkapan[]" value="Hasil Tes Lisan" 
                            {{ in_array('Hasil Tes Lisan', $savedArray) ? 'checked' : '' }} 
                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                            <span class="ml-2">Hasil Tes Lisan</span>
                        </label>

                        {{-- CHECKBOX 5 --}}
                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="bukti_kelengkapan[]" value="Hasil Wawancara" 
                            {{ in_array('Hasil Wawancara', $savedArray) ? 'checked' : '' }} 
                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                            <span class="ml-2">Hasil Wawancara</span>
                        </label>

                        {{-- CHECKBOX LAINNYA --}}
                        <div class="col-span-1 sm:col-span-2 mt-2">
                            <label class="flex items-center text-gray-700 cursor-pointer font-semibold text-blue-700">
                                <input type="checkbox" id="check_lainnya" name="bukti_kelengkapan[]" value="Lainnya" 
                                {{ !empty($lainnyaText) ? 'checked' : '' }}
                                class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                                <span class="ml-2">Lainnya (Isi Sendiri)</span>
                            </label>
                            
                            <input type="text" id="text_lainnya" name="bukti_lainnya_text" 
                                class="w-full mt-2 border-gray-300 rounded-md shadow-sm hidden focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                placeholder="Tulis bukti lainnya di sini..."
                                value="{{ $lainnyaText }}">
                        </div>
                    </div>
                </dd>
            </dl>
        </div>

        {{-- AREA TANDA TANGAN (Hanya Lihat) --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h4 class="font-bold mb-4 text-gray-700">Tanda Tangan Asesi (Review Only)</h4>
            <div class="border-2 border-dashed border-gray-300 rounded h-40 flex items-center justify-center bg-gray-50">
                @php $ttd = getTtdBase64($asesi->tanda_tangan ?? null); @endphp
                @if($ttd)
                    <img src="data:image/png;base64,{{ $ttd }}" class="h-32 object-contain" alt="TTD Asesi">
                @else
                    <div class="text-center text-gray-400">
                         <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        <p class="text-sm mt-2">Asesi belum tanda tangan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- TOMBOL SUBMIT --}}
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-3 border-t pt-6">
            <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition w-full sm:w-auto text-center">
                Kembali
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 w-full sm:w-auto">
                SIMPAN & VERIFIKASI
            </button>
        </div>

        </form>
    </div>

    {{-- Script JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkLainnya = document.getElementById('check_lainnya');
            const textLainnya = document.getElementById('text_lainnya');

            function toggleLainnya() {
                if(checkLainnya.checked) {
                    textLainnya.classList.remove('hidden');
                    textLainnya.required = true;
                    // Hanya focus jika input masih kosong (agar tidak ganggu saat load)
                    if(textLainnya.value === '') { textLainnya.focus(); }
                } else {
                    textLainnya.classList.add('hidden');
                    textLainnya.required = false;
                }
            }

            // Jalankan saat load (untuk memunculkan text jika sudah tersimpan di DB)
            if(checkLainnya && textLainnya) {
                toggleLainnya();
                checkLainnya.addEventListener('change', toggleLainnya);
            }
        });
    </script>
@endsection