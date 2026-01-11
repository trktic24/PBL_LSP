@extends($layout ?? 'layouts.app-sidebar')

@section('custom_styles')
    <style>
        .accordion-content { transition: max-height 0.3s ease-out, opacity 0.3s ease-out; max-height: 0; opacity: 0; overflow: hidden; }
        .accordion-content.active { max-height: 2000px; opacity: 1; }
        .accordion-icon { transition: transform 0.3s ease; }
        .accordion-btn[aria-expanded="true"] .accordion-icon { transform: rotate(180deg); }
        input[type="radio"]:checked { background-color: #2563eb; border-color: #2563eb; }
        .main-content { margin-left: 0; }
        @media (min-width: 1024px) { .main-content { margin-left: 16rem !important; } }
        /* Style Read Only */
        input:disabled, textarea:disabled { background-color: #f9fafb; cursor: not-allowed; color: #6b7280; }
    </style>
@endsection

@section('content')
    <main class="main-content flex-1 bg-white min-h-screen overflow-y-auto p-6 lg:p-12">

        {{-- Helper PHP untuk Mencari Jawaban --}}
        @php
            $getAnswer = function($pertanyaanKey) use ($existingData) {
                if(isset($existingData) && $existingData->count() > 0) {
                    return $existingData->firstWhere('pertanyaan', $pertanyaanKey);
                }
                return null;
            };
        @endphp

        <div class="max-w-6xl mx-auto">

            <form action="{{ isset($isMasterView) ? '#' : route('ia07.store') }}" method="POST">
                @csrf
                @if($sertifikasi)
                <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
                @endif

                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6 flex justify-between items-end">
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan</h1>
                        <p class="text-gray-600">Daftar pertanyaan lisan yang diajukan asesor untuk menilai kompetensi asesi.</p>
                    </div>
                    @if(isset($isReadOnly) && $isReadOnly)
                        <span class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full border border-green-200">
                            ✓ SUDAH TERISI
                        </span>
                    @endif
                </div>

                {{-- Info Box --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Skema Sertifikasi</dt>
                            <dd class="text-gray-900 font-semibold">{{ $skema->nama_skema ?? 'SKEMA KOSONG' }}</dd>
                            <dt class="font-medium text-gray-500 mt-2">Nomor Skema</dt>
                            <dd class="text-gray-900 font-semibold">{{ $skema->nomor_skema ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Nama Asesor</dt>
                            <dd class="text-gray-900 font-semibold">{{ $asesor->nama_lengkap ?? 'N/A' }}</dd>
                            <dt class="font-medium text-gray-500 mt-2">Nama Asesi</dt>
                            <dd class="text-gray-900 font-semibold">{{ $asesi->nama_lengkap ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 mb-2">Pilih TUK</dt>
                            <dd class="text-gray-900 font-semibold flex flex-wrap gap-4">
                                @forelse($jenisTukOptions as $id => $jenis)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="id_jenis_tuk" value="{{ $id }}"
                                            class="form-radio h-4 w-4 text-blue-600" 
                                            {{ $id == ($jadwal->id_jenis_tuk ?? 1) ? 'checked' : '' }}
                                            {{ $isReadOnly ? 'disabled' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $jenis }}</span>
                                    </label>
                                @empty
                                    <span class="text-red-500 text-xs">Data TUK kosong.</span>
                                @endforelse
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Pelaksanaan</dt>
                            <dd class="text-gray-900 font-semibold">
                                <input type="date" name="tanggal_pelaksanaan" value="{{ date('Y-m-d') }}"
                                    class="border-gray-300 rounded-md text-sm shadow-sm"
                                    {{ $isReadOnly ? 'disabled' : '' }}>
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- PANDUAN ASESOR --}}
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl shadow-sm mb-8">
                    <h3 class="text-base font-bold text-blue-800 mb-2">PANDUAN BAGI ASESOR</h3>
                    <ul class="list-disc list-inside space-y-1 text-blue-700 text-sm">
                        <li>Ajukan pertanyaan kepada Asesi dari daftar di bawah ini.</li>
                        <li>Tulis ringkasan jawaban Asesi di kolom yang tersedia.</li>
                        <li>Berikan penilaian (K = Kompeten, BK = Belum Kompeten).</li>
                    </ul>
                </div>

                {{-- DAFTAR UNIT (ACCORDION) --}}
                <div class="space-y-4 mb-10">
                    @foreach($units as $index => $unit)
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <button type="button" class="accordion-btn w-full bg-blue-50 p-5 flex justify-between items-center text-left hover:bg-blue-100 transition-colors" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                <div>
                                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Unit {{ $index + 1 }}</span>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $unit['code'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $unit['title'] }}</p>
                                </div>
                                <svg class="accordion-icon w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div class="accordion-content bg-white {{ $index === 0 ? 'active' : '' }}">
                                <div class="p-6 border-t border-gray-200">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                            <thead class="bg-gray-800 text-white">
                                                <tr>
                                                    <th class="px-4 py-3 text-left w-10">No</th>
                                                    <th class="px-4 py-3 text-left">Pertanyaan & Jawaban Asesi</th>
                                                    <th class="px-4 py-3 text-center w-32">Keputusan (K/BK)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @for($q = 1; $q <= 3; $q++)
                                                    @php
                                                        // Cari Data Lama
                                                        $keyPertanyaan = "Pertanyaan No $q Unit " . $unit['code'];
                                                        $dataLama = $getAnswer($keyPertanyaan);
                                                        $valJawaban = $dataLama ? $dataLama->jawaban_asesi : '';
                                                        $valKompeten = $dataLama ? ($dataLama->pencapaian == 1 ? 'K' : 'BK') : '';
                                                    @endphp

                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-4 align-top">{{ $q }}.</td>
                                                        <td class="px-4 py-4 align-top">
                                                            <p class="mb-2 font-bold text-base text-gray-800">P{{ $q }}: Apa yang dimaksud dengan {{ strtolower(substr($unit['title'], 0, 20)) }}...?</p>
                                                            <div class="mt-4 bg-gray-100 p-3 rounded-md border border-gray-200">
                                                                <p class="text-xs font-semibold text-gray-600 mb-1">Kunci Jawaban:</p>
                                                                <p class="text-xs text-blue-700 italic">Peserta mampu menjelaskan fungsi dan kapan menggunakan {{ $unit['code'] }}.</p>
                                                            </div>
                                                            <label class="block text-xs font-semibold text-gray-600 mt-3 mb-1">Ringkasan Jawaban Asesi:</label>
                                                            <textarea name="jawaban_{{$unit['code']}}_q{{$q}}"
                                                                class="w-full border-gray-300 rounded-md text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                                rows="3" placeholder="Tulis ringkasan jawaban Asesi di sini..." required 
                                                                {{ $isReadOnly ? 'disabled' : '' }}>{{ $valJawaban }}</textarea>
                                                        </td>
                                                        <td class="px-4 py-4 align-top">
                                                            <div class="flex flex-col space-y-4 items-center mt-6">
                                                                <label class="inline-flex items-center">
                                                                    <input type="radio" name="keputusan_{{$unit['code']}}_q{{$q}}" value="K"
                                                                        class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer" required 
                                                                        {{ $valKompeten == 'K' ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }}>
                                                                    <span class="ml-2 text-sm font-bold text-green-700">K</span>
                                                                </label>
                                                                <label class="inline-flex items-center">
                                                                    <input type="radio" name="keputusan_{{$unit['code']}}_q{{$q}}" value="BK"
                                                                        class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer"
                                                                        {{ $valKompeten == 'BK' ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }}>
                                                                    <span class="ml-2 text-sm font-bold text-red-700">BK</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- TANDA TANGAN (TIDAK DIHAPUS) --}}
                <div class="bg-white border border-gray-300 rounded-xl p-6 shadow-md mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Umpan Balik & Tanda Tangan</h3>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Umpan Balik untuk Asesi</label>
                    <textarea name="umpan_balik_asesi"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-6"
                        rows="3" placeholder="Tuliskan kesimpulan dan saran di sini..." {{ $isReadOnly ? 'disabled' : '' }}></textarea>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Asesor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asesor (Tanda Tangan)</label>
                            <div class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center">
                                <p class="text-gray-400 text-sm">Tanda tangan dari Asesor</p>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $asesor->nama_lengkap ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">No. Reg. {{ $asesor->nomor_regis ?? 'N/A' }}</p>
                        </div>

                        {{-- Asesi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asesi (Tanda Tangan)</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-400 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <p class="text-xs text-gray-500 mt-1">Klik untuk TTD Asesi</p>
                                </div>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $asesi->nama_lengkap ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL NAVIGASI --}}
                @if(!$isReadOnly && !isset($isMasterView))
                <div class="flex justify-end items-center mt-12 border-t border-gray-200 pt-6">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Simpan Penilaian
                    </button>
                </div>
                @elseif($isReadOnly)
                <div class="flex justify-center items-center mt-12 border-t border-gray-200 pt-6">
                    <a href="/asesor/tracker/{{ $sertifikasi->id_data_sertifikasi_asesi }}" class="px-8 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-md">
                        Kembali ke Tracker
                    </a>
                </div>
                @endif

            </form>
        </div>
    </main>

    {{-- SCRIPT AREA --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logic Accordion
            const accordions = document.querySelectorAll('.accordion-btn');
            accordions.forEach(acc => {
                if (acc.getAttribute('aria-expanded') === 'true') { acc.nextElementSibling.classList.add('active'); }
                acc.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    content.classList.toggle('active');
                    this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'false');
                    
                    accordions.forEach(otherAcc => {
                        if (otherAcc !== this) {
                            otherAcc.nextElementSibling.classList.remove('active');
                            otherAcc.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            });

            // Logic SweetAlert (Popup)
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
                        window.location.href = "/asesor/tracker/{{ $sertifikasi->id_data_sertifikasi_asesi }}";
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({ title: 'Gagal!', text: "{{ session('error') }}", icon: 'error' });
            @endif
        });
    </script>
@endsection