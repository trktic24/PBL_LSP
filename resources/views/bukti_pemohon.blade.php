<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelengkapan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <x-sidebar></x-sidebar>
        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                <div class="flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1</div>
                    </div>
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            2</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                            3</div>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-4">Bukti Kelengkapan Pemohon</h1>
                <p class="text-gray-600 mb-8">
                    Bukti kelengkapan persyaratan dasar pemohon
                </p>

                <div class="space-y-4">

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Foto Background Merah</h3>
                                <p class="text-sm text-gray-500">Ukuran file maks. 2MB</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">pasfoto.jpg</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-0" class="hidden">
                                    <label for="file-upload-0"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Ijazah</h3>
                                <p class="text-sm text-gray-500">Ijazah SMK Teknik Telekomunikasi atau...</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">sertifikasi.pdf</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-1" class="hidden">
                                    <label for="file-upload-1"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Pengalaman Kerja / Curriculum Vitae (CV)
                                </h3>
                                <p class="text-sm text-gray-500">Pengalaman kerja minimal 2 tahun</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">buktiskt.pdf</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-2" class="hidden">
                                    <label for="file-upload-2"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">KTP dan Surat Pernyataan</h3>
                                <p class="text-sm text-gray-500">KTP & Surat Pernyataan</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">buktiskt.pdf</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-3" class="hidden">
                                    <label for="file-upload-3"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Kartu Hasil Studi</h3>
                                <p class="text-sm text-gray-500">Transkrip nilai semester terakhir yang relevan</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">pasfoto.jpg</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-4" class="hidden">
                                    <label for="file-upload-4"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Sertifikasi Pelatihan Polines</h3>
                                <p class="text-sm text-gray-500">Sertifikat pelatihan dari internal Polines</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">sertifikasi.pdf</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-5" class="hidden">
                                    <label for="file-upload-5"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Surat Keterangan Kerja</h3>
                                <p class="text-sm text-gray-500">Surat Keterangan Kerja minimal 2 tahun</p>
                            </div>
                            <a href="#"
                                class="flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                0 berkas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center space-x-5">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">buktiskt.pdf</span>
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <div class="flex space-x-3">
                                    <input type="file" id="file-upload-6" class="hidden">
                                    <label for="file-upload-6"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-between items-center mt-10">
                    <a href="/data_sertifikasi"
                        class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300">
                        Sebelumnya
                    </a>
                    <a href="/tanda_tangan_pemohon"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md">
                        Selanjutnya
                    </a>
                </div>

            </div>
        </main>

    </div>

</body>

</html>
