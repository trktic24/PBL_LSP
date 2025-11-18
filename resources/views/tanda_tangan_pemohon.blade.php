<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Pemohon</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    <style>
        /* Sembunyikan input file default dan ganti dengan tombol custom */
        .file-input-wrapper input[type="file"] {
            display: none;
        }

        /* Border dashed untuk area drag & drop */
        .drag-drop-area.is-dragover {
            border-color: #2563eb; /* blue-600 */
            background-color: #eff6ff; /* blue-50 */
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <x-sidebar></x-sidebar>
        
        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <div class="flex items-center justify-center mb-12">
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div>
                    <div class="w-24 h-1 bg-yellow-400"></div>
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">2</div>
                    <div class="w-24 h-1 bg-yellow-400"></div>
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">3</div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-8">Tanda Tangan Pemohon</h1>
                
                <div class="space-y-4 text-sm mb-6">
                    <p class="text-base text-gray-800">Saya yang bertanda tangan di bawah ini</p>
                    
                    <div class="flex">
                        <label class="w-48 text-gray-600">Nama</label>
                        <span class="text-gray-900 font-medium" id="nama-pemohon">: {{ $asesi->nama_lengkap ?? 'Data Tidak Ditemukan' }}</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Jabatan</label>
                        <span class="text-gray-900 font-medium" id="jabatan-pemohon">: {{ $asesi->pekerjaan ?? 'Data Tidak Ditemukan' }}</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Perusahaan</label>
                        <span class="text-gray-900 font-medium" id="perusahaan-pemohon">: {{ $asesi->kebangsaan ?? 'Data Tidak Ditemukan' }}</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Alamat Perusahaan</label>
                        <span class="text-gray-900 font-medium" id="alamat-perusahaan-pemohon">: {{ $asesi->alamat_rumah ?? 'Data Tidak Ditemukan' }}</span>
                    </div>
                    <p class="pt-4 text-gray-700 text-sm leading-relaxed">
                        Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
                    </p>
                </div>

                <form id="signature-upload-form" method="POST" action="{{ route('simpan.tandatangan') }}">
                    @csrf 
                    
                    <div class="mt-6">
                        
                        <div id="signature-preview-container" class="drag-drop-area w-full h-48 border border-dashed border-gray-400 rounded-lg flex items-center justify-center bg-gray-50 mb-4 transition-all duration-300">
                            <img id="signature-preview" class="max-h-full max-w-full object-contain hidden" alt="Tanda Tangan Preview">
                            <span id="upload-placeholder" class="text-gray-500 text-sm">Upload File Tanda Tangan Anda di sini (PNG/JPG)</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="file-input-wrapper">
                                <label for="signature-file-input" class="cursor-pointer px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                    Choose File
                                </label>
                                <input type="file" id="signature-file-input" accept="image/png, image/jpeg"/>
                            </div>
                        </div>

                        <input type="hidden" name="data_tanda_tangan" id="data-tanda-tangan-base64">
                        
                        <div class="flex justify-end items-center mt-4 space-x-3">
                            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors" id="clear-signature">
                                Hapus
                            </button>
                            <button type="button" class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 shadow-md transition-colors disabled:bg-blue-300" id="save-signature" disabled>
                                Simpan
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-12">
                        <a href="{{ url('/bukti_pemohon') }}" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                            Sebelumnya
                        </a>
                        <button type="submit" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                            Selanjutnya
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Elemen-elemen DOM
            const fileInput = document.getElementById('signature-file-input');
            const previewContainer = document.getElementById('signature-preview-container');
            const previewImg = document.getElementById('signature-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const saveButton = document.getElementById('save-signature');
            const clearButton = document.getElementById('clear-signature');
            const base64Input = document.getElementById('data-tanda-tangan-base64'); 
            const form = document.getElementById('signature-upload-form');

            let uploadedFileBase64 = null; // Menyimpan Base64 sementara

            // Fungsi untuk memproses file dan menampilkan preview
            function processFile(file) {
                if (!file.type.startsWith('image/')) {
                    alert("Format file tidak didukung. Mohon unggah file gambar (PNG/JPG).");
                    return;
                }

                // Reset status
                uploadedFileBase64 = null;
                saveButton.textContent = 'Simpan';
                saveButton.disabled = true;
                base64Input.value = '';

                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    uploadedFileBase64 = e.target.result; // Simpan Base64 sementara
                    saveButton.disabled = false; // Aktifkan tombol simpan
                };

                reader.readAsDataURL(file);
            }

            // ----------------------------------------------------
            // 1. Logika Upload File & Drag/Drop
            // ----------------------------------------------------

            // A. File Input Change Handler (Saat tombol "Choose File" digunakan)
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    processFile(this.files[0]);
                }
            });

            // B. Drag & Drop Handlers
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                previewContainer.addEventListener(eventName, preventDefaults, false)
            });

            function preventDefaults (e) {
                e.preventDefault()
                e.stopPropagation()
            }

            previewContainer.addEventListener('dragenter', () => previewContainer.classList.add('is-dragover'), false);
            previewContainer.addEventListener('dragover', () => previewContainer.classList.add('is-dragover'), false);
            previewContainer.addEventListener('dragleave', () => previewContainer.classList.remove('is-dragover'), false);
            previewContainer.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                previewContainer.classList.remove('is-dragover');
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    processFile(files[0]);
                    fileInput.files = files; // Sinkronkan ke input file
                }
            }


            // ----------------------------------------------------
            // 2. Logika Tombol Simpan dan Hapus
            // ----------------------------------------------------

            saveButton.addEventListener('click', function() {
                if (uploadedFileBase64) {
                    base64Input.value = uploadedFileBase64;
                    this.textContent = 'Tersimpan ✔️';
                    this.disabled = true;

                    alert('File tanda tangan berhasil disimpan sementara. Klik "Selanjutnya" untuk mengirim data.');

                } else {
                    alert('Mohon unggah file gambar tanda tangan terlebih dahulu.');
                }
            });

            clearButton.addEventListener('click', function() {
                fileInput.value = ''; 
                uploadedFileBase64 = null;
                previewImg.classList.add('hidden');
                previewImg.src = '';
                placeholder.classList.remove('hidden');
                saveButton.textContent = 'Simpan';
                saveButton.disabled = true;
                base64Input.value = '';

                alert('Tanda tangan dihapus.');
            });


            // ----------------------------------------------------
            // 3. Logika Submit Form (Validasi)
            // ----------------------------------------------------
            form.addEventListener('submit', function(event) {
                if (!base64Input.value) {
                    event.preventDefault(); // Menghentikan submit
                    alert("⚠️ Mohon unggah file dan tekan tombol 'Simpan' tanda tangan Anda terlebih dahulu sebelum melanjutkan.");
                    return;
                }
                console.log("Data Base64 Tanda Tangan siap dikirim ke backend.");
            });
            

            // ----------------------------------------------------
            // 4. PENGAMBILAN DATA OTOMATIS (LOGIC LAMA DIHAPUS)
            // ----------------------------------------------------
            // Logic ini sudah dipindahkan ke Controller/Blade.
        });
    </script>

</body>
</html>