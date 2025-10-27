<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tempat Uji Kompetensi</title>
    <style>
        /* Gaya Dasar dan Layout Grid */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #f4f7f8, #e0e6f0);
            min-height: 100vh;
        }

        .main-layout {
            display: flex;
            max-width: 1200px;
            margin: 30px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* --- SIDEBAR KIRI (Skema Sertifikat) --- */
        .sidebar-left {
            width: 350px;
            min-height: 100%;
            padding: 40px 30px;
            background: linear-gradient(to bottom, #d6d3d1 0%, #a4b4d6 50%, #7da2cc 100%);
            color: #2c3e50;
            text-align: center;
            box-sizing: border-box;
        }
        
        .sidebar-left .back-link {
            text-align: left;
            margin-bottom: 40px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            display: block;
            text-decoration: none;
        }

        .sidebar-left h2 {
            font-size: 32px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 30px;
            color: #000;
            text-align: left;
        }

        .profile-image-container {
            width: 150px;
            height: 150px;
            background-color: #f0f0f0;
            border-radius: 50%;
            margin: 0 auto 30px;
            overflow: hidden;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
        
        /* Tambahan info TUK di sidebar */
        .certification-details .tuk-info {
            margin-top: 15px;
            font-weight: bold;
            color: #000;
        }
        .certification-details .tuk-info small {
            font-weight: normal;
            display: block;
            font-size: 12px;
            color: #555;
        }

        /* --- KONTEN KANAN (Verifikasi TUK) --- */
        .content-right {
            flex-grow: 1;
            padding: 40px;
            background-color: #ffffff;
            box-sizing: border-box;
        }

        .content-right h1 {
            color: #333; /* Warna Judul Verifikasi */
            font-size: 30px;
            padding-bottom: 15px;
            margin-top: 0;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .content-right h4 {
            font-size: 15px;
            color: #777;
            margin-bottom: 30px;
            font-weight: normal;
        }

        /* Item Verifikasi (Card) */
        .verification-item {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        
        .verification-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .verification-item-header strong {
            font-size: 16px;
            color: #2c3e50;
        }

        .verification-item-header small {
            font-size: 13px;
            color: #E53935; /* Merah untuk 0 berkas */
            font-weight: bold;
        }
        
        /* Area Unggah File */
        .upload-area {
            border: 1px dashed #ccc;
            padding: 15px;
            border-radius: 4px;
            background-color: #fff;
        }
        
        .file-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
            color: #555;
        }

        .file-info span {
            font-weight: bold;
        }

        .action-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .action-group input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex-grow: 1;
            font-size: 13px;
        }

        /* Tombol */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-upload {
            background-color: #1E88E5; /* Biru */
            color: white;
        }

        .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #ccc;
        }

        .btn-bottom {
            background-color: #1E88E5;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            float: right;
        }
    </style>
</head>
<body>

    <div class="main-layout">
        
        <div class="sidebar-left">
            <a href="#" class="back-link">← Kembali</a>
            
            <h2>Skema Sertifikat</h2>
            
            <div class="profile-image-container">
                            </div>
            
            <div class="certification-details">
                <h3>Junior Web Developer</h3>
                <p>SKM12XXXXXXX</p>
                <p style="font-style: italic; margin-bottom: 30px;">
                    "Lorem ipsum dolor sit amet, you're the best person I've ever met"
                </p>
                
                <p class="assessor-name">Nama Asesor</p>
                <p class="assessor-info">
                    Jajang Sokbreker, S.T., M.T.<br>
                    No. Reg. MET00xxxx.xxxxxx
                </p>
                
                <p class="tuk-info">TUK<small>Jl. Prof. Sudarto sesko ssesas</small></p>
            </div>
        </div>

        <div class="content-right">
            <h1>Verifikasi Tempat Uji Kompetensi</h1>
            <h4>Bukti kelengkapan persyaratan dasar pemohon</h4>
            
            <div class="verification-item">
                <div class="verification-item-header">
                    <strong>Video 360° Ruangan</strong>
                    <small>0 berkas A</small>
                </div>
                <div class="upload-area">
                    <div class="file-info">
                        <span>Video 360° Ruangan-Uji Kompetensi</span>
                        <span>ruang.mp4</span>
                    </div>
                    <div class="action-group">
                        <input type="text" placeholder="Tambahkan Keterangan">
                        <button class="btn btn-upload">Upload</button>
                        <button class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>

            <div class="verification-item">
                <div class="verification-item-header">
                    <strong>Foto Peralatan / Perlengkapan Sesuai Skema</strong>
                    <small>0 berkas A</small>
                </div>
                <div class="upload-area">
                    <div class="file-info">
                        <span>Foto Peralatan / Perlengkapan Sesuai Skema</span>
                        <span>alat.png</span>
                    </div>
                    <div class="action-group">
                        <input type="text" placeholder="Tambahkan Keterangan">
                        <button class="btn btn-upload">Upload</button>
                        <button class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>
            
            <div class="verification-item">
                <div class="verification-item-header">
                    <strong>Foto Tampak Ruangan</strong>
                    <small>0 berkas A</small>
                </div>
                <div class="upload-area">
                    <div class="file-info">
                        <span>Foto Tampak Ruangan</span>
                        <span>FTBtuang.png</span>
                    </div>
                    <div class="action-group">
                        <input type="text" placeholder="Tambahkan Keterangan">
                        <button class="btn btn-upload">Upload</button>
                        <button class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>

            <div class="verification-item">
                <div class="verification-item-header">
                    <strong>Paket Data Internet</strong>
                    <small>0 berkas A</small>
                </div>
                <div class="upload-area">
                    <div class="file-info">
                        <span>Foto Paket Data Internet</span>
                        <span>pktData.png</span>
                    </div>
                    <div class="action-group">
                        <input type="text" placeholder="Tambahkan Keterangan">
                        <button class="btn btn-upload">Upload</button>
                        <button class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>

            <div class="verification-item">
                <div class="verification-item-header">
                    <strong>Kecepatan Internet</strong>
                    <small>0 berkas A</small>
                </div>
                <div class="upload-area">
                    <div class="file-info">
                        <span>Foto Kecepatan Internet</span>
                        <span>inst.png</span>
                    </div>
                    <div class="action-group">
                        <input type="text" placeholder="Tambahkan Keterangan">
                        <button class="btn btn-upload">Upload</button>
                        <button class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>

            <div class="verification-item">
                <div class="verification-item-header">
                    <strong>Foto Ruang Sertifikasi</strong>
                    <small>0 berkas A</small>
                </div>
                <div class="upload-area">
                    <div class="file-info">
                        <span>Foto Ruang Sertifikasi</span>
                        <span>#sber.png</span>
                    </div>
                    <div class="action-group">
                        <input type="text" placeholder="Tambahkan Keterangan">
                        <button class="btn btn-upload">Upload</button>
                        <button class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>
            
            <button class="btn btn-bottom">Selanjutnya</button>

        </div>

    </div>

</body>
</html>