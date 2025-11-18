<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.APL.02 - Asesmen Mandiri</title>
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
            max-height: 3000px; /* Nilai cukup besar untuk menampung tabel panjang */
            opacity: 1;
        }
        /* Rotate icon */
        .accordion-icon {
            transition: transform 0.3s ease;
        }
        .accordion-btn[aria-expanded="true"] .accordion-icon {
            transform: rotate(180deg);
        }
        /* Custom radio color */
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
                    <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">FR.APL.02. Asesmen Mandiri</h1>
                    <p class="text-gray-600">
                        Peserta diminta untuk menilai diri sendiri dan melampirkan bukti pendukung.
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
                            <dt class="font-medium text-gray-500">Nomor Skema</dt>
                            <dd class="text-gray-900 font-semibold">SKM/001/JWD/2024</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Nama Asesi</dt>
                            <dd class="text-gray-900 font-semibold">Peserta Uji (Statis)</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Pengisian</dt>
                            <dd class="text-gray-900 font-semibold">20 November 2025</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
                    <p class="text-sm text-blue-700">
                        <strong>Panduan:</strong> Baca setiap pertanyaan di kolom kiri, beri tanda centang pada kotak <strong>K (Kompeten)</strong> jika Anda yakin dapat melakukannya, atau <strong>BK (Belum Kompeten)</strong> jika belum. Sertakan bukti yang relevan (Portofolio/Sertifikat) di kolom kanan.
                    </p>
                </div>

                {{-- DAFTAR UNIT (ACCORDION) --}}
                <div class="space-y-4 mb-10">

                    {{-- 
                       DATA DUMMY UNIT (Sesuai gambar preases1 - preases6)
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
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                        
                        {{-- Header Accordion --}}
                        <button type="button" class="accordion-btn w-full bg-gray-50 p-5 flex justify-between items-center text-left hover:bg-gray-100 transition-colors" aria-expanded="false">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Unit {{ $index + 1 }}</span>
                                    <span class="text-xs text-gray-500">{{ $unit['code'] }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $unit['title'] }}</h3>
                            </div>
                            <svg class="accordion-icon w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Body Accordion --}}
                        <div class="accordion-content">
                            <div class="p-0">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-800 text-white">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold uppercase w-10">No</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold uppercase w-1/2">Daftar Pertanyaan (Asesmen Mandiri/Self Assessment)</th>
                                                <th class="px-6 py-3 text-center text-xs font-bold uppercase w-32">Penilaian</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Bukti Pendukung</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            
                                            {{-- Simulasi 3 Elemen Kompetensi per Unit --}}
                                            @for($elemen = 1; $elemen <= 3; $elemen++)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 align-top">{{ $elemen }}.</td>
                                                <td class="px-6 py-4 text-sm text-gray-700 align-top">
                                                    <p class="mb-1 font-semibold text-gray-900">Elemen Kompetensi {{ $elemen }}</p>
                                                    <p class="mb-3">Apakah anda dapat melakukan identifikasi materi {{ strtolower(substr($unit['title'], 0, 10)) }} sesuai spesifikasi?</p>
                                                </td>
                                                <td class="px-6 py-4 align-top">
                                                    <div class="flex flex-col space-y-3 items-center justify-center h-full">
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="radio" name="unit_{{$index}}_elm_{{$elemen}}" value="K" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm font-bold text-blue-700">K</span>
                                                        </label>
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="radio" name="unit_{{$index}}_elm_{{$elemen}}" value="BK" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                                            <span class="ml-2 text-sm font-bold text-red-700">BK</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 align-top">
                                                    <div class="space-y-2">
                                                        <select class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                            <option value="">-- Pilih Bukti --</option>
                                                            <option value="1">Ijazah SMK/D3/S1</option>
                                                            <option value="2">Sertifikat Pelatihan</option>
                                                            <option value="3">Portofolio Project</option>
                                                            <option value="4">Surat Keterangan Kerja</option>
                                                        </select>
                                                        <div class="text-xs text-gray-500 flex justify-between">
                                                            <span>Atau upload baru:</span>
                                                        </div>
                                                        <input type="file" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rekomendasi & Tanda Tangan</h3>
                    
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <strong>Catatan:</strong> Asesmen mandiri ini akan diverifikasi oleh Asesor. Pastikan bukti yang Anda lampirkan valid dan asli.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                            <p class="text-xs text-gray-500">Tanggal: 20-11-2025</p>
                        </div>

                        {{-- Asesor (Biasanya Readonly di tahap ini) --}}
                        <div class="opacity-50">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi Asesor</label>
                            <div class="w-full h-40 bg-gray-100 border-2 border-gray-200 rounded-xl flex items-center justify-center">
                                <p class="text-gray-400 text-sm italic">Menunggu Verifikasi</p>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">Nama Asesor</p>
                            <p class="text-xs text-gray-500">Ditinjau: -</p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Navigasi --}}
                <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
                    <a href="#" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Sebelumnya
                    </a>
                    <button class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Simpan & Lanjutkan
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