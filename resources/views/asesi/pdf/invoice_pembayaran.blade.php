<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice Pembayaran</title>
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
            max-height: 115px;
        }

        .logo-bnsp {
            max-height: 135px;
        }

        .title-section {
            text-align: center;
            margin: 30px 0 20px 0;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }

        .title-section h1 {
            margin: 0;
            font-size: 20pt;
            font-weight: bold;
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
            width: 30%;
        }

        .info-table td:nth-child(2) {
            width: 2%;
        }

        .alert-box {
            margin-left: 20px;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 10pt;
            line-height: 1.5;
        }

        .bg-green {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #064e3b;
        }

        .bg-yellow {
            background: #fef9c3;
            border: 1px solid #facc15;
            color: #854d0e;
        }

        .bg-red {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #7f1d1d;
        }

        .alert-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .checkmark {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14pt;
            font-weight: bold;
            line-height: 1;
            color: #064e3b;
        }

        .warning-icon {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14pt;
            font-weight: bold;
            line-height: 1;
            color: #854d0e;
        }

        .bordered-table {
            border: 1px solid #000;
            margin-top: 10px;
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .bordered-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }

        .bordered-table thead td {
            font-weight: bold;
            text-align: center;
            background-color: #000;
            color: #fff;
            text-transform: uppercase;
            font-size: 10pt;
        }

        .bordered-table tbody td {
            background-color: #e5e7eb;
            text-align: center;
            font-weight: bold;
        }

        .va-section {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .va-label {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .va-box {
            background: #fff7d1;
            border: 1px solid #ffd658;
            padding: 12px;
            border-radius: 6px;
            font-style: italic;
            margin-top: 6px;
        }

        .footer-note {
            font-size: 9pt;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 20px;
            margin-left: 20px;
        }

        .status-lunas {
            color: #166534;
            font-weight: bold;
        }

        .status-pending {
            color: #ca8a04;
            font-weight: bold;
        }

        .status-jatuh-tempo {
            color: #dc2626;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <!-- HEADER dengan Logo -->
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('images/Logo_BNSP.png') }}" alt="BNSP" class="logo-bnsp">
        </div>
        <div class="header-right">
            <img src="{{ public_path('images/Logo_LSP_No_BG.png') }}" alt="LSP" class="logo-lsp">
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-section">
        <h1>PEMBAYARAN UJI KOMPETENSI</h1>
    </div>

    <!-- INFORMASI PEMBAYARAN -->
    <div class="section-title">Informasi Pembayaran</div>

    <table class="info-table">
        <tr>
            <td>No. Tagihan</td>
            <td>:</td>
            <td style="font-family: 'Courier New', monospace;">{{ $payment->order_id }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $asesi->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $asesi->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ $asesi->pekerjaan ?? 'Mahasiswa' }}</td>
        </tr>
        <tr>
            <td>Tanggal Terbuat</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Jatuh Tempo</td>
            <td>:</td>
            <td class="status-jatuh-tempo">
                {{ \Carbon\Carbon::parse($payment->created_at)->addDay()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Status Pembayaran</td>
            <td>:</td>
            <td>
                @if (in_array($payment->status_transaksi, ['settlement', 'capture']))
                    <span class="status-lunas">LUNAS</span>
                @else
                    <span class="status-pending">{{ strtoupper($payment->status_transaksi) }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Dibayar Melalui</td>
            <td>:</td>
            <td>{{ strtoupper($payment->jenis_pembayaran ?? '-') }}</td>
        </tr>
    </table>

    <!-- DETAIL PEMBAYARAN -->
    <div class="section-title">Detail Pembayaran</div>

    @if (in_array($payment->status_transaksi, ['settlement', 'capture']))
        <div class="alert-box bg-green">
            <div class="alert-title">
                <span class="checkmark">✓</span> TAGIHAN SUDAH DIBAYAR
            </div>
            <table style="font-size: 10pt; margin-left: 0;">
                <tr>
                    <td style="width: 28%; font-weight: bold; padding: 2px 0;">Waktu Pembayaran</td>
                    <td style="width: 2%; padding: 2px 0;">:</td>
                    <td style="padding: 2px 0;">
                        {{ \Carbon\Carbon::parse($payment->updated_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 2px 0;">Melalui Bank</td>
                    <td style="padding: 2px 0;">:</td>
                    <td style="padding: 2px 0;">{{ strtoupper($payment->bank ?? 'QRIS/E-Wallet') }}</td>
                </tr>
            </table>
        </div>
    @else
        <div class="alert-box bg-red">
            <div class="alert-title">
                <span class="warning-icon">⚠</span> MENUNGGU PEMBAYARAN
            </div>
            Harap segera lakukan pembayaran sebelum jatuh tempo.
        </div>
    @endif

    <!-- TABEL TAGIHAN -->
    <div class="section-title">Rincian Tagihan</div>

    <table class="bordered-table">
        <thead>
            <tr>
                <td style="width: 33%;">Total Tagihan</td>
                <td style="width: 33%;">Sudah Dibayar</td>
                <td style="width: 34%;">Belum Dibayar</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>
                    @if (in_array($payment->status_transaksi, ['settlement', 'capture']))
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    @else
                        Rp 0
                    @endif
                </td>
                <td>
                    @if (in_array($payment->status_transaksi, ['settlement', 'capture']))
                        Rp 0
                    @else
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- NOMOR VIRTUAL ACCOUNT -->
    <div class="va-section">
        <div class="va-label">Nomor Virtual Account (VA):</div>
        <div class="va-box">
            @if (isset($payment->va_number))
                <div style="font-size: 16pt; font-weight: bold; letter-spacing: 1px; margin-bottom: 6px;">
                    {{ $payment->va_number }}
                </div>
                <div style="font-size: 9pt;">
                    Bank: {{ strtoupper($payment->bank ?? '') }}
                </div>
            @else
                <div>Nomor VA akan muncul setelah metode pembayaran dipilih.</div>
            @endif
        </div>
    </div>

    <!-- FOOTER NOTE -->
    <div class="footer-note">
        <strong style="color: #000;">Catatan:</strong><br>
        Simpan dokumen ini sebagai bukti pembayaran yang sah.
    </div>

</body>

</html>
