<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelengkapan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div>
        {{-- 1. Sidebar Component (Statis & Responsif) --}}
        {{-- Pastikan file component sidebar Anda memiliki id="sidebar" dan class responsif seperti di panduan sebelumnya --}}
        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        {{-- 2. Main Content Wrapper --}}
        <main class="flex-1 bg-white min-h-screen overflow-y-auto lg:ml-64 p-6 lg:p-12">

            {{-- Tombol Hamburger (Hanya muncul di HP) --}}
            <button id="hamburger-btn" type="button" class="lg:hidden text-gray-700 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="max-w-4xl mx-auto">

                {{-- 3. Progress Bar (Step 2 Aktif) --}}
                <div class="flex items-center justify-center mb-12">
                    {{-- Step 1: Selesai --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1
                        </div>
                    </div>
                    {{-- Garis 1: Selesai --}}
                    <div class="w-16 sm:w-24 h-1 bg-yellow-400 mx-2 sm:mx-4"></div>
                    
                    {{-- Step 2: Aktif (Kuning) --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md ring-4 ring-yellow-100">
                            2
                        </div>
                    </div>
                    
                    {{-- Garis 2: Belum --}}
                    <div class="w-16 sm:w-24 h-1 bg-gray-200 mx-2 sm:mx-4"></div>
                    
                    {{-- Step 3: Belum --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg">
                            3
                        </div>
                    </div>
                </div>

                {{-- Header --}}
                <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4">Bukti Kelengkapan Pemohon</h1>
                <p class="text-gray-600 mb-8">
                    Unggah bukti kelengkapan persyaratan dasar sesuai dengan skema sertifikasi.
                </p>

                {{-- 4. Form Upload Wrapper --}}
                <div class="space-y-6">

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">1. Pas Foto (Background Merah)</h3>
                                <p class="text-sm text-gray-500 mt-1">Format: JPG/PNG. Maks: 2MB.</p>
                            </div>

                            <a href="#" class="toggle-button flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>

                        <div class="toggle-content mt-6 border-t border-gray-200 pt-6 hidden">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                                {{-- Preview Box --}}
                                <div class="preview-box w-full sm:w-32 h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden">
                                    <span class="text-xs text-gray-500 break-all">Preview</span>
                                </div>

                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan File</label>
                                    <input type="text" placeholder="Contoh: Foto Terbaru 2024"
                                        class="description-input w-full border-gray-300 rounded-lg shadow-sm text-sm mb-4 p-2.5 focus:border-blue-500 focus:ring-blue-500">

                                    {{-- Initial Buttons --}}
                                    <div class="flex space-x-3 button-group-initial">
                                        <input type="file" id="file-upload-1" class="file-input hidden" accept="image/*">
                                        <label for="file-upload-1" class="cursor-pointer px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                                            Pilih File
                                        </label>
                                        <button type="button" class="cancel-button-initial px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                            Tutup
                                        </button>
                                    </div>

                                    {{-- Success/Action Buttons (Hidden by default) --}}
                                    <div class="flex space-x-3 button-group-success hidden">
                                        <button type="button" class="edit-button px-5 py-2.5 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">
                                            Ganti File
                                        </button>
                                        <button type="button" class="save-button px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">2. Kartu Tanda Penduduk (KTP)</h3>
                                <p class="text-sm text-gray-500 mt-1">Format: PDF/JPG. Pastikan NIK terlihat jelas.</p>
                            </div>
                            <a href="#" class="toggle-button flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="toggle-content mt-6 border-t border-gray-200 pt-6 hidden">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                                <div class="preview-box w-full sm:w-32 h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden">
                                    <span class="text-xs text-gray-500 break-all">Preview</span>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="text" placeholder="Keterangan (Opsional)" class="description-input w-full border-gray-300 rounded-lg shadow-sm text-sm mb-4 p-2.5 focus:border-blue-500 focus:ring-blue-500">
                                    <div class="flex space-x-3 button-group-initial">
                                        <input type="file" id="file-upload-2" class="file-input hidden">
                                        <label for="file-upload-2" class="cursor-pointer px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">Pilih File</label>
                                        <button type="button" class="cancel-button-initial px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Tutup</button>
                                    </div>
                                    <div class="flex space-x-3 button-group-success hidden">
                                        <button type="button" class="edit-button px-5 py-2.5 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Ganti File</button>
                                        <button type="button" class="save-button px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">3. Ijazah Terakhir</h3>
                                <p class="text-sm text-gray-500 mt-1">Ijazah SMK/D3/S1 sesuai bidang kompetensi.</p>
                            </div>
                            <a href="#" class="toggle-button flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="toggle-content mt-6 border-t border-gray-200 pt-6 hidden">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                                <div class="preview-box w-full sm:w-32 h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden">
                                    <span class="text-xs text-gray-500 break-all">Preview</span>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="text" placeholder="Keterangan (Opsional)" class="description-input w-full border-gray-300 rounded-lg shadow-sm text-sm mb-4 p-2.5 focus:border-blue-500 focus:ring-blue-500">
                                    <div class="flex space-x-3 button-group-initial">
                                        <input type="file" id="file-upload-3" class="file-input hidden">
                                        <label for="file-upload-3" class="cursor-pointer px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">Pilih File</label>
                                        <button type="button" class="cancel-button-initial px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Tutup</button>
                                    </div>
                                    <div class="flex space-x-3 button-group-success hidden">
                                        <button type="button" class="edit-button px-5 py-2.5 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Ganti File</button>
                                        <button type="button" class="save-button px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">4. Daftar Riwayat Hidup (CV)</h3>
                                <p class="text-sm text-gray-500 mt-1">Cantumkan pengalaman kerja yang relevan.</p>
                            </div>
                            <a href="#" class="toggle-button flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors">
                                <span class="file-count">0 berkas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="toggle-content mt-6 border-t border-gray-200 pt-6 hidden">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                                <div class="preview-box w-full sm:w-32 h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden">
                                    <span class="text-xs text-gray-500 break-all">Preview</span>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="text" placeholder="Keterangan (Opsional)" class="description-input w-full border-gray-300 rounded-lg shadow-sm text-sm mb-4 p-2.5 focus:border-blue-500 focus:ring-blue-500">
                                    <div class="flex space-x-3 button-group-initial">
                                        <input type="file" id="file-upload-4" class="file-input hidden">
                                        <label for="file-upload-4" class="cursor-pointer px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">Pilih File</label>
                                        <button type="button" class="cancel-button-initial px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Tutup</button>
                                    </div>
                                    <div class="flex space-x-3 button-group-success hidden">
                                        <button type="button" class="edit-button px-5 py-2.5 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Ganti File</button>
                                        <button type="button" class="save-button px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- 5. Tombol Footer --}}
                <div class="flex justify-between items-center mt-10 pt-6 border-t border-gray-200">
                    <a href="/data_sertifikasi"
                        class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Kembali
                    </a>
                    <a href="/halaman-tanda-tangan"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Selanjutnya
                    </a>
                </div>

            </div>
        </main>
    </div>

    {{-- 6. Script JavaScript (Sidebar Logic & File Upload) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // --- LOGIKA SIDEBAR RESPONSIVE ---
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar'); // Pastikan component sidebar punya id="sidebar"

            if (hamburgerBtn && sidebar) {
                hamburgerBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // --- LOGIKA FILE UPLOAD (Sama seperti kode Anda, hanya penyesuaian selector jika perlu) ---
            const iconChevronUp = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />';
            const iconChevronDown = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />';

            const uploadSections = document.querySelectorAll('.upload-section');

            uploadSections.forEach(section => {
                const toggleButton = section.querySelector('.toggle-button');
                const toggleContent = section.querySelector('.toggle-content');
                const toggleIcon = section.querySelector('.toggle-icon');
                const fileCount = section.querySelector('.file-count');
                const fileInput = section.querySelector('.file-input');
                const previewBox = section.querySelector('.preview-box');
                const descriptionInput = section.querySelector('.description-input');
                const initialButtons = section.querySelector('.button-group-initial');
                const successButtons = section.querySelector('.button-group-success');
                const editButton = section.querySelector('.edit-button');
                const saveButton = section.querySelector('.save-button');
                const initialCancelButton = section.querySelector('.cancel-button-initial');

                function resetPreview() {
                    previewBox.innerHTML = '<span class="text-xs text-gray-500 break-all">Belum ada file</span>';
                }

                function showImagePreview(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewBox.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover rounded-lg';
                        previewBox.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }

                function showFilePreview(fileName) {
                    previewBox.innerHTML = '';
                    // Icon file sederhana
                    previewBox.innerHTML = `
                        <div class="flex flex-col items-center justify-center text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-1">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="text-xs text-gray-600 break-all px-1 line-clamp-2">${fileName}</span>
                        </div>
                    `;
                }

                resetPreview();

                if (toggleButton && toggleContent && toggleIcon) {
                    toggleButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        toggleContent.classList.toggle('hidden');
                        const isHidden = toggleContent.classList.contains('hidden');
                        toggleIcon.innerHTML = isHidden ? iconChevronDown : iconChevronUp;
                    });
                }

                if (fileInput) {
                    fileInput.addEventListener('change', () => {
                        if (fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            
                            fileCount.textContent = '1 berkas dipilih';
                            toggleButton.classList.replace('bg-blue-50', 'bg-green-100');
                            toggleButton.classList.replace('text-blue-600', 'text-green-700');
                            
                            initialButtons.classList.add('hidden');
                            successButtons.classList.remove('hidden');

                            if (file.type.startsWith('image/')) {
                                showImagePreview(file);
                            } else {
                                showFilePreview(file.name);
                            }
                        }
                    });
                }

                if (editButton && fileInput) {
                    editButton.addEventListener('click', () => fileInput.click());
                }

                if (saveButton) {
                    saveButton.addEventListener('click', () => {
                         // Simulasi Simpan Sukses
                         saveButton.textContent = 'Tersimpan!';
                         saveButton.classList.replace('bg-green-600', 'bg-gray-500');
                         saveButton.disabled = true;
                         
                         setTimeout(() => {
                             alert('Data berhasil disimpan sementara!');
                             saveButton.textContent = 'Simpan';
                             saveButton.classList.replace('bg-gray-500', 'bg-green-600');
                             saveButton.disabled = false;
                         }, 500);
                    });
                }

                if (initialCancelButton) {
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