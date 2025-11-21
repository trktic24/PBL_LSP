<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Asesi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .upload-card {
            transition: all 0.3s ease;
        }
        .upload-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        <main class="flex-1 h-full overflow-y-auto bg-white lg:ml-64 p-6 lg:p-12">

            <button id="hamburger-btn" type="button" class="lg:hidden text-gray-700 mb-4 sticky top-0 z-10 bg-white p-2 rounded-md shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="max-w-5xl mx-auto pb-20"> {{-- Tambah padding bawah agar footer tidak mepet --}}

                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">Portofolio Asesi</h1>
                    <p class="text-gray-600">
                        Silakan unggah dokumen portofolio Anda sesuai dengan kategori di bawah ini.
                    </p>
                </div>

                {{-- Info Box --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Nama Asesi</p>
                        <p class="text-lg font-bold text-gray-900">Peserta Uji (Statis)</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Skema</p>
                        <p class="font-medium text-gray-900">Junior Web Developer</p>
                    </div>
                </div>

                {{-- AREA KONTEN UTAMA --}}
                <div class="space-y-8">

                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                        <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                            <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">1</div>
                            <h3 class="text-lg font-bold text-gray-800">PERSYARATAN DASAR</h3>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">
                                Unggah dokumen persyaratan dasar kompetensi (contoh: KTP, Sertifikat Pelatihan, Raport/Transkrip Nilai, dll).
                            </p>

                            <div class="space-y-4">
                                {{-- Item 1 --}}
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="text-blue-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Kartu Tanda Penduduk (KTP)</p>
                                            <p class="text-xs text-gray-400">Wajib</p>
                                        </div>
                                    </div>
                                    <button class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition">Upload</button>
                                </div>

                                {{-- Item 2 --}}
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="text-blue-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Ijazah Terakhir / Transkrip</p>
                                            <p class="text-xs text-gray-400">Wajib</p>
                                        </div>
                                    </div>
                                    <button class="px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-md border border-green-100">Lihat File</button>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Pendukung Lainnya</label>
                                    <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                        <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                            <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">2</div>
                            <h3 class="text-lg font-bold text-gray-800">PERSYARATAN ADMINISTRATIF</h3>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">
                                Unggah kelengkapan administrasi (contoh: Bukti Pembayaran, Formulir Pendaftaran, CV).
                            </p>

                            <div class="space-y-4">
                                {{-- Item 1 --}}
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="text-purple-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Curriculum Vitae (CV)</p>
                                            <p class="text-xs text-gray-400">Terbaru</p>
                                        </div>
                                    </div>
                                    <button class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition">Upload</button>
                                </div>

                                {{-- Drag & Drop Area --}}
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Upload file lainnya</span>
                                                <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Tombol Footer --}}
                <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
                    <a href="#" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Kembali
                    </a>
                    <button class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Simpan Portofolio
                    </button>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar'); // Pastikan component sidebar punya id="sidebar"

            if (hamburgerBtn && sidebar) {
                hamburgerBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
        });
    </script>

</body>
</html>