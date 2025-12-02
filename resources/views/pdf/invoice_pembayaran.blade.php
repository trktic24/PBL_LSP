<!DOCTYPE html>

<html lang="id">
<head>
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Invoice Pembayaran</title>
<style>
    /* ===== PAGE / PDF ===== */
    @page {
        margin: 20px 30px; /* margin aman untuk PDF */
        size: A4 portrait;
    }

/* global box-sizing penting untuk perhitungan width/padding */
*, *:before, *:after { box-sizing: border-box; }

body {
    margin: 0;
    padding: 0;
    font-family: "DejaVu Sans", sans-serif;
    font-size: 13px;
    color: #1f2937;
    line-height: 1.45;
    background: #fff;
}

/* WRAPPER UTAMA - kontrol layout seluruh dokumen */
.page-wrap {
    width: 100%;
    max-width: 900px;   /* batas lebar konten */
    margin: 0 auto;     /* center di halaman */
    padding: 16px 18px; /* padding aman */
}

/* container internal: jangan duplicate padding besar */
.container {
    width: 100%;
    padding: 0; /* biar gak double padding */
}

/* HEADER */
.header-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-bottom: 18px;
}
.header-table td { vertical-align: middle; padding: 0; }

.title-divider {
    width: 100%;
    border-bottom: 2px solid #6b7280; /* warna abu gelap seperti gambar */
    margin: 6px 0 18px; /* jarak atas dan bawah */
}

.logo-img {
    max-width: 120px; /* sesuaikan kalau butuh lebih kecil */
    width: auto;
    height: auto;
    display: block;
}

.page-title {
    text-align: center;
    font-size: 18px;
    font-weight: 700;
    margin: 6px 0 18px;
    text-transform: uppercase;
}

