<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Sertifikasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 750px;
            margin: 20px auto;
            padding: 0;
        }

        /* Border untuk seluruh kartu */
        .card-wrapper {
            border: 3px dashed #999;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Header dengan logo */
        .header {
            border: 2px solid #000;
            padding: 15px;
            display: table;
            width: 100%;
            margin-bottom: 0;
        }

        .header-content {
            display: table-row;
        }

        .foto-peserta {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
            padding-right: 15px;
        }

        .foto-peserta img {
            width: 200px;
            height: 230px;
            border: 2px solid #000;
            object-fit: cover;
            background-color: #ff0000;
        }

        .header-title {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 0px;
        }

        .logo-container img {
            height: 50px;
            margin: 0 50px;
            vertical-align: middle;
        }

        .header-title h1 {
            font-size: 20pt;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-title h2 {
            font-size: 15pt;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }

        /* Section Identitas */
        .section {
            border: 2px solid #000;
            border-top: none;
            padding: 15px;
            margin-bottom: 0;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .info-label {
            display: table-cell;
            width: 150px; 
            font-weight: bold;
            vertical-align: top;
            text-transform: uppercase;
        }

        .info-colon {
            display: table-cell;
            width: 15px;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            vertical-align: top;
        }

        /* PERUBAHAN UTAMA: Class untuk menjorokkan baris data */
        .indented-info {
            margin-left: 20px; /* Mendorong seluruh baris ke kanan */
        }
        /* Sesuaikan lebar table-cell untuk baris yang menjorok */
        .indented-info .info-label {
            width: 130px; /* Kurangi lebar label karena sudah ada margin kiri 20px (150px - 20px = 130px) */
        }


        /* Section dengan judul */
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 12px;
            margin-top: 8px;
            text-transform: uppercase;
        }

        /* Last section */
        .section:last-child {
            margin-bottom: 0;
        }

        /* Responsive adjustments */
        @media print {
            .card-wrapper {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-wrapper">

            <div class="header">
                <div class="header-content">
                    <div class="foto-peserta">
                        @if ($fotoAsesi)
                            <img src="data:image/png;base64,{{ $fotoAsesi }}" alt="Foto Peserta">
                        @else
                            <div
                                style="width: 100px; height: 130px; background-color: #ff0000; border: 2px solid #000;">
                            </div>
                        @endif
                    </div>

                    <div class="header-title">
                        <div class="logo-container">
                            @if ($logoBnsp)
                                <img src="data:image/png;base64,{{ $logoBnsp }}" alt="Logo BNSP">
                            @endif
                            @if ($logoLsp)
                                <img src="data:image/png;base64,{{ $logoLsp }}" alt="Logo LSP">
                            @endif
                        </div>
                        <h1>Kartu Tanda Peserta</h1>
                        <h2>Lembaga Sertifikasi Profesi</h2>
                        <h2>Tahun 2025</h2>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="info-row">
                    <div class="info-label">Nama Asesi</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $namaAsesi }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Lahir</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $tanggalLahir }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIK</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $nik }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Jadwal Sertifikasi</div>
                
                <div class="info-row indented-info">
                    <div class="info-label">Hari</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $hari }}</div>
                </div>
                <div class="info-row indented-info">
                    <div class="info-label">Tanggal</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $tanggal }}</div>
                </div>
                <div class="info-row indented-info">
                    <div class="info-label">Pukul</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $pukul }}</div>
                </div>

                <div class="section-title">Lokasi Uji Kompetensi</div>
                <div class="info-row indented-info">
                    <div class="info-label">Jenis TUK</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $jenisTuk }}</div>
                </div>
                <div class="info-row indented-info">
                    <div class="info-label">Lokasi</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $lokasi }}</div>
                </div>
                <div class="info-row indented-info">
                    <div class="info-label">Alamat</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $alamat }}</div>
                </div>

                <div class="section-title">Skema Sertifikasi</div>
                <div class="info-row indented-info">
                    <div class="info-label">Skema</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $skema }}</div>
                </div>
                <div class="info-row indented-info">
                    <div class="info-label">Nomor Skema</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $nomorSkema }}</div>
                </div>
                <div class="info-row indented-info">
                    <div class="info-label">Nama Asesor</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $namaAsesor }}</div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>