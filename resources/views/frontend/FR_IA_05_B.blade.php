<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.05.B - Lembar Kunci Jawaban</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .answer-key-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* 2 kolom sama lebar */
            gap: 0 24px; /* Jarak antar kolom */
            width: 100%;
        }
        .answer-key-grid .form-table {
            margin-bottom: 0; /* Hapus margin bawah tabel di dalam grid */
        }
        .answer-key-grid .form-table th,
        .answer-key-grid .form-table td {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    @include('layouts.app-sidebar')

    <main class="main-content">
        
        <header class="form-header">
            <div class="title-block">
                <h1>FR.IA.05.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA</h1>
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
            </div>

            <div class="form-section">
                <table class="form-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Kelompok Pekerjaan ...</th>
                            <th style="width: 10%;">No.</th>
                            <th style="width: 30%;">Kode Unit</th>
                            <th style="width: 35%;">Judul Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3" style="vertical-align: top; padding-top: 10px;">..............................</td>
                            <td>1.</td>
                            <td><input type="text" name="kode_unit_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="judul_unit_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td><input type="text" name="kode_unit_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="judul_unit_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td><input type="text" name="kode_unit_3" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="judul_unit_3" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Dst.</td>
                            <td><input type="text" name="kode_unit_dst" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            <td><input type="text" name="judul_unit_dst" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-section">
                <h3 style="margin-bottom: 16px; font-weight: 600;">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
                
                <div class="answer-key-grid">
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">No.</th>
                                <th>Kunci Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td><input type="text" name="kunci_1" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td><input type="text" name="kunci_2" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td><input type="text" name="kunci_3" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td><input type="text" name="kunci_4" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td><input type="text" name="kunci_5" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">No.</th>
                                <th>Kunci Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>6.</td>
                                <td><input type="text" name="kunci_6" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td><input type="text" name="kunci_7" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td><input type="text" name="kunci_8" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td><input type="text" name="kunci_9" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                            <tr>
                                <td>Dst.</td>
                                <td><input type="text" name="kunci_dst" style="width:100%; border: 1px solid #ccc; padding: 5px;"></td>
                            </tr>
                        </tbody>
                    </table>
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