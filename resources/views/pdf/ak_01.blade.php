<!DOCTYPE html>
<html>
<head>
    <title>FR.AK.01 - Persetujuan Asesmen dan Kerahasiaan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 14px; text-align: center; }
        
        /* Box Persetujuan */
        .agreement-box { border: 1px solid #ccc; background-color: #f9f9f9; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="header">
        FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN
    </div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="150"><strong>TUK</strong></td>
            <td width="10">:</td>
            <td>
                @php 
                    $tuk = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu'; 
                @endphp
                [{!! $tuk == 'Sewaktu' ? 'V' : '&nbsp;&nbsp;' !!}] Sewaktu &nbsp;&nbsp;
                [{!! $tuk == 'Tempat Kerja' ? 'V' : '&nbsp;&nbsp;' !!}] Tempat Kerja &nbsp;&nbsp;
                [{!! $tuk == 'Mandiri' ? 'V' : '&nbsp;&nbsp;' !!}] Mandiri
            </td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
        </tr>
    </table>

    <hr>

    <div style="margin-bottom: 5px; font-weight: bold; margin-top: 10px;">Bukti yang akan dikumpulkan:</div>
    <table class="no-border">
        <tr>
            <td>
                @foreach($masterBukti as $bukti)
                    @php
                        // Cek apakah bukti ini ada di respon bukti yang dipilih
                        $isChecked = in_array($bukti->id_bukti_ak01, $responBukti);
                    @endphp
                    <div style="margin-bottom: 3px;">
                        <span class="check-mark">[{!! $isChecked ? 'V' : '&nbsp;&nbsp;' !!}]</span> {{ $bukti->bukti }}
                    </div>
                @endforeach
            </td>
        </tr>
    </table>

    <div class="agreement-box">
        <div style="font-weight: bold; margin-bottom: 5px; text-decoration: underline;">Persetujuan Asesmen dan Kerahasiaan:</div>
        <ol style="padding-left: 20px; margin: 0;">
            <li style="margin-bottom: 5px;">Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.</li>
            <li style="margin-bottom: 5px;">Saya menyatakan bahwa saya telah mendapatkan penjelasan mengenai Hak dan Prosedur Banding oleh Asesor.</li>
            <li style="margin-bottom: 5px;">Saya bersedia mengikuti asesmen sesuai dengan jadwal yang telah ditetapkan.</li>
        </ol>
    </div>

    <br><br>
    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                Asesi,
                <br><br><br><br>
                @if($sertifikasi->asesi && $sertifikasi->asesi->tanda_tangan)
                    <img src="{{ getTtdBase64($sertifikasi->asesi->tanda_tangan) }}" style="width: 100px; height: auto;">
                    <br>
                @else
                    <br><br><br><br>
                @endif
                <strong>{{ $sertifikasi->asesi->nama_lengkap ?? '(.......................)' }}</strong>
                <br>Tanggal: {{ date('d-m-Y') }}
            </td>
            <td style="width: 50%; text-align: center;">
                Asesor Kompetensi,
                <br><br><br><br>
                @if($sertifikasi->jadwal->asesor && $sertifikasi->jadwal->asesor->tanda_tangan)
                    <img src="{{ getTtdBase64($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="width: 100px; height: auto;">
                    <br>
                @else
                    <br><br><br><br>
                @endif
                <strong>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '(.......................)' }}</strong>
                <br>No. Reg: -
            </td>
        </tr>
    </table>

</body>
</html>