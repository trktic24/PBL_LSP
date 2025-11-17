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

        <!-- Sidebar lu (biarin aja) -->
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                <!-- Progress Bar -->
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

                    <!-- ================= SECTION 1: FOTO ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <!-- Header Toggle -->
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Foto Background Merah</h3>
                                <p class="text-sm text-gray-500">Ukuran file maks. 2MB</p>
                            </div>

                            <!--
                                FIX 1:
                                - Nambahin class 'toggle-button'
                                - Nambahin class 'file-count' di span
                                - Nambahin class 'toggle-icon' di svg
                                - Ganti </button> jadi </a>
                            -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>

                        <!--
                            FIX 2:
                            - Nambahin class 'toggle-content'
                            - Nambahin class 'hidden' (biar ketutup dulu)
                        -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!--
                                FIX 3:
                                - Nambahin class 'preview-box'
                            -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">pasfoto.jpg</span>
                            </div>
                            <div class="flex-1">
                                <!--
                                    FIX 4:
                                    - Nambahin class 'description-input'
                                -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">

                                <!--
                                    FIX 5:
                                    - Nambahin class 'file-input'
                                    - Nambahin class 'button-group-initial'
                                    - Nambahin class 'cancel-button-initial'
                                -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-0" class="file-input hidden">
                                    <label for="file-upload-0"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 2: IJAZAH ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Ijazah</h3>
                                <p class="text-sm text-gray-500">Ijazah SMK Teknik Telekomunikasi atau...</p>
                            </div>
                            <!-- FIX: Tambah class & benerin tag tutup -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <!-- FIX: Tambah class -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!-- FIX: Tambah class -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">sertifikasi.pdf</span>
                            </div>
                            <div class="flex-1">
                                <!-- FIX: Tambah class -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <!-- FIX: Tambah class -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-1" class="file-input hidden">
                                    <label for="file-upload-1"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 3: CV ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Pengalaman Kerja / Curriculum Vitae (CV)
                                </h3>
                                <p class="text-sm text-gray-500">Pengalaman kerja minimal 2 tahun</p>
                            </div>
                            <!-- FIX: Tambah class & benerin tag tutup -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <!-- FIX: Tambah class -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!-- FIX: Tambah class -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">buktiskt.pdf</span>
                            </div>
                            <div class="flex-1">
                                <!-- FIX: Tambah class -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <!-- FIX: Tambah class -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-2" class="file-input hidden">
                                    <label for="file-upload-2"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 4: KTP ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">KTP dan Surat Pernyataan</h3>
                                <p class="text-sm text-gray-500">KTP & Surat Pernyataan</p>
                            </div>
                            <!-- FIX: Tambah class & benerin tag tutup -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <!-- FIX: Tambah class -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!-- FIX: Tambah class -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">buktiskt.pdf</span>
                            </div>
                            <div class="flex-1">
                                <!-- FIX: Tambah class -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <!-- FIX: Tambah class -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-3" class="file-input hidden">
                                    <label for="file-upload-3"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 5: KHS ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Kartu Hasil Studi</h3>
                                <p class="text-sm text-gray-500">Transkrip nilai semester terakhir yang relevan</p>
                            </div>
                            <!-- FIX: Tambah class & benerin tag tutup -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <!-- FIX: Tambah class -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!-- FIX: Tambah class -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">pasfoto.jpg</span>
                            </div>
                            <div class="flex-1">
                                <!-- FIX: Tambah class -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <!-- FIX: Tambah class -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-4" class="file-input hidden">
                                    <label for="file-upload-4"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 6: SERTIFIKAT POLINES ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Sertifikasi Pelatihan Polines</h3>
                                <p class="text-sm text-gray-500">Sertifikat pelatihan dari internal Polines</p>
                            </div>
                            <!-- FIX: Tambah class & benerin tag tutup -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <!-- FIX: Tambah class -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!-- FIX: Tambah class -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">sertifikasi.pdf</span>
                            </div>
                            <div class="flex-1">
                                <!-- FIX: Tambah class -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <!-- FIX: Tambah class -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-5" class="file-input hidden">
                                    <label for="file-upload-5"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 7: SURAT KERJA ================= -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Surat Keterangan Kerja</h3>
                                <p class="text-sm text-gray-500">Surat Keterangan Kerja minimal 2 tahun</p>
                            </div>
                            <!-- FIX: Tambah class & benerin tag tutup -->
                            <a href="#"
                                class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <!-- FIX: Tambah class -->
                        <div class="toggle-content flex items-center space-x-5 hidden">
                            <!-- FIX: Tambah class -->
                            <div
                                class="preview-box w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center p-2 text-center">
                                <span class="text-xs text-gray-500 break-all">buktiskt.pdf</span>
                            </div>
                            <div class="flex-1">
                                <!-- FIX: Tambah class -->
                                <input type="text" placeholder="Tambahkan Keterangan"
                                    class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm mb-3 focus:border-blue-500 focus:ring-blue-500">
                                <!-- FIX: Tambah class -->
                                <div class="flex space-x-3 button-group-initial">
                                    <input type="file" id="file-upload-6" class="file-input hidden">
                                    <label for="file-upload-6"
                                        class="cursor-pointer px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                        Upload
                                    </label>
                                    <button type="button"
                                        class="cancel-button-initial px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex space-x-3 button-group-success hidden">
                                    <button type="button"
                                        class="edit-button cursor-pointer px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="save-button cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        Simpan
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
                    <a href="/halaman-tanda-tangan/{jadwal_id}"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md">
                        Selanjutnya
                    </a>
                </div>

            </div>
        </main>

    </div>

    <!--
        KODE JAVASCRIPT LU (UDAH BENER, GAK GUA UBAH)
        Dia udah bisa jalan karena HTML-nya udah dibenerin.
    -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const iconChevronUp =
                '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />';
            const iconChevronDown =
                '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />';

            const uploadSections = document.querySelectorAll('.upload-section');

            uploadSections.forEach(section => {
                // Ambil semua elemen (SEKARANG UDAH KETEMU KARENA CLASS-NYA UDAH DITAMBAHIN)
                const toggleButton = section.querySelector('.toggle-button');
                const toggleContent = section.querySelector('.toggle-content');
                const toggleIcon = section.querySelector('.toggle-icon');
                const fileCount = section.querySelector('.file-count');
                const fileInput = section.querySelector('.file-input');

                // Elemen baru untuk preview
                const previewBox = section.querySelector('.preview-box');

                const descriptionInput = section.querySelector('.description-input');
                const initialButtons = section.querySelector('.button-group-initial');
                const successButtons = section.querySelector('.button-group-success');
                const editButton = section.querySelector('.edit-button');
                const saveButton = section.querySelector('.save-button');
                const initialCancelButton = section.querySelector('.cancel-button-initial');


                // Fungsi untuk mereset preview box ke state awal
                function resetPreview() {
                    previewBox.innerHTML = ''; // Kosongkan box
                    const span = document.createElement('span');
                    span.className = 'text-xs text-gray-500 break-all';
                    span.textContent = 'Belum ada file';
                    previewBox.appendChild(span);
                }

                // Fungsi untuk menampilkan preview gambar
                function showImagePreview(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewBox.innerHTML = ''; // Kosongkan box
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className =
                            'w-full h-full object-cover rounded-lg'; // 'object-cover' agar gambar pas
                        previewBox.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }

                // Fungsi untuk menampilkan preview file (bukan gambar)
                function showFilePreview(fileName) {
                    previewBox.innerHTML = ''; // Kosongkan box
                    const span = document.createElement('span');
                    span.className = 'text-xs text-gray-500 break-all';
                    span.textContent = fileName;
                    previewBox.appendChild(span);
                }

                // Inisialisasi preview box
                resetPreview();

                // 1. Event listener untuk tombol toggle (buka/tutup)
                if (toggleButton && toggleContent && toggleIcon) {
                    toggleButton.addEventListener('click', (e) => {
                        e.preventDefault(); // Biar link '#' gak loncat
                        toggleContent.classList.toggle('hidden');
                        const isHidden = toggleContent.classList.contains('hidden');
                        toggleIcon.innerHTML = isHidden ? iconChevronDown : iconChevronUp;
                    });
                }

                // 2. Event listener untuk input file (cek perubahan)
                if (fileInput && fileCount && previewBox && toggleButton && initialButtons &&
                    successButtons) {
                    fileInput.addEventListener('change', () => {
                        if (fileInput.files.length > 0) {
                            // --- Ada file dipilih ---
                            const file = fileInput.files[0];
                            const fileName = file.name;

                            // Update UI
                            fileCount.textContent = '1 berkas';
                            toggleButton.classList.remove('text-red-600', 'hover:text-red-800');
                            toggleButton.classList.add('text-green-600', 'hover:text-green-800');

                            // Ganti grup tombol
                            initialButtons.classList.add('hidden');
                            successButtons.classList.remove('hidden');

                            // --- LOGIKA PREVIEW BARU ---
                            if (file.type.startsWith('image/')) {
                                // Tampilkan preview gambar
                                showImagePreview(file);
                            } else {
                                // Tampilkan nama file (untuk PDF, doc, dll)
                                showFilePreview(fileName);
                            }

                        } else {
                            // --- File dibatalkan/dikosongkan ---

                            // Reset UI
                            fileCount.textContent = '0 berkas';
                            toggleButton.classList.remove('text-green-600', 'hover:text-green-800');
                            toggleButton.classList.add('text-red-600', 'hover:text-red-800');

                            // Kembalikan grup tombol
                            initialButtons.classList.remove('hidden');
                            successButtons.classList.add('hidden');

                            // --- LOGIKA PREVIEW BARU ---
                            // Kembalikan preview ke state awal
                            resetPreview();
                        }
                    });
                }

                // 3. Event listener untuk tombol [Edit]
                if (editButton && fileInput) {
                    editButton.addEventListener('click', () => {
                        fileInput.click(); // Picu ulang input file
                    });
                }

                // 4. Event listener untuk tombol [Simpan]
                if (saveButton && descriptionInput && fileInput) {
                    saveButton.addEventListener('click', () => {
                        const description = descriptionInput.value;
                        const file = fileInput.files[0];

                        if (file) {
                            // --- INI CUMA SIMULASI ---
                            // Ganti alert() dengan dialog kustom
                            const modal = document.createElement('div');
                            modal.className =
                                'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
                            modal.innerHTML = `
                                <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div class="mt-3 text-center">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">Data Disimpan</h3>
                                        <div class="mt-2 px-7 py-3">
                                            <p class="text-sm text-gray-500">
                                                <strong>File:</strong> ${file.name}<br>
                                                <strong>Keterangan:</strong> ${description || '(Tidak ada)'}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-4">(Data ini akan dikirim ke server)</p>
                                        </div>
                                        <div class="items-center px-4 py-3">
                                            <button class="close-modal px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            document.body.appendChild(modal);
                            modal.querySelector('.close-modal').addEventListener('click', () => {
                                document.body.removeChild(modal);
                            });

                        } else {
                            // Ganti alert() dengan dialog kustom
                            const modal = document.createElement('div');
                            modal.className =
                                'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
                            modal.innerHTML = `
                                <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div class="mt-3 text-center">
                                        <h3 class="text-lg leading-6 font-medium text-red-900">Error</h3>
                                        <div class="mt-2 px-7 py-3">
                                            <p class="text-sm text-gray-500">
                                               Terjadi kesalahan, file tidak ditemukan. Silakan "Edit" dan upload ulang.
                                            </p>
                                        </div>
                                        <div class="items-center px-4 py-3">
                                            <button class="close-modal px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            document.body.appendChild(modal);
                            modal.querySelector('.close-modal').addEventListener('click', () => {
                                document.body.removeChild(modal);
                            });
                        }
                    });
                }

                // 5. Event listener untuk tombol [Cancel] awal
                if (initialCancelButton && toggleContent && toggleIcon) {
                    initialCancelButton.addEventListener('click', () => {
                        toggleContent.classList.add('hidden');
                        toggleIcon.innerHTML = iconChevronDown;
                    });
                }

            });
        });
    </script>

</body>

</html>
