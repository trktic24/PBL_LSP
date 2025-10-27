<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* CSS Kustom Minimal untuk Tampilan Sisi Kiri (Sidebar) */
        .sidebar-bg {
            background: linear-gradient(180deg, #F0F8FF 0%, #E0F7FA 100%);
        }

        /* Styling ikon centang (Warna biru) */
        .checkmark-list li {
            position: relative;
            padding-left: 1.5rem; 
        }

        .checkmark-list svg {
            color: #3b82f6; /* text-blue-600 */
        }

        /* Area Upload/Preview */
        .upload-box {
            width: 100%;
            height: 180px;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        #signature-preview {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        #upload-label {
            position: absolute;
            color: #888;
            font-style: italic;
            font-size: 1.1em;
            pointer-events: none; 
        }

        /* Posisi Tombol Aksi */
        .signature-actions-container {
            display: flex;
            justify-content: flex-end; 
            gap: 0.5rem;
            margin-top: 1rem;
        }

        /* Gaya Tombol yang Dinonaktifkan */
        .btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <aside class="w-80 flex-shrink-0 p-8 sidebar-bg">
            
            <a href="/tracker" class="flex items-center text-sm font-medium text-gray-700 hover:text-black mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </a>

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Skema Sertifikat</h2>
                
                <div class="w-28 h-28 rounded-full mx-auto mb-4 border-4 border-white shadow-lg object-cover bg-blue-800 flex items-center justify-center">
                </div>
                
                <h1 class="text-xl font-semibold text-gray-900">Junior Web Developer</h1>
                <p class="text-sm text-gray-500 mb-4">SKM12XXXXXX</p>
                
                <p class="text-xs text-gray-600 italic px-2">
                    "Lorem ipsum dolor sit amet, you're the best person I've ever met"
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Persyaratan Utama</h3>
                <ul class="space-y-3 checkmark-list">
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Rincian Data Pemohon Sertifikasi</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Data Diri</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Bukti Kelengkapan Pemohon</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Bukti Pembayaran</span>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                
                <div class="flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div>
                    </div>
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">2</div>
                    </div>
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">3</div>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-4">Tanda Tangan Pemohon</h1>
                
                <div class="data-declaration mb-8">
                    <p class="mb-4 text-gray-900 font-medium">Saya yang bertanda tangan di bawah ini</p>
                    <div class="data-row flex mb-2">
                        <label class="font-normal w-40 text-gray-700">Nama</label>
                        <span class="auto-filled-value" id="nama-pemohon">: </span>
                    </div>
                    <div class="data-row flex mb-2">
                        <label class="font-normal w-40 text-gray-700">Jabatan</label>
                        <span class="auto-filled-value" id="jabatan-pemohon">: </span>
                    </div>
                    <div class="data-row flex mb-2">
                        <label class="font-normal w-40 text-gray-700">Perusahaan</label>
                        <span class="auto-filled-value" id="perusahaan-pemohon">: </span>
                    </div>
                    <div class="data-row flex mb-2">
                        <label class="font-normal w-40 text-gray-700">Alamat Perusahaan</label>
                        <span class="auto-filled-value" id="alamat-perusahaan-pemohon">: </span>
                    </div>

                    <p class="text-gray-600 mt-6 text-sm">
                        Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
                    </p>
                </div>
                
                
                <div class="signature-area mt-8">
                    
                    <div id="signature-preview-container" class="upload-box mb-1">
                        <input type="file" id="signature-file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        
                        <img id="signature-preview" src="#" alt="Preview Tanda Tangan" class="hidden w-full h-full p-2 object-contain">
                        <span id="upload-label" class="text-gray-500 italic text-lg pointer-events-none">
                            Klik untuk mengunggah gambar tanda tangan
                        </span>
                    </div>
                    
                    <p class="text-red-600 text-sm">*Tanda Tangan di sini</p>
                    
                    <div class="signature-actions-container">
                        
                        <button type="button" id="save-signature-btn" class="px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 btn-disabled">
                            Simpan
                        </button>
                        
                        <button type="button" id="clear-upload" class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 btn-disabled">
                            Hapus
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-10">
                    <a href="/bukti_kelengkapan_pemohon" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">
                        Sebelumnya
                    </a>
                    <button type="button" id="next-button" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 btn-disabled">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Elemen DOM ---
            const fileInput = document.getElementById('signature-file');
            const previewImage = document.getElementById('signature-preview');
            const uploadLabel = document.getElementById('upload-label');
            const clearButton = document.getElementById('clear-upload');
            const saveButton = document.getElementById('save-signature-btn');
            const nextButton = document.getElementById('next-button');
            
            // --- VARIABEL STATUS & SIMULASI BACKEND ---
            const STORAGE_KEY = 'userSavedSignature';
            let tempFile = null; 

            // ----------------------------------------------------
            // 1. FUNGSI UTAMA STATUS & TAMPILAN
            // ----------------------------------------------------

            function toggleButtonState(button, enable, activeColorClass, defaultColorClass = 'bg-gray-200 text-gray-800') {
                if (enable) {
                    button.classList.remove('btn-disabled', defaultColorClass, 'bg-gray-400');
                    button.classList.add(activeColorClass);
                } else {
                    button.classList.add('btn-disabled');
                    button.classList.remove(activeColorClass, defaultColorClass);
                    button.classList.add('bg-gray-200', 'text-gray-800');
                }
            }

            function updateUI() {
                const savedDataUrl = localStorage.getItem(STORAGE_KEY);
                
                // 1. Update Preview Area
                if (savedDataUrl) {
                    previewImage.src = savedDataUrl;
                    previewImage.classList.remove('hidden');
                    uploadLabel.classList.add('hidden');
                } else if (tempFile) {
                    previewImage.src = URL.createObjectURL(tempFile);
                    previewImage.classList.remove('hidden');
                    uploadLabel.classList.add('hidden');
                } else {
                    previewImage.classList.add('hidden');
                    uploadLabel.classList.remove('hidden');
                }
                
                // 2. Kontrol Tombol Aksi
                if (tempFile && !savedDataUrl) {
                    // Ada preview baru, siap disimpan
                    toggleButtonState(saveButton, true, 'bg-blue-500 text-white');
                    toggleButtonState(clearButton, true, 'bg-red-500 text-white'); // Hapus preview sementara
                } else if (savedDataUrl) {
                    // Sudah tersimpan
                    toggleButtonState(saveButton, false, 'bg-blue-500 text-white'); // Simpan non-aktif
                    toggleButtonState(clearButton, true, 'bg-red-500 text-white'); // Hapus aktif (Hapus permanen)
                } else {
                    // Kosong
                    toggleButtonState(saveButton, false, 'bg-blue-500 text-white');
                    toggleButtonState(clearButton, false, 'bg-red-500 text-white');
                }

                // 3. Kontrol Tombol Selanjutnya
                const nextBtnEnable = !!savedDataUrl;
                toggleButtonState(nextButton, nextBtnEnable, 'bg-blue-600 text-white');
            }

            // ----------------------------------------------------
            // 2. Event Listeners
            // ----------------------------------------------------

            // Saat file baru dipilih/diubah
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) { 
                        alert("Ukuran file terlalu besar! Maksimal 2MB.");
                        fileInput.value = ''; 
                        return;
                    }
                    tempFile = file;
                } else if (!localStorage.getItem(STORAGE_KEY)) {
                    // Jika input dicancel DAN tidak ada yang tersimpan, reset tempFile
                    tempFile = null;
                }
                updateUI();
            });

            // Tombol "Simpan"
            saveButton.addEventListener('click', function() {
                if (tempFile) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        localStorage.setItem(STORAGE_KEY, e.target.result);
                        tempFile = null; 
                        alert("Tanda tangan berhasil disimpan!");
                        updateUI();
                    };
                    reader.readAsDataURL(tempFile);
                }
            });

            // Tombol "Hapus"
            clearButton.addEventListener('click', function() {
                const isSaved = localStorage.getItem(STORAGE_KEY);

                if (isSaved) {
                    if (confirm("Apakah Anda yakin ingin menghapus tanda tangan yang sudah tersimpan?")) {
                        localStorage.removeItem(STORAGE_KEY);
                        tempFile = null;
                        fileInput.value = ''; 
                        alert("Tanda tangan telah dihapus.");
                    }
                } else if (tempFile) {
                    // Hapus preview sementara
                    tempFile = null;
                    fileInput.value = ''; 
                }
                updateUI();
            });

            // Logika Navigasi "Selanjutnya"
            nextButton.addEventListener('click', function(event) {
                event.preventDefault(); 

                if (!localStorage.getItem(STORAGE_KEY)) {
                    alert("Mohon unggah dan simpan gambar tanda tangan Anda terlebih dahulu sebelum melanjutkan.");
                    return;
                }

                console.log("Tanda tangan valid. Melanjutkan ke halaman berikutnya.");
                window.location.href = "/bukti_pembayaran"; 
            });

            // ----------------------------------------------------
            // 3. Inisialisasi Data Otomatis & Tampilan Awal
            // ----------------------------------------------------
            
            // Inisialisasi Data Otomatis (Nama, Jabatan, dll.)
            const namaPemohon = document.getElementById('nama-pemohon');
            const jabatanPemohon = document.getElementById('jabatan-pemohon');
            const perusahaanPemohon = document.getElementById('perusahaan-pemohon');
            const alamatPerusahaanPemohon = document.getElementById('alamat-perusahaan-pemohon');
            const userDataFromPreviousPage = JSON.parse(localStorage.getItem('userData')) || {};

            namaPemohon.textContent = ': ' + (userDataFromPreviousPage.nama || '');
            jabatanPemohon.textContent = ': ' + (userDataFromPreviousPage.jabatan || '');
            perusahaanPemohon.textContent = ': ' + (userDataFromPreviousPage.perusahaan || '');
            alamatPerusahaanPemohon.textContent = ': ' + (userDataFromPreviousPage.alamat || '');

            // Muat tampilan awal setelah semua siap
            updateUI();
        });
    </script>

</body>
</html>