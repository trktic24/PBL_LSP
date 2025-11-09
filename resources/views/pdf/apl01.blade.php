<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.APL.01 - Permohonan Sertifikasi Kompetensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            padding: 15mm;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
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

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 10px 0;
        }

        .subtitle {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            font-size: 10pt;
        }

        .subsection-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 8px;
            font-size: 10pt;
            margin-left: 15px;
        }

        .info-text {
            margin-bottom: 10px;
            font-size: 9pt;
            line-height: 1.4;
        }

        .form-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .form-label {
            display: table-cell;
            width: 180px;
            padding-left: 30px;
            vertical-align: top;
        }

        .form-separator {
            display: table-cell;
            width: 15px;
            text-align: center;
            vertical-align: top;
        }

        .form-value {
            display: table-cell;
            border-bottom: 1px dotted #666;
            padding-left: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            font-size: 9pt;
        }

        th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }

        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
        }

        .checkbox.checked {
            background-color: #000;
        }

        .checkbox.checked::after {
            content: '✓';
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            display: block;
            text-align: center;
            line-height: 10px;
        }

        .signature-section {
            margin-top: 25px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
        }

        .signature-img {
            margin: 15px auto;
            height: 60px;
            border: 1px solid #ccc;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <strong>BNSP</strong><br>
            <small>BADAN NASIONAL SERTIFIKASI PROFESI</small>
        </div>
        <div class="header-right">
            <img src="{{ public_path('images/Logo_LSP_No_BG.png') }}" alt="LSP POLINES" style="height: 50px;"><br>
            <strong>LSP POLINES</strong>
        </div>
    </div>

    <div class="title">PERMOHONAN SERTIFIKASI KOMPETENSI</div>
    <div class="subtitle">FR.APL.01</div>

    <hr>

    <!-- BAGIAN A: RINCIAN DATA PEMOHON -->
    <div class="section-title">A. Rincian Data Pemohon Sertifikasi</div>
    <p class="info-text">
        Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan Anda pada saat ini.
    </p>

    <!-- a. Data Pribadi -->
    <div class="subsection-title">a. Data Pribadi</div>

    <div class="form-row">
        <div class="form-label">Nama Lengkap</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->nama_lengkap ?? '' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">NIK</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->nik ?? '' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">Tempat / tgl. Lahir</div>
        <div class="form-separator">:</div>
        <div class="form-value">
            {{ $asesi->tempat_lahir ?? '' }} /
            {{ $asesi->tanggal_lahir ? \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d-m-Y') : '' }}
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">Jenis Kelamin</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">Kebangsaan</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->kebangsaan ?? 'Indonesia' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">Alamat Rumah</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->alamat_rumah ?? '' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">Kode pos</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->kode_pos ?? '' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">No. Telepon/E-mail</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->nomor_hp ?? '' }} / {{ $asesi->user->email ?? '' }}</div>
    </div>

    <div class="form-row">
        <div class="form-label">Kualifikasi Pendidikan</div>
        <div class="form-separator">:</div>
        <div class="form-value">{{ $asesi->pendidikan ?? '' }}</div>
    </div>

    <!-- b. Data Pekerjaan Sekarang -->
    <div class="subsection-title">b. Data Pekerjaan Sekarang</div>

    @if ($dataPekerjaan)
        <div class="form-row">
            <div class="form-label">Nama Institusi</div>
            <div class="form-separator">:</div>
            <div class="form-value">{{ $dataPekerjaan->nama_perusahaan ?? '' }}</div>
        </div>

        <div class="form-row">
            <div class="form-label">Jabatan</div>
            <div class="form-separator">:</div>
            <div class="form-value">{{ $dataPekerjaan->jabatan ?? '' }}</div>
        </div>

        <div class="form-row">
            <div class="form-label">Alamat Kantor</div>
            <div class="form-separator">:</div>
            <div class="form-value">{{ $dataPekerjaan->alamat_kantor ?? '' }}</div>
        </div>

        <div class="form-row">
            <div class="form-label">No. Telepon/Fax/Email</div>
            <div class="form-separator">:</div>
            <div class="form-value">{{ $dataPekerjaan->no_telp_kantor ?? '' }} /
                {{ $dataPekerjaan->email_kantor ?? '' }}</div>
        </div>
    @endif

    <!-- BAGIAN B: DATA SERTIFIKASI -->
    <div class="section-title" style="margin-top: 20px;">B. Data Sertifikasi</div>
    <p class="info-text">
        Berikut Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada
        skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta
        pengalaman kerja yang anda miliki.
    </p>

    @if ($dataSertifikasi)
        <table style="margin-bottom: 10px;">
            <tr>
                <th style="text-align: left; width: 200px;">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</th>
                <th style="text-align: left;">Judul</th>
                <td style="border: none; width: 15px;">:</td>
                <td>{{ $dataSertifikasi->jadwal->masterSkema->nama_skema ?? '' }}</td>
            </tr>
            <tr>
                <th style="text-align: left;"></th>
                <th style="text-align: left;">Nomor</th>
                <td style="border: none;">:</td>
                <td>{{ $dataSertifikasi->jadwal->masterSkema->nomor_skema ?? '' }}</td>
            </tr>
        </table>

        <div class="form-row" style="margin-bottom: 10px;">
            <div class="form-label">Tujuan Asesmen</div>
            <div class="form-separator">:</div>
            <div class="form-value">
                <span class="checkbox {{ strtolower($tujuanAsesmen ?? '') == 'sertifikasi' ? 'checked' : '' }}"></span>
                Sertifikasi<br>
                <span class="checkbox {{ strtolower($tujuanAsesmen ?? '') == 'pkt' ? 'checked' : '' }}"></span>
                Pengakuan Kompetensi Terkini (PKT)<br>
                <span
                    class="checkbox {{ strtolower($tujuanAsesmen ?? '') == 'rekognisi pembelajaran lampau' ? 'checked' : '' }}"></span>
                Rekognisi Pembelajaran Lampau<br>
                <span class="checkbox {{ strtolower($tujuanAsesmen ?? '') == 'lainnya' ? 'checked' : '' }}"></span>
                Lainnya
            </div>
        </div>
    @endif

    <!-- Daftar Unit Kompetensi -->
    <p style="margin-top: 15px; margin-bottom: 5px;"><strong>Daftar Unit Kompetensi sesuai kemasan</strong></p>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 100px;">Kode Unit</th>
                <th>Judul Unit</th>
                <th style="width: 100px;">Standar Kompetensi Kerja</th>
            </tr>
        </thead>
        <tbody>
            @if (is_countable($unitKompetensi) && count($unitKompetensi) > 0)
                @foreach ($unitKompetensi as $index => $unit)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $unit->kode_unit ?? '' }}</td>
                        <td>{{ $unit->nama_unit ?? '' }}</td>
                        <td style="text-align: center;">{{ $unit->standar_kompetensi_kerja ?? 'SKKNI' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">Belum ada unit kompetensi</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- BAGIAN C: BUKTI KELENGKAPAN -->
    <div class="section-title" style="margin-top: 20px;">C. Bukti Kelengkapan Pemohon</div>

    <p class="subsection-title">a. Bukti Persyaratan Dasar Pemohon</p>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Bukti Persyaratan Dasar</th>
                <th style="width: 80px;">Memenuhi Syarat</th>
                <th style="width: 100px;">Tidak Memenuhi Syarat</th>
                <th style="width: 70px;">Tidak Ada</th>
            </tr>
        </thead>
        <tbody>
            @php
                $persyaratanDasar = ['KTP/Identitas', 'Ijazah Pendidikan', 'Sertifikat Pelatihan'];
            @endphp
            @foreach ($persyaratanDasar as $index => $nama)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $nama }}</td>
                    <td style="text-align: center;"><span class="checkbox"></span></td>
                    <td style="text-align: center;"><span class="checkbox"></span></td>
                    <td style="text-align: center;"><span class="checkbox"></span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="subsection-title">b. Bukti Administratif</p>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Bukti Administratif</th>
                <th style="width: 80px;">Memenuhi Syarat</th>
                <th style="width: 100px;">Tidak Memenuhi Syarat</th>
                <th style="width: 70px;">Tidak Ada</th>
            </tr>
        </thead>
        <tbody>
            @php
                $administratif = ['Formulir Permohonan', 'Pas Foto', 'Bukti Pembayaran'];
            @endphp
            @foreach ($administratif as $index => $nama)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $nama }}</td>
                    <td style="text-align: center;"><span class="checkbox"></span></td>
                    <td style="text-align: center;"><span class="checkbox"></span></td>
                    <td style="text-align: center;"><span class="checkbox"></span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- BAGIAN D: REKOMENDASI -->
    <div class="section-title" style="margin-top: 20px;">D. Rekomendasi</div>
    <p class="info-text">
        Berdasarkan ketentuan persyaratan dasar, maka pemohon <strong>Diterima/Tidak diterima</strong> sebagai peserta
        sertifikasi
    </p>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-box">
            <strong>Pemohon,</strong><br>
            <small>(nama asesi)</small>
            <div class="signature-img">
                @if ($fullPathTandaTangan)
                    <img src="{{ $fullPathTandaTangan }}" alt="TTD" style="max-height: 60px;">
                @endif
            </div>
            <strong>(tanda tangan)</strong>
            <div style="margin-top: 10px;">
                <strong>{{ $asesi->nama_lengkap ?? '' }}</strong>
            </div>
        </div>

        <div class="signature-box">
            <strong>Lembaga Sertifikasi Profesi</strong><br>
            <small>(nama admin)</small>
            <div class="signature-img"></div>
            <strong>(tanda tangan)</strong>
            <div style="margin-top: 10px;">
                <strong>_______________________</strong>
            </div>
        </div>
    </div>

    <p style="margin-top: 20px; text-align: center; font-size: 8pt; color: #666;">
        Dokumen dicetak otomatis pada {{ now()->format('d F Y, H:i') }} WIB
    </p>
</body>

</html>
