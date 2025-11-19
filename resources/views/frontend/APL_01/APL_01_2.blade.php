@extends('layouts.app-sidebar')

@section('content')
    <div class="p-3 sm:p-6 md:p-8 max-w-5xl mx-auto">

        {{-- Progress Bar --}}
        <div class="flex items-center justify-center mb-8 sm:mb-12">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-base sm:text-xl shadow-md">
                    1
                </div>
            </div>
            <div class="w-20 sm:w-32 h-1 bg-yellow-400 mx-2 sm:mx-3"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-base sm:text-xl shadow-md ring-4 ring-yellow-100">
                    2
                </div>
            </div>
            <div class="w-20 sm:w-32 h-1 bg-gray-300 mx-2 sm:mx-3"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 font-bold text-base sm:text-xl">
                    3
                </div>
            </div>
        </div>

        {{-- Header --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Bukti Kelengkapan Pemohon</h1>
        <p class="text-gray-600 mb-8 sm:mb-10 text-sm sm:text-base leading-relaxed">
            Unggah bukti kelengkapan persyaratan dasar sesuai dengan skema sertifikasi.
        </p>

        {{-- Form Upload Container --}}
        <div class="space-y-4 sm:space-y-6">

            {{-- 1. Pas Foto --}}
            <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-6 upload-section transition-all duration-300 hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">1. Pas Foto (Background Merah)</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Format: JPG/PNG. Maks: 2MB.</p>
                    </div>
                    <a href="#" class="toggle-button flex items-center text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors self-start sm:self-auto">
                        <span class="file-count">0 berkas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                </div>

                <div class="toggle-content mt-4 sm:mt-6 border-t border-gray-200 pt-4 sm:pt-6 hidden">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-5">
                        <div class="preview-box w-full sm:w-32 h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden flex-shrink-0">
                            <span class="text-xs text-gray-500 break-all">Preview</span>
                        </div>
                        <div class="flex-1 w-full min-w-0">
                            <input type="text" placeholder="Keterangan: (Opsional)"
                                class="description-input w-full border-gray-300 rounded-lg shadow-sm text-xs sm:text-sm mb-3 sm:mb-4 p-2 sm:p-2.5 focus:border-blue-500 focus:ring-blue-500">

                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 button-group-initial">
                                <input type="file" class="file-input hidden" accept="image/*">
                                <label class="cursor-pointer px-4 sm:px-5 py-2 sm:py-2.5 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm text-center">
                                    Pilih File
                                </label>
                                <button type="button" class="cancel-button-initial px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                    Tutup
                                </button>
                            </div>

                            <div class="flex-col sm:flex-row gap-2 sm:gap-3 button-group-success hidden">
                                <button type="button" class="edit-button px-4 sm:px-5 py-2 sm:py-2.5 bg-yellow-500 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">
                                    Ganti File
                                </button>
                                <button type="button" class="save-button px-4 sm:px-5 py-2 sm:py-2.5 bg-green-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. KTP --}}
            <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-6 upload-section transition-all duration-300 hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">2. Kartu Tanda Penduduk (KTP)</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Format: PDF/JPG. Pastikan NIK terlihat jelas.</p>
                    </div>
                    <a href="#" class="toggle-button flex items-center text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors self-start sm:self-auto">
                        <span class="file-count">0 berkas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                </div>
                <div class="toggle-content mt-4 sm:mt-6 border-t border-gray-200 pt-4 sm:pt-6 hidden">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-5">
                        <div class="preview-box w-full sm:w-32 h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden flex-shrink-0">
                            <span class="text-xs text-gray-500 break-all">Preview</span>
                        </div>
                        <div class="flex-1 w-full min-w-0">
                            <input type="text" placeholder="Keterangan (Opsional)" class="description-input w-full border-gray-300 rounded-lg shadow-sm text-xs sm:text-sm mb-3 sm:mb-4 p-2 sm:p-2.5 focus:border-blue-500 focus:ring-blue-500">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 button-group-initial">
                                <input type="file" class="file-input hidden" accept=".pdf,image/*">
                                <label class="cursor-pointer px-4 sm:px-5 py-2 sm:py-2.5 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm text-center">Pilih File</label>
                                <button type="button" class="cancel-button-initial px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition">Tutup</button>
                            </div>
                            <div class="flex-col sm:flex-row gap-2 sm:gap-3 button-group-success hidden">
                                <button type="button" class="edit-button px-4 sm:px-5 py-2 sm:py-2.5 bg-yellow-500 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Ganti File</button>
                                <button type="button" class="save-button px-4 sm:px-5 py-2 sm:py-2.5 bg-green-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Ijazah Terakhir --}}
            <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-6 upload-section transition-all duration-300 hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">3. Ijazah Terakhir</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Format: PDF. Ijazah SMK/D3/S1 sesuai bidang kompetensi.</p>
                    </div>
                    <a href="#" class="toggle-button flex items-center text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors self-start sm:self-auto">
                        <span class="file-count">0 berkas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                </div>
                <div class="toggle-content mt-4 sm:mt-6 border-t border-gray-200 pt-4 sm:pt-6 hidden">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-5">
                        <div class="preview-box w-full sm:w-32 h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden flex-shrink-0">
                            <span class="text-xs text-gray-500 break-all">Preview</span>
                        </div>
                        <div class="flex-1 w-full min-w-0">
                            <input type="text" placeholder="Keterangan (Opsional)" class="description-input w-full border-gray-300 rounded-lg shadow-sm text-xs sm:text-sm mb-3 sm:mb-4 p-2 sm:p-2.5 focus:border-blue-500 focus:ring-blue-500">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 button-group-initial">
                                <input type="file" class="file-input hidden" accept=".pdf">
                                <label class="cursor-pointer px-4 sm:px-5 py-2 sm:py-2.5 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm text-center">Pilih File</label>
                                <button type="button" class="cancel-button-initial px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition">Tutup</button>
                            </div>
                            <div class="flex-col sm:flex-row gap-2 sm:gap-3 button-group-success hidden">
                                <button type="button" class="edit-button px-4 sm:px-5 py-2 sm:py-2.5 bg-yellow-500 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Ganti File</button>
                                <button type="button" class="save-button px-4 sm:px-5 py-2 sm:py-2.5 bg-green-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. CV --}}
            <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-6 upload-section transition-all duration-300 hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">4. Daftar Riwayat Hidup (CV)</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Format: PDF. Cantumkan pengalaman kerja yang relevan.</p>
                    </div>
                    <a href="#" class="toggle-button flex items-center text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-full transition-colors self-start sm:self-auto">
                        <span class="file-count">0 berkas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-4 h-4 ml-1 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                </div>
                <div class="toggle-content mt-4 sm:mt-6 border-t border-gray-200 pt-4 sm:pt-6 hidden">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-5">
                        <div class="preview-box w-full sm:w-32 h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center p-2 text-center overflow-hidden flex-shrink-0">
                            <span class="text-xs text-gray-500 break-all">Preview</span>
                        </div>
                        <div class="flex-1 w-full min-w-0">
                            <input type="text" placeholder="Keterangan (Opsional)" class="description-input w-full border-gray-300 rounded-lg shadow-sm text-xs sm:text-sm mb-3 sm:mb-4 p-2 sm:p-2.5 focus:border-blue-500 focus:ring-blue-500">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 button-group-initial">
                                <input type="file" class="file-input hidden" accept=".pdf">
                                <label class="cursor-pointer px-4 sm:px-5 py-2 sm:py-2.5 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm text-center">Pilih File</label>
                                <button type="button" class="cancel-button-initial px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition">Tutup</button>
                            </div>
                            <div class="flex-col sm:flex-row gap-2 sm:gap-3 button-group-success hidden">
                                <button type="button" class="edit-button px-4 sm:px-5 py-2 sm:py-2.5 bg-yellow-500 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Ganti File</button>
                                <button type="button" class="save-button px-4 sm:px-5 py-2 sm:py-2.5 bg-green-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Buttons --}}
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 mt-8 sm:mt-10 pt-6 border-t border-gray-200">
            @csrf
            
            <a href="#"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-white border border-gray-300 text-gray-700 font-semibold text-sm rounded-lg hover:bg-gray-50 transition shadow-sm text-center flex items-center justify-center">
                Kembali
            </a>
            <button type="button" id="tombol-selanjutnya"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 text-center flex items-center justify-center">
                Selanjutnya
            </button>
        </div>

    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                    previewBox.innerHTML = `
                        <div class="flex flex-col items-center justify-center text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 sm:w-12 sm:h-12 mb-1">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="text-[10px] sm:text-xs text-gray-700 break-all px-1 line-clamp-2 text-center font-medium">${fileName}</span>
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
                    const fileLabel = fileInput.nextElementSibling;
                    
                    fileLabel.addEventListener('click', (e) => {
                        fileInput.click();
                    });

                    fileInput.addEventListener('change', () => {
                        if (fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            
                            fileCount.textContent = '1 berkas';
                            toggleButton.classList.remove('bg-blue-50', 'text-blue-600');
                            toggleButton.classList.add('bg-green-100', 'text-green-700');
                            
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
                         saveButton.textContent = 'Tersimpan!';
                         saveButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                         saveButton.classList.add('bg-gray-500');
                         saveButton.disabled = true;
                         
                         setTimeout(() => {
                             alert('Data berhasil disimpan!');
                             saveButton.textContent = 'Simpan';
                             saveButton.classList.remove('bg-gray-500');
                             saveButton.classList.add('bg-green-600', 'hover:bg-green-700');
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
@endsection