<!DOCTYPE html>
<html>

<head>
    <title>FR.AK.01 Persetujuan Asesmen</title>
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            margin: 30px;
            color: #000;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }

        .header-right img {
            max-height: 90px;
        }

        .logo-bnsp {
            max-height: 100px;
        }

        .title-section {
            text-align: center;
            margin: 30px 0 20px 0;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }

        .title-section h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }

        .title-section h2 {
            margin: 5px 0 0 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .intro-text {
            margin: 20px 0;
            text-align: justify;
            line-height: 1.5;
        }

        .section-title {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .info-table {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 25%;
        }

        .info-table td:nth-child(2) {
            width: 2%;
        }

        .bukti-list {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .bukti-grid {
            display: table;
            width: 100%;
        }

        .bukti-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .checkbox-item {
            margin-bottom: 5px;
            position: relative;
            padding-left: 20px;
        }

        .checkmark {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12pt;
            font-weight: bold;
            line-height: 1;
            color: #000;
            position: absolute;
            left: 0;
            top: 0;
        }

        .pelaksanaan-section {
            margin-left: 20px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .pelaksanaan-section table {
            width: 100%;
        }

        .pelaksanaan-section td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .pelaksanaan-section td:first-child {
            width: 20%;
        }

        .pelaksanaan-section td:nth-child(2) {
            width: 2%;
        }

        .tuk-indent {
            margin-left: 20px;
        }

        .pernyataan-section {
            margin-left: 20px;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .pernyataan-item {
            margin-bottom: 15px;
            text-align: justify;
            line-height: 1.5;
        }

        .pernyataan-item strong {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-left {
            display: table-cell;
            width: 50%;
            text-align: left;
            vertical-align: top;
            padding: 5px;
        }

        .signature-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
            padding: 5px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 10px;
        }

        .signature-date {
            margin-top: 5px;
            font-size: 9pt;
        }
    </style>
</head>

<body>

    <!-- HEADER dengan Logo -->
    <div class="header">
        <div class="header-left">
            @if ($logoBnspBase64)
                <img src="data:image/png;base64,{{ $logoBnspBase64 }}" alt="BNSP" class="logo-bnsp">
            @endif
        </div>
        <div class="header-right">
            @if ($logoLspBase64)
                <img src="data:image/png;base64,{{ $logoLspBase64 }}" alt="LSP" class="logo-lsp">
            @endif
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-section">
        <h1>PERSETUJUAN ASESMEN DAN KERAHASIAAN</h1>
        <h2>FR.AK.01</h2>
    </div>

    <!-- INTRO TEXT -->
    <div class="intro-text">
        Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan
        proses asesmen.
    </div>

    <!-- SKEMA SERTIFIKASI -->
    <div class="section-title">Skema Sertifikasi</div>

    <table class="info-table">
        <tr>
            <td>Judul</td>
            <td>:</td>
            <td>{{ $skema->nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $skema->nomor_skema }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td>:</td>
            <td>{{ $jenisTuk->jenis_tuk ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Asesor</td>
            <td>:</td>
            <td>{{ $asesor->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Asesi</td>
            <td>:</td>
            <td>{{ $asesi->nama_lengkap }}</td>
        </tr>
    </table>

    <!-- BUKTI YANG AKAN DIKUMPULKAN -->
    <div class="section-title">Bukti yang dikumpulkan</div>

    @php
        // Kelompokkan bukti menjadi 2 kolom
        $totalBukti = $responBukti->count();
        $middleIndex = ceil($totalBukti / 2);
        $buktiKiri = $responBukti->slice(0, $middleIndex);
        $buktiKanan = $responBukti->slice($middleIndex);
    @endphp

    <div class="bukti-list">
        <div class="bukti-grid">
            <div class="bukti-col">
                @foreach ($buktiKiri as $respon)
                    <div class="checkbox-item">
                        <span class="checkmark">✓</span>
                        {{ $respon->buktiMaster->bukti ?? 'Bukti' }}
                    </div>
                @endforeach
            </div>

            <div class="bukti-col">
                @foreach ($buktiKanan as $respon)
                    <div class="checkbox-item">
                        <span class="checkmark">✓</span>
                        {{ $respon->buktiMaster->bukti ?? 'Bukti' }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- PELAKSANAAN ASESMEN -->
    <div class="section-title">Pelaksanaan Asesmen Disepakati pada</div>

    <div class="pelaksanaan-section">
        <table>
            <tr>
                <td>Hari/Tanggal</td>
                <td>:</td>
                <td>{{ $hari }}, {{ $tanggal }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $jam }} WIB</td>
            </tr>
            <tr>
                <td>TUK</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>

        <table class="tuk-indent">
            <tr>
                <td>Lokasi</td>
                <td>:</td>
                <td>{{ $tuk->nama_lokasi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $tuk->alamat_tuk ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- PERNYATAAN -->
    <div class="section-title">Pernyataan</div>

    <div class="pernyataan-section">
        <div class="pernyataan-item">
            <strong>Asesi:</strong><br>
            Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.
        </div>

        <div class="pernyataan-item">
            <strong>Asesor:</strong><br>
            Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam
            pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan
            dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
        </div>

        <div class="pernyataan-item">
            <strong>Asesi:</strong><br>
            Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk
            pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
        </div>
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-title">Asesi</div>
            <div class="signature-space">
                @if ($ttdAsesiBase64)
                    <img src="data:image/png;base64,{{ $ttdAsesiBase64 }}" alt="Tanda Tangan Asesi"
                        style="max-width: 120px; max-height: 70px; margin-top: 5px;">
                @endif
            </div>
            <div class="signature-name">
                ({{ $asesi->nama_lengkap }})
            </div>
            <div class="signature-date">
                {{ $tanggalRespon }}
            </div>
        </div>

        <div class="signature-right">
            <div class="signature-title">Asesor</div>
            <div class="signature-space">
                @if ($ttdAsesorBase64)
                    <img src="data:image/png;base64,{{ $ttdAsesorBase64 }}" alt="Tanda Tangan Asesor"
                        style="max-width: 120px; max-height: 70px; margin-top: 5px;">
                @endif
            </div>
            <div class="signature-name">
                ({{ $asesor->nama_lengkap ?? '____________________' }})
            </div>
            <div class="signature-date">
                (No. reg {{ $asesor->nomor_regis ?? '____________________' }})
            </div>
            <div class="signature-date">
                {{ $tanggalRespon }}
            </div>
        </div>
    </div>

</body>

</html>
