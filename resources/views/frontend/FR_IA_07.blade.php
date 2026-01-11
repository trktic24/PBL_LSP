@extends($layout ?? 'layouts.app-sidebar')

@section('custom_styles')
    <style>
        /* Tambahkan style untuk transisi accordion */
        .accordion-content {
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }

        .accordion-content.active {
            max-height: 2000px;
            /* Nilai cukup besar untuk menampung konten */
            opacity: 1;
        }

        /* Rotate icon */
        .accordion-icon {
            transition: transform 0.3s ease;
        }

        .accordion-btn[aria-expanded="true"] .accordion-icon {
            transform: rotate(180deg);
        }

        input[type="radio"]:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        /* Style untuk menyesuaikan layout sidebar */
        .main-content {
            margin-left: 0;
        }

        @media (min-width: 1024px) {
            .main-content {
                margin-left: 16rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="main-content flex-1 bg-white min-h-screen overflow-y-auto p-6 lg:p-12">

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- 2. Main Content --}}
        <div class="max-w-6xl mx-auto">

            <form action="{{ isset($isMasterView) ? '#' : route('ia07.store') }}" method="POST">
                @csrf
                @if($sertifikasi)
                <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
                @endif

                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan (ASESOR)</h1>
                    <p class="text-gray-600">
                        @if(isset($isMasterView))
                           <span class="text-blue-600 font-bold">[TEMPLATE MASTER]</span>
                        @endif
                        Daftar pertanyaan lisan yang diajukan asesor untuk menilai kompetensi asesi.
                    </p>
                </div>

                {{-- Info Box (Data Dinamis) --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        {{-- Baris 1: Skema --}}
                        <div>
                            <dt class="font-medium text-gray-500">Skema Sertifikasi</dt>
                            <dd class="text-gray-900 font-semibold">{{ $skema->nama_skema ?? 'SKEMA KOSONG' }}</dd>
                            <dt class="font-medium text-gray-500 mt-2">Nomor Skema</dt>
                            <dd class="text-gray-900 font-semibold">{{ $skema->nomor_skema ?? 'N/A' }}</dd>
                        </div>
                        {{-- Baris 2: Nama Asesor & Asesi --}}
                        <div>
                            <dt class="font-medium text-gray-500">Nama Asesor</dt>
                            <dd class="text-gray-900 font-semibold">{{ $asesor->nama_lengkap ?? 'N/A' }}</dd>
                            <dt class="font-medium text-gray-500 mt-2">Nama Asesi</dt>
                            <dd class="text-gray-900 font-semibold">{{ $asesi->nama_lengkap ?? 'N/A' }}</dd>
                        </div>
                        {{-- Baris 3: TUK --}}
                        <div>
                            <dt class="font-medium text-gray-500 mb-2">Pilih TUK</dt>
                            <dd class="text-gray-900 font-semibold flex flex-wrap gap-4">
                                {{-- Menggunakan data dinamis JenisTukOptions --}}
                                @forelse($jenisTukOptions as $id => $jenis)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="id_jenis_tuk" value="{{ $id }}"
                                            class="form-radio h-4 w-4 text-blue-600" {{ $id == 1 ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $jenis }}</span>
                                    </label>
                                @empty
                                    <span class="text-red-500 text-xs">Data TUK kosong.</span>
                                @endforelse
                            </dd>
                        </div>
                        {{-- Baris 4: Tanggal --}}
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Pelaksanaan</dt>
                            <dd class="text-gray-900 font-semibold">
                                <input type="date" name="tanggal_pelaksanaan" value="{{ date('Y-m-d') }}"
                                    class="border-gray-300 rounded-md text-sm shadow-sm">
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- PANDUAN ASESOR (Warna Biru) --}}
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl shadow-sm mb-8">
                    <h3 class="text-base font-bold text-blue-800 mb-2">PANDUAN BAGI ASESOR</h3>
                    <ul class="list-disc list-inside space-y-1 text-blue-700 text-sm">
                        <li>Ajukan pertanyaan kepada Asesi dari daftar di bawah ini.</li>
                        <li>Tulis ringkasan jawaban Asesi di kolom yang tersedia.</li>
                        <li>Berikan penilaian (K = Kompeten, BK = Belum Kompeten).</li>
                    </ul>
                </div>

                {{-- DAFTAR UNIT (ACCORDION DENGAN DATA DINAMIS) --}}
                <div class="space-y-4 mb-10">

                    {{-- Loop Real Data dari Controller --}}
                    @foreach($dataIA07 as $index => $group)
                        @php 
                            $unit = $group['unit']; 
                            $questions = $group['questions'];
                        @endphp
                        
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">

                            {{-- Header Accordion --}}
                            <button type="button"
                                class="accordion-btn w-full bg-blue-50 p-5 flex justify-between items-center text-left hover:bg-blue-100 transition-colors"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                <div>
                                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Unit Kompetensi</span>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $unit->kode_unit }}</h3>
                                    <p class="text-sm text-gray-600">{{ $unit->judul_unit }}</p>
                                </div>
                                <svg class="accordion-icon w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Body Accordion --}}
                            <div class="accordion-content bg-white {{ $index === 0 ? 'active' : '' }}">
                                <div class="p-6 border-t border-gray-200">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                            <thead class="bg-gray-800 text-white">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase w-10">No</th>
                                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">Pertanyaan & Jawaban Asesi</th>
                                                    <th class="px-4 py-3 text-center text-xs font-bold uppercase w-32">Keputusan (K/BK)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @forelse($questions as $key => $q)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-4 text-sm font-medium text-gray-900 align-top border-r border-gray-200">
                                                            {{ $loop->iteration }}.
                                                        </td>
                                                        <td class="px-4 py-4 text-sm text-gray-700 align-top border-r border-gray-200">
                                                            <p class="mb-2 font-bold text-base text-gray-800">{{ $q->pertanyaan }}</p>

                                                            <div class="mt-4 bg-gray-100 p-3 rounded-md border border-gray-200">
                                                                <p class="text-xs font-semibold text-gray-600 mb-1">Kunci Jawaban:</p>
                                                                <p class="text-xs text-blue-700 italic">{{ $q->jawaban_diharapkan ?? 'Lihat Kunci Jawaban' }}</p>
                                                            </div>

                                                            <label class="block text-xs font-semibold text-gray-600 mt-3 mb-1">Ringkasan Jawaban Asesi:</label>
                                                            <textarea name="jawaban[{{ $q->id_ia07 }}]"
                                                                class="w-full border-gray-300 rounded-md text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                                rows="3" placeholder="Tulis ringkasan jawaban Asesi di sini..."
                                                                required>{{ $q->jawaban_asesi }}</textarea>
                                                        </td>
                                                        <td class="px-4 py-4 align-top border-gray-200">
                                                            <div class="flex flex-col space-y-4 items-center mt-6">
                                                                <label class="inline-flex items-center">
                                                                    <input type="radio" name="keputusan[{{ $q->id_ia07 }}]"
                                                                        value="K"
                                                                        class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer"
                                                                        {{ $q->pencapaian === true || $q->pencapaian === 1 ? 'checked' : '' }}
                                                                        required>
                                                                    <span class="ml-2 text-sm font-bold text-green-700">K</span>
                                                                </label>
                                                                <label class="inline-flex items-center">
                                                                    <input type="radio" name="keputusan[{{ $q->id_ia07 }}]"
                                                                        value="BK"
                                                                        class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer"
                                                                        {{ $q->pencapaian === false || ($q->pencapaian !== null && $q->pencapaian == 0) ? 'checked' : '' }}>
                                                                    <span class="ml-2 text-sm font-bold text-red-700">BK</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="px-4 py-6 text-center text-gray-500 italic">
                                                            Belum ada pertanyaan lisan untuk unit ini.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tanda Tangan --}}
                <div class="bg-white border border-gray-300 rounded-xl p-6 shadow-md mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Umpan Balik & Tanda Tangan</h3>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Umpan Balik untuk Asesi (Kompeten / Belum
                        Kompeten)</label>
                    <textarea name="umpan_balik_asesi"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-6"
                        rows="3" placeholder="Tuliskan kesimpulan dan saran di sini..."></textarea>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Asesor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asesor (Tanda Tangan)</label>
                            <div
                                class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center">
                                @php
                                    $ttdAsesorBase64 = getTtdBase64($asesor->tanda_tangan ?? null, $asesor->id_user ?? $asesor->user_id ?? null, 'asesor');
                                @endphp
                                @if($ttdAsesorBase64)
                                    <img src="{{ $ttdAsesorBase64 }}" alt="Tanda Tangan Asesor" class="h-32 object-contain">
                                @else
                                    <p class="text-gray-400 text-sm">Tanda tangan belum tersedia</p>
                                @endif
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $asesor->nama_lengkap ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">No. Reg. {{ $asesor->nomor_regis ?? 'N/A' }}</p>
                        </div>

                        {{-- Asesi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asesi (Tanda Tangan)</label>
                            <div
                                class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-400 transition-colors">
                                @php
                                    $ttdAsesiBase64 = getTtdBase64($asesi->tanda_tangan ?? null, null, 'asesi');
                                @endphp
                                @if($ttdAsesiBase64)
                                    <img src="{{ $ttdAsesiBase64 }}" alt="Tanda Tangan Asesi" class="h-32 object-contain">
                                @else
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                        <p class="text-xs text-gray-500 mt-1">Tanda tangan belum tersedia</p>
                                    </div>
                                @endif
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $asesi->nama_lengkap ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Navigasi --}}
                @if(!isset($isMasterView))
                <div class="flex justify-end items-center mt-12 border-t border-gray-200 pt-6">
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Simpan Penilaian
                    </button>
                </div>
                @endif

        </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                // Atur agar unit pertama terbuka saat load
                if (acc.getAttribute('aria-expanded') === 'true') {
                    acc.nextElementSibling.classList.add('active');
                }

                acc.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    content.classList.toggle('active');

                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);

                    // Mengambil semua accordion yang lain dan menutupnya
                    accordions.forEach(otherAcc => {
                        if (otherAcc !== this) {
                            otherAcc.nextElementSibling.classList.remove('active');
                            otherAcc.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            });
        });
    </script>
@endsection