<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skema Sertifikat - Tanda Tangan Pemohon</title>
    
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <style>
        /* Definisi Warna */
        :root {
            --text-dark: #333333; 
            --icon-dark: #555555; 
            --icon-blue: #007bff; /* Warna ikon centang biru */
            --sidebar-bg: #e0f7fa; 
            --progress-yellow: #ffc107; 
            --blue-button: #007bff; 
        }

        /* General Styling & Reset */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            display: flex;
            /* Hapus justify-content: center; agar konten bisa menempel ke kiri/kanan */
        }

        .container-fluid {
            display: flex;
            width: 100%; /* PENTING: Mengisi seluruh lebar viewport */
            /* Hapus max-width: 1200px; untuk memungkinkan tampilan full screen */
            background-color: white;
            min-height: 100vh;
            /* Hapus box-shadow untuk tampilan full screen yang lebih rapi */
        }

        /* ----------------------- */
        /* Sidebar Styling (Left Column) */
        /* ----------------------- */
        .sidebar {
            width: 350px; /* Diubah menjadi lebar tetap untuk menjaga proporsi saat full screen */
            background-color: var(--sidebar-bg); 
            padding: 30px 25px;
            color: var(--text-dark);
            flex-shrink: 0; /* Pastikan sidebar tidak mengecil */
        }

        /* Gaya Tombol Kembali (Tetap Warna Gelap) */
        .back-link-custom {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: bold;
            color: var(--icon-dark); 
            margin-bottom: 30px;
            font-size: 1.1em;
        }

        .back-arrow {
            display: inline-block;
            width: 0.8em;
            height: 0.8em;
            border: solid var(--icon-dark); 
            border-width: 0 0 2px 2px; 
            transform: rotate(45deg);
            margin-right: 8px;
            margin-top: -2px;
        }

        .section-title {
            font-size: 1.5em;
            font-weight: 600;
            margin-bottom: 25px;
            color: #000;
        }

        .profile-card {
            text-align: center;
            margin-bottom: 40px;
        }

        .image-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 10px;
            background: linear-gradient(45deg, #1e3c72 0%, #2a5298 50%, #6a1b9a 100%);
            box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.4);
        }

        .profile-card h3 {
            margin: 5px 0;
            font-size: 1.4em;
            font-weight: bold;
        }

        .skema-id {
            color: #555;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .description {
            font-size: 0.85em;
            color: #333;
            padding: 0 10px;
        }

        .requirements {
            margin-top: 30px;
            padding-top: 20px;
        }

        .requirements h4 {
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }

        .requirements ul {
            list-style: none;
            padding: 0;
        }

        .requirements li {
            padding: 5px 0 5px 30px; 
            position: relative;
            font-size: 0.9em;
            color: #555;
        }

        /* Gaya Ikon Centang (Warna Biru) */
        .requirements li::before {
            content: ''; 
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 18px; 
            height: 18px; 
            border-radius: 50%; 
            background-color: var(--icon-blue); 
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .requirements li::after {
            content: '✓'; 
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: white;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        
        /* ----------------------- */
        /* Main Content Styling (Right Column) */
        /* ----------------------- */
        .main-content {
            flex-grow: 1; /* PENTING: Mengisi sisa ruang yang tersedia */
            padding: 40px 60px;
        }

        /* Progress Bar Styling */
        .progress-bar-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
        }

        .step {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #ccc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1em;
            z-index: 1;
        }

        .step.active {
            background-color: var(--progress-yellow); 
            box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
        }

        .line {
            height: 4px;
            width: 100px; 
            background-color: #ccc;
        }

        .line.active {
            background-color: var(--progress-yellow);
        }

        .main-header {
            font-size: 2.5em;
            font-weight: 900;
            margin: 0;
            color: var(--text-dark);
        }

        /* Data and Declaration Styling */
        .data-declaration {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .data-declaration p:first-child {
            margin-bottom: 20px;
            font-weight: 500;
        }

        .data-row {
            display: flex;
            margin: 10px 0;
        }

        .data-row label {
            width: 180px; 
            font-weight: normal;
        }

        .auto-filled-value {
            flex-grow: 1;
            font-weight: 500; 
        }

        .declaration-text {
            margin-top: 30px;
            font-size: 0.95em;
            line-height: 1.5;
            color: #555;
        }

        /* Signature Area Styling */
        .signature-area {
            position: relative;
            text-align: left;
            margin-top: 20px;
        }

        .signature-input {
            width: 100%;
            height: 180px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 10px;
            box-sizing: border-box;
            cursor: pointer;
            touch-action: none; 
        }

        .signature-prompt {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s, opacity 0.3s;
        }

        .signature-actions {
            display: flex;
            justify-content: center; 
            margin-top: 5px;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #555;
        }

        .btn-secondary:hover {
            background-color: #ccc;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 50px; 
        }

        .btn-previous {
            background-color: #f0f0f0;
            color: #555;
        }

        .btn-previous:hover {
            background-color: #e0e0e0;
        }

        .btn-next {
            background-color: var(--blue-button); 
            color: white;
        }

        .btn-next:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="sidebar">
            
            <a href="#" class="back-link-custom">
                <span class="back-arrow"></span> Kembali
            </a>
            
            <h2 class="section-title">Skema Sertifikat</h2>
            
            <div class="profile-card">
                <div class="image-placeholder"></div>
                <h3>Junior Web Developer</h3>
                <p class="skema-id">SKM12XXXXXXX</p>
                <p class="description">Lorem ipsum dolor sit amet, you're the best person I've ever met</p>
            </div>

            <div class="requirements">
                <h4>Persyaratan Utama</h4>
                <ul>
                    
                    <li>Rincian Data Pemohon Sertifikasi</li>
                    <li>Data Diri</li>
                    <li>Bukti Kelengkapan Pemohon</li>
                    <li>Bukti Pembayaran</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            
            <div class="progress-bar-container">
                <div class="step active">1</div>
                <div class="line active"></div>
                <div class="step active">2</div>
                <div class="line active"></div>
                <div class="step active">3</div>
            </div>

            <h1 class="main-header">Tanda Tangan Pemohon</h1>
            
            <div class="data-declaration">
                <p>Saya yang bertanda tangan di bawah ini</p>
                <div class="data-row">
                    <label>Nama</label>
                    <span class="auto-filled-value" id="nama-pemohon">: </span>
                </div>
                <div class="data-row">
                    <label>Jabatan</label>
                    <span class="auto-filled-value" id="jabatan-pemohon">: </span>
                </div>
                <div class="data-row">
                    <label>Perusahaan</label>
                    <span class="auto-filled-value" id="perusahaan-pemohon">: </span>
                </div>
                <div class="data-row">
                    <label>Alamat Perusahaan</label>
                    <span class="auto-filled-value" id="alamat-perusahaan-pemohon">: </span>
                </div>

                <p class="declaration-text">
                    Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
                </p>
            </div>

            <div class="signature-area">
                
                <canvas id="signature-canvas" class="signature-input"></canvas>
                <p class="signature-prompt">*Tanda Tangan di sini</p>
                
                <div class="signature-actions">
                    <button type="button" class="btn btn-secondary" id="clear-signature">Hapus</button>
                </div>
                
            </div>

            <div class="navigation-buttons">
                <button type="button" class="btn btn-previous">Sebelumnya</button>
                <button type="submit" class="btn btn-next">Selanjutnya</button>
            </div>

        </div>
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

            const userDataFromPreviousPage = JSON.parse(localStorage.getItem('userData')) || {};

            // Mengisi data (jika ada) - Defaultnya kosong
            namaPemohon.textContent = ': ' + (userDataFromPreviousPage.nama || '');
            jabatanPemohon.textContent = ': ' + (userDataFromPreviousPage.jabatan || '');
            perusahaanPemohon.textContent = ': ' + (userDataFromPreviousPage.perusahaan || '');
            alamatPerusahaanPemohon.textContent = ': ' + (userDataFromPreviousPage.alamat || '');


            // ----------------------------------------------------
            // 3. Logika Navigasi (Selanjutnya)
            // ----------------------------------------------------
            const nextButton = document.querySelector('.btn-next');
            nextButton.addEventListener('click', function(event) {
                event.preventDefault(); 

                if (signaturePad.isEmpty()) {
                    alert("Mohon berikan tanda tangan Anda terlebih dahulu sebelum melanjutkan.");
                    return;
                }

                const signatureDataURL = signaturePad.toDataURL(); 
                console.log("Data Tanda Tangan (Data URL):", signatureDataURL.substring(0, 50) + "...");
                
                alert("Validasi sukses! Data tanda tangan siap dikirim.");
            });
        });
    </script>

</body>
</html>