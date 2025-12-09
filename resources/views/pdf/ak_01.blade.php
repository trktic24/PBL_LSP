<!DOCTYPE html>
<html>
<head>
    <title>FR.AK.01 - Cetak PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { vertical-align: top; padding: 5px; }
        .border-table td, .border-table th { border: 1px solid black; }
        .checkbox { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 10px; font-size: 10px; }
        .checked { background-color: #000; color: #fff; } /* Hitam jika dicentang */
        .signature-box { height: 80px; border-bottom: 1px solid black; width: 200px; margin-top: 40px; }
    </style>
</head>
<body>

    <div class="header">
        FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN
    </div>

    <table>
        <tr>
            <td width="25%"><strong>TUK</strong></td>
            <td>
                <span class="checkbox">{{ $data->tuk == 'Sewaktu' ? 'X' : '' }}</span> Sewaktu
                <span class="checkbox" style="margin-left: 15px;">{{ $data->tuk == 'Tempat Kerja' ? 'X' : '' }}</span> Tempat Kerja
                <span class="checkbox" style="margin-left: 15px;">{{ $data->tuk == 'Mandiri' ? 'X' : '' }}</span> Mandiri
            </td>
        </tr>
        
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>: {{ $data->nama_asesor ?? '...........................' }}</td>
        </tr>

        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>: {{ $data->nama_asesi ?? 'Devi Ibnu Nabila' }}</td>
        </tr>
    </table>

    <br>

    <table class="border-table">
        <tr style="background-color: #f0f0f0;">
            <th style="text-align: left;">Bukti yang akan dikumpulkan:</th>
            <th width="10%" style="text-align: center;">Cek</th>
        </tr>
        {{-- Loop data bukti dari tabel respon_bukti_ak01 --}}
        @foreach($bukti_list as $bukti)
        <tr>
            <td>{{ $bukti->nama_bukti }}</td> <td style="text-align: center;">
                {{ $bukti->respon == 1 ? 'X' : '' }}
            </td>
        </tr>
        @endforeach
    </table>

    <br>

    <div style="border: 1px solid #ccc; padding: 10px;">
        <strong>Persetujuan dan Kerahasiaan:</strong>
        <p>
            Bahwa saya sudah mendapatkan penjelasan Hak dan Prosedur Banding oleh Asesor. 
            Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan 
            hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
        </p>
    </div>

    <table style="margin-top: 30px;">
        <tr>
            <td width="50%"></td>
            <td align="center">
                Semarang, {{ date('d-m-Y') }}<br>
                Tanda Tangan Peserta,
                <br><br>
                {{-- Jika ada gambar TTD di database, gunakan tag img --}}
                {{-- <img src="{{ public_path('storage/'.$data->ttd_path) }}" height="60"> --}}
                
                <div style="height: 60px;"></div> <hr style="width: 70%; border-top: 1px solid #000;">
                <strong>{{ $data->nama_asesi }}</strong>
            </td>
        </tr>
    </table>

</body>
</html>