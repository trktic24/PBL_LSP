<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hadir - {{ $jadwal->kode_jadwal }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header h3 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .info-table {
            border: none;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            border: none;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR PESERTA</h2>
        <h3>LSP - LEMBAGA SERTIFIKASI PROFESI</h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%">Kode Jadwal</td>
            <td width="80%">: {{ $jadwal->kode_jadwal }}</td>
        </tr>
        <tr>
            <td>Skema</td>
            <td>: {{ $jadwal->skema->nama_skema }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td>: {{ $jadwal->masterTuk->nama_tuk }} ({{ $jadwal->jenisTuk->nama_jenis_tuk }})</td>
        </tr>
        <tr>
            <td>Asesor</td>
            <td>: {{ $jadwal->asesor->nama_asesor }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Peserta</th>
                <th width="20%">No. HP</th>
                <th width="20%">Institusi</th>
                <th width="15%">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->asesi->nama_lengkap }}</td>
                <td>{{ $item->asesi->nomor_hp }}</td>
                <td>{{ $item->asesi->dataPekerjaan->nama_institusi_pekerjaan ?? '-' }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>................., {{ date('d-m-Y') }}</p>
        <br><br><br>
        <p>( {{ $jadwal->asesor->nama_asesor }} )</p>
        <p>Asesor Kompetensi</p>
    </div>
</body>
</html>