/* BIODATA */
.info-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-bottom: 14px;
}
.info-table td { padding: 4px 6px; vertical-align: top; }
.col-label { width: 30%; font-weight: 700; color: #374151; }
.col-sep { width: 4%; text-align: center; }
.col-val { width: 66%; }

/* ALERT BOX */
.alert-box {
    padding: 14px;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 13px;
}
.bg-green { background: #d1fae5; border: 1px solid #10b981; color: #064e3b; }
.bg-yellow { background: #fef9c3; border: 1px solid #facc15; color: #854d0e; }

/* TABEL TAGIHAN */
.bill-title { text-align: center; font-weight: 700; margin: 12px 0; }
.bill-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-bottom: 18px;
}
.bill-table th {
    background: #000;
    color: #fff;
    padding: 10px 8px;
    font-size: 12px;
    text-transform: uppercase;
}
.bill-table td {
    background: #e5e7eb;
    padding: 12px 8px;
    text-align: center;
    font-weight: 700;
    border: 1px solid #fff;
    word-wrap: break-word;
}
/* kolom fixed supaya gak geser */
.bill-col-1 { width: 33%; }
.bill-col-2 { width: 33%; }
.bill-col-3 { width: 34%; }

/* VA BOX */
.va-label { font-weight: 700; font-size: 14px; margin-bottom: 6px; }
.va-box {
    background: #fff7d1;
    border: 1px solid #ffd658;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 22px;
    font-style: italic;
}

/* FOOTER */
.footer-note {
    font-size: 11px;
    color: #6b7280;
    border-top: 1px solid #e5e7eb;
    padding-top: 10px;
    margin-top: 6px;
}

/* responsive safety: jika PDF engine merubah DPI */
@media print {
    .logo-img { max-width: 110px; }
}

</style>
</head>
<body>

<div class="page-wrap">
    <div class="container">

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td style="width:50%; text-align:left;">
                <img src="{{ public_path('images/Logo_BNSP.png') }}" alt="BNSP" class="logo-img">
            </td>
            <td style="width:50%; text-align:right;">
                <img src="{{ public_path('images/Logo_LSP_No_BG.png') }}" alt="LSP" class="logo-img">
            </td>
        </tr>
    </table>

    <!-- TITLE -->
    <div class="page-title">PEMBAYARAN UJI KOMPETENSI</div>
    <div class="title-divider"></div>

    <!-- INFO -->
    <table class="info-table" role="presentation">
        <tr>
            <td class="col-label">No. Tagihan</td>
            <td class="col-sep">:</td>
            <td class="col-val" style="font-family: monospace;">{{ $payment->order_id }}</td>
        </tr>
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $asesi->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $asesi->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $asesi->pekerjaan ?? 'Mahasiswa' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tanggal Terbuat</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="col-label">Jatuh Tempo</td>
            <td class="col-sep">:</td>
            <td class="col-val" style="color:#dc2626;">{{ \Carbon\Carbon::parse($payment->created_at)->addDay()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="col-label">Status Pembayaran</td>
            <td class="col-sep">:</td>
            <td class="col-val">
                @if(in_array($payment->status_transaksi, ['settlement','capture']))
                    <span style="color:#166534; font-weight:700;">LUNAS</span>
                @else
                    <span style="color:#ca8a04; font-weight:700;">{{ strtoupper($payment->status_transaksi) }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-label">Dibayar Melalui</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ strtoupper($payment->jenis_pembayaran ?? '-') }}</td>
        </tr>
    </table>

    <!-- DETAIL PEMBAYARAN -->
    <div style="font-weight:700; margin-bottom:8px;">Detail Pembayaran:</div>

    @if(in_array($payment->status_transaksi,['settlement','capture']))
        <div class="alert-box bg-green">
            <div style="font-weight:700; margin-bottom:8px;">✔ TAGIHAN SUDAH DIBAYAR</div>
            <table width="100%" role="presentation" style="font-size:13px;">
                <tr>
                    <td style="width:28%; font-weight:700;">Waktu Pembayaran</td>
                    <td style="width:4%;">:</td>
                    <td>{{ \Carbon\Carbon::parse($payment->updated_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td style="font-weight:700;">Melalui Bank</td>
                    <td>:</td>
                    <td>{{ strtoupper($payment->bank ?? 'QRIS/E-Wallet') }}</td>
                </tr>
            </table>
        </div>
    @else
        <div class="alert-box bg-yellow" style="background:#fee2e2; border-color:#f87171;">
            <div style="font-weight:700; margin-bottom:6px;">⚠ MENUNGGU PEMBAYARAN</div>
            Harap segera lakukan pembayaran sebelum jatuh tempo.
        </div>
    @endif

    <!-- TAGIHAN -->
    <div class="bill-title">TAGIHAN</div>

    <table class="bill-table" role="presentation">
        <thead>
            <tr>
                <th class="bill-col-1">TOTAL TAGIHAN</th>
                <th class="bill-col-2">SUDAH DIBAYAR</th>
                <th class="bill-col-3">BELUM DIBAYAR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rp {{ number_format($payment->amount,0,',','.') }}</td>
                <td>
                    @if(in_array($payment->status_transaksi,['settlement','capture']))
                        Rp {{ number_format($payment->amount,0,',','.') }}
                    @else
                        Rp 0
                    @endif
                </td>
                <td>
                    @if(in_array($payment->status_transaksi,['settlement','capture']))
                        Rp 0
                    @else
                        Rp {{ number_format($payment->amount,0,',','.') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- VA -->
    <div class="va-label">Nomor Virtual Account (VA) :</div>
    <div class="va-box">
        @if(isset($payment->va_number))
            <div style="font-size:18px; font-weight:700; letter-spacing:1px;">{{ $payment->va_number }}</div>
            <div style="font-size:12px; margin-top:6px;">Bank: {{ strtoupper($payment->bank ?? '') }}</div>
        @else
            <div style="font-style:italic;">Nomor VA akan muncul setelah metode pembayaran dipilih.</div>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="footer-note">
        <strong style="color:#000;">Catatan:</strong><br>
        Simpan dokumen ini sebagai bukti pembayaran yang sah.
    </div>

</div>

</div>

</body>
</html>
