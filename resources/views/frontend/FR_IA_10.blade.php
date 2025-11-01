<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.10 - Verifikasi Pihak Ketiga</title>
    <style>
        /* CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background-color: #ffffff; /* Fallback background */
        }

        /* Sidebar (dari Gambar) */
        .sidebar {
            width: 280px;
            background-color: #3F96F6; /* Warna navy gelap dari gambar */
            color: #ffffff;
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .sidebar-header {
            font-size: 0.875rem;
            color: #000000;
            margin-bottom: 24px;
        }

        .sidebar-header a {
            color: inherit;
            text-decoration: none;
        }

        .profile {
            text-align: center;
            margin-bottom: 24px;
        }

        .profile .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 16px auto;
            background-color: #4b5563; /* Placeholder avatar */
            /* Ganti dengan <img> jika ada URL gambar */
            /* background-image: url('path-to-avatar.png'); */
            /* background-size: cover; */
        }

        .profile h2 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .profile p {
            font-size: 0.875rem;
            color: #000000;
        }

        .participant-info {
            border-top: 1px solid #000000;
            padding-top: 24px;
        }

        .participant-info .info-item {
            margin-bottom: 16px;
        }

        .participant-info label {
            font-size: 0.75rem;
            color: #000000;
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .participant-info p {
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Main Content (dari Gambar) */
        .main-content {
            flex: 1;
            padding: 32px 48px;
            background-color: #fdfaf6; /* Warna krem dari gambar */
            overflow-y: auto;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
        }

        .form-header img {
            height: 40px; /* Logo BNSP dari gambar */
        }

        .form-header .title-block {
            text-align: right;
        }

        .form-header h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #11182c;
        }

        .form-body {
            max-width: 900px;
        }

        /* Styling untuk Konten FR.IA.10 */
        .metadata-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 8px 16px;
            margin-bottom: 24px;
        }

        .metadata-grid label {
            font-weight: 600;
            color: #11182c;
        }
         
        .metadata-grid span, .metadata-grid div {
            display: flex;
            align-items: center;
        }

        .metadata-grid input[type="text"] {
            border: none;
            border-bottom: 1px solid #ccc;
            padding: 4px 0;
            font-size: 0.9rem;
            width: 100%;
        }

        .metadata-grid input[type="text"]:focus {
            outline: none;
            border-bottom-color: #3b82f6;
        }

        .metadata-grid .radio-group input {
            margin-left: 10px;
            margin-right: 4px;
        }

        .guide-box {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 8px;
            margin: 24px 0;
            font-size: 0.875rem;
        }

        .guide-box h3 {
            font-weight: bold;
            color: #11182c;
            margin-bottom: 8px;
        }
        
        .guide-box ul {
            list-style-position: inside;
        }
        
        .guide-box li {
            margin-bottom: 4px;
            color: #374151;
        }

        .form-section {
            margin-bottom: 24px;
        }
        
        .form-section h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #11182c;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 0.875rem;
            color: #11182c;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .form-table th,
        .form-table td {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.875rem;
        }

        .form-table th {
            background-color: #000000;
            font-weight: 600;
            color: #ffffff;
        }

        .form-table td:nth-child(2),
        .form-table td:nth-child(3) {
            text-align: center;
            width: 60px;
        }
        
        .form-table input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }

        .signature-section {
            margin-top: 32px;
            display: flex;
            justify-content: flex-end; /* Hanya asesor di kanan */
        }

        .signature-box {
            text-align: center;
            width: 280px;
        }

        .signature-box label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #11182c;
        }

        .signature-pad {
            height: 120px;
            margin: 16px 0 8px 0;
            /* Placeholder untuk tanda tangan, mirip gambar */
            /* background-image: url('path-to-signature.png'); */
            /* background-repeat: no-repeat; */
            /* background-position: center; */
            /* background-size: contain; */
            border-bottom: 2px solid #11182c;
        }

        .signature-box input[type="text"] {
            border: none;
            border-bottom: 1px solid #9ca3af;
            text-align: center;
            font-size: 0.875rem;
            padding: 4px;
            width: 200px;
        }
        
        .signature-box .date-input {
            margin-top: 8px;
            font-size: 0.875rem;
        }
        .signature-box .date-input input {
            width: 120px;
            border: none;
            border-bottom: 1px solid #9ca3af;
            font-size: 0.875rem;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 48px;
        }

        .btn {
            padding: 10px 24px;
            border: 1px solid;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #3b82f6; /* Biru dari gambar */
            border-color: #3b82f6;
        }

        .btn-primary {
            background-color: #3b82f6; /* Biru dari gambar */
            color: #ffffff;
            border-color: #3b82f6;
        }
        
        .footer-notes {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 32px;
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="#">&lt; kembali</a>
        </div>
        <div class="profile">
            <div class="avatar"></div>
            <h2>Junior Web Developer</h2>
            <p>Nama LSP (diambil dari gambar)</p>
            <p>Youre the best version (diambil dari gambar)</p>
        </div>
        <div class="participant-info">
            <div class="info-item">
                <label>OLEH PESERTA</label>
                <p>Tatang Sidartang</p>
            </div>
            <div class="info-item">
                <label>DIMULAI PADA</label>
                <p>2023-08-29 06:19:25</p>
            </div>
        </div>
    </aside>

    <main class="main-content">
        
        <header class="form-header">
            <div class="title-block">
                <h1>FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA</h1>
            </div>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Logo_BNSP.svg/2560px-Logo_BNSP.svg.png" alt="Logo BNSP">
        </header>

        <form class="form-body">
            
            <div class="metadata-grid">
                <label>Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div>: <input type="text" value="Junior Web Developer (Contoh)"></div>
                
                <label>Nomor</label>
                <div>: <input type="text" value="SSK.XX.XXXX (Contoh)"></div>

                <label>TUK</label>
                <div class="radio-group">
                    : 
                    <input type="radio" id="tuk_sewaktu" name="tuk_type"><label for="tuk_sewaktu">Sewaktu</label>
                    <input type="radio" id="tuk_tempatkerja" name="tuk_type" checked><label for="tuk_tempatkerja">Tempat Kerja</label>
                    <input type="radio" id="tuk_mandiri" name="tuk_type"><label for="tuk_mandiri">Mandiri</label>
                </div>

                <label>Nama Asesor</label>
                <div>: <input type="text" value="Ajeng Febria Hidayati (Contoh)"></div>
                
                <label>Nama Asesi</label>
                <div>: <input type="text" value="Tatang Sidartang (Contoh)"></div>
                
                <label>Tanggal</label>
                <div>: <input type="date" value="<?php echo date('Y-m-d'); ?>"></div>
            </div>

            <div class="guide-box">
                <h3>PANDUAN BAGI ASESOR</h3>
                <ul>
                    <li>Tentukan pihak ketiga yang akan dimintai verifikasi.</li>
                    <li>Ajukan pertanyaan kepada pihak ketiga.</li>
                    <li>Berikan penilaian kepada asesi berdasarkan verifikasi pihak ketiga.</li>
                    <li>Pertanyaan/pernyataan dapat dikembangkan sesuai dengan konteks pekerjaan dan relasi.</li>
                </ul>
            </div>
            
            <div class="form-section">
                <h2>Data Pihak Ketiga</h2>
                <div class="form-group">
                    <label for="supervisor_name">Nama Pengawas/penyelia/atasan/orang lain di perusahaan :</label>
                    <input type="text" id="supervisor_name" name="supervisor_name">
                </div>
                <div class="form-group">
                    <label for="workplace">Tempat kerja :</label>
                    <input type="text" id="workplace" name="workplace">
                </div>
                <div class="form-group">
                    <label for="address">Alamat :</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form-group">
                    <label for="phone">Telepon :</label>
                    <input type="text" id="phone" name="phone">
                </div>
            </div>

            <div class="form-section">
                <h2>Daftar Pertanyaan</h2>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Ya</th>
                            <th>Tdk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>"Apakah asesi bekerja dengan mempertimbangkan Kesehatan, Keamanan dan Keselamatan Kerja?"</td>
                            <td><input type="checkbox" name="q1" value="ya"></td>
                            <td><input type="checkbox" name="q1" value="tidak"></td>
                        </tr>
                        <tr>
                            <td>Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?</td>
                            <td><input type="checkbox" name="q2" value="ya"></td>
                            <td><input type="checkbox" name="q2" value="tidak"></td>
                        </tr>
                        <tr>
                            <td>Apakah asesi dapat mengelola tugas-tugas secara bersamaan?</td>
                            <td><input type="checkbox" name="q3" value="ya"></td>
                            <td><input type="checkbox" name="q3" value="tidak"></td>
                        </tr>
                        <tr>
                            <td>Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?</td>
                            <td><input type="checkbox" name="q4" value="ya"></td>
                            <td><input type="checkbox" name="q4" value="tidak"></td>
                        </tr>
                        <tr>
                            <td>Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?</td>
                            <td><input type="checkbox" name="q5" value="ya"></td>
                            <td><input type="checkbox" name="q5" value="tidak"></td>
                        </tr>
                        <tr>
                            <td>Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?</td>
                            <td><input type="checkbox" name="q6" value="ya"></td>
                            <td><input type="checkbox" name="q6" value="tidak"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-section">
                <h2>Detail Verifikasi</h2>
                <div class="form-group">
                    <label for="relation">Apa hubungan Anda dengan asesi?</label>
                    <textarea id="relation" name="relation"></textarea>
                </div>
                <div class="form-group">
                    <label for="duration">Berapa lama Anda bekerja dengan asesi?</label>
                    <textarea id="duration" name="duration"></textarea>
                </div>
                <div class="form-group">
                    <label for="proximity">Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?</label>
                    <textarea id="proximity" name="proximity"></textarea>
                </div>
                <div class="form-group">
                    <label for="experience">Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)</label>
                    <textarea id="experience" name="experience"></textarea>
                </div>
            </div>

            <div class="form-section">
                <h2>Kesimpulan</h2>
                <div class="form-group">
                    <label for="consistency">"Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?"</label>
                    <textarea id="consistency" name="consistency"></textarea>
                </div>
                <div class="form-group">
                    <label for="training_needs">Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:</label>
                    <textarea id="training_needs" name="training_needs"></textarea>
                </div>
                <div class="form-group">
                    <label for="other_comments">Ada komentar lain:</label>
                    <textarea id="other_comments" name="other_comments"></textarea>
                </div>
            </div>
            
            <div class="signature-section">
                <div class="signature-box">
                    <label>Tanda tangan Asesor:</label>
                    <div class="signature-pad">
                        </div>
                    <input type="text" value="Ajeng Febria Hidayati (Contoh)" readonly>
                    <div class="date-input">
                        <label>Tanggal: </label>
                        <input type="text" value="<?php echo date('d - m - Y'); ?>">
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <button type="button" class="btn btn-secondary">Sebelumnya</button>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
            
            <div class="footer-notes">
                <p>*Coret yang tidak perlu</p>
                <p>Informasi Rahasia</p>
                <br>
                <p>Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008</p>
            </div>

        </form>

    </main>

</body>
</html>