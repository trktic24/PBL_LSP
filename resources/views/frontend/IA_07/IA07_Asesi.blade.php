<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.07 - Pertanyaan Lisan (Asesi)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Style untuk transisi accordion */
        .accordion-content {
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        .accordion-content.active {
            max-height: 2000px; /* Nilai cukup besar untuk menampung konten */
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
            /* Asumsi Sidebar punya lebar 16rem di Tailwind's lg:ml-64 */
            .main-content {
                margin-left: 16rem !important; 
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <div>
        {{-- SIMULASI SIDEBAR (Tetap ada agar layout tidak berantakan) --}}
        <!-- Ganti ini dengan komponen sidebar asli kamu -->
        {{-- 1. Sidebar Component (Statis) --}}
        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        {{-- Memberi margin kiri untuk sidebar --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto ml-64">

        {{-- 2. Main Content --}}
        <main class="main-content flex-1 bg-white min-h-screen overflow-y-auto p-6 lg:p-12">
            
            <form action="{{ route('ia07.store') }}" method="POST">
            @csrf

            <div class="max-w-6xl mx-auto">
                
                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan (ASESI)</h1>
                    <p class="text-gray-600">
                        Isi jawaban Anda atas pertanyaan kompetensi yang diberikan oleh Asesor.
                    </p>
                </div>

                {{-- Info Box (Data Dinamis) --}}
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
                            <dt class="font-medium text-gray-500 mt-2">Nama Anda (Asesi)</dt>
                            <dd class="text-gray-900 font-semibold">{{ $asesi->nama_lengkap ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 mb-2">Pilih TUK</dt>
                            <dd class="text-gray-900 font-semibold flex flex-wrap gap-4">
                                @forelse($jenisTukOptions as $id => $jenis)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="id_jenis_tuk" value="{{ $id }}" class="form-radio h-4 w-4 text-blue-600">
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
                                <input type="date" name="tanggal_pelaksanaan" value="{{ date('Y-m-d') }}" class="border-gray-300 rounded-md text-sm shadow-sm">
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- PANDUAN ASESI --}}
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl shadow-sm mb-8">
                    <h3 class="text-base font-bold text-yellow-800 mb-2">PANDUAN BAGI ASESI</h3>
                    <ul class="list-disc list-inside space-y-1 text-yellow-700 text-sm">
                        <li>Baca dan pahami setiap pertanyaan dengan seksama.</li>
                        <li>Tulis jawaban Anda di kolom yang tersedia dengan ringkas dan jelas.</li>
                        <li>Pastikan Anda telah menjawab semua pertanyaan sebelum menyimpan.</li>
                    </ul>
                </div>

                {{-- DAFTAR UNIT (ACCORDION DENGAN DATA DINAMIS) --}}
                <div class="space-y-4 mb-10">

                    @foreach($units as $index => $unit)
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        
                        {{-- Header Accordion --}}
                        <button type="button" class="accordion-btn w-full bg-yellow-50 p-5 flex justify-between items-center text-left hover:bg-yellow-100 transition-colors" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                            <div>
                                <span class="text-xs font-bold text-yellow-600 uppercase tracking-wide">Unit {{ $index + 1 }}</span>
                                <h3 class="text-lg font-bold text-gray-900">{{ $unit['code'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $unit['title'] }}</p>
                            </div>
                            <svg class="accordion-icon w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Pertanyaan & Jawaban Anda</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            {{-- Simulasi 3 Pertanyaan per Unit --}}
                                            @for($q = 1; $q <= 3; $q++)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-4 text-sm font-medium text-gray-900 align-top border-r border-gray-200">{{ $q }}.</td>
                                                <td class="px-4 py-4 text-sm text-gray-700 align-top">
                                                    <p class="mb-2 font-bold text-base text-gray-800">P{{ $q }}: Jelaskan bagaimana Anda {{ strtolower(substr($unit['title'], 0, 20)) }}...?</p>
                                                    
                                                    <label class="block text-xs font-semibold text-gray-600 mt-3 mb-1">Jawaban Anda:</label>
                                                    <textarea name="jawaban_{{$unit['code']}}_q{{$q}}" class="w-full border-gray-300 rounded-md text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="4" placeholder="Tulis jawaban lengkap Anda di sini..." required></textarea>
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

                {{-- Tanda Tangan --}}
                <div class="bg-white border border-gray-300 rounded-xl p-6 shadow-md mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Konfirmasi & Tanda Tangan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Asesor (Hanya Nama) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asesor (Telah Menerima Jawaban)</label>
                            <div class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center">
                                <p class="text-gray-400 text-sm">Menunggu penilaian Asesor</p>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $asesor->nama_lengkap ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">No. Reg. {{ $asesor->nomor_regis ?? 'N/A' }}</p>
                        </div>

                        {{-- Asesi (Tanda Tangan) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Anda (Asesi)</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-400 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <p class="text-xs text-gray-500 mt-1">Klik untuk TTD Anda</p>
                                </div>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $asesi->nama_lengkap ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end items-center mt-12 border-t border-gray-200 pt-6">
                    <button type="submit" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md transition transform hover:-translate-y-0.5">
                        Kirim Jawaban
                    </button>
                </div>

            </div>
            </form>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                // Atur agar unit pertama terbuka saat load
                if (acc.getAttribute('aria-expanded') === 'true') {
                    acc.nextElementSibling.classList.add('active');
                }

                acc.addEventListener('click', function() {
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

</body>
</html>