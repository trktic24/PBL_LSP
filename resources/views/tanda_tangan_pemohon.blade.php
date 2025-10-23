<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skema Sertifikat - Tanda Tangan Pemohon</title>
    
    <style>
        /* General Styling & Reset */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
        }

        .container-fluid {
            display: flex;
            width: 100%;
            max-width: 1200px;
            background-color: white;
            min-height: 100vh;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* ----------------------- */
        /* Sidebar Styling (Left Column) */
        /* ----------------------- */
        .sidebar {
            width: 35%; 
            background-color: #e0f7fa; /* Light blue background */
            padding: 30px 25px;
            color: #333;
        }

        .back-link {
            color: #555;
            text-decoration: none;
            font-size: 0.9em;
            display: block;
            margin-bottom: 30px;
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
            /* Blue/purple background gradient to mimic the image style */
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
            padding: 5px 0 5px 25px;
            position: relative;
            font-size: 0.9em;
            color: #555;
        }

        .requirements li::before {
            content: '✓'; 
            position: absolute;
            left: 0;
            color: #007bff;
            font-weight: bold;
        }
        
        /* ----------------------- */
        /* Main Content Styling (Right Column) */
        /* ----------------------- */
        .main-content {
            width: 65%; 
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
            background-color: #ffc107; /* Yellow color */
            box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
        }

        .line {
            height: 4px;
            width: 100px; 
            background-color: #ccc;
        }

        .line.active {
            background-color: #ffc107;
        }

        .main-header {
            font-size: 2.5em;
            font-weight: 900;
            margin: 0;
            color: #333;
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
            resize: none;
            background-color: #fff;
            padding: 10px;
            box-sizing: border-box;
            cursor: pointer;
        }

        .signature-prompt {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        /* Button Styling */
        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s, opacity 0.3s;
        }

        /* Positioning the "Hapus" button in the center */
        .signature-actions {
            display: flex;
            justify-content: center; /* Centering the button horizontally */
            margin-top: 5px;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #555;
            /* Adjust padding/margin if needed to match image gap */
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
            background-color: #007bff; 
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
            <a href="#" class="back-link">← Kembali</a>
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
                    <li>Data Sertifikasi</li>
                    <li>Rincian Data Pemohon Sertifikasi</li>
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
                <textarea id="signature-pad" class="signature-input" readonly></textarea>
                <p class="signature-prompt">*Tanda Tangan di sini</p>
                
                <div class="signature-actions">
                    <button type="button" class="btn btn-secondary">Hapus</button>
                </div>
                
            </div>

            <div class="navigation-buttons">
                <button type="button" class="btn btn-previous">Sebelumnya</button>
                <button type="submit" class="btn btn-next">Selanjutnya</button>
            </div>

        </div>
    </div>

    <script>
        // -----------------------
        // JavaScript for Auto-Filling Data
        // -----------------------
        document.addEventListener('DOMContentLoaded', function() {
            // *** SIMULASI DATA OTOMATIS: Ganti data ini dengan data yang sebenarnya dari backend Anda ***
            const userData = {
                nama: "Rizky Firmansyah",
                jabatan: "Junior Web Developer",
                perusahaan: "PT Digital Kreasi",
                alamat: "Jl. Teknologi No. 45, Bandung"
            };

            // Mengisi data ke elemen HTML
            document.getElementById('nama-pemohon').textContent = ': ' + userData.nama;
            document.getElementById('jabatan-pemohon').textContent = ': ' + userData.jabatan;
            document.getElementById('perusahaan-pemohon').textContent = ': ' + userData.perusahaan;
            document.getElementById('alamat-perusahaan-pemohon').textContent = ': ' + userData.alamat;
        });
    </script>

</body>
</html>