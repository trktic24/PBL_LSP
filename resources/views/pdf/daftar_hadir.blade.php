<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        .ttd-box {
            width: 100px;
            height: 60px;
            border: none;
            overflow: hidden;
        }
        .text-center {
            text-align: center;
        }
        .small {
            font-size: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="images/Logo_LSP_No_BG.png" alt="Logo LSP" class="ttd-box">
    <h2>DAFTAR HADIR PESERTA</h2>
</div>

<div class="mb-4 text-sm text-gray-700">
    <p><span class="font-semibold">Skema:</span> {{ $jadwal->skema->nama_skema }}</p>
    <p><span class="font-semibold">Tanggal Pelaksanaan:</span> {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</p>
    <p><span class="font-semibold">Tempat Uji Kompetensi:</span> {{ $jadwal->masterTuk->nama_lokasi }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 30px">No</th>
            <th>Nama Peserta</th>
            <th>Institusi / Perusahaan</th>
            <th>Alamat</th>
            <th>Pekerjaan</th>
            <th>No. HP</th>
            <th class="text-center">Status</th>
            <th class="text-center">Tanda Tangan</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($pendaftar as $i => $data)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $data->asesi->nama_lengkap }}</td>
            <td>{{ $data->asesi->pekerjaan->nama_institusi_pekerjaan ?? '-' }}</td>
            <td>{{ $data->asesi->alamat_rumah }}</td>
            <td>{{ $data->asesi->pekerjaan }}</td>
            <td>{{ $data->asesi->nomor_hp }}</td>

            <td class="text-center">
                @if ($data->presensi && $data->presensi->hadir == 1)
                    Hadir
                @else
                    Tidak Hadir
                @endif
            </td>

            <td class="text-center">
                @if ($data->presensi && $data->presensi->hadir == 1)
                    <div class="ttd-box">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('private_docs')->path($data->asesi->tanda_tangan) }}" style="width: 100%; height: 100%; object-fit: contain;" />
                    </div>
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<br><br>
<table style="border: none; width: 100%; margin-top: 40px;">
    <tr>
        <td style="border: none; width: 70%;"></td>
        <td style="border: none; text-align: center;">
            <p>Asesor,</p>
<br>
<div class="ttd-box" style="margin: 0 auto;">
    @if($jadwal->asesor && $jadwal->asesor->tanda_tangan)
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('private_docs')->path($jadwal->asesor->tanda_tangan) }}" style="width: 100%; height: 100%; object-fit: contain;" />
    @endif
</div>
<br>
            <p><strong>{{ $jadwal->asesor->nama_lengkap ?? '-' }}</strong></p>
        </td>
    </tr>
</table>

</body>
</html>