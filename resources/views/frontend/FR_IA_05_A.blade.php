<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.05A - Pertanyaan Tertulis Pilihan Ganda</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body>

    @include('layouts.app-sidebar')

    <main class="main-content">
        
        <header class="form-header">
            <div class="title-block">
                <h1>FR.IA.05A. DPT - PERTANYAAN TERTULIS PILIHAN GANDA</h1>
            </div>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Logo_BNSP.svg/2560px-Logo_BNSP.svg.png" alt="Logo BNSP">
        </header>

        @if (session('success'))
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form class="form-body" method="POST" action="">
            @csrf 
            
            <div class="metadata-grid">
                <label>Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div>: <input type="text" name="judul" placeholder="Judul Skema..."></div>
                
                <label>Nomor</label>
                <div>: <input type="text" name="nomor" placeholder="Nomor Skema..."></div>

                <label>TUK</label>
                <div class="radio-group">
                    : 
                    <input type="radio" id="tuk_sewaktu" name="tuk_type" value="sewaktu"><label for="tuk_sewaktu">Sewaktu</label>
                    <input type="radio" id="tuk_tempatkerja" name="tuk_type" value="tempat_kerja" checked><label for="tuk_tempatkerja">Tempat Kerja</label>
                    <input type="radio" id="tuk_mandiri" name="tuk_type" value="mandiri"><label for="tuk_mandiri">Mandiri</label>
                </div>

                <label>Nama Asesor</label>
                <div>: <input type="text" name="asesor" placeholder="Nama Asesor..."></div>
                
                <label>Nama Asesi</label>
                <div>: <input type="text" name="asesi" placeholder="Nama Asesi..."></div>
                
                <label>Tanggal</label>
                <div>: <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>"></div>

                <label>Waktu</label>
                <div>: <input type="time" name="waktu"></div>
            </div>

            <div class="form-section">
                <h3 style="margin-bottom: 16px; font-weight: 600;">Jawab semua pertanyaan berikut:</h3>

                <div class="form-group" style="background: #fdfdfd; padding: 16px; border-radius: 8px; border: 1px solid #eee;">
                    <label for="q1" style="font-size: 1rem;">1.</label>
                    <textarea id="q1" name="q1" rows="2" placeholder="Tulis pertanyaan nomor 1 di sini..."></textarea>
                    
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 8px 12px; align-items: center; margin-top: 12px; margin-left: 20px;">
                        <label for="q1a">a.</label> <input type="text" id="q1a" name="q1a" placeholder="Opsi jawaban a...">
                        <label for="q1b">b.</label> <input type="text" id="q1b" name="q1b" placeholder="Opsi jawaban b...">
                        <label for="q1c">c.</label> <input type="text" id="q1c" name="q1c" placeholder="Opsi jawaban c...">
                        <label for="q1d">d.</label> <input type="text" id="q1d" name="q1d" placeholder="Opsi jawaban d...">
                    </div>
                </div>

                <div class="form-group" style="background: #fdfdfd; padding: 16px; border-radius: 8px; border: 1px solid #eee; margin-top:16px;">
                    <label for="q2" style="font-size: 1rem;">2.</label>
                    <textarea id="q2" name="q2" rows="2" placeholder="Tulis pertanyaan nomor 2 di sini..."></textarea>
                    
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 8px 12px; align-items: center; margin-top: 12px; margin-left: 20px;">
                        <label for="q2a">a.</label> <input type="text" id="q2a" name="q2a" placeholder="Opsi jawaban a...">
                        <label for="q2b">b.</label> <input type="text" id="q2b" name="q2b" placeholder="Opsi jawaban b...">
                        <label for="q2c">c.</label> <input type="text" id="q2c" name="q2c" placeholder="Opsi jawaban c...">
                        <label for="q2d">d.</label> <input type="text" id="q2d" name="q2d" placeholder="Opsi jawaban d...">
                    </div>
                </div>

                <div class="form-group" style="background: #fdfdfd; padding: 16px; border-radius: 8px; border: 1px solid #eee; margin-top:16px;">
                    <label for="q3" style="font-size: 1rem;">3.</label>
                    <textarea id="q3" name="q3" rows="2" placeholder="Tulis pertanyaan nomor 3 di sini..."></textarea>
                    
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 8px 12px; align-items: center; margin-top: 12px; margin-left: 20px;">
                        <label for="q3a">a.</label> <input type="text" id="q3a" name="q3a" placeholder="Opsi jawaban a...">
                        <label for="q3b">b.</label> <input type="text" id="q3b" name="q3b" placeholder="Opsi jawaban b...">
                        <label for="q3c">c.</label> <input type="text" id="q3c" name="q3c" placeholder="Opsi jawaban c...">
                        <label for="q3d">d.</label> <input type="text" id="q3d" name="q3d" placeholder="Opsi jawaban d...">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>PENYUSUN DAN VALIDATOR</h2>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>NOMOR MET</th>
                            <th>TANDA TANGAN DAN TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PENYUSUN</td>
                            <td>1</td>
                            <td><input type="text" name="penyusun_nama_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="penyusun_met_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td style="height: 60px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>2</td>
                            <td><input type="text" name="penyusun_nama_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="penyusun_met_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td style="height: 60px;"></td>
                        </tr>
                        <tr>
                            <td>VALIDATOR</td>
                            <td>1</td>
                            <td><input type="text" name="validator_nama_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="validator_met_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td style="height: 60px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>2</td>
                            <td><input type="text" name="validator_nama_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="validator_met_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td style="height: 60px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="form-footer">
                <button type="button" class="btn btn-secondary">Sebelumnya</button>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
            
            <div class="footer-notes">
                <p>*Coret yang tidak perlu</p>
            </div>

        </form>

    </main>

</body>
</html>