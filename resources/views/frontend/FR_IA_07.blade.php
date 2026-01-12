@extends($layout ?? 'layouts.app-sidebar')

@section('custom_styles')
    <style>
        .question-card { transition: all 0.2s ease-in-out; border-left: 4px solid transparent; }
        .question-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .btn-radio { cursor: pointer; transition: all 0.2s; }
        .radio-input:checked + .btn-radio-label { ring-width: 2px; ring-offset-width: 2px; }
        .radio-input[value="1"]:checked ~ .question-card-inner { border-color: #10b981; } 
        .sticky-footer { animation: slideUp 0.3s ease-out; }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    </style>
@endsection

@section('content')
    {{-- Layout Fix: md:pl-64 biar gak ketutupan sidebar --}}
    <div class="w-full md:pl-64 flex flex-col min-h-screen bg-gray-50 transition-all duration-300">
        <div class="p-6 lg:p-10 pb-32 max-w-7xl mx-auto w-full">

            <form action="{{ isset($isMasterView) ? '#' : route('ia07.store') }}" method="POST">
                @csrf
                @if($sertifikasi)
                    <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
                @endif

                {{-- HEADER --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">FR.IA.07 Pertanyaan Lisan</h1>
                    <p class="text-gray-500 mt-2 text-lg">Formulir penilaian kompetensi melalui metode wawancara.</p>
                </div>

                {{-- PANDUAN --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl shadow-sm mb-10">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div class="ml-4 text-sm text-blue-700">
                            <p class="font-bold text-blue-900">Panduan Asesor:</p>
                            <ul class="list-disc list-inside mt-1">
                                <li>Pilih <span class="font-bold text-green-700">Kompeten (K)</span> atau <span class="font-bold text-red-700">Belum Kompeten (BK)</span>.</li>
                                <li>Isi ringkasan jawaban Asesi pada kolom teks yang tersedia.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- REFERENSI UNIT --}}
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Referensi Unit Kompetensi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($units as $unit)
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-start gap-3">
                                <span class="bg-gray-100 text-gray-600 text-xs font-mono font-bold px-2 py-1 rounded border border-gray-300 flex-shrink-0">{{ $unit->kode_unit }}</span>
                                <p class="text-sm font-medium text-gray-700 leading-snug">{{ $unit->judul_unit }}</p>
                            </div>
                        @empty
                            <div class="col-span-2 p-4 text-center text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-300">Tidak ada data unit.</div>
                        @endforelse
                    </div>
                </div>

                {{-- LOOPING PERTANYAAN --}}
                <div class="space-y-6 mb-12">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Penilaian Pertanyaan</h3>
                    @forelse($daftarSoal as $index => $soal)
                        <div class="question-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative group">
                            <div class="absolute top-0 left-0 bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-br-lg z-10">Soal #{{ $loop->iteration }}</div>
                            <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                                {{-- KIRI: Pertanyaan & Jawaban --}}
                                <div class="lg:col-span-8 space-y-6 pt-4 lg:pt-0">
                                    <h4 class="text-lg font-semibold text-gray-900 leading-relaxed">{{ $soal->pertanyaan }}</h4>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 relative">
                                        <div class="absolute top-2 left-2 w-1 h-full bg-yellow-400 rounded-full" style="height: calc(100% - 16px);"></div>
                                        <p class="text-xs font-bold text-yellow-800 uppercase mb-1 ml-3">Kunci Jawaban:</p>
                                        <p class="text-sm text-gray-700 ml-3 italic">"{{ $soal->jawaban_diharapkan ?? '-' }}"</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Ringkasan Jawaban Asesi</label>
                                        <textarea name="jawaban_asesi[{{ $soal->id_ia07 }}]" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-800 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all p-3" placeholder="Jawaban asesi..." required>{{ $soal->jawaban_asesi }}</textarea>
                                    </div>
                                </div>
                                {{-- KANAN: Penilaian --}}
                                <div class="lg:col-span-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-6 lg:pt-0 lg:pl-8 flex flex-col justify-center">
                                    <div class="space-y-3">
                                        <label class="relative block cursor-pointer group">
                                            <input type="radio" name="penilaian[{{ $soal->id_ia07 }}]" value="1" class="peer sr-only radio-input" {{ $soal->pencapaian == 1 ? 'checked' : '' }} required>
                                            <div class="btn-radio-label p-3 rounded-xl border-2 border-gray-200 hover:border-green-200 bg-white peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:ring-green-500 transition-all flex items-center">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 peer-checked:bg-green-500 peer-checked:text-white">K</div>
                                                <span class="text-sm font-bold text-gray-900 peer-checked:text-green-800">Kompeten</span>
                                            </div>
                                        </label>
                                        <label class="relative block cursor-pointer group">
                                            <input type="radio" name="penilaian[{{ $soal->id_ia07 }}]" value="0" class="peer sr-only radio-input" {{ $soal->pencapaian === 0 ? 'checked' : '' }}>
                                            <div class="btn-radio-label p-3 rounded-xl border-2 border-gray-200 hover:border-red-200 bg-white peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:ring-red-500 transition-all flex items-center">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-3 peer-checked:bg-red-500 peer-checked:text-white">BK</div>
                                                <span class="text-sm font-bold text-gray-900 peer-checked:text-red-800">Belum Kompeten</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200 transition-colors question-card-inner"></div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-300"><p class="text-gray-500">Belum ada data pertanyaan.</p></div>
                    @endforelse
                </div>

                {{-- TANDA TANGAN (Tanpa Umpan Balik) --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Tanda Tangan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Asesor --}}
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                            <label class="block text-sm font-bold text-gray-700 mb-4">Tanda Tangan Asesor</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden mb-4">
                                @php
                                    $ttdAsesorBase64 = null;
                                    if(function_exists('getTtdBase64')) {
                                        $ttdAsesorBase64 = getTtdBase64($asesor->tanda_tangan ?? null, $asesor->id_user ?? $asesor->user_id ?? null, 'asesor');
                                    }
                                @endphp
                                @if($ttdAsesorBase64)
                                    <img src="{{ $ttdAsesorBase64 }}" alt="TTD Asesor" class="h-32 object-contain">
                                @else
                                    <p class="text-gray-400 text-sm italic">Belum ada TTD</p>
                                @endif
                            </div>
                            <p class="font-bold text-gray-900">{{ $asesor->nama_lengkap ?? '-' }}</p>
                            <p class="text-xs text-gray-500">No. Reg: {{ $asesor->nomor_regis ?? '-' }}</p>
                        </div>
                        {{-- Asesi --}}
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                            <label class="block text-sm font-bold text-gray-700 mb-4">Tanda Tangan Asesi</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden mb-4">
                                @php
                                    $ttdAsesiBase64 = null;
                                    if(function_exists('getTtdBase64')) {
                                        $ttdAsesiBase64 = getTtdBase64($asesi->tanda_tangan ?? null, null, 'asesi');
                                    }
                                @endphp
                                @if($ttdAsesiBase64)
                                    <img src="{{ $ttdAsesiBase64 }}" alt="TTD Asesi" class="h-32 object-contain">
                                @else
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <p class="text-gray-400 text-xs italic">Belum tersedia</p>
                                    </div>
                                @endif
                            </div>
                            <p class="font-bold text-gray-900">{{ $asesi->nama_lengkap ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- STICKY FOOTER --}}
                <div class="sticky-footer fixed bottom-0 left-0 right-0 p-4 bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 flex justify-end items-center md:pl-72">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        SIMPAN & SELESAI
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', confirmButtonText: 'Oke', confirmButtonColor: '#2563eb' });
            @endif
            @if(session('error'))
                Swal.fire({ title: 'Gagal!', text: "{{ session('error') }}", icon: 'error' });
            @endif
        });
    </script>
@endsection