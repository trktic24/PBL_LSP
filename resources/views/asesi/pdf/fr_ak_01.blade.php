<!DOCTYPE html>
<html>
<head>
    <title>FR.AK.01 Persetujuan Asesmen</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.3; }
        .header { font-weight: bold; font-size: 13px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .no-border { border: none !important; }
        .bg-grey { background-color: #f0f0f0; }
        .text-bold { font-weight: bold; }
        .checkbox { display: inline-block; width: 10px; height: 10px; border: 1px solid black; margin-right: 5px; }
        .checked { background-color: black; }
    </style>
</head>
<body>

    <div class="header">FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN</div>

    <div style="margin-bottom: 10px;">
        Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen.
    </div>

    <table style="border: 2px solid black;">
        <tr>
            <td width="25%" class="bg-grey text-bold">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td width="75%">
                Judul : {{ $skema->nama_skema }}<br>
                Nomor : {{ $skema->nomor_skema }}
            </td>
        </tr>
        <tr>
            <td class="bg-grey text-bold">TUK</td>
            <td>{{ $tuk->nama_tuk ?? 'TUK Sewaktu/Tempat Kerja/Mandiri' }}</td>
        </tr>
        <tr>
            <td class="bg-grey text-bold">Nama Asesor</td>
            <td>{{ $asesor->nama_asesor ?? '-' }}</td>
        </tr>
        <tr>
            <td class="bg-grey text-bold">Nama Asesi</td>
            <td>{{ $asesi->nama_lengkap }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td rowspan="3" width="25%" class="bg-grey text-bold">Bukti yang akan dikumpulkan</td>
            <td><div class="checkbox"></div> Hasil Verifikasi Portofolio</td>
            <td><div class="checkbox"></div> Hasil Observasi Langsung</td>
            <td><div class="checkbox"></div> Hasil Kegiatan Terstruktur</td>
        </tr>
        <tr>
            <td><div class="checkbox"></div> Hasil Reviu Produk</td>
            <td><div class="checkbox"></div> Hasil Tanya Jawab</td>
            <td><div class="checkbox"></div> Lainnya: ____________</td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="checkbox"></div> Daftar Pertanyaan Tertulis &nbsp;&nbsp;
                <div class="checkbox"></div> Daftar Pertanyaan Lisan &nbsp;&nbsp;
                <div class="checkbox"></div> Daftar Pertanyaan Wawancara
            </td>
        </tr>
    </table>

    <table style="border: 2px solid black;">
        <tr>
            <td colspan="2" class="bg-grey text-bold">Pelaksanaan asesmen disepakati pada:</td>
        </tr>
        <tr>
            <td width="25%" class="bg-grey">Hari / Tanggal</td>
            <td>{{ $hari }}, {{ $tanggal }}</td>
        </tr>
        <tr>
            <td class="bg-grey">Waktu</td>
            <td>{{ $jam }} WIB</td>
        </tr>
        <tr>
            <td class="bg-grey">TUK</td>
            <td>{{ $tuk->nama_tuk ?? 'Lokasi TUK' }}</td>
        </tr>
    </table>

    <table style="border: 2px solid black;">
        <tr>
            <td colspan="2" class="bg-grey text-bold">Pernyataan:</td>
        </tr>
        <tr>
            <td width="100%" colspan="2">
                <strong>Asesi:</strong><br>
                Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Asesor:</strong><br>
                Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Asesi:</strong><br>
                Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
            </td>
        </tr>
    </table>

    <br>
    <table class="no-border">
        <tr class="no-border">
            <td width="50%" class="no-border">
                Tanda tangan Asesi:<br><br><br><br>
                <strong>( {{ $asesi->nama_lengkap }} )</strong><br>
                Tanggal: {{ $tanggal }}
            </td>
            <td width="50%" class="no-border">
                Tanda tangan Asesor:<br><br><br><br>
                <strong>( {{ $asesor->nama_asesor ?? '........................' }} )</strong><br>
                Tanggal: {{ $tanggal }}
            </td>
        </tr>
    </table>

</body>
</html>