<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.AK.04 - Banding Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom style untuk checkbox/radio agar seragam */
        input[type="radio"]:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div>
        {{-- 1. Sidebar Component (Sama seperti halaman sebelumnya) --}}
        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        {{-- 2. Main Content Wrapper --}}
        <main class="flex-1 bg-white min-h-screen overflow-y-auto lg:ml-64 p-6 lg:p-12">

            {{-- Tombol Hamburger (Hanya muncul di HP) --}}
            <button id="hamburger-btn" type="button" class="lg:hidden text-gray-700 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="max-w-5xl mx-auto">

                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">FR.AK.04. Banding Asesmen</h1>
                    <p class="text-gray-600">
                        Formulir ini digunakan jika peserta ingin mengajukan banding atas keputusan asesmen.
                    </p>
                </div>

                {{-- 3. Info Box (Identitas) --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
                    <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                        <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                        <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                            <label class="flex items-center text-gray-900 font-medium cursor-pointer">
                                <input type="radio" name="tuk" value="Sewaktu" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-2">
                                Sewaktu
                            </label>
                            <label class="flex items-center text-gray-900 font-medium cursor-pointer">
                                <input type="radio" name="tuk" value="Tempat Kerja" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-2">
                                Tempat Kerja
                            </label>
                            <label class="flex items-center text-gray-900 font-medium cursor-pointer">
                                <input type="radio" name="tuk" value="Mandiri" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-2">
                                Mandiri
                            </label>
                        </dd>
                        
                        <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                        <dd class="col-span-3 text-gray-900 font-semibold block">: Tatang Sidartang (Statis)</dd>
    
                        <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                        <dd class="col-span-3 text-gray-900 font-semibold block">: Peserta Uji (Statis)</dd>

                        <dt class="col-span-1 font-medium text-gray-500">Tanggal Asesmen</dt>
                        <dd class="col-span-3 text-gray-900 font-semibold block">: 20 November 2025</dd>
                    </dl>
                </div>

                {{-- 4. Tabel Pertanyaan --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
                    <div class="p-4 bg-blue-50 border-b border-blue-100">
                        <p class="text-sm text-gray-800 font-medium">
                            Jawablah dengan <span class="font-bold">Ya</span> atau <span class="font-bold">Tidak</span> pertanyaan-pertanyaan berikut ini:
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Komponen</th>
                                    <th scope="col" class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider w-24">Ya</th>
                                    <th scope="col" class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider w-24">Tidak</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                                
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="q1" value="ya" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="q1" value="tidak" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="q2" value="ya" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="q2" value="tidak" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?</td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="q3" value="ya" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="q3" value="tidak" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 5. Detail Banding --}}
                <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm mb-8">
                    <p class="text-sm text-gray-700 mb-6 leading-relaxed">
                        Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional berikut:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Skema Sertifikasi</label>
                            <input type="text" value="Junior Web Developer" disabled 
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-700 text-sm focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Skema Sertifikasi</label>
                            <input type="text" value="SKM/001/JWD/2024" disabled 
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-700 text-sm focus:outline-none">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="alasan_banding" class="block text-sm font-bold text-gray-900 mb-2">
                            Banding ini diajukan atas alasan sebagai berikut:
                        </label>
                        <textarea id="alasan_banding" rows="5" 
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-4" 
                            placeholder="Berikan keterangan atau alasan pengajuan banding disini..."></textarea>
                    </div>
                </div>

                {{-- 6. Pernyataan & Tanda Tangan --}}
                <div class="mb-8">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <p class="text-sm text-red-700">
                            <strong>Catatan:</strong> Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                        </p>
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Pemohon Banding</label>
                    
                    {{-- Container Tanda Tangan --}}
                    <div class="w-full h-56 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors cursor-pointer" id="ttd_container">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p id="ttd_placeholder" class="mt-2 text-sm text-gray-500">Klik area ini untuk menandatangani</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end items-center mt-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-300 transition shadow-sm">
                            Hapus Tanda Tangan
                        </button>
                    </div>
                </div>

                {{-- 7. Tombol Footer --}}
                <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
                    <a href="#" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition shadow-sm">
                        Sebelumnya
                    </a>
                    <button class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Kirim Pengajuan
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
        });
    </script>

</body>
</html>