<!DOCTYPE html>
<html>

<head>
    <title>FR.APL.02 Asesmen Mandiri</title>
    <style>
        @page {
            size: A4;
            /* Ukuran kertas A4 */
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
            margin-bottom: 10px;
            /* Dikurangi untuk dekatkan jarak ke title */
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
            margin: 15px 0 10px 0;
            /* Dikurangi untuk dekatkan jarak */
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .bg-grey {
            background-color: #e2e2e2;
        }

        .panduan-box {
            border: 1px solid black;
            padding: 10px;
            margin: 20px 0;
            background-color: #f9f9f9;
        }

        .panduan-box .title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .checkmark {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14pt;
            font-weight: bold;
            line-height: 1;
            color: #000;
        }

        .unit-table {
            margin-top: 20px;
            /* Hapus border tebal untuk hilangkan garis bawah unit kompetensi */
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
            /* Sesuaikan seperti APL01: align kanan */
            vertical-align: top;
            padding: 5px;
            /* Hapus padding-left */
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
            margin-top: 5px;
        }

        .signature-detail {
            margin-top: 3px;
            font-size: 9pt;
        }

        .rekomendasi-section {
            margin-top: 20px;
            padding: 10px;
            /* Hapus border untuk hilangkan kotak */
        }

        .rekomendasi-text {
            margin: 10px 0;
        }

        .status-text {
            font-weight: bold;
            font-size: 12pt;
            color: #000;
        }

        .rekomendasi-box {
            /* Style mirip APL01 */
            padding: 10px 0;
            text-align: center;
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
        <h1>ASESMEN MANDIRI</h1>
        <h2>FR.APL.02</h2>
    </div>

    <!-- INFO SKEMA -->
    <table style="border: 2px solid black;">
        <tr>
            <td rowspan="2" width="25%" class="text-bold">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td width="15%" class="text-bold">Judul</td>
            <td width="2%">:</td>
            <td>{{ $skema->nama_skema }}</td>
        </tr>
        <tr>
            <td class="text-bold">Nomor</td>
            <td>:</td>
            <td>{{ $skema->nomor_skema }}</td>
        </tr>
    </table>

    <!-- PANDUAN ASESMEN MANDIRI -->
    <div class="panduan-box">
        <div class="title">Panduan Asesmen Mandiri</div>
        <div style="margin-top: 8px;">
            <strong>Instruksi:</strong>
        </div>
        <div style="margin-left: 10px; margin-top: 5px;">
            1. Baca setiap pertanyaan di kolom sebelah kiri.<br>
            2. Beri tanda centang (<span class="checkmark">✓</span>) pada kotak jika Anda yakin dapat melakukan tugas
            yang dijelaskan.<br>
            3. Isi kolom di sebelah kanan dengan menuliskan bukti yang relevan anda miliki untuk menunjukkan bahwa anda
            melakukan pekerjaan.
        </div>
    </div>

    <!-- UNIT KOMPETENSI -->
    @foreach ($skema->unitKompetensi as $unit)
        <table class="unit-table">
            <tr class="bg-grey">
                <td width="20%" class="text-bold">Unit Kompetensi {{ $loop->iteration }}</td>
                <td width="15%" class="text-bold">Kode Unit</td>
                <td width="2%">:</td>
                <td>{{ $unit->kode_unit }}</td>
            </tr>
            <tr class="bg-grey">
                <td></td>
                <td class="text-bold">Judul Unit</td>
                <td>:</td>
                <td>{{ $unit->judul_unit }}</td>
            </tr>
        </table>

        <table style="border: 2px solid black;">
            <thead>
                <tr class="bg-grey">
                    <th width="55%">Dapatkah saya?</th>
                    <th width="8%">K</th>
                    <th width="8%">BK</th>
                    <th width="29%">Bukti yang Relevan</th>
                </tr>
            </thead>
            <tbody>
                @if ($unit->elemen && count($unit->elemen) > 0)
                    @foreach ($unit->elemen as $idxElemen => $elemen)
                        <tr>
                            <td colspan="4" class="bg-grey text-bold">
                                Elemen {{ $idxElemen + 1 }}:
                            </td>
                        </tr>

                        @if ($elemen->kriteria && count($elemen->kriteria) > 0)
                            @foreach ($elemen->kriteria as $idxKuk => $kuk)
                                @php
                                    // Cari respon dari database berdasarkan id_kriteria
                                    $respon = $sertifikasi->responapl2ia01
                                        ->where('id_kriteria', $kuk->id_kriteria)
                                        ->first();
                                    $responAsesi = $respon->respon_asesi_apl02 ?? null;
                                    $buktiAsesi = $respon->bukti_asesi_apl02 ?? '-';
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $idxElemen + 1 }}.{{ $idxKuk + 1 }}. Kriteria Unjuk
                                            Kerja</strong><br>
                                        {{ $kuk->kriteria ?? ($kuk->nama_kriteria ?? 'Kriteria ' . ($idxKuk + 1)) }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        @if ($responAsesi === 1)
                                            <span class="checkmark">✓</span> <!-- Tanpa kotak -->
                                        @endif
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        @if ($responAsesi === 0)
                                            <span class="checkmark">✓</span> <!-- Tanpa kotak -->
                                        @endif
                                    </td>
                                    <td>
                                        {{ $buktiAsesi }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">Data Kriteria belum tersedia di database.</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Rincian Elemen Kompetensi belum tersedia.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
    @endforeach

    <!-- REKOMENDASI -->
    <div style="page-break-inside: avoid;">
        <div class="rekomendasi-section" style="page-break-inside: avoid;">
            <div class="text-bold" style="margin-bottom: 10px;">D. Rekomendasi</div>
            <p style="margin-left: 20px; margin-top: 5px;"> <!-- Sesuaikan seperti APL01 -->
                Berdasarkan ketentuan persyaratan dasar, maka asesmen
                <span class="rekomendasi-box">
                    @if ($sertifikasi->rekomendasi_apl02 == 'diterima')
                        <span class="status-text">Dapat</span>
                    @elseif($sertifikasi->rekomendasi_apl02 == 'tidak_diterima')
                        <span class="status-text">Tidak dapat</span>
                    @else
                        <span class="status-text">Belum Direkomendasikan</span>
                    @endif
                </span>
                dilanjutkan.
            </p>
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
                <div class="signature-detail">
                    {{ $tanggal }}
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
                <div class="signature-detail">
                    (No. reg {{ $asesor->nomor_regis ?? '____________________' }})
                </div>
                <div class="signature-detail">
                    {{ $tanggal }}
                </div>
            </div>
        </div>
    </div>

</body>

</html>
