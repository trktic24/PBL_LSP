<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.07 - Pertanyaan Lisan</title>
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
    </style>
</head>

<body class="bg-gray-100">

    <div>
        {{-- 1. Sidebar Component --}}
        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        {{-- 2. Main Content --}}
        <main class="flex-1 bg-white min-h-screen overflow-y-auto lg:ml-64 p-6 lg:p-12">

            {{-- Tombol Hamburger --}}
            <button id="hamburger-btn" type="button" class="lg:hidden text-gray-700 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="max-w-6xl mx-auto">

                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">FR.IA.07. Pertanyaan Lisan</h1>
                    <p class="text-gray-600">
                        Daftar pertanyaan lisan yang diajukan asesor untuk menilai kompetensi asesi.
                    </p>
                </div>

                {{-- Info Box --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Skema Sertifikasi</dt>
                            <dd class="text-gray-900 font-semibold">Junior Web Developer</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Nama Asesor</dt>
                            <dd class="text-gray-900 font-semibold">Tatang Sidartang</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">TUK</dt>
                            <dd class="text-gray-900 font-semibold">Sewaktu / Tempat Kerja / Mandiri</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal</dt>
                            <dd class="text-gray-900 font-semibold">20 November 2025</dd>
                        </div>
                    </dl>
                </div>

                {{-- DAFTAR UNIT (ACCORDION) --}}
                <div class="space-y-4 mb-10">

                    {{-- 
                       LOOPING STATIC DATA (Simulasi 8 Unit sesuai gambar)
                       Di Laravel asli, Anda bisa menggunakan @foreach 
                    --}}
                    @php
                        $units = [
                            ['code' => 'J.620100.004.02', 'title' => 'Menggunakan Struktur Data'],
                            ['code' => 'J.620100.005.02', 'title' => 'Mengimplementasikan User Interface'],
                            ['code' => 'J.620100.009.01', 'title' => 'Melakukan Instalasi Software Tools Pemrograman'],
                            ['code' => 'J.620100.016.01', 'title' => 'Menulis Kode dengan Prinsip Sesuai Guidelines'],
                            ['code' => 'J.620100.017.02', 'title' => 'Mengimplementasikan Pemrograman Terstruktur'],
                            ['code' => 'J.620100.019.02', 'title' => 'Menggunakan Library atau Komponen Pre-Existing'],
                            ['code' => 'J.620100.023.02', 'title' => 'Membuat Dokumen Kode Program'],
                            ['code' => 'J.620100.025.02', 'title' => 'Melakukan Debugging'],
                        ];
                    @endphp

                    @foreach($units as $index => $unit)
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        
                        {{-- Header Accordion --}}
                        <button type="button" class="accordion-btn w-full bg-blue-50 p-5 flex justify-between items-center text-left hover:bg-blue-100 transition-colors" aria-expanded="false">
                            <div>
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Unit {{ $index + 1 }}</span>
                                <h3 class="text-lg font-bold text-gray-900">{{ $unit['code'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $unit['title'] }}</p>
                            </div>
                            <svg class="accordion-icon w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Body Accordion --}}
                        <div class="accordion-content bg-white">
                            <div class="p-6 border-t border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-10">No</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pertanyaan</th>
                                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-32">Rekomendasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            {{-- Simulasi 3 Pertanyaan per Unit --}}
                                            @for($q = 1; $q <= 3; $q++)
                                            <tr>
                                                <td class="px-4 py-4 text-sm font-medium text-gray-900 align-top">{{ $q }}.</td>
                                                <td class="px-4 py-4 text-sm text-gray-700 align-top">
                                                    <p class="mb-2 font-medium">Jelaskan langkah-langkah dalam melakukan {{ strtolower(substr($unit['title'], 0, 15)) }}...?</p>
                                                    <p class="text-xs text-gray-500 italic">Kunci Jawaban: Peserta mampu menjelaskan...</p>
                                                    
                                                    {{-- Input Jawaban Asesi (Opsional, biasanya lisan tapi asesor ngetik ringkasan) --}}
                                                    <textarea class="mt-2 w-full border-gray-300 rounded-md text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="2" placeholder="Ringkasan jawaban asesi..."></textarea>
                                                </td>
                                                <td class="px-4 py-4 align-top">
                                                    <div class="flex flex-col space-y-2 items-center">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="unit_{{$index}}_q{{$q}}" value="K" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                                            <span class="ml-2 text-sm font-medium text-gray-700">K</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="unit_{{$index}}_q{{$q}}" value="BK" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                                            <span class="ml-2 text-sm font-medium text-gray-700">BK</span>
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

                {{-- Tanda Tangan --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tanda Tangan & Keputusan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Asesor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Asesor</label>
                            <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center">
                                <p class="text-gray-400 text-sm">Sudah ditandatangani</p>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">Tatang Sidartang</p>
                            <p class="text-xs text-gray-500">No. Reg. MET.000.000000.2019</p>
                        </div>

                        {{-- Asesi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Asesi</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center cursor-pointer hover:border-blue-400 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <p class="text-xs text-gray-500 mt-1">Klik untuk TTD</p>
                                </div>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">Nama Peserta Uji</p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Navigasi --}}
                <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
                    <a href="#" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Sebelumnya
                    </a>
                    <button class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Simpan & Selesai
                    </button>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- LOGIKA SIDEBAR RESPONSIVE ---
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar');

            if (hamburgerBtn && sidebar) {
                hamburgerBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // --- LOGIKA ACCORDION ---
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                acc.addEventListener('click', function() {
                    // Toggle active class pada content
                    const content = this.nextElementSibling;
                    content.classList.toggle('active');
                    
                    // Toggle aria-expanded untuk icon rotation
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                });
            });
        });
    </script>

</body>
</html>