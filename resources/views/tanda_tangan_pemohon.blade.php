<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Pemohon</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    <style>
        canvas#signature-canvas {
            touch-action: none;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <aside class="w-80 flex-shrink-0 p-8" style="background: linear-gradient(180deg, #FDFDE0 0%, #F0F8FF 100%);">
            
            <a href="#" class="flex items-center text-sm font-medium text-gray-700 hover:text-black mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </a>

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Skema Sertifikat</h2>
                
                <img src="https://plus.unsplash.com/premium_photo-1661763171882-685b838f50b7?w=128&h=128&fit=crop&q=80" 
                     alt="Junior Web Developer" 
                     class="w-28 h-28 rounded-full mx-auto mb-4 border-4 border-white shadow-lg object-cover">
                
                <h1 class="text-xl font-semibold text-gray-900">Junior Web Developer</h1>
                <p class="text-sm text-gray-500 mb-4">SKM12XXXXXX</p>
                
                <p class="text-xs text-gray-600 italic px-2">
                    "Lorem ipsum dolor sit amet, you're the best person I've ever met"
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Persyaratan Utama</h3>
                <ul class="space-y-3">
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
                        <span class="text-gray-900 font-medium" id="nama-pemohon">: </span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Jabatan</label>
                        <span class="text-gray-900 font-medium" id="jabatan-pemohon">: </span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Perusahaan</label>
                        <span class="text-gray-900 font-medium" id="perusahaan-pemohon">: </span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Alamat Perusahaan</label>
                        <span class="text-gray-900 font-medium" id="alamat-perusahaan-pemohon">: </span>
                    </div>

                    <p class="pt-4 text-gray-700 text-sm leading-relaxed">
                        Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
                    </p>
                </div>

                <div class="mt-6">
                    <canvas id="signature-canvas" class="w-full h-48 border border-gray-300 rounded-lg shadow-inner bg-white cursor-pointer"></canvas>
                    
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                        
                        <button type="button" class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300" id="clear-signature">
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-12">
                    <button type="button" class="btn-previous px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Sebelumnya
                    </button>
                    
                    <button type="submit" class="btn-next px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ----------------------------------------------------
            // 1. Inisialisasi Signature Pad (Tanda Tangan)
            // ----------------------------------------------------
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)', 
                penColor: 'rgb(0, 0, 0)' 
            });

            // Fungsi untuk menyesuaikan ukuran canvas agar responsif
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear(); 
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas(); 

            // Tombol "Hapus" tanda tangan
            const clearButton = document.getElementById('clear-signature');
            clearButton.addEventListener('click', function() {
                signaturePad.clear();
            });


            // ----------------------------------------------------
            // 2. Pengambilan Data Otomatis (Simulasi LocalStorage)
            // ----------------------------------------------------
            const namaPemohon = document.getElementById('nama-pemohon');
            const jabatanPemohon = document.getElementById('jabatan-pemohon');
            const perusahaanPemohon = document.getElementById('perusahaan-pemohon');
            const alamatPerusahaanPemohon = document.getElementById('alamat-perusahaan-pemohon');

            // Baris ini HANYA untuk simulasi, ganti dengan data Anda dari Laravel
            try {
                const userDataFromPreviousPage = JSON.parse(localStorage.getItem('userData')) || {};
                
                // Mengisi data (jika ada) - Defaultnya kosong
                namaPemohon.textContent = ': ' + (userDataFromPreviousPage.nama || 'Nama Peserta (Otomatis)');
                jabatanPemohon.textContent = ': ' + (userDataFromPreviousPage.jabatan || 'Mahasiswa (Otomatis)');
                perusahaanPemohon.textContent = ': ' + (userDataFromPreviousPage.perusahaan || 'Politeknik Negeri Semarang (Otomatis)');
                alamatPerusahaanPemohon.textContent = ': ' + (userDataFromPreviousPage.alamat || 'Jl. Prof. Sudarto, Tembalang (Otomatis)');

            } catch (e) {
                console.error("Gagal mengambil data dari localStorage:", e);
                // Fallback jika localStorage tidak ada
                namaPemohon.textContent = ': Nama Peserta (Otomatis)';
                jabatanPemohon.textContent = ': Mahasiswa (Otomatis)';
                perusahaanPemohon.textContent = ': Politeknik Negeri Semarang (Otomatis)';
                alamatPerusahaanPemohon.textContent = ': Jl. Prof. Sudarto, Tembalang (Otomatis)';
            }


            // ----------------------------------------------------
            // 3. Logika Navigasi (Selanjutnya)
            // ----------------------------------------------------
            const nextButton = document.querySelector('.btn-next');
            nextButton.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah form submit langsung

                if (signaturePad.isEmpty()) {
                    alert("Mohon berikan tanda tangan Anda terlebih dahulu sebelum melanjutkan.");
                    return;
                }

                // Jika sudah ditanda tangan, Anda bisa ambil datanya
                const signatureDataURL = signaturePad.toDataURL(); // Ini adalah datanya (base64 PNG)
                console.log("Data Tanda Tangan (Data URL):", signatureDataURL.substring(0, 50) + "...");
                
                alert("Validasi sukses! Data tanda tangan siap dikirim.");
                
                // Di aplikasi nyata, Anda akan submit form di sini
                // misalnya: document.getElementById('form-anda').submit();
            });
        });
    </script>

</body>
</html>