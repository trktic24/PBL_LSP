<!DOCTYPE html>
<html>

<head>
    <title>FR.APL.01</title>
    <style>
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

        .section-title {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .subsection-title {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 15px;
            margin-bottom: 10px;
            margin-left: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .bordered-table {
            border: 1px solid #000;
            margin-top: 10px;
        }

        .bordered-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }

        .bordered-table thead td {
            font-weight: bold;
            text-align: center;
            background-color: #e2e2e2;
        }

        .rekomendasi-box {
            margin-top: 10px;
            padding: 10px 0;
            text-align: center;
        }

        .status-diterima {
            font-weight: bold;
            font-size: 12pt;
            color: #0066cc;
        }

        .status-tidak-diterima {
            font-weight: bold;
            font-size: 12pt;
            color: #dc3545;
        }

        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .signature-left,
        {
        display: table-cell;
        width: 50%;
        text-align: left;
        vertical-align: top;
        padding: 5px;
        margin-left: 20px;
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
    </style>
</head>

<body>

    <!-- HEADER dengan Logo -->
    <div class="header">
        <div class="header-left">
            <!-- Gunakan Base64 untuk Logo BNSP -->
            @if ($logoBnspBase64)
                <img src="data:image/png;base64,{{ $logoBnspBase64 }}" alt="BNSP" class="logo-bnsp">
            @endif
        </div>
        <div class="header-right">
            <!-- Gunakan Base64 untuk Logo LSP -->
            @if ($logoLspBase64)
                <img src="data:image/png;base64,{{ $logoLspBase64 }}" alt="LSP" class="logo-lsp">
            @endif
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-section">
        <h1>PERMOHONAN SERTIFIKASI KOMPETENSI</h1>
        <h2>FR.APL.01</h2>
    </div>

    <!-- BAGIAN A: RINCIAN DATA PEMOHON -->
    <div class="section-title">A. Rincian Data Pemohon Sertifikasi</div>
    <p style="margin-left: 20px; margin-top: 5px;">
        Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan Anda pada saat ini.
    </p>

    <div class="subsection-title">a. Data Pribadi</div>
    <table class="info-table">
        <tr>
            <td>Nama Lengkap</td>
            <td>:</td>
            <td>{{ $asesi->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $asesi->nik }}</td>
        </tr>
        <tr>
            <td>Tempat/Tgl. Lahir</td>
            <td>:</td>
            <td>{{ $asesi->tempat_lahir }}, {{ \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $asesi->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Kebangsaan</td>
            <td>:</td>
            <td>{{ $asesi->kebangsaan }}</td>
        </tr>
        <tr>
            <td>Alamat Rumah</td>
            <td>:</td>
            <td>{{ $asesi->alamat_rumah }}</td>
        </tr>
        <tr>
            <td>Kode pos</td>
            <td>:</td>
            <td>{{ $asesi->kode_pos }}</td>
        </tr>
        <tr>
            <td>No. Telepon</td>
            <td>:</td>
            <td>{{ $asesi->nomor_hp }}</td>
        </tr>
        <tr>
            <td>Kualifikasi Pendidikan</td>
            <td>:</td>
            <td>{{ $asesi->pendidikan }}</td>
        </tr>
    </table>

    <div class="subsection-title">b. Data Pekerjaan Sekarang</div>
    <table class="info-table">
        <tr>
            <td>Nama Perusahaan</td>
            <td>:</td>
            <td>{{ $pekerjaan->nama_institusi_pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $pekerjaan->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Kantor</td>
            <td>:</td>
            <td>{{ $pekerjaan->alamat_institusi ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. Telepon</td>
            <td>:</td>
            <td>{{ $pekerjaan->no_telepon_institusi ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kode Pos</td>
            <td>:</td>
            <td>{{ $pekerjaan->kode_pos_institusi ?? '-' }}</td>
        </tr>
    </table>

    <!-- BAGIAN B: DATA SERTIFIKASI -->
    <div class="section-title" style="margin-top: 30px;">B. Data Sertifikasi</div>
    <p style="margin-left: 20px; margin-top: 5px;">
        Berikut Judul dan Nomor Skema Sertifikasi yang Anda ajukan serta Unit Kompetensi sesuai kemasan pada skema
        sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman
        kerja yang anda miliki.
    </p>

    <table class="bordered-table">
        <thead>
            <tr>
                <td colspan="2" style="text-align: center;">Skema Sertifikasi</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 25%; font-weight: bold;">Judul</td>
                <td>{{ $skema->nama_skema ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Nomor</td>
                <td>{{ $skema->nomor_skema ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tujuan Asesmen</td>
                <td>
                    @if ($sertifikasi->tujuan_asesmen == 'Sertifikasi')
                        Sertifikasi
                    @elseif($sertifikasi->tujuan_asesmen == 'PKT')
                        Pengakuan Kompetensi Terkini (PKT)
                    @elseif($sertifikasi->tujuan_asesmen == 'RPL')
                        Rekognisi Pembelajaran Lampau (RPL)
                    @elseif($sertifikasi->tujuan_asesmen_lainnya)
                        Lainnya: {{ $sertifikasi->tujuan_asesmen_lainnya }}
                    @else
                        {{ $sertifikasi->tujuan_asesmen }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="subsection-title">Daftar Unit Kompetensi</div>

    <table class="bordered-table">
        <thead>
            <tr>
                <td style="width: 8%;">No.</td>
                <td style="width: 25%;">Kode Unit</td>
                <td style="width: 47%;">Judul Unit</td>
                <td style="width: 20%;">Standar Kompetensi Kerja</td>
            </tr>
        </thead>
        <tbody>
            @forelse ($skema->unitKompetensi as $index => $unit)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $unit->kode_unit }}</td>
                    <td>{{ $unit->judul_unit }}</td>
                    <td style="text-align: center;">{{ $unit->jenis_standar ?? 'SKKNI' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Unit Kompetensi tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- BAGIAN C: BUKTI KELENGKAPAN -->
    <div class="section-title" style="margin-top: 30px;">C. Bukti Kelengkapan Pemohon</div>

    <div class="subsection-title">a. Bukti Persyaratan Dasar Pemohon</div>

    <table class="bordered-table">
        <thead>
            <tr>
                <td rowspan="2" style="width: 8%; text-align: center;">No.</td>
                <td rowspan="2" style="width: 52%; text-align: center;">Bukti Persyaratan Dasar</td>
                <td colspan="2" style="width: 30%; text-align: center;">Ada</td>
                <td rowspan="2" style="width: 10%; text-align: center;">Tidak Ada</td>
            </tr>
            <tr>
                <td style="text-align: center; width: 15%;">Memenuhi Syarat</td>
                <td style="text-align: center; width: 15%;">Tidak Memenuhi Syarat</td>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($sertifikasi->buktiDasar as $bukti)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td>{{ $bukti->bukti_dasar }}</td>
                    <td style="text-align: center;">
                        @if (strtoupper($bukti->status_kelengkapan) == 'M' || strtoupper($bukti->status_kelengkapan) == 'MEMENUHI')
                            <img src="{{ public_path('images/bukti_dasar/checkmark.jpg') }}" width="14">
                        @endif
                    </td>
                    </td>
                    <td style="text-align:center;">
                        @if ($bukti->status_kelengkapan == 'tidak_memenuhi')
                            <strong style="color:red;">X</strong> {{-- Menggunakan X sesuai gambar output --}}
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($bukti->status_kelengkapan == 'tidak_ada')
                            <strong style="color:black;">-</strong> {{-- Menggunakan - sesuai gambar output --}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- BAGIAN D: REKOMENDASI -->
    <div class="section-title" style="margin-top: 30px;">D. Rekomendasi</div>
    <p style="margin-left: 20px; margin-top: 5px;">
        Berdasarkan ketentuan persyaratan dasar, maka pemohon
        <span class="rekomendasi-box">
            @if (isset($sertifikasi->status_rekomendasi) && $sertifikasi->status_rekomendasi == 'diterima')
                <span class="status-diterima">Diterima</span>
            @elseif(isset($sertifikasi->status_rekomendasi) && $sertifikasi->status_rekomendasi == 'tidak_diterima')
                <span class="status-tidak-diterima">Tidak Diterima</span>
            @else
                <span class="status-diterima">Diterima</span>
            @endif
        </span>
        sebagai peserta sertifikasi
    </p>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-title">Pemohon</div>
            <div class="signature-space">
                @if ($asesi->tanda_tangan)
                    <img src="{{ public_path($asesi->tanda_tangan) }}"
                        style="max-width: 120px; max-height: 70px; margin-top: 5px;">
                @endif
            </div>
            <div class="signature-name">
                ({{ $asesi->nama_lengkap }})
            </div>
        </div>
        <div class="signature-right">
            <div class="signature-title">Lembaga Sertifikasi Profesi</div>
            <div class="signature-space">
                @if ($ttdBase64)
                    <img src="data:image/png;base64,{{ $ttdBase64 }}" alt="Tanda Tangan Admin"
                        style="max-width: 120px; max-height: 70px; margin-top: 5px;">
                @endif
            </div>
            <div class="signature-name">
                ({{ $admin->nama_admin ?? 'nama admin disini' }})
            </div>
        </div>
    </div>

</body>

</html>
