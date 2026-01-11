@extends($layout ?? 'layouts.app-sidebar')

@section('content')
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
        <x-header_form.header_form title="FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN" />
        
        <form action="{{ route('asesor.ak01.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf

        <br>
        {{-- Pesan Flash Session tetap ditampilkan sebagai cadangan --}}
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

        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>
            
            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                
                {{-- TUK (READ ONLY) --}}
                <dt class="col-span-1 font-medium text-gray-500 flex items-center">TUK (Sesuai Jadwal)</dt>
                <dd class="col-span-3">
                    <div class="flex flex-wrap gap-x-6 gap-y-2 items-center">
                        @php 
                            $idTuk = $sertifikasi->jadwal->id_jenis_tuk ?? 0; 
                        @endphp

                        <label class="flex items-center cursor-not-allowed opacity-80">
                            <input type="radio" name="jenis_tuk_display" value="Sewaktu" disabled {{ $idTuk == 1 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100"> 
                            <span class="ml-2 font-bold text-gray-700">Sewaktu</span>
                        </label>

                        <label class="flex items-center cursor-not-allowed opacity-80">
                            <input type="radio" name="jenis_tuk_display" value="Tempat Kerja" disabled {{ $idTuk == 2 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100"> 
                            <span class="ml-2 font-bold text-gray-700">Tempat Kerja</span>
                        </label>

                        <label class="flex items-center cursor-not-allowed opacity-80">
                            <input type="radio" name="jenis_tuk_display" value="Mandiri" disabled {{ $idTuk == 3 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100"> 
                            <span class="ml-2 font-bold text-gray-700">Mandiri</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 italic">*TUK ditentukan otomatis berdasarkan jadwal sertifikasi.</p>
                </dd>

                {{-- NAMA ASESOR --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</dd>

                {{-- NAMA ASESI --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: {{ $asesi->nama_lengkap ?? '-' }}</dd>

                {{-- BUKTI KELENGKAPAN --}}
                <dt class="col-span-1 font-medium text-gray-500 pt-2">Bukti yang akan dikumpulkan:</dt>
                <dd class="col-span-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4">
                        @php
                            $savedIds = $sertifikasi->responbuktiAk01->pluck('id_bukti_ak01')->toArray();
                            $savedLainnya = $sertifikasi->responbuktiAk01->where('id_bukti_ak01', 9)->first();
                            $textLainnyaValue = $savedLainnya ? $savedLainnya->respon : '';
                        @endphp

                        @foreach($masterBukti as $mb)
                            <div class="{{ $mb->id_bukti_ak01 == 9 ? 'col-span-1 sm:col-span-2 mt-2' : '' }}">
                                <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                    <input type="checkbox" name="bukti_kelengkapan[]" value="{{ $mb->id_bukti_ak01 }}" id="check_bukti_{{ $mb->id_bukti_ak01 }}" {{ in_array($mb->id_bukti_ak01, $savedIds) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"> 
                                    <span class="ml-2 {{ $mb->id_bukti_ak01 == 9 ? 'font-bold text-blue-700' : '' }}">{{ $mb->bukti }}</span>
                                </label>
                                @if($mb->id_bukti_ak01 == 9)
                                    <input type="text" id="text_lainnya" name="bukti_lainnya_text" class="w-full mt-2 border-gray-300 rounded-md shadow-sm hidden focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Tulis bukti lainnya di sini..." value="{{ $textLainnyaValue }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </dd>
            </dl>
        </div>

        {{-- AREA TANDA TANGAN --}}
        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
            <h4 class="font-bold mb-4 text-gray-700">Tanda Tangan Asesi (Review Only)</h4>
            <div class="border-2 border-dashed border-gray-300 rounded h-40 flex items-center justify-center bg-gray-50">
                @php $ttd = getTtdBase64($asesi->tanda_tangan ?? null); @endphp
                @if($ttd)
                    <img src="data:image/png;base64,{{ $ttd }}" class="h-32 object-contain" alt="TTD Asesi">
                @else
                    <span class="text-gray-400 italic">Asesi belum tanda tangan</span>
                @endif
            </div>
        </div>

        {{-- TOMBOL SUBMIT --}}
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-3 border-t pt-6">
            <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition w-full sm:w-auto text-center">Kembali</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 w-full sm:w-auto">SIMPAN & VERIFIKASI</button>
        </div>
        </form>
    </div>

    {{-- SCRIPT AREA: DIPINDAHKAN KE SINI (DI DALAM SECTION CONTENT) --}}
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. LOGIC CHECKBOX LAINNYA ---
            const checkLainnya = document.getElementById('check_bukti_9'); 
            const textLainnya = document.getElementById('text_lainnya');

            function toggleLainnya() {
                if(checkLainnya && textLainnya) {
                    if(checkLainnya.checked) {
                        textLainnya.classList.remove('hidden');
                        textLainnya.required = true;
                    } else {
                        textLainnya.classList.add('hidden');
                        textLainnya.required = false;
                    }
                }
            }
            if(checkLainnya) {
                toggleLainnya(); 
                checkLainnya.addEventListener('change', toggleLainnya);
            }

            // --- 2. LOGIC POPUP SWEETALERT ---
            // Cek Session Sukses
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Lanjut ke Tracker',
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false, 
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke Tracker
                        window.location.href = "/asesor/tracker/{{ $sertifikasi->id_data_sertifikasi_asesi }}";
                    }
                });
            @endif

            // Cek Session Error
            @if(session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'Tutup'
                });
            @endif
        });
    </script>

@endsection