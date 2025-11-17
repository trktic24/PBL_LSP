<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        .file-input-wrapper input[type="file"] {
            display: none;
        }

        .drag-drop-area.is-dragover {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <x-sidebar :idAsesi="$id_asesi_untuk_js"></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">

                <div class="flex items-center justify-center mb-12">
                    <div
                        class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        1</div>
                    <div class="w-24 h-1 bg-yellow-400"></div>
                    <div
                        class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        2</div>
                    <div class="w-24 h-1 bg-yellow-400"></div>
                    <div
                        class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        3</div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-8">Tanda Tangan Pemohon</h1>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                        <strong>Oops! Ada yang salah:</strong>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                        <strong>Oops! Gagal:</strong> {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-4 text-sm mb-6">
                    <p class="text-base text-gray-800">Saya yang bertanda tangan di bawah ini</p>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Nama</label>
                        <span class="text-gray-900 font-medium" id="nama-pemohon">:
                            {{ isset($asesi) ? $asesi->nama_lengkap : 'Data tidak ada' }}</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Jabatan</label>
                        <span class="text-gray-900 font-medium" id="jabatan-pemohon">:
                            {{ isset($asesi->dataPekerjaan) ? $asesi->dataPekerjaan->jabatan : 'Data tidak ada' }}</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Perusahaan</label>
                        <span class="text-gray-900 font-medium" id="perusahaan-pemohon">:
                            {{ isset($asesi->dataPekerjaan) ? $asesi->dataPekerjaan->nama_institusi_pekerjaan : 'Data tidak ada' }}</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Alamat Perusahaan</label>
                        <span class="text-gray-900 font-medium" id="alamat-perusahaan-pemohon">:
                            {{ isset($asesi->dataPekerjaan) ? $asesi->dataPekerjaan->alamat_institusi : 'Data tidak ada' }}</span>
                    </div>
                    <p class="pt-4 text-gray-700 text-sm leading-relaxed">
                        Dengan ini saya menyatakan mengisi data dengan sebenarnya...
                    </p>
                </div>
                <form id="signature-upload-form" data-asesi-id="{{ $id_asesi_untuk_js }}">
                    @csrf

                    <div class="mt-6">
                        <div id="signature-preview-container"
                            class="drag-drop-area w-full h-48 border border-dashed border-gray-400 rounded-lg flex items-center justify-center bg-gray-50 mb-4 transition-all duration-300">
                            @if (isset($asesi) && $asesi->tanda_tangan)
                                <img id="signature-preview" class="max-h-full max-w-full object-contain"
                                    src="{{ asset($asesi->tanda_tangan) }}" alt="Tanda Tangan Tersimpan">
                                <span id="upload-placeholder" class="text-gray-500 text-sm hidden">Upload File...</span>
                            @else
                                <img id="signature-preview" class="max-h-full max-w-full object-contain hidden"
                                    alt="Tanda Tangan Preview">
                                <span id="upload-placeholder" class="text-gray-500 text-sm">Upload File Tanda Tangan
                                    Anda di sini...</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mt-4 mb-4">
                            <div class="file-input-wrapper">
                                <label for="signature-file-input"
                                    class="cursor-pointer px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                    Choose File
                                </label>
                                <input type="file" id="signature-file-input" accept="image/png, image/jpeg" />
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="button"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors"
                                    id="clear-signature">
                                    Hapus
                                </button>
                                <button type="button"
                                    class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 shadow-md transition-colors disabled:bg-blue-300"
                                    id="save-signature"
                                    @if (isset($asesi) && $asesi->tanda_tangan) disabled
                                    @else
                                    disabled @endif>
                                    @if (isset($asesi) && $asesi->tanda_tangan)
                                        Tersimpan ✔️
                                    @else
                                        Simpan
                                    @endif
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="data_tanda_tangan" id="data-tanda-tangan-base64"
                            value="{{ isset($asesi) && $asesi->tanda_tangan ? $asesi->tanda_tangan : '' }}">

                    </div>

                    <div class="flex justify-between items-center mt-12">
                        <a href="{{ route('bukti.pemohon', ['id_asesi' => $id_asesi_untuk_js]) }}"
                            class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                            Sebelumnya
                        </a>
                        <a href="/halaman-tanda-tangan/{id_asesi}"
                            class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                            Selanjutnya
                        </a>
                    </div>
                </form>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Ambil semua elemen penting (ini gak berubah)
            const fileInput = document.getElementById('signature-file-input');
            const previewContainer = document.getElementById('signature-preview-container');
            const previewImg = document.getElementById('signature-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const saveButton = document.getElementById('save-signature');
            const clearButton = document.getElementById('clear-signature');
            const base64Input = document.getElementById('data-tanda-tangan-base64');
            const form = document.getElementById('signature-upload-form');
            const csrfToken = form.querySelector('input[name="_token"]').value;
            const asesiId = form.dataset.asesiId;

            let uploadedFileBase64 = null;

            // ========================================================
            // || INI KODE FETCH DATA YANG DIPERBAIKI ||
            // ========================================================

            // 1. Fungsi buat ngisi data ke HTML
            function populateData(data) {
                // Isi data pemohon
                document.getElementById('nama-pemohon').textContent = ': ' + (data.nama_lengkap ||
                    'Data tidak ada');

                // [PERBAIKAN 2] Cara baca data_pekerjaan sebagai ARRAY
                // Cek apakah data_pekerjaan ada DAN tidak kosong
                if (data.data_pekerjaan && data.data_pekerjaan.length > 0) {
                    // Ambil data pekerjaan PERTAMA (indeks [0])
                    const pekerjaan = data.data_pekerjaan[0];

                    document.getElementById('jabatan-pemohon').textContent = ': ' + (pekerjaan.jabatan ||
                        'Data tidak ada');
                    document.getElementById('perusahaan-pemohon').textContent = ': ' + (pekerjaan
                        .nama_institusi_pekerjaan || 'Data tidak ada');
                    document.getElementById('alamat-perusahaan-pemohon').textContent = ': ' + (
                        pekerjaan.alamat_institusi || 'Data tidak ada');
                } else {
                    // Kalo gak ada data pekerjaan
                    document.getElementById('jabatan-pemohon').textContent = ': Data tidak ada';
                    document.getElementById('perusahaan-pemohon').textContent = ': Data tidak ada';
                    document.getElementById('alamat-perusahaan-pemohon').textContent = ': Data tidak ada';
                }

                // Cek data tanda tangan (ini biarin aja, buat nanti)
                if (data.tanda_tangan) {
                    previewImg.src = `{{ asset('') }}${data.tanda_tangan}`; // Tampilin gambar
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    base64Input.value = data.tanda_tangan; // Isi input hidden
                    saveButton.textContent = 'Tersimpan ✔️';
                    saveButton.disabled = true;
                } else {
                    previewImg.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    saveButton.textContent = 'Simpan';
                    saveButton.disabled = true;
                }
            }

            // 2. Tembak API-nya pas halaman kebuka
            // [PERBAIKAN] Ganti nama rute API-mu jadi '/api/show-detail/'
            fetch(`/api/show-detail/${asesiId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data Asesi');
                    }
                    return response.json();
                })
                .then(responseAsesi => {
                    // [PERBAIKAN 1] Ambil data dari BUNGKUSAN LUAR
                    // Kirim 'responseAsesi.data' BUKAN 'responseAsesi'
                    populateData(responseAsesi.data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Gagal memuat data pemohon. Coba refresh halaman.');
                    document.getElementById('nama-pemohon').textContent = ': Gagal memuat';
                });

            // ========================================================
            // || KODE LAMA LU (UPLOAD/SIMPAN) TETAP DI SINI ||
            // ========================================================

            // Fungsi processFile (Gak berubah, udah bener)
            function processFile(file) {
                if (!file.type.startsWith('image/')) {
                    alert("Format file tidak didukung...");
                    return;
                }
                uploadedFileBase64 = null;
                saveButton.textContent = 'Simpan';
                saveButton.disabled = true;
                base64Input.value = '';
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    uploadedFileBase64 = e.target.result;
                    saveButton.disabled = false;
                };
                reader.readAsDataURL(file);
            }

            // 1. Logika Upload File & Drag/Drop (Gak berubah)
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    processFile(this.files[0]);
                }
            });
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                previewContainer.addEventListener(eventName, preventDefaults, false)
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation()
            }
            previewContainer.addEventListener('dragenter', () => previewContainer.classList.add('is-dragover'),
                false);
            previewContainer.addEventListener('dragover', () => previewContainer.classList.add('is-dragover'),
                false);
            previewContainer.addEventListener('dragleave', () => previewContainer.classList.remove('is-dragover'),
                false);

            previewContainer.addEventListener('drop', handleDrop, false); // Tambahin ini

            function handleDrop(e) {
                previewContainer.classList.remove('is-dragover');
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    processFile(files[0]);
                    fileInput.files = files;
                }
            }

            // 2. Logika Tombol Simpan (AJAX) (Gak berubah)
            saveButton.addEventListener('click', function() {
                // ... (kode simpan TTD-mu biarin aja, gak usah diubah) ...
                if (!uploadedFileBase64) {
                    alert('Mohon unggah file gambar tanda tangan terlebih dahulu.');
                    return;
                }
                this.textContent = 'Menyimpan...';
                this.disabled = true;
                fetch(`/api/ajax-simpan-tandatangan/${asesiId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            data_tanda_tangan: uploadedFileBase64
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            saveButton.textContent = 'Tersimpan ✔️';
                            saveButton.disabled = true;
                            base64Input.value = data.path;
                            uploadedFileBase64 = null;
                            alert(data.message);
                        } else {
                            alert('Error: ' + data.message);
                            saveButton.textContent = 'Simpan';
                            saveButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal terhubung ke server. Coba lagi.');
                        saveButton.textContent = 'Simpan';
                        saveButton.disabled = false;
                    });
            });

            // 3. Logika Tombol Hapus (Gak berubah)
            clearButton.addEventListener('click', function() {
                // ... (kode hapus TTD-mu biarin aja, gak usah diubah) ...
                if (!confirm('Yakin mau hapus tanda tangan ini PERMANEN? Data ini gak bisa balik lagi.')) {
                    return; // Kalo batal, stop
                }
                // Tampilkan loading di tombol Hapus
                clearButton.textContent = 'Menghapus...';
                clearButton.disabled = true;
                saveButton.disabled = true; // Nonaktifkan tombol Simpan juga
                // Tembak API 'deleteAjax'
                fetch(`/api/ajax-hapus-tandatangan/${asesiId}`, {
                        method: 'POST', // Kita pake POST aja biar gampang
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message); // "Berhasil dihapus!"
                            fileInput.value = '';
                            uploadedFileBase64 = null;
                            previewImg.classList.add('hidden');
                            previewImg.src = '';
                            Vplaceholder.classList.remove('hidden');
                            saveButton.textContent = 'Simpan';
                            saveButton.disabled = true;
                            base64Input.value = '';
                        } else {
                            alert('Error: ' + data.message);
                        }
                        clearButton.textContent = 'Hapus';
                        clearButton.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal terhubung ke server. Coba lagi.');
                        clearButton.textContent = 'Hapus';
                        clearButton.disabled = false;
                    });
            });

        });
    </script>
</body>

</html>
